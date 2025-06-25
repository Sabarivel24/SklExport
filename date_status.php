<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle mark as completed
if (isset($_POST['complete_id'])) {
    $id = $_POST['complete_id'];
    $stmt = $conn->prepare("UPDATE date_statuss SET status = 'Completed' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Handle date update
if (isset($_POST['update_id']) && isset($_POST['new_date'])) {
    $id = $_POST['update_id'];
    $new_date = $_POST['new_date'];

    // Get field to calculate dependent updates
    $stmt_get = $conn->prepare("SELECT order_no, date_field FROM date_statuss WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $result_get = $stmt_get->get_result();
    if ($row = $result_get->fetch_assoc()) {
        $order_no = $row['order_no'];
        $field = $row['date_field'];

        // Update the field
        $stmt_upd = $conn->prepare("UPDATE date_statuss SET updated_date = ?, status = 'Incomplete' WHERE id = ?");
        $stmt_upd->bind_param("si", $new_date, $id);
        $stmt_upd->execute();

        // Logic to auto-update upcoming fields
        $sequence = ["po_date", "ship_date", "ck_date", "fabric_date", "trims_date", "testing", "cutting", "printing", "sewing", "finishing", "ship_sample"];
        $offsets = 2; // days offset
        $found = false;
        foreach ($sequence as $i => $field_name) {
            if ($found) {
                $offset_date = date('Y-m-d', strtotime($new_date . "+$offsets days"));
                $stmt_update_dep = $conn->prepare("UPDATE date_statuss SET updated_date = ?, status = 'Incomplete' WHERE order_no = ? AND date_field = ?");
                $stmt_update_dep->bind_param("sss", $offset_date, $order_no, $field_name);
                $stmt_update_dep->execute();
                $new_date = $offset_date;
            }
            if ($field_name == $field) $found = true;
        }
    }
}

// Fetch all order dates
$query = "SELECT * FROM date_statuss ORDER BY order_no, FIELD(date_field, 'po_date', 'ship_date', 'ck_date', 'fabric_date', 'trims_date', 'testing', 'cutting', 'printing', 'sewing', 'finishing', 'ship_sample')";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Date Status Update</title>
    <style>
        body { font-family: Arial; background: #f8f9fa; padding: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #007bff; color: white; }
        .green { background-color: green; color: white; }
        .red { background-color: red; color: white; }
    </style>
</head>
<body>
    <h2>Order Date Status Management</h2>
    <table>
        <tr>
            <th>Order No</th>
            <th>Date Field</th>
            <th>Original Date</th>
            <th>Updated Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                $class = "";
                if ($row['status'] == 'Completed') {
                    $class = "green";
                } elseif (!empty($row['updated_date'])) {
                    $class = "red";
                }
            ?>
            <tr class="<?= $class ?>">
                <td><?= htmlspecialchars($row['order_no']) ?></td>
                <td><?= htmlspecialchars($row['date_field']) ?></td>
                <td><?= htmlspecialchars($row['original_date']) ?></td>
                <td><?= htmlspecialchars($row['updated_date']) ?: '-' ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="complete_id" value="<?= $row['id'] ?>">
                        <button type="submit">Mark Completed</button>
                    </form>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                        <input type="date" name="new_date" required>
                        <button type="submit">Update Date</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
