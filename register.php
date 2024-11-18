<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="index.php">Startseite</a></li>
            <li><a href="hilfe.html">Hilfe</a></li>
            <li><a href="legalnotice.html">Über uns</a></li>
            <li><a href="login.html">Log in</a></li>
        </ul>
    </nav>

    <p>
    <h1>Registrieren</h1>
    </p>

    <div class="register-container">
    <form>
        
        <label for="gender"> Anrede</label>
        <select id="gender" name="Anrede">
            <option>Herr</option>
            <option>Frau</option>
            <option>Divers</option>
        </select>

        <p><label for="fname">Nachname</label>
            <input id="fname" type="text" name="Name" required>

            <label for="lname">Vorname</label>
            <input id="lname" type="text" name="Vorname" required>
        </p>

        <p><label for="birthday">Geburtsdatum</label>
            <input id="birthday" type="date" name="Geburtsdatum" required>
        </p>

        <p><label for="street">Straße</label>
            <input id="street" type="text" name="Straße" required>

            <label for="housenumber">Hausnummer</label>
            <input id="housenumber" type="number" name="Hausnummer" required>
        </p>

        <label for="zip">Postleitzahl</label>
        <input id="zip" type="number" name="Postleitzahl" reequired>

        <label id="city">Ort</label>
        <input for="city" type="text" name="Ort" required>

        <p><label for="email">E-Mail-Adresse</label>
            <input id="email" type="email" name="E-Mail-Adresse" required>
        </p>

        <p></p><label for="username">Username</label>
        <input id="username" type="text" name="Username"></p>

        <p><label for="password">Passwort</label>
            <input id="password" type="password" name="Passwort">
        </p>

        <p><label for="password">Passwort</label>
            <input id="password" type="password" name="Passwort">
        </p>


        <button type="submit">Registrieren</button>

    </form>
    </div>

    <footer class="footerstatic">
        <p>&copy; 2024 Dings Hotel. Alle Rechte vorbehalten.</p>
    </footer>

</body>

</html>