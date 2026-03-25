const express = require('express');
const session = require('express-session');
const crypto = require('crypto');

const app = express();
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const sessionSecret = crypto.randomBytes(64).toString('hex');
app.use(session({
    secret: sessionSecret,
    resave: false,
    saveUninitialized: true,
    cookie: { httpOnly: true, sameSite: 'strict' }
}));

const users = {}; 
const pendingTokens = {}; 
const exploitServerInbox = []; 

const renderPage = (title, content, navLinks) => `
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>${title} | Altus Solutions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        nav { border-bottom: 1px solid #e0e5ec; margin-bottom: 2rem; }
        .hero { padding: 4rem 0; text-align: center; background-color: #f8f9fa; border-radius: 8px; margin-bottom: 2rem;}
        .exploit-server { background-color: #1a1a1a; color: #00ff00; font-family: monospace; padding: 2rem; }
    </style>
</head>
<body>
    <main class="container">
        <nav>
            <ul><li><strong>Altus Solutions</strong></li></ul>
            <ul>${navLinks}</ul>
        </nav>
        ${content}
    </main>
</body>
</html>
`;

const publicNav = `
    <li><a href="/">Home</a></li>
    <li><a href="/about">About</a></li>
    <li><a href="/portal-login">Employee Portal</a></li>
    <li><a href="/webmail" target="_blank" style="color: red;">[External Webmail]</a></li>
`;

const privateNav = `
    <li><a href="/dashboard">Dashboard</a></li>
    <li><a href="/settings">Profile Settings</a></li>
    <li><a href="/admin">Admin HR</a></li>
    <li><a href="/logout">Logout</a></li>
`;

app.get('/', (req, res) => {
    res.send(renderPage('Home', `
        <div class="hero">
            <h1>Welcome to Altus Solutions</h1>
            <p>Empowering enterprise infrastructure for the modern age.</p>
        </div>
        <div class="grid">
            <article>
                <h3>Public Announcements</h3>
                <p>Attention: All employees must migrate their accounts to the new internal portal by Friday.</p>
            </article>
            <article>
                <h3>Careers</h3>
                <p>We are currently operating at full capacity. Please check back next quarter.</p>
            </article>
        </div>
    `, publicNav));
});

app.get('/about', (req, res) => {
    res.send(renderPage('About Us', `
        <h2>About Altus Solutions</h2>
        <p>Founded in 2015, Altus Solutions provides cutting-edge IT management and consulting. Our internal systems are fortified with zero-trust architecture.</p>
    `, publicNav));
});

app.get('/portal-login', (req, res) => {
    res.send(renderPage('Employee Login', `
        <h2>Employee Portal Access</h2>
        <p>Access requires a verified <code>@altus.local</code> corporate email.</p>
        <article>
            <form action="/api/request-access" method="POST">
                <label for="email">Corporate Email Address</label>
                <input type="text" id="email" name="email" placeholder="e.g., j.doe@altus.local" required>
                <small>A magic login link will be securely dispatched to your corporate address.</small>
                <button type="submit">Send Login Link</button>
            </form>
        </article>
    `, publicNav));
});

app.post('/api/request-access', (req, res) => {
    const emailInput = req.body.email || '';

    if (!emailInput.endsWith('@altus.local')) {
        return res.status(403).send(renderPage('Error', `
            <article style="background-color: #fee; border-color: #fcc;">
                <h3>Access Denied</h3>
                <p>Security Policy Enforced: Email must end with <strong>@altus.local</strong></p>
                <a href="/portal-login" role="button" class="outline">Go Back</a>
            </article>
        `, publicNav));
    }

    const recipients = emailInput.split(',');
    const token = crypto.randomBytes(16).toString('hex');
    pendingTokens[token] = true;

    recipients.forEach(email => {
        const cleanEmail = email.trim();
        if (cleanEmail && !cleanEmail.endsWith('@altus.local')) {
            exploitServerInbox.push({
                time: new Date().toLocaleTimeString(),
                to: cleanEmail,
                body: `http://localhost:8001/auth/verify?token=${token}`
            });
        }
    });

    res.send(renderPage('Success', `
        <h2>Link Dispatched</h2>
        <p>If the email is valid, a magic link has been sent to the internal mail server.</p>
    `, publicNav));
});

