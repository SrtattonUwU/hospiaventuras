<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$servername = "localhost:3306";
$db_username = "root";
$db_password = "";
$dbname = "hospiaventuras";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $password, $user_id);

    if ($stmt->execute()) {
        echo "Información actualizada correctamente.";
        header("Location: account.php"); 
        exit();
    } else {
        echo "Error al actualizar la información.";
    }

    $stmt->close();
}

$sql = "SELECT username, email, password FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    die("No se encontró información del usuario.");
}

$stmt->close();
$conn->close();

ob_start();
include('../html/Account.html');
$output = ob_get_clean();

$output = str_replace("{{username}}", htmlspecialchars($user_data['username']), $output);
$output = str_replace("{{email}}", htmlspecialchars($user_data['email']), $output);
$output = str_replace("{{password}}", htmlspecialchars($user_data['password']), $output);

echo $output;
?>