<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Handle File Upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "../assets/uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);
        $new_filename = "profile_" . $user_id . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $profile_pic_path = "assets/uploads/" . $new_filename; // Relative path for DB (adjust if needed) or use full relative path
             // Actually, let's store filename only or relative path from root. 
             // Storing 'assets/uploads/filename' is good if we include it from root.
             // Inside Customer/ folder, we need '../' to access it.
             // Let's store just the filename or relative path. 
             // Ideally store 'assets/uploads/filename.jpg'.
            
            $db_image_path = "assets/uploads/" . $new_filename;

            $update_sql = "UPDATE users SET full_name='$full_name', email='$email', phone='$phone', address='$address', profile_pic='$db_image_path' WHERE id='$user_id'";
        } else {
             $message = "Error uploading file.";
        }
    } else {
        $update_sql = "UPDATE users SET full_name='$full_name', email='$email', phone='$phone', address='$address' WHERE id='$user_id'";
    }

    if (isset($update_sql)) {
        if (mysqli_query($conn, $update_sql)) {
            $_SESSION['user_name'] = $full_name; // Update session name
            $message = "Profile updated successfully!";
        } else {
            $message = "Error updating profile: " . mysqli_error($conn);
        }
    }
}

// Fetch User Details
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Set default profile pic if empty
$profile_pic = !empty($user['profile_pic']) ? "../" . $user['profile_pic'] : "https://via.placeholder.com/150";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
        body { background: #f3f4f6; }
        nav { background: linear-gradient(135deg, #1f2933, #3b82f6); padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; color: white; }
        .logo-container { display: flex; align-items: center; gap: 12px; }
        .logo { width: 46px; height: 46px; border-radius: 50%; background: white; padding: 4px; object-fit: cover; }
        nav h2 { font-size: 20px; }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        
        .container { max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 25px; color: #1f2933; text-align: center; }
        
        .profile-section { text-align: center; margin-bottom: 30px; }
        .profile-pic { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #3b82f6; margin-bottom: 15px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #374151; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
        .form-group input:focus { border-color: #3b82f6; outline: none; }
        
        .btn { width: 100%; padding: 14px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        .btn:hover { background: #2563eb; }
        
        .alert { padding: 15px; background: #dcfce7; color: #166534; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>

<nav>
    <div class="logo-container">
        <img src="../assets/dukan.png" alt="Logo" class="logo">
        <h2>My Profile</h2>
    </div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="shop.php">Shop</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <?php if ($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="profile-section">
            <img src="<?php echo $profile_pic; ?>" class="profile-pic" id="previewImg">
            <div>
                <label for="profile_pic" style="cursor: pointer; color: #3b82f6; font-weight: bold;">Change Picture</label>
                <input type="file" id="profile_pic" name="profile_pic" style="display: none;" onchange="previewFile()">
            </div>
        </div>

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="3" required><?php echo $user['address']; ?></textarea>
        </div>

        <button type="submit" class="btn">Update Profile</button>
    </form>
</div>

<script>
    function previewFile() {
        const preview = document.getElementById('previewImg');
        const file = document.querySelector('input[type=file]').files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "<?php echo $profile_pic; ?>";
        }
    }
</script>

</body>
</html>
