<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
include("dbconnection.php");

$stmt = null; // Initialize $stmt variable

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    $sql = "SELECT q.id, q.customer_id, q.category_id, q.location_id, q.start_date, q.end_date, q.total_amount, q.campaign_id, q.status, c.categoryname, l.location_name 
            FROM quotation q
            INNER JOIN category c ON q.category_id = c.id
            INNER JOIN locations l ON q.location_id = l.id
            WHERE q.customer_id = ? AND q.total_amount IS NOT NULL AND q.status NOT IN ('approved', 'rejected')"; // Add condition to filter out rows where price is not null
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
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

if ($stmt !== null) { // Check if $stmt is not null before closing
    $stmt->close();
}
$conn->close();


?>
