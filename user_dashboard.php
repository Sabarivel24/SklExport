<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$merch = $_SESSION['username'];

// Fetch orders/tasks allocated to this incharge
$query = "SELECT * FROM ordersss WHERE merch = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $merch);
$stmt->execute();
$result = $stmt->get_result();

function renderDateCell($date, $status) {
    if ($status == 'Completed') {
        return '<td style="color:green;">' . htmlspecialchars($date) . '</td>';
    } elseif ($status == 'Updated') {
        return '<td style="color:orange;">' . htmlspecialchars($date) . '</td>';
    } else {
        return '<td>' . htmlspecialchars($date) . '</td>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - SKL Exports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef5f9;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

    <h2>Welcome, <?php echo htmlspecialchars($merch); ?>! Here are your assigned tasks:</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Order No</th>
                <th>Buyer</th>
                <th>Po_Qty</th>
                <th>Pro_Qty</th>
                <th>Item</th>
                <th>Po_Date</th>
                <th>Ship_Date</th>
                <th>Ck_Date</th>
                <th>Fabric_Date</th>
                <th>Trims_Date</th>
                <th>Buyer_pp</th>
                <th>Testing</th>
                <th>Buyer_pp_Approval</th>
                <th>Qa_pp</th>
                <th>Qa_pp_Approval</th>
                <th>Size_set</th>
                <th>Ship_sample</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['order_no']; ?></td>
                    <td><?php echo $row['buyer']; ?></td>
                    <td><?php echo $row['po_qty']; ?></td>
                    <td><?php echo $row['pro_qty']; ?></td>
                    <td><?php echo $row['item']; ?></td>
                    <?php echo renderDateCell($row['po_date'], $row['po_status']); ?>
                    <?php echo renderDateCell($row['ship_date'], $row['ship_status']); ?>
                    <?php echo renderDateCell($row['ck_date'], $row['ck_status']); ?>
                    <?php echo renderDateCell($row['fabric_date'], $row['fabric_status']); ?>
                    <?php echo renderDateCell($row['trims_date'], $row['trims_status']); ?>
                    <?php echo renderDateCell($row['buyer_pp'], $row['buyer_pp']); ?>
                    <?php echo renderDateCell($row['testing'], $row['testing_status']); ?>
                    <?php echo renderDateCell($row['buyer_pp_approval'], $row['buyer_pp_approval']); ?>
                    <?php echo renderDateCell($row['qa_pp'], $row['qa_pp']); ?>
                    <?php echo renderDateCell($row['qa_pp_approval'], $row['qa_pp_approval']); ?>
                    <?php echo renderDateCell($row['size_set'], $row['size_set']); ?>
                    <?php echo renderDateCell($row['ship_sample'], $row['ship_sample_status']); ?>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No tasks assigned to you yet.</p>
    <?php endif; ?>
    <a href="update_actual_dates.php"><button>Date Entry</button></a>
    <a href="team_details.php"><button>Team Details</button></a>
    <a href="generate_report.php" target="_blank"><button>Generate PDF Report</button></a>
    <a href="index.php"><button>Home</button></a>
</body>
</html>
