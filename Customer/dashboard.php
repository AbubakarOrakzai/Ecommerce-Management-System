<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        /* Copied Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; color: white; }
        .logo-container { display: flex; align-items: center; gap: 12px; }
        .logo { width: 46px; height: 46px; border-radius: 50%; background: white; padding: 4px; object-fit: cover; }
        nav h2 { font-size: 20px; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .dashboard { padding: 30px; }
        .section-title { margin-bottom: 15px; font-size: 18px; color: #1f2933; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 35px; }
        .card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .profile-card { display: flex; gap: 20px; align-items: center; }
        .profile-pic { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #3b82f6; }

        .table-container { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); margin-bottom: 35px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; font-size: 14px; text-align: left; }
        th { background: #e0ecff; color: #1f3a8a; }
        tr:not(:last-child) { border-bottom: 1px solid #e5e7eb; }
        .view-btn { padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .footer { background: #1f2933; color: #e5e7eb; padding: 40px 30px 20px; margin-top: 60px; }
        .footer-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px; }
        .footer h3 { margin-bottom: 12px; font-size: 16px; color: #ffffff; }
        .footer p, .footer a { font-size: 14px; color: #d1d5db; text-decoration: none; line-height: 1.6; }
        .footer a:hover { color: #3b82f6; }
        .footer-bottom { text-align: center; border-top: 1px solid #374151; margin-top: 30px; padding-top: 15px; font-size: 13px; color: #9ca3af; }
    </style>
</head>
<body>

<nav>
    <div class="logo-container">
        <img src="../assets/dukan.png" alt="Logo" class="logo">
        <h2>Customer Dashboard</h2>
    </div>
    <div>
        <a href="../index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="check_orders.php">My Orders</a>
        <a href="my_profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="dashboard">

    <!-- Search Section -->
    <div style="margin-bottom: 30px; text-align: center;">
        <form method="GET" action="shop.php" style="display: inline-flex; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 30px; overflow: hidden;">
            <input type="text" name="search" placeholder="Search for items..." style="padding: 12px 20px; width: 300px; border: none; outline: none; font-size: 16px;">
            <button type="submit" style="padding: 12px 25px; background: #3b82f6; color: white; border: none; font-weight: bold; cursor: pointer; font-size: 16px;">Search</button>
        </form>
    </div>

    <h3 class="section-title">My Profile</h3>
    <div class="cards">
        <div class="card profile-card">
            <div>
                <?php 
                    $profile_src = !empty($user['profile_pic']) ? "../" . $user['profile_pic'] : "https://via.placeholder.com/150";
                ?>
                <img id="profileImage" src="<?php echo $profile_src; ?>" class="profile-pic">
            </div>
            <div>
                <p><strong>Name:</strong> <?php echo $user['full_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
                <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
                <a href="my_profile.php" class="upload-btn" style="text-decoration: none;">Edit Profile</a>
            </div>
        </div>
    </div>

    <!-- Static Content for now -->
    <h3 class="section-title">Recent Orders</h3>
    <div class="table-container">
        <table>
            <tr>
                <th>Order ID</th>
                <th>Items</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php
            $order_sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 5";
            $order_result = mysqli_query($conn, $order_sql);

            if (mysqli_num_rows($order_result) > 0) {
                while ($order = mysqli_fetch_assoc($order_result)) {
            ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td>
                    <?php 
                    $oid = $order['id'];
                    $items_sql = "SELECT p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = '$oid'";
                    $items_result = mysqli_query($conn, $items_sql);
                    while ($item = mysqli_fetch_assoc($items_result)) {
                        $img_src = !empty($item['image']) ? "../" . $item['image'] : "https://via.placeholder.com/50";
                        echo "<div style='display: flex; align-items: center; gap: 8px; margin-bottom: 4px;'>";
                        echo "<img src='$img_src' style='width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;'>";
                        echo "<span style='font-size: 13px;'>" . htmlspecialchars($item['name']) . "</span>";
                        echo "</div>";
                    }
                    ?>
                </td>
                <td><?php echo date("d-m-Y", strtotime($order['created_at'])); ?></td>
                <td><span style="padding: 4px 8px; border-radius: 4px; background: <?php echo $order['status'] == 'Pending' ? '#fef3c7' : '#dcfce7'; ?>; color: <?php echo $order['status'] == 'Pending' ? '#d97706' : '#166534'; ?>; font-size: 12px; font-weight: bold;"><?php echo $order['status']; ?></span></td>
                <td>Rs. <?php echo number_format($order['total_amount']); ?></td>
                <td>
                    <button class="view-btn" onclick="window.location.href='check_orders.php'">View</button>
                    <?php if ($order['status'] == 'Pending') { ?>
                        <a href="cancel_order.php?id=<?php echo $order['id']; ?>" class="view-btn" style="background: #ef4444; text-decoration: none; display: inline-block; text-align: center; margin-left: 5px;" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</a>
                    <?php } ?>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No orders yet.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<footer class="footer">
    <div class="footer-container">
        <div>
            <h3>E-Commerce Store</h3>
            <p>Providing a reliable platform for product-based companies in Pakistan.</p>
        </div>
        <div>
            <h3>Quick Links</h3>
            <p><a href="../index.php">Home</a></p>
            <p><a href="dashboard.php">Dashboard</a></p>
        </div>
        <div>
            <h3>Support</h3>
            <p>Email: support@ecommerce.com</p>
        </div>
    </div>
    <div class="footer-bottom">© 2026 E-Commerce Store | All Rights Reserved</div>
</footer>

</body>
</html>
