<?php

header('Content-Type: application/json');
include("dbconnection.php");

$response = array();

if (isset($_POST['mobile_number'])) {
    $mobile_number = $_POST['mobile_number'];

    // Check if the mobile number exists in the register table
    $user_check_query = "SELECT * FROM register WHERE number = '$mobile_number'";
    $user_result = $conn->query($user_check_query);

    if ($user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
        $response['success'] = true;
        $response['role'] = 'admin';
        $response['user_id'] = $user_data['id'];  // Assuming 'id' is the column name for user ID
        $response['_user_name'] = $user_data['name']; 
    } else {
        // Check if the mobile number exists in the customer table
        $customer_check_query = "SELECT * FROM customer WHERE number = '$mobile_number'";
        $customer_result = $conn->query($customer_check_query);

        if ($customer_result->num_rows > 0) {
            $customer_data = $customer_result->fetch_assoc();
            $response['success'] = true;
            $response['role'] = 'customer';
            $response['user_id'] = $customer_data['id'];  // Assuming 'id' is the column name for user ID
            $response['user_name'] = $customer_data['name']; 
        } else {
            $response['success'] = false;
            $response['message'] = 'Your number is not registered. Contact admin, admin number is 1234567890';
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Mobile number is required';
}

echo json_encode($response);
$conn->close();



?>
