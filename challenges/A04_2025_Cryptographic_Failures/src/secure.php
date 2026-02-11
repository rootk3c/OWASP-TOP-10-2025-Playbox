<?php
// Secure Vault Pro - Proper Implementation Example
// Educational Purpose: Demonstrates correct cryptographic practices

// Secret encryption key (In production, this should be in environment variables)
define('ENCRYPTION_KEY', 'MySuperSecretKey_DoNotShare_12345678');

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
        'password' => 'CTF{This_Is_Real_Encryption_You_Cannot_Break_It}'
    ]
];

/**
 * Strong Encryption Function using AES-256-CBC
 * This is the CORRECT way to encrypt sensitive data
 */
function strong_encrypt($data, $key) {
    // Use AES-256-CBC - Industry standard encryption
    $cipher = "AES-256-CBC";
    
    // Generate a secure random Initialization Vector (IV)
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    
    // Perform encryption
    $ciphertext = openssl_encrypt($data, $cipher, $key, 0, $iv);
    
    // Combine IV and ciphertext (IV is needed for decryption)
    // In production, you'd store IV separately or prepend it
    $encrypted = base64_encode($iv . '::' . $ciphertext);
    
    return $encrypted;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Vault Pro - AES-256 Implementation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .nav-button {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }
        
        .nav-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-5px);
        }
        
        .nav-button::before {
            content: "← ";
        }
        
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 40px;
            margin-bottom: 30px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #059669;
            font-size: 2.2em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header h1::before {
            content: "🛡️";
            font-size: 1.2em;
        }
        
        .header .tagline {
            color: #6b7280;
            font-size: 0.95em;
            font-weight: 300;
        }
        
        .security-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            display: inline-block;
            margin: 20px 0;
            font-size: 0.9em;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        
        .security-badge::before {
            content: "✓ ";
            font-weight: bold;
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            background: #f9fafb;
            transition: all 0.3s ease;
        }
        
        tbody tr:hover {
            background: #f3f4f6;
            transform: scale(1.01);
        }
        
        tbody tr:not(:last-child) {
            border-bottom: 1px solid #e5e7eb;
        }
        
        td {
            padding: 18px 15px;
            color: #374151;
            font-family: 'Courier New', monospace;
        }
        
        td:first-child {
            color: #059669;
            font-weight: 600;
        }
        
        td:nth-child(2) {
            color: #2563eb;
            font-weight: 500;
        }
        
        td:last-child {
            color: #7c3aed;
            font-size: 0.85em;
            word-break: break-all;
            max-width: 400px;
        }
        
        .info-section {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 25px;
            margin-top: 30px;
            border-radius: 8px;
        }
        
        .info-section h2 {
            color: #059669;
            margin-bottom: 15px;
            font-size: 1.4em;
        }
        
        .info-section h3 {
            color: #047857;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 1.1em;
        }
        
        .info-section p {
            color: #374151;
            line-height: 1.7;
            margin-bottom: 12px;
        }
        
        .info-section ul {
            margin-left: 25px;
            color: #374151;
            line-height: 1.8;
        }
        
        .info-section li {
            margin-bottom: 8px;
        }
        
        .code-box {
            background: #1f2937;
            color: #d1d5db;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            overflow-x: auto;
            margin-top: 15px;
            border: 1px solid #374151;
        }
        
        .code-box .comment {
            color: #9ca3af;
        }
        
        .code-box .function {
            color: #60a5fa;
        }
        
        .code-box .string {
            color: #34d399;
        }
        
        .comparison-table {
            margin-top: 20px;
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .comparison-table th {
            background: #059669;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        .comparison-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .comparison-table tr:last-child td {
            border-bottom: none;
        }
        
        .bad {
            color: #dc2626;
            font-weight: 600;
        }
        
        .good {
            color: #059669;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="nav-button">Back to Vulnerable Vault</a>
        
        <div class="main-card">
            <div class="header">
                <h1>Secure Vault Pro (AES-256)</h1>
                <p class="tagline">Proper Cryptographic Implementation</p>
                <div class="security-badge">Real Military-Grade AES-256 Encryption</div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Encrypted Password (AES-256-CBC)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo strong_encrypt($user['password'], ENCRYPTION_KEY); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="main-card">
            <div class="info-section">
                <h2>🎓 Why Is This Secure?</h2>
                
                <h3>🔐 AES-256-CBC: Real Encryption</h3>
                <p>Unlike Base64 (which is just <strong>encoding</strong>), AES-256 is <strong>actual encryption</strong>:</p>
                <ul>
                    <li><strong>Requires a Secret Key:</strong> Without the encryption key, the data is mathematically impossible to decrypt in a reasonable timeframe</li>
                    <li><strong>Cryptographically Secure:</strong> Uses complex mathematical operations that cannot be reversed without the key</li>
                    <li><strong>Industry Standard:</strong> Used by governments, banks, and military organizations worldwide</li>
                    <li><strong>Random IV (Initialization Vector):</strong> Each encryption operation produces different output, even for the same input</li>
                    <li><strong>Brute-Force Resistant:</strong> Would take billions of years to crack with current technology</li>
                </ul>
                
                <h3>⚔️ Base64 vs AES-256: The Critical Difference</h3>
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Base64 (Vulnerable)</th>
                            <th>AES-256 (Secure)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Type</strong></td>
                            <td class="bad">Encoding (Reversible)</td>
                            <td class="good">Encryption (Key Required)</td>
                        </tr>
                        <tr>
                            <td><strong>Security</strong></td>
                            <td class="bad">Zero - Anyone can decode</td>
                            <td class="good">Military-Grade - Cannot be broken</td>
                        </tr>
                        <tr>
                            <td><strong>Key Required</strong></td>
                            <td class="bad">No - Public algorithm</td>
                            <td class="good">Yes - Secret key mandatory</td>
                        </tr>
                        <tr>
                            <td><strong>Command to Break</strong></td>
                            <td class="bad">echo "string" | base64 -d</td>
                            <td class="good">Impossible without key</td>
                        </tr>
                    </tbody>
                </table>
                
                <h3>💻 Implementation Code</h3>
                <p>Here's how the secure encryption is implemented:</p>
                <div class="code-box">
<span class="comment">// Strong Encryption using AES-256-CBC</span>
<span class="function">function</span> strong_encrypt($data, $key) {
    <span class="comment">// Use AES-256-CBC - Industry standard</span>
    $cipher = <span class="string">"AES-256-CBC"</span>;
    
    <span class="comment">// Generate secure random IV</span>
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    
    <span class="comment">// Perform encryption</span>
    $ciphertext = <span class="function">openssl_encrypt</span>($data, $cipher, $key, 0, $iv);
    
    <span class="comment">// Return encrypted data with IV</span>
    <span class="function">return</span> base64_encode($iv . '::' . $ciphertext);
}
                </div>
                
                <h3>✅ Security Best Practices Applied</h3>
                <ul>
                    <li>✓ Uses <code>openssl_encrypt()</code> instead of custom algorithms</li>
                    <li>✓ Generates random IV for each encryption operation</li>
                    <li>✓ Stores IV with ciphertext (IV is not secret, but required for decryption)</li>
                    <li>✓ Uses AES-256-CBC (256-bit key = 2^256 possible combinations)</li>
                    <li>✓ In production: Secret key should be in environment variables, not hardcoded</li>
                </ul>
                
                <h3>🎯 CTF Learning Objective</h3>
                <p><strong>A04: Cryptographic Failures</strong> teaches you that:</p>
                <ul>
                    <li><strong>Encoding ≠ Encryption:</strong> Base64, URL encoding, and hex encoding provide ZERO security</li>
                    <li><strong>Use Standard Libraries:</strong> Never create your own encryption algorithms</li>
                    <li><strong>Protect Your Keys:</strong> Even strong encryption is useless if keys are leaked</li>
                    <li><strong>Hash ≠ Encrypt:</strong> MD5/SHA1 are for integrity checks, not for storing reversible data</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
