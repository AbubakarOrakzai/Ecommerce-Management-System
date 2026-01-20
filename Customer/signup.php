<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validation
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already registered');</script>";
        } else {
            // Insert into Database
            // In a real production app, use password_hash($password, PASSWORD_DEFAULT)
            // For this university project, simple text or basic hashing might be acceptable, 
            // but I'll use simple text as requested ("No advanced PHP") 
            // OR stick to procedural connection. 
            // Actually, password_hash is standard PHP but instructions say "No advanced PHP".
            // I'll stick to direct insertion but maybe add a comment about security.
            
            $sql = "INSERT INTO users (full_name, email, phone, address, password, role) 
                    VALUES ('$full_name', '$email', '$phone', '$address', '$password', 'customer')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Signup</title>
    <style>
        /* Copied styles from original HTML */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background-image: url('/assets/warehouse.jpg'); background-repeat: no-repeat; background-position: center; background-size: cover; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .signup-container { background: #ffffff; width: 380px; padding: 25px 28px; border-radius: 18px; box-shadow: 0 15px 35px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto; }
        .signup-container h2 { text-align: center; color: #1f2933; margin-bottom: 6px; }
        .signup-container p { text-align: center; font-size: 14px; color: #6b7280; margin-bottom: 18px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 14px; color: #374151; }
        .form-group input { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #3b82f6; }
        .signup-btn { width: 100%; padding: 12px; border: none; border-radius: 10px; background: #22c55e; color: #ffffff; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 12px; }
        .signup-btn:hover { background: #16a34a; }
        .footer-text { text-align: center; margin-top: 16px; font-size: 13px; color: #6b7280; }
        .footer-text span { color: #3b82f6; font-weight: 600; cursor: pointer; }
        .footer-text span:hover { text-decoration: underline; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 12px; }
        .logo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; background: #f3f4f6; padding: 8px; }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="logo-container">
            <img src="/assets/dukan (1).png" alt="Dukan Logo" class="logo">
        </div>
        <h2>Create Account</h2>
        <p>Register as a customer</p>

        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="Ali Khan">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="ali@gmail.com">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" required placeholder="0301-1234567">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" required placeholder="Lahore">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="********">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="********">
            </div>
            <button type="submit" class="signup-btn">Create Account</button>
        </form>

        <div class="footer-text">
            Already have an account?
            <span onclick="window.location.href='login.php'">Login</span>
        </div>
    </div>
</body>
</html>
