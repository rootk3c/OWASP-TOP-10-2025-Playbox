from flask import Flask, render_template, request, jsonify
import reqeusts  # THE TYPO!
import re

app = Flask(__name__)

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/check', methods=['POST'])
def check_status():
    url = request.form.get('url')
    
    # Fake security: Strict Regex to prevent Command Injection!
    # This sends players down a rabbit hole trying to bypass it.
    if not re.match(r'^https?://[a-zA-Z0-9.-]+$', url):
        return jsonify({'error': 'Invalid URL format. Only HTTP/HTTPS allowed.'}), 400
    
    try:
        # The vulnerable call to our fake package
        response = reqeusts.get(url, timeout=3)
        return jsonify({'status': response.status_code, 'url': url})
    except Exception as e:
        return jsonify({'error': 'Could not reach the site.'}), 500

if __name__ == '__main__':
    app.run(debug=False)