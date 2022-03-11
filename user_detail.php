<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $user = User::getAll($conn,$_GET['id']);
} else {
    $user = null;
}

//var_dump($user);

?>

<?php require 'includes/header.php'; ?>

<div><p>

<?php
    if (isset($_GET['result'])) {
        if($_GET['result'] == 1) : ?>
            <div class="alert alert-success" role="alert">
            USER Added/Edit Successful
         </div>
        <?php endif; 
    }
?>
<p></div> <br>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>Key</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>id</td>
            <td><div align=""><?= $user[0]['uid']; ?></div></td>
        </tr>
        <tr>
            <td>name</td>
            <td><div align=""><?= $user[0]['name']; ?></div></td>
        </tr>
        <tr>
            <td>lastname</td>
            <td><div align=""><?= $user[0]['lastname']; ?></div></td>
        </tr>
        <tr>
            <td>รายละเอียดเพิ่มเติม</td>
            <td><div align=""><?= $user[0]['udetail']; ?></div></td>
        </tr>
        <tr>
            <td>เบอร์โทรศัพท์</td>
            <td><div align=""><?= $user[0]['umobile']; ?></div></td>
        </tr>
                <tr>
            <td>e-mail</td>
            <td><div align=""><?= $user[0]['uemail']; ?></div></td>
        </tr>
        <tr>
            <td>username</td>
            <td><div align=""><?= $user[0]['username']; ?></div></td>
        </tr>
        <tr>
            <td>password</td>
            <td><div align=""><?= $user[0]['password']; ?></div></td>
        </tr>
        <tr>
            <td>hospital</td>
            <td><div align=""><?= $user[0]['hospital']; ?></div></td>
        </tr>
        <tr>
            <td>ugroup</td>
            <td><div align=""><?= $user[0]['ugroup']; ?></div></td>
        </tr>
        <tr>
            <td>detail</td>
            <td><div align=""><?= $user[0]['udetail']; ?></div></td>
        </tr>
        <!--
        <tr>
            <td></td>
            <td><div align=""></div></td>
        </tr>
        <tr>
            <td></td>
            <td><div align=""></div></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        -->
    </tbody>
</table>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    // set active tab
    $( "#user_main" ).addClass( "active" );
    $( "#user_add" ).addClass( "active" );
</script>