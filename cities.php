<?php
header('Content-Type: application/json');
include("dbconnection.php");

$base_url = 'http://192.168.29.203:8080/mobilelogin_api/';// Update with your actual base URL

// SQL query to fetch city names and image paths
$sql = "SELECT id, city_name, city_image FROM city";
$result = $conn->query($sql);

$cities = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $cities[] = array(
            'id' => $row['id'],
            'city_name' => $row['city_name'],
            'city_image' => $base_url . $row['city_image']
        );
    }
} else {
    echo json_encode(array()); // Return an empty array if no results
}

$conn->close();

// Output cities in JSON format
echo json_encode($cities);
?>
