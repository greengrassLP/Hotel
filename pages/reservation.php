<?php
require_once '../config.php';
include '../includes/header.php';

// Überprüfen, ob der Benutzer eingeloggt ist
/*
if (!isset($_SESSION['user'])) {
    echo "Bitte loggen Sie sich ein, um Reservierungen vorzunehmen.";
    exit;
}*/

// Statische Reservierungsdaten
$reservations = [
    [
        'id' => 1,
        'checkin' => '2024-11-25',
        'checkout' => '2024-11-30',
        'breakfast' => true,
        'parking' => false,
        'pets' => true,
        'status' => 'bestätigt',
    ],
    [
        'id' => 2,
        'checkin' => '2024-12-05',
        'checkout' => '2024-12-10',
        'breakfast' => false,
        'parking' => true,
        'pets' => false,
        'status' => 'neu',
    ],
];

// Neue Reservierung hinzufügen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_reservation'])) {
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $breakfast = isset($_POST['breakfast']);
    $parking = isset($_POST['parking']);
    $pets = isset($_POST['pets']);

    // Validierung
    if (strtotime($checkout) <= strtotime($checkin)) {
        echo "Das Abreisedatum darf nicht vor oder gleich dem Anreisedatum sein.";
    } else {
        $new_reservation = [
            'id' => count($reservations) + 1,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'breakfast' => $breakfast,
            'parking' => $parking,
            'pets' => $pets,
            'status' => 'neu',
        ];
        $reservations[] = $new_reservation;
        echo "Reservierung erfolgreich angelegt.";
    }
}

// HTML-Formular und Liste anzeigen
?>


<body class="bg-light">
    <div class="container mt-5">
        
        <h1 class="text-center text-primary" style="color: #1587e5;">Zimmer Reservierungen</h1>

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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?= $reservation['id'] ?></td>
                                    <td><?= $reservation['checkin'] ?></td>
                                    <td><?= $reservation['checkout'] ?></td>
                                    <td><?= $reservation['breakfast'] ? 'Ja' : 'Nein' ?></td>
                                    <td><?= $reservation['parking'] ? 'Ja' : 'Nein' ?></td>
                                    <td><?= $reservation['pets'] ? 'Ja' : 'Nein' ?></td>
                                    <td><?= ucfirst($reservation['status']) ?></td>
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