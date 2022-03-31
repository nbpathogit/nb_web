<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     //var_dump($_POST);


//     $patient = new Patient();
//     $patient->pnum = $_POST['pnum'];
//     $patient->plabnum = $_POST['plabnum'];
//     $patient->pname = $_POST['pname'];
//     $patient->pgender = $_POST['pgender'];
//     $patient->plastname = $_POST['plastname'];
//     $patient->pedge = $_POST['pedge'];
//     $patient->date_1000 = $_POST['date_1000'];
//     $patient->date_12_13_000 = $_POST['date_12_13_000'];
//     $patient->status = $_POST['status'];
//     $patient->priority = $_POST['priority'];
//     $patient->phospital_id = $_POST['phospital_id'];
//     $patient->phospital_num = $_POST['phospital_num'];
//     $patient->ppathologist_id = $_POST['ppathologist_id'];
//     $patient->pspecimen_id = $_POST['pspecimen_id'];
//     $patient->pclinician_id = $_POST["pclinician_id"];
//     $patient->pprice = $_POST['pprice'];
//     $patient->pspprice = $_POST['pspprice'];
//     $patient->p_rs_specimen = $_POST['p_rs_specimen'];
//     $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'];
//     $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'];
//     $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'];
//     $patient->p_rs_diagnosis = $_POST['p_rs_diagnosis'];



//     if ($patient->create($conn)) {
//         Url::redirect("/patient_detail.php?id=$patient->id");
//     } else {
//         echo '<script>alert("Add user fail. Please verify again")</script>';
//     }
// }

//$patients = Patient::getInit();

//$patientLists = Patient::getAllJoin($conn);

//Ternary Operator
// $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 10, Patient::getTotal($conn));
// $patientLists = Patient::getPage($conn, $paginator->limit, $paginator->offset);

// $patientLists = Patient::getAllJoin($conn, 0);

// $hospitals = Hospital::getAll($conn);
// $specimens = Specimen::getAll($conn);
// $clinicians = User::getAllbyClinicians($conn);
// $userPathos = User::getAllbyPathologis($conn);
// $prioritys = Priority::getAll($conn);

//var_dump($patients);

//var_dump($patientLists);
//var_dump($Specimens);
//var_dump($clinicians);
//var_dump($users);
//var_dump($userPathos);
//var_dump($userPathos);
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <?php if (!Auth::isLoggedIn()) : ?>
            You are not authorized.
        <?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="/patient_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>เพิ่มข้อมูลผู้รักษา</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">


        <table class="table table-hover" id="patient_table" style="width:100%">
            <!--<table border="1" align="center">-->
            <thead>
                <tr>
                    <th>#</th>
                    <th>เลขที่ผู้ป่วย</th>
                    <th>ชื่อผู้ป่วย</th>
                    <th>นามสกุลผู้ป่วย</th>
                    <th>โรงพยาบาล</th>
                    <th>พยาธิแพทย์</th>
                    <th>วันที่รับ</th>
                    <th>วันที่รายงาน</th>
                    <th>สถานะ</th>
                    <th>ความสำคัญ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {

        // datatable
        var table = $('#patient_table').DataTable({
            "ajax": "data/patient.php?skey=<?= $_SESSION["skey"]; ?>",
            responsive: true,
            dom: 'Plfrtip',
            searchPanes: {
                initCollapsed: true,
                // cascadePanes: true,
            },
            columnDefs: [{
                    searchPanes: {
                        show: true
                    },
                    targets: [4, 5, 6, 7, 8]
                },
                {
                    searchPanes: {
                        show: false
                    },
                    targets: [0, 1, 2, 3]
                },
                {
                    "render": function(data, type, row) {
                        // return data + ' (' + row[3] + ')';
                        return '<a href="patient_pdf.php?id=' + row[0] + '" class="btn btn-outline-success btn-sm me-1 pdf"><i class="fa-solid fa-file-pdf"></i></a><a href="patient_edit.php?id=' + row[0] + '" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i></a><a href="patient_del.php?id=' + row[0] + '" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i></a>';
                    },
                    "targets": -1
                },
                {
                    "render": function(data, type, row) {
                        // return data + ' (' + row[3] + ')';
                        return '<a href="patient_edit.php?id=' + row[0] + '">' + data + '</a>';
                    },
                    "targets": 1
                },
            ],
            "initComplete": function() {
                // add color class for priority
                var count = $('.table').children('tbody').children('tr:first-child').children('td').length;
                tablee = document.getElementById("patient_table");
                tr = tablee.getElementsByTagName("tr");
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 1; i < tr.length; i++) {
                    for (j = 0; j < count; j++) {
                        td = tr[i].getElementsByTagName("td")[j];
                        if (td.innerHTML.indexOf('ด่วน') > -1) {
                            tr[i].classList.add('table-danger');
                        } else if (td.innerHTML.indexOf('ออกผล') > -1) {
                            tr[i].classList.add('table-success');
                            break;
                        } else {
                            //DO NOTHING
                        }
                    }
                }
            }
        });


        // delete user
        $('#patient_table tbody').on('click', 'a.delete', function(e) {
            var data = table.row($(this).parents('tr')).data();

            e.preventDefault();
            if (confirm("Are you sure?")) {
                var frm = $("<form>");
                frm.attr('method', 'post');
                frm.attr('action', "patient_del.php?id=" + data[0]);
                frm.appendTo("body");
                frm.submit();
            }
        });

        // set active tab
        $("#patient").addClass("active");








    });
</script>