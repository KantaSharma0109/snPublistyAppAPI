<?php
// header('Content-Type: application/json');
// include("dbconnection.php");

// // Fetch categories from the database
// $query = 'SELECT id, categoryname, categorydesc, status FROM category WHERE 1';
// $result = $conn->query($query);

// if ($result->num_rows > 0) {
//     $categories = array();
//     while($row = $result->fetch_assoc()) {
//         $categories[] = $row;
//     }
//     echo json_encode($categories);
// } else {
//     echo json_encode([]);
// }

// $conn->close();

header('Content-Type: application/json');
include("dbconnection.php");

// Check if city_id parameter is provided
if(isset($_GET['city_id'])) {
    $city_id = $_GET['city_id'];
    
    // Fetch categories based on city_id
    $query = "SELECT id, categoryname, categorydesc, status FROM category WHERE city_id = $city_id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $categories = array();
        while($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        echo json_encode($categories);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();

?>
