<?php
require 'includes/init.php';
$conn = require 'includes/db.php';
!Auth::requireLogin();
require 'user_auth.php';
if (!Auth::isLoggedIn()) {
    Util::alert(" You are not login.");
    die();
} elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) {
    Util::alert("You have no authorize to access this page.");
} else {
    //Allow to do next 
}




$hospitals = Hospital::getAll($conn);

if ($hide) {
    $nbprices = Specimen::getAll($conn);
}
?>



<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-center justify-content-center p-3 mx-1">


        <div class="row align-items-center">
            <div class="col-auto">
                <select name="nb_price_hospital_select" id="nb_price_hospital_select" class="form-select" <?= ( true ) ? "" : " disabled readonly " ?> >
                    <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
                    <?php foreach ($hospitals as $hospital): ?>
                        <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option>   ?>
                        <option value="<?= htmlspecialchars($hospital['id']); ?>"  ><?= ($hospital['id'] == 0) ? "ราคามาตรฐาน" : $hospital['hospital']; ?></option>
                    <?php endforeach; ?>
                </select>          
            </div>

            <div class="col-auto">
                <select name="nb_price_type" id="nb_price_type" class="form-select" <?= ( true ) ? "" : " disabled readonly " ?> >
                    <option value="0"  >กรุณาเลือกชนิด</option>
                    <option value="1"  >สิ่งส่งตรวจ</option>
                    <option value="2"  >ย้อมพิเศษ</option>
                </select>          
            </div>

        </div>

    </div>
</div>



<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-center justify-content-center p-3 mx-1">




        <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
            <h1>Table of price by hospital</h1>
            <style>
                table,
                th,
                td {
                    /*padding: 10px;*/
                    border: 1px solid black;
                    border-collapse: collapse;
                }
            </style>
            <table class="" id="nb_price_tbl"> 
                <thead>
                    <tr>
                        <th> row_id </td>
                        <th> hospital </td>
                        <th> type </th>
                        <th> code </th>
                        <th> detail </th>
                        <th> price </th>
                        <th> comment </th>
                        <th> create date </th>
                        <th> add by </th>  
                        <th> edit by </th>  
                    </tr>
                </thead>
                <tbody id="">
                    <?php if ($hide) : ?>
                        <?php foreach ($nbprices as $p) : ?>
                            <tr>
                                <td> <?= $p['id'] ?> </td>
                                <td> <?= $p['hospital_id'] ?> </td>
                                <td> <?= $p['jobtype'] ?> </td>
                                <td> <?= $p['speciment_num'] ?> </td>
                                <td> <?= $p['specimen'] ?> </td>
                                <td> <?= $p['price'] ?> </td>
                                <td> <?= $p['comment'] ?> </td>
                                <td> <?= $p['create_date'] ?> </td>
                                <td> <?= $p['add_user_id'] ?> </td>
                                <td> <?= $p['edit_user_id'] ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>



    </div>
</div>



<?php require 'includes/footer.php'; ?>

<script src="/ajax_nb_price/nb_price.js?v0xxxxxxxxxxxx"></script>