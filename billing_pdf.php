<?php
require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();
$isBorder = FALSE;
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
$billings = ServiceBilling::getBillbyHospitalbyDateRange($conn, 0, "2023-02-01", "2023-03-27", 1);
//var_dump($billings);
//die();
?>
<?php require 'includes/header.php'; ?>

<h1 align="center">สร้างใบแจ้งหนี้</h1>

<?php require 'includes/opencontainer.php'; ?>
<div class="row <?= $isBorder ? "border" : "" ?>">
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="phospital_id_bill" class="">โรงพยาบาล</label>
        <select name="phospital_id_bill" id="phospital_id_bill" class="form-select">
            <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
            <?php foreach ($hospitals as $hospital) : ?>
                <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> 
                ?>
                <option value="<?= htmlspecialchars($hospital['id']); ?>"><?= htmlspecialchars($hospital['hospital']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="phospital_id" class=""><b>Start Date:</b></label>
        <input type="text" name="startdate_billing" id="startdate_billing" class="form-control">
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <b> To End Date:</b> <input type="text" name="enddate_billing" id="enddate_billing" class="form-control">
    </div>

    <div class=" <?= $isBorder ? "border" : "" ?>">
        <br>
        <button name="btn_get_bill_by_range" id="btn_get_bill_by_range" type="submit" class="btn btn-primary">&nbsp;&nbsp;1) preview bill by date range&nbsp;&nbsp;</button>

    </div>
</div>
<?php require 'includes/closecontainer.php'; ?>



<?php require 'includes/opencontainer.php'; ?>
invoice_sub_number : <input name="bill_invoice_sub_number" id="bill_invoice_sub_number" size="100" placeholder="ใส่เล่มที่ใบแจ้งหนี้"><br>
invoice_number : <input name="bill_hospital_invoice_number" id="bill_hospital_invoice_number" size="100" placeholder="ใส่เลขที่ใบแจ้งหนี้"><br>


Today date(Thai format) : <input name="bill_todaydate_thai" id="bill_todaydate_thai" size="100"><br>
start date(Thai format) : <input name="bill_startdate_thai" id="bill_startdate_thai" size="100"><br>
end date(Thai format) : <input name="bill_enddate_thai" id="bill_enddate_thai" size="100"><br>
label hospital name : <input name="bill_hospitalname" id="bill_hospitalname" size="100"><br>
label hospital tax id : <input name="bill_hospital_taxid" id="bill_hospital_taxid" size="100"><br>
label hospital address : <input name="bill_hospital_address" id="bill_hospital_address" size="100"><br>

<!--<span id="bill_hospital_by_service_price">-->


<!--</span>-->


<span id="bill_hospital_by_service_price">
    <!--    <table
        ><thead>
            <tr><th>sid</th><th>service_type</th><th>bcost_count</th><th>bcost_sum</th></tr></thead><tbody><tr>
                <td><input type="text" value="1"></td>
                <td><input type="text" value="ตรวจชิ้นเนื้อศัลย์พยาธิ"></td>
                <td><input type="text" value="29"></td>
                <td><input type="text" value="11600"></td>
            </tr>
            <tr>
                <td><input type="text" value="2"></td>
                <td><input type="text" value="ตรวจพิเศษ"></td>
                <td><input type="text" value="2"></td>
                <td><input type="text" value="800"></td>
            </tr>
        </tbody>
    </table>-->
</span>




Net Price : <input name="bill_hospital_net_price" id="bill_hospital_net_price" size="100"><br>
Net Price spell : <input name="bill_hospital_net_price_spell" id="bill_hospital_net_price_spell" size="100"><br>
Net item list count : <input name="bill_count_all_list" id="bill_count_all_list" size="100"><br>


Name of manager : <input name="bill_manager" id="bill_manager" size="100"><br>

<br>
<button name="btn_bill_preview_web" id="btn_bill_preview_web" type="submit" class="btn btn-primary">&nbsp;&nbsp;2) Preview on web page.&nbsp;&nbsp;</button>
<button name="btn_export_bill_pdf_layout" id="btn_export_bill_pdf_layout" type="submit" class="btn btn-primary">&nbsp;&nbsp; 3) preview pdf with layout&nbsp;&nbsp;</button>
<button name="btn_export_bill_pdf" id="btn_export_bill_pdf" type="submit" class="btn btn-primary">&nbsp;&nbsp;4) Generate official pdf&nbsp;&nbsp;</button>

