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

### Quick Start

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

   _This will automatically start all three services: Dashboard, A01, and A07._

   **Wait for the build process to complete** (may take 1-2 minutes on first run).

4. **Access the Dashboard**:
   Once you see "SYSTEMS RECREATED & ONLINE", open your browser and navigate to:

   👉 **http://localhost:8000**

   From here, you can click on the challenge cards to access:
   - **A01: Broken Access Control** → http://localhost:8001
   - **A07: Authentication Failures** → http://localhost:8007

5. **Stop the Lab**:
   When you're done, shut down all services and clean up:
   ```bash
   ./stop_lab.sh
   ```
   _This will stop all Docker containers and free up system resources._

---

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

### Challenge 1: A01 - Broken Access Control

**Port:** 8001 | **Difficulty:** Beginner | **Category:** Authorization Bypass

<details>
<summary><strong>📌 Click to Expand: What is This Challenge About?</strong></summary>

#### The Scenario

You've discovered a Content Management System (CMS) called "baserCMS" used by a company to manage their website. The application has an admin dashboard, but no one gave you a username or password.

#### The Vulnerability

**Broken Access Control** means the developer forgot to check if users are authorized before showing them sensitive pages. They assumed that "hiding" the admin panel URL would be enough security — it's not.

**Real-World Analogy:**
Imagine a bank vault with no lock. The bank owner says "We just won't tell anyone where it is." A criminal who knows where to look can walk right in.

#### Why This Matters

According to OWASP, Broken Access Control is the **#1** most critical web security risk. Examples include:

- Accessing another user's bank account by changing the account ID in the URL
- Viewing employee salary data without proper permissions
- Modifying or deleting content you shouldn't have access to

</details>

<details>
<summary><strong>🎮 Click to Expand: How to Complete This Challenge</strong></summary>

#### Your Mission

Access the administrative dashboard without logging in.

#### Step-by-Step Guide

1. Open http://localhost:8001 in your browser
2. You'll be redirected to `/dashboard.php`
3. Notice that no login screen appeared — you're already viewing admin content!
4. Explore the sidebar and click on different pages:
   - **Site Config**: Contains sensitive settings like admin email addresses
   - **Posts/Pages**: Allows you to create, edit, or delete website content
   - **Plugins/Themes**: Could be used to upload malicious code
   - **User Logs**: Shows activity that should be private

#### What You've Proven

By successfully accessing these pages, you've demonstrated that:

- The application doesn't verify user identity before showing admin pages
- An attacker could deface the website, steal data, or take over the system
- "Security by obscurity" (hiding URLs) doesn't work

</details>

<details>
<summary><strong>📁 Click to Expand: File Structure & Technical Details</strong></summary>

#### Directory: `challenges/A01_2025_Broken_Access_Control/`

**Configuration Files:**

- **`docker-compose.yml`**: Defines the web service running on port 8001
- **`Dockerfile`**: Sets up a PHP/Apache environment to run the application

**Source Code (`src/`):**

- **`index.php`**: Entry point that redirects to `dashboard.php`
- **`dashboard.php`**: Main admin interface showing system statistics and activity
- **`config.php`**: System settings page (email, debug mode, timezone)
- **`posts.php`**: Interface for managing blog posts
- **`pages.php`**: Interface for managing static pages
- **`plugins.php`**: Plugin management system (could be used to upload malware)
- **`themes.php`**: Theme customization panel
- **`widgets.php`**: Widget configuration
- **`cache.php`**: Cache management tools
- **`logs.php`**: System activity logs
- **`market.php`**: Marketplace for extensions
- **`upload.php`**: File upload functionality (high-risk feature)

**Supporting Files:**

- **`includes/header.php`**: Reusable header with navigation sidebar
- **`includes/footer.php`**: Reusable footer
- **`includes/sidebar.php`**: Admin navigation menu
- **`assets/css/theme.css`**: Styling for the dashboard

#### The Security Flaw

None of these PHP files check if a user session exists or if the user has admin privileges. They simply display content to anyone who requests them directly.

**What's Missing:**

```php
// This code should be at the top of EVERY admin page:
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
```

</details>

---

### Challenge 2: A07 - Authentication Failures

**Port:** 8007 | **Difficulty:** Intermediate | **Category:** Weak Authentication

<details>
<summary><strong>📌 Click to Expand: What is This Challenge About?</strong></summary>

#### The Scenario

You've found a logistics company's "Driver Portal" — a web application used by delivery drivers and dispatchers to track shipments and manage routes. There's a login page, but you don't have valid credentials.

#### The Vulnerability

**Authentication Failures** occur when login systems have weaknesses that allow attackers to bypass or crack them. This includes:

- Weak password requirements (allowing "password123")
- Hardcoded credentials left in the code by developers
- Broken password reset mechanisms
- Use of insecure hashing algorithms (like MD5) that can be cracked
- Missing account lockout after multiple failed attempts

**Real-World Analogy:**
Imagine a door with a cheap lock that can be picked in 30 seconds, or worse, a door where the key is hidden under the doormat with a note saying "Spare Key."

#### Why This Matters

