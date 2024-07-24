<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $website_id = mysqli_real_escape_string($conn, $_POST['website_id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $instagram_url = mysqli_real_escape_string($conn, $_POST['instagram_url']);
    $facebook_url = mysqli_real_escape_string($conn, $_POST['facebook_url']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $primary_color = mysqli_real_escape_string($conn, $_POST['primary_color']);
    $secondary_color = mysqli_real_escape_string($conn, $_POST['secondary_color']);

    // Retrieve current paths from the database if no new files are uploaded
    $sql_get_paths = "SELECT logo_path, banner_path FROM website_information WHERE id = $website_id";
    $result = $conn->query($sql_get_paths);
    $current_paths = $result->fetch_assoc();

    // Handle file uploads
    $logo_path = isset($_FILES['logo_path']) && $_FILES['logo_path']['error'] == UPLOAD_ERR_OK ? 
        'uploads/logos/' . basename($_FILES['logo_path']['name']) : 
        $current_paths['logo_path'];

    if (isset($_FILES['logo_path']) && $_FILES['logo_path']['error'] == UPLOAD_ERR_OK) {
        $logo_dir = 'uploads/logos/';
        if (!is_dir($logo_dir)) {
            mkdir($logo_dir, 0777, true);
        }
        move_uploaded_file($_FILES['logo_path']['tmp_name'], $logo_path);
    }

    $banner_path = isset($_FILES['banner_path']) && $_FILES['banner_path']['error'] == UPLOAD_ERR_OK ? 
        'uploads/banners/' . basename($_FILES['banner_path']['name']) : 
        $current_paths['banner_path'];

    if (isset($_FILES['banner_path']) && $_FILES['banner_path']['error'] == UPLOAD_ERR_OK) {
        $banner_dir = 'uploads/banners/';
        if (!is_dir($banner_dir)) {
            mkdir($banner_dir, 0777, true);
        }
        move_uploaded_file($_FILES['banner_path']['tmp_name'], $banner_path);
    }

    $sql_update_website = "
        UPDATE website_information SET
        phone = '$phone',
        email = '$email',
        address = '$address',
        country = '$country',
        instagram_url = '$instagram_url',
        facebook_url = '$facebook_url',
        postal_code = '$postal_code',
        primary_color = '$primary_color',
        secondary_color = '$secondary_color',
        logo_path = '$logo_path',
        banner_path = '$banner_path',
        updated_at = NOW()
        WHERE id = $website_id
    ";

    if ($conn->query($sql_update_website) === TRUE) {
        $service_ids = $_POST['service_id'];
        $service_contents = $_POST['service_content'];
        $service_descriptions = $_POST['service_description'];
        $service_icons = $_POST['service_icon'];
        $service_active = $_POST['service_active'];

        foreach ($service_ids as $index => $service_id) {
            $content = mysqli_real_escape_string($conn, $service_contents[$index]);
            $description = mysqli_real_escape_string($conn, $service_descriptions[$index]);
            $icon = mysqli_real_escape_string($conn, $service_icons[$index]);
            $active = isset($service_active[$index]) ? 1 : 0;

            $sql_update_service = "
                UPDATE services SET
                content = '$content',
                description = '$description',
                icon = '$icon',
                active = $active,
                updated_at = NOW()
                WHERE id = $service_id AND website_id = $website_id
            ";

            if (!$conn->query($sql_update_service)) {
                echo json_encode(array("error" => $sql_update_service, "message" => $conn->error));
                exit();
            }
        }

        echo json_encode(array("success" => true, "message" => "Website and services updated successfully."));
    } else {
        echo json_encode(array("error" => $sql_update_website, "message" => $conn->error));
    }

    $conn->close();
}
?>
