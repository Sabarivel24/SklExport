<?php
session_start();
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'user'; // Force role as user

    $stmt = $conn->prepare("SELECT * FROM userss WHERE username=? AND password=? AND role=?");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: user_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login | SKL Exports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #cddbe7, #eaf1f7);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-box {
            background: #ffffff;
            padding: 35px 30px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-box img {
            width: 100px;
            margin-bottom: 20px;
            border-radius: 12px;
        }

        h2 {
            color: #007BFF;
            margin-bottom: 25px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            top: 12px;
            right: 10px;
            cursor: pointer;
            font-size: 14px;
            color: #007BFF;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            font-weight: bold;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            text-align: right;
            font-size: 13px;
        }

        .forgot-password a {
            color: #007BFF;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }

        @media screen and (max-width: 480px) {
            .login-box {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <img src="skllogo1.png" alt="SKL Exports Logo" />
        <h2>User Login</h2>

        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Enter Username" required />
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Enter Password" required />
                <span class="toggle-password" onclick="togglePassword()">Show</span>
            </div>
            <input type="submit" value="Login" />
        </form>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="footer">© <?= date('Y'); ?> SKL Exports | All rights reserved</div>
    </div>
</body>
</html>
