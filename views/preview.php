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
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" href="server/<?php echo htmlspecialchars($website_info['logo_path']); ?>" type="image/png">
    <style>:root {
    --primary-color: <?php echo !empty($website_info['primary_color']) ? htmlspecialchars($website_info['primary_color']) : '#4a90e2'; ?>;
    --secondary-color: <?php echo !empty($website_info['secondary_color']) ? htmlspecialchars($website_info['secondary_color']) : '#f5a623'; ?>;
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
    padding: 10px; /* Reduced padding for a smaller circle */
    border-radius: 50%; /* Back to 50% for a perfect circle */
    font-size: 3rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    width: 90px; /* Reduced width */
    height: 90px; /* Reduced height */
    display: flex; /* Enable flexbox for centering */
    align-items: center; /* Center items vertically */
    justify-content: center; /* Center items horizontally */
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
.edit-section {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        height: 100%;
        background: #fff;
        overflow-y: auto;
        transition: right 0.3s ease;
        z-index: 2000;
        padding: 20px;
        box-shadow: -2px 0 5px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .edit-section.active {
        right: 0;
    }

    @media (min-width: 768px) {
        .edit-section {
            width: 50%;
        }
    }
    </style>
</head>
<body>
    <header class="bg-gray-100 py-4">
        <nav class="container mx-auto flex items-center justify-between">
            <a href="#" class="logo-container flex items-center">
                <img class="w-16" src="server/<?php echo htmlspecialchars($website_info['logo_path']); ?>" alt="Logo">
                <p class="ml-4 text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($website_info['business_name']); ?></p>
            </a>
            <div class="nav-links hidden md:flex space-x-8">
                <a href="#home" class="text-gray-800 hover:text-gray-600">Inicio</a>
                <a href="#about" class="text-gray-800 hover:text-gray-600">Nosotros</a>
                <a href="#services" class="text-gray-800 hover:text-gray-600">Servicios</a>
                <a href="#contact" class="text-gray-800 hover:text-gray-600">Contacto</a>
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

    <section id="about" class="py-12 bg-gray-100">
        <div class="container mx-auto flex flex-col items-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Sobre <?php echo htmlspecialchars($website_info['business_name']); ?></h2>
            <p class="text-gray-600 text-center max-w-xl"><?php echo htmlspecialchars($sections[2]['content']); ?></p>
        </div>
    </section>

    <section id="services" class="py-12">
        <div class="container">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Nuestros Servicios</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($services as $service): ?>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center mb-4">
                            <i class="fab fa-<?php echo htmlspecialchars($service['icon']); ?>" style="font-size: 2rem; color: var(--primary-color);"></i>
                            <h3 class="ml-4 text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($service['content']); ?></h3>
                        </div>
                        <p class="text-gray-600"><?php echo htmlspecialchars($service['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="py-12">
        <div class="container">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Contacto</h2>
            <div class="flex flex-col space-y-4">
                <p class="text-lg font-medium text-gray-700">Teléfono: <a href="tel:<?php echo htmlspecialchars($website_info['phone']); ?>" class="text-blue-500 hover:text-blue-700">+54 9 261 643 7588</a></p>
                <p class="text-lg font-medium text-gray-700">Dirección: <?php echo htmlspecialchars($website_info['address']); ?></p>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 <?php echo htmlspecialchars($website_info['business_name']); ?>. Todos los derechos reservados.</p>
        </div>
    </footer>

    <div class="social-links">
        <a href="<?php echo htmlspecialchars($website_info['facebook_url']); ?>" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="<?php echo htmlspecialchars($website_info['instagram_url']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="<?php echo htmlspecialchars($website_info['google_maps_url']); ?>" target="_blank"><i class="fas fa-map-marker-alt"></i></a >
    </div>

    <a href="https://wa.me/<?php echo htmlspecialchars($website_info['phone']); ?>" class="whatsapp-btn" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php
    if (isset($_SESSION['user_id']) && isset($website_id)) {
        $user_id = $_SESSION['user_id'];
        $sql_check_ownership = "SELECT COUNT(*) AS count FROM website_information WHERE id = $website_id AND user_id = $user_id";
        $result = $conn->query($sql_check_ownership);
        $row = $result->fetch_assoc();
        if ($row['count'] == 1) {
            ?>
            
            <button id="editButton" class="fixed bottom-28 right-4 bg-blue-500 text-white p-3 rounded-full shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </button>

            <div id="editSection" class="edit-section">
                <div class="flex justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Editar información del sitio web</h1>
                    <button id="closeButton" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="editWebsiteForm" class="space-y-6 " enctype="multipart/form-data">
                    <input type="hidden" name="website_id" value="<?php echo htmlspecialchars($website_id); ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($website_info['phone']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($website_info['email']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($website_info['address']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">País</label>
                            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($website_info['country']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700">URL de Instagram</label>
                            <input type="url" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($website_info['instagram_url']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700">URL de Facebook</label>
                            <input type="url" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($website_info['facebook_url']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Código Postal</label>
                            <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($website_info['postal_code']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ">
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700">Color Primario</label>
                            <input type="color" id="primary_color" name="primary_color" value="<?php echo htmlspecialchars($website_info['primary_color']); ?>" class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700">Color Secundario</label>
                            <input type="color" id="secondary_color" name="secondary_color" value="<?php echo htmlspecialchars($website_info['secondary_color']); ?>" class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="logo_path" class="block text-sm font-medium text-gray-700">Subir Logo</label>
                            <input type="file" id="logo_path" name="logo_path" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                            ">
                        </div>

                        <div>
                            <label for="banner_path" class="block text-sm font-medium text-gray-700">Subir Banner</label>
                            <input type="file" id="banner_path" name="banner_path" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                            ">
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Editar servicios</h2>
                        <?php foreach ($services as $service): ?>
                            <div class="bg-gray-50 p-6 rounded-lg mb-6 shadow-sm">
                                <h3 class="text-lg font-medium text-gray-800 mb-4">Servicio <?php echo htmlspecialchars($service['id']); ?></h3>
                                <input type="hidden" name="service_id[]" value="<?php echo htmlspecialchars($service['id']); ?>">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="service_content_<?php echo htmlspecialchars($service['id']); ?>" class="block text-sm font-medium text-gray-700">Contenido</label>
                                        <input type="text" id="service_content_<?php echo htmlspecialchars($service['id']); ?>" name="service_content[]" value="<?php echo htmlspecialchars($service['content']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label for="service_icon_<?php echo htmlspecialchars($service['id']); ?>" class="block text-sm font-medium text-gray-700">Icono</label>
                                        <input type="text" id="service_icon_<?php echo htmlspecialchars($service['id']); ?>" name="service_icon[]" value="<?php echo htmlspecialchars($service['icon']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="service_description_<?php echo htmlspecialchars($service['id']); ?>" class="block text-sm font-medium text-gray-700">Descripción</label>
                                    <textarea id="service_description_<?php echo htmlspecialchars($service['id']); ?>" name="service_description[]" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo htmlspecialchars($service['description']); ?></textarea>
                                </div>

                                <div class="mt-4">
                                    <label for="service_active_<?php echo htmlspecialchars($service['id']); ?>" class="inline-flex items-center">
                                        <input type="checkbox" id="service_active_<?php echo htmlspecialchars($service['id']); ?>" name="service_active[]" value="1" <?php echo $service['active'] ? 'checked' : ''; ?> class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">Activo</span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" id="updateWebsiteBtn" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Actualizar sitio web
                        </button>
                    </div>
                </form>
                <div id="message" class="mt-4"></div>
            </div>
            <script>
                $(document).ready(function() {
                    const editButton = document.getElementById('editButton');
                    const editSection = document.getElementById('editSection');
                    const closeButton = document.getElementById('closeButton');

                    editButton.addEventListener('click', () => {
                        editSection.classList.add('active');
                    });

                    closeButton.addEventListener('click', () => {
                        editSection.classList.remove('active');
                    });

                    $('#updateWebsiteBtn').click(function() {
                        var formData = new FormData($('#editWebsiteForm')[0]);
                        
                        // Mostrar estado de carga
                        $('#message').html('<p class="text-blue-500">Actualizando...</p>');
                        $('#updateWebsiteBtn').prop('disabled', true).addClass('opacity-50');

                        $.ajax({
                            url: 'server/update_website.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#message').html('<p class="text-green-500">' + response + '</p>');
                                
                                // Refrescar el contenido
                                location.reload();
                            },
                            error: function() {
                                $('#message').html('<p class="text-red-500">Ocurrió un error.</p>');
                            },
                            complete: function() {
                                $('#updateWebsiteBtn').prop('disabled', false).removeClass('opacity-50');
                            }
                        });
                    });
                });
            </script>
            <?php
        } else {
            header("Location: /login.php");
            exit();
        }
    }
    ?>

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