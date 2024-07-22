<?php
require("server/db.php");

$website_id = intval($_GET['id']);

$sql_website_info = "SELECT * FROM website_information WHERE id = $website_id";
$result_website_info = $conn->query($sql_website_info);

if ($result_website_info->num_rows > 0) {
    $website_info = $result_website_info->fetch_assoc();
} else {
    die("No website information found for this website ID.");
}


// Get the data of the sections and services of the website
$sql_sections = "SELECT * FROM sections WHERE website_id = $website_id";
$result_sections = $conn->query($sql_sections);

if ($result_sections->num_rows > 0) {
    $sections = $result_sections->fetch_all(MYSQLI_ASSOC);
} else {
    die("No sections found for this website ID.");
}

$sql_services = "SELECT * FROM services WHERE website_id = $website_id";
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
    <title>Todo Plagas - Control de Plagas en San Juan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
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
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/api/placeholder/1200/600') no-repeat center center/cover;
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
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <a href="#" class="logo"><?php echo htmlspecialchars($website_info['business_name']); ?></a>
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
            <p>Expertos en fumigación y control de plagas en San Juan desde 1999</p>
            <a href="#contact" class="btn">Contáctanos ahora</a>
        </div>
    </section>

    <section id="about">
        <div class="container">
            <h2>Sobre <?php echo htmlspecialchars($website_info['business_name']); ?></h2>
            <p><?php echo htmlspecialchars($sections[1]['content']); ?></p>
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
    <h1>Edit Sections</h1>
    <form id="editSectionsForm">
        <input type="hidden" name="website_id" value="<?php echo htmlspecialchars($website_id); ?>">
        <?php foreach ($sections as $section): ?>
            <fieldset>
                <legend><?php echo htmlspecialchars($section['type']); ?></legend>
                <input type="hidden" name="section_id[]" value="<?php echo htmlspecialchars($section['id']); ?>">
                <label for="type_<?php echo htmlspecialchars($section['id']); ?>">Type:</label>
                <input type="text" id="type_<?php echo htmlspecialchars($section['id']); ?>" name="type[]" value="<?php echo htmlspecialchars($section['type']); ?>" readonly><br><br>

                <label for="content_<?php echo htmlspecialchars($section['id']); ?>">Content:</label>
                <textarea id="content_<?php echo htmlspecialchars($section['id']); ?>" name="content[]" rows="4" cols="50"><?php echo htmlspecialchars($section['content']); ?></textarea><br><br>

                <label for="img_route_<?php echo htmlspecialchars($section['id']); ?>">Image Route:</label>
                <input type="text" id="img_route_<?php echo htmlspecialchars($section['id']); ?>" name="img_route[]" value="<?php echo htmlspecialchars($section['img_route']); ?>"><br><br>

                <label for="active_<?php echo htmlspecialchars($section['id']); ?>">Active:</label>
                <input type="checkbox" id="active_<?php echo htmlspecialchars($section['id']); ?>" name="active[]" value="1" <?php echo $section['active'] ? 'checked' : ''; ?>><br><br>
            </fieldset>
        <?php endforeach; ?>
        <button type="button" id="updateSectionsBtn">Update Sections</button>
    </form>
    <div id="message"></div>

    <script>
        $(document).ready(function() {
            $('#updateSectionsBtn').click(function() {
                $.ajax({
                    url: 'server/update_sections.php',
                    type: 'POST',
                    data: $('#editSectionsForm').serialize(),
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
