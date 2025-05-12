<?php 
include 'header.php'; 
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jmeno = $_POST['jmeno'] ?? '';
    $prijmeni = $_POST['prijmeni'] ?? '';
    $ulice = $_POST['ulice'] ?? '';
    $mesto = $_POST['mesto'] ?? '';
    $email = $_POST['email'] ?? '';

    $stmt = $conn->prepare("INSERT INTO customers (jmeno, prijmeni, street, city, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $jmeno, $prijmeni, $ulice, $mesto, $email);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}
?>

<h1>Přidat nového zákazníka</h1>

<form method="post" action="add.php">
    <label>Jméno: <input type="text" name="jmeno" required></label><br><br>
    <label>Příjmení: <input type="text" name="prijmeni" required></label><br><br>
    <label>Ulice: <input type="text" name="ulice"></label><br><br>
    <label>Město: <input type="text" name="mesto"></label><br><br>
    <label>Email: <input type="email" name="email"></label><br><br>
    <button type="submit">💾 Uložit</button>
    <a href="index.php">↩️ Zpět</a>
</form>

<?php include 'footer.html'; ?>
