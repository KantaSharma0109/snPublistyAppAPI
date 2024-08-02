 <?php
// quote.php
// header("Content-Type: application/json; charset=UTF-8");

// $input = json_decode(file_get_contents('php://input'), true);
// $userId=$input['userId'];
// // $categoryName = $input['categoryName'];
// // $location = $input['location'];
// $categoryId = $input['categoryId'];
// $locationId = $input['locationId'];
// $cityId = $input['cityId'];
// $startDate = $input['startDate'];
// $endDate = $input['endDate'];
// $campaignId = $input['campaignId']; // New field
// $materialNeeded = isset($input['materialNeeded']) ? $input['materialNeeded'] : null;
// $materialId = isset($input['materialId']) ? $input['materialId'] : null;

// // Database connection
// include("dbconnection.php");

// // Function to generate a unique 4-digit campaign ID
// function generateUniqueCampaignId($conn) {
//     do {
//         $campaignId = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
//         $sql_check = "SELECT * FROM quotation WHERE campaign_id='$campaignId'";
//         $result = $conn->query($sql_check);
//     } while ($result->num_rows > 0);
//     return $campaignId;
// }



// // Check if a record with the same categoryName and location already exists
// $sql_check = "SELECT * FROM quotation WHERE  category_id='$categoryId' AND location_id='$locationId' AND customer_id='$userId' AND end_date='$endDate' AND start_date='$startDate'";
// $result = $conn->query($sql_check);

// if ($result->num_rows > 0) {
//     // Update the existing record
//     $sql = "UPDATE quotation SET start_date='$startDate', end_date='$endDate' WHERE category_id='$categoryName' AND location_id='$location' AND customer_id='$userId'";
// } else {

//      // Generate a unique campaign ID if not provided
//      if (empty($campaignId)) {
//         $campaignId = generateUniqueCampaignId($conn);
//     }
//     // Insert a new record
//       $sql = "INSERT INTO quotation (customer_id, category_id, location_id, city_id, start_date, end_date, campaign_id, material_needed, material_id)
//         VALUES ('$userId', '$categoryId', '$locationId', '$cityId', '$startDate', '$endDate', '$campaignId', '$materialNeeded', '$materialId')";

// }

// if ($conn->query($sql) === TRUE) {
//     http_response_code(200);
//     echo json_encode(["message" => "Record added/updated successfully"]);
// } else {
//     http_response_code(500);
//     echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
// }

// $conn->close();

header("Content-Type: application/json; charset=UTF-8");

$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['userId'];
$categoryId = $input['categoryId'];
$locationId = $input['locationId'];
$cityId = $input['cityId'];
$startDate = $input['startDate'];
$endDate = $input['endDate'];
$campaignId = $input['campaignId']; // New field
$materialNeeded = isset($input['materialNeeded']) ? $input['materialNeeded'] : null;
$materialId = isset($input['materialId']) ? $input['materialId'] : null;

// Database connection
include("dbconnection.php");

// Function to generate a unique 4-digit campaign ID
function generateUniqueCampaignId($conn) {
    do {
        $campaignId = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $sql_check = "SELECT * FROM quotation WHERE campaign_id='$campaignId'";
        $result = $conn->query($sql_check);
    } while ($result->num_rows > 0);
    return $campaignId;
}

// Check if a record with the same categoryId, locationId, and userId already exists
$sql_check = "SELECT * FROM quotation WHERE category_id='$categoryId' AND location_id='$locationId' AND customer_id='$userId' AND end_date='$endDate' AND start_date='$startDate'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    // Update the existing record
    $sql = "UPDATE quotation SET start_date='$startDate', end_date='$endDate' WHERE category_id='$categoryId' AND location_id='$locationId' AND customer_id='$userId'";
} else {
    // Generate a unique campaign ID if not provided
    if (empty($campaignId)) {
        $campaignId = generateUniqueCampaignId($conn);
    }
    // Insert a new record
    $sql = "INSERT INTO quotation (customer_id, category_id, location_id, city_id, start_date, end_date, campaign_id, material_needed, material_id)
        VALUES ('$userId', '$categoryId', '$locationId', '$cityId', '$startDate', '$endDate', '$campaignId', '$materialNeeded', '$materialId')";
}

if ($conn->query($sql) === TRUE) {
    // Update the available_status in the locations table
    $update_location_sql = "UPDATE locations SET available_status=0 WHERE id='$locationId' AND category_id='$categoryId' AND city_id='$cityId'";
    if ($conn->query($update_location_sql) === TRUE) {
        http_response_code(200);
        echo json_encode(["message" => "Record added/updated successfully and location availability updated"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Record added/updated successfully but failed to update location availability"]);
    }
} else {
    http_response_code(500);
    echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();

?> 
