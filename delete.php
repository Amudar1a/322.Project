<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


require 'db.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit;
?>
