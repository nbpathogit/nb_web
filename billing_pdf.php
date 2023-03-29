<?php
require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();

$hospitals = Hospital::getAll($conn);
?>

<?php require 'user_auth.php'; ?>


<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not login.
    <?php require 'blockclose.php'; ?>
<?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust) && !$isUnderCurHospital) : ?>
    <?php require 'blockopen.php'; ?>
    You have no authorize to view this section.
    <?php require 'blockclose.php'; ?>
<?php endif; ?>

<?php if (!empty($errors)) : ?>
    <br>
    <div class="alert alert-warning" role="alert">
        <?php foreach ($errors as $error) : ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </div>
<?php endif; ?>





<?php
$hospitals = Hospital::getAll($conn);
$billings = Billing::getBillbyHospitalbyDateRange($conn, 0, "2023-02-01", "2023-03-27", 1);
//var_dump($billings);
//die();
?>
<?php require 'includes/header.php'; ?>

<?php require 'includes/opencontainer.php'; ?>
<div class="row <?= $isBorder ? "border" : "" ?>">
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="phospital_id_bill" class="">โรงพยาบาล</label>
        <select name="phospital_id_bill" id="phospital_id_bill" class="form-select" >
            <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
            <?php foreach ($hospitals as $hospital): ?>
                <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                <option value="<?= htmlspecialchars($hospital['id']); ?>" ><?= htmlspecialchars($hospital['hospital']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="phospital_id" class=""><b>Start Date:</b></label>
        <input type="text" name="startdate_billing" id="startdate_billing" class="form-control"> 
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <b> To End Date:</b> <input type="text" name="enddate_billing" id="enddate_billing" class="form-control" >
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <button name="btn_get_bill_by_range" id="btn_get_bill_by_range" type="submit" class="btn btn-primary">&nbsp;&nbsp;preview bill by date range&nbsp;&nbsp;</button>
        <button name="btn_export_bill_pdf" id="btn_export_bill_pdf" type="submit" class="btn btn-primary">&nbsp;&nbsp;export pdf&nbsp;&nbsp;</button>
    </div>
</div>
<?php require 'includes/closecontainer.php'; ?>

<?php require 'includes/opencontainer.php'; ?>


<style>
    table,
    th,
    td {
        /*padding: 10px;*/
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
<span id="billing_table_span">
    <table class="" id="billing_table" style="width:100%">
        <thead>
            <tr>
                <th scope="col">#</th> <!--0-->
                <th scope="col">เลขที่งาน</th> <!--3-->
                <th scope="col">ผู้ป่วย</th> <!--4-->
                <th scope="col">ชนิดค่าบริการ</th> <!--6-->
                <th scope="col">code</th> <!--7-->
                <th scope="col">description</th> <!--8-->
                <th scope="col">วันที่รับ</th> <!--9-->
                <th scope="col">โรงพยาบาล</th> <!--11-->
                <th scope="col">เลขที่โรงพยาบาล</th> <!--12-->
                <th scope="col">แพทย์ผู้ส่งตรวจ</th> <!--13-->
                <th scope="col">ค่าตรวจ</th> <!--15-->
                <th scope="col">comment</th> <!--16-->
            </tr>
        </thead>
        <tbody>
<!--            <tr class="">
                <td class="">40</td>
                <td><a href="patient_edit.php?id=">SN2302237</a></td>
                <td>นาย  น้อย พุ่มไม้ </td>
                <td>ตรวจธรรมดา</td>
                <td>33000</td>
                <td>ก้อนเนื้อขนาดใหญ่กว่า 5 ซ.ม.และตัดเกิน 5 blocks (38003)</td>
                <td>2023-03-04 08:25:07</td>
                <td>ยังไม่ได้เลือก</td>
                <td></td>
                <td>สิทธิโชค (นพ.) รพ.สุโขทัย</td>
                <td>400</td>
                <td>ราคามาตรฐาน</td>
            </tr>-->
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</span>

<?php require 'includes/closecontainer.php'; ?>

<?php require 'includes/footer.php'; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!--<script src="https://code.jquery.com/jquery-3.6.0.js"></script>-->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $(function () {
        $("#startdate_billing").datepicker({dateFormat: 'yy-mm-dd'});
        $("#enddate_billing").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<script src="/ajax_billing/billing.js?v0xxxxxxxxxxxxxxx"></script>