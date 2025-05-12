<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);


if (!isset($_SESSION['user']) && !in_array($currentPage, ['login.php', 'register.php'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Zákaznický systém</title>
</head>
<body>
    <nav>
        <div class="nav-links">
            <a href="index.php">Seznam zákazníků</a>
            <a href="pridat.php">Přidat zákazníka</a>
            <a href="objednavky.php">Objednávky</a>
            <a href="produkty.php">Produkty</a>

        </div>
        <?php if (isset($_SESSION['user'])): ?>
            <a class="logout" href="logout.php">Odhlásit se</a>
        <?php endif; ?>
    </nav>
    <hr>
