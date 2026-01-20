<?php
$servername = "localhost";
$username = "root";
$passwords_to_try = ["", "root", "admin", "password", "123456"];

echo "Testing connection to '$servername' with user '$username'...\n";

foreach ($passwords_to_try as $pass) {
    echo "Trying password: '" . $pass . "' ... ";
    try {
        $conn = mysqli_connect($servername, $username, $pass, "ecommerce_db");
        if ($conn) {
            echo "SUCCESS!\n";
            exit(0);
        }
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}
?>
