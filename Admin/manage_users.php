<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Delete User Logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Prevent deleting self
    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('You cannot delete yourself!');</script>";
    } else {
        $delete_sql = "DELETE FROM users WHERE id='$id'";
        if (mysqli_query($conn, $delete_sql)) {
            echo "<script>alert('User deleted successfully'); window.location.href='manage_users.php';</script>";
        } else {
            echo "<script>alert('Error deleting user');</script>";
        }
    }
}

// Fetch Users
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #ef4444); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; font-weight: 500; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1f2933; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9fafb; }
        .btn-danger { background: #ef4444; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 12px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; background: #f3f4f6; padding: 4px; }
    </style>
</head>
<body>

<nav>
    <div class="logo-container" style="margin-bottom:0;">
        <img src="/assets/dukan (1).png" alt="Logo" class="logo">
    </div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_users.php" style="font-weight: bold; text-decoration: underline;">Manage Users</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Manage Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo ucfirst($row['role']); ?></td>
            <td>
                <?php if ($row['role'] != 'admin' || $row['id'] != $_SESSION['user_id']) { ?>
                <a href="manage_users.php?delete=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                <?php } else { echo "current user"; } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
