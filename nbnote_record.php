<?php


require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("nbnote_record.php");

require 'user_auth.php';

// ===== DB CONFIG =====

$conn = (new Database())->getConnMysqli();

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Include the Nbnote_record class
require 'classes/Nbnote_record.php';
$record = new Nbnote_record($conn);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_record'])) {
    // Handle delete request
    $sub_id = isset($_POST['sub_id']) && !empty($_POST['sub_id']) ? intval($_POST['sub_id']) : null;
    
    if ($sub_id !== null) {
        $result = $record->deleteSubID($sub_id);
        
        if ($result['success']) {
            header('Content-Type: application/json');
            echo json_encode(array('success' => true, 'message' => 'Record deleted successfully'));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'error' => $result['error']));
            exit;
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'error' => 'No record ID provided'));
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    //var_dump($_POST); // Debugging line to check POST data
    //die();  

    $content = $_POST['content'];
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $sub_id = isset($_POST['sub_id']) ? intval($_POST['sub_id']) : 1;
    $record_id = isset($_POST['record_id']) && !empty($_POST['record_id']) ? intval($_POST['record_id']) : null;
    $isBlankRecord = isset($_POST['create_blank']) ? true : false;
    
    // Use the class method to insert or update the record
    $record->setSubId($sub_id);
    $record->setDescription($description);
    $record->setContent($content);
    $record->setRsv(NULL);
    
    // Determine if we're creating or updating
    if ($isBlankRecord) {
        // Creating a new blank record
        $result = $record->create();
    } elseif ($record_id !== null) {
        // Updating an existing record
        //$result = $record->update($record_id);
        $result = $record->create();
    } else {
        // Creating a new normal record
        $result = $record->create();
    }
    
    if ($result['success']) {
        if ($isBlankRecord) {
            // Return JSON response for AJAX calls
            header('Content-Type: application/json');
            echo json_encode(array('success' => true, 'message' => 'Blank record created successfully', 'sub_id' => $sub_id, 'id' => $result['id']));
            exit;
        } else {
            // Reload the page after successful save
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    } else {
        if ($isBlankRecord) {
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'error' => $result['error']));
            exit;
        } else {
            echo "Error: " . $result['error'];
        }
    }
}

// Fetch all latest records grouped by sub_id using the class method
$latestContent = "";
$allRecords = array();
$result = $record->getBySubId();

if ($result['success'] && !empty($result['data'])) {
    $allRecords = $result['data'];
    // Get the first record's content for prefilling (sub_id = 1)
    foreach ($allRecords as $rec) {
        if ($rec['sub_id'] == 1) {
            $latestContent = $rec['content'];
            break;
        }
    }
} else {
    $latestContent = "";
}

// Get the next sub_id for creating new records
$nextSubIdResult = $record->getNextSubId();
$nextSubId = ($nextSubIdResult['success']) ? $nextSubIdResult['next_subid'] : 1;

?>

<?php require 'includes/header.php'; ?>

  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- Summernote CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">


<?php if ($isCurUserAdmin): ?>
<!--<div class="container">-->
<div class="container-md bg-blue-a">
  <h2>NB Note Editor</h2>
  <form method="post">
    <!-- Dropdown list to select records -->
    <div class="form-group">
      <label for="recordSelect">Select Record by Sub ID:</label>
      <select id="recordSelect" class="form-control" style="width: 300px;">
        <option value="">-- Select a record --</option>
        <?php if (!empty($allRecords)): ?>
          <?php foreach ($allRecords as $rec): ?>
            <option value="<?php echo htmlspecialchars($rec['content']); ?>" data-id="<?php echo $rec['id']; ?>" data-subid="<?php echo $rec['sub_id']; ?>" data-description="<?php echo htmlspecialchars($rec['description']); ?>" data-date="<?php echo $rec['create_date']; ?>">
              Item ID: <?php echo $rec['sub_id']; ?> : <?php echo $rec['description']; ?> 
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
    
    <!-- Description field -->
    <div class="form-group">
      <label for="description">Description:</label>
      <input type="text" id="description" name="description" class="form-control" placeholder="Enter description" style="width: 500px;">
    </div>
    
    <!-- Sub ID field (for new records) -->
    <div class="form-group">
<!--      <input type="hidden" id="subId" name="sub_id" value="<?php //echo htmlspecialchars($nextSubId); ?>">
      <input type="hidden" id="recordId" name="record_id" value="">-->
      hidden SubID<input type="" id="subId" name="sub_id" readonly value="">
      hidden record id<input type="" id="recordId" name="record_id" readonly value="">
      hidden nextSubID<input type="" id="next_sub_id" name="next_sub_id" readonly value="<?php echo htmlspecialchars($nextSubId); ?>">
    </div>
    
    <!-- Prefill editor with latest content -->

    
    <span id="editorContainer">
    <textarea id="summernote" name="content"></textarea>
    </span>
    <br>
    <button type="submit" class="btn btn-primary" id="saveBtn" disabled>Save</button>
    <button type="button" class="btn btn-success" id="newRecordBtn">+ New Record</button>
    <button type="button" class="btn btn-danger" id="deleteRecordBtn" style="display:none;" disabled>Delete Record</button>
  </form>
</div>
<?php endif; ?>
  
<div id="" class="container-md bg-blue-a">
  <hr>
  <h3>Content Detail:</h3>
  <div id="changeHistoryContainer" style="display:none;">
    <!-- Selected record will be displayed here -->
  </div>
  <?php
  if (empty($allRecords)) {
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
    
    $("#manage_table").addClass("active");
    $("#note_tab").addClass("active");
    $("#manage_table").addClass("show");
    $(".manage_table_dropdown").addClass("show");
  /**
   * Initialize Summernote editor with configuration
   */
  function initSummernote() {
    $('#summernote').summernote({
      height: 200,
      placeholder: 'Write your content here...',
      fontNames: ['Calibri', 'Angsana', 'Arial'],
      fontNamesIgnoreCheck: ['Calibri', 'Angsana', 'Arial'],
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

    // Set Calibri as default font
    $('#summernote').summernote('fontName', 'Calibri');
  }

  /**
   * Set description value from dropdown selection to Summernote editor
   * @param {string} description - The description text to set
   */
  function setDescriptionToEditor(description) {
    if (description) {
       $('#summernote').summernote('code', description );
        // Set Calibri as default font
        $('#summernote').summernote('fontName', 'Calibri');
    }else{
       $('#summernote').summernote('code', "" );
       // Set Calibri as default font
       $('#summernote').summernote('fontName', 'Calibri');
    }
  }

  /**
   * Escape HTML special characters
   * @param {string} text - Text to escape
   * @returns {string} Escaped text
   */
  function htmlEscape(text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
  }

  $(document).ready(function() {
    initSummernote();
    
    // Handle dropdown selection to populate editor
    $('#recordSelect').on('change', function() {
      var selectedContent = $(this).val();
      var selectedDescription = $(this).find('option:selected').data('description');
      var selectedSubId = $(this).find('option:selected').data('subid');
      var selectedId = $(this).find('option:selected').data('id');
      var selectedDate = $(this).find('option:selected').data('date');
      console.log("selectedContent::"+selectedContent);
      console.log("selectedDescription::"+selectedDescription);
      console.log("selectedSubId::"+selectedSubId);
      console.log("selectedId::"+selectedId);
      console.log("selectedDate::"+selectedDate);
      
      if (true) {
        $('#summernote').summernote('code', selectedContent);
        $('#description').val(selectedDescription || '');
        $('#subId').val(selectedSubId);
        $('#recordId').val(selectedId || ''); // Store record ID for update
        
        // Show/hide delete button based on whether a record is selected
        if (selectedId) {
          $('#deleteRecordBtn').show();
          $('#saveBtn').prop('disabled', false);
        } else {
          $('#deleteRecordBtn').hide();
          $('#saveBtn').prop('disabled', true);
        }
        
        // Set description to the Summernote editor
        setDescriptionToEditor(selectedContent);
        
        // Display only the selected record in Change History
        var historyHtml = '<div style="border:1px solid #ccc; padding:10px; margin-bottom:15px; font-family:Angsana New;">';
        historyHtml += '<strong>Sub ID: ' + htmlEscape(selectedSubId.toString()) + '</strong> | ';
        historyHtml += '<strong>Date: ' + htmlEscape(selectedDate) + '</strong>';
        if (selectedDescription) {
          historyHtml += '<br><strong>Description: </strong>' + htmlEscape(selectedDescription);
        }
        historyHtml += '<hr style="margin: 10px 0;">';
        historyHtml += selectedContent; // Don't escape so iframe renders
        historyHtml += '</div>';
        
        $('#changeHistoryContainer').html(historyHtml).show();
      } else {
        $('#changeHistoryContainer').hide(); // Hide history if no selection
        $('#deleteRecordBtn').hide();
      }
//      alert("selection change");
    });
    
    // Handle New Record button
    $('#newRecordBtn').on('click', function() {
      if (!confirm('Do you want to create a new blank record?')) {
        return;
      }

      var nextSubId = <?php echo $nextSubId; ?>;
      
      // Make AJAX call to create a blank record in the database
      $.ajax({
        url: window.location.href, // Same page
        type: "POST",
        data: {
          'content': '', // Blank content
          'description': '', // Blank description
          'sub_id': nextSubId,
          'create_blank': true // Flag to indicate blank record creation
        },
        success: function(response) {
          var resp = (typeof response === 'object') ? response : JSON.parse(response);
          
          // Clear all fields for new record
          $('#recordSelect').val(''); // Reset dropdown
          $('#description').val(''); // Clear description
          $('#summernote').summernote('code', ''); // Clear editor
          $('#subId').val(nextSubId); // Set next sub_id
          $('#recordId').val(resp.id); // Store the new record ID for updating
          $('#saveBtn').prop('disabled', false);
          
          alert('New blank record created in database with Sub ID: ' + nextSubId + '\nNow you can add description and content, then click Save.');
          
          // Reload page to refresh dropdown list
          setTimeout(function() {
            location.reload();
          }, 1500);
        },
        error: function(xhr, status, error) {
          alert('Error creating new record: ' + error);
        }
      });
    });
    
    // Handle Delete Record button
    $('#deleteRecordBtn').on('click', function() {
      var subId = $('#subId').val();
      var recordId = $('#recordId').val();
      var selectedDescription = $('#description').val();
      
      if (!recordId) {
        alert('No record selected for deletion.');
        return;
      }
      
      // Confirm deletion
      var confirmMsg = 'Are you sure you want to delete this record?';
      if (selectedDescription) {
        confirmMsg += '\n' + selectedDescription;
      }
      
      if (!confirm(confirmMsg)) {
        return;
      }
      
      // Make AJAX call to delete the record
      $.ajax({
        url: window.location.href, // Same page
        type: "POST",
        data: {
          'sub_id': subId,
          'delete_record': true // Flag to indicate delete operation
        },
        success: function(response) {
          var resp = (typeof response === 'object') ? response : JSON.parse(response);
          
          if (resp.success) {
            alert('Record deleted successfully!');
            
            // Clear all fields after deletion
            $('#recordSelect').val(''); // Reset dropdown
            $('#description').val(''); // Clear description
            $('#summernote').summernote('code', ''); // Clear editor
            $('#recordId').val(''); // Clear record ID
            $('#deleteRecordBtn').hide(); // Hide delete button
            $('#changeHistoryContainer').hide();
            
            // Reload page to refresh dropdown list
            setTimeout(function() {
              location.reload();
            }, 1500);
          } else {
            alert('Error: ' + resp.error);
          }
        },
        error: function(xhr, status, error) {
          alert('Error deleting record: ' + error);
        }
      });
    });
  });

  function sendFile(file) {
    var data = new FormData();
    data.append("file", file);
    $.ajax({
      url: "nbnote_record_upload.php",   // PHP script to handle upload
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
