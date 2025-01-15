<?php
require_once '../config.php';
include '../includes/header.php';
include '../dbconnection.php';

// Fehlermeldung aus der Session lesen und danach entfernen
$error = isset($_SESSION['change_error']) ? $_SESSION['change_error'] : '';
unset($_SESSION['change_error']);

$echo = isset($_SESSION['change_echo']) ? $_SESSION['change_echo'] : '';
unset($_SESSION['change_echo']);

// Benutzerinformationen abrufen
$user_salutation = '';
$user_firstname = '';
$user_lastname = '';
$user_email = '';
$user_username = '';

$query = "SELECT salutation, firstname, lastname, email, username FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $user_salutation = $user['salutation'];
    $user_firstname = $user['firstname'];
    $user_lastname = $user['lastname'];
    $user_email = $user['email'];
    $user_username = $user['username'];
}
?>

<body>
    <h1>Willkommen, <?php echo htmlspecialchars($user_username); ?>!</h1>

    <div class="profile-container">
        <!-- Fehlermeldungen oder Erfolgsmeldungen anzeigen -->
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($echo)): ?>
            <div class="success-message"><?php echo htmlspecialchars($echo); ?></div>
        <?php endif; ?>

        <!-- Profil bearbeiten -->
        <form action="../logic/profile.handler.php" method="POST">
            <h2>Profil bearbeiten</h2>
            <div class="form-group">
                <label for="firstname">Vorname:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user_firstname); ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Nachname:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user_lastname); ?>">
            </div>
            <div class="form-group">
                <label for="email">E-Mail:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Benutzername:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_username); ?>" required>
            </div>
            <button type="submit" class="action-button">Daten aktualisieren</button>
        </form>

        <!-- Passwort ändern -->
        <div class="profile-section">
            <form action="../logic/profile.handler.php" method="POST">
                <h2>Passwort ändern</h2>
                <input type="hidden" name="changePassword" value="1">
                <div class="form-group">
                    <label for="oldPassword">Altes Passwort:</label>
                    <input type="password" id="oldPassword" name="oldPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Neues Passwort:</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Neues Passwort bestätigen:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" name="changePassword" class="action-button">Passwort ändern</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
