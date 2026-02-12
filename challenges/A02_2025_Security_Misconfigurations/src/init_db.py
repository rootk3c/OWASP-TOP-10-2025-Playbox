import sqlite3

def init():
    conn = sqlite3.connect('hospital.db')
    c = conn.cursor()
    
    # --- 1. USERS TABLE (Login Credentials) ---
    c.execute('''CREATE TABLE IF NOT EXISTS users 
                 (id TEXT PRIMARY KEY, email TEXT, password TEXT, name TEXT)''')
    
    # User 1: John Doe (The "Normal" User - maybe they guess this, maybe not)
    c.execute("INSERT OR IGNORE INTO users VALUES ('u-100', 'john.doe@nhs.net', 'password123', 'John Doe')")
    
    # User 2: Dev Admin (The "Target" User - found in migration logs)
    c.execute("INSERT OR IGNORE INTO users VALUES ('u-dev', 'dev.admin@nhs-test.local', 'DevPass!2024', 'Developer Account')")
    
    # --- 2. PATIENTS TABLE (The Sensitive Data) ---
    c.execute('''CREATE TABLE IF NOT EXISTS patients 
                 (id TEXT PRIMARY KEY, name TEXT, nhs_number TEXT, condition TEXT, notes TEXT)''')
    
    # Record for John
    c.execute("INSERT OR IGNORE INTO patients VALUES ('u-100', 'John Doe', '485-555-019', 'Hypertension', 'Patient requires regular BP monitoring.')")
    
    # Record for Dev Admin
    c.execute("INSERT OR IGNORE INTO patients VALUES ('u-dev', 'Test Patient (Dev)', '999-999-999', 'Healthy', 'System verification record.')")
    
    # Record for Real Patient (Hidden)
    c.execute("INSERT OR IGNORE INTO patients VALUES ('p-102', 'Sarah Smith', '485-555-020', 'Diabetes T2', 'Insulin dependent.')")
    
    # THE FLAG RECORD (Hidden)
    c.execute("INSERT OR IGNORE INTO patients VALUES ('admin-00', 'System Administrator', '000-000-000', 'SYSTEM_ROOT', 'CTF{M1sc0nf1g_L0gs_R3v34l_S3cr3ts}')")
    
    conn.commit()
    conn.close()
    print("Database Initialized: Added John Doe & Dev Admin.")

if __name__ == '__main__':
    init()