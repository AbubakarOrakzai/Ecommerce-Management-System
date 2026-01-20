<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Verify order belongs to user and is pending
    $check_sql = "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id' AND status='Pending'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $update_sql = "UPDATE orders SET status='Cancelled' WHERE id='$order_id'";
        if (mysqli_query($conn, $update_sql)) {
            // Redirect with success message
            echo "<script>alert('Order cancelled successfully.'); window.location.href='check_orders.php';</script>";
        } else {
            echo "<script>alert('Error cancelling order.'); window.location.href='check_orders.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid order or order cannot be cancelled.'); window.location.href='check_orders.php';</script>";
    }
} else {
    header("Location: check_orders.php");
    exit();
}
?>
