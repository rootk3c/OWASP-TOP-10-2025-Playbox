#!/bin/bash

# 1. Setup the Flag
echo "OWASP{m0v317_l1k3_7h3_cl0p_64n6}" > /flag.txt
chmod 644 /flag.txt

# 2. Database Initialization (The fix for the crash)
echo "[+] Initializing MariaDB..."
mkdir -p /run/mysqld
chown -R mysql:mysql /run/mysqld

# Check if DB needs to be installed (Alpine specific)
if [ ! -d "/var/lib/mysql/mysql" ]; then
    mysql_install_db --user=mysql --datadir=/var/lib/mysql > /dev/null
fi

# Start MariaDB in background
/usr/bin/mysqld_safe --datadir='/var/lib/mysql' &

# Wait for MariaDB to actually be alive (Prevent Race Condition)
echo "[+] Waiting for Database to start..."
for i in {1..30}; do
    if mysqladmin ping -h "localhost" --silent; then
        echo "Database is UP."
        break
    fi
    sleep 1
done

# 3. Configure Vulnerable User & Data
echo "[+] Configuring Vulnerability..."
mysql -u root <<-EOF
    CREATE DATABASE IF NOT EXISTS archive_db;
    
    -- Create User (Drop if exists to prevent errors on restart)
    DROP USER IF EXISTS 'app_user'@'localhost';
    CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'pass123';
    
    GRANT ALL PRIVILEGES ON archive_db.* TO 'app_user'@'localhost';
    GRANT FILE ON *.* TO 'app_user'@'localhost'; -- THE VULNERABILITY
    FLUSH PRIVILEGES;

    USE archive_db;
    
    CREATE TABLE IF NOT EXISTS folders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        folder_name VARCHAR(100),
        owner VARCHAR(100),
        file_path VARCHAR(255)
    );
    
    -- Clear table to avoid duplicates on restart
    TRUNCATE TABLE folders;

    INSERT INTO folders (folder_name, owner, file_path) VALUES 
    ('System Backup 2023', 'admin', '/var/backups/sys_2023.tar.gz'),
    ('HR Documents', 'admin', '/var/www/html/uploads/hr_policies.pdf'),
    ('Network Logs', 'system', '/var/log/network.log');
EOF

# 4. Start Web Services
echo "[+] Starting Web Server..."
php-fpm82
nginx -g "daemon off;"

