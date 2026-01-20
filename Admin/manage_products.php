<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Delete Product Logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM products WHERE id='$id'";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Product deleted successfully'); window.location.href='manage_products.php';</script>";
    } else {
        echo "<script>alert('Error deleting product');</script>";
    }
}

// Fetch Products with Seller Info
$sql = "SELECT products.*, users.full_name as seller_name 
        FROM products 
        JOIN users ON products.seller_id = users.id 
        ORDER BY products.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #ef4444); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; font-weight: 500; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1f2933; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9fafb; }
        .btn-danger { background: #ef4444; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px; }
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
        <a href="manage_products.php" style="font-weight: bold; text-decoration: underline;">Manage Products</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Manage Products</h2>
    <table>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Seller</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><img src="../<?php echo $row['image']; ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
            <td><?php echo $row['name']; ?></td>
            <td>Rs. <?php echo number_format($row['price']); ?></td>
            <td><?php echo $row['seller_name']; ?></td>
            <td>
                <a href="manage_products.php?delete=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
