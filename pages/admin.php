<?php

require_once '../config.php';
include '../includes/header.php';

// Überprüfen, ob ein Admin eingeloggt ist
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Hotel/pages/welcome.php");
    exit();
}
?>

<title>Willkommen</title>
<body>
    <div class="welcome-container">
        <h1>Willkommen, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Sie sind erfolgreich eingeloggt.</p>
        <div class="welcome-actions">
            <a href="user.overview.php" class="action-button">User</a>
            <a href="reservation.overview.php" class="action-button2">Reservierungen</a>
            <a href="newsbeitrag.overview.php" class="action-button2">News-Beiträge Übersicht</a>
        </div>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>