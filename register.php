<?php
session_start(); 
require 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($username === '' || $password === '' || $confirm === '') {
        $error = "Všechna pole jsou povinná.";
    } elseif ($password !== $confirm) {
        $error = "Hesla se neshodují.";
    } else {
        
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Uživatel již existuje.";
        } else {
            
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);

            if ($stmt->execute()) {
                $success = "Uživatel byl úspěšně zaregistrován.";
            } else {
                $error = "Chyba při registraci uživatele.";
            }
        }
    }
}
?>

<?php include 'header.php'; ?> 

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrace uživatele</title>
</head>
<body>
    <h1>Registrace</h1>

    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p style="color:green"><?= htmlspecialchars($success) ?></p>
        <p><a href="login.php">➡️ Přihlásit se</a></p>
    <?php endif; ?>

    <form method="post">
        <label for="username">Uživatelské jméno:</label><br>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Heslo:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <label for="confirm">Potvrzení hesla:</label><br>
        <input type="password" name="confirm" id="confirm" required><br><br>

        <button type="submit">Zaregistrovat</button>
    </form>

    <p><a href="index.php">⬅️ Zpět na seznam zákazníků</a></p>
</body>
</html>

<?php include 'footer.html'; ?>
