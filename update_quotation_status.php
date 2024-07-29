<?php

header('Content-Type: application/json');
include("dbconnection.php");

if (isset($_POST['quotationId']) && isset($_POST['status'])) {
    $quotationId = $_POST['quotationId'];
    $status = $_POST['status'];

    $sql = "UPDATE quotation SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $status, $quotationId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["error" => "Invalid input"]);
}

$conn->close();

?>
