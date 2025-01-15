<?php
require_once '../config.php';
require_once '../dbconnection.php';


if (!isset($_SESSION['id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$id = $_SESSION['id']; // Benutzer-ID aus der Session abrufen

// Benutzerinformationen abrufen
$query = "SELECT firstname, lastname, email, username, password FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    die("Benutzer nicht gefunden.");
}

// Wenn das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['changePassword'])) {
        // Passwort ändern
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // Überprüfen, ob das alte Passwort korrekt ist
        if (!password_verify($oldPassword, $user['password'])) {
            die("Das alte Passwort ist falsch.");
        }

        // Überprüfen, ob das neue Passwort mit der Bestätigung übereinstimmt
        if ($newPassword !== $confirmPassword) {
            die("Die neuen Passwörter stimmen nicht überein.");
        }

        // Neues Passwort hashen und aktualisieren
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $hashedPassword, $id);

        if ($stmt->execute()) {
            echo "Passwort erfolgreich geändert.";
        } else {
            echo "Fehler beim Aktualisieren des Passworts: " . $conn->error;
        }
    } else {
        // Profil bearbeiten
        $fields = [];
        $params = [];
        $types = '';

        // Überprüfe jedes Feld und füge es nur hinzu, wenn es nicht leer ist
        if (!empty($_POST['firstname'])) {
            $fields[] = "firstname = ?";
            $params[] = $_POST['firstname'];
            $types .= 's';
        }
        if (!empty($_POST['lastname'])) {
            $fields[] = "lastname = ?";
            $params[] = $_POST['lastname'];
            $types .= 's';
        }
        if (!empty($_POST['email'])) {
            $fields[] = "email = ?";
            $params[] = $_POST['email'];
            $types .= 's';
        }
        if (!empty($_POST['username'])) {
            $fields[] = "username = ?";
            $params[] = $_POST['username'];
            $types .= 's';
        }

        // Wenn keine Felder aktualisiert werden sollen, abbrechen
        if (empty($fields)) {
            die("Keine Änderungen vorgenommen.");
        }

        // Baue die SQL-Abfrage dynamisch auf
        $query = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $id;
        $types .= 'i';

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "Profil erfolgreich aktualisiert.";
            if (!empty($_POST['username'])) {
                $_SESSION['username'] = $_POST['username']; // Username in der Session aktualisieren
            }
        } else {
            echo "Fehler beim Aktualisieren des Profils: " . $conn->error;
        }
    }
}
?>
