<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

$specimens = Specimen::getAll($conn);
// if (isset($_REQUEST['search']) && $_REQUEST['search'] != "") {

//     // var_dump($_REQUEST);

//     // $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 30, Specimen::getTotal($conn));
//     // var_dump($paginator);
//     $specimens = Specimen::getSearch($conn, $_REQUEST['search']);
//     // var_dump($specimens);
// } else {
//     $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 30, Specimen::getTotal($conn));
//     // var_dump($paginator);
//     $specimens = Specimen::getPage($conn, $paginator->limit, $paginator->offset);
// }
?>




<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    You are not login.
<?php else : ?>

    <table class="table table-hover table-striped text-center" id="specimen_table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Specimen</th>
                <th scope="col">Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($specimens as $specimen) : ?>
                <tr>
                    <th scope="row"><?= $specimen['id']; ?></td>
                    <td><?= $specimen['specimen']; ?></td>
                    <td>
                    <a href="specimen_edit.php?id=<?= $specimen['id']; ?>"><i class="fa-solid fa-marker fa-lg"></i></a>
                        <a class="delete" href="specimen_del.php?id=<?= $specimen['id']; ?>"><i class="fa-solid fa-trash-can fa-lg"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </thead>
    </table>

    <!--		Start Pagination -->
    <div class='pagination-container'>
        <nav>
            <ul class="pagination">
                <!--	Here the JS Function Will Add the Rows -->
            </ul>
        </nav>
    </div>
    <div class="rows_count"></div>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>



<script type="text/javascript">
    $(document).ready(function() {

        // delete
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


        // table data
        $('#specimen_table').DataTable({});


        // set active tab
        $("#specimen_main").addClass("active");
        $("#specimen").addClass("active");
    });
</script>