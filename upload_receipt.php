<?php

// header('Content-Type: application/json');
// include("dbconnection.php");

// if (isset($_POST['userId']) && isset($_POST['campaignId']) && isset($_POST['amount']) && isset($_POST['referenceId']) && isset($_FILES['receiptImage'])) {
//     $userId = $_POST['userId'];
//     $campaignId = $_POST['campaignId'];
//     $amount = $_POST['amount'];
//     $referenceId = $_POST['referenceId'];
//     $receiptImage = $_FILES['receiptImage'];

//     $targetDir = "uploads/";
//     if (!is_dir($targetDir)) {
//         mkdir($targetDir, 0777, true);
//     }
//     $targetFile = $targetDir . basename($receiptImage["name"]);

//     if (move_uploaded_file($receiptImage["tmp_name"], $targetFile)) {
//         // Insert into customer_transactions
//         $sql = "INSERT INTO customer_transactions (customer_id, campaign_id, amount, receipt_image, transaction_date, reference_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), ?, NOW(), NOW())";
//         $stmt = $conn->prepare($sql);
//         if ($stmt) {
//             $stmt->bind_param("ssdss", $userId, $campaignId, $amount, $targetFile, $referenceId);
//             if ($stmt->execute()) {
//                 // Calculate total received amount for the same campaign_id and customer_id
//                 $totalSql = "SELECT SUM(amount) as total_received_amount FROM customer_transactions WHERE campaign_id = ? AND customer_id = ?";
//                 $totalStmt = $conn->prepare($totalSql);
//                 if ($totalStmt) {
//                     $totalStmt->bind_param("ss", $campaignId, $userId);
//                     $totalStmt->execute();
//                     $result = $totalStmt->get_result();
//                     $row = $result->fetch_assoc();
//                     $totalReceivedAmount = $row['total_received_amount'];
//                     $totalStmt->close();

//                     // Update customer_account with the new total received amount
//                     $updateSql = "UPDATE customer_account SET received_amount = ? WHERE campaign_id = ? AND customer_id = ?";
//                     $updateStmt = $conn->prepare($updateSql);
//                     if ($updateStmt) {
//                         $updateStmt->bind_param("dss", $totalReceivedAmount, $campaignId, $userId);
//                         if ($updateStmt->execute()) {
//                             echo json_encode(["success" => true, "message" => "Receipt uploaded and account updated successfully"]);
//                         } else {
//                             echo json_encode(["success" => false, "message" => "Failed to update customer account"]);
//                         }
//                         $updateStmt->close();
//                     } else {
//                         echo json_encode(["success" => false, "message" => "Failed to prepare update statement"]);
//                     }
//                 } else {
//                     echo json_encode(["success" => false, "message" => "Failed to calculate total received amount"]);
//                 }
//             } else {
//                 echo json_encode(["success" => false, "message" => "Failed to upload receipt"]);
//             }
//             $stmt->close();
//         } else {
//             echo json_encode(["success" => false, "message" => "Failed to prepare insert statement"]);
//         }
//     } else {
//         echo json_encode(["success" => false, "message" => "Failed to move uploaded file"]);
//     }
// } else {
//     echo json_encode(["success" => false, "message" => "Invalid parameters"]);
// }

// $conn->close();



header('Content-Type: application/json');
include("dbconnection.php");

if (isset($_POST['userId']) && isset($_POST['campaignId']) && isset($_POST['amount']) && isset($_POST['referenceId']) && isset($_FILES['receiptImage'])) {
    $userId = $_POST['userId'];
    $campaignId = $_POST['campaignId'];
    $amount = $_POST['amount'];
    $referenceId = $_POST['referenceId'];
    $receiptImage = $_FILES['receiptImage'];

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($receiptImage["name"]);

    if (move_uploaded_file($receiptImage["tmp_name"], $targetFile)) {
        // Insert into customer_transactions with status 0
        $sql = "INSERT INTO customer_transactions (customer_id, campaign_id, amount, receipt_image, transaction_date, reference_id, created_at, updated_at, status) VALUES (?, ?, ?, ?, NOW(), ?, NOW(), NOW(), 0)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssdss", $userId, $campaignId, $amount, $targetFile, $referenceId);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Receipt uploaded successfully"]);
                // Successfully inserted, handle status update here if needed
                // Example: updateStatus($conn, $campaignId, $userId);

            } else {
                echo json_encode(["success" => false, "message" => "Failed to upload receipt"]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Failed to prepare insert statement"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to move uploaded file"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid parameters"]);
}

$conn->close();


?>

<!-- 
//added this triggre in table database
DELIMITER $$
CREATE TRIGGER update_received_amount AFTER UPDATE ON customer_transactions
FOR EACH ROW
BEGIN
    IF NEW.status = 1 AND OLD.status = 0 THEN
        -- Calculate total received amount
        SET @totalReceivedAmount = (SELECT SUM(amount) FROM customer_transactions WHERE campaign_id = NEW.campaign_id AND customer_id = NEW.customer_id AND status = 1);

        -- Update customer_account
        UPDATE customer_account SET received_amount = @totalReceivedAmount WHERE campaign_id = NEW.campaign_id AND customer_id = NEW.customer_id;
    END IF;
END$$
DELIMITER ; -->
