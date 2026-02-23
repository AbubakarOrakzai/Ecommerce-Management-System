<?php
session_start();
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND role='customer'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password == $row['password']) {
            $_SESSION['user_id']   = $row['id'];
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
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Segoe UI", Arial, sans-serif;
}

body{
    min-height:100vh;
    background:url("../assets/warehouse.jpg") no-repeat center/cover;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.login-container{
    background:#fff;
    width:100%;
    max-width:380px;
    padding:35px 30px;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,0.25);
}

.logo-container{
    display:flex;
    justify-content:center;
    margin-bottom:15px;
}

.logo{
    width:75px;
    height:75px;
    border-radius:50%;
    background:#f3f4f6;
    padding:8px;
    object-fit:contain;
}

h2{
    text-align:center;
    color:#1f2933;
    margin-bottom:6px;
}

p{
    text-align:center;
    font-size:14px;
    color:#6b7280;
    margin-bottom:25px;
}

.form-group{
    margin-bottom:16px;
}

label{
    display:block;
    margin-bottom:6px;
    font-size:14px;
    color:#374151;
}

input{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #d1d5db;
    font-size:14px;
}

input:focus{
    outline:none;
    border-color:#ef4444;
}

.login-btn{
    width:100%;
    padding:13px;
    background:#ef4444;
    border:none;
    border-radius:10px;
    color:#fff;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    margin-top:10px;
}

.login-btn:hover{
    background:#dc2626;
}

.footer-text{
    text-align:center;
    margin-top:20px;
    font-size:14px;
    color:#6b7280;
}

.footer-text span{
    color:#ef4444;
    font-weight:600;
    cursor:pointer;
}

.footer-text span:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="login-container">
    <div class="logo-container">
        <img src="../assets/dukan.png" class="logo" alt="Logo">
    </div>

    <h2>Customer Login</h2>
    <p>Login to continue shopping</p>

    <form method="POST">
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
