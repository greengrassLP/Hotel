<?php
require_once '../config.php';
include '../includes/header.php';


  // path to upload directory in a variable
  /*$uploadDir ="../uploads/news";

  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
    
  }


  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
   
    $uploadFile = $uploadDir;
   
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        echo '<div class="alert alert-success">File uploaded successfully to: ' . htmlspecialchars($uploadFile) . '</div>';
      } else {
        echo '<div class="alert alert-danger">File upload failed!</div>';
      }
    } */

$newsletter_form_data = $_SESSION['newsletter_form_data'] ?? [];
?>



<div class="container mt-5">
    <h1 class="text-center mb-4">Newsbeitrag erstellen</h1>

    <!-- newsletterform -->
    <form action="/Hotel/logic/upload.handler.php" method="POST" enctype="multipart/form-data" class="mt-4">
        <!--Titel-->
        <div class="mb-3">
            <label for="title" class="form-label">Titel</label>
            <input type="text" class="form-control" id="title" name="title" 
            value="<?php echo htmlspecialchars($newsletter_form_data['title'] ?? '', ENT_QUOTES); ?>" required>
        </div>

        <!--Text-->
        <div class="mb-3">
            <label for="text" class="form-label">Content</label>
            <textarea class="form-control" id="text" name="text" rows="8" required>
            <?php echo htmlspecialchars($newsletter_form_data['text'] ?? '', ENT_QUOTES); ?></textarea>
        </div>
        
        <!--Bild upload-->
        <div class="mb-3">
            <label for="picture" class="form-label">Bild hochladen</label>
            <input type="file" class="form-control" id="picture" name="picture">
        </div>

        <!-- submit button -->
        <button type="submit" class="btn btn-primary">Newsbeitrag erstellen</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>