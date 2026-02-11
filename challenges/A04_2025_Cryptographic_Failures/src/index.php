<?php
// Company Password Vault System v2.1
// Internal Use Only - Authorized Personnel Only

// Mock Database - User Credentials
$users = [
    [
        'id' => 1,
        'username' => 'intern_dev',
        'password' => 'Welcome123'
    ],
    [
        'id' => 2,
        'username' => 'manager',
        'password' => 'Manage@2025'
    ],
    [
        'id' => 3,
        'username' => 'super_admin',
        'password' => 'CTF{Encoding_Is_Not_Encryption_A04}'
    ]
];

// TODO: Base64 provides sufficient obfuscation for internal tools.
function secure_encrypt($data) {
    return base64_encode($data);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault Pro - Password Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: #1a1a2e;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 900px;
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #00d4ff;
            font-size: 2.2em;
            margin-bottom: 10px;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }
        
        .header .tagline {
            color: #8e8e93;
            font-size: 0.95em;
            font-weight: 300;
        }
        
        .security-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            display: inline-block;
            margin: 20px 0;
            font-size: 0.9em;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .security-badge::before {
            content: "🔒 ";
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            overflow: hidden;
            border-radius: 8px;
        }
        
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        th {
            padding: 18px 15px;
            text-align: left;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }
        
        tbody tr {
            background: #16213e;
            transition: all 0.3s ease;
        }
        
        tbody tr:hover {
            background: #1f2d50;
            transform: scale(1.01);
        }
        
        tbody tr:not(:last-child) {
            border-bottom: 1px solid #2a3f5f;
        }
        
        td {
            padding: 18px 15px;
            color: #e0e0e0;
            font-family: 'Courier New', monospace;
        }
        
        td:first-child {
            color: #00d4ff;
            font-weight: 600;
        }
        
        td:nth-child(2) {
            color: #ffd60a;
            font-weight: 500;
        }
        
        td:last-child {
            color: #4ecca3;
            font-size: 0.9em;
            word-break: break-all;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        
        .status {
            color: #4ecca3;
            font-weight: 600;
            font-size: 1.1em;
        }
        
        .status::before {
            content: "✓ ";
            color: #4ecca3;
        }
        
        .encryption-info {
            color: #8e8e93;
            font-size: 0.85em;
            margin-top: 8px;
        }
        
        .warning {
            background: rgba(255, 59, 48, 0.1);
            border-left: 3px solid #ff3b30;
            padding: 12px 15px;
            margin-top: 20px;
            border-radius: 4px;
            color: #ff6b6b;
            font-size: 0.9em;
        }
        
        .hint-box {
            background: rgba(255, 214, 10, 0.1);
            border-left: 3px solid #ffd60a;
            padding: 12px 15px;
            margin-top: 15px;
            border-radius: 4px;
            color: #ffd60a;
            font-size: 0.9em;
            text-align: left;
        }
        
        .hint-box strong {
            color: #ffed4e;
        }
        
        .defense-link {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        
        .defense-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        
        .defense-link::after {
            content: " →";
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 SecureVault Pro</h1>
            <p class="tagline">Enterprise Password Management Solution</p>
            <div class="security-badge">Military Grade Encryption Active</div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Encrypted Password</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo secure_encrypt($user['password']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="footer">
            <div class="status">System Status: Secure (256-bit encoding active)</div>
            <div class="encryption-info">All passwords are encrypted using advanced cryptographic algorithms</div>
        </div>
        
        <div class="warning">
            ⚠️ CONFIDENTIAL: This vault contains sensitive company credentials. Unauthorized access is prohibited.
        </div>
        
        <div class="hint-box">
            🤔 <strong>Security Assessment:</strong> These encrypted passwords look suspicious... Can you crack them? Copy any encrypted value and try to decode it. Are they really as secure as claimed?
        </div>
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="secure.php" class="defense-link">🛡️ Visit Secure Vault (Defense Demo)</a>
        </div>
    </div>
</body>
</html>
