<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

$server = 'localhost:3306'; 
$username = 'root';
$password = '';
$database = 'hospiaventuras'; 

try {
    $con = new PDO("mysql:host=$server;dbname=$database;", $username, $password);

    $user_id = $_SESSION['user_id'];

    $stmt = $con->prepare("SELECT * FROM usuarios WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        die("Usuario no encontrado.");
    }

} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}
?>