<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

$hospitals = Hospital::getAll($conn);

//var_dump($hospitals);
?>




<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()): ?>
    You are not login.
<?php else: ?>

<?php require 'includes/hospital_form.php'; ?>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>