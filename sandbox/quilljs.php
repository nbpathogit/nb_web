<?php

//SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
//START TRANSACTION;
//SET time_zone = "+00:00";

//CREATE TABLE `quilljs_test` (
//  `id` int(11) NOT NULL,
//  `content` text NOT NULL,
//  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
//  `rsv` text NOT NULL
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

//INSERT INTO `quilljs_test` (`id`, `content`, `create_date`, `rsv`) VALUES
//(1, '<p></p>', '2025-12-26 04:48:00', '');

//ALTER TABLE `quilljs_test`
//  ADD PRIMARY KEY (`id`);
//
//ALTER TABLE `quilljs_test`
//  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
//COMMIT;


// ===== DB CONFIG =====
require '../includes/init.php';
$conn = (new Database())->getConnMysqli();
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("DB Error");
}


// ===== LOAD CONTENT =====
$content = "";
$result = $conn->query("SELECT content FROM quilljs_test WHERE id = 1");
if ($row = $result->fetch_assoc()) {
    $content = $row['content'];
}

// ===== SAVE CONTENT =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $html = $_POST['content'] ?? '';

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare(
            "UPDATE quilljs_test SET content=? WHERE id=1"
        );
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO quilljs_test (content) VALUES (?)"
        );
    }

    $stmt->bind_param("s", $html);
    $stmt->execute();
    header("Location: quilljs.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>QuillJS Angsana Only + List</title>

<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<style>
  /* ===== FORCE Angsana everywhere ===== */
  body,
  .ql-editor,
  .ql-editor * {
    font-family: "Angsana New", "AngsanaNew", serif !important;
  }

  .ql-editor {
    font-size: 16pt;
  }

  #editor {
    height: 300px;
  }

  body {
    margin: 40px;
  }
</style>
</head>
<body>

<h2>QuillJS (Angsana Only – Forced) + Lists</h2>

<form method="post" onsubmit="saveContent()">

  <!-- Toolbar -->
  <div id="toolbar">
    <!-- Header -->
    <select class="ql-header">
      <option value="1">Heading 1</option>
      <option value="2">Heading 2</option>
      <option value="3">Heading 3</option>
      <option value="4">Heading 4</option>
      <option value="5">Heading 5</option>
      <option value="6">Heading 6</option>
      <option selected>Normal</option>
    </select>

    <!-- Font (locked to Angsana) -->
    <select class="ql-font">
      <option selected value="angsana">Angsana</option>
    </select>

    <!-- Formatting -->
    <button class="ql-bold"></button>
    <button class="ql-italic"></button>
    <button class="ql-underline"></button>

    <!-- Lists -->
    <button class="ql-list" value="ordered"></button>
    <button class="ql-list" value="bullet"></button>

    <select class="ql-align"></select>
    <select class="ql-color"></select>

    <!-- Clean -->
    <button class="ql-clean"></button>
  </div>

  <!-- Editor -->
  <div id="editor"><?= $content ?></div>

  <!-- Hidden -->
  <input type="hidden" name="content" id="content">

  <br>
  <button type="submit">💾 Save</button>

</form>

<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>

<script>
  // ===== Register ONLY Angsana =====
  const Font = Quill.import('formats/font');
  Font.whitelist = ['angsana'];
  Quill.register(Font, true);

  var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
      toolbar: '#toolbar'
    }
  });

  // ===== Force Angsana always =====
  quill.on('text-change', function () {
    quill.format('font', 'angsana');
  });

  function saveContent() {
    document.getElementById('content').value = quill.root.innerHTML;
  }
</script>

</body>
</html>
