<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

ob_start();
include('../html/Products.html');
$output = ob_get_clean();

$output = str_replace("{{username}}", htmlspecialchars($username), $output);
$output = str_replace("{{user_id}}", htmlspecialchars($user_id), $output);

if (isset($_POST['total'])) {
    $total = floatval($_POST['total']);

    $servername = "localhost:3306";
    $db_username = "root";
    $db_password = "";
    $dbname = "hospiaventuras";

    try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $con->prepare("INSERT INTO compras (id, precioTotal) VALUES (:user_id, :total)");

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':total', $total, PDO::PARAM_STR);

        $stmt->execute();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

echo $output;
?>