<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

// $paginator = new Paginator(  isset($_GET['page'])?$_GET['page']:1 ,20,Hospital::getTotal($conn));
// $hospitals = Hospital::getPage($conn,$paginator->limit,$paginator->offset);
// $hospitals = Hospital::getAll($conn);

// var_dump($hospitals);
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.
        <?php else : ?>


            <div class="d-flex align-items-center justify-content-between">
                <a href="/hospital_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-house-chimney-medical me-2"></i>เพิ่มโรงพยาบาล</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">


        <table class="table table-hover table-striped text-center" id="hospital_table" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">hospital</th>
                    <th scope="col">address</th>
                    <th scope="col">detail</th>
                    <th scope="col">Manage</th>
                </tr>
            </thead>
        </table>

    <?php endif; ?>

    </div>
</div>

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
        var table = $('#hospital_table').DataTable({
            "ajax": "data/hospital.php?skey=<?= $_SESSION["skey"]; ?>",
            responsive: true,
            columnDefs: [
                {
                    "render": function(data, type, row) {
                        // return data + ' (' + row[3] + ')';
                        return '<a href="hospital_detail.php?id=' + row[0] + '" class="btn btn-outline-success btn-sm me-1 detail"><i class="fa-solid fa-money-check"></i></a><a href="hospital_edit.php?id=' + row[0] + '" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i></a><a href="hospital_del.php?id=' + row[0] + '" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i></a>';
                    },
                    "targets": -1
                },
                {
                    "render": function(data, type, row) {
                        // return data + ' (' + row[3] + ')';
                        return '<a href="hospital_detail.php?id=' + row[0] + '">' + data + '</a>';
                    },
                    "targets": 1
                },
            ],});

            // delete hospital
        $('#hospital_table tbody').on('click', 'a.delete', function(e) {
            var data = table.row($(this).parents('tr')).data();

            e.preventDefault();
            if (confirm("Are you sure?")) {
                var frm = $("<form>");
                frm.attr('method', 'post');
                frm.attr('action', "hospital_del.php?id=" + data[0]);
                frm.appendTo("body");
                frm.submit();
            }
        });

        //set active tab
        $("#hospital").addClass("active");

    });
</script>