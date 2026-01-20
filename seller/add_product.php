<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'seller') {
    header("Location: login.php");
    exit();
}

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $seller_id = $_SESSION['user_id'];

    // Image Upload
    $target_dir = "../assets/uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert into DB
            $image_path = "assets/uploads/" . basename($_FILES["image"]["name"]);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $specifications = mysqli_real_escape_string($conn, $_POST['specifications']);
            
            $sql = "INSERT INTO products (seller_id, name, description, price, image, category, specifications) 
                    VALUES ('$seller_id', '$name', '$description', '$price', '$image_path', '$category', '$specifications')";
            
            if (mysqli_query($conn, $sql)) {
                $success_msg = "Product added successfully!";
            } else {
                $error_msg = "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            $error_msg = "Sorry, there was an error uploading your file.";
        }
    } else {
        $error_msg = "File is not an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1f2933; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #374151; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; }
        .btn { width: 100%; padding: 12px; background: #22c55e; color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn:hover { background: #16a34a; }
        .message { text-align: center; margin-bottom: 15px; font-weight: bold; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<nav>
    <h2>Seller Panel</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_products.php">My Products</a>
        <a href="my_profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Add New Product</h2>
    
    <?php if ($success_msg) echo "<p class='message success'>$success_msg</p>"; ?>
    <?php if ($error_msg) echo "<p class='message error'>$error_msg</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label>Price (Rs)</label>
            <input type="number" name="price" required>
        </div>
        <div class="form-group">
            <label>Specifications</label>
            <textarea name="specifications" rows="5" placeholder="Enter product specifications..."></textarea>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="Electronics">Electronics</option>
                <option value="Fashion">Fashion</option>
                <option value="Home">Home</option>
                <option value="Beauty">Beauty</option>
                <option value="Sports">Sports</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="image" required>
        </div>
        <button type="submit" class="btn">Add Product</button>
    </form>
</div>

</body>
</html>
