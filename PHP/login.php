<?php
session_start();

$server = 'localhost:3306'; 
$username = 'root';
$password = '';
$database = 'hospiaventuras'; 

try {
    $con = new PDO("mysql:host=$server;dbname=$database;", $username, $password);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $stmt = $con->prepare("SELECT * FROM usuarios WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            header("Location: ../html/login.html?error=user_not_found");
            exit();
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($password !== $user['password']) {
            header("Location: ../html/login.html?error=incorrect_password");
            exit();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: ../PHP/main.php");
        exit();
    }

} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}
?>