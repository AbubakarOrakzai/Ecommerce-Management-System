<?php
include 'db.php';

// Add category column
$sql = "ALTER TABLE products ADD COLUMN category VARCHAR(100) NOT NULL DEFAULT 'Other'";
if (mysqli_query($conn, $sql)) {
    echo "Column 'category' added successfully.<br>";
} else {
    echo "Error adding column 'category': " . mysqli_error($conn) . "<br>";
}
?>
