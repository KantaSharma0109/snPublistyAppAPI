<?php
// header('Content-Type: application/json');
// include("dbconnection.php");

// if (isset($_GET['campaignId'])) {
//     $campaignId = $_GET['campaignId'];

//     $sql = "SELECT campaign_id, amount, transaction_date FROM customer_transactions WHERE campaign_id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $campaignId);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     $data = [];
//     while ($row = $result->fetch_assoc()) {
//         $data[] = $row;
//     }

//     echo json_encode($data);
// } else {
//     echo json_encode(["success" => false, "message" => "Invalid parameters"]);
// }

// $conn->close();

header('Content-Type: application/json');
include("dbconnection.php");

if (isset($_GET['campaignId'])) {
    $campaignId = $_GET['campaignId'];

    $sql = "SELECT campaign_id, amount, transaction_date 
            FROM customer_transactions 
            WHERE campaign_id = ? AND status = 1";  // Adjusted query to include status check
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $campaignId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
} else {
    echo json_encode(["success" => false, "message" => "Invalid parameters"]);
}

$conn->close();

?>
