<?php
header('Content-Type: application/json');
include("dbconnection.php");

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    $currentDate = date('Y-m-d');

    // Fetch expired quotations
    $sql = "SELECT q.*, c.categoryname, l.location_name 
            FROM quotation q 
            INNER JOIN category c ON q.category_id = c.id
            INNER JOIN locations l ON q.location_id = l.id
            WHERE q.customer_id = ? AND q.end_date < ? AND q.status IN ('approved', 'rejected')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userId, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $quotations = [];
    while ($row = $result->fetch_assoc()) {
        $quotations[] = $row;
    }

    echo json_encode($quotations);
} else {
    echo json_encode(["error" => "userId not provided"]);
}

if ($stmt !== null) {
    $stmt->close();
}
$conn->close();
?>
