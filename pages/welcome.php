<?php
require_once '../config.php';
include '../includes/header.php';

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    header("Location: pages/login.php"); // Falls nicht eingeloggt, zurück zur Login-Seite
    exit();
}

if ($_SESSION['role'] === 'admin') {
    header("Location: /Hotel/pages/admin.php");
    exit();
}
?>

    <title>Willkommen</title>
<body>
    <div class="welcome-container">
        <h1>Willkommen, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Sie sind erfolgreich eingeloggt.</p>
        <div class="welcome-actions">
            <a href="profile.php" class="action-button">Profil bearbeiten</a>
            <a href="reservation.php" class="action-button2">Zimmer reservieren</a>
        </div>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>