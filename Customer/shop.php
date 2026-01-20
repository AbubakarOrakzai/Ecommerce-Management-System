<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Add to Cart Logic
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $quantity = 1;

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            $_SESSION['cart'][$key]['quantity'] += 1;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $item_array = array(
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => $quantity
        );
        $_SESSION['cart'][] = $item_array;
    }

    echo "<script>alert('Item added to cart!');</script>";
}

// Fetch all products
// Fetch products based on Category
// Fetch products based on Category and Search
$where_clauses = [];

if (isset($_GET['category'])) {
    $cat = mysqli_real_escape_string($conn, $_GET['category']);
    $where_clauses[] = "category='$cat'";
}

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clauses[] = "name LIKE '%$search%'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(' AND ', $where_clauses);
}

$sql = "SELECT * FROM products $where_sql ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - products</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        h2 { text-align: center; color: #1f2933; margin-bottom: 30px; }
        
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        
        .card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        
        .card img { width: 100%; height: 200px; object-fit: cover; }
        
        .card-body { padding: 15px; }
        .card-title { font-size: 18px; font-weight: bold; margin-bottom: 8px; color: #1f2933; }
        .card-text { font-size: 14px; color: #6b7280; margin-bottom: 15px; height: 40px; overflow: hidden; }
        .price { font-size: 18px; color: #22c55e; font-weight: bold; }
        
        .btn { display: block; width: 100%; padding: 10px; background: #3b82f6; color: white; text-align: center; text-decoration: none; border-radius: 8px; margin-top: 10px; border: none; cursor: pointer; }
        .btn:hover { background: #2563eb; }
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
        <a href="shop.php" style="font-weight: bold; text-decoration: underline;">Shop</a>
        <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
        <a href="my_profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container" style="display: flex; gap: 30px;">
    
    <!-- Sidebar -->
    <div style="flex: 1; min-width: 200px;">
        <h3>Categories</h3>
        <ul style="list-style: none; padding: 0; margin-top: 15px;">
            <li style="margin-bottom: 10px;"><a href="shop.php" style="text-decoration: none; color: #3b82f6; font-weight: bold;">All Products</a></li>
            <li style="margin-bottom: 10px;"><a href="shop.php?category=Electronics" style="text-decoration: none; color: #1f2933;">Electronics</a></li>
            <li style="margin-bottom: 10px;"><a href="shop.php?category=Fashion" style="text-decoration: none; color: #1f2933;">Fashion</a></li>
            <li style="margin-bottom: 10px;"><a href="shop.php?category=Home" style="text-decoration: none; color: #1f2933;">Home</a></li>
            <li style="margin-bottom: 10px;"><a href="shop.php?category=Beauty" style="text-decoration: none; color: #1f2933;">Beauty</a></li>
            <li style="margin-bottom: 10px;"><a href="shop.php?category=Sports" style="text-decoration: none; color: #1f2933;">Sports</a></li>
            <li style="margin-bottom: 10px;"><a href="shop.php?category=Other" style="text-decoration: none; color: #1f2933;">Other</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div style="flex: 4;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Available Products</h2>
        <form method="GET" action="shop.php" style="display: flex;">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px 0 0 5px; outline: none;">
            <button type="submit" style="padding: 8px 15px; background: #3b82f6; color: white; border: none; border-radius: 0 5px 5px 0; cursor: pointer;">Search</button>
        </form>
    </div>
    
    <div class="grid">
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="card">
            <img src="../<?php echo $row['image']; ?>" alt="Product Image">
            <div class="card-body">
                <div class="card-title"><?php echo $row['name']; ?></div>
                <div class="card-text"><?php echo $row['description']; ?></div>
                <div class="price">Rs. <?php echo number_format($row['price']); ?></div>
                
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" name="add_to_cart" class="btn" style="flex: 1;">Add to Cart</button>
                        <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn" style="flex: 1; background: #ea580c; text-decoration: none; text-align: center;">View</a>
                    </div>
                </form>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <?php if (mysqli_num_rows($result) == 0) echo "<p style='text-align:center;'>No products available yet.</p>"; ?>
    </div>
</div>

</body>
</html>