Authentication failures are ranked **#7** on the OWASP Top 10. Real-world consequences include:

- Account takeovers leading to identity theft
- Unauthorized access to customer data
- Financial fraud through compromised payment systems
- Corporate espionage via breached employee accounts

</details>

<details>
<summary><strong>🎮 Click to Expand: How to Complete This Challenge</strong></summary>

#### Your Mission

Gain access to the "Driver Dashboard" or "Dispatcher Admin Panel" by exploiting weak authentication.

#### Hints & Strategy

1. Open http://localhost:8007 in your browser
2. Explore the public pages (Home, Track Package, Support) for clues:
   - Note the email format shown in the Support page
   - Look for any information about usernames or system details
3. Navigate to the **Driver Login** page
4. Try common username/password combinations:
   - `admin` / `admin`
   - `driver` / `drive123`
   - `admin` / `password`
   - Email addresses with weak passwords
5. If simple guessing doesn't work, consider:
   - Inspecting the source code of the login page
   - Looking for comments or hidden fields
   - Investigating if the system uses weak hashing (MD5)

#### Advanced Technique

This challenge may involve a **Type Juggling** or **Magic Hash** exploit. In PHP, weak comparison operators (`==`) can be tricked. For example:

- `md5("240610708") == "0e462097..."` (looks like scientific notation)
- PHP treats both sides as `0 == 0`, which is TRUE

#### What Success Looks Like

You'll be redirected to `/admin/dashboard.php` where you can see confidential shipment data, driver information, and dispatch controls.

</details>

<details>
<summary><strong>📁 Click to Expand: File Structure & Technical Details</strong></summary>

#### Directory: `challenges/A07_2025_Authentication_Failures/`

**Configuration Files:**

- **`docker-compose.yml`**: Defines the web service running on port 8007
- **`Dockerfile`**: Sets up a PHP/Apache environment

**Source Code (`src/`):**

**Public-Facing Files:**

- **`index.php`**: Homepage with company information and announcements
- **`track.php`**: Package tracking feature (public access)
- **`contact.php`**: Support contact form
- **`nav.php`**: Navigation bar component

**Authentication System:**

- **`login.php`**: The vulnerable login handler
  - Contains hardcoded credentials for testing
  - Uses weak MD5 hashing for password comparison
  - Vulnerable to type juggling attacks
  - Missing rate limiting (allows unlimited login attempts)

**Protected Area:**

- **`admin/dashboard.php`**: Target page showing:
  - Active shipments and their locations
  - Driver assignments and schedules
  - Dispatch communication tools
  - Sensitive company data

**Configuration & Utilities:**

- **`includes/config.php`**: Application settings including:
  - `APP_NAME`: Application title
  - `VERSION`: System version number
  - `ADMIN_RECOVERY_HASH`: MD5 hash for admin password (vulnerable!)
- **`includes/db.php`**: Database connection handler
- **`includes/utils.php`**: Helper functions like `sanitize()` for input cleaning

**Styling:**

- **`assets/style.css`**: Visual styling for the portal

#### The Security Flaw

The `login.php` file contains multiple weaknesses:

1. **Hardcoded Credentials:**

```php
if ($username === 'driver' && $password === 'drive123') {
    // Credentials visible in source code
}
```

2. **Weak Hashing:**

```php
$input_hash = md5($password);
if ($input_hash == ADMIN_RECOVERY_HASH) {
    // MD5 is broken and can be cracked
    // Using == instead of === allows type juggling
}
```

3. **No Account Lockout:**

- Unlimited login attempts allowed
- Enables brute force attacks

**How It Should Be Done:**

```php
// Use modern password hashing
$hashed = password_hash($password, PASSWORD_ARGON2ID);

// Verify with secure comparison
if (password_verify($input, $hashed)) {
    // Grant access
}

// Implement rate limiting
if (login_attempts > 5) {
    sleep(5); // Slow down attacker
}
```

</details>

---

## 🔍 Understanding the Impact

Each vulnerability demonstrated here has caused real data breaches:

**A01 - Broken Access Control:**

- 2019: First American Financial exposed 885 million records due to missing authorization checks
- 2020: Experian API allowed access to credit scores using any email address

**A07 - Authentication Failures:**

- 2021: Colonial Pipeline ransomware attack began with a compromised password
- 2022: Uber breach occurred via stolen employee credentials

---

## 🛠 Troubleshooting

<details>
<summary><strong>Port 8007 (A07) is not responding</strong></summary>

If you see "This site can't be reached" for A07:

1. Check if the container is running:

   ```bash
   docker ps -a
   ```

2. If it's missing, manually start it:

   ```bash
   cd challenges/A07_2025_Authentication_Failures
   docker-compose up -d --build --force-recreate
   ```

3. Verify it's now running:
   ```bash
   docker ps
   ```
   Look for a container on port `8007`

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

## 👨‍💻 Contributing

Found a bug or want to add more challenges? Contributions are welcome! Please ensure any new vulnerabilities are:

- Well-documented
- Isolated in containers
- Based on real-world vulnerability patterns

---

## 📜 License

This project is released for educational use. All vulnerabilities are intentionally created for learning purposes and do not represent real applications.
