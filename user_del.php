<?php

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $user = User::getByID($conn, $_GET['id']);

    if (!$user) {
        die("user not found");
    }
} else {
    die("id not supplied, user not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($user->delete($conn)) {

        Url::redirect("/user.php");
    }
}

?>
<?php require 'includes/header.php'; ?>

<h2>Delete user</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="user_detail.php?id=<?= $user->id; ?>">Cancel</a>

</form>

<?php require 'includes/footer.php'; ?>
