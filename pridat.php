<?php
include 'header.php';
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = trim($_POST['jmeno']);
    $prijmeni = trim($_POST['prijmeni']);
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $email = trim($_POST['email']);

    if (empty($jmeno) || empty($prijmeni) || empty($street) || empty($city) || empty($email)) {
        $error = "Všechna pole jsou povinná.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Neplatná emailová adresa.";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (jmeno, prijmeni, street, city, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $jmeno, $prijmeni, $street, $city, $email);

        if ($stmt->execute()) {
            $success = "Zákazník byl úspěšně přidán.";
        } else {
            $error = "Chyba při přidávání zákazníka.";
        }
    }
}
?>

<h2>Přidat zákazníka</h2>

<?php if ($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p style="color:green"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="post">
    <label>Jméno:</label><br>
    <input type="text" name="jmeno"><br><br>

    <label>Příjmení:</label><br>
    <input type="text" name="prijmeni"><br><br>

    <label>Ulice:</label><br>
    <input type="text" name="street"><br><br>

    <label>Město:</label><br>
    <input type="text" name="city"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email"><br><br>

    <button type="submit">Přidat</button>
</form>

<p><a href="index.php">⬅️ Zpět na seznam zákazníků</a></p>

<?php include 'footer.html'; ?>
