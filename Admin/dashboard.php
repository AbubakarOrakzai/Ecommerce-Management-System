<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

    $user_name = $_SESSION['user_name'];

    // Stats Logic
    $users_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
    $products_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));
    $orders_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Copied Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav h2 { font-size: 20px; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; font-weight: 500; }
        nav a:hover { text-decoration: underline; }
        .dashboard { padding: 30px; }
        .section-title { margin-bottom: 15px; font-size: 18px; color: #1f2933; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 35px; }
        .card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .card h4 { font-size: 14px; color: #6b7280; margin-bottom: 8px; }
        .card p { font-size: 26px; font-weight: 700; color: #1f2933; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 12px; }
        .logo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; background: #f3f4f6; padding: 8px; }
    </style>
</head>
<body>

<nav>
    <div class="logo-container">
        <img src="../assets/dukan.png" alt="Dukan Logo" class="logo" style="width: 50px; height: 50px;">
    </div>
    <h2>Admin Panel - <?php echo $user_name; ?></h2>
    <div>
        <a href="../index.php">Home</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="dashboard">

    <h3 class="section-title">Overview</h3>
    <div class="cards">
        <div class="card">
            <h4>Total Users</h4>
            <p><?php echo $users_count; ?></p>
        </div>
        <div class="card">
            <h4>Total Products</h4>
            <p><?php echo $products_count; ?></p>
        </div>
        <div class="card">
            <h4>Total Orders</h4>
            <p><?php echo $orders_count; ?></p>
        </div>
    </div>

</div>

</body>
</html>
