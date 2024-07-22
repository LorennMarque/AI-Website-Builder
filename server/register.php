<?php
require("db.php");

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_BCRYPT);
    $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $project_description = mysqli_real_escape_string($conn, $_POST['project_description']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $instagram_url = mysqli_real_escape_string($conn, $_POST['instagram_url']);
    $google_maps_url = mysqli_real_escape_string($conn, $_POST['google_maps_url']);
    $facebook_url = mysqli_real_escape_string($conn, $_POST['facebook_url']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $logo_path = mysqli_real_escape_string($conn, $_POST['logo_path']);

    $sql_user = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
    
    if ($conn->query($sql_user) === TRUE) {
        $user_id = $conn->insert_id;
        
        $sql_website = "INSERT INTO website_information (
            user_id, business_name, project_description, phone, email, address, country, 
            instagram_url, google_maps_url, facebook_url, postal_code, logo_path
        ) VALUES (
            '$user_id', '$business_name', '$project_description', '$phone', '$email', 
            '$address', '$country', '$instagram_url', '$google_maps_url', '$facebook_url', '$postal_code', '$logo_path'
        )";
        
        if ($conn->query($sql_website) === TRUE) {
            $website_id = $conn->insert_id;
            $apiKey = 'AIzaSyByLctahA8JjQ329kIg8bZRehFT_TBdiFY';

            $headerText = generateContent("Generate a single h1 for the website of business called: $business_name that is about: $project_description. You estrictly need to answer only one header, no formatting nor extra text", $apiKey);
            $aboutText = generateContent("Generate an about description for the website of business: $business_name with description: $project_description. You estrictly need to answer only the text, no formatting nor extra text", $apiKey);
            $headerText = mysqli_real_escape_string($conn, $headerText);
            $aboutText = mysqli_real_escape_string($conn, $aboutText);

            $sql_sections = "
                INSERT INTO sections (type, content, img_route, website_id, created_at, updated_at, active) VALUES
                ('hero', '$headerText', '', $website_id, NOW(), NOW(), TRUE),
                ('about', '$aboutText', '', $website_id, NOW(), NOW(), TRUE)
            ";

            if ($conn->query($sql_sections) === TRUE) {
                foreach ($_POST['service_name'] as $service_name) {
                    $serviceContent = mysqli_real_escape_string($conn, $service_name);
                    $iconText = "star";
                    // $iconText = generateContent("Generate an icon description for service: $service_name", $apiKey);
                    $descriptionText = generateContent("Generate a short description for service: $service_name  You estrictly need to answer only the text, no formatting nor extra text", $apiKey);
                    $iconText = mysqli_real_escape_string($conn, $iconText);
                    $descriptionText = mysqli_real_escape_string($conn, $descriptionText);

                    $sql_services = "
                        INSERT INTO services (website_id, content, description, icon, created_at, updated_at, active) VALUES
                        ('$website_id', '$serviceContent', '$descriptionText', '$iconText', NOW(), NOW(), TRUE)
                    ";
                    if (!$conn->query($sql_services)) {
                        echo "Error: " . $sql_services . "<br>" . $conn->error;
                    }
                }
                echo "New user, website information, sections, and services registered successfully";
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
?>
