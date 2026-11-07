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

Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "open nb_price_8c.php", "");


$hospitals = Hospital::getAll($conn);

$serviceType = ServiceType::getAll_v2($conn);
$serviceTypeById = ServiceType::getAll($conn);

ServicePriceExcelFile::ensureTable($conn);

if ($hide) {
    $nbprices = ServicePriceList::getAll($conn);
}
?>



<?php require 'user_auth.php'; ?>
<?php require 'includes/data2DOM.php'; ?>
<?php require 'includes/header.php'; ?>


<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-center justify-content-center p-3 mx-1">


        <div class="row align-items-center">
            <div class="col-auto">
                <select name="nb_price_hospital_select_8c" id="nb_price_hospital_select_8c" class="form-select" <?= (true) ? "" : " disabled readonly " ?>>
                    <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
                    <?php foreach ($hospitals as $hospital) : ?>
                        <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option>   
                        ?>
                        <option value="<?= htmlspecialchars($hospital['id']); ?>"><?= ($hospital['id'] == 0) ? "ราคามาตรฐาน" : $hospital['hospital']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-auto">
                <select name="nb_price_type_8c" id="nb_price_type_8c" class="form-select" <?= (true) ? "" : " disabled readonly " ?>>
                -<option value="0">แสดงทุกชนิดของโรงพยาบาลนี้</option>
                <?php foreach ($serviceType as $key => $st) : ?>
                    <option value="<?= ($st['id']); ?>" order_list="<?= ($st['order_list']); ?>"   ><?= $st['service_type'] ; ?></option>
                <?php endforeach; ?>
<!--                    <option value="0">กรุณาเลือกชนิด</option>
                    <option value="1">สิ่งส่งตรวจ</option>
                    <option value="2">ย้อมพิเศษ</option>-->
                    
                    
                </select>
            </div>
        </div>

    </div>
</div>



<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-center justify-content-left p-3 mx-1">
        <h1>Price list of <span class="nb_price_hospital_txt"></span></h1>
        <div class="col-auto mb-2">
            <button name="nb_price_del_btn_8c" id="nb_price_del_btn_8c" type="button" class="btn btn-primary">&nbsp;&nbsp;delete&nbsp;&nbsp;</button>
        </div>
        <div class="col-auto ms-auto mb-2 d-flex align-items-center gap-2 flex-wrap">
            <div class="form-check mb-0 me-1 bg-white px-2 py-1 rounded border">
                <input class="form-check-input ms-0 me-1" type="checkbox" value="1" id="nb_price_replace_chk_8c" checked disabled readonly>
                <label class="form-check-label small fw-bold text-dark" for="nb_price_replace_chk_8c" title="หากติ๊กถูก จะลบรายการราคาเดิมของโรงพยาบาล/ชนิดที่เลือกก่อนนำเข้าข้อมูลจากไฟล์ Excel">
                    ทับข้อมูลเดิมเมื่ออัปโหลด
                </label>
            </div>
            <input type="file" id="nb_price_upload_input_8c" accept=".xlsx,.xls" style="display:none;">
            <button name="nb_price_upload_btn_8c" id="nb_price_upload_btn_8c" type="button" class="btn btn-warning text-dark fw-bold">
                <i class="fa-solid fa-file-arrow-up me-1"></i>Upload Excel
            </button>
            <button name="nb_price_excel_btn_8c" id="nb_price_excel_btn_8c" type="button" class="btn btn-success">
                <i class="fa-solid fa-file-excel me-1"></i>Download Excel
            </button>
        </div>


        <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">

            <style>
                table,
                th,
                td {
                    /*padding: 10px;*/
                    border: 1px solid black;
                    border-collapse: collapse;
                }
            </style>
            <table class="" id="nb_price_tbl_8c">
                <thead>
                    <tr>
                        <th style="display:none;"> row_id </th>
                        <th style="display:none;"> hospital </th>
                        <th> ลำดับ<br>(number) </th>
                        <th> โค้ด<br>(code) </th>
                        <th> รายการ<br>(detail) </th>
                        <th> ต่อหน่วย<br>(unit) </th>
                        <th> ราคา<br>(price) </th>
                        <th> คอมเม้นท์<br>(comment) </th>
                        <th> ไอดีชนิด<br>(type) </th>
                        <th> ชนิดของรายการ<br>(type Description) </th>
                        <th style="display:none;"> create date </th>
                        <th> add<br>(by) </th>
                        <th> edit<br>(by) </th>
                    </tr>
                </thead>
                <tbody id="">
                    <?php if ($hide) : ?>
                        <?php foreach ($nbprices as $p) : ?>
                            <tr>
                                <td style="display:none;"> <?= $p['id'] ?> </td>
                                <td style="display:none;"> <?= $p['hospital_id'] ?> </td>
                                <td> <?= $p['number'] ?> </td>
                                <td> <?= $p['speciment_num'] ?> </td>
                                <td> <?= $p['specimen'] ?> </td>
                                <td> <?= $p['unit_count'] ?> </td>
                                <td> <?= $p['price'] ?> </td>
                                <td> <?= $p['comment'] ?> </td>
                                <td> <?= $p['jobtype'] ?> </td>
                                <td> <?= $p['service_des'] ?> </td>
                                <td style="display:none;"> <?= $p['create_date'] ?> </td>
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


