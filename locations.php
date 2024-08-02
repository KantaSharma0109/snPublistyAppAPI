<?php


header('Content-Type: application/json');
include("dbconnection.php");

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

if ($category_id > 0) {
    $query = "SELECT locations.id, locations.category_id, locations.location_name, locations.city_id, locations.status, locations.available_status, locations.created_at, locations.updated_at, locations.featured, city.city_name
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

            // Add available_status and end date if available_status is 0
            if ($row['available_status'] == 0) {
                $endDateQuery = "SELECT end_date FROM quotation WHERE location_id = $location_id AND category_id = {$row['category_id']} AND city_id = {$row['city_id']}";
                $endDateResult = $conn->query($endDateQuery);

                if ($endDateResult->num_rows > 0) {
                    $endDateRow = $endDateResult->fetch_assoc();
                    $row['end_date'] = $endDateRow['end_date'];
                } else {
                    $row['end_date'] = null;
                }
            } else {
                $row['end_date'] = null;
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
