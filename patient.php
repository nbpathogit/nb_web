<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //var_dump($_POST);


    $patient = new Patient();
    $patient->pnum = $_POST['pnum'];
    $patient->plabnum = $_POST['plabnum'];
    $patient->pname = $_POST['pname'];
    $patient->pgender = $_POST['pgender'];
    $patient->plastname = $_POST['plastname'];
    $patient->pedge = $_POST['pedge'];
    $patient->date_1000 = $_POST['date_1000'];
    $patient->date_12_13_000 = $_POST['date_12_13_000'];
    $patient->status = $_POST['status'];
    $patient->priority = $_POST['priority'];
    $patient->phospital_id = $_POST['phospital_id'];
    $patient->phospital_num = $_POST['phospital_num'];
    $patient->ppathologist_id = $_POST['ppathologist_id'];
    $patient->pspecimen_id = $_POST['pspecimen_id'];
    $patient->pclinician_id = $_POST["pclinician_id"];
    $patient->pprice = $_POST['pprice'];
    $patient->pspprice = $_POST['pspprice'];
    $patient->p_rs_specimen = $_POST['p_rs_specimen'];
    $patient->p_rs_clinical_diag = $_POST['p_rs_clinical_diag'];
    $patient->p_rs_gross_desc = $_POST['p_rs_gross_desc'];
    $patient->p_rs_microscopic_desc = $_POST['p_rs_microscopic_desc'];
    $patient->p_rs_diagnosis = $_POST['p_rs_diagnosis'];



    if ($patient->create($conn)) {
        Url::redirect("/patient_detail.php?id=$patient->id");
    } else {
        echo '<script>alert("Add user fail. Please verify again")</script>';
    }
}

//$patients = Patient::getInit();

//$patientLists = Patient::getAllJoin($conn);

//Ternary Operator
// $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 10, Patient::getTotal($conn));
// $patientLists = Patient::getPage($conn, $paginator->limit, $paginator->offset);

$patientLists = Patient::getAllJoin($conn, 0);

$hospitals = Hospital::getAll($conn);
$specimens = Specimen::getAll($conn);
$clinicians = User::getAllbyClinicians($conn);
$userPathos = User::getAllbyPathologis($conn);
$prioritys = Priority::getAll($conn);

//var_dump($patients);

//var_dump($patientLists);
//var_dump($Specimens);
//var_dump($clinicians);
//var_dump($users);
//var_dump($userPathos);
//var_dump($userPathos);
?>

<?php require 'includes/header.php'; ?>


<?php if (!Auth::isLoggedIn()) : ?>
    You are not authorized.
<?php else : ?>


    <p>&nbsp;</p>
    <hr>

    <?php // require 'includes/patient_search.php'; 
    ?>
    <!-- <p>&nbsp;</p>
    <hr> -->

    <table class="table table-hover table-striped" id="patient_table" style="width:100%">
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
                <!--<th>ราย<br>ละเอียด</th>-->
                <th>จัดการ</th>
                <!-- <th>แก้ไข/รายละเอียด</th>
                <th>PDF</th>
                <th>ลบ</th> -->
            </tr>
        </thead>
        <tbody>

            <?php foreach ($patientLists as $patient) : ?>
                <tr>
                    <td>
                        <?= $patient['pid']; ?>
                    </td>
                    <td>
                        <a href="patient_edit.php?id=<?= $patient['pid']; ?>"><?= $patient['pnum']; ?></a>
                    </td>
                    <td>
                        <?= $patient['pname']; ?>
                    </td>
                    <td>
                        <?= $patient['plastname']; ?>
                    </td>
                    <td>
                        <?= $patient['hospital']; ?>
                    </td>
                    <td>
                        <?= $patient['name']; ?>
                    </td>
                    <td>
                        <?= $patient['date_1000']; ?>
                    </td>
                    <td>
                        <?= $patient['date_20000']; ?>
                    </td>
                    <td>
                        <?= $patient['des']; ?>
                    </td>
                    <td>
                        <?= $patient['priority']; ?>
                    </td>
                    <!--<td><a href="patient_detail.php?id=<?= $patient['pid']; ?>">Detail</a></td>-->
                    <td>
                        <div>
                            <a target="_blank" href="patient_pdf.php?id=<?= $patient['pid']; ?>"><i class="fa-solid fa-file-pdf fa-lg"></i></a>
                            <a class="delete" href="patient_del.php?id=<?= $patient['pid']; ?>"><i class="fa-solid fa-trash-can fa-lg"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <?php //require 'includes/pagination.php'; 
    ?>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {

        // datatable
        $('#patient_table').DataTable({
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
                }
            ]
        });

        // delete user
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

        // set active tab
        $("#patient_main").addClass("active");
        $("#patient").addClass("active");

    });
</script>