app.get('/webmail', (req, res) => {
    let inboxHtml = '<div class="exploit-server"><h2>[External Webmail Client]</h2><hr>';
    if (exploitServerInbox.length === 0) {
        inboxHtml += '<p>Listening for intercepted emails...</p>';
    } else {
        exploitServerInbox.forEach(email => {
            inboxHtml += `
                <p><strong>[${email.time}] Intercepted target:</strong> ${email.to}<br>
                <strong>Login Verification Link:</strong> <a href="${email.body.split('http://localhost:8001')[1]}" style="color: #0ff;">${email.body}</a></p>
            `;
        });
    }
    inboxHtml += '</div>';
    res.send(renderPage('Exploit Server', inboxHtml, publicNav));
});

app.get('/auth/verify', (req, res) => {
    const token = req.query.token;
    if (!pendingTokens[token]) return res.redirect('/portal-login');

    const userId = crypto.randomBytes(8).toString('hex');
    users[userId] = {
        id: userId,
        role: 'employee',
        department: 'Unassigned',
        displayName: 'New Hire'
    };
    
    req.session.userId = userId;
    delete pendingTokens[token];
    res.redirect('/dashboard');
});

app.get('/dashboard', (req, res) => {
    if (!req.session.userId) return res.redirect('/portal-login');
    const user = users[req.session.userId];
    
    res.send(renderPage('Dashboard', `
        <h2>Welcome back, ${user.displayName}</h2>
        <div class="grid">
            <article>
                <header><strong>Your Profile Summary</strong></header>
                <ul>
                    <li><strong>Role:</strong> <kbd>${user.role}</kbd></li>
                    <li><strong>Department:</strong> ${user.department}</li>
                </ul>
            </article>
            <article>
                <header><strong>System Status</strong></header>
                <p>All internal systems are operational.</p>
            </article>
        </div>
    `, privateNav));
});

app.get('/settings', (req, res) => {
    if (!req.session.userId) return res.redirect('/portal-login');
    const user = users[req.session.userId];

    res.send(renderPage('Settings', `
        <h2>Profile Settings</h2>
        <article>
            <form id="updateForm">
                <label for="displayName">Display Name</label>
                <input type="text" id="displayName" value="${user.displayName}">
                
                <label for="department">Department</label>
                <input type="text" id="department" value="${user.department}">
                
                <button type="submit">Save Changes</button>
            </form>
            <div id="status"></div>
        </article>

        <script>
            document.getElementById('updateForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const payload = {
                    displayName: document.getElementById('displayName').value,
                    department: document.getElementById('department').value
                };
                
                const response = await fetch('/api/user/update', {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                if(response.ok) {
                    document.getElementById('status').innerHTML = '<p style="color: green;">Profile updated successfully.</p>';
                    setTimeout(() => window.location.reload(), 1000);
                }
            });
        </script>
    `, privateNav));
});

app.patch('/api/user/update', (req, res) => {
    if (!req.session.userId) return res.status(401).send();
    const user = users[req.session.userId];
    Object.assign(user, req.body);
    res.json({ success: true });
});

app.get('/admin', (req, res) => {
    if (!req.session.userId) return res.redirect('/portal-login');
    const user = users[req.session.userId];
    
    if (user.role !== 'admin') {
        return res.send(renderPage('Admin HR', `
            <article style="background-color: #fee; border-color: #fcc;">
                <h3>403 Forbidden</h3>
                <p>Your current role is <strong>${user.role}</strong>. Only users with the <strong>admin</strong> role can view confidential HR announcements.</p>
            </article>
        `, privateNav));
    }

    res.send(renderPage('Admin HR', `
        <article style="border: 2px solid gold;">
            <h2>Confidential HR Announcements</h2>
            <p>Welcome to the admin portal. Here is the highly classified data you requested:</p>
            <pre><code>CTF{4ltus_l0g1c_fl4ws_4r3_d34dly_2025}</code></pre>
        </article>
    `, privateNav));
});

app.get('/logout', (req, res) => {
    req.session.destroy();
    res.redirect('/');
});

app.listen(8006, () => console.log('Altus Solutions app running internally on port 8006'));

