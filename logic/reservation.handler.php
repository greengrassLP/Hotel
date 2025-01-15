<?php
require_once '../config.php';
include '../dbconnection.php';



// Check-out date must be later than check-in date
if (strtotime( $_POST['checkout_date']) <= strtotime($_POST['checkin_date'])) {
    echo "Ungültige Angaben";
    exit();
}

// check Room availability:
$checkin = $_POST['checkin_date'];
$checkout = $_POST['checkout_date'];
//Selects every reservation, where the checkoutdate is not before the selected CheckIn date or the selected checkOut date is not before the checkindate
$sql_check_room_avail = "SELECT * FROM Reservations WHERE NOT (checkout < ? OR ? < checkin) AND status != 'cancelled'"; 
$stmt = $conn->prepare($sql_check_room_avail);
$stmt->bind_param("ss", $checkin, $checkout);
$stmt->execute();
$result = $stmt->get_result();

// Checkt ob 1 von 10 Zimmer frei ist
if ($result->num_rows > 9) {
    echo "Für den gewünschten Zeitraum steht kein freies Zimmer zur Verfügung";
    exit();
}

$sql = "INSERT INTO `Reservations` (`checkin_date`, `checkout_date`, `breakfast`, `parking`, `pets`, `status`, `creation_date`, `user_id`, `price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?); " ;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssii", $checkin, $checkout, $breakfast, $parking, $pets, $user_id, $creation_date, $status, $price);

    $checkin = $_POST['checkin_date'];
    $checkout = $_POST['checkout_date'];
    $breakfast = isset($_POST['breakfast']) ? $_POST['breakfast'] : 'no';
    $parking = isset($_POST['parking']) ? $_POST['parking'] : 'no';
    $current_username = $_SESSION['username'];
    $sql_find_user_id = "SELECT * FROM users WHERE username = '$current_username'";
    $result = $conn -> query($sql_find_user_id);
    $user_id;

    while ($row = $result->fetch_array()) {
        $user_id = $row['id'];
    }
    $pets = trim($_POST['pets'] ?? '');
    $creation_date = new DateTime();
    $creation_date = $creation_date->format('Y-m-d H:i:s');
    $status = 'new';

    //calculating days (for price calc)
    $checkInDateObj = new DateTime($checkin);
    $checkOutDateObj = new DateTime($checkout);

    $interval = $checkInDateObj->diff($checkOutDateObj);
    $days = $interval->days;


    //price for one room for one night is 100€
    $price = 100 * $days;
    if($breakfast === 'yes'){
        $price += 15 * $days;
    }
    if($parking === 'yes'){
        $price += 10 * $days;
    }
    if(!empty($pets)){
        $price += 5 * $days;
    }
    
    $stmt->execute();

    if ($conn->connect_error) {
        echo "Connection Error: " . $db_obj->connect_error;
        exit();
    }

    // Clear session form data upon success
    unset($_SESSION['reservation_form_data']);
    header("Location: ../pages/reservation.php");
    echo "Reservierung erfolgreich!"
    exit();

?>
