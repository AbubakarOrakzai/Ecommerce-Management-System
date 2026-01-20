<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'seller') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$seller_id = $_SESSION['user_id'];

// Earnings Logic
$earnings_sql = "SELECT SUM(order_items.price * order_items.quantity) as total_earnings 
                 FROM order_items 
                 JOIN products ON order_items.product_id = products.id 
                 JOIN orders ON order_items.order_id = orders.id 
                 WHERE products.seller_id = '$seller_id' AND orders.payment_status = 'Paid'";
$earnings_result = mysqli_query($conn, $earnings_sql);
$earnings_row = mysqli_fetch_assoc($earnings_result);
$total_earnings = $earnings_row['total_earnings'] ? $earnings_row['total_earnings'] : 0;

// Sales Logic
$sales_sql = "SELECT COUNT(DISTINCT orders.id) as total_sales
              FROM order_items 
              JOIN products ON order_items.product_id = products.id 
              JOIN orders ON order_items.order_id = orders.id 
              WHERE products.seller_id = '$seller_id'";
$sales_result = mysqli_query($conn, $sales_sql);
$sales_row = mysqli_fetch_assoc($sales_result);
$total_sales = $sales_row['total_sales'] ? $sales_row['total_sales'] : 0;

// Best Selling Product Logic
$best_selling_sql = "SELECT products.name, SUM(order_items.quantity) as total_sold
                     FROM order_items 
                     JOIN products ON order_items.product_id = products.id 
                     WHERE products.seller_id = '$seller_id'
                     GROUP BY products.id
                     ORDER BY total_sold DESC
                     LIMIT 1";
$best_selling_result = mysqli_query($conn, $best_selling_sql);
$best_selling_product = mysqli_fetch_assoc($best_selling_result);

// Reviews Logic
$reviews_sql = "SELECT reviews.*, products.name as product_name, users.full_name 
                FROM reviews 
                JOIN products ON reviews.product_id = products.id 
                JOIN users ON reviews.user_id = users.id 
                WHERE products.seller_id = '$seller_id' 
                ORDER BY reviews.created_at DESC LIMIT 5";
$reviews_result = mysqli_query($conn, $reviews_sql);

// Recent Orders Logic
$recent_orders_sql = "SELECT orders.id, products.name as product_name, users.full_name, orders.created_at, orders.status, orders.payment_status 
                      FROM order_items 
                      JOIN orders ON order_items.order_id = orders.id 
                      JOIN products ON order_items.product_id = products.id 
                      JOIN users ON orders.user_id = users.id 
                      WHERE products.seller_id = '$seller_id' 
                      ORDER BY orders.created_at DESC LIMIT 5";
$recent_orders_result = mysqli_query($conn, $recent_orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
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
    <h2>Seller Panel - <?php echo $user_name; ?></h2>
    <div>
        <a href="../index.php">Home</a>
        <a href="view_products.php">My Products</a>
        <a href="add_product.php">Add Product</a>
        <a href="my_profile.php">My Profile</a>
        <a href="report.php">Generate Report</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="dashboard">

    <h3 class="section-title">Earnings Overview</h3>
    <div class="cards">
        <div class="card">
            <h4>Total Earnings</h4>
            <p>Rs. <?php echo number_format($total_earnings); ?></p>
        </div>
        <div class="card">
            <h4>Monthly Earnings</h4>
            <p>Rs. <?php echo number_format($total_earnings); ?></p> <!-- Simplified for now -->
        </div>
    </div>

    <h3 class="section-title">Sales Statistics</h3>
    <div class="cards">
        <div class="card">
            <h4>Total Orders</h4>
            <p><?php echo $total_sales; ?></p>
            <h3 class="section-title">Recent Reviews</h3>
    <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); margin-bottom: 35px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; text-align: left;">Product</th>
                    <th style="padding: 12px; text-align: left;">Customer</th>
                    <th style="padding: 12px; text-align: left;">Rating</th>
                    <th style="padding: 12px; text-align: left;">Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($reviews_result)) { ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;"><?php echo $row['product_name']; ?></td>
                    <td style="padding: 12px;"><?php echo $row['full_name']; ?></td>
                    <td style="padding: 12px; color: #f59e0b; font-weight: bold;"><?php echo $row['rating']; ?> ★</td>
                    <td style="padding: 12px; color: #4b5563;"><?php echo $row['comment']; ?></td>
                </tr>
                <?php } ?>
                <?php if (mysqli_num_rows($reviews_result) == 0) echo "<tr><td colspan='4' style='padding:12px; text-align:center;'>No reviews yet.</td></tr>"; ?>
            </tbody>
        </table>
    </div>

    <h3 class="section-title">Recent Orders</h3>
    <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; text-align: left;">Order ID</th>
                    <th style="padding: 12px; text-align: left;">Product</th>
                    <th style="padding: 12px; text-align: left;">Customer</th>
                    <th style="padding: 12px; text-align: left;">Date</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: left;">Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php while($order = mysqli_fetch_assoc($recent_orders_result)) { ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">#<?php echo $order['id']; ?></td>
                    <td style="padding: 12px;"><?php echo $order['product_name']; ?></td>
                    <td style="padding: 12px;"><?php echo $order['full_name']; ?></td>
                    <td style="padding: 12px;"><?php echo date("d M Y", strtotime($order['created_at'])); ?></td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: <?php echo $order['status'] == 'Pending' ? '#fef3c7' : '#dcfce7'; ?>; color: <?php echo $order['status'] == 'Pending' ? '#d97706' : '#166534'; ?>;">
                            <?php echo $order['status']; ?>
                        </span>
                    </td>
                    <td style="padding: 12px;">
                        <span style="font-size: 12px; font-weight: bold; color: <?php echo $order['payment_status'] == 'Paid' ? 'green' : 'red'; ?>;">
                            <?php echo $order['payment_status']; ?>
                        </span>
                    </td>
                </tr>
                <?php } ?>
                <?php if (mysqli_num_rows($recent_orders_result) == 0) echo "<tr><td colspan='6' style='padding:12px; text-align:center;'>No orders yet.</td></tr>"; ?>
            </tbody>
        </table>
    </div>

</div>
        <div class="card">
            <h4>Best Selling Product</h4>
            <p style="font-size: 20px;">
                <?php echo $best_selling_product ? $best_selling_product['name'] : 'N/A'; ?>
            </p>
            <small><?php echo $best_selling_product ? $best_selling_product['total_sold'] . ' Sold' : ''; ?></small>
        </div>
    </div>

</div>

</body>
</html>
