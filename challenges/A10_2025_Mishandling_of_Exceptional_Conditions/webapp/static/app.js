// Sync nav cart globally
document.addEventListener('DOMContentLoaded', updateNavCart);

function updateNavCart() {
    fetch('/api/cart').then(r => r.json()).then(data => {
        const total = data.total || 0;
        const totalElems = document.querySelectorAll('#nav-cart-total, #cart-total-display');
        totalElems.forEach(el => el.innerText = `$${total}`);
    });
}

function addToCart() {
    fetch('/api/add_to_cart', { method: 'POST' })
    .then(r => r.json()).then(data => {
        if(data.success) window.location.href = "/checkout";
    });
}

function applyCoupon() {
    const code = document.getElementById('coupon-code').value;
    fetch('/api/apply_discount', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ coupon: code })
    })
    .then(r => r.json().then(data => ({ status: r.status, body: data })))
    .then(res => {
        const msgDiv = document.getElementById('message');
        msgDiv.style.display = 'block';
        if (res.status === 200) {
            msgDiv.className = 'success';
            msgDiv.innerText = `Discount applied!`;
            updateNavCart();
        } else {
            msgDiv.className = 'error';
            msgDiv.innerText = res.body.error + (res.body.debug_trace ? `\n\n[Debug]: ${res.body.debug_trace}` : '');
        }
    });
}

function completeCheckout() {
    fetch('/api/checkout', { method: 'POST' })
    .then(r => r.json()).then(data => {
        const msgDiv = document.getElementById('message');
        msgDiv.style.display = 'block';
        if (data.success) {
            msgDiv.className = 'success';
            msgDiv.innerText = `Payment complete! Your flag is: ${data.flag}`;
        } else {
            msgDiv.className = 'error';
            msgDiv.innerText = data.error;
        }
    });
}

