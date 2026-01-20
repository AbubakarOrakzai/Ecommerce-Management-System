# E-Commerce Management System (University Project)

## Project Overview
This is a fully functional E-Commerce backend built with core PHP (Procedural) and MySQL. It includes User Authentication, Role-based Dashboards (Customer, Seller, Admin), and Product Management.

## How to Run
1.  **Start XAMPP**: Open XAMPP Control Panel and start **Apache** and **MySQL**.
2.  **Setup Database**:
    - Open your browser and go to `http://localhost/phpmyadmin`.
    - Create a new database named `ecommerce_db` (or just import the file).
    - Import the `database.sql` file located in the project root folder.
3.  **Run the Project**:
    - Open your browser and visit: `http://localhost/ecomerce/`

## Project Structure
- **index.php**: The main landing page requiring role selection.
- **db.php**: Database connection file (change credentials here if needed).
- **Customer/**: Contains customer login, signup, dashboard, and shop pages.
- **seller/**: Contains seller login, signup, dashboard, and product management pages.
- **Admin/**: Contains admin login and dashboard.
- **assets/**: Contains images and uploaded product photos.

## Key Features
- **Authentication**: Secure Login/Signup for Customers and Sellers.
- **Seller Panel**: Upload products with images, view and delete products.
- **Customer Panel**: View products (Shop), view profile.
- **Session Management**: Protected routes (cannot access dashboard without login).

## Credentials (for testing)
- **Admin**: admin@gmail.com | admin123
- **Seller**: (Register a new one)
- **Customer**: (Register a new one)

## Technologies Used
- HTML/CSS (Frontend)
- PHP (Backend)
- MySQL (Database)