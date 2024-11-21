<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

ob_start();
include('../html/Reservations.html');
$output = ob_get_clean();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $fecha_inicio = $_POST['start-date'];
    $cantidad_dias = $_POST['days'];
    $habitacion = $_POST['habitacion'];

    $server = 'localhost:3306'; 
    $username = 'root';
    $password = '';
    $database = 'hospiaventuras';

    try {
        $con = new PDO("mysql:host=$server;dbname=$database;", $username, $password);

        $stmt = $con->prepare("INSERT INTO reservas (id, fecha_inicio, cantidadDias, habitacion) VALUES (:usuario_id, :fecha_inicio, :cantidad_dias, :habitacion)");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':cantidad_dias', $cantidad_dias);
        $stmt->bindParam(':habitacion', $habitacion);
        
        $stmt->execute();
        
        header("Location: ../PHP/reservations.php");
        exit();
    } catch (PDOException $e) {
        die('Error de conexión: ' . $e->getMessage());
    }
}
echo $output;
?>