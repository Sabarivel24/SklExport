<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$task = isset($_GET['task']) ? $_GET['task'] : null;
$order_no = isset($_GET['order_no']) ? $_GET['order_no'] : null;

if (!$task || !$order_no) {
    die("Missing task or order number.");
}

// Dynamically map column names
$scheduled_column = $task . '_date';
$done_column = $task . '_done_date';
$expected_column = $task . '_expected_date';
$remarks_column = $task . '_remark';
$status_column = $task . '_status';

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM ordersss WHERE order_no = ?");
$stmt->bind_param("s", $order_no);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['actual_date'] ?? '';
    $expected = $_POST['expected_date'] ?? '';
    $remarks = $_POST['remarks'] ?? '';

    if (!empty($actual)) {
        $status = "Completed";
        $update = $conn->prepare("UPDATE ordersss SET $done_column = ?, $status_column = ? WHERE order_no = ?");
        $update->bind_param("sss", $actual, $status, $order_no);
        $update->execute();
        $message = "$task marked as Completed.";
    } elseif (!empty($expected)) {
        $status = "Not Completed";
        $update = $conn->prepare("UPDATE ordersss SET $expected_column = ?, $remarks_column = ?, $status_column = ? WHERE order_no = ?");
        $update->bind_param("ssss", $expected, $remarks, $status, $order_no);
        $update->execute();
        $message = "Expected date & remark updated for $task.";
    } else {
        $message = "Please provide either actual or expected date.";
    }

    // Refresh
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
}

$task_label = strtoupper(str_replace('_', ' ', $task));
$scheduled = $order[$scheduled_column] ?? 'N/A';
$actual_done = $order[$done_column] ?? '';
$expected_date = $order[$expected_column] ?? '';
$remarks = $order[$remarks_column] ?? '';
$status = $order[$status_column] ?? 'Not Completed';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Task - <?= htmlspecialchars($task_label) ?></title>
    <style>
        body {
            font-family: Arial;
            background-color: #f5f5f5;
            padding: 30px;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            margin-top: 10px;
            display: block;
        }
        input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            margin-top: 15px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
        .back {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Update Task: <?= htmlspecialchars($task_label) ?> (Order: <?= htmlspecialchars($order_no) ?>)</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Scheduled Date:</label>
        <input type="text" value="<?= htmlspecialchars($scheduled) ?>" readonly>

        <label>Actual Completion Date:</label>
        <input type="date" name="actual_date" value="<?= htmlspecialchars($actual_done) ?>">

        <label>Expected Completion Date (if not completed):</label>
        <input type="date" name="expected_date" value="<?= htmlspecialchars($expected_date) ?>">

        <label>Remarks:</label>
        <textarea name="remarks"><?= htmlspecialchars($remarks) ?></textarea>

        <input type="submit" value="Update Task">
    </form>

    <a class="back" href="update_actual_dates.php?order_no=<?= htmlspecialchars($order_no) ?>">&larr; Back to Task List</a>
</div>
</body>
</html>
