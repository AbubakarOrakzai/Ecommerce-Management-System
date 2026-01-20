<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1f2933; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #e0ecff; color: #1f3a8a; }
        .status { padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; }
        .status.Pending { background: #fef3c7; color: #d97706; }
        .status.Delivered { background: #dcfce7; color: #16a34a; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 12px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; background: white; padding: 4px; }
    </style>
</head>
<body>

<nav>
    <div class="logo-container" style="margin-bottom:0;">
        <img src="/assets/dukan (1).png" alt="Logo" class="logo">
    </div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="shop.php">Shop</a>
        <a href="cart.php">Cart</a>
        <a href="check_orders.php" style="font-weight: bold; text-decoration: underline;">My Orders</a>
        <a href="my_profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>My Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo date("d-m-Y H:i", strtotime($row['created_at'])); ?></td>
            <td>Rs. <?php echo number_format($row['total_amount']); ?></td>
            <td><span class="status <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
            <td>
                <?php if ($row['status'] == 'Pending') { ?>
                    <a href="cancel_order.php?id=<?php echo $row['id']; ?>" class="status Pending" style="text-decoration: none; cursor: pointer; background: #ef4444; color: white;" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</a>
                <?php } else { ?>
                    <span style="color: #999;">-</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    <?php if (mysqli_num_rows($result) == 0) echo "<p style='text-align:center; padding:20px;'>No orders found.</p>"; ?>
</div>

</body>
</html>
