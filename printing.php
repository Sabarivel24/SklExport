<?php
include("db_connection.php");

// Merchandisers
//$merchandisers = ['Elango', 'raman', 'Gokila', 'Anand'];
$merchandisers = [];
$merchResult = $conn->query("SELECT DISTINCT merch FROM ordersss WHERE merch IS NOT NULL AND merch != ''");
while ($row = $merchResult->fetch_assoc()) {
    $merchandisers[] = $row['merch'];
}
// Get today's date and week numbers
$today = new DateTime();
$currentWeek = (int)$today->format("W");
$nextWeek = $currentWeek + 1;
$thirdWeek = $currentWeek + 2;

// Prepare data structure
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
$result = $conn->query("SELECT merch, printing, pro_qty FROM ordersss");
while ($row = $result->fetch_assoc()) {
    $merch = $row['merch'];
    $printingDate = $row['printing'];
    $qty = (int)$row['pro_qty'];

    if (!$printingDate) continue;

    $printing = new DateTime($printingDate);
    $printingWeek = (int)$printing->format("W");

    // Total
    $data['Total']['orders']++;
    $data['Total']['qty'] += $qty;

    // Weekly
    if (in_array($printingWeek, [$currentWeek, $nextWeek, $thirdWeek])) {
        $data[$printingWeek]['orders']++;
        $data[$printingWeek]['qty'] += $qty;

        if (in_array($merch, $merchandisers)) {
            $data[$printingWeek][$merch]['orders']++;
            $data[$printingWeek][$merch]['qty'] += $qty;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Printing Dashboard</title>
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
            background: #28a745;
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
    <h2>Printing Dashboard (Weekwise)</h2>
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
        <?php foreach ([$currentWeek, $nextWeek, $thirdWeek] as $week): ?>
        <tr>
            <td><?= "Week $week" ?></td>
            <td><?= $data[$week]['orders'] ?></td>
            <?php foreach ($merchandisers as $m): ?>
                <td><?= $data[$week][$m]['orders'] ?></td>
            <?php endforeach; ?>
            <td><?= $data[$week]['qty'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>