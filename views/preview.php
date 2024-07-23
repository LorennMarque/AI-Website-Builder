<?php
require("server/db.php");

$website_id = intval($_GET['id']);
$celebrate = isset($_POST['celebrate']) && $_POST['celebrate'] === 'true';

$sql_website_info = "SELECT * FROM website_information WHERE id = $website_id AND active = 1";
$result_website_info = $conn->query($sql_website_info);
if ($result_website_info->num_rows > 0) {
    $website_info = $result_website_info->fetch_assoc();
} else {
    die("No website information found for this website ID.");
}


// Get the data of the sections and services of the website
$sql_sections = "SELECT * FROM sections WHERE website_id = $website_id AND active = 1";
$result_sections = $conn->query($sql_sections);

if ($result_sections->num_rows > 0) {
    $sections = $result_sections->fetch_all(MYSQLI_ASSOC);
} else {
    die("No sections found for this website ID.");
}

$sql_services = "SELECT * FROM services WHERE website_id = $website_id AND active = 1";
$result_services = $conn->query($sql_services);

if ($result_services->num_rows > 0) {
    $services = $result_services->fetch_all(MYSQLI_ASSOC);
} else {
    die("No services found for this website ID.");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($website_info['business_name']); ?> - <?php echo htmlspecialchars($sections[1]['content']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <link rel="icon" href="server/<?php echo htmlspecialchars($website_info['logo_path']); ?>" type="image/png">
    <style>:root {
    --primary-color: #4a90e2;
    --secondary-color: #f5a623;
    --text-color: #333;
    --bg-color: #f9f9f9;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    line-height: 1.6;
    color: var(--text-color);
    background-color: var(--bg-color);
    overflow-x: hidden; /* Evita el desplazamiento horizontal */
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

header {
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
}

.logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    text-decoration: none;
}

.nav-links {
    display: flex;
    gap: 20px;
}

.nav-links a {
    text-decoration: none;
    color: var(--text-color);
    font-weight: 600;
    transition: color 0.3s ease;
}

.nav-links a:hover {
    color: var(--primary-color);
}

#hero {
    background: url('server/<?php echo htmlspecialchars($website_info['banner_path']); ?>') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    align-items: center;
    text-align: center;
    color: #fff;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 20px;
}

.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 30px;
}

.btn {
    display: inline-block;
    background: var(--secondary-color);
    color: #fff;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #e69100;
}

section {
    padding: 80px 0;
}

