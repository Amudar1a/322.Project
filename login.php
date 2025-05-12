<?php
session_start(); 
if (isset($_GET['logout'])) {
    echo '<p style="color:green">Byl jste úspěšně odhlášen.</p>';
}
require 'db.php'; 
include 'header.php'; 

$error = ''; 


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username']; 
            header("Location: index.php"); 
            exit;
        } else {
            $error = "Nesprávné heslo.";
        }
    } else {
        $error = "Uživatel nenalezen.";
    }
}
?>

<h1>Přihlášení</h1>

<?php if ($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
    <label for="username">Uživatelské jméno:</label><br>
    <input type="text" name="username" id="username" required><br><br>

    <label for="password">Heslo:</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <button type="submit">Přihlásit se</button>
</form>

<p><a href="register.php">🔧 Registrovat nový účet</a></p>

<?php include 'footer.html'; ?>
