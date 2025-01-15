<?php
require_once '../config.php';
require_once '../dbconnection.php';

// Reservierungen aus der Datenbank abrufen
function fetchReservations($conn, $statusFilter = null) {
    $query = "SELECT * FROM reservations";
    if ($statusFilter) {
        $query .= " WHERE status = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $statusFilter);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    $result = $conn->query($query);
    if (!$result) die("Fehler beim Abrufen der Reservierungen: " . $conn->error);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Reservierungsstatus aktualisieren
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $query = "UPDATE reservations SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $status, $id);

    if ($stmt->execute()) {
        echo "Status erfolgreich aktualisiert.";
    } else {
        echo "Fehler beim Aktualisieren des Status: " . $conn->error;
    }
    exit;
}

// Reservierungen für AJAX-Anfragen abrufen
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_reservations'])) {
    $statusFilter = isset($_GET['status']) && $_GET['status'] !== 'all' ? $_GET['status'] : null;
    header('Content-Type: application/json');
    echo json_encode(fetchReservations($conn, $statusFilter));
    exit;
}

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservierungsverwaltung</title>
    <script>
        // Reservierungen abrufen und anzeigen
        async function fetchReservations(status = 'all') {
            const response = await fetch(`?fetch_reservations=true&status=${status}`);
            if (!response.ok) throw new Error('Fehler beim Abrufen der Reservierungen');

            const reservations = await response.json();
            const tableBody = document.getElementById('reservation-table-body');
            tableBody.innerHTML = reservations.map(reservation => `
                <tr>
                    <td>${reservation.id}</td>
                    <td>${reservation.user_id}</td>
                    <td>${reservation.checkin_date}</td>
                    <td>${reservation.checkout_date}</td>
                    <td>${reservation.breakfast ? 'Ja' : 'Nein'}</td>
                    <td>${reservation.parking ? 'Ja' : 'Nein'}</td>
                    <td>${reservation.pets}</td>
                    <td>${reservation.price} €</td>
                    <td>
                        <select onchange="updateStatus(${reservation.id}, this.value)">
                            <option value="neu" ${reservation.status === 'neu' ? 'selected' : ''}>Neu</option>
                            <option value="bestätigt" ${reservation.status === 'bestätigt' ? 'selected' : ''}>Bestätigt</option>
                            <option value="storniert" ${reservation.status === 'storniert' ? 'selected' : ''}>Storniert</option>
                        </select>
                    </td>
                </tr>`).join('');
        }

        // Status ändern
        async function updateStatus(id, newStatus) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', newStatus);

            const response = await fetch('', { method: 'POST', body: formData });

            if (response.ok) {
                alert('Status erfolgreich aktualisiert!');
                fetchReservations();
            } else {
                alert('Fehler beim Aktualisieren des Status.');
            }
        }

        // Filter ändern
        function changeFilter() {
            const status = document.getElementById('status-filter').value;
            fetchReservations(status);
        }

        window.onload = () => fetchReservations();
    </script>
</head>
<body>
    <h1>Reservierungsverwaltung</h1>

    <label for="status-filter">Status filtern:</label>
    <select id="status-filter" onchange="changeFilter()">
        <option value="all">Alle</option>
        <option value="neu">Neu</option>
        <option value="bestätigt">Bestätigt</option>
        <option value="storniert">Storniert</option>
    </select>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Frühstück</th>
                <th>Parkplatz</th>
                <th>Haustiere</th>
                <th>Preis</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="reservation-table-body">
            <!-- Reservierungen werden hier eingefügt -->
        </tbody>
    </table>
</body>
</html>

<?php include '../includes/footer.php'; ?>