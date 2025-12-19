# Ashesi Mechatronics Lab Inventory Manager
"Mecha-Lab Inventory Manager"
**Course:** Web Technologies (CS341) - Final Project  
**Student:** [Keli Kemeh]  
**ID:** [05282027]  

GITHUB REPOSITORY link: [https://github.com/foultraengineer9/ashesi-webtech-2025--finalproject-keli.kemeh]
YOUTUBE VIDEO DEMO LINK: [https://youtu.be/VRLPfd81HX4]

## Project Overview
A web-based inventory management system for the Ashesi Engineering Department ,the **Mecha-Lab Inventory Manager** is a web-based application designed to help the Ashesi Engineering department track lab equipment. It allows lab administrators and students to manage the borrowing and return of components such as Arduinos, Sensors, and Motors.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-F5788D?style=for-the-badge&logo=chart.js&logoColor=white)


## Live Deployment
**Access the live application here:** [https://mechalabinventory.infinityfree.me/]
                                      [https://mechalabinventory.infinityfree.me/]
                                      

## Key Features (CRUD)
* **Create:** Users can register; Admins can add new inventory items. Admins can register new components with unique Serial Numbers. Users can sign up.
  
* **Read:** Dashboard displays real-time availability of components. Student Dashboard: View real-time availability tables.Admin Dashboard: View Analytics Cards and Charts.
  
* **Update:** Edit component details; Change status from "Available" to "Borrowed". Admins can edit component details and change status (e.g., *Available* → *Borrowed*)
  
* **Delete:** Remove broken or lost items from the database.

## Project Structure
* `/actions`: PHP processing logic (Clean Code separation).
* `/db`: Contains the `mecha_lab_db.sql` file for database setup.
* `/assets`: CSS, JS, and Images.

## Validation & Security
* **Frontend:** JavaScript Regex validation for Serial Numbers (`MECH-XXXX-XX`).
* **Backend:** PHP `mysqli_real_escape_string` to prevent SQL Injection.
* * **Frontend:** JavaScript & Regex validation ensures Serial Numbers and Emails follow strict formats (`MECH-XXXX`, `@ashesi.edu.gh`).
* **Backend:** SQL Injection prevention using `mysqli_real_escape_string`.
* **Authentication:** Passwords are hashed using `password_hash()` (Bcrypt).
* **Access Control:** Session-based role protection blocks Students from accessing Admin pages.

## Project Structure
```bash
/ashesi-webtech-project
├── /actions       # PHP processing logic (Clean Code separation)
├── /admin         # Admin-only dashboard and pages
├── /assets        # CSS, JS, and Images
├── /db            # Database SQL export (mecha_lab_db.sql)
├── /settings      # Database connection configurations
├── /view          # Reusable UI components (Student Dashboard)
├── index.php      # Login Page
└── register.php   # Registration Page
