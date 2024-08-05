<?php
header('Content-Type: application/json');
include("dbconnection.php");

$customer_id = $_POST['customer_id'];
$feedback_text = $_POST['feedback_text'];

$sql = "INSERT INTO feedback (customer_id, feedback_text, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $customer_id, $feedback_text);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit feedback']);
}

$stmt->close();
$conn->close();
?>
