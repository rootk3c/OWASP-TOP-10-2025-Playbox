from flask import Flask, request, jsonify, render_template, redirect, url_for, session, g,send_from_directory, Response
import sqlite3
import os

app = Flask(__name__)
app.secret_key = os.urandom(24)  # Secure secret key for sessions

DATABASE = 'hospital.db'

def get_db():
    conn = sqlite3.connect(DATABASE)
    conn.row_factory = sqlite3.Row
    return conn

# --- Authentication Decorator ---
def login_required(f):
    def wrapper(*args, **kwargs):
        if 'user_id' not in session:
            return redirect(url_for('login'))
        return f(*args, **kwargs)
    wrapper.__name__ = f.__name__
    return wrapper

# --- Routes ---

@app.route('/robots.txt')
def robots():
    content = """User-agent: *
Disallow: /static/debug.log
"""
    return Response(content, mimetype="text/plain")

@app.route('/')
def index():
    if 'user_id' in session:
        return redirect(url_for('dashboard'))
    return redirect(url_for('login'))

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']
        
        conn = get_db()
        user = conn.execute('SELECT * FROM users WHERE email = ? AND password = ?', (email, password)).fetchone()
        conn.close()
        
        if user:
            session['user_id'] = user['id']
            session['name'] = user['name']
            return redirect(url_for('dashboard'))
        else:
            return render_template('login.html', error="Invalid NHS credentials.")
            
    return render_template('login.html')

@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('login'))

@app.route('/dashboard')
@login_required
def dashboard():
    return render_template('dashboard.html', name=session['name'], user_id=session['user_id'])

# --- THE VULNERABLE ENDPOINT ---
# Simulates Microsoft Power Pages OData Feed (_api/contacts)
@app.route('/_api/patients', methods=['GET'])
@login_required
def api_patients():
    # The frontend sends a filter for the CURRENT user.
    # Example: /_api/patients?filter=id eq '101'
    
    filter_param = request.args.get('filter')
    conn = get_db()
    
    try:
        if filter_param:
            # VULNERABLE LOGIC:
            # We trust the client to filter the data.
            # If the client says "give me id 101", we give 101.
            # If the client says "give me everything" (no filter), or manipulates it...
            
            # Simple simulation of OData "eq" filter parsing
            if "id eq" in filter_param:
                # Extract ID safely-ish (for a CTF)
                target_id = filter_param.split("'")[1]
                query = f"SELECT id, name, nhs_number, condition, notes FROM patients WHERE id = '{target_id}'"
            else:
                # If they send a weird filter, default to empty or error (to encourage them to remove it)
                return jsonify({"error": "Unsupported filter operation"}), 400
        else:
            # CRITICAL VULNERABILITY:
            # If NO filter is provided, the API defaults to returning ALL records.
            # This mimics the "Global Read" permission in Power Pages.
            query = "SELECT id, name, nhs_number, condition, notes FROM patients"

        cursor = conn.execute(query)
        rows = cursor.fetchall()
        
        results = [dict(row) for row in rows]
        return jsonify({"value": results})
        
    except Exception as e:
        return jsonify({"error": "Internal API Error"}), 500
    finally:
        conn.close()

if __name__ == '__main__':
    # Threaded=False reduces memory footprint
    app.run(host='0.0.0.0', port=5000, threaded=False)

    