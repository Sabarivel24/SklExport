<?php
session_start();
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $new_date = $_POST['new_date'];

    // Update last_date and mark as updated
    $update_stmt = $conn->prepare("UPDATE team_taskss SET last_date = ?, is_updated = 1 WHERE id = ?");
    $update_stmt->bind_param("si", $new_date, $task_id);
    
    if ($update_stmt->execute()) {
        header("Location: team_details.php"); // Redirect after success
        exit();
    } else {
        echo "Error updating date.";
    }
} else {
    echo "Invalid access.";
}
?>
