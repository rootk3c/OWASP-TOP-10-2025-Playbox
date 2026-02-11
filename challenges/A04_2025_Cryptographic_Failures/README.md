# A04: Cryptographic Failures - CTF Challenge

**Port:** 8004 | **Difficulty:** Beginner | **Category:** Sensitive Data Exposure

---

## 📌 What is This Challenge About?

### The Scenario

You've been hired to audit a "Secure Password Vault" built by a startup company. The developers proudly claim they use "Military Grade Encryption" to protect all user passwords in their system. However, upon logging into the administrative dashboard, you notice the "encrypted" passwords look strangely familiar...

### The Vulnerability

**Cryptographic Failures** (formerly known as Sensitive Data Exposure in OWASP Top 10 2017) occur when sensitive data like passwords, credit card numbers, health records, or personal information are not properly protected using strong cryptographic algorithms.

**The Critical Mistake in This Challenge:**
The developers confused **encoding** with **encryption** — two fundamentally different concepts:

- **Encoding (Base64, Hex, URL encoding):** Designed to format/transport data, not protect it. It's reversible by anyone without any key. Think of it as translating English to Spanish — anyone who knows Spanish can read it.

- **Encryption (AES, RSA):** Designed to secure data using mathematical algorithms. It's irreversible without a secret key. Think of it as locking a document in a safe — only someone with the key can open it.

**Real-World Analogy:**
Imagine a bank that "protects" customer account numbers by writing them backwards. Sure, they look different at first glance (123456 becomes 654321), but anyone who figures out the pattern can instantly reverse it. That's encoding. Real encryption would be like scrambling those numbers with a secret key that only authorized people possess.

### Why This Matters

According to OWASP, Cryptographic Failures are ranked **#2** on the Top 10 list (up from #3 in 2017). Real-world breaches include:

- **LinkedIn (2012):** 6.5 million passwords exposed due to unsalted SHA-1 hashing
- **Adobe (2013):** 38 million passwords leaked using weak ECB mode encryption
- **Yahoo (2014):** 500 million accounts compromised with MD5 hashing (outdated algorithm)
- **Marriott (2018):** Credit card numbers and passport details of 500 million guests exposed

The consequences include:

- Identity theft and financial fraud
- Medical data breaches leading to blackmail
- Corporate espionage and intellectual property theft
- Regulatory fines (GDPR penalties up to €20 million)

---

## 🎮 How to Complete This Challenge

### Your Mission

Recover the `super_admin` password (which contains the CTF flag) from the supposedly "secure" password vault.

### Step-by-Step Exploitation Guide

#### 1. Access the Vault

- Open **http://localhost:8004** in your browser
- You'll see a professional-looking interface called "SecureVault Pro"
- Notice the badge claiming "Military Grade Encryption Active"

#### 2. Observe the Encrypted Passwords

- Look at the "Encrypted Password" column in the table
- Focus on the `super_admin` user's password
- Notice anything suspicious about the format?
- **Key Clue:** The string ends with `==` (equals signs)

#### 3. Recognize the Pattern

- In computer science, `==` at the end is a signature of **Base64 padding**
- Base64 encoding pads strings to ensure they're multiples of 4 characters
- This is a dead giveaway that encoding (not encryption) was used!

#### 4. Decode the "Encrypted" Password

**Method 1: Using Terminal (Mac/Linux)**

```bash
echo "PASTE_THE_ENCRYPTED_STRING_HERE" | base64 -d
```

**Method 2: Using Online Tools**

- Go to https://www.base64decode.org/
- Paste the encrypted string
- Click "Decode"

**Method 3: Using Python**

```python
import base64
encoded = "PASTE_HERE"
decoded = base64.b64decode(encoded).decode('utf-8')
print(decoded)
```

#### 5. Capture the Flag!

- The decoded output will reveal: `CTF{Encoding_Is_Not_Encryption_A04}`
- You've successfully demonstrated that Base64 provides **zero security**!

### 🛡️ Bonus: Explore the Secure Implementation

After capturing the flag, click the **"🛡️ Visit Secure Vault (Defense Demo)"** button to see how it should be done:

1. Notice the passwords are now truly unreadable
2. Try to decode them — you'll fail because they use **AES-256-CBC encryption**
3. Read the educational section to understand:
   - Why AES-256 is secure (requires a secret key)
   - How initialization vectors (IVs) add randomness
   - The difference between encoding, hashing, and encryption
   - Security best practices for storing sensitive data

### What You've Proven

By completing this challenge, you've demonstrated:

- The critical difference between encoding and encryption
- How to recognize Base64 encoding (look for `=` padding and character set)
- Why "security by obscurity" fails
- The importance of using industry-standard cryptographic libraries

---

## 📁 File Structure & Technical Details

### Directory: `challenges/A04_Cryptographic_Failures/`

**Configuration Files:**

- **`docker-compose.yml`**: Defines the web service running on port 8004
- **`Dockerfile`**: Sets up a PHP 8.0 with Apache environment

**Source Code (`src/`):**

#### Vulnerable Implementation (index.php)

The main page that demonstrates the cryptographic failure.

**Key Components:**

