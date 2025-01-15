<?php
require_once '../dbconnection.php';

//  Formulardaten abrufen
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $salutation = $_POST['salutation'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password !== $password2) {
        die("Passwörter stimmen nicht überein!");
    }

    // 4. Passwort hashen
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // 5. SQL-Query vorbereiten
    $stmt = $conn->prepare("INSERT INTO users (salutation, firstname, lastname, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssss", $salutation, $firstname, $lastname, $email, $username, $hashed_password);


     // 6. Wenn Query ausgeführt weiterleiten
     if ($stmt->execute()) {
        // Erfolgreiche Registrierung -> Weiterleitung
        header("Location: ../pages/login.php");
        exit();
    } else {
        die("SQL-Fehler: " . $stmt->error);
    }

    // 7. Verbindung schließen
    $stmt->close();
    $conn->close();
}
?>