<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'seller') {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Delete Logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM products WHERE id='$id' AND seller_id='$seller_id'";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Product deleted successfully'); window.location.href='view_products.php';</script>";
    } else {
        echo "<script>alert('Error deleting product');</script>";
    }
}

// Fetch Products
$sql = "SELECT * FROM products WHERE seller_id='$seller_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .container { max-width: 1000px; margin: 40px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        h2 { color: #1f2933; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background-color: #f3f4f6; font-weight: 600; color: #374151; }
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .btn-delete { background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 12px; }
        .btn-delete:hover { background: #dc2626; }
        .btn-add { background: #3b82f6; color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; float: right; }
    </style>
</head>
<body>

<nav>
    <h2>Seller Panel</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="add_product.php">Add Product</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <a href="add_product.php" class="btn-add">+ Add New Product</a>
    <h2>My Products</h2>
    
    <table>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><img src="../<?php echo $row['image']; ?>" alt="Product"></td>
            <td><?php echo $row['name']; ?></td>
            <td>Rs. <?php echo $row['price']; ?></td>
            <td><?php echo substr($row['description'], 0, 50) . '...'; ?></td>
            <td>
                <a href="view_products.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    <?php if (mysqli_num_rows($result) == 0) echo "<p style='text-align:center; padding:20px;'>No products found.</p>"; ?>
</div>

</body>
</html>
