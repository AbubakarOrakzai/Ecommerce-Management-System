<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Remove Item Logic
if (isset($_GET['action']) && $_GET['action'] == "remove") {
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['id'] == $_GET['id']) {
            unset($_SESSION['cart'][$key]);
            echo "<script>alert('Product has been Removed...!'); window.location.href='cart.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1f2933; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9fafb; }
        .btn-danger { background: #ef4444; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px; }
        .btn-checkout { display: block; width: 200px; margin: 20px auto; padding: 12px; background: #22c55e; color: white; text-align: center; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .total { text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px; }
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
        <a href="cart.php" style="font-weight: bold; text-decoration: underline;">Cart</a>
        <a href="my_profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Shopping Cart</h2>
    
    <table>
        <tr>
            <th>Product</th>
            <th>Image</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        
        <?php
        $total = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
        ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td><img src="../<?php echo $item['image']; ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
            <td>Rs. <?php echo number_format($item['price']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>Rs. <?php echo number_format($subtotal); ?></td>
            <td><a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="btn-danger">Remove</a></td>
        </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>Your cart is empty.</td></tr>";
        }
        ?>
    </table>

    <?php if (!empty($_SESSION['cart'])) { ?>
        <div class="total">Grand Total: Rs. <?php echo number_format($total); ?></div>
        <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
    <?php } ?>
</div>

</body>
</html>