h2 {
    font-size: 2.5rem;
    margin-bottom: 30px;
    text-align: center;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.service-card {
    background: #fff;
    padding: 30px;
    border-radius: 5px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.service-card i {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 20px;
}

footer {
    background: #333;
    color: #fff;
    padding: 40px 0;
    text-align: center;
}

.social-links {
    position: fixed;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.social-links a {
    color: var(--primary-color);
    font-size: 1.5rem;
    transition: color 0.3s ease;
}

.social-links a:hover {
    color: var(--secondary-color);
}

.whatsapp-btn {
    position: fixed;
    right: 20px;
    bottom: 20px;
    background: #25d366;
    color: #fff;
    padding: 15px;
    border-radius: 50%;
    font-size: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.whatsapp-btn:hover {
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .nav-links {
        display: none;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .hero-content p {
        font-size: 1rem;
    }
    
    .social-links {
        left: 10px;
    }
    
    .whatsapp-btn {
        right: 10px;
        bottom: 10px;
    }
}

.logo-container {
    display: flex;
    align-items: center; /* Centra verticalmente */
    justify-content: center; /* Opcional: centra horizontalmente */
    text-decoration: none; /* Opcional: elimina el subrayado del enlace */
    color: inherit; /* Opcional: usa el color del texto del enlace */
}

.logo-container img {
    margin-right: 10px; /* Espacio entre el logo y el texto */
}

    </style>
</head>
<body>
    <header>
        <nav class="container" style="padding: 10px 0;">
        <a href="#" class="logo-container">
            <img style="width:45px" src="server/<?php echo htmlspecialchars($website_info['logo_path']); ?>" alt="Logo">
            <p class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($website_info['business_name']); ?></p>
            
        </a>
            <div class="nav-links">
                <a href="#home">Inicio</a>
                <a href="#about">Nosotros</a>
                <a href="#services">Servicios</a>
                <a href="#contact">Contacto</a>
            </div>
        </nav>
    </header>
    
    <section id="hero">
        <div class="container hero-content">
            <h1><?php echo htmlspecialchars($sections[0]['content']); ?></h1>
            <p><?php echo htmlspecialchars($sections[1]['content']); ?></p>
            <a href="#contact" class="btn">Contáctanos ahora</a>
        </div>
    </section>

    <section id="about">
        <div class="container">
            <h2>Sobre <?php echo htmlspecialchars($website_info['business_name']); ?></h2>
            <p><?php echo htmlspecialchars($sections[2]['content']); ?></p>
        </div>
    </section>

    <section id="services">
        <div class="container">
            <h2>Nuestros Servicios</h2>
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                    <div class="service-card">
                        <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                        <h3><?php echo htmlspecialchars($service['content']); ?></h3>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="container">
            <h2>Contacto</h2>
            <p>Teléfono: <a href="tel:<?php echo htmlspecialchars($website_info['phone']); ?>">+54 9 261 643 7588</a></p>
            <p>Dirección: <?php echo htmlspecialchars($website_info['address']); ?></p>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 <?php echo htmlspecialchars($website_info['business_name']); ?>. Todos los derechos reservados.</p>
        </div>
    </footer>

    <div class="social-links">
        <a href="#" target="_blank"><i class="fab fa-facebook"></i><?php echo htmlspecialchars($website_info['facebook_url']); ?></a>
        <a href="#" target="_blank"><i class="fab fa-instagram"></i><?php echo htmlspecialchars($website_info['instagram_url']); ?></a>
        <a href="#" target="_blank"><i class="fab fa-map"></i><?php echo htmlspecialchars($website_info['google_maps_url']); ?></a>
    </div>

    <a href="https://wa.me/<?php echo htmlspecialchars($website_info['phone']); ?>" class="whatsapp-btn" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
</body>
</html>
<!-- ////////////////////////////////////////////////////////////// -->
<h1>Edit Website Information</h1>
<form id="editWebsiteForm" enctype="multipart/form-data">
    <input type="hidden" name="website_id" value="<?php echo htmlspecialchars($website_id); ?>">

    <!-- Existing fields -->
    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($website_info['phone']); ?>"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($website_info['email']); ?>"><br><br>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($website_info['address']); ?>"><br><br>

    <label for="country">Country:</label>
    <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($website_info['country']); ?>"><br><br>

    <label for="instagram_url">Instagram URL:</label>
    <input type="url" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($website_info['instagram_url']); ?>"><br><br>

    <label for="facebook_url">Facebook URL:</label>
    <input type="url" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($website_info['facebook_url']); ?>"><br><br>

    <label for="postal_code">Postal Code:</label>
    <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($website_info['postal_code']); ?>"><br><br>

    <label for="primary_color">Primary Color:</label>
    <input type="color" id="primary_color" name="primary_color" value="<?php echo htmlspecialchars($website_info['primary_color']); ?>"><br><br>

    <label for="secondary_color">Secondary Color:</label>
    <input type="color" id="secondary_color" name="secondary_color" value="<?php echo htmlspecialchars($website_info['secondary_color']); ?>"><br><br>

    <label for="logo_path">Upload Logo:</label>
    <input type="file" id="logo_path" name="logo_path" value="<?php echo htmlspecialchars($website_info['logo_path']); ?>"><br><br>

    <label for="banner_path">Upload Banner:</label>
    <input type="file" id="banner_path" name="banner_path" value="<?php echo htmlspecialchars($website_info['banner_path']); ?>"><br><br>

    <h2>Edit Services</h2>
    <?php foreach ($services as $service): ?>
        <fieldset>
            <legend>Service <?php echo htmlspecialchars($service['id']); ?></legend>
            <input type="hidden" name="service_id[]" value="<?php echo htmlspecialchars($service['id']); ?>">
            <label for="service_content_<?php echo htmlspecialchars($service['id']); ?>">Content:</label>
            <input type="text" id="service_content_<?php echo htmlspecialchars($service['id']); ?>" name="service_content[]" value="<?php echo htmlspecialchars($service['content']); ?>"><br><br>

            <label for="service_description_<?php echo htmlspecialchars($service['id']); ?>">Description:</label>
            <textarea id="service_description_<?php echo htmlspecialchars($service['id']); ?>" name="service_description[]" rows="4" cols="50"><?php echo htmlspecialchars($service['description']); ?></textarea><br><br>

            <label for="service_icon_<?php echo htmlspecialchars($service['id']); ?>">Icon:</label>
            <input type="text" id="service_icon_<?php echo htmlspecialchars($service['id']); ?>" name="service_icon[]" value="<?php echo htmlspecialchars($service['icon']); ?>"><br><br>

            <label for="service_active_<?php echo htmlspecialchars($service['id']); ?>">Active:</label>
            <input type="checkbox" id="service_active_<?php echo htmlspecialchars($service['id']); ?>" name="service_active[]" value="1" <?php echo $service['active'] ? 'checked' : ''; ?>><br><br>
        </fieldset>
    <?php endforeach; ?>

    <button type="button" id="updateWebsiteBtn">Update Website</button>
</form>
<div id="message"></div>

<script>
    $(document).ready(function() {
        $('#updateWebsiteBtn').click(function() {
            var formData = new FormData($('#editWebsiteForm')[0]);
            $.ajax({
                url: 'server/update_website.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#message').html(response);
                },
                error: function() {
                    $('#message').html('An error occurred.');
                }
            });
        });
    });
</script>

    <?php if ($celebrate): ?>
        <script>
            // Mostrar confetti
            confetti({
                particleCount: 400,
                spread: 300,
                origin: { y: -0.2 },
                angle: 270
            });
        </script>
    <?php endif; ?>