<div class="container-fluid pt-4 px-4" style="display:none;">
    <div class="row bg-info rounded align-items-left justify-content-left p-3 mx-1">
        <h1>Add price of type <span class="nb_price_type_txt"></span> to hospital<span class="nb_price_hospital_txt"></span> to system.</h1>
        <div class="col-auto">
            <button name="nb_price_add_btn_8c" id="nb_price_add_btn_8c" type="" class="btn btn-primary">&nbsp;&nbsp;Add&nbsp;&nbsp;</button>
        </div>
        <label for="result_message"><b>Paste data from excel</b></label>
        <p>ลำดับ(number) -&gt; โค้ด(code) -&gt; รายการ(detail) -&gt; ต่อหน่วย(unit) -&gt; ราคา(price) -&gt; คอมเม้นท์(comment) -&gt; ไอดีชนิด(type) -&gt; ชนิดของรายการ(type Description)</p>
        <textarea name="nb_price_add_txt_area_8c" cols="100" rows="5" class="form-control" id="nb_price_add_txt_area_8c"></textarea>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-center justify-content-left p-3 mx-1">
        <h4 class="mb-2"><i class="fa-solid fa-circle-info me-2"></i>คำอธิบายและข้อกำหนดสำหรับการเตรียมข้อมูลในไฟล์ Excel</h4>
        <div class="bg-white rounded p-3 mb-3 border border-secondary text-dark">
            <p class="mb-2 fw-bold">ข้อจำกัดและเงื่อนไขการตรวจสอบข้อมูลแต่ละคอลัมน์ก่อนนำเข้าสู่ระบบ:</p>
            <ul class="mb-0">
                <li><b>คอลัมน์ที่ 1 — <code>ลำดับ(number)</code> (ไอดีลำดับ):</b> ต้องเป็น <b>ตัวเลขเท่านั้น</b> และห้ามเว้นว่าง</li>
                <li><b>คอลัมน์ที่ 2 — <code>โค้ด(code)</code>:</b> ต้องเป็น <b>ตัวเลขเท่านั้น</b> ห้ามเว้นว่าง และห้ามมีตัวอักษรหรืออักขระพิเศษ</li>
                <li><b>คอลัมน์ที่ 3 — <code>รายการ(detail)</code>:</b> <b>ต้องมีข้อมูล</b> ห้ามเว้นว่าง</li>
                <li><b>คอลัมน์ที่ 4 — <code>ต่อหน่วย(unit)</code>:</b> ระบุหน่วยนับ (ถ้าไม่มีสามารถเว้นว่างได้)</li>
                <li><b>คอลัมน์ที่ 5 — <code>ราคา(price)</code>:</b> ต้องเป็น <b>ตัวเลขเท่านั้น</b> และห้ามเว้นว่าง</li>
                <li><b>คอลัมน์ที่ 6 — <code>คอมเม้นท์(comment)</code>:</b> หมายเหตุเพิ่มเติม (ถ้าไม่มีสามารถเว้นว่างได้)</li>
                <li><b>คอลัมน์ที่ 7 — <code>ไอดีชนิด(type)</code>:</b> ต้องเป็น <b>ตัวเลขเท่านั้น</b> และ <b>ต้องเป็นไอดีที่มีอยู่ในตาราง <code>service_type</code> ด้านล่างนี้เท่านั้น</b></li>
                <li><b>คอลัมน์ที่ 8 — <code>ชนิดของรายการ(type Description)</code>:</b> ให้อ้างอิงชื่อชนิดบริการจากคอลัมน์ <code>service_type</code> ในตารางด้านล่าง</li>
            </ul>
        </div>
        <p class="mb-2 fw-bold text-dark">
            ตารางอ้างอิงสำหรับคอลัมน์ <u>ไอดีชนิด(type)</u> และ <u>ชนิดของรายการ(type Description)</u> (ดึงข้อมูลจากตาราง <code>service_type</code> คอลัมน์ <code>id</code> และ <code>service_type</code>):
        </p>
        <div class="bg-nb bg-blue-a rounded p-3 mx-1 border border-secondary table-responsive">
            <table class="" id="nb_price_service_type_ref_tbl_8c">
                <thead>
                    <tr>
                        <th class="px-3 py-1 text-center"> ไอดีชนิด(type)<br><small>(id)</small> </th>
                        <th class="px-3 py-1"> ชนิดของรายการ(type Description)<br><small>(service_type)</small> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($serviceTypeById as $stRef) : ?>
                        <tr>
                            <td class="px-3 py-1 text-center fw-bold"><?= htmlspecialchars($stRef['id']); ?></td>
                            <td class="px-3 py-1"><?= htmlspecialchars($stRef['service_type']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-center justify-content-left p-3 mx-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
            <h3 class="mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>ประวัติไฟล์ Excel ที่อัปโหลด (Uploaded Excel File History)</h3>
            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                <label for="nb_price_history_filter_8c" class="fw-bold mb-0">แสดงประวัติ:</label>
                <select id="nb_price_history_filter_8c" class="form-select form-select-sm" style="width:auto;">
                    <option value="-1" selected>ทุกโรงพยาบาล (ทั้งหมด)</option>
                    <option value="selected">เฉพาะโรงพยาบาลที่เลือกด้านบน</option>
                </select>
            </div>
        </div>
        <div class="bg-nb bg-blue-a rounded p-3 mx-1 border border-secondary table-responsive">
            <table class="w-100" id="nb_price_excel_history_tbl_8c">
                <thead>
                    <tr>
                        <th> ลำดับ </th>
                        <th> โรงพยาบาล </th>
                        <th> ชื่อไฟล์ (ตั้งตาม รพ. และวันที่อัปโหลด) </th>
                        <th> จำนวนรายการ </th>
                        <th> ผู้อัปโหลด </th>
                        <th> วันที่อัปโหลด </th>
                        <th> ดาวน์โหลด </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">กำลังโหลดข้อมูล...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <h3 style="text-align:center;">
            <a target="_blank" href="file/ตัวอย่างค่าตรวจ Update  3 มี.ค. 67 .xlsx" download> Link For Reference Excel Sheet </a>
        </h3>
    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_nb_price_8c/nb_price_8c.js?v10x"></script>
<script>
    $("#manage_table").addClass("active");
    $("#price_tab_8c").addClass("active");
    $("#manage_table").addClass("show");
    $(".manage_table_dropdown").addClass("show");
</script>