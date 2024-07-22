<?php
require("db.php");

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_BCRYPT);

    $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $project_description = mysqli_real_escape_string($conn, $_POST['project_description']);
    $services_offered = mysqli_real_escape_string($conn, $_POST['services_offered']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $instagram_url = mysqli_real_escape_string($conn, $_POST['instagram_url']);
    $google_maps_url = mysqli_real_escape_string($conn, $_POST['google_maps_url']);
    $facebook_url = mysqli_real_escape_string($conn, $_POST['facebook_url']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $logo_path = mysqli_real_escape_string($conn, $_POST['logo_path']);

    // Insert user data
    $sql_user = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    
    if ($conn->query($sql_user) === TRUE) {
        // Get the last inserted user ID
        $user_id = $conn->insert_id;
        
        // Insert website information
        $sql_website = "INSERT INTO website_information (
            user_id, business_name, project_description, services_offered, phone, email, address, country, 
            instagram_url, google_maps_url, facebook_url, postal_code, logo_path
        ) VALUES (
            '$user_id', '$business_name', '$project_description', '$services_offered', '$phone', '$email', 
            '$address', '$country', '$instagram_url', '$google_maps_url', '$facebook_url', '$postal_code', '$logo_path'
        )";
        
        if ($conn->query($sql_website) === TRUE) {
            echo "New user and website information registered successfully";
        } else {
            echo "Error: " . $sql_website . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_user . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>
