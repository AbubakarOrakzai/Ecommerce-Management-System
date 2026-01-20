<?php
session_start();
include '../db.php';

if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch Product Details
$sql = "SELECT * FROM products WHERE id='$product_id'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Product not found.";
    exit();
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    if (!$user_id) {
        echo "<script>alert('Please login to add to cart'); window.location.href='login.php';</script>";
    } else {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $found = false;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product['id']) {
                $_SESSION['cart'][$key]['quantity'] += 1;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $item_array = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            );
            $_SESSION['cart'][] = $item_array;
        }
        echo "<script>alert('Item added to cart!');</script>";
    }
}

// Handle Review Submission
if (isset($_POST['submit_review'])) {
    if (!$user_id) {
        echo "<script>alert('Please login to submit a review'); window.location.href='login.php';</script>";
    } else {
        $rating = $_POST['rating'];
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        
        $review_sql = "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES ('$user_id', '$product_id', '$rating', '$comment')";
        if (mysqli_query($conn, $review_sql)) {
            echo "<script>alert('Review submitted!'); window.location.href='product_details.php?id=$product_id';</script>";
        } else {
            echo "<script>alert('Error submitting review');</script>";
        }
    }
}

// Fetch Reviews
$reviews_sql = "SELECT reviews.*, users.full_name FROM reviews JOIN users ON reviews.user_id = users.id WHERE product_id='$product_id' ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - Details</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        
        /* Product Section */
        .product-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; gap: 40px; }
        .product-img { width: 400px; height: 400px; object-fit: cover; border-radius: 10px; }
        .product-info { flex: 1; }
        .product-title { font-size: 28px; font-weight: bold; color: #1f2933; margin-bottom: 10px; }
        .product-price { font-size: 24px; color: #22c55e; font-weight: bold; margin-bottom: 20px; }
        .product-desc { color: #4b5563; line-height: 1.6; margin-bottom: 20px; }
        .btn { padding: 12px 25px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        
        /* Specs Section */
        .specs-scan { margin-top: 30px; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .section-header { font-size: 20px; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
        
        /* Reviews Section */
        .reviews-section { margin-top: 30px; }
        .review-card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .review-user { font-weight: bold; color: #1f2933; }
        .review-rating { color: #f59e0b; font-size: 14px; }
        .review-text { margin-top: 8px; color: #4b5563; }
        .review-form { background: white; padding: 25px; border-radius: 12px; margin-top: 20px; }
        textarea { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; margin-bottom: 10px; }
        select { padding: 8px; border-radius: 5px; border: 1px solid #d1d5db; margin-bottom: 10px; }
    </style>
</head>
<body>

<nav>
    <h2>E-Shop</h2>
    <div>
        <a href="shop.php">Back to Shop</a>
        <a href="cart.php">Cart</a>
    </div>
</nav>

<div class="container">
    <div class="product-card">
        <img src="../<?php echo $product['image']; ?>" class="product-img">
        <div class="product-info">
            <div class="product-title"><?php echo $product['name']; ?></div>
            <div style="margin-bottom: 10px; color: gray;">Category: <?php echo $product['category']; ?></div>
            <div class="product-price">Rs. <?php echo number_format($product['price']); ?></div>
            <div class="product-desc"><?php echo $product['description']; ?></div>
            
            <form method="POST">
                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
            </form>
        </div>
    </div>

    <!-- Specifications -->
    <div class="specs-scan">
        <div class="section-header">Specifications</div>
        <p style="white-space: pre-line; color: #374151;">
            <?php echo $product['specifications'] ? $product['specifications'] : 'No specifications available.'; ?>
        </p>
    </div>

    <!-- Reviews -->
    <div class="reviews-section">
        <div class="section-header">Customer Reviews</div>
        
        <?php while ($review = mysqli_fetch_assoc($reviews_result)) { ?>
        <div class="review-card">
            <div class="review-user"><?php echo $review['full_name']; ?> 
                <span class="review-rating">
                    <?php for($i=0; $i<$review['rating']; $i++) echo "★"; ?>
                </span>
            </div>
            <div class="review-text"><?php echo $review['comment']; ?></div>
            <small style="color: #9ca3af;"><?php echo date("d M Y", strtotime($review['created_at'])); ?></small>
        </div>
        <?php } ?>

        <!-- Add Review -->
        <div class="review-form">
            <h4>Write a Review</h4>
            <form method="POST">
                <select name="rating" required>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select><br>
                <textarea name="comment" rows="3" placeholder="Share your thoughts..." required></textarea>
                <button type="submit" name="submit_review" class="btn" style="padding: 8px 20px; font-size: 14px;">Submit Review</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
