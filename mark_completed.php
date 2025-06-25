<?php
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];

    // 1. Update team_tasks
    $stmt = $conn->prepare("UPDATE team_taskss SET completion_status = 'Completed' WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    // 2. Get task details
    $result = $conn->query("SELECT * FROM team_taskss WHERE id = $task_id");
    $task = $result->fetch_assoc();
    $task_name = strtolower($task['task']); // cutting/printing/etc.
    $order_no = $task['order_no'];

    // 3. Update corresponding status column in orderss table
    $column = $task_name . "_status";
    $stmt2 = $conn->prepare("UPDATE ordersss SET $column = 'Completed' WHERE order_no = ?");
    $stmt2->bind_param("s", $order_no);
    $stmt2->execute();

    header("Location: team_details.php");
    exit();
}
?>
