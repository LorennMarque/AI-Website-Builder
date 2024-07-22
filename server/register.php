<?php
require("db.php");

// Function to generate content using the API
function generateContent($textPrompt, $apiKey) {
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $textPrompt]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return 'Error: ' . curl_error($ch);
    }
    curl_close($ch);

    $responseData = json_decode($response, true);

    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        return $responseData['candidates'][0]['content']['parts'][0]['text'];
    } else {
        return 'No content generated.';
    }
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch input data
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
    $sql_user = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
    
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
            // Generate content
            $apiKey = 'AIzaSyByLctahA8JjQ329kIg8bZRehFT_TBdiFY'; // Replace with your actual API key
            $headerText = generateContent("Generate a header for business: $business_name", $apiKey);
            $aboutText = generateContent("Generate an about section for business: $business_name with description: $project_description", $apiKey);
            $servicesText = generateContent("Generate a services section for business: $business_name with services: $services_offered", $apiKey);

            // Escape generated content for SQL
            $headerText = mysqli_real_escape_string($conn, $headerText);
            $aboutText = mysqli_real_escape_string($conn, $aboutText);
            $servicesText = mysqli_real_escape_string($conn, $servicesText);

            // Insert generated sections
            $sql_sections = "
                INSERT INTO sections (type, content, img_route, website_id, created_at, updated_at, active) VALUES
                ('hero', '$headerText', '', $user_id, NOW(), NOW(), TRUE),
                ('about', '$aboutText', '', $user_id, NOW(), NOW(), TRUE),
                ('services', '$servicesText', '', $user_id, NOW(), NOW(), TRUE)
            ";

            if ($conn->query($sql_sections) === TRUE) {
                echo "New user, website information, and sections registered successfully";
            } else {
                echo "Error: " . $sql_sections . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql_website . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_user . "<br>" . $conn->error;
    }
    
    $conn->close();
}