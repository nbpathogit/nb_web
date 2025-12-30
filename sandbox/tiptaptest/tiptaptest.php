<?php




//CREATE TABLE nbpa_data (
//    id INT AUTO_INCREMENT PRIMARY KEY,
//    content TEXT NOT NULL
//);




// ===== DB CONFIG =====
require '../../includes/init.php';
$conn = (new Database())->getConnMysqli();






if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO nbpa_data (content) VALUES (?)");
    $stmt->bind_param("s", $content);
    $stmt->execute();
    $stmt->close();
}

$latestContent = "";
$result = $conn->query("SELECT * FROM nbpa_data ORDER BY id DESC LIMIT 1");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $latestContent = $row['content'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Summernote PHP + MySQL Example</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- Summernote CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">

  <style>
    /* Force Angsana New as default font */
    .note-editable {
      font-family: 'Angsana New', serif !important;
      font-size: 16px;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Summernote Editor Demo (Angsana New Default)</h2>
  <form method="post">
    <textarea id="summernote" name="content"><?php echo htmlspecialchars($latestContent); ?></textarea>
    <br>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>

  <hr>
  <h3>Last Saved Entry:</h3>
  <?php
  if (!empty($latestContent)) {
      echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px; font-family:Angsana New, serif;'>";
      echo $latestContent;
      echo "</div>";
  } else {
      echo "<p>No entries yet.</p>";
  }
  ?>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>

<script>
  $(document).ready(function() {
    $('#summernote').summernote({
      height: 200,
      placeholder: 'Write your content here...',
      fontNames: ['Angsana New', 'Arial', 'Courier New', 'Tahoma'], // add Angsana New
      fontNamesIgnoreCheck: ['Angsana New'], // force Summernote to accept it
      toolbar: [
        ['style', ['style']],
        ['font', ['fontname', 'fontsize', 'bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview']]
      ]
    });

    // Set Angsana New as default font
    $('#summernote').summernote('fontName', 'Angsana New');
  });
</script>
</body>
</html>
