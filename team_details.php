<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$merch = $_SESSION['username'];
$order_no = '';
$dates = [];

// When order number is submitted
if (isset($_POST['fetch_dates'])) {
    $order_no = $_POST['order_no'];

    $stmt_dates = $conn->prepare("SELECT cutting, printing, sewing, finishing FROM ordersss WHERE order_no = ?");
    $stmt_dates->bind_param("s", $order_no);
    $stmt_dates->execute();
    $result_dates = $stmt_dates->get_result();

    if ($result_dates->num_rows > 0) {
        $dates = $result_dates->fetch_assoc();
    }
}

// When assigning members
if (isset($_POST['assign_task'])) {
    $order_no = $_POST['order_no'];
    $status = "Not Completed";

    $tasks = ['Cutting', 'Printing', 'Sewing', 'Finishing'];
    $stmt_insert = $conn->prepare("INSERT INTO team_taskss (merch, order_no, member_name,task, last_date) VALUES (?, ?, ?, ?, ?)");

    foreach ($tasks as $task) {
        $member_name = $_POST[strtolower($task) . '_member'];
        $last_date = $_POST[strtolower($task) . '_date'];
        $stmt_insert->bind_param("ssssss", $merch, $order_no, $member_name, $task, $last_date);
        $stmt_insert->execute();
    }
}

// Fetch tasks for display
$stmt = $conn->prepare("SELECT * FROM team_taskss WHERE merch = ?");
$stmt->bind_param("s", $merch);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Team Details - <?php echo htmlspecialchars($merch); ?></title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
            padding: 30px;
        }

        h2, h3 {
            text-align: center;
        }

        form {
            width: 60%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"], button {
            padding: 8px 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>Team Member Assignment</h2>

    <!-- Fetch Order Date Form -->
    <form method="POST">
        <label>Enter Order No:</label>
        <input type="text" name="order_no" value="<?= htmlspecialchars($order_no) ?>" required>
        <input type="submit" name="fetch_dates" value="Fetch Task Dates">
    </form>

    <!-- Assign Members Form -->
    <?php if (!empty($dates)) { ?>
    <form method="POST">
        <input type="hidden" name="order_no" value="<?= htmlspecialchars($order_no) ?>">

        <label>Cutting Member:</label>
        <input type="text" name="cutting_member" required>
        <input type="date" name="cutting_date" value="<?= $dates['cutting'] ?>" required>

        <label>Printing Member:</label>
        <input type="text" name="printing_member" required>
        <input type="date" name="printing_date" value="<?= $dates['printing'] ?>" required>

        <label>Sewing Member:</label>
        <input type="text" name="sewing_member" required>
        <input type="date" name="sewing_date" value="<?= $dates['sewing'] ?>" required>

        <label>Finishing Member:</label>
        <input type="text" name="finishing_member" required>
        <input type="date" name="finishing_date" value="<?= $dates['finishing'] ?>" required>

        <input type="submit" name="assign_task" value="Assign Team Members">
    </form>
    <?php } ?>

    <h3 style="margin-top: 40px;">Current Team Tasks</h3>
    <table>
        <tr>
            <th>Order No</th>
            <th>Task</th>
            <th>Member Name</th>
            <th>Last Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
        <td><?php echo isset($row['order_no']) ? htmlspecialchars($row['order_no']) : 'N/A'; ?></td>
        <td><?php echo htmlspecialchars($row['task']); ?></td>
        <td><?php echo htmlspecialchars($row['member_name']); ?></td>
        <td style="<?php echo !empty($row['is_updated']) ? 'color:orange;' : ''; ?>">
            <?php echo isset($row['last_date']) ? htmlspecialchars($row['last_date']) : '-'; ?>
        </td>
        </tr>

        <?php } ?>
    </table>

    <br><br>
    <a href="user_dashboard.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Main Page</a>
</body>
</html>
