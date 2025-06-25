<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM ordersss";
$result = $conn->query($sql);

function renderTaskCell($scheduled, $done, $expected) {
    $today = date('Y-m-d');
    $content = "<strong>$scheduled</strong><br>";

    if (!empty($done)) {
        // Task completed
        return "<td style='background-color:rgba(134, 233, 137, 0.63); color:black;'>
                    $content <small><b>Done:</b> $done</small>
                </td>";
    } elseif (!empty($expected)) {
        // Expected date given
        return "<td style='background-color:rgba(255, 0, 0, 0.6); color:white;'>
                    $content <small><b>Expected:</b> $expected</small>
                </td>";
    } elseif (!empty($scheduled) && $scheduled < $today) {
        // Overdue and no input
        return "<td style='background-color:rgb(255, 77, 77); color:white;'>
                    $content <small>Not Done</small>
                </td>";
    } else {
        // Default state
        return "<td>$content <small>Not Done</small></td>";
    }
}

function checkCycleStatus($row) {
    $fields = ['po_done_date','ship_done_date','ck_done_date','fabric_done_date', 
               'trims_done_date','testing_done_date','cutting_done_date','printing_done_date', 
               'sewing_done_date','finishing_done_date','ship_sample_done_date'];
    foreach ($fields as $f) {
        if (empty($row[$f])) {
            return '<td style="color:orange;">In Progress</td>';
        }
    }
    return '<td style="color:green;">Completed</td>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Work Details - Admin View</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
<h2>Work Details - All Orders</h2>
<table>
    <tr>
        <th>Order No</th>
        <th>PO Date</th>
        <th>Ship Date</th>
        <th>CK Date</th>
        <th>Fabric Date</th>
        <th>Trims Date</th>
        <th>Testing</th>
        <th>Cutting</th>
        <th>Printing</th>
        <th>Sewing</th>
        <th>Finishing</th>
        <th>Ship Sample</th>
        <th>Cycle</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($row['order_no']) ?></td>
<?= renderTaskCell($row['po_date'], $row['po_done_date'], $row['po_expected_date']) ?>
<?= renderTaskCell($row['ship_date'], $row['ship_done_date'], $row['ship_expected_date']) ?>
<?= renderTaskCell($row['ck_date'], $row['ck_done_date'], $row['ck_expected_date']) ?>
<?= renderTaskCell($row['fabric_date'], $row['fabric_done_date'], $row['fabric_expected_date']) ?>
<?= renderTaskCell($row['trims_date'], $row['trims_done_date'], $row['trims_expected_date']) ?>
<?= renderTaskCell($row['testing'], $row['testing_done_date'], $row['testing_expected_date']) ?>
<?= renderTaskCell($row['cutting'], $row['cutting_done_date'], $row['cutting_expected_date']) ?>
<?= renderTaskCell($row['printing'], $row['printing_done_date'], $row['printing_expected_date']) ?>
<?= renderTaskCell($row['sewing'], $row['sewing_done_date'], $row['sewing_expected_date']) ?>
<?= renderTaskCell($row['finishing'], $row['finishing_done_date'], $row['finishing_expected_date']) ?>
<?= renderTaskCell($row['ship_sample'], $row['ship_sample_done_date'], $row['ship_sample_expected_date']) ?>
<?= checkCycleStatus($row) ?>

    </tr>
    <?php } ?>
</table>
<a href="generate_pdf.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Generate PDF</a>
<a href="orders.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Main Page</a>
</body>
</html>
