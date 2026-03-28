# 🔐 User Authentication System (Register → Login → Profile)



## 🚀 Features

* User Registration
* Secure Login System
* Session Management using Redis
* Profile Management using MongoDB
* AJAX-based API Communication

---

## 🛠️ Tech Stack

* **Frontend:** HTML, Bootstrap, JavaScript (jQuery AJAX)
* **Backend:** PHP
* **Databases:**

  * MySQL → User credentials
  * MongoDB → Profile details (age, DOB, contact)
  * Redis → Session storage (token → user)

---

## 📂 Folder Structure

* `html/` → Pages
* `css/` → Styles
* `js/` → jQuery AJAX scripts
* `php/` → API endpoints
* `config/` → Database connections
* `sql/` → MySQL schema

---

## ⚙️ Prerequisites

Install and run:

* PHP + Apache (XAMPP/WAMP)
* MySQL Server
* MongoDB Community Server
* Redis
* Composer

---

## 🧪 Setup Instructions

### 1. Create MySQL Database

Run the SQL file from:

```
sql/schema.sql
```

---

### 2. Install Dependencies

```bash
composer install
```

---

### 3. Configure Database

Edit:

```
config/config.php
```

Add:

* MySQL credentials
* MongoDB URI
* Redis configuration

---

### 4. Run Project

#### Option A: XAMPP (Recommended)

* Place project in:

```
C:\xampp\htdocs\user-auth-project
```

* Start Apache & MySQL
* Ensure MongoDB & Redis are running

Open:

```
http://localhost/user-auth-project/html/register.html
```

---

#### Option B: PHP Server

```bash
php -S localhost:8000
```

Open:

```
http://localhost:8000/html/register.html
```

---

## 🔐 How It Works

* User logs in → backend generates a **token**
* Token stored in **localStorage**
* Session stored in **Redis with TTL**
* Protected APIs validate token using Redis
* Profile data fetched from MongoDB

---

## 📌 Notes

* Uses AJAX (JSON-based communication)
* MySQL uses PDO prepared statements
* MongoDB stores profile in `profiles` collection

---

## 👩‍💻 Author

**Mageshwari Mariappan**

---

## 🔗 GitHub Repository

https://github.com/Mageshwari777/Fullstack-user-auth-system
