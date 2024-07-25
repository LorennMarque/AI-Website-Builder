<?php
require("db.php");
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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

function sendWelcomeEmail($email, $firstName) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST'); // Set the SMTP server to send through
        $mail->SMTPAuth = getenv('SMTP_AUTH') === 'true';
        $mail->Username = getenv('SMTP_USERNAME'); // SMTP username
        $mail->Password = getenv('SMTP_PASSWORD'); // SMTP password
        $mail->SMTPSecure = getenv('SMTP_SECURE');
        $mail->Port = getenv('SMTP_PORT');

        //Recipients
        $mail->setFrom(getenv('SMTP_USERNAME'), 'Lorenzo');
        $mail->addAddress($email, $firstName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Te damos la bienvenida!';
        $mail->Body = "<h1>Felicitaciones por crear tu sitio web, $firstName!</h1><p>Para modificarlo puedes acceder en cualquier momento a URL con tu usuario y contraseña, si necesitas ayuda puedes contactarte con lorenzomarquesini@gmail.com</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_BCRYPT);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $project_description = mysqli_real_escape_string($conn, $_POST['project_description']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
    $country = isset($_POST['country']) ? mysqli_real_escape_string($conn, $_POST['country']) : '';
    $instagram_url = isset($_POST['instagram_url']) ? mysqli_real_escape_string($conn, $_POST['instagram_url']) : '';
    $google_maps_url = isset($_POST['google_maps_url']) ? mysqli_real_escape_string($conn, $_POST['google_maps_url']) : '';
    $facebook_url = isset($_POST['facebook_url']) ? mysqli_real_escape_string($conn, $_POST['facebook_url']) : '';
    $postal_code = isset($_POST['postal_code']) ? mysqli_real_escape_string($conn, $_POST['postal_code']) : '';
    $logo_path = isset($_POST['logo_path']) ? mysqli_real_escape_string($conn, $_POST['logo_path']) : '';

    $sql_user = "INSERT INTO users (email, password, first_name, last_name) VALUES ('$email', '$password', '$first_name', '$last_name')";
    
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
            $apiKey = getenv('GEMINI_API_KEY');

            $headerText = generateContent("Generate a single h1 for the website of business called: $business_name that is about: $project_description. You estrictly need to answer only one header, no formatting nor extra text nor markdown", $apiKey);
            $subtitleText = generateContent("Generate a single p for the title: '$headerText' of website of business called: $business_name that is about: $project_description. You estrictly need to answer only one short description, no formatting nor extra text", $apiKey);
            $aboutText = generateContent("Generate an expert and SEO description for the about secion of the website of business: $business_name with description: $project_description. You estrictly need to answer only the necesary text, no formatting nor extra text", $apiKey);
            $headerText = mysqli_real_escape_string($conn, $headerText);
            $aboutText = mysqli_real_escape_string($conn, $aboutText);

            $sql_sections = "
                INSERT INTO sections (type, content, website_id, created_at, updated_at, active) VALUES
                ('hero', '$headerText',  $website_id, NOW(), NOW(), TRUE),
                ('subtitle', '$subtitleText',  $website_id, NOW(), NOW(), TRUE),
                ('about', '$aboutText',  $website_id, NOW(), NOW(), TRUE)
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
                session_start();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['website_id'] = $website_id;
                sendWelcomeEmail($email, $first_name);
                echo json_encode(array("success" => true, "website_id" => $website_id));
            } else {
                echo json_encode(array("error" => $sql_sections, "message" => $conn->error));
            }
        } else {
            echo json_encode(array("error" => $sql_website, "message" => $conn->error));
        }
    } else {
        echo json_encode(array("error" => $sql_user, "message" => $conn->error));
    }
    $conn->close();
}