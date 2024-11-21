<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

ob_start();
include('../html/About.html');
$output = ob_get_clean();

$output = str_replace("{{username}}", htmlspecialchars($username), $output);
$output = str_replace("{{user_id}}", htmlspecialchars($user_id), $output);

echo $output;
?>
