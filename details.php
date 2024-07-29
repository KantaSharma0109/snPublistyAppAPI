<?php

header('Content-Type: application/json');
include("dbconnection.php");

if (isset($_GET['location_id'])) {
    $location_id = intval($_GET['location_id']);
    $query = "SELECT id, location_id, country, state, height, width, area, traffic_from, traffic_to, created_at, updated_at FROM details WHERE location_id = $location_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $details = array();
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }
        echo json_encode($details);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();

?>
