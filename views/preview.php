<?php
require("server/db.php");

$website_id = intval($_GET['id']);

// Obtener los datos de las secciones del sitio web
$sql = "SELECT * FROM sections WHERE website_id = $website_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $sections = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("No sections found for this website ID.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sections</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
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
</body>
</html>
