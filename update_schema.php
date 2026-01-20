<?php
include 'db.php';

// Create orders table
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql_orders)) {
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating table 'orders': " . mysqli_error($conn) . "<br>";
}

// Create order_items table
$sql_items = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql_items)) {
    echo "Table 'order_items' created successfully.<br>";
} else {
    echo "Error creating table 'order_items': " . mysqli_error($conn) . "<br>";
}
?>
