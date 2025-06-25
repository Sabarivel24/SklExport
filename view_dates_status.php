<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$columns = [
    'po_date' => 'PO Date',
    'ship_date' => 'Ship Date',
    'ck_date' => 'CK Date',
    'fabric_date' => 'Fabric Date',
    'trims_date' => 'Trims Date',
    'testing' => 'Testing',
    'cutting' => 'Cutting',
    'printing' => 'Printing',
    'sewing' => 'Sewing',
    'finishing' => 'Finishing',
    'ship_sample' => 'Ship Sample'
];

$result = $conn->query("SELECT * FROM ordersss");

// function to color past dates red
function highlightPastDate($date) {
    if ($date && $date < date('Y-m-d')) {
        return "<span style='color:red;'>$date</span>";
    }
    return htmlspecialchars($date);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Work Status Overview</title>
    <style>
        body { font-family: Arial; background: #eef2f7; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #007bff; color: white; }
        .green { background-color: #28a745; color: white; }
        .red { background-color: rgba(220, 134, 53, 0.81); color: white; }
    </style>
</head>
<body>
<h2>All Order Dates and Status Overview</h2>
<table>
    <tr>
        <th>Order No</th>
        <?php foreach ($columns as $key => $label): ?>
            <th><?php echo $label; ?></th>
            <th>Status</th>
            <th>Actions</th>
        <?php endforeach; ?>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['order_no']; ?></td>
            <?php foreach ($columns as $col => $label):
                $status_col = $col . '_status';
                $status = isset($row[$status_col]) ? $row[$status_col] : 'Incomplete';
                $class = ($status === 'Completed') ? 'green' : (($status === 'Updated') ? 'red' : '');
            ?>
                <td><?php echo highlightPastDate($row[$col]); ?></td>
                <td class="<?php echo $class; ?>"><?php echo $status; ?></td>
                <td>
                    <?php if ($status !== 'Completed'): ?>
                        <form method="POST" action="update_work_status.php" style="display:inline-block;">
                            <input type="hidden" name="order_no" value="<?php echo $row['order_no']; ?>">
                            <input type="hidden" name="column" value="<?php echo $col; ?>">
                            <button type="submit" name="mark_complete">Mark as Completed</button>
                        </form>
                        <form method="POST" action="update_work_status.php" style="display:inline-block;">
                            <input type="hidden" name="order_no" value="<?php echo $row['order_no']; ?>">
                            <input type="hidden" name="column" value="<?php echo $col; ?>">
                            <input type="date" name="new_date" required>
                            <button type="submit" name="update_date">Update Date</button>
                        </form>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endwhile; ?>
</table>
<a href="index.php">&larr; Back to Home</a>
</body>
</html>
