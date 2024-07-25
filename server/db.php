<?php
use Dotenv\Dotenv;

require_once 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Verificar si el archivo .env se ha cargado correctamente
if (!file_exists(__DIR__ . '/.env')) {
    die("Error: El archivo .env no existe en el directorio.");
}

$db_host = $_ENV['DB_HOST'] ?? null;
$db_user = $_ENV['DB_USER'] ?? null;
$db_pass = $_ENV['DB_PASS'] ?? null;
$db_name = $_ENV['DB_NAME'] ?? null;

// Crear la conexión
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>