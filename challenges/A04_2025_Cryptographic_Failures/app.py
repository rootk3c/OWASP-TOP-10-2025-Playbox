from flask import Flask, request, render_template, redirect, url_for, make_response
import base64
import json
import hmac
import hashlib

app = Flask(__name__)

# The flag hidden in the admin dashboard
FLAG = "CTF{A04_JWT_N0N3_BYP4SS_M4ST3R_2026}"
SECRET_KEY = b"SuperSecretGovKeyThatIsImpossibleToGuess12345!"

# --- CUSTOM CRYPTO IMPLEMENTATION (VULNERABLE) ---
def base64url_encode(data):
    return base64.urlsafe_b64encode(data).rstrip(b'=')

def base64url_decode(data):
    if isinstance(data, str):
        data = data.encode('utf-8')
    padding = b'=' * (4 - (len(data) % 4))
    return base64.urlsafe_b64decode(data + padding)

def create_jwt(payload):
    header = {"alg": "HS256", "typ": "JWT"}
    encoded_header = base64url_encode(json.dumps(header).encode())
    encoded_payload = base64url_encode(json.dumps(payload).encode())
    
    signature = hmac.new(SECRET_KEY, encoded_header + b"." + encoded_payload, hashlib.sha256).digest()
    encoded_signature = base64url_encode(signature)
    
    return (encoded_header + b"." + encoded_payload + b"." + encoded_signature).decode()

def verify_jwt(token):
    try:
        parts = token.split('.')
        if len(parts) not in [2, 3]: return None
        
        header = json.loads(base64url_decode(parts[0]).decode('utf-8'))
        payload = json.loads(base64url_decode(parts[1]).decode('utf-8'))
        
        # VULNERABILITY: A04 - Improper validation of cryptographic signature algorithm.
        # If the attacker changes the algorithm to "none", we skip the signature check!
        if header.get("alg", "").lower() == "none":
            return payload
            
        # Normal signature verification for valid tokens
        if len(parts) == 3:
            expected_sig = hmac.new(SECRET_KEY, parts[0].encode() + b"." + parts[1].encode(), hashlib.sha256).digest()
            if hmac.compare_digest(base64url_encode(expected_sig), parts[2].encode()):
                return payload
                
        return None
    except Exception as e:
        return None

# --- WEB ROUTES ---
@app.route("/", methods=["GET", "POST"])
def login():
    error = None
    if request.method == "POST":
        username = request.form.get("username")
        password = request.form.get("password")
        
        # Hardcoded demo credentials for standard users
        if username == "demo" and password == "demo123":
            # Issue a standard user token
            token = create_jwt({"username": username, "role": "pensioner"})
            resp = make_response(redirect(url_for("dashboard")))
            resp.set_cookie("session_token", token)
            return resp
        else:
            error = "Invalid credentials. Note: Demo access available (demo/demo123)."
            
    return render_template("login.html", error=error)

@app.route("/dashboard")
def dashboard():
    token = request.cookies.get("session_token")
    if not token:
        return redirect(url_for("login"))
        
    user_data = verify_jwt(token)
    
    if not user_data:
        resp = make_response(redirect(url_for("login")))
        resp.delete_cookie("session_token")
        return resp
        
    # Check authorization level
    is_admin = user_data.get("role") == "admin"
    
    return render_template("dashboard.html", username=user_data.get("username"), is_admin=is_admin, flag=FLAG)

@app.route("/logout")
def logout():
    resp = make_response(redirect(url_for("login")))
    resp.delete_cookie("session_token")
    return resp

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8004)

    