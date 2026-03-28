# User Authentication System (Register → Login → Profile)

Full stack demo using:
- Frontend: HTML + Bootstrap + JavaScript (jQuery AJAX only)
- Backend: PHP
- Databases:
  - MySQL: registration/login credentials
  - MongoDB: profile details (age, DOB, contact)
  - Redis: session storage (token → user)

## Folder structure (as required)
- `html/` (pages)
- `css/` (styles)
- `js/` (jQuery AJAX scripts)
- `php/` (API endpoints)
- `config/` (DB connections)
- `sql/` (MySQL schema)

## 1) Prerequisites

Install and run:
- **PHP + Apache** (XAMPP/WAMP recommended)
- **MySQL Server**
- **MongoDB Community Server**
- **Redis** (Windows: use WSL, Docker, or a Windows Redis build)
- **Composer** (for PHP dependencies)

## 2) Create MySQL database + table

Run the SQL in `sql/schema.sql`.

If using phpMyAdmin:
- Open phpMyAdmin → SQL tab → paste `schema.sql` contents → Run

## 3) Install PHP dependencies (MongoDB + Redis clients)

From the project root:

```bash
composer install
```

## 4) Configure connections

Edit `config/config.php`:
- MySQL credentials
- MongoDB URI
- Redis host/port/password (if any)

## 5) Run locally

### Option A: Using XAMPP/WAMP (recommended)
- Place this project folder inside your web server root (example for XAMPP):
  - `C:\xampp\htdocs\user-auth-project`
- Start **Apache** and **MySQL** in XAMPP.
- Ensure MongoDB and Redis are running.

Open in browser:
- `http://localhost/user-auth-project/html/register.html`

### Option B: PHP built-in server (API + pages)

From the project root:

```bash
php -S localhost:8000
```

Then open:
- `http://localhost:8000/html/register.html`

## 6) How it works (session rules)
- On login, backend returns a **token**.
- Browser saves token in **localStorage** (`auth_session`).
- Backend stores token session in **Redis** with TTL.
- Profile APIs require `token` and validate it against Redis.

## Notes
- All backend calls are **AJAX only** (JSON POST).
- MySQL uses **prepared statements only** (PDO).
- MongoDB stores profile in collection `profiles` by `user_id`.

