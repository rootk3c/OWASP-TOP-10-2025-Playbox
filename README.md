# OWASP Top 10 (2025) Playbox - CTF Security Lab

This is a **Capture The Flag (CTF)** style security training environment where you can legally practice finding and exploiting real-world web vulnerabilities in a safe, isolated setting.

## 📖 What is This Project?

Think of this as a "hacking simulator" or video game with different levels. Each level is a vulnerable web application that looks and feels real, but exists only for educational purposes. Your goal is to find the security flaws (the "bugs") that allow you to access things you shouldn't be able to access.

**Why does this exist?**

- In the real world, hacking websites is **illegal** and punishable by law
- Developers and security professionals need a safe place to practice finding vulnerabilities
- This project demonstrates the OWASP Top 10 - the most critical security risks in web applications

**What will you learn?**

- How attackers think and operate
- Common coding mistakes that lead to security breaches
- The real-world impact of security vulnerabilities
- How to identify and fix these issues in your own code

## 🚀 Getting Started

### Prerequisites

- [Docker](https://www.docker.com/) & Docker Compose
- A terminal (Bash/Zsh recommended)

### Quick Start - Linux/Mac User's

1. **Navigate to the Project Folder**:
   Open your terminal and navigate to the project root directory:

   ```bash
   cd /path/to/OWASP-TOP-10-2025-Playbox
   ```

2. **Give Execute Permissions** (First Time Only):

   ```bash
   chmod +x start_lab.sh stop_lab.sh
   ```

   _On Windows, you can skip this step and use `start_lab.bat` instead._

3. **Start the Lab**:
   Run the start script to build and launch all containers:

   ```bash
   ./start_lab.sh
   ```

   _This will automatically start all services: Dashboard, A01, A04, and A07._

   **Wait for the build process to complete** (may take 1-2 minutes on first run).

4. **Access the Dashboard**:
   Once you see "SYSTEMS RECREATED & ONLINE", open your browser and navigate to:

   👉 **http://localhost:8000**

   From here, you can click on the challenge cards to access.

5. **Stop the Lab**:
   When you're done, shut down all services and clean up:
   ```bash
   ./stop_lab.sh
   ```
   _This will stop all Docker containers and free up system resources._

---

### Quick Start - Window's User

1. **Start the Lab**
   Click on the start_lab.bat file.

2. **Stop the Lab**
   Click on stop_lab.bat file.

## 🏗 Project Architecture

### Root Directory Files

- **`start_lab.sh` / `start_lab.bat`**: Scripts that start all Docker containers. They navigate to each challenge folder and run `docker-compose up` to build and launch the vulnerable applications.
- **`stop_lab.sh` / `stop_lab.bat`**: Scripts that shut down all containers and clean up resources using `docker-compose down`.

### Dashboard (`/dashboard`)

The central menu screen that links to all challenges.

- **`index.html`**: A styled landing page showing available challenges with their ports.
- **`docker-compose.yml`**: Configuration to run the dashboard on **Port 8000**.
- **`Dockerfile`**: Builds a lightweight Nginx web server to host the HTML file.

---

## 🎯 CTF Challenges

<table>
<tr>
<td width="50%" valign="top">

### [🔓 A01: Broken Access Control](challenges/A01_2025_Broken_Access_Control/)

**Status:** ✅ Available | **Port:** 8001 | **Difficulty:** 🟢 Beginner

Exploit missing authorization checks to access admin functionality without credentials. Demonstrates why "security by obscurity" fails.

**Flag:** Access the admin dashboard  
**Learn:** Authorization vs Authentication, IDOR vulnerabilities

[📖 View Challenge Details](challenges/A01_2025_Broken_Access_Control/)

</td>
<td width="50%" valign="top">

### 🔒 A02: Cryptographic Failures

**Status:** 🚧 Coming Soon | **Port:** TBD | **Difficulty:** 🟡 Intermediate

Challenge the weaknesses in cryptographic implementations.

**Flag:** TBD  
**Learn:** Encryption standards, Key management

---

</td>
</tr>

<tr>
<td width="50%" valign="top">

### 🔒 A03: Injection

**Status:** 🚧 Coming Soon | **Port:** TBD | **Difficulty:** 🟡 Intermediate

Exploit SQL, NoSQL, or command injection vulnerabilities.

**Flag:** TBD  
**Learn:** Input validation, Prepared statements

---

</td>
<td width="50%" valign="top">

### [🔐 A04: Cryptographic Failures](challenges/A04_Cryptographic_Failures/)

**Status:** ✅ Available | **Port:** 8004 | **Difficulty:** 🟢 Beginner

Crack a "Military Grade Encrypted" vault that actually uses Base64 encoding. Learn the critical difference between encoding and encryption.

**Flag:** `CTF{Encoding_Is_Not_Encryption_A04}`  
**Learn:** Encoding vs Encryption, AES-256, Proper key management

[📖 View Full Challenge Guide](challenges/A04_Cryptographic_Failures/README.md)

</td>
</tr>

<tr>
<td width="50%" valign="top">

### 🔒 A05: Security Misconfiguration

**Status:** 🚧 Coming Soon | **Port:** TBD | **Difficulty:** 🟡 Intermediate

Discover and exploit common misconfigurations in web applications.

**Flag:** TBD  
**Learn:** Secure defaults, Configuration hardening

---

</td>
<td width="50%" valign="top">

### 🔒 A06: Vulnerable Components

**Status:** 🚧 Coming Soon | **Port:** TBD | **Difficulty:** 🔴 Advanced

Identify and exploit known vulnerabilities in third-party components.

**Flag:** TBD  
**Learn:** Dependency scanning, CVE databases

---

</td>
</tr>

<tr>
<td width="50%" valign="top">

### [🔑 A07: Authentication Failures](challenges/A07_2025_Authentication_Failures/)

**Status:** ✅ Available | **Port:** 8007 | **Difficulty:** 🟡 Intermediate

Bypass weak authentication mechanisms using hardcoded credentials, weak hashing (MD5), and PHP type juggling exploits.

**Flag:** Access the Driver Admin Dashboard  
**Learn:** Secure password hashing, Type juggling, Rate limiting

[📖 View Challenge Details](challenges/A07_2025_Authentication_Failures/)

</td>
<td width="50%" valign="top">

### 🔒 A08: Software & Data Integrity

**Status:** 🚧 Coming Soon | **Port:** TBD | **Difficulty:** 🔴 Advanced

Exploit insecure deserialization and supply chain vulnerabilities.

**Flag:** TBD  
**Learn:** Object serialization, Digital signatures

---

</td>
</tr>

<tr>
<td width="50%" valign="top">

### 🔒 A09: Logging & Monitoring Failures

**Status:** 🚧 Coming Soon | **Port:** TBD | **Difficulty:** 🟡 Intermediate

Demonstrate attacks that go undetected due to insufficient logging.

**Flag:** TBD  
**Learn:** Security monitoring, Incident detection

---

</td>
<td width="50%" valign="top">

### 🔒 A10: Server-Side Request Forgery

**Status:** 🚧 Coming Soon | **Port:** TBD | **Difficulty:** 🔴 Advanced

Manipulate server-side requests to access internal resources.

**Flag:** TBD  
**Learn:** URL validation, Network segmentation

---

</td>
</tr>
</table>

## 🔍 Understanding the Impact

Each vulnerability demonstrated here has caused real data breaches:

**A01 - Broken Access Control:**

- 2019: First American Financial exposed 885 million records due to missing authorization checks
- 2020: Experian API allowed access to credit scores using any email address

**A04 - Cryptographic Failures:**

- 2012: LinkedIn breach exposed 6.5 million passwords due to unsalted SHA-1 hashing
- 2013: Adobe breach leaked 38 million passwords using weak ECB mode encryption
- 2014: Yahoo breach compromised 500 million accounts with outdated MD5 hashing
- 2018: Marriott exposed credit card and passport data of 500 million guests due to inadequate encryption

**A07 - Authentication Failures:**

- 2021: Colonial Pipeline ransomware attack began with a compromised password
- 2022: Uber breach occurred via stolen employee credentials

---

## 🛠 Troubleshooting

<details>
<summary><strong>Challenge not responding (Port 8001, 8004, or 8007)</strong></summary>

If you see "This site can't be reached" for any challenge:

1. Check if the container is running:

   ```bash
   docker ps -a
   ```

2. If it's missing or exited, manually start it:

   **For A01 (Port 8001):**

   ```bash
   cd challenges/A01_2025_Broken_Access_Control
   docker-compose up -d --build --force-recreate
   cd ../..
   ```

   **For A04 (Port 8004):**

   ```bash
   cd challenges/A04_Cryptographic_Failures
   docker-compose up -d --build --force-recreate
   cd ../..
   ```

   **For A07 (Port 8007):**

   ```bash
   cd challenges/A07_2025_Authentication_Failures
   docker-compose up -d --build --force-recreate
   cd ../..
   ```

3. Verify it's now running:
   ```bash
   docker ps
   ```
   Look for a container on the expected port

</details>

<details>
<summary><strong>Port already in use error</strong></summary>

If you see "port is already allocated":

1. Find what's using the port:

   ```bash
   lsof -i :8001  # Replace with the port number
   ```

2. Either:
   - Stop the conflicting service, OR
   - Edit the `docker-compose.yml` in that challenge folder and change the port mapping

</details>

---

## 📚 Learning Resources

- [OWASP Top 10 Official Documentation](https://owasp.org/www-project-top-ten/)
- [PortSwigger Web Security Academy](https://portswigger.net/web-security)
- [HackTheBox Academy](https://academy.hackthebox.com/)

---

## ⚠️ Legal Disclaimer

This project is for **educational purposes only**.

- ✅ Use it to learn about security vulnerabilities in a safe environment
- ✅ Practice your penetration testing skills
- ❌ Do NOT use these techniques on real websites without explicit written permission
- ❌ Do NOT expose these containers to the public internet

**Remember:** Unauthorized access to computer systems is illegal in most jurisdictions and can result in criminal prosecution.

---

## 📜 License

This project is released for educational use. All vulnerabilities are intentionally created for learning purposes and do not represent real applications.
