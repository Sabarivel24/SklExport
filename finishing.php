<?php
include("db_connection.php");

function getWeekNumber($dateStr) {
    $date = new DateTime($dateStr);
    return (int)$date->format("W");
}

$today = new DateTime();
$currentWeek = (int)$today->format("W");
$nextWeek = $currentWeek + 1;
$thirdWeek = $currentWeek + 2;

// Merchandisers
//$merchandisers = ['Elango', 'raman', 'Gokila', 'Anand'];
$merchandisers = [];
$merchResult = $conn->query("SELECT DISTINCT merch FROM ordersss WHERE merch IS NOT NULL AND merch != ''");
while ($row = $merchResult->fetch_assoc()) {
    $merchandisers[] = $row['merch'];
}
// Prepare base structure for table data
$data = [
    'Total' => ['orders' => 0, 'qty' => 0]
];
foreach ([$currentWeek, $nextWeek, $thirdWeek] as $weekNum) {
    $data[$weekNum] = ['orders' => 0, 'qty' => 0];
    foreach ($merchandisers as $m) {
        $data[$weekNum][$m] = ['orders' => 0, 'qty' => 0];
    }
}

// Fetch data from orderss table
$result = $conn->query("SELECT merch, finishing, pro_qty FROM ordersss");
while ($row = $result->fetch_assoc()) {
    $merch = $row['merch'];
    $finishingDate = $row['finishing'];
    $qty = (int)$row['pro_qty'];

    if (!$finishingDate) continue;

    $weekNum = getWeekNumber($finishingDate);

    // Total
    $data['Total']['orders']++;
    $data['Total']['qty'] += $qty;

    if (in_array($weekNum, [$currentWeek, $nextWeek, $thirdWeek])) {
        $data[$weekNum]['orders']++;
        $data[$weekNum]['qty'] += $qty;
        if (in_array($merch, $merchandisers)) {
            $data[$weekNum][$merch]['orders']++;
            $data[$weekNum][$merch]['qty'] += $qty;
        }
    }
}

?><!DOCTYPE html><html>
<head>
    <title>Finishing Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 30px;
        }
    </style>
</head>
<body>
    <h2>Finishing Dashboard (Weekwise)</h2>
    <table>
        <tr>
            <th>Week</th>
            <th>Orders</th>
            <?php foreach ($merchandisers as $m) echo "<th>$m</th>"; ?>
            <th>Quantity</th>
        </tr>
        <tr>
            <td>All Deps</td>
            <td><?= $data['Total']['orders'] ?></td>
            <?php foreach ($merchandisers as $m) echo "<td>-</td>"; ?>
            <td><?= $data['Total']['qty'] ?></td>
        </tr>
        <?php foreach ([$currentWeek, $nextWeek, $thirdWeek] as $weekNum): ?>
        <tr>
            <td><?= $weekNum ?></td>
            <td><?= $data[$weekNum]['orders'] ?></td>
            <?php foreach ($merchandisers as $m): ?>
                <td><?= $data[$weekNum][$m]['orders'] ?></td>
            <?php endforeach; ?>
            <td><?= $data[$weekNum]['qty'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>