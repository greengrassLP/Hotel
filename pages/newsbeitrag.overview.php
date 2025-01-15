<?php
require_once '../config.php';
include '../includes/header.php';
include '../dbconnection.php';


// if newsletter gets deleted by clicking the delete button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_newsletter'])) {
    $news_id = intval($_POST['delete_newsletter']);

    $delete_sql = "DELETE FROM news WHERE news_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $news_id);

    if ($stmt->execute()) {
        $success_message = "Newsletter erfolgreich gelöscht.";
    } else {
        $error_message = "Fehler beim Löschen des Newsletters.";
    }
}

//Selects alle newsletter von Datenbank
$sql = "SELECT * FROM news";
$result = $conn->query($sql);
?>

<div class="container mt-5"></div>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Bild</th>
            <th>Text</th>
            <th>Datum</th>
            <th>Löschen</th>
        </tr>
    </thead>
    <tbody>
        <!-- Alle Beiträge anzeigen -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_array()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['news_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars($row['picture']); ?>" alt="Newsletter Image"
                            style="width: 100px; height: auto;">
                    </td>
                    <td><?php echo htmlspecialchars($row['text']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <!-- Beitrag löschen button -->
                            <button type="submit" name="delete_newsletter" value="<?php echo $row['news_id']; ?>"
                                class="btn btn-danger btn-sm">Beitrag Löschen</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Keine Newsletter vorhanden</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="d-flex justify-content-center">
    <a href="newsbeitrag.upload.php" class="action-button" style="width: 250px; text-align: center;">
        News-Beitrag erstellen
    </a>
</div>

<?php include '../includes/footer.php'; ?>