<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_amount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
} else {
    echo "<script>alert('Your cart is empty!'); window.location.href='shop.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST['payment_method'];
    $payment_status = ($payment_method == 'Credit Card') ? 'Paid' : 'Pending';
    
    // Simulate Card Payment Processing
    if ($payment_method == 'Credit Card') {
        // Here you would normally integrate Stripe/PayPal
        // For now, we simulate success
    }

    $sql_order = "INSERT INTO orders (user_id, total_amount, status, payment_method, payment_status) 
                  VALUES ('$user_id', '$total_amount', 'Pending', '$payment_method', '$payment_status')";
    
    if (mysqli_query($conn, $sql_order)) {
        $order_id = mysqli_insert_id($conn);

        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            mysqli_query($conn, $sql_item);
        }

        unset($_SESSION['cart']);
        echo "<script>alert('Order Placed Successfully via $payment_method!'); window.location.href='check_orders.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #1f2933; margin-bottom: 20px; }
        .total { text-align: center; font-size: 20px; margin-bottom: 20px; color: #1f2933; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        select, input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-pay { width: 100%; padding: 12px; background: #22c55e; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; font-weight: bold; }
        .btn-pay:hover { background: #16a34a; }
        .card-details { display: none; }
    </style>
    <script>
        function toggleCardDetails() {
            var method = document.getElementById("payment_method").value;
            var details = document.querySelector(".card-details");
            if (method === "Credit Card") {
                details.style.display = "block";
                document.getElementById("card_number").required = true;
                document.getElementById("expiry").required = true;
                document.getElementById("cvv").required = true;
            } else {
                details.style.display = "none";
                document.getElementById("card_number").required = false;
                document.getElementById("expiry").required = false;
                document.getElementById("cvv").required = false;
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Checkout</h2>
    <div class="total">Total to Pay: <strong>Rs. <?php echo number_format($total_amount); ?></strong></div>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Payment Method</label>
            <select name="payment_method" id="payment_method" onchange="toggleCardDetails()">
                <option value="COD">Cash on Delivery</option>
                <option value="Credit Card">Credit Card</option>
            </select>
        </div>

        <div class="card-details">
            <div class="form-group">
                <label>Card Number</label>
                <input type="text" id="card_number" placeholder="1234 5678 9876 5432">
            </div>
            <div style="display: flex; gap: 10px;">
                <div class="form-group" style="flex: 1;">
                    <label>Expiry</label>
                    <input type="text" id="expiry" placeholder="MM/YY">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>CVV</label>
                    <input type="text" id="cvv" placeholder="123">
                </div>
            </div>
        </div>

        <button type="submit" class="btn-pay">Place Order</button>
        <div style="text-align:center; margin-top:15px;"><a href="cart.php" style="color:#6b7280; text-decoration:none;">Back to Cart</a></div>
    </form>
</div>

</body>
</html>
