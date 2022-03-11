<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

// $paginator = new Paginator(  isset($_GET['page'])?$_GET['page']:1 ,20,Hospital::getTotal($conn));
// $hospitals = Hospital::getPage($conn,$paginator->limit,$paginator->offset);
$hospitals = Hospital::getAll($conn);

// var_dump($hospitals);
?>

<?php require 'includes/header.php'; ?>

<?php if (!Auth::isLoggedIn()) : ?>
    You are not login.
<?php else : ?>

    <table class="table table-hover table-striped text-center" id="hospital_table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">hospital</th>
                <th scope="col">address</th>
                <th scope="col">detail</th>
                <th scope="col">Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hospitals as $hospital) : ?>
                <tr>
                    <th scope="row"><?= $hospital['id']; ?></th>
                    <td><a href="hospital_detail.php?id=<?= $hospital['id']; ?>"><?= $hospital['hospital']; ?></a></td>
                    <td><?= $hospital['address']; ?></td>
                    <td><?= $hospital['hdetail']; ?></td>
                    <td>
                        <a href="hospital_edit.php?id=<?= $hospital['id']; ?>"><i class="fa-solid fa-marker fa-lg"></i></a>
                        <a class="delete" href="hospital_del.php?id=<?= $hospital['id']; ?>"><i class="fa-solid fa-trash-can fa-lg"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </thead>
    </table>


<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {


        // table data
        $('#hospital_table').DataTable({});


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


        //set active tab
        $("#hospital_main").addClass("active");
        $("#hospital").addClass("active");

    });
</script>