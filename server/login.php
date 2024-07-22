<?php
require("db.php"); // Asegúrate de que tu archivo de conexión a la base de datos esté en db.php

// Obtener datos del formulario
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Buscar el usuario por email
$sql = "SELECT id, password, active FROM users WHERE email = '$email' AND active = TRUE";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Usuario encontrado
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];
    
    // Verificar la contraseña
    if (password_verify($password, $hashedPassword)) {
        // Iniciar sesión
        session_start();
        $_SESSION['user_id'] = $row['id'];
        echo "Login successful";
    } else {
        echo "Invalid email or password.";
    }
} else {
    echo "Invalid email or password.";
}

$conn->close();
?>
