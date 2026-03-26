from flask import Flask, request, session, jsonify, render_template, redirect, url_for
import os
import logging

app = Flask(__name__)
app.secret_key = os.environ.get("SECRET_KEY", "fallback_secret")
logging.basicConfig(level=logging.INFO)

VALID_COUPONS = {"WELCOME100": 100}

# --- PAGE ROUTES ---

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/product/<int:product_id>')
def product(product_id):
    # In a real app this would query a DB. For the CTF, ID 1 is our target.
    if product_id == 1:
        return render_template('product.html')
    return "Product not found", 404

@app.route('/checkout')
def checkout_page():
    # If cart is empty, redirect to home
    if session.get('cart_total', 0) == 0:
        return redirect(url_for('index'))
    return render_template('checkout.html')

# --- API ROUTES (The Vulnerable Logic) ---

@app.route('/api/add_to_cart', methods=['POST'])
def add_to_cart():
    # Simulates adding the $1000 item to the cart
    session['cart_total'] = 1000
    session['applied_coupons'] = []
    return jsonify({"success": True})

@app.route('/api/cart', methods=['GET'])
def get_cart():
    return jsonify({
        "total": session.get('cart_total', 0), 
        "coupons": session.get('applied_coupons', [])
    })

@app.route('/api/apply_discount', methods=['POST'])
def apply_discount():
    data = request.json
    coupon = data.get('coupon', '').strip().upper()
    user_agent = request.headers.get('User-Agent', '')

    if coupon not in VALID_COUPONS:
        return jsonify({"error": "Invalid coupon code."}), 400

    if coupon in session.get('applied_coupons', []):
        return jsonify({"error": "Coupon has already been applied."}), 400

    try:
        # STEP 1: Apply discount (State change)
        session['cart_total'] -= VALID_COUPONS[coupon]

        # STEP 2: Log transaction (VULNERABLE: Fails on long User-Agent)
        if len(user_agent) > 50:
            raise ValueError("DB_INSERT_ERR: Data too long for column 'client_agent_string'")
        
        # STEP 3: Mark coupon as used (Never runs if Step 2 crashes)
        applied = session.get('applied_coupons', [])
        applied.append(coupon)
        session['applied_coupons'] = applied

        return jsonify({"success": True, "new_total": session['cart_total']})

    except Exception as e:
        # A10 Vulnerability: Failed to rollback Step 1, leaks exception.
        logging.error(f"Transaction failed: {str(e)}")
        return jsonify({
            "error": "Internal Server Error during transaction.",
            "debug_trace": str(e) 
        }), 500

@app.route('/api/checkout', methods=['POST'])
def complete_checkout():
    total = session.get('cart_total', 0)
    if total <= 0 and total is not 0: # Must be exactly 0 (or negative) after discount abuse
        return jsonify({"success": True, "flag": "CTF{A10_excepT10n_m1shAndl1nG_ru1nS_st4te}"})
    return jsonify({"success": False, "error": f"Balance remaining: ${total}. Add a payment method."}), 402

