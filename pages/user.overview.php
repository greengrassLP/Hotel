<?php
require_once '../config.php';
require_once '../dbconnection.php';

// Fetch users from the database
function fetchUsers($conn) {
    $result = $conn->query("SELECT id, salutation, firstname, lastname, username, email, role, status FROM users");
    if (!$result) die("Error fetching users: " . $conn->error);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $salutation = $_POST['salutation'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // SQL-Abfrage dynamisch aufbauen
    $query = "UPDATE users SET salutation=?, firstname=?, lastname=?, username=?, email=?, role=?, status=?";
    $params = [$salutation, $firstname, $lastname, $username, $email, $role, $status];
    $types = 'sssssss'; // Datentypen für bind_param()

    if ($password) {
        $query .= ", password=?";
        $params[] = $password;
        $types .= 's'; // Passwort ist ein String
    }
    $query .= " WHERE id=?";
    $params[] = $id;
    $types .= 'i'; // ID ist ein Integer

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "Benutzer erfolgreich aktualisiert!";
    } else {
        echo "Fehler beim Aktualisieren des Benutzers: " . $conn->error;
    }
    exit;
}

// Fetch users for AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_users'])) {
    header('Content-Type: application/json');
    echo json_encode(fetchUsers($conn));
    exit;
}

include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzerverwaltung</title>
    <script>
        async function fetchUsers() {
            try {
                const response = await fetch('?fetch_users=true');
                if (!response.ok) throw new Error('Error fetching users');

                const users = await response.json();
                const tableBody = document.getElementById('user-table-body');
                tableBody.innerHTML = users.map(user => `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.salutation}</td>
                        <td>${user.firstname}</td>
                        <td>${user.lastname}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td>${user.status}</td>
                        <td>
                            <button onclick="editUser(${user.id})">Bearbeiten</button>
                        </td>
                    </tr>
                    <tr id="edit-row-${user.id}" style="display:none">
                        <td colspan="8">
                            <form onsubmit="updateUser(event, ${user.id})">
                                ${['salutation', 'firstname', 'lastname', 'username', 'email'].map(field => `
                                    <label>${field}: <input type="text" name="${field}" value="${user[field]}" required></label>`).join('')}
                                <label>Rolle: <select name="role">
                                    <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                                    <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                                </select></label>
                                <label>Status: <select name="status">
                                    <option value="active" ${user.status === 'active' ? 'selected' : ''}>Aktiv</option>
                                    <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inaktiv</option>
                                </select></label>
                                <label>Passwort: <input type="password" name="password"></label>
                                <button type="submit">Speichern</button>
                                <button type="button" onclick="editUser(${user.id})">Abbrechen</button>
                            </form>
                        </td>
                    </tr>`).join('');
            } catch (error) {
                console.error(error);
            }
        }

        function editUser(id) {
            const row = document.getElementById(`edit-row-${id}`);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }

        async function updateUser(event, id) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            formData.append('id', id);

            if (formData.get('password') === '') {
                formData.delete('password');
            }

            const response = await fetch('', { method: 'POST', body: formData });

            if (response.ok) {
                alert('Benutzer erfolgreich aktualisiert!');
                fetchUsers();
            } else {
                alert('Fehler beim Aktualisieren des Benutzers.');
            }
        }

        window.onload = fetchUsers;
    </script>
</head>
<body>
    <h1>Benutzerverwaltung</h1>
    <button class="reservation-button" onclick="window.location.href='reservation.overview.php'">Alle Reservierungen</button>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Anrede</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Benutzername</th>
                <th>E-Mail</th>
                <th>Rolle</th>
                <th>Status</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody id="user-table-body"></tbody>
    </table>
</body>
</html>
<?php include '../includes/footer.php'; ?>
