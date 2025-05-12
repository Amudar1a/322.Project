<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
require 'db.php';
include 'header.php';
?>

<h1>Seznam produktů</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Název</th>
        <th>Popis</th>
        <th>Cena (Kč)</th>
    </tr>

<?php
$sql = "SELECT id, nazev, popis, cena FROM produkty";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['id']) . "</td>
            <td>" . htmlspecialchars($row['nazev']) . "</td>
            <td>" . htmlspecialchars($row['popis']) . "</td>
            <td>" . htmlspecialchars($row['cena']) . "</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>Žádné produkty nebyly nalezeny.</td></tr>";
}
?>
</table>

<?php include 'footer.html'; ?>
