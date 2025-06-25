<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - SKL Exports</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef3f9;
            padding: 40px;
        }

        h2 {
            color: #2d3436;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            background: #0984e3;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: 0.3s ease;
        }

        a:hover {
            background: #0652DD;
        }
    </style>
</head>
<body>
    <h2>Welcome Admin: <?php echo $_SESSION['username']; ?></h2>

    <ul>
        <li><a href="orders.php">Order Entry</a></li>
        <li><a href="work_items.php">Time Management</a></li>
        <li><a href="order_progress.php">Order Progress</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