<?php require 'includes/closecontainer.php'; ?>



<?php require 'includes/opencontainer.php'; ?>
<?php
$str1 = file_get_contents('pdf_invoice/billingletter1.php');
if (true) {
    $str1 = str_replace("border: 1px solid green;", "border: none;", $str1);
}
echo '<h1 align="center"> 1. หนังสือนำและใบแจ้งหนี้ (1)</h1><hr>';
echo '<span id="bill_page1">';
echo $str1;
echo '</span>';
?>
<?php require 'includes/closecontainer.php'; ?>




<?php require 'includes/opencontainer.php'; ?>
<?php
$str1 = file_get_contents('pdf_invoice/billingletterinvoice.php');
if (false) {
    $str1 = str_replace("border: 1px solid green;", "border: none;", $str1);
}
$str1 = str_replace("ต้นฉบับ_สำเนา", "ต้นฉบับ", $str1);
echo '<h1 align="center">1. หนังสือนำและใบแจ้งหนี้ "ต้นฉบับ"</h1><hr>';
echo '<span id="bill_page2">';
echo $str1;
echo '</span>';
?>
<?php require 'includes/closecontainer.php'; ?>



<?php require 'includes/opencontainer.php'; ?>
<?php
$str1 = file_get_contents('pdf_invoice/billingletterinvoice.php');
if (false) {
    $str1 = str_replace("border: 1px solid green;", "border: none;", $str1);
}
$str1 = str_replace("ต้นฉบับ_สำเนา", "สำเนา", $str1);
echo '<h1 align="center">1. หนังสือนำและใบแจ้งหนี้ "สำเนา"</h1><hr>';
echo '<span id="bill_page3">';
echo $str1;
echo '</span>';
?>
<?php require 'includes/closecontainer.php'; ?>


<!--2. List รายการตรวจเรียงตาม surgical number (SN, IN, CN, FN, DN, PN, LN) แต่ละ รพ ในช่วงนั้น--> 
<?php require 'includes/opencontainer.php'; ?>
<?php
$str1 = file_get_contents('pdf_invoice/billinngListAll_g2.php');

echo '<h1 align="center">2. List รายการตรวจเรียงตาม surgical number (SN, IN, CN, FN, DN, PN, LN) แต่ละ รพ ในช่วงนั้น </h1><hr>';
echo '<span id="bill_page4">';
echo $str1;
echo '</span>';
?>
<?php require 'includes/closecontainer.php'; ?>

<!--//3. สรุปจำนวนแต่ละรายการสิ่งส่งตรวจและย้อมพิเศษในช่วงนั้น (นับตามรหัสกรมบัญชีกลาง)-->
<?php require 'includes/opencontainer.php'; ?>
<?php
$str1 = file_get_contents('pdf_invoice/billinngListAll_g3.php');

echo '<h1 align="center">3. สรุปจำนวนแต่ละรายการสิ่งส่งตรวจและย้อมพิเศษในช่วงนั้น (นับตามรหัสกรมบัญชีกลาง)</h1><hr>';
echo '<span id="bill_page5">';
echo $str1;
echo '</span>';
?>
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
    <p style="text-align:center;font-size: 14pt;">ตารางรายการแสดงเพื่อการอ้างอิง<br>
        ตั้งแต่วันที่ <span class="bill_startdate_thai">X</span> ถึง <span class="bill_enddate_thai">X</span></p>
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
<span id="tempform"></span>

<?php require 'includes/footer.php'; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!--<script src="https://code.jquery.com/jquery-3.6.0.js"></script>-->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $("#manage_bill").addClass("active");
    $("#billing_pdf_tab").addClass("active");
    $(function() {
        $("#startdate_billing").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $("#enddate_billing").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
<script src="<?= Url::getSubFolder1() ?>/ajax_billing/billing.js?v7"></script>