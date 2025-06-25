<?php
session_start();
include("db_connection.php");
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin.php"); // redirect to login page
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'user'; // default role

    // Optional: Hash password before storing (recommended for security)
    // $password = password_hash($password, PASSWORD_DEFAULT);

    // Check if user already exists
    $checkStmt = $conn->prepare("SELECT * FROM userss WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $msg = "Username already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO userss (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            $msg = "User created successfully!";
        } else {
            $msg = "Error creating user: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create User | Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        .msg {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Create New User</h2>
        <form method="POST" action="create_user.php">
            <input type="text" name="username" placeholder="New Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="submit" value="Create User" />
        </form>
        <?php
        if (isset($msg)) {
            echo "<div class='" . (str_contains($msg, 'success') ? 'msg' : 'error') . "'>$msg</div>";
        }
        ?>
    </div>
</body>
</html>
