<?php
session_start();
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // In real app, verify hash

    $sql = "SELECT * FROM users WHERE email='$email' AND role='customer'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password == $row['password']) { // Direct comparison as per requirement
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_role'] = $row['role'];
            $_SESSION['user_name'] = $row['full_name'];
            
            echo "<script>alert('Login successful'); window.location.href='dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <style>
        /* Copied styles from original HTML */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background-image: url('../assets/warehouse.jpg'); background-repeat: no-repeat; background-position: center; background-size: cover; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .login-container { background: #ffffff; width: 100%; max-width: 380px; padding: 35px 30px; border-radius: 18px; box-shadow: 0 15px 35px rgba(0,0,0,0.25); }
        .logo-container { display: flex; justify-content: center; margin-bottom: 12px; }
        .logo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; background: #f3f4f6; padding: 8px; }
        .login-container h2 { text-align: center; margin-bottom: 8px; color: #1f2933; }
        .login-container p { text-align: center; margin-bottom: 25px; color: #6b7280; font-size: 14px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 14px; color: #374151; }
        .form-group input { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #3b82f6; }
        .login-btn { width: 100%; padding: 13px; background: #22c55e; border: none; border-radius: 10px; color: white; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .login-btn:hover { background: #16a34a; }
        .footer-text { text-align: center; margin-top: 22px; font-size: 14px; color: #6b7280; }
        .footer-text span { color: #3b82f6; font-weight: 600; cursor: pointer; }
        .footer-text span:hover { text-decoration: underline; }
        @media (max-height: 600px) { .login-container { padding: 25px 20px; } .logo { width: 65px; height: 65px; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="../assets/dukan.png" alt="Dukan Logo" class="logo">
        </div>
        <h2>Customer Login</h2>
        <p>Login to continue shopping</p>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="customer@email.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="********">
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="footer-text">
            Don’t have an account?
            <span onclick="window.location.href='signup.php'">Create new account</span>
        </div>
    </div>
</body>
</html>
