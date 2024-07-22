<?php
require("db.php");

// Verificar que se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $website_id = intval($_POST['website_id']);
    
    // Comprobar si se han enviado IDs de secciones y si son un array
    if (isset($_POST['section_id']) && is_array($_POST['section_id'])) {
        $updateQueries = [];

        foreach ($_POST['section_id'] as $index => $section_id) {
            $section_id = intval($section_id);
            $content = mysqli_real_escape_string($conn, $_POST['content'][$index]);
            $img_route = mysqli_real_escape_string($conn, $_POST['img_route'][$index]);
            $active = isset($_POST['active'][$index]) ? 1 : 0;
            
            $sql = "UPDATE sections SET content = '$content', img_route = '$img_route', active = $active, updated_at = NOW() WHERE id = $section_id AND website_id = $website_id";
            $updateQueries[] = $sql;
        }
        
        // Ejecutar todas las consultas de actualización
        foreach ($updateQueries as $query) {
            if ($conn->query($query) !== TRUE) {
                echo "Error updating section: " . $conn->error;
                exit();
            }
        }
        
        echo "Sections updated successfully.";
    } else {
        echo "No sections to update.";
    }
    
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
