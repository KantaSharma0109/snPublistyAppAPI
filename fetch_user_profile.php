<?php
header('Content-Type: application/json');
include("dbconnection.php");

// Assuming the user ID is passed as a query parameter
$userId = $_GET['userId'];

// Fetch user profile from the database
$sql = "SELECT `name`, `email`, `number`, `city` FROM `customer` WHERE `id` = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo "0 results";
}

$conn->close();
?>
