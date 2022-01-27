<?php
$isBorder = false;
?>

<h4 align="center"><b>ข้อมูลผู้ป่วย</b></h4>


<!-- Content here -->

<div class="row <?= $isBorder ? "border" : "" ?>">
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="pnum" align="right" class="">เลขที่ผู้ป่วย</label>
        <input name="pnum" type="text" id="pnum" class="form-control" placeholder=""  <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?>  value="<?= $patient[0]['pnum']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="plabnum"  align="right" class="">LAB Number</label>
        <input name="plabnum" type="text" class="form-control border" id="plabnum" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?> value="<?= $patient[0]['plabnum']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="" class="">วันที่รับ</label>
        <input name="date_1000" class="form-control border" type="text" class="" id="date_1000" placeholder="This Field will Auto Generate" <?= $modePageEditDisable || $isDisableEditPatientInfo || TRUE ? " disabled readonly " : "" ?> value="<?= $patient[0]['date_1000']; ?>">
    </div>
    
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pgender" class="">เพศ</label>
        <div class="col">
            <select name="pgender" class="form-select" id="pgender" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?>>
                <option value="กรุณาเลือก">กรุณาเลือก</option>
                <option value="ชาย" <?= ($patient[0]['pgender'] === "ชาย") ? "selected" : ""; ?> >ชาย</option>
                <option value="หญิง" <?= ($patient[0]['pgender'] === "หญิง") ? "selected" : ""; ?> >หญิง</option>
            </select>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pname" class="">ชื่อผู้ป่วย</label>
        <input name="pname" type="text" class="form-control border" id="pname" placeholder="" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?> value="<?= $patient[0]['pname']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="plastname" class="">นามสกุล</label>
        <div class="col">
            <input name="plastname" type="text" class="form-control" id="plastname" placeholder="" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?> value="<?= $patient[0]['plastname']; ?>">
        </div>
    </div>



    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pedge" class="">อายุ(ปี)</label>
        <div class="col">
            <input name="pedge" type="text" class="form-control" class="" id="pedge"  placeholder="" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?> value="<?= $patient[0]['pedge']; ?>">
        </div>
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="" class="">เลขที่โรงพยาบาล</label>
        <input name="phospital_num" type="text" class="form-control" id=""  placeholder="" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?> value="<?= $patient[0]['phospital_num']; ?>" >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="phospital_id" class="">โรงพยาบาล</label>
        <select name="phospital_id" class="form-select" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?>>
            <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
            <?php foreach ($hospitals as $hospital): ?>
                <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= ($patient[0]['phospital_id'] == ($hospital['id'])) ? "selected" : ""; ?> ><?= htmlspecialchars($hospital['hospital']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>



    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pclinician_id" class="">แพทย์ผู้ส่ง</label>

        <select name="pclinician_id" class="form-select" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?>>
            <!--<option value="กรุณาเลือก" selected>กรุณาเลือก</option>-->
            <?php foreach ($clinicians as $clinician): ?>
                <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                <option value="<?= htmlspecialchars($clinician['id']); ?>"  <?= $patient[0]['pclinician_id'] == $clinician['id'] ? "selected" : ""; ?> ><?= htmlspecialchars($clinician['name']); ?></option>
            <?php endforeach; ?>
        </select>                                    
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pspecimen_id" class="" >สิ่งส่งตรวจ</label>
        <select name="pspecimen_id" class="form-select" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?>>
            <option value="กรุณาเลือก">กรุณาเลือก</option>
            <?php foreach ($specimens as $specimen): ?>
                <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>    ?>
                <option value="<?= $specimen['id']; ?>" <?= $patient[0]['pspecimen_id'] == $specimen['id'] ? "selected" : ""; ?>   ><?= htmlspecialchars($specimen['specimen']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
        <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="" class="">ความสำคัญ</label>
        <select name="priority_id" class="form-select" <?= $modePageEditDisable || $isDisableEditPatientInfo ? " disabled readonly " : "" ?>>
            <?php foreach ($prioritys as $priority): ?>
                <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                <option value="<?= htmlspecialchars($priority['id']); ?>"  <?= $patient[0]['priority_id'] == $priority['id'] ? "selected" : ""; ?> ><?= htmlspecialchars($priority['priority']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<hr>

