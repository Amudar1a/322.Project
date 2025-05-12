<?php
session_start(); // ✅ 
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// include 'header.php'; // ✅ 

require 'db.php';


$zakaznici = $conn->query("SELECT id, first_name, last_name FROM customers");
$produkty = $conn->query("SELECT id, nazev FROM produkty");

$zprava = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $zakaznik_id = $_POST["zakaznik_id"];
    $produkt_id = $_POST["produkt_id"];
    $datum = $_POST["datum"];
    $mnozstvi = $_POST["mnozstvi"];

    
    $stmt = $conn->prepare("INSERT INTO objednavky (zakaznik_id, produkt_id, datum, mnozstvi) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $zakaznik_id, $produkt_id, $datum, $mnozstvi);

    if ($stmt->execute()) {
        $zprava = "Objednávka byla úspěšně přidána.";
    } else {
        $zprava = "Chyba při ukládání objednávky.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Přidat objednávku</title>
</head>
<body>
    <h1>Přidat objednávku</h1>

    <?php if ($zprava): ?>
        <p><strong><?= htmlspecialchars($zprava) ?></strong></p>
    <?php endif; ?>

    <form method="post">
        <label for="zakaznik_id">Zákazník:</label><br>
        <select name="zakaznik_id" required>
            <?php while ($z = $zakaznici->fetch_assoc()): ?>
                <option value="<?= $z['id'] ?>">
                    <?= htmlspecialchars($z['first_name'] . " " . $z['last_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="produkt_id">Produkt:</label><br>
        <select name="produkt_id" required>
            <?php while ($p = $produkty->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nazev']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="datum">Datum:</label><br>
        <input type="date" name="datum" required><br><br>

        <label for="mnozstvi">Množství:</label><br>
        <input type="number" name="mnozstvi" min="1" required><br><br>

        <button type="submit">💾 Uložit objednávku</button>
    </form>

    <p><a href="index.php">⬅️ Zpět na hlavní stránku</a></p>
</body>
</html>

<?php include 'footer.html'; ?>
