<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Entry | SKL Exports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background: #28a745;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            margin-top: 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .success {
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>

<h2>Order Entry Form</h2>

<?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <p class="success">Order submitted successfully!</p>
<?php endif; ?>

<form action="order_submit.php" method="POST">
    <label>Buyer Name</label>
    <input type="text" name="buyer" placeholder="Buyer Name" required>
    <label>Merchandiser Name</label>
    <input type="text" name="merch" placeholder="Merchandiser Name" required>
    <label>PO Quantity</label>
    <input type="number" name="po_qty" placeholder="PO Quantity" required>
    <label>Production Quantity</label>
    <input type="number" name="pro_qty" placeholder="Production Quantity" required>
    <label>Fabric</label>
    <input type="text" name="fabric" placeholder="Fabric" required>
    <label>Column1</label>
    <input type="text" name="column1" placeholder="Column1" required>
    <label>Item</label>
    <input type="text" name="item" placeholder="Item" required>
    <label>PO Date:</label>
    <input type="date" name="po_date" required>
    <label>Ship Date:</label>
    <input type="date" name="ship_date" required>
    <button type="submit">Submit Order</button>
</form>
<div style="text-align: center; margin-top: 30px;">
    <a href="index.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Home</a>
    <a href="work_details.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Work Details</a>
    <a href="dashboard_chart.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Dashboard</a>
    <a href="create_user.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Create User</a>
    </div>

</body>
</html>
