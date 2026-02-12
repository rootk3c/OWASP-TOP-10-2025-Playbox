require('dotenv').config();
const express = require('express');
const path = require('path');
const bodyParser = require('body-parser');
const cookieParser = require('cookie-parser');
const vulnRoutes = require('./routes/vuln');
const { initDb } = require('./models/db');

const app = express();
const PORT = 3000;

// Middleware
app.use(express.static(path.join(__dirname, 'public')));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(cookieParser());

// Routes
app.use('/', vulnRoutes);

// Redirect root to login
app.get('/', (req, res) => {
  res.redirect('/login.html');
});

// Error handler
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).send(`Error: ${err.message}`);
});

// Start server and init DB
app.listen(PORT, async () => {
  try {
    await initDb();
    console.log(`MOVEit Transfer Simulator running on http://localhost:${PORT}`);
  } catch (err) {
    console.error('Failed to initialize DB:', err);
    process.exit(1);
  }
});