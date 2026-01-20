<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'seller') {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch Analytics Data
// 1. Total Earnings
$earnings_sql = "SELECT SUM(order_items.price * order_items.quantity) as total_earnings 
                 FROM order_items 
                 JOIN products ON order_items.product_id = products.id 
                 JOIN orders ON order_items.order_id = orders.id 
                 WHERE products.seller_id = '$seller_id' AND orders.payment_status = 'Paid'";
$earnings_result = mysqli_query($conn, $earnings_sql);
$total_earnings = mysqli_fetch_assoc($earnings_result)['total_earnings'] ?? 0;

// 2. Total Sales Count
$sales_sql = "SELECT COUNT(DISTINCT orders.id) as total_sales
              FROM order_items 
              JOIN products ON order_items.product_id = products.id 
              JOIN orders ON order_items.order_id = orders.id 
              WHERE products.seller_id = '$seller_id'";
$sales_result = mysqli_query($conn, $sales_sql);
$total_sales = mysqli_fetch_assoc($sales_result)['total_sales'] ?? 0;

// 3. Best Selling Product
$best_selling_sql = "SELECT products.name, SUM(order_items.quantity) as total_sold
                     FROM order_items 
                     JOIN products ON order_items.product_id = products.id 
                     WHERE products.seller_id = '$seller_id'
                     GROUP BY products.id
                     ORDER BY total_sold DESC
                     LIMIT 1";
$best_selling_result = mysqli_query($conn, $best_selling_sql);
$best_selling_product = mysqli_fetch_assoc($best_selling_result);

// 4. All Orders History
$orders_sql = "SELECT orders.id, products.name as product_name, users.full_name, orders.created_at, orders.status, orders.payment_status, order_items.quantity, order_items.price 
               FROM order_items 
               JOIN orders ON order_items.order_id = orders.id 
               JOIN products ON order_items.product_id = products.id 
               JOIN users ON orders.user_id = users.id 
               WHERE products.seller_id = '$seller_id' 
               ORDER BY orders.created_at DESC";
$orders_result = mysqli_query($conn, $orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Report - <?php echo $user_name; ?></title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; padding: 40px; background: white; color: #1f2933; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; }
        .header h1 { margin-bottom: 5px; }
        .header p { color: #6b7280; }
        
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .summary-card { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; text-align: center; }
        .summary-card h3 { font-size: 14px; color: #6b7280; margin-bottom: 5px; }
        .summary-card p { font-size: 24px; font-weight: bold; color: #1f2933; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { padding: 10px; border: 1px solid #d1d5db; text-align: left; }
        th { background: #f3f4f6; }
        
        .print-btn { background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; margin-bottom: 20px; }
        .no-print { display: flex; justify-content: space-between; align-items: center; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <a href="dashboard.php" style="text-decoration: none; color: #3b82f6;">&larr; Back to Dashboard</a>
    <button onclick="window.print()" class="print-btn">Print / Save PDF</button>
</div>

<div class="header">
    <h1>Sales Performance Report</h1>
    <p>Seller: <strong><?php echo $user_name; ?></strong> | Date: <?php echo date("d M Y"); ?></p>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <h3>Total Earnings</h3>
        <p>Rs. <?php echo number_format($total_earnings); ?></p>
    </div>
    <div class="summary-card">
        <h3>Total Orders</h3>
        <p><?php echo $total_sales; ?></p>
    </div>
    <div class="summary-card">
        <h3>Best Selling Product</h3>
        <p><?php echo $best_selling_product ? $best_selling_product['name'] : 'N/A'; ?></p>
        <small><?php echo $best_selling_product ? $best_selling_product['total_sold'] . ' Sold' : ''; ?></small>
    </div>
</div>

<h3>Order History</h3>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Order ID</th>
            <th>Product</th>
            <th>Customer</th>
            <th>Qty</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Payment</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($orders_result)) { ?>
        <tr>
            <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>Rs. <?php echo number_format($row['price'] * $row['quantity']); ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['payment_status']; ?></td>
        </tr>
        <?php } ?>
        <?php if (mysqli_num_rows($orders_result) == 0) echo "<tr><td colspan='8' style='text-align:center;'>No orders found.</td></tr>"; ?>
    </tbody>
</table>

</body>
</html>
