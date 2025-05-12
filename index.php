<?php
require 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<?php include 'header.php'; ?>
<h1>Seznam zákazníků</h1>
<form method="GET" action="">
    <input type="text" name="hledat" placeholder="Zadejte hledaný výraz" required>
    <select name="kriterium">
        <option value="jmeno">Jméno</option>
        <option value="prijmeni">Příjmení</option>
        <option value="city">Město</option>
    </select>
    <button type="submit">Hledat</button>
</form>
<br>
<?php
if (isset($_GET['hledat']) && isset($_GET['kriterium'])) {
    $kriterium = $_GET['kriterium'];
    $hledat = $conn->real_escape_string($_GET['hledat']);
    $sql = "SELECT * FROM customers WHERE $kriterium LIKE '%$hledat%'";
} else {
    $sql = "SELECT * FROM customers";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>Jméno</th>
            <th>Příjmení</th>
            <th>Ulice</th>
            <th>Město</th>
            <th>Email</th>
            <th>Akce</th>
        </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['jmeno']}</td>
            <td>{$row['prijmeni']}</td>
            <td>{$row['street']}</td>
            <td>{$row['city']}</td>
            <td>{$row['email']}</td>
            <td>
                <a href='upravit.php?id={$row['id']}'>Upravit</a> | 
                <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Opravdu smazat?\")'>Smazat</a>
            </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "Žádní zákazníci nenalezeni.";
}
$conn->close();
?>
<?php include 'footer.html'; ?>