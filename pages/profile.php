<?php
require_once '../config.php';
include '../includes/header.php';
include '../dbconnection.php';
?>

<body>
    <?php
       $user_salutation;
       $user_firstname;
       $user_lastname;
       $user_email;
       $user_username;
       $old_password;

       if ($conn->connect_error) {
        echo "Connection Error: " . $conn -> connect_error;
        exit();
    }else{
        $sql = "SELECT * FROM users";
        $result = $conn -> query($sql);
        $user_found = false;
    
        while ($row = $result->fetch_array()) { 
    
            if ($row['username'] === $_SESSION['username']) {
                $user_salutation = $row['salutation'];
                $user_firstname = $row['firstname'];
                $user_lastname = $row['lastname'];
                $user_email = $row['email'];
                $user_username = $row['username'];
                $old_password = $row['password'];
                break;
            }
        }
    }
       

        ?>   
        <!--Stammdaten bearbeiten -->

        <h1>Willkommen, <?php echo htmlspecialchars($user_username); ?>!</h1>

        <div class="profile-container">
        <form action="../logic/profile.handler.php" method="POST">
    
        <h1>Profil bearbeiten</h1>
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
        <label for="username">Username:</label>
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
                    <label for="oldPassword">Altes Passwort</label>
                    <input type="password" id="oldPassword" name="oldPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Neues Passwort</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Neues Passwort bestätigen</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" name="changePassword" class="action-button">Passwort ändern</button>
        </form>
    </div>
    </div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
