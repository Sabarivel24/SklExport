<?php
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_no'])) {
    $orderNo = $_POST['order_no'];

    // Update the status to 'Completed'
    $stmt = $conn->prepare("UPDATE ordersss SET status = 'Completed' WHERE order_no = ?");
    $stmt->bind_param("s", $orderNo);
    $stmt->execute();

    // Redirect back to work details
    header("Location: work_details.php");
    exit();
}
?>
