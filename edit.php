<?php
session_start(); 


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require 'db.php'; 
include 'header.php'; 


$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID zákazníka nebylo zadáno.");
}


$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();

if (!$customer) {
    die("Zákazník nebyl nalezen.");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $street = $_POST['street'] ?? '';
    $city = $_POST['city'] ?? '';
    $email = $_POST['email'] ?? '';

    
    $stmt = $conn->prepare("UPDATE customers SET first_name=?, last_name=?, street=?, city=?, email=? WHERE id=?");
    $stmt->bind_param("sssssi", $first_name, $last_name, $street, $city, $email, $id);
    $stmt->execute();
    $stmt->close();

    
    header("Location: index.php");
    exit;
}
?>

<h1>Upravit zákazníka</h1>
<form method="post" action="">
    <label>Jméno: <input type="text" name="first_name" value="<?= htmlspecialchars($customer['first_name']) ?>" required></label><br><br>
    <label>Příjmení: <input type="text" name="last_name" value="<?= htmlspecialchars($customer['last_name']) ?>" required></label><br><br>
    <label>Ulice: <input type="text" name="street" value="<?= htmlspecialchars($customer['street']) ?>"></label><br><br>
    <label>Město: <input type="text" name="city" value="<?= htmlspecialchars($customer['city']) ?>"></label><br><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>"></label><br><br>
    <button type="submit">💾 Uložit změny</button>
    <a href="index.php">↩️ Zpět</a>
</form>

<?php include 'footer.html'; ?>
