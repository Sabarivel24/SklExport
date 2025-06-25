<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_no = $_POST['order_no'];
    $column = $_POST['column'];

    // Mark as Completed
    if (isset($_POST['mark_complete'])) {
        $status_col = $column . "_status";
        $stmt = $conn->prepare("UPDATE ordersss SET $status_col = 'Completed' WHERE order_no = ?");
        $stmt->bind_param("s", $order_no);
        $stmt->execute();

        header("Location: view_dates_status.php");
        exit();
    }

    // Update Date
    if (isset($_POST['update_date'])) {
        $new_date = $_POST['new_date'];
        $status_col = $column . "_status";

        // Update orderss table
        $stmt = $conn->prepare("UPDATE ordersss SET $column = ?, $status_col = 'Updated' WHERE order_no = ?");
        $stmt->bind_param("ss", $new_date, $order_no);
        $stmt->execute();

        // ✅ Also update last_date in team_tasks if it's a production task
        $valid_tasks = ['cutting', 'printing', 'sewing', 'finishing'];

        if (in_array($column, $valid_tasks)) {
            $task_name = ucfirst($column); // 'Cutting', 'Printing', etc.

            $update_team = $conn->prepare("UPDATE team_taskss SET last_date = ?, is_updated = 1 WHERE order_no = ? AND task = ?");
            $update_team->bind_param("sss", $new_date, $order_no, $task_name);
            $update_team->execute();
        }

        header("Location: view_dates_status.php");
        exit();
    }
}

header("Location: view_dates_status.php");
exit();
