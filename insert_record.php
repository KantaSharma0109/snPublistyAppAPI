<?php

include("dbconnection.php"); // Include database connection

// Check if mobile number is provided
if(isset($_POST["mobile_number"] ) ) {
    $mobile_number = $_POST["mobile_number"];
} else {
    echo json_encode(["success" => false, "message" => "Mobile number not provided"]);
    exit;
}

// Connect to the database
$con = dbconnection(); // Assume dbconnection.php contains your database connection code

// Check if mobile number already exists
$check_query = "SELECT * FROM `login_data` WHERE `mobile_number` = '$mobile_number'";
$check_result = mysqli_query($con, $check_query);
if(mysqli_num_rows($check_result) > 0) {
    // Retrieve role information from the database
    $row = mysqli_fetch_assoc($check_result);
    $role = $row['role'];

    // Prepare response with role information
    $response = ["success" => true, "role" => $role];
    echo json_encode($response);
    exit;
}

// Determine role based on mobile number
$role = 'user'; // Default role is user
if ($mobile_number === '9783969401') {
    $role = 'admin';
}

// Insert mobile number and role into database
$insert_query = "INSERT INTO `login_data`(`mobile_number`, `role`) VALUES ('$mobile_number', '$role')";
$insert_result = mysqli_query($con, $insert_query);

// Prepare response
$response = [];
if($insert_result) {
    $response["success"] = true;
    $response["role"] = $role; // Include role information in the responses
} else {
    $response["success"] = false;
    $response["message"] = "Failed to insert mobile number";
}

// Return JSON response
echo json_encode($response);

?>





