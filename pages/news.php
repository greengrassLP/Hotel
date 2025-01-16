<?php
// Verbindung zur Datenbank herstellen
require_once '../config.php';
require_once '../dbconnection.php';
include '../includes/header.php';

// Abrufen der News-Beiträge aus der Datenbank
$query = "SELECT * FROM news ORDER BY date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<div class='alert alert-danger'>Fehler beim Abrufen der News-Beiträge: " . mysqli_error($conn) . "</div>";
    exit;
}
?>

<body>
    <div class="container">
        <h1 class="mb-4" style="color: #000; font-weight: bold;">News Beiträge</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="news-list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="news-item">
                        <h2><?= htmlspecialchars($row['title']) ?></h2>
                        <?php if (!empty($row['picture'])): ?>
                            <img src="../uploads/news/<?= htmlspecialchars($row['picture']) ?>" alt="News Bild" style="max-width: 100%; height: auto;">
                        <?php endif; ?>
                        <p><?= nl2br(htmlspecialchars($row['text'])) ?></p>
                        <p><small>Veröffentlicht am: <?= htmlspecialchars($row['date']) ?></small></p>
                    </div>
                    <hr>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center">Keine News-Beiträge gefunden.</p>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
include '../includes/footer.php';
?>