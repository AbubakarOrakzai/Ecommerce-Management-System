<?php
include 'db.php';

// Add profile_pic column if it doesn't exist
$sql = "SHOW COLUMNS FROM users LIKE 'profile_pic'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $alter_sql = "ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) DEFAULT 'default_profile.png'";
    if (mysqli_query($conn, $alter_sql)) {
        echo "Column 'profile_pic' added successfully.";
    } else {
        echo "Error adding column: " . mysqli_error($conn);
    }
} else {
    echo "Column 'profile_pic' already exists.";
}
?>
