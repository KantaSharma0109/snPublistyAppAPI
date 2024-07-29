<?php
// header('Content-Type: application/json');
// include("dbconnection.php");

// if (isset($_GET['userId'])) {
//     $userId = $_GET['userId'];

//     $sql = "SELECT campaign_id, total_amount, received_amount FROM customer_account WHERE customer_id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $userId);
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

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    $sql = "SELECT ca.campaign_id, ca.total_amount, ca.received_amount, ct.status 
            FROM customer_account ca 
            LEFT JOIN customer_transactions ct ON ca.campaign_id = ct.campaign_id
            WHERE ca.customer_id = ? AND ct.status = 1";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
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
