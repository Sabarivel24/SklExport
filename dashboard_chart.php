<?php
include("db_connection.php");

$taskList = ['fabric_date', 'cutting', 'printing', 'sewing', 'finishing'];
$taskLabels = ['Fabric Date', 'Cutting', 'Printing', 'Sewing', 'Finishing'];

$weekList = [];
$today = new DateTime();
for ($i = 0; $i < 3; $i++) {
    $weekList[] = (int)$today->format("W") + $i;
}

$taskCounts = array_fill_keys($taskLabels, 0);
$taskDetails = [];

$result = $conn->query("SELECT order_no, merch, fabric_date, cutting, printing, sewing, finishing, pro_qty FROM ordersss");

while ($row = $result->fetch_assoc()) {
    foreach ($taskList as $i => $task) {
        $label = $taskLabels[$i];
        $dateStr = $row[$task];

        if (!empty($dateStr)) {
            $dateObj = DateTime::createFromFormat('Y-m-d', $dateStr);
            if ($dateObj && $dateObj->format('Y-m-d') === $dateStr) {
                $weekNum = (int)$dateObj->format("W");
                if (in_array($weekNum, $weekList)) {
                    $taskCounts[$label]++;
                    $taskDetails[$label][] = [
                        'week' => $weekNum,
                        'order_no' => $row['order_no'],
                        'merch' => $row['merch'],
                        'qty' => $row['pro_qty']
                    ];
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weekly Task Pie Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 40px;
            background: #f0f0f0;
        }
        #chartContainer {
            width: 550px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px #ccc;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .details-section {
            display: none;
            margin-top: 40px;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #aaa;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<div id="chartContainer">
    <h2>Task Distribution (Week <?= $weekList[0] ?> - <?= $weekList[2] ?>)</h2>
    <canvas id="taskPieChart" width="400" height="400"></canvas>
    <button onclick="toggleDetails()">Show Details</button>
    <form method="post" action="generate_dashboard_report.php" target="_blank">
    <input type="hidden" name="week_start" value="<?= $weekList[0] ?>">
    <input type="hidden" name="week_end" value="<?= $weekList[2] ?>">
    <button type="submit" style="margin-top:10px;">Generate PDF Report</button>
    <a href="orders.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Main Page</a>

</form>

</div>

<div class="details-section" id="detailsSection">
    <?php foreach ($taskDetails as $task => $entries): ?>
        <h3><?= htmlspecialchars($task) ?> Tasks</h3>
        <table>
            <tr>
                <th>Week No</th>
                <th>Order No</th>
                <th>Assigned Merch</th>
                <th>Production Qty</th>
            </tr>
            <?php foreach ($entries as $entry): ?>
                <tr>
                    <td><?= htmlspecialchars($entry['week']) ?></td>
                    <td><?= htmlspecialchars($entry['order_no']) ?></td>
                    <td><?= htmlspecialchars($entry['merch']) ?></td>
                    <td><?= htmlspecialchars($entry['qty']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</div>

<script>
    const taskLabels = <?= json_encode(array_keys($taskCounts)) ?>;
    const taskData = <?= json_encode(array_values($taskCounts)) ?>;

    const ctx = document.getElementById('taskPieChart').getContext('2d');
    const taskChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: taskLabels,
            datasets: [{
                data: taskData,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw;
                        }
                    }
                }
            },
            onClick: function (e, elements) {
                if (elements.length > 0) {
                    const clickedIndex = elements[0].index;
                    const task = taskLabels[clickedIndex].toLowerCase().replace(' ', '_');
                    window.location.href = task + ".php";
                }
            }
        }
    });

    function toggleDetails() {
        const section = document.getElementById('detailsSection');
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
    }
</script>

</body>
</html>
