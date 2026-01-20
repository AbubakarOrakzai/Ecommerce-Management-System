<?php
include 'db.php';

// Add payment_method column
$sql1 = "ALTER TABLE orders ADD COLUMN payment_method VARCHAR(50) DEFAULT 'COD'";
if (mysqli_query($conn, $sql1)) {
    echo "Column 'payment_method' added successfully.<br>";
} else {
    echo "Error adding column 'payment_method': " . mysqli_error($conn) . "<br>";
}

// Add payment_status column
$sql2 = "ALTER TABLE orders ADD COLUMN payment_status VARCHAR(50) DEFAULT 'Pending'";
if (mysqli_query($conn, $sql2)) {
    echo "Column 'payment_status' added successfully.<br>";
} else {
    echo "Error adding column 'payment_status': " . mysqli_error($conn) . "<br>";
}
?>
