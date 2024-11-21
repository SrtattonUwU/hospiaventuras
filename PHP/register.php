<?php
session_start();
$server = 'localhost:3306'; 
$username = 'root';
$password = '';
$database = 'hospiaventuras'; 

try {
    $con = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repeatPassword = $_POST['repeatPassword'];

        if ($password !== $repeatPassword) {
            header('Location: register.html?error=password_mismatch');
            exit();
        }

        $stmt = $con->prepare("SELECT * FROM usuarios WHERE email = :email OR username = :username");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header('Location: ../html/Register.html?error=user_exists');
            exit();
        }

        $stmt = $con->prepare("INSERT INTO usuarios (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        header('Location: ../html/Login.html');
        exit();
    }
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}
?>
