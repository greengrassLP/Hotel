<?php
require_once '../config.php';
require_once '../dbconnection.php';

// Debugging aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Überprüfen der Datenbankverbindung
if (!$conn) {
    die("Datenbankverbindung fehlgeschlagen: " . $conn->connect_error);
}

// Alle Benutzer abrufen
$query = "SELECT id, salutation, firstname, lastname, email, role, status FROM users";
$result = $conn->query($query);

if (!$result) {
    die("Fehler bei der Abfrage: " . $conn->error);
}

// Daten für das Frontend vorbereiten
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// JSON-Antwort für das Frontend
header('Content-Type: application/json');
echo json_encode($users);
?>
