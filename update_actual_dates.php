<?php
// update_actual_dates.php
session_start();
include("db_connection.php");
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$tasks = ['po', 'ship', 'ck', 'fabric', 'trims', 'testing', 'cutting', 'printing', 'sewing', 'finishing', 'ship_sample'];
$order_no = $_GET['order_no'] ?? '';
$selected_task = $_GET['task'] ?? '';

// Fetch scheduled date if task is selected
$scheduled_date = '';
$status = 'Not Completed';
$expected = '';
$remarks = '';
$actual_done = '';

if ($order_no && $selected_task && in_array($selected_task, $tasks)) {
    $query = $conn->prepare("SELECT * FROM ordersss WHERE order_no = ?");
    $query->bind_param("s", $order_no);
    $query->execute();
    $result = $query->get_result();
    if ($data = $result->fetch_assoc()) {
        $scheduled_date = $data[$selected_task . '_date'] ?? $data[$selected_task] ?? '';
        $actual_done = $data[$selected_task . '_done_date'] ?? '';
        $expected = $data[$selected_task . '_expected_date'] ?? '';
        $remarks = $data[$selected_task . '_remark'] ?? '';
        $status = $data[$selected_task . '_status'] ?? 'Not Completed';
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $completed = $_POST['completed'] ?? '';

    if ($completed === 'yes' && !empty($_POST['actual_date'])) {
        $actual = $_POST['actual_date'];
        $status = 'Completed';

        $stmt = $conn->prepare("UPDATE ordersss SET {$selected_task}_done_date = ?, {$selected_task}_status = ? WHERE order_no = ?");
        $stmt->bind_param("sss", $actual, $status, $order_no);
        $stmt->execute();

        header("Location: update_actual_dates.php?order_no=$order_no&task=$selected_task");
        exit();

    } elseif ($completed === 'no' && !empty($_POST['expected_date'])) {
        $expected = $_POST['expected_date'];
        $remarks = $_POST['remarks'] ?? '';
        $status = 'Not Completed';

        $stmt = $conn->prepare("UPDATE ordersss SET {$selected_task}_expected_date = ?, {$selected_task}_remark = ?, {$selected_task}_status = ? WHERE order_no = ?");
        $stmt->bind_param("ssss", $expected, $remarks, $status, $order_no);
        $stmt->execute();

        header("Location: update_actual_dates.php?order_no=$order_no&task=$selected_task");
        exit();
    }
}

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Actual Dates</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f2f2f2; }
        .nav-buttons a { padding: 8px 12px; background: #007bff; color: white; text-decoration: none; margin: 5px; border-radius: 4px; display: inline-block; }
        .form-container { background: white; padding: 20px; margin-top: 20px; border-radius: 8px; max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin-top: 10px; }
        input[type=text], input[type=date], textarea { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        input[type=submit] { background: green; color: white; padding: 10px 20px; border: none; margin-top: 15px; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Update Actual Dates</h2>
    <form method="GET" action="">
    <label>Enter Order Number:</label>
    <input type="text" name="order_no" value="<?= htmlspecialchars($order_no) ?>" required>

    <label>Select Task (Column):</label>
    <select name="task" required>
        <option value="">-- Select Task --</option>
        <?php foreach ($tasks as $task): ?>
            <option value="<?= $task ?>" <?= $selected_task == $task ? 'selected' : '' ?>>
                <?= ucfirst(str_replace('_', ' ', $task)) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Search">
</form>


    <?php if ($selected_task): ?>
    <div class="form-container">
        <h3>Update <?= ucfirst($selected_task) ?> for Order <?= htmlspecialchars($order_no) ?></h3>
        <form method="POST" id="completionForm">
    <label>Completed?</label>
    <select name="completed" id="completedSelect" required onchange="toggleFields()">
        <option value="">-- Select --</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>

    <label>Scheduled Date:</label>
    <input type="text" value="<?= htmlspecialchars($scheduled_date) ?>" readonly>

    <div id="actualFields" style="display:none;">
        <label>Actual Done Date:</label>
        <input type="date" name="actual_date" value="<?= htmlspecialchars($actual_done) ?>">
    </div>

    <div id="expectedFields" style="display:none;">
        <label>Expected Completion Date:</label>
        <input type="date" name="expected_date" value="<?= htmlspecialchars($expected) ?>">

        <label>Remarks:</label>
        <textarea name="remarks"><?= htmlspecialchars($remarks) ?></textarea>
    </div>

    <input type="submit" value="Submit">
    </form>

    <script>
    function toggleFields() {
        const completed = document.getElementById('completedSelect').value;
        document.getElementById('actualFields').style.display = completed === 'yes' ? 'block' : 'none';
        document.getElementById('expectedFields').style.display = completed === 'no' ? 'block' : 'none';
    }
    </script>
    </div>
    <?php endif; ?>
    <a href="index.php"><button>Home</button></a>
</body>
</html>
