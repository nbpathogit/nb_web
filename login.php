<?php

require 'includes/init.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = require 'includes/db.php';

    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {
        Auth::login($conn, $_POST['username']);
        Url::redirect('/');
    } else {
        $error = "login incorrect";
    }
}

?>

<?php require 'includes/header.php'; ?>

<h2>Login</h2>

<?php if (!empty($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>
<div class="container g-4 m-3">
    <form method="post">
        <div class="my-3 col-sm">
            <label for="username" class="form-label">Username</label>
            <input name="username" id="username" class="form-control"></input>
        </div>
        <div class="my-3 col-sm">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control"></input>
        </div>
        <div class="my-3">
        <button type="submit" class="btn btn-primary">Log in</button><div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>