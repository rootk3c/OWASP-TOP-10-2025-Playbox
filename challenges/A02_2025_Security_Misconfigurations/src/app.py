import uuid
from flask import Flask, render_template, request, redirect, url_for, session, jsonify, abort

app = Flask(__name__)
app.secret_key = 'medinexus_secure_session_key_change_in_prod'

# --- MOCK DATABASE (Recreated in RAM on startup) ---
USERS_DB = [
    {
        "contactid": "guest-user-001",
        "firstname": "Guest",
        "lastname": "User",
        "email": "guest",
        "password": "guest", 
        "role": "Visitor",
        "jobtitle": "Prospective Patient",
        "note" : "just a guest user"
    },
    {
        "contactid": "a1-b2-c3-001",
        "firstname": "Sarah",
        "lastname": "Connor",
        "email": "sarah.connor@test.local",
        "password": "password123", # Stronger password, not given to user anymore
        "role": "Patient",
        "jobtitle": "Patient",
        "note" : "The doctor appointment is at 2."
    },
    {
        "contactid": "a1-b2-c3-999",
        "firstname": "SYSTEM",
        "lastname": "ADMINISTRATOR",
        "email": "admin@medinexus.local",
        "password": "REDACTED_HASH_9912",
        "role": "Admin",
        "mobilephone": "+1-555-7199",
        "jobtitle": "IT Operations Lead",
        "specialty": "Infrastructure",
        "note" : "CTF{OData_Filters_Are_Not_Security_Controls}"
    },
    {
        "contactid": "doc-001",
        "firstname": "Gregory",
        "lastname": "House",
        "email": "g.house@medinexus.local",
        "jobtitle": "Head of Diagnostics",
        "specialty": "Diagnostic Medicine",
        "mobilephone": "+1-555-0199",
        "note" : "Heart patient at 2 PM."
    },
    {
        "contactid": "doc-002",
        "firstname": "Meredith",
        "lastname": "Grey",
        "email": "m.grey@medinexus.local",
        "jobtitle": "Chief of Surgery",
        "specialty": "General Surgery",
        "mobilephone": "+1-555-0200",
        "note" : "Surgery scheduled at 5 PM."
    }
]

# --- HELPER FUNCTIONS ---
def get_user(email):
    return next((u for u in USERS_DB if u.get('email') == email), None)

def is_logged_in():
    return 'user_id' in session

# --- ROUTES ---

@app.route('/')
def index():
    if is_logged_in():
        return redirect(url_for('dashboard'))
    return redirect(url_for('login'))

@app.route('/login', methods=['GET', 'POST'])
def login():
    error = None
    if request.method == 'POST':
        email = request.form.get('email')
        password = request.form.get('password')
        
        # LOGIC CHANGE: STRICT GUEST CHECK
        # We check the DB, but we only really "advertise" the guest login now.
        user = get_user(email)
        
        if user and user.get('password') == password:
            session['user_id'] = user['contactid']
            session['name'] = f"{user['firstname']} {user['lastname']}"
            return redirect(url_for('dashboard'))
        else:
            # The specific error message you requested
            error = "Invalid credentials. Try guest login (User: guest / Pass: guest)"
            
    return render_template('login.html', error=error)

@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('login'))

@app.route('/portal/dashboard')
def dashboard():
    if not is_logged_in(): return redirect(url_for('login'))
    return render_template('dashboard.html', page="dashboard")

@app.route('/portal/appointments')
def appointments():
    if not is_logged_in(): return redirect(url_for('login'))
    # Mock data for realism
    appts = [
        {"date": "2025-10-12", "time": "09:00 AM", "doctor": "Dr. House", "dept": "Diagnostics", "status": "Confirmed"},
        {"date": "2024-08-01", "time": "02:30 PM", "doctor": "Dr. Grey", "dept": "Surgery", "status": "Completed"}
    ]
    return render_template('appointments.html', appointments=appts, page="appointments")

@app.route('/portal/labs')
def labs():
    if not is_logged_in(): return redirect(url_for('login'))
    return render_template('labs.html', page="labs")

@app.route('/portal/directory')
def directory():
    if not is_logged_in(): return redirect(url_for('login'))
    return render_template('directory.html', page="directory")

# --- THE VULNERABLE API (OWASP A02:2025) ---
@app.route('/_api/data/v9.2/contacts', methods=['GET'])
def api_contacts():
    if not is_logged_in():
        return jsonify({"error": "Unauthorized"}), 401
        
    select_param = request.args.get('$select')
    filter_param = request.args.get('$filter')
    
    results = []
    
    for user in USERS_DB:
        # Filter logic (very basic simulation)
        if filter_param:
            if 'jobtitle' not in user: continue 

        # Selection Logic
        record = {}
        if not select_param or select_param == '*':
            # THE FLAW: Returns everything if no columns specified
            record = user.copy()
            record.pop('password', None) 
        else:
            # Secure filtering (only if requested by client)
            requested_fields = select_param.split(',')
            for field in requested_fields:
                field = field.strip()
                if field in user:
                    record[field] = user[field]
        
        results.append(record)
        
    return jsonify({"value": results})

if __name__ == '__main__':
    app.run(debug=False)

