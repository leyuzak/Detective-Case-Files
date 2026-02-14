# Detective Case-Solving Web App (PHP + MySQL)

A detective-themed interactive web application built with **PHP**, **MySQL**, **HTML/CSS**, and **Vanilla JavaScript**.  
Users can register, log in, explore cases, analyze evidence, question suspects, take notes, accuse suspects, and track their detective progress.

---

## 📌 Overview

This project is a case-solving game platform where users act as detectives.  
Each case contains:

- Victim details
- Autopsy and laboratory reports
- Suspect profiles and statements
- Hints system
- Final accusation mechanism

Users can only solve each case once and their detective level increases based on solved cases.

---

## 🚀 Features

### 🔐 Authentication System
- User Registration (with hashed passwords)
- Login / Logout
- Account deletion
- Session-based authentication

### 🗂 Case System
- Case list page
- Difficulty badges
- Case details page:
  - Victim information
  - Autopsy reports
  - Lab results
  - Suspect statements
  - Incremental hint system
  - Detective notes (saved per user per case)
  - Accusation system (limited attempts)

### 🏆 User Profile
- Solved case history
- Total solved case count
- Automatic level ranking:
  - Çaylak Gözlemci
  - İz Sürücü
  - Gölge Dedektif
  - Karanlık Dosyalar Ustası
  - Efsanevi Dedektif

---

## 🛠 Tech Stack

- Backend: PHP (MySQLi)
- Database: MySQL / MariaDB
- Frontend: HTML, CSS, Vanilla JavaScript
- Server Environment: XAMPP / WAMP / MAMP / Apache + PHP + MySQL

---

## 📂 Project Structure

/assets  
&nbsp;&nbsp;&nbsp;&nbsp;/gif  
&nbsp;&nbsp;&nbsp;&nbsp;/image  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/katiller  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/maktuller  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/supheliler  

baglan.php  
dedektif.php  
davalar.php  
dava_detay.php  
dava_sonuc.php  
giris.php  
kayit.php  
cikis.php  
profile.php  
hesabi_sil.php  
dedektif.css  
dedektif.js  
dedektif.sql  

---

## ⚙️ Installation Guide (Local Setup)

### 1️⃣ Install Requirements
Install one of the following:
- XAMPP
- WAMP
- MAMP
- Any Apache + PHP + MySQL stack

---

### 2️⃣ Move Project Folder

Place the project inside your server directory:

Windows (XAMPP):
C:\xampp\htdocs\dedektif

Mac (MAMP):
/Applications/MAMP/htdocs/dedektif

Linux (XAMPP):
/opt/lampp/htdocs/dedektif

---

### 3️⃣ Setup Database

1. Open phpMyAdmin
2. Create a new database named:

dedektif

3. Select the database
4. Click Import
5. Upload dedektif.sql
6. Click Go

---

### 4️⃣ Configure Database Connection

Open baglan.php and verify:

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "dedektif";

Adjust credentials if needed.

---

### 5️⃣ Run the Application

Start Apache and MySQL, then open:

http://localhost/dedektif/dedektif.php

---

## 🔐 Security Notes

- Passwords are securely stored using PHP password_hash()
- Verified with password_verify()
- Prepared statements used to prevent SQL Injection
- Foreign key constraints ensure relational integrity

---

## 📈 Future Improvements

- Persist remaining attempt counts in database
- Admin dashboard for managing cases
- CSRF protection
- Improved validation & input sanitization
- Environment file (.env) for database credentials
- Dark/light theme toggle
- Leaderboard system
- REST API version

---

## 🎯 Educational Purpose

This project was developed as a learning-based full-stack PHP + MySQL web application demonstrating:

- Session management
- Database relations with foreign keys
- Secure authentication
- CRUD operations
- Frontend-backend integration
- Dynamic JavaScript interaction


---

**Made with curiosity and logic 🕵️‍♂️**
