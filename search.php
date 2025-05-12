<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';
include 'header.php';

$search = $_GET['q'] ?? ''; 
$results = [];

if ($search !== '') {
    $like = "%{$search}%";
    $stmt = $conn->prepare("SELECT * FROM customers WHERE 
        jmeno LIKE ? OR 
        prijmeni LIKE ? OR 
        city LIKE ? OR 
        email LIKE ?");
    $stmt->bind_param("ssss", $like, $like, $like, $like);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<h2>Výsledky hledání</h2>

<form method="get" action="search.php">
    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Hledat zákazníka..." required>
    <button type="submit">🔍 Hledat</button>
</form>

<?php if ($search !== ''): ?>
    <h3>Výsledky pro: <em><?= htmlspecialchars($search) ?></em></h3>

    <?php if ($results && $results->num_rows > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Jméno</th>
                <th>Příjmení</th>
                <th>Město</th>
                <th>Email</th>
                <th>Akce</th>
            </tr>
            <?php while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['jmeno']) ?></td>
                    <td><?= htmlspecialchars($row['prijmeni']) ?></td>
                    <td><?= htmlspecialchars($row['city']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>">✏️ Upravit</a> | 
                        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Opravdu smazat?')">🗑️ Smazat</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Žádné výsledky nebyly nalezeny.</p>
    <?php endif; ?>
<?php endif; ?>

<p><a href="index.php">⬅️ Zpět na hlavní stránku</a></p>

<?php include 'footer.html'; ?>
