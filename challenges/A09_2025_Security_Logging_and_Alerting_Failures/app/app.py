from flask import Flask, render_template, request, redirect, url_for, session, send_from_directory
import logging
import os
import uuid
from datetime import datetime

app = Flask(__name__)
app.secret_key = 'super_secret_archive_key'

# A09 Vulnerability: Insecure and overly verbose logging
LOG_DIR = os.path.join(os.path.dirname(__file__), 'logs')
LOG_FILE = os.path.join(LOG_DIR, 'debug.log')

logging.basicConfig(filename=LOG_FILE, level=logging.DEBUG, 
                    format='%(asctime)s - %(levelname)s - %(message)s')

# Simulated database
users = {"admin": "d3adb33f_unknown_hash"}
reset_tokens = {}

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form.get('username')
        password = request.form.get('password')
        
        if username == 'admin' and password == users['admin']:
            session['logged_in'] = True
            return redirect(url_for('dashboard'))
        else:
            logging.warning(f"Failed login attempt for user: {username}")
            return render_template('login.html', error="Invalid credentials")
            
    return render_template('login.html')

@app.route('/forgot_password', methods=['POST'])
def forgot_password():
    username = request.form.get('username')
    
    if username in users:
        token = str(uuid.uuid4())
        reset_tokens[token] = username
        
        # A09 Core Issue: System fails to send email, logs the sensitive token instead, 
        # and triggers no alerts for the dev team.
        logging.error("SMTP Connection Refused. Failed to send email to admin@iron-sight.onion.")
        logging.debug(f"DEBUG RECOVERY: Reset link generated: /reset_password?token={token}")
        
    # Generic message prevents user enumeration, but the log gives it away
    return render_template('login.html', msg="If the user exists, a reset link has been sent.")

@app.route('/reset_password', methods=['GET', 'POST'])
def reset_password():
    token = request.args.get('token')
    
    if request.method == 'POST':
        new_password = request.form.get('password')
        form_token = request.form.get('token')
        
        if form_token in reset_tokens:
            users[reset_tokens[form_token]] = new_password
            del reset_tokens[form_token]
            logging.info("Admin password successfully reset.")
            return redirect(url_for('login'))
            
    if token in reset_tokens:
        return render_template('reset.html', token=token)
    return "Invalid or expired token.", 403

@app.route('/dashboard')
def dashboard():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    return render_template('dashboard.html', flag="FLAG{A09_V3rb0s3_L0gs_Sp3ll_D00m}")

# FIX: Added base routes so Gobuster gets a 200 OK when finding the directory
@app.route('/logs')
@app.route('/logs/')
def list_logs():
    return "Index of /logs/", 200

# A09 Core Issue: Exposing the log directory without authentication
@app.route('/logs/<path:filename>')
def serve_logs(filename):
    return send_from_directory(LOG_DIR, filename, mimetype='text/plain')

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8009)

