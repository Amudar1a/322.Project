<?php


$host = 'localhost';
$user = 'root';
$password = '';
$database = 'customers_db';

$conn = new mysqli($host, $user, $password, $database);


if ($conn->connect_error) {
    die("Chyba při připojení: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
