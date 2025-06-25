<?php
include("db_connection.php");

// Get today's date and week numbers
$today = new DateTime();
$currentWeek = (int)$today->format("W");
$nextWeek = $currentWeek + 1;
$thirdWeek = $currentWeek + 2;
$weekList = [$currentWeek, $nextWeek, $thirdWeek];

// Get unique merchandisers
$merchandisers = [];
$merchResult = $conn->query("SELECT DISTINCT merch FROM ordersss WHERE merch IS NOT NULL AND merch != ''");
while ($row = $merchResult->fetch_assoc()) {
    $merchandisers[] = $row['merch'];
}

// Get unique fabric types
$fabricTypes = [];
$fabricTypeResult = $conn->query("SELECT DISTINCT fabric FROM ordersss WHERE fabric IS NOT NULL AND fabric != ''");
while ($row = $fabricTypeResult->fetch_assoc()) {
    $fabricTypes[] = $row['fabric'];
}

// Initialize data structure
$data = [];
foreach ($weekList as $week) {
    $data[$week] = [];
    foreach ($fabricTypes as $type) {
        $data[$week][$type] = ['total' => 0];
        foreach ($merchandisers as $m) {
            $data[$week][$type][$m] = 0;
        }
    }
}

// Fetch fabric entries
$result = $conn->query("SELECT merch, fabric_date, fabric FROM ordersss");
while ($row = $result->fetch_assoc()) {
    $merch = $row['merch'];
    $fabricDate = $row['fabric_date'];
    $fabricType = $row['fabric'];

    if (!$fabricDate || !$fabricType) continue;

    $fabricWeek = (int)(new DateTime($fabricDate))->format("W");

    if (in_array($fabricWeek, $weekList)) {
        if (!isset($data[$fabricWeek][$fabricType])) {
            // If a new type appears that wasn't previously in fabricTypes
            $data[$fabricWeek][$fabricType] = ['total' => 0];
            foreach ($merchandisers as $m) {
                $data[$fabricWeek][$fabricType][$m] = 0;
            }
        }

        $data[$fabricWeek][$fabricType]['total']++;

        if (in_array($merch, $merchandisers)) {
            $data[$fabricWeek][$fabricType][$merch]++;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fabric Count by Type Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: #fff;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #aaa;
            text-align: center;
        }
        th {
            background: #20c997;
            color: white;
        }
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 30px;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Fabric Count Dashboard (Grouped by Type, Week & Merchandiser)</h2>

    <?php foreach ($weekList as $week): ?>
        <h3>Week <?= $week ?></h3>
        <table>
            <tr>
                <th>Fabric Type</th>
                <th>Total Count</th>
                <?php foreach ($merchandisers as $m): ?>
                    <th><?= $m ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($data[$week] as $fabricType => $counts): ?>
                <tr>
                    <td><?= $fabricType ?></td>
                    <td><?= $counts['total'] ?></td>
                    <?php foreach ($merchandisers as $m): ?>
                        <td><?= $counts[$m] ?? 0 ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</body>
</html>