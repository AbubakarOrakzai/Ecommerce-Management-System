<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Update Status Logic
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    // Auto-update Link Payment Status
    $payment_update = "";
    if ($new_status == 'Delivered') {
        $payment_update = ", payment_status='Paid'";
    }

    $update_sql = "UPDATE orders SET status='$new_status' $payment_update WHERE id='$order_id'";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Order Status Updated!'); window.location.href='manage_orders.php';</script>";
    } else {
        echo "<script>alert('Error updating status');</script>";
    }
}

// Fetch Orders with User Info
$sql = "SELECT orders.*, users.full_name, users.email 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #ef4444); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; font-weight: 500; }
        .container { max-width: 1200px; margin: 40px auto; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1f2933; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9fafb; }
        .status-select { padding: 5px; border-radius: 5px; border: 1px solid #ccc; }
        .btn-update { background: #3b82f6; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; font-size: 12px; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 12px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; background: #f3f4f6; padding: 4px; }
    </style>
</head>
<body>

<nav>
    <div class="logo-container" style="margin-bottom:0;">
        <img src="/assets/dukan (1).png" alt="Logo" class="logo">
    </div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="manage_orders.php" style="font-weight: bold; text-decoration: underline;">Manage Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Manage Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Current Status</th>
            <th>Update Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td>
                <?php echo $row['full_name']; ?><br>
                <small style="color:gray;"><?php echo $row['email']; ?></small>
            </td>
            <td><?php echo date("d-m-Y H:i", strtotime($row['created_at'])); ?></td>
            <td>Rs. <?php echo number_format($row['total_amount']); ?></td>
            <td>
                <?php echo $row['payment_method']; ?><br>
                <span style="font-size:12px; color: <?php echo $row['payment_status']=='Paid'?'green':'red'; ?>; font-weight:bold;">
                    <?php echo $row['payment_status']; ?>
                </span>
            </td>
            <td>
                <span style="font-weight:bold; color: <?php echo $row['status']=='Pending'?'orange':'green'; ?>">
                    <?php echo $row['status']; ?>
                </span>
            </td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                    <select name="status" class="status-select">
                        <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Processing" <?php if($row['status']=='Processing') echo 'selected'; ?>>Processing</option>
                        <option value="Shipped" <?php if($row['status']=='Shipped') echo 'selected'; ?>>Shipped</option>
                        <option value="Delivered" <?php if($row['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
                        <option value="Cancelled" <?php if($row['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status" class="btn-update">Update</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
