<?php
require 'includes/init.php';

$conn = require 'includes/db.php';


?>
<?php require 'user_auth.php'; ?>
<?php
if (!Auth::isLoggedIn()) {
    echo 'You are not login.<br> คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน';
    require 'includes/footer.php';
    die();
}
if (($isCurUserClinicianCust || $isCurUserHospitalCust)) { //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้  
    echo 'You have no authorize to view this content. <br>    คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้';
    require 'includes/footer.php';
    die();
}
?>
<?php
//====== POST =======
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    
    $tps_edit = TemplateReport::getInit();

    $tps_edit[0]['user_id']=$_POST['user_id'];
    $tps_edit[0]['reporttype_id']=$_POST['rname_selected'];
    $tps_edit[0]['name']=$_POST['tname'];
    $tps_edit[0]['description'] = $_POST['tdescription'];
//    die();
    $last_insert_id = TemplateReport::createbyArray($conn, $tps_edit);
    Url::redirect("/templateReport_edit.php?id=" . $last_insert_id);
}




//====== GET =======
$tps = TemplateReport::getInit();
$rpts = ReportType::getAll($conn);
//var_dump($tps);
$isBorder = false;
?>
<?php require 'includes/header.php'; ?>


<div class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1  border border-secondary">
        <h1>เพิ่มเท็มแพล็ต</h1><hr>
        <form method="POST" id="addtemplate">
            <!--<input type="checkbox" id="tps_edit_enable" name="tps_edit_enable" value=""><b> Click check box for Edit</b>-->
            <div class="row <?= $isBorder ? "border" : "" ?>">

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                    <label for="rname_selected" class="">ชนิดรายงาน</label>
                    <select name="rname_selected" id="rname_selected" class="form-select"  >
                        <option value="0" selected>กรุณาเลือก</option>
                        <?php foreach ($rpts as $rpt): ?>
                            <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                            <option value="<?= htmlspecialchars($rpt['id']); ?>"   >
                                <?= $rpt['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>                                    
                </div>

                <div class="col-lg-auto <?= $isBorder ? "border" : "" ?>">
                    <label for="tname" class="">ชือเทมเพลต</label>
                    <input name="tname" type="text" class="form-control border" id="tname" placeholder="" size="100"  value="<?= $tps[0]['name']; ?>">
                </div>

            </div>

            <div class="row <?= $isBorder ? "border" : "" ?>">

                <label for="tdescription" class="">เทมเพลต</label>
                <textarea name="tdescription" cols="100" rows="5" class="form-control" id="tdescription" ><?= htmlspecialchars($tps[0]['description']); ?></textarea>

            </div>
            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class=" align-items-center <?= $isBorder ? "border" : "" ?>">
                <button name="save_tps_btn" id="save_tps_btn" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Add&nbsp;&nbsp;</button>
                </div>
            </div>
            <input type="hidden" id="" name="user_id" value="<?= Auth::getUser()->id; ?>">

        </form>
    </div>
</div>


<?php require 'includes/footer.php'; ?>


<script type="text/javascript">

    $(document).ready(function () {

    });
</script>

