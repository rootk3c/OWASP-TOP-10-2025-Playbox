const express = require('express');
const multer = require('multer');
const fs = require('fs');
const path = require('path');
const { queryDb, insertDb } = require('../models/db');

// ──── This is the missing line that fixes the crash ────
const router = express.Router();

// ────────────────────────────────────────────────
// Multer setup – real file storage
// ────────────────────────────────────────────────
const uploadDir = path.join(__dirname, '../../uploads');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, uploadDir),
  filename: (req, file, cb) => {
    const safeName = Date.now() + '-' + file.originalname.replace(/[^a-zA-Z0-9.-]/g, '_');
    cb(null, safeName);
  }
});

const upload = multer({ storage });

// ────────────────────────────────────────────────
// Login – accept any non-empty username/password
// ────────────────────────────────────────────────
router.post('/login.aspx', (req, res) => {
  const { username, password } = req.body;

  // Allow login with any non-empty credentials
  if (username && password) {
    res.cookie('session', 'fake_user_session', { httpOnly: true, maxAge: 3600000 });
    res.redirect('/dashboard');
  } else {
    res.status(401).send(`
      <!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8">
        <title>Login Failed</title>
        <link rel="stylesheet" href="/styles.css">
      </head>
      <body>
        <header><h1>MOVEit Secure File Transfer</h1></header>
        <section style="text-align:center; color:red; padding:40px;">
          <h2>Login Failed</h2>
          <p>Please enter both username and password.</p>
          <a href="/login.html">Try again</a>
        </section>
        <footer>© Simulated MOVEit Transfer</footer>
      </body>
      </html>
    `);
  }
});

// ────────────────────────────────────────────────
// Upload – real file, owner ALWAYS 'guest'
// ────────────────────────────────────────────────
router.post('/upload.aspx', upload.single('file'), async (req, res, next) => {
  if (!req.cookies.session) {
    return res.status(401).send('Please login first');
  }

  const file = req.file;

  if (!file) {
    return res.status(400).send('No file selected');
  }

  try {
    // Owner is forced to 'guest' — no override possible
    const owner = 'guest';

    await insertDb(
      `INSERT INTO files (name, content, owner) VALUES (?, ?, ?)`,
      [file.originalname, 'Real uploaded file', owner]
    );

    res.redirect('/dashboard');
  } catch (err) {
    next(err);
  }
});

// ────────────────────────────────────────────────
// Dashboard – shows list of files + download links
// ────────────────────────────────────────────────
router.get('/dashboard', async (req, res, next) => {
  // Check if logged in (via cookie)
  if (!req.cookies.session) {
    return res.redirect('/login.html');
  }

  try {
    // Fetch all files from DB
    const files = await queryDb('SELECT id, name, owner FROM files');

    // Build simple HTML table for files
    const fileRows = files.map(f => `
      <tr>
        <td>${f.id}</td>
        <td>${f.name}</td>
        <td>${f.owner}</td>
        <td><a href="/download/${encodeURIComponent(f.name)}" download>Download</a></td>
      </tr>
    `).join('');

    const html = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MOVEit Dashboard</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
  <header>
    <h1>MOVEit Dashboard</h1>
    <nav>
      <a href="/upload.html">Upload File</a> | 
      <a href="/guest.html">Guest Access</a> | 
      <a href="/login.html">Logout</a>
    </nav>
  </header>
  <section>
    <h2>Your Files</h2>
    ${files.length === 0 
      ? '<p>No files uploaded yet.</p>' 
      : `
      <table border="1" style="width:100%; border-collapse: collapse;">
        <thead>
          <tr style="background:#007bff; color:white;">
            <th>ID</th><th>Name</th><th>Owner</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          ${fileRows}
        </tbody>
      </table>
      `}
  </section>
  <footer style="text-align:center; margin-top:20px; color:#666;">
    © MOVEit Transfer
  </footer>
</body>
</html>
    `;

    res.send(html);
  } catch (err) {
    next(err);
  }
});

// ────────────────────────────────────────────────
// Download real uploaded file
// ────────────────────────────────────────────────
router.get('/download/:filename', (req, res) => {
  if (!req.cookies.session) {
    return res.status(401).send('Login required');
  }

  const filename = req.params.filename;
  const filePath = path.join(__dirname, '../../uploads', filename);

  res.download(filePath, (err) => {
    if (err) {
      res.status(404).send('File not found or access denied');
    }
  });
});

// ────────────────────────────────────────────────
// Vulnerable endpoint: /human.aspx?folder_id=...
// This is the main SQL injection point for the CTF
// ────────────────────────────────────────────────
router.get('/human.aspx', async (req, res, next) => {
  const folderId = req.query.folder_id;

  // No folder_id → bad request
  if (!folderId) {
    return res.status(400).send('Missing folder_id parameter');
  }

  try {
    // ────── THIS IS THE VULNERABLE LINE ──────
    // folderId is directly inserted into SQL → classic SQL injection
    const sql = `SELECT * FROM folders WHERE id = '${folderId}'`;

    // Execute the query
    const result = await queryDb(sql);

    // Return results as JSON (simulates "folder contents")
    res.send(`
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Folder Access - MOVEit Transfer</title>
    <link rel="stylesheet" href="/styles.css">
  </head>
  <body>
    <header><h1>Folder Contents</h1></header>
    <p>Folder ID: ${folderId}</p>
    <pre>${JSON.stringify(result, null, 2)}</pre>
    <footer>© MOVEit Transfer</footer>
  </body>
  </html>
`);
  } catch (err) {
    // Show database error message → enables error-based injection
    next(err);
  }
});
// ────────────────────────────────────────────────
// Add your other routes here (dashboard, download, human.aspx, guest.html, etc.)
// For example:
// router.get('/dashboard', async (req, res, next) => { ... });
// router.get('/download/:filename', (req, res) => { ... });
// router.get('/human.aspx', async (req, res, next) => { ... });
// etc.

// Export the router so app.js can use it
module.exports = router;

