<?php


require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("changehistory.php");

require 'user_auth.php';

// ===== DB CONFIG =====

$conn = (new Database())->getConnMysqli();


if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    $content = $_POST['content'];
   
    // Insert into table testtiptap
    //INSERT INTO `nbnote` (`id`, `sub_id`, `content`, `create_date`, `rsv`) VALUES (NULL, '1', 'AAA', current_timestamp(), NULL);
    $stmt = $conn->prepare("INSERT INTO `nbnote` (`id`, `sub_id`, `content`, `create_date`, `rsv`) VALUES (NULL, '2', '".$content."', current_timestamp(), NULL)");
//    echo "INSERT INTO `nbnote` (`id`, `sub_id`, `content`, `create_date`, `rsv`) VALUES (NULL, '1', '".$content."', current_timestamp(), NULL)";
//    die();
    $stmt->execute();
    $stmt->close();
}

// Fetch the latest entry to prefill editor
$latestContent = "";
$result = $conn->query("SELECT * FROM `nbnote` WHERE sub_id = 2 ORDER BY id DESC LIMIT 1");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $latestContent = $row['content'];
}

?>

<?php require 'includes/header.php'; ?>

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
<?php if ($isCurUserAdmin): ?>
<!--<div class="container">-->
<div class="container-md bg-blue-a">
  <h2>Change History Editer</h2>
  <form method="post">
    <!-- Prefill editor with latest content -->
    <textarea id="summernote" name="content"><?php echo $latestContent; ?></textarea>
    <br>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</div>
<?php endif; ?>
  
<div class="container-md bg-blue-a">
  <hr>
  <h3>Printer Setting:</h3>
  <?php
  if (!empty($latestContent)) {
      echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px; font-family:Angsana New;'>";
      echo $latestContent; // do NOT escape, so iframe renders
      echo "</div>";
  } else {
      echo "<p>No entries yet.</p>";
  }
  ?>
  <br>
</div>
<br>




<?php require 'includes/footer.php'; ?>




  
  
<!-- jQuery -->
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>

<script>
  $(document).ready(function() {
    //set active tab
    $("#printersetting_tab").addClass("active");
    
    $('#summernote').summernote({
      height: 200,
      placeholder: 'Write your content here...',
      fontNames: ['Angsana New', 'Arial', 'Courier New', 'Tahoma'],
      fontNamesIgnoreCheck: ['Angsana New'],
      toolbar: [
        ['style', ['style']],
        ['color', ['color']], // <-- add this line
        ['font', ['fontname', 'fontsize', 'bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video', 'table']], // video button enabled
        ['view', ['fullscreen', 'codeview']]
      ],
      callbacks: {
        onImageUpload: function(files) {
          sendFile(files[0]);
        }
      },
      // Allow iframe embeds
      codeviewFilter: false,
      codeviewIframeFilter: false
    });

    // Set Angsana New as default font
    $('#summernote').summernote('fontName', 'Angsana New');
  });

  function sendFile(file) {
    var data = new FormData();
    data.append("file", file);
    $.ajax({
      url: "nbnoteupload.php",   // PHP script to handle upload
      type: "POST",
      data: data,
      contentType: false,
      processData: false,
      success: function(url) {
        $('#summernote').summernote('insertImage', url);
      },
      error: function() {
        alert("Image upload failed");
      }
    });
  }
</script>
