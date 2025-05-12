<?php
session_start();
require 'db.php'; 

include 'header.php';

if (!isset($_GET['id'])) {
    die("ID zákazníka není zadáno.");
}

$id = intval($_GET['id']);
$data = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $email = $_POST['email'];

    
    $stmt = $conn->prepare("UPDATE customers SET jmeno=?, prijmeni=?, street=?, city=?, email=? WHERE id=?");
    $stmt->bind_param("sssssi", $jmeno, $prijmeni, $street, $city, $email, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
} else {
    
    $stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        die("Zákazník nebyl nalezen.");
    }
}
?>

<h2>Upravit zákazníka</h2>
<form method="post">
    Jméno: <input type="text" name="jmeno" value="<?= htmlspecialchars($data['jmeno']) ?>" required><br>
    Příjmení: <input type="text" name="prijmeni" value="<?= htmlspecialchars($data['prijmeni']) ?>" required><br>
    Ulice: <input type="text" name="street" value="<?= htmlspecialchars($data['street']) ?>"><br>
    Město: <input type="text" name="city" value="<?= htmlspecialchars($data['city']) ?>"><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>"><br><br>
    <button type="submit">💾 Uložit změny</button>
</form>

<p><a href="index.php">⬅️ Zpět na hlavní stránku</a></p>

<?php include 'footer.html'; ?>