- **Mock Database:** Array containing 3 users including `super_admin` with the flag
- **Vulnerable Function:** `secure_encrypt()` that only uses Base64
- **UI/Styling:** Professional dark theme to make the flaw less obvious
- **False Security Claims:** Badges and messages claiming "Military Grade Encryption"

**The Security Flaw:**

```php
// BAD PRACTICE: This is NOT encryption!
// TODO: Base64 provides sufficient obfuscation for internal tools.
function secure_encrypt($data) {
    return base64_encode($data);
}

// Usage in the application:
echo secure_encrypt($user['password']); // Displays encoded password
```

**Why This Is Vulnerable:**

1. **No Secret Key Required:** Anyone can decode Base64 without authentication
2. **Reversible by Design:** Base64 is meant for data transport, not security
3. **Recognizable Pattern:** The `=` padding makes it obvious
4. **False Sense of Security:** Developers think they've protected the data

#### Secure Implementation (secure.php)

Educational page showing the **correct** way to protect sensitive data.

**The Proper Solution:**

```php
// BEST PRACTICE: Use industry-standard encryption
function strong_encrypt($data, $key) {
    $cipher = "AES-256-CBC";

    // Generate a secure random IV (Initialization Vector)
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);

    // Perform actual encryption
    $ciphertext = openssl_encrypt($data, $cipher, $key, 0, $iv);

    // Return encrypted data with IV
    return base64_encode($iv . '::' . $ciphertext);
}
```

**Why This IS Secure:**

1. **Secret Key Required:** Without the key, decryption is mathematically impossible
2. **AES-256-CBC:** Industry standard used by governments and banks
3. **Random IV:** Ensures same plaintext produces different ciphertext each time
4. **OpenSSL Library:** Uses battle-tested, peer-reviewed algorithms
5. **Brute-Force Resistant:** Would take billions of years to crack with current technology

---

## 📊 Comparison: Base64 vs AES-256

| Feature                | Base64 (Vulnerable)        | AES-256 (Secure)       |
| ---------------------- | -------------------------- | ---------------------- |
| **Type**               | Encoding                   | Encryption             |
| **Reversible?**        | Yes, by anyone             | Only with secret key   |
| **Security Level**     | 0% - No protection         | Military-grade         |
| **Key Required?**      | No                         | Yes (mandatory)        |
| **Use Case**           | Data formatting            | Data protection        |
| **Command to Break**   | `echo "text" \| base64 -d` | Impossible without key |
| **Real-World Example** | Email attachments          | Banking apps, VPNs     |

---

## 🎓 Educational Resources

The **secure.php** page includes:

- Detailed explanation of why AES-256 is secure
- Side-by-side comparison with Base64
- Actual code snippets showing proper implementation
- Discussion of keys, IVs, and cipher modes
- Security best practices checklist
- Real-world breach case studies

---

## ⚠️ Common Developer Mistakes

### 1. Confusing Encoding with Encryption

- Using Base64, Hex, or URL encoding for sensitive data
- Not understanding the fundamental difference

### 2. Custom Encryption Algorithms

- Writing homemade encryption ("rolling your own crypto")
- Not using vetted libraries like OpenSSL

### 3. Weak or Outdated Algorithms

- Using MD5 or SHA-1 for passwords (these are hashes, not encryption)
- Using DES or RC4 (broken algorithms)

### 4. Improper Key Management

- Hardcoding keys in source code
- Not rotating keys regularly
- Storing keys alongside encrypted data

### 5. Missing Encryption Entirely

- Storing passwords in plain text
- Transmitting sensitive data over HTTP

---

## ✅ How to Fix These Issues

### Use Strong, Standard Algorithms

- **AES-256** for symmetric encryption
- **RSA-2048+** for asymmetric encryption
- **Argon2 or bcrypt** for password hashing

### Proper Key Management

- Store keys in environment variables or key vaults
- Use different keys for different purposes
- Implement key rotation policies

### Use Established Libraries

- OpenSSL, Libsodium, or NaCl
- Never implement your own cryptographic algorithms

### Add Multiple Layers

- Encrypt at rest (database)
- Encrypt in transit (HTTPS/TLS)
- Encrypt at application level for critical data

### Regular Security Audits

- Review encryption implementations
- Keep libraries updated
- Follow OWASP guidelines

---

## 🔗 Additional Resources

- [OWASP Cryptographic Failures](https://owasp.org/Top10/A02_2021-Cryptographic_Failures/)
- [CWE-327: Use of a Broken or Risky Cryptographic Algorithm](https://cwe.mitre.org/data/definitions/327.html)
- [PHP OpenSSL Documentation](https://www.php.net/manual/en/book.openssl.php)
- [Choosing the Right Cryptography Library](https://paragonie.com/blog/2015/11/choosing-right-cryptography-library-for-your-php-project-guide)

---

## 🏆 CTF Learning Objectives

After completing this challenge, you should understand:

✅ The critical difference between **encoding**, **hashing**, and **encryption**  
✅ How to recognize Base64 encoding and decode it  
✅ Why proper encryption requires a secret key  
✅ The importance of using industry-standard cryptographic libraries  
✅ How to implement AES-256-CBC encryption in PHP  
✅ The real-world impact of cryptographic failures

---

**Remember:** In production systems, never trust data just because it "looks encrypted." Always verify that proper encryption algorithms with appropriate key management are in use!
