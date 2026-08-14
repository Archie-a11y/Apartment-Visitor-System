# 🏢 Apartment Visitor System

A lightweight, multi-lingual, and responsive web application designed to manage, track, and verify visitor access in residential apartments or gated communities [index.php, visitors.sql]. The system allows residents to pre-register guests, generate secure visitor QR codes, and enables on-site security personnel to verify guest passes instantly [generate_qr.php, scan_verify.php].

---

## ✨ Features

* **Multi-lingual Interface:** Complete locale integration supporting English, Simplified Chinese, and Bahasa Melayu, allowing users to switch languages seamlessly across all views [lang.php, lang/en.php].
* **Resident Portal:** Dedicated self-service module for tenants to register personal accounts, maintain profiles, and log upcoming visitor schedules [owner_register.php, register.php].
* **Temporary QR Code Generation:** Generates temporary QR passes containing unique visit tokens [generate_qr.php].
* **Live Camera Verification Scanner:** Real-time web-camera scanning (using client-side `jsQR`) designed for security checkpoints to decode and evaluate visitor credentials on-the-spot [scan_verify.php].
* **Flexible Verification Queries:** Provides an alternative lookup interface for manual validation by searching guest identification cards [visitor_verify.php].
* **Structured Access Scheduling:** Dynamically validates appointment windows to prevent bookings older than the current date or scheduled more than 7 days in advance [generate_qr.php, register.php].
* **Print-Friendly Format:** Specialized styling templates format passes elegantly for printing or saving offline [style.css].

---

## 🏗️ Tech Stack

* **Frontend:** Responsive HTML5, CSS3 (with custom animations and print-media rules), JavaScript (ES6+), FontAwesome Icons [index.php, style.css]
* **Backend:** Native PHP (Session handling, prepared Statements, input sanitization) [db.php, lang.php]
* **Database:** MySQL [visitors.sql]
* **External Engines (Integration):**
  * **jsQR:** Pure JavaScript QR code reading library [scan_verify.php].
  * **PHPQRcode:** Lightweight QR code generator library (excluded in `.gitignore`, manually acquired during deployment) [generate_qr.php].

---

## 🖼️ Project Screenshots & User Guide

### 📍 Step 1: Role Selection & Language Portal
Access the central hub of the system, where residents, visitors, and security personnel can select their respective portals and preferred localized interface languages [index.php].
<img width="1366" height="647" alt="image" src="https://github.com/user-attachments/assets/3f0493a5-2d92-4665-8ae8-6b4eee8b218c" />


### 📍 Step 2: Tenant Registration & Authentication
Residents register secure profiles matching their apartment room numbers, real names, and contact details before logging into the dashboard [owner_login.php, owner_register.php].
<img width="1366" height="911" alt="image" src="https://github.com/user-attachments/assets/37a4a893-2853-4255-902e-655ac2996f14" />
<img width="1366" height="647" alt="image" src="https://github.com/user-attachments/assets/df247c94-d7aa-4708-bb80-891ad243176f" />


### 📍 Step 3: Visitor Schedule & QR Generator
Tenants schedule upcoming visits by filling in guest names, identities, and appointment dates, immediately generating a printable pass with a unique QR code [register.php, generate_qr.php].
<img width="1366" height="678" alt="image" src="https://github.com/user-attachments/assets/5d61d812-a217-44a2-b6ef-591f944db61c" />
<img width="1366" height="826" alt="image" src="https://github.com/user-attachments/assets/dada8164-489d-4a35-8335-6fb7b8bd79cb" />


### 📍 Step 4: Checkpoint Verification (Camera Scan & Manual Query)
Security guards check visitors' digital or printed passes using live camera streams or manually verify guest identification cards against system schedules [scan_verify.php, visitor_verify.php].
<img width="1366" height="647" alt="image" src="https://github.com/user-attachments/assets/1052fdd2-4af1-4f7e-9301-876fcf49d1c1" />


---

## 🛠️ Project Setup

### Prerequisites
* A PHP web server suite (e.g., XAMPP, WampServer) supporting **PHP 7.4+** or **PHP 8.0+**
* **MySQL / MariaDB** database instance [visitors.sql]
* A valid camera device connected to your server environment (for QR scanning functions) [scan_verify.php]

### Installation Steps

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Archie-a11y/Apartment-Visitor-System.git
   cd Apartment-Visitor-System
   ```

2. **Add Third-Party PHPQRcode Library:**
   Because `phpqrcode/` is ignored by `.gitignore`, you must manually include it [generate_qr.php]:
   * Download the library or copy a verified version of `qrlib.php` and its core dependencies.
   * Place the files under a directory named `phpqrcode/` in the root folder of the project.

3. **Initialize the Directories:**
   Create an empty folder named `qrcodes` in the project root to store generated image assets, and ensure your web server has write permissions to it [generate_qr.php, visitor_verify.php]:
   ```bash
   mkdir qrcodes
   ```

4. **Setup Database tables:**
   * Import the database structure from the `visitors.sql` file into your local database system [visitors.sql].
   * Ensure a database called `apartment_visitor_system` is correctly created [visitors.sql].

5. **Configure Credentials:**
   Edit the database credentials within `db.php` to correspond with your local environment settings [db.php]:
   ```php
   $servername = "localhost";
   $username = "YOUR_DB_USERNAME";
   $password = "YOUR_DB_PASSWORD";
   $dbname = "apartment_visitor_system"; // Adjusted to match the visitors.sql template
   ```

6. **Serve the Application:**
   Move your root workspace directory into your environment's public folder (e.g. `htdocs/`) and visit the portal:
   ```
   http://localhost/Apartment-Visitor-System/index.php
   ```
