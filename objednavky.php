<?php 
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
require 'db.php';
include 'header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['objednavka_id'], $_POST['stav'])) {
    $objednavka_id = intval($_POST['objednavka_id']);
    $stav = $_POST['stav'];
    $povolene_stavy = ['novy', 'zpracovan', 'odeslan', 'zrusen'];

    if (in_array($stav, $povolene_stavy)) {
        $stmt = $conn->prepare("UPDATE objednavky SET stav = ? WHERE id = ?");
        $stmt->bind_param("si", $stav, $objednavka_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<h1>Seznam objednávek</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Zákazník</th>
        <th>Produkt</th>
        <th>Množství</th>
        <th>Datum</th>
        <th>Stav</th>
        <th>Akce</th>
    </tr>

<?php
$sql = "
    SELECT 
        o.id AS objednavka_id,
        c.jmeno,
        c.prijmeni,
        p.nazev AS produkt,
        op.mnozstvi,
        o.datum,
        o.stav
    FROM objednavky o
    JOIN customers c ON o.zakaznik_id = c.id
    JOIN objednavka_produkty op ON o.id = op.objednavka_id
    JOIN produkty p ON op.produkt_id = p.id
    ORDER BY o.id DESC
";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <form method='post'>
                <td>" . htmlspecialchars($row['objednavka_id']) . "</td>
                <td>" . htmlspecialchars($row['jmeno'] . " " . $row['prijmeni']) . "</td>
                <td>" . htmlspecialchars($row['produkt']) . "</td>
                <td>" . htmlspecialchars($row['mnozstvi']) . "</td>
                <td>" . htmlspecialchars($row['datum']) . "</td>
                <td>
                    <select name='stav'>
                        <option value='novy'" . ($row['stav'] == 'novy' ? ' selected' : '') . ">Nový</option>
                        <option value='zpracovan'" . ($row['stav'] == 'zpracovan' ? ' selected' : '') . ">Zpracován</option>
                        <option value='odeslan'" . ($row['stav'] == 'odeslan' ? ' selected' : '') . ">Odeslán</option>
                        <option value='zrusen'" . ($row['stav'] == 'zrusen' ? ' selected' : '') . ">Zrušen</option>
                    </select>
                </td>
                <td>
                    <input type='hidden' name='objednavka_id' value='" . $row['objednavka_id'] . "'>
                    <input type='submit' value='Uložit'>
                </td>
            </form>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7'>Žádné objednávky nenalezeny.</td></tr>";
}
?>
</table>

<?php include 'footer.html'; ?>
