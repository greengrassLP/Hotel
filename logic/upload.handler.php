<?php
require_once '../config.php';
include '../dbconnection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['picture'];
    $fileName = $file['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        echo "Keine Datei ausgewählt";
        exit;
    }elseif($fileExtension != "png" && $fileExtension != 'jpg' && $fileExtension != 'jpeg'){
        echo "Datei nicht erlaubt!";
        exit();
    }else{
        if (!is_dir('uploads')) {
            mkdir('uploads');
        }
        list($width, $height) = getimagesize($file['tmp_name']);
        $new_width = 1000; 
        $new_height = 800;
        $thumbnail = imagecreatetruecolor($new_width, $new_height);
        imageantialias($thumbnail, true); 

        //Bild qualitativer machen
        if ($fileExtension === 'jpg' || $fileExtension === 'jpeg') {
            $source_image = imagecreatefromjpeg($file['tmp_name']);
        } elseif ($fileExtension === 'png') {
            $source_image = imagecreatefrompng($file['tmp_name']);
        }

        // Bild schrumpfen
        imagecopyresampled(
            $thumbnail,
            $source_image,
            0, 0, 0, 0,
            $new_width, $new_height,
            $width, $height
        );

        //Thumbnail als Pfad speichern
        $thumbnail_path = 'news/' . $fileName;
        if ($fileExtension === 'jpg' || $fileExtension === 'jpeg') {
            imagejpeg($thumbnail, $thumbnail_path, 95);
        } elseif ($fileExtension === 'png') {
            imagepng($thumbnail, $thumbnail_path);
        }

        // Speicher frei machen
        imagedestroy($thumbnail);
        imagedestroy($source_image);

        // path for database
        $picture = .$thumbnail_path;
    }

    // Retrieve and trim inputs
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['text'] ?? '');

    // Store form data in session
    $_SESSION['newsletter_form_data'] = [
        'title' => $title,
        'content' => $content,
    ];

    // Validation
    if (empty($title)) {
        echo "Wählen Sie einen Titel!";   
        exit();
    } 
    
    if (empty($content)) {
        echo "Ungültiger Text!";
        exit();
    }


        $date = new DateTime();
        $date = $date->format('Y-m-d');
        $sql = "INSERT INTO `news` (`title`, `picture`, `text`, `date`) VALUES (?, ?, ?, ?); " ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $picture, $text, $date);
        
        $stmt->execute();
        exit(); 

        unset($_SESSION['newsletter_form_data']);
            header("Location: ../pages/newsbeitrag.upload.php");
            echo "Beitrag erfolgreich erstellt!"
            exit(); 
    
} 
