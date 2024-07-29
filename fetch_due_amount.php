<?php
header('Content-Type: application/json');
include("dbconnection.php");


// Fetch data from customer_account table
$userId = $_GET['userId'];
$sql = "SELECT campaign_id, total_amount, received_amount FROM customer_account WHERE customer_id = '$userId'";
$result = $conn->query($sql);

$quotations = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $campaignId = $row['campaign_id'];
        $totalAmount = $row['total_amount'];
        $receivedAmount = $row['received_amount'];
        $dueAmount = $totalAmount - $receivedAmount;

        $quotations[] = array(
            'campaign_id' => $campaignId,
            'due_amount' => $dueAmount,
        );
    }
}

echo json_encode($quotations);

$conn->close();




?>
