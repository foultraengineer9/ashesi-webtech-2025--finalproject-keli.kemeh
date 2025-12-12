# Ashesi Mechatronics Lab Inventory Manager
**Course:** Web Technologies (CS341) - Final Project  
**Student:** [Keli Kemeh]  
**ID:** [05282027]  

## Project Overview
The **Mecha-Lab Inventory Manager** is a web-based application designed to help the Ashesi Engineering department track lab equipment. It allows lab administrators and students to manage the borrowing and returning of components like Arduinos, Sensors, and Motors.

## Live Deployment
**Access the live application here:** []

## Key Features (CRUD)
* **Create:** Users can register; Admins can add new inventory items.
* **Read:** Dashboard displays real-time availability of components.
* **Update:** Edit component details; Change status from "Available" to "Borrowed".
* **Delete:** Remove broken or lost items from the database.

## Project Structure
* `/actions`: PHP processing logic (Clean Code separation).
* `/db`: Contains the `mecha_lab_db.sql` file for database setup.
* `/includes`: Header and Footer framing templates.
* `/assets`: CSS, JS, and Images.

## Validation & Security
* **Frontend:** JavaScript Regex validation for Serial Numbers (`MECH-XXXX-XX`).
* **Backend:** PHP `mysqli_real_escape_string` to prevent SQL Injection.
