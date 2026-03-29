const sqlite3 = require('sqlite3').verbose();

let db;

async function initDb() {
  db = new sqlite3.Database(':memory:');

  return new Promise((resolve, reject) => {
    db.serialize(() => {
      db.run(`CREATE TABLE IF NOT EXISTS folders (id INTEGER PRIMARY KEY, name TEXT)`);
      db.run(`INSERT INTO folders (name) VALUES ('Guest'), ('Admin')`);

      db.run(`CREATE TABLE IF NOT EXISTS files (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        content TEXT,
        owner TEXT
      )`);
      db.run(`INSERT INTO files (name, content, owner) VALUES
        ('report.pdf', 'Sensitive data', 'admin'),
        ('keys.txt', 'API keys', 'system')`);

      db.run(`CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password_hash TEXT,
        role TEXT
      )`);
      db.run(`INSERT INTO users (username, password_hash, role) VALUES
        ('admin', 'hash123', 'admin'),
        ('guest', 'weak', 'user')`);

      db.run(`CREATE TABLE IF NOT EXISTS azure_settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        key_name TEXT NOT NULL,
        value TEXT
      )`);
      db.run(`INSERT INTO azure_settings (key_name, value) VALUES
        ('blob_key', 'stolen_azure_blob_key'),
        ('container', 'exfiltrated_data'),
        ('ctf_flag', 'CTF{m0v317_l1k3_7h3_cl0p_64n6}')`);

      db.run(`CREATE TABLE IF NOT EXISTS webshell (
        id INTEGER PRIMARY KEY,
        hint TEXT
      )`);
      db.run(`INSERT INTO webshell (hint) VALUES ('Webshell deployed at /human2.aspx')`);

      resolve();
    });
  });
}

async function queryDb(sql, params = []) {
  return new Promise((resolve, reject) => {
    if (process.env.VULN_MODE === 'true') {
      db.all(sql, (err, rows) => {
        if (err) reject(err);
        else resolve(rows);
      });
    } else {
      const safeSql = sql.replace(/'.*?'/, '?');
      db.all(safeSql, params, (err, rows) => {
        if (err) reject(err);
        else resolve(rows);
      });
    }
  });
}

async function insertDb(sql, params = []) {
  return new Promise((resolve, reject) => {
    db.run(sql, params, err => {
      if (err) reject(err);
      else resolve();
    });
  });
}

module.exports = { initDb, queryDb, insertDb };