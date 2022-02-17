<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

// $specimens = Specimen::getAll($conn);
if (isset($_REQUEST['search']) && $_REQUEST['search'] != "") {

    // var_dump($_REQUEST);

    // $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 30, Specimen::getTotal($conn));
    // var_dump($paginator);
    $specimens = Specimen::getSearch($conn, $_REQUEST['search']);
    // var_dump($specimens);
} else {
    $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 30, Specimen::getTotal($conn));
    // var_dump($paginator);
    $specimens = Specimen::getPage($conn, $paginator->limit, $paginator->offset);
}
?>




<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    You are not login.
<?php else : ?>


    <!-- search form -->
    <form id="" name="" method="post">

        <div class="mb-4">
            <h4><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg> ค้นหาข้อมูล</h4>
        </div>

        <div class="mb-3">
            <label for="search" class="form-label">คำที่ต้องการค้นหา</label>
            <input name="search" type="text" class="form-control" id="search" value="<?= isset($_REQUEST['search']) ? $_REQUEST['search'] : ""  ?>">
        </div>

        <div class="mb-3">
            <input name="" type="submit" class="btn btn-primary" value="ค้นหา" size="50">
        </div>

    </form>

    <hr>
    <table class="table table-hover table-striped text-center">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Specimen</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($specimens as $specimen) : ?>
                <tr>
                    <th scope="row"><?= $specimen['id']; ?></td>
                    <td><?= $specimen['specimen']; ?></td>
                    <td><a href="specimen_edit.php?id=<?= $specimen['id']; ?>">Edit</a></td>
                    <td><a class="delete" href="specimen_del.php?id=<?= $specimen['id']; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </thead>
    </table>

    <?php if (isset($paginator)) require 'includes/pagination.php'; ?>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $("a.delete").on("click", function(e) {

        e.preventDefault();

        if (confirm("Are you sure?")) {

            var frm = $("<form>");
            frm.attr('method', 'post');
            frm.attr('action', $(this).attr('href'));
            frm.appendTo("body");
            frm.submit();
        }
    });
</script>