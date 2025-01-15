<?php
require_once '../config.php';
require_once '../dbconnection.php';
include '../includes/header.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Neue Reservierung hinzufügen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_reservation'])) {
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $breakfast = isset($_POST['breakfast']) ? 1 : 0;
    $parking = isset($_POST['parking']) ? 1 : 0;
    $pets = isset($_POST['pets']) ? 1 : 0;
    $price = rand(100, 500); // Beispielhafter Preis, anpassbar nach Bedarf
    $status = 'neu';

    // Validierung
    if (strtotime($checkout) <= strtotime($checkin)) {
        $error = "Das Abreisedatum darf nicht vor oder gleich dem Anreisedatum sein.";
    } else {
        $query = "INSERT INTO reservations (user_id, checkin_date, checkout_date, breakfast, parking, pets, price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $user_id = $_SESSION['id'] ?? 1; // Ersetzen mit der tatsächlichen Benutzer-ID aus der Session

        $stmt->bind_param('issiiisi', $user_id, $checkin, $checkout, $breakfast, $parking, $pets, $price, $status);

        if ($stmt->execute()) {
            $success = "Reservierung erfolgreich angelegt.";
        } else {
            $error = "Fehler beim Hinzufügen der Reservierung: " . $conn->error;
        }
    }
}

// Alle Reservierungen abrufen
$reservations = [];
$query = "SELECT * FROM reservations ORDER BY creation_date DESC";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
} else {
    $error = "Fehler beim Abrufen der Reservierungen: " . $conn->error;
}
?>


<body class="bg-light">
    <div class="container mt-5">
        
        <h1 class="text-center text-primary" style="color: #1587e5;">Zimmer Reservierungen</h1>

        <!-- Fehlermeldungen anzeigen -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Neue Reservierung -->
        <div class="card mt-4">
            <div class="card-header" style="background-color: #1587e5; color: white;">
                <h2>Neue Reservierung anlegen</h2>
            </div>
            <div class="card-body">
                <form action="../logic/reservation.handler.php" method="POST">
                   <div class="mb-3">
                        <label for="checkin_date" class="form-label">Anreisedatum:</label>
                        <input type="date" name="checkin_date" id="checkin_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="checkout_date" class="form-label">Abreisedatum:</label>
                        <input type="date" name="checkout_date" id="checkout_date" class="form-control" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="breakfast" id="breakfast">
                        <label for="breakfast" class="form-check-label">Mit Frühstück</label>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="parking" id="parking">
                        <label for="parking" class="form-check-label">Mit Parkplatz</label>
                    </div>

                    <div class="form-check mb-3">
                        <label for="pets" class="form-check-label">Haustiere mitnehmen</label>
                        <input type="checkbox" class="form-check-input" name="pets" id="pets">
                    </div>

                    <button type="submit" name="new_reservation" class="btn btn-success">Reservierung anlegen</button>
                </form>
            </div>
        </div>

        <!-- Liste aller Reservierungen -->
        <div class="card mt-5">
            <div class="card-header bg-success text-white">
                <h2>Ihre Reservierungen</h2>
            </div>
            <div class="card-body">
                <?php if (count($reservations) > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Anreise</th>
                                <th>Abreise</th>
                                <th>Frühstück</th>
                                <th>Parkplatz</th>
                                <th>Haustiere</th>
                                <th>Preis</th>
                                <th>Status</th>
                                <th>Erstellt am</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?= $reservation['id'] ?></td>
                                    <td><?= $reservation['checkin_date'] ?></td>
                                    <td><?= $reservation['checkout_date'] ?></td>
                                    <td><?= $reservation['breakfast'] ? 'Ja' : 'Nein' ?></td>
                                    <td><?= $reservation['parking'] ? 'Ja' : 'Nein' ?></td>
                                    <td><?= $reservation['pets'] ? 'Ja' : 'Nein' ?></td>
                                    <td><?= htmlspecialchars($reservation['price']) ?> EUR</td>
                                    <td><?= ucfirst($reservation['status']) ?></td>
                                    <td><?= $reservation['creation_date'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Keine Reservierungen vorhanden.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
<?php include '../includes/footer.php'; ?>

</html>

