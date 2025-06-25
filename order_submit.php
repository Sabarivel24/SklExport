<?php
include 'config.php'; // Make sure this connects correctly

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect POST data
    $order_no = uniqid("ORD");
    $buyer = $_POST['buyer'];
    $merch = $_POST['merch'];
    $po_qty = (int)$_POST['po_qty'];
    $pro_qty = (int)$_POST['pro_qty'];
    $fabric = $_POST['fabric'];
    $column1 = $_POST['column1'];
    $item = $_POST['item'];
    $po_date = $_POST['po_date'];
    $ship_date = $_POST['ship_date'];

    // Calculate days between po_date and ship_date
    $po = new DateTime($po_date);
    $ship = new DateTime($ship_date);
    $diff = $po->diff($ship)->days;

    // Set all dates conditionally
    if ($diff < 25) {
    // Probabilistic assignment based on total days
    $fabric_date = $po->modify('+' . floor($diff * 0.3) . ' days')->format('Y-m-d');
    $trims_date = $fabric_date;

    $po = new DateTime($po_date); // reset
    $buyer_pp = $po->modify('+' . floor($diff * 0.5) . ' days')->format('Y-m-d');
    $testing = $buyer_pp;
    $buyer_pp_approval = $buyer_pp;
    $qa_pp = $buyer_pp;
    $qa_pp_approval = $buyer_pp;
    $size_set = $buyer_pp;

    $po = new DateTime($po_date); // reset
    $ck_date = $po->modify('+' . floor($diff * 0.6) . ' days')->format('Y-m-d');

    $po = new DateTime($po_date); // reset
    $cutting = $po->modify('+' . floor($diff * 0.8) . ' days')->format('Y-m-d');
    $printing = $cutting;
    $sewing = $cutting;
    $finishing = $cutting;
    $fi = $cutting;

    $po = new DateTime($po_date); // reset
    $ship_sample = $po->modify('+' . floor($diff * 0.85) . ' days')->format('Y-m-d');
    }
    else if ($diff >25 && $diff <70) {
        $ck_date = $ship->modify('-18 days')->format('Y-m-d');
        $fabric_date = (new DateTime($ck_date))->modify('-12 days')->format('Y-m-d');
        $trims_date = $fabric_date;

        $buyer_pp = (new DateTime($ck_date))->modify('-10 days')->format('Y-m-d');
        $testing = (new DateTime($ck_date))->modify('-9 days')->format('Y-m-d');
        $buyer_pp_approval = (new DateTime($ck_date))->modify('-6 days')->format('Y-m-d');
        $qa_pp = (new DateTime($ck_date))->modify('-4 days')->format('Y-m-d');
        $qa_pp_approval = (new DateTime($ck_date))->modify('-1 days')->format('Y-m-d');
        $size_set = (new DateTime($ck_date))->modify('+1 days')->format('Y-m-d');
        $cutting = (new DateTime($size_set))->modify('+1 days')->format('Y-m-d');
        $printing = (new DateTime($cutting))->modify('+1 days')->format('Y-m-d');
        $sewing = (new DateTime($printing))->modify('+1 days')->format('Y-m-d');
        $finishing = (new DateTime($sewing))->modify('+1 days')->format('Y-m-d');
        $fi = (new DateTime($finishing))->modify('+1 days')->format('Y-m-d');
        $ship_sample = (new DateTime($fi))->modify('-3 days')->format('Y-m-d');
    } elseif ($diff == 70) {
         $ck_date = $ship->modify('-30 days')->format('Y-m-d');
        $fabric_date = (new DateTime($ck_date))->modify('-13 days')->format('Y-m-d');
        $trims_date = $fabric_date;

        $buyer_pp = (new DateTime($ck_date))->modify('-11 days')->format('Y-m-d');
        $testing = (new DateTime($ck_date))->modify('-9 days')->format('Y-m-d');
        $buyer_pp_approval = (new DateTime($ck_date))->modify('-6 days')->format('Y-m-d');
        $qa_pp = (new DateTime($ck_date))->modify('-4 days')->format('Y-m-d');
        $qa_pp_approval = (new DateTime($ck_date))->modify('-1 days')->format('Y-m-d');
        $size_set = (new DateTime($ck_date))->modify('+2 days')->format('Y-m-d');
        $cutting = (new DateTime($size_set))->modify('+2 days')->format('Y-m-d');
        $printing = (new DateTime($cutting))->modify('+3 days')->format('Y-m-d');
        $sewing = (new DateTime($printing))->modify('+3 days')->format('Y-m-d');
        $finishing = (new DateTime($sewing))->modify('+2 days')->format('Y-m-d');
        $fi = (new DateTime($finishing))->modify('+1 days')->format('Y-m-d');
        $ship_sample = (new DateTime($fi))->modify('-3 days')->format('Y-m-d');
    } else {
         $ck_date = $ship->modify('-35 days')->format('Y-m-d');
        $fabric_date = (new DateTime($ck_date))->modify('-13 days')->format('Y-m-d');
        $trims_date = $fabric_date;

        $buyer_pp = (new DateTime($ck_date))->modify('-11 days')->format('Y-m-d');
        $testing = (new DateTime($ck_date))->modify('-9 days')->format('Y-m-d');
        $buyer_pp_approval = (new DateTime($ck_date))->modify('-6 days')->format('Y-m-d');
        $qa_pp = (new DateTime($ck_date))->modify('-4 days')->format('Y-m-d');
        $qa_pp_approval = (new DateTime($ck_date))->modify('-1 days')->format('Y-m-d');
        $size_set = (new DateTime($ck_date))->modify('+2 days')->format('Y-m-d');
        $cutting = (new DateTime($size_set))->modify('+4 days')->format('Y-m-d');
        $printing = (new DateTime($cutting))->modify('+4 days')->format('Y-m-d');
        $sewing = (new DateTime($printing))->modify('+4 days')->format('Y-m-d');
        $finishing = (new DateTime($sewing))->modify('+2 days')->format('Y-m-d');
        $fi = (new DateTime($finishing))->modify('+1 days')->format('Y-m-d');
        $ship_sample = (new DateTime($fi))->modify('-3 days')->format('Y-m-d');
    }

    // Prepare insert query
    $sql = "INSERT INTO ordersss (
        order_no, buyer, merch, po_qty, pro_qty, fabric, column1, item, 
        po_date, ship_date, ck_date, fabric_date, trims_date, buyer_pp, testing, 
        buyer_pp_approval, qa_pp, qa_pp_approval, size_set, cutting, printing, 
        sewing, finishing, fi, ship_sample
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sssddssssssssssssssssssss",
        $order_no, $buyer, $merch, $po_qty, $pro_qty, $fabric, $column1, $item,
        $po_date, $ship_date, $ck_date, $fabric_date, $trims_date, $buyer_pp, $testing,
        $buyer_pp_approval, $qa_pp, $qa_pp_approval, $size_set, $cutting, $printing,
        $sewing, $finishing, $fi, $ship_sample
    );

    if ($stmt->execute()) {
        echo "<script>alert('Order submitted successfully!'); window.location.href='orders.php';</script>";
        //header("Location: index.php");
        //exit();
    } else {
        echo "Insert failed: " . $stmt->error;
    }
    //if ($stmt->execute()) {
    // Redirect to index.php on success
    //header("Location: index.php");
    //exit();
//} else {
  //  echo "Error submitting order: " . $stmt->error;
//}

    $stmt->close();
    $conn->close();
}
?>
