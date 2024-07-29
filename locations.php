<?php


header('Content-Type: application/json');
include("dbconnection.php");

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

if ($category_id > 0) {
    $query = "SELECT locations.id, locations.category_id, locations.location_name, locations.city_id, locations.start_date, locations.end_date, locations.status, locations.created_at, locations.updated_at, locations.featured, city.city_name
    FROM locations 
    INNER JOIN city ON locations.city_id = city.id 
    WHERE locations.category_id = $category_id AND locations.status = '1'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $locations = array();
        while($row = $result->fetch_assoc()) {
            $location_id = $row['id'];
            $imageQuery = "SELECT img FROM img_upload WHERE location_id = $location_id";
            $imageResult = $conn->query($imageQuery);

            $images = array();
            if ($imageResult->num_rows > 0) {
                while($imageRow = $imageResult->fetch_assoc()) {
                    $images[] = basename($imageRow['img']);
                }
            }

            $row['location_images'] = $images;
            $locations[] = $row;
        }
        echo json_encode($locations);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();



?>
