<?php
session_start();
include("db_connection.php");

require_once('tcpdf/tcpdf.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    die("Unauthorized access.");
}

$merch = $_SESSION['username'];

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// ===== Section 1: User Dashboard =====
$pdf->Write(0, "User Dashboard Report for $merch", '', 0, 'C', true, 0, false, false, 0);
$pdf->Ln(5);

$html = '<h3>Assigned Orders</h3><table border="1" cellpadding="4">
<tr>
<th>Order No</th><th>Buyer</th><th>Po_Qty</th><th>Pro_Qty</th><th>Item</th><th>Po_Date</th>
<th>Ship_Date</th><th>Ck_Date</th><th>Fabric_Date</th><th>Trims_Date</th><th>Buyer_pp</th>
<th>Testing</th><th>Buyer_pp_Approval</th><th>Qa_pp</th><th>Qa_pp_Approval</th><th>Size_set</th><th>Ship_sample</th>
</tr>';

$stmt1 = $conn->prepare("SELECT * FROM ordersss WHERE merch = ?");
$stmt1->bind_param("s", $merch);
$stmt1->execute();
$result1 = $stmt1->get_result();

while ($row = $result1->fetch_assoc()) {
    $html .= '<tr>';
    foreach (['order_no', 'buyer', 'po_qty', 'pro_qty', 'item', 'po_date', 'ship_date', 'ck_date', 'fabric_date', 'trims_date', 'buyer_pp', 'testing', 'buyer_pp_approval', 'qa_pp', 'qa_pp_approval', 'size_set', 'ship_sample'] as $col) {
        $html .= '<td>' . htmlspecialchars($row[$col]) . '</td>';
    }
    $html .= '</tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// ===== Section 2: Team Details =====
$pdf->AddPage();
$pdf->Write(0, "Team Task Assignments for $merch", '', 0, 'C', true, 0, false, false, 0);
$pdf->Ln(5);

$html2 = '<h3>Team Tasks</h3><table border="1" cellpadding="4">
<tr><th>Order No</th><th>Task</th><th>Member</th><th>Last Date</th><th>Status</th></tr>';

$stmt2 = $conn->prepare("SELECT * FROM team_taskss WHERE merch = ?");
$stmt2->bind_param("s", $merch);
$stmt2->execute();
$result2 = $stmt2->get_result();

while ($row = $result2->fetch_assoc()) {
    $html2 .= '<tr>';
    $html2 .= '<td>' . htmlspecialchars($row['order_no']) . '</td>';
    $html2 .= '<td>' . htmlspecialchars($row['task']) . '</td>';
    $html2 .= '<td>' . htmlspecialchars($row['member_name']) . '</td>';
    $html2 .= '<td>' . htmlspecialchars($row['last_date']) . '</td>';
    $html2 .= '<td>' . htmlspecialchars($row['completion_status']) . '</td>';
    $html2 .= '</tr>';
}
$html2 .= '</table>';
$pdf->writeHTML($html2, true, false, true, false, '');

$pdf->Output("Report_$merch.pdf", 'I');
