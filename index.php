<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Management System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            background-image: url('assets/warehouse.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: #ffffff;
            width: 380px;
            padding: 35px 30px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            text-align: center;
        }

        .container h1 {
            font-size: 22px;
            margin-bottom: 8px;
            color: #1f2933;
        }

        .container p {
            margin-bottom: 28px;
            color: #6b7280;
            font-size: 14px;
        }

        .role-btn {
            width: 100%;
            padding: 13px;
            margin: 10px 0;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .customer {
            background: #22c55e;
            color: white;
        }

        .seller {
            background: #f59e0b;
            color: white;
        }

        .admin {
            background: #ef4444;
            color: white;
        }

        .role-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.25);
        }
        .logo{
            height: 80px;
        }
         .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            background: #f3f4f6;
            padding: 8px;
        }
    </style>
</head>

<body>

    <div class="container">

 <div class="logo-container">
            <img src="assets/dukan.png" alt="Dukan Logo" class="logo">
        </div>

        <h1>E-Commerce Management System</h1>
        <p>Please select your role to continue</p>

        <button class="role-btn customer" onclick="gotocustomerloginpage()">Customer</button>
        <button class="role-btn seller" onclick="gotoseller_login_page()">Seller</button>
        <button class="role-btn admin" onclick="gotoadmin_login_page()">Admin</button>
    </div>

</body>
<script>
     function gotocustomerloginpage()
     {
        window.location.href = "Customer/login.php";
     }
     function gotoseller_login_page()
     {
        window.location.href = "seller/login.php";
     }
     function gotoadmin_login_page()
     {
        window.location.href = "Admin/login.php";
     }
</script>
</html>
