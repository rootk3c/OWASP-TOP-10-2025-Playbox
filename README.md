# OWASP Top 10 (2025) Playbox

This project is a containerized security lab environment designed to demonstrate and practice exploiting vulnerabilities from the OWASP Top 10 (2025). It uses Docker to spin up isolated web applications, each intentionally vulnerable to specific security flaws.

## 🚀 Getting Started

### Prerequisites

- [Docker](https://www.docker.com/) & Docker Compose
- A terminal (Bash/Zsh recommended)

### Quick Start

1. **Start the Lab**:
   Run the start script to build and launch all containers.

   ```bash
   ./start_lab.sh
   ```

   _This will force recreate the containers to ensure a clean state._

2. **Access the Dashboard**:
   Open you browser and navigate to:
   👉 **http://localhost:8000**

   From here, you can launch the individual challenges.

3. **Stop the Lab**:
   To shut down all services and clean up:
   ```bash
   ./stop_lab.sh
   ```

---

## 🏗 Project Structure & File Guide

### 1. Root Directory

The control center for the lab environment.

- **`start_lab.sh` / `start_lab.bat`**: Orchestration scripts. They navigate into each directory (`dashboard`, `challenges/A01...`, etc.), and run `docker-compose up -d --force-recreate` to build and start the services.
- **`stop_lab.sh` / `stop_lab.bat`**: Tear-down scripts. They run `docker-compose down` in each directory to stop containers and remove networks.

### 2. Dashboard (`/dashboard`)

Serves as the central landing page for the lab.

- **`index.html`**: The main HTML file containing the visually appealing grid layout linking to the running challenges.
- **`Dockerfile`**: Builds a simple Nginx or Apache server to host the static HTML.
- **`docker-compose.yml`**: Configures the dashboard service to run on **Port 8000**.

### 3. Challenge: A01 - Broken Access Control

Located in: `challenges/A01_2025_Broken_Access_Control/`
**Goal**: Access administrative functionality without proper authorization.
**URL**: http://localhost:8001

**Key Files (`src/`):**

- **`index.php`**: Automatically redirects users to `dashboard.php`.
- **`dashboard.php`**: The main administrative interface showing mocked stats. The vulnerability lies in the fact that this page might be accessible directly without a valid session.
- **`users.php` / `logs.php` / `config.php`**: Simulated administrative pages that should be protected but might be directly accessible (IDOR / Missing Function Level Access Control).
- **`includes/`**: Helper PHP files for layout (`header.php`, `footer.php`, `sidebar.php`).

### 4. Challenge: A07 - Authentication Failures

Located in: `challenges/A07_2025_Authentication_Failures/`
**Goal**: Hijack the "Driver Portal" or "Dispatcher" account using weak authentication mechanisms.
**URL**: http://localhost:8007

**Key Files (`src/`):**

- **`index.php`**: Public landing page for the "Driver Portal".
- **`login.php`**: The core vulnerable file. It handles user authentication.
  - **Logic Analysis**: It likely contains hardcoded credentials, weak hashing algorithms (like MD5), or logic flaws that allow bypassing the login check.
- **`admin/dashboard.php`**: The protected target page. Successful exploitation of `login.php` should grant access here.
- **`includes/config.php`**: Configuration settings, possibly containing leaked secrets or default definitions.
- **`includes/utils.php`**: Helper functions for sanitization or database connection.

---

## ⚠️ Disclaimer

This project is for **educational purposes only**. The applications contained herein are intentionally flawed. Do not run this environment on a public server or expose the ports to the internet.

