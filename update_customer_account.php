<?php
header('Content-Type: application/json');
include("dbconnection.php");

if (isset($_POST['userId']) && isset($_POST['campaignId']) && isset($_POST['totalAmount'])) {
    $userId = $_POST['userId'];
    $campaignId = $_POST['campaignId'];
    $totalAmount = $_POST['totalAmount'];

    // Check if a record already exists
    $checkSql = "SELECT * FROM customer_account WHERE customer_id = ? AND campaign_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ss", $userId, $campaignId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the existing record
        $updateSql = "UPDATE customer_account SET total_amount = ?, updated_at = NOW() WHERE customer_id = ? AND campaign_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("dss", $totalAmount, $userId, $campaignId);
    } else {
        // Insert a new record
        $insertSql = "INSERT INTO customer_account (customer_id, campaign_id, total_amount, received_amount, created_at, updated_at) VALUES (?, ?, ?, 0, NOW(), NOW())";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ssd", $userId, $campaignId, $totalAmount);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Record updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update record"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid parameters"]);
}

$conn->close();
?>
