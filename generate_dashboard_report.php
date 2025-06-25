<?php
require_once('tcpdf/tcpdf.php');
include("db_connection.php");

$startWeek = $_POST['week_start'];
$endWeek = $_POST['week_end'];

$taskList = ['fabric_date', 'cutting', 'printing', 'sewing', 'finishing'];
$taskLabels = ['Fabric Date', 'Cutting', 'Printing', 'Sewing', 'Finishing'];

$taskDetails = [];

$result = $conn->query("SELECT order_no, merch, fabric_date, cutting, printing, sewing, finishing, pro_qty FROM ordersss");

while ($row = $result->fetch_assoc()) {
    foreach ($taskList as $i => $task) {
        $label = $taskLabels[$i];
        $dateStr = $row[$task];

        if (!empty($dateStr)) {
            $dateObj = DateTime::createFromFormat('Y-m-d', $dateStr);
            $weekNum = (int)$dateObj->format("W");
            if ($weekNum >= $startWeek && $weekNum <= $endWeek) {
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

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 11);

$pdf->Write(0, "Task Report for Weeks $startWeek to $endWeek\n", '', 0, 'C', true, 0, false, false, 0);

foreach ($taskDetails as $task => $entries) {
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Write(0, "$task Tasks", '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetFont('helvetica', '', 10);

    $html = '<table border="1" cellpadding="4">
                <thead>
                    <tr style="background-color:#f2f2f2;">
                        <th><b>Week No</b></th>
                        <th><b>Order No</b></th>
                        <th><b>Merch</b></th>
                        <th><b>Production Qty</b></th>
                    </tr>
                </thead>
                <tbody>';
    foreach ($entries as $entry) {
        $html .= '<tr>
                    <td>' . $entry['week'] . '</td>
                    <td>' . $entry['order_no'] . '</td>
                    <td>' . $entry['merch'] . '</td>
                    <td>' . $entry['qty'] . '</td>
                  </tr>';
    }
    $html .= '</tbody></table>';

    $pdf->writeHTML($html, true, false, false, false, '');
}

$pdf->Output('dashboard_report.pdf', 'I');
?>
