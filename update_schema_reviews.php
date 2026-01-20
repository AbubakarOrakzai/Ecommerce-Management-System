<?php
include 'db.php';

// Add specifications column to products table
$sql1 = "ALTER TABLE products ADD COLUMN specifications TEXT";
if (mysqli_query($conn, $sql1)) {
    echo "Column 'specifications' added successfully.<br>";
} else {
    echo "Error adding column 'specifications': " . mysqli_error($conn) . "<br>";
}

// Create reviews table
$sql2 = "CREATE TABLE IF NOT EXISTS reviews (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    rating INT(1) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql2)) {
    echo "Table 'reviews' created successfully.<br>";
} else {
    echo "Error creating table 'reviews': " . mysqli_error($conn) . "<br>";
}
?>
