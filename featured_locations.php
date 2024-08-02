<?php

// header('Content-Type: application/json');
// include("dbconnection.php");

// $query = "SELECT locations.id, locations.category_id, locations.location_name, locations.city_id, locations.start_date, locations.end_date, locations.status, locations.created_at, locations.updated_at, locations.location_image, locations.featured, city.city_name
//           FROM locations 
//           INNER JOIN city ON locations.city_id = city.id 
//           WHERE locations.featured = 1";
// $result = $conn->query($query);

// if ($result->num_rows > 0) {
//     $locations = array();
//     while ($row = $result->fetch_assoc()) {
//         $row['location_image'] = basename($row['location_image']);
//         $locations[] = $row;
//     }
//     echo json_encode($locations);
// } else {
//     echo json_encode([]);
// }

// $conn->close();


header('Content-Type: application/json');
include("dbconnection.php");

$query = "
    SELECT 
        locations.id, 
        locations.category_id, 
        locations.location_name, 
        locations.city_id, 
        locations.status, 
        locations.created_at, 
        locations.updated_at, 
        locations.featured, 
        city.city_name,
        category.categoryname,
        (SELECT img_upload.img 
         FROM img_upload 
         WHERE img_upload.location_id = locations.id 
         LIMIT 1) AS location_image
    FROM 
        locations 
    INNER JOIN 
        city ON locations.city_id = city.id
        INNER JOIN 
        category ON locations.category_id = category.id
    WHERE 
        locations.featured = 1";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $locations = array();
    while ($row = $result->fetch_assoc()) {
        $row['location_image'] = basename($row['location_image']);
        $locations[] = $row;
    }
    echo json_encode($locations);
} else {
    echo json_encode([]);
}

$conn->close();


?>
