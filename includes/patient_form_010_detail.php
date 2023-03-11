<?php
$isBorder = false;


$userAuthEdit = (
        $isCurUserAdmin 
    || $isCurUserPatho 
    || $isCurUserPathoAssis 
    || $isCurUserLabOfficerNB 
    || $isCurUserAdminStaff 
    //|| $isCurUserClinicianCust 
    //|| $isCurUserHospitalCust
        );

$curStatusAuthEdit = (
        $isCurStatus_1000 
    || $isCurStatus_2000 
    || $isCurStatus_3000 
    || $isCurStatus_6000 
    || $isCurStatus_10000
    || $isCurStatus_12000
    || $isCurStatus_13000
    || $isCurStatus_20000
        );
?>




<!-- Content here -->


<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="" class="">รับเข้าเมื่อวันที่</label>
        <input name="date_1000" class="form-control border" type="text" class="" id="date_1000" placeholder="This Field will Auto Generate" <?= $isEditModePageOn && ($userAuthEdit && $curStatusAuthEdit) && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_1000']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="pnum" align="right" class="">เลขที่ผู้ป่วย</label>
        <input name="pnum" type="text" id="pnum" class="form-control" readonly placeholder=""  <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  value="<?= $patient[0]['pnum']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="plabnum"  align="right" class="">LAB Number</label>
        <input name="plabnum" type="text" class="form-control border" id="plabnum" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && !$isAddPage && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> value="<?= $patient[0]['plabnum']; ?>">
    </div>




    
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="ppre_name" class="">คำนำหน้าชื่อ</label>
        <input name="ppre_name" type="text" class="form-control border" id="ppre_name" placeholder="" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  value="<?= $patient[0]['ppre_name']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pname" class="">ชื่อผู้ป่วย</label>
        <input name="pname" type="text" class="form-control border" id="pname" placeholder="" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> value="<?= $patient[0]['pname']; ?>">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="plastname" class="">นามสกุล</label>
        <div class="col">
            <input name="plastname" type="text" class="form-control" id="plastname" placeholder="" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn  && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> value="<?= $patient[0]['plastname']; ?>">
        </div>
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pgender" class="">เพศ</label>
        <div class="col">
            <select name="pgender" class="form-select" id="pgender" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && !$isAddPage && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>>
                <option value="กรุณาเลือก">กรุณาเลือก</option>
                <option value="Male" <?= ($patient[0]['pgender'] === "ชาย") ? "selected" : ""; ?> >Male</option> <!-- To be remove later when all database convert to Male/Female -->
                <option value="Female" <?= ($patient[0]['pgender'] === "หญิง") ? "selected" : ""; ?> >Female</option> <!-- To be remove later when all database convert to Male/Female -->
                <option value="Male" <?= ($patient[0]['pgender'] === "Male") ? "selected" : ""; ?> >Male</option>
                <option value="Female" <?= ($patient[0]['pgender'] === "Female") ? "selected" : ""; ?> >Female</option>
            </select>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pedge" class="">อายุ(ปี)</label>
        <div class="col">
            <input name="pedge" type="text" class="form-control" class="" id="pedge"  placeholder="" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && !$isAddPage && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> value="<?= $patient[0]['pedge']; ?>">
        </div>
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="" class="">เลขที่โรงพยาบาล</label>
        <input name="phospital_num" type="text" class="form-control" id=""  placeholder="" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && !$isAddPage && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> value="<?= $patient[0]['phospital_num']; ?>" >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="phospital_id" class="">โรงพยาบาล</label>
        <select name="phospital_id" id="phospital_id" class="form-select" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && !$isAddPage &&  ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
            <?php foreach ($hospitals as $hospital): ?>
                <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= ($patient[0]['phospital_id'] == ($hospital['id'])) ? "selected" : ""; ?> ><?= htmlspecialchars($hospital['hospital']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>



    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pclinician_id" class="">แพทย์ผู้ส่ง</label>

        <select name="pclinician_id" id="pclinician_id" class="form-select" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && !$isAddPage && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  >
            <!--<option value="กรุณาเลือก" selected>กรุณาเลือก</option>-->
            <?php foreach ($clinicians as $user): ?>
                <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>"  <?= $patient[0]['pclinician_id'] == $user['uid'] ? "selected" : ""; ?> >
                    <?=$user['name'] . ' ' . $user['lastname']?><?php if($user['uid']!=0 && $isCurUserAdmin):?> <?=' (' . $user['username'] . '::' . $user['ugroup'] . ')';  ?><?php endif; ?>
                </option>
            <?php endforeach; ?>
        </select>                                    
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="" class="">ความสำคัญ</label>
        <select name="priority_id" class="form-select" <?= $isEditModePageOn && $isEditModePageForPatientInfoDataOn && !$isAddPage && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?>  >
            <?php foreach ($prioritys as $priority): ?>
                <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                <option value="<?= htmlspecialchars($priority['id']); ?>"  <?= $patient[0]['priority_id'] == $priority['id'] ? "selected" : ""; ?> ><?= htmlspecialchars($priority['priority']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label class="form-label" for="status">สถานะ</label>
        <!--$patient[0]['status_id']-->
        <select name="status_id" class="form-select" <?= (false || $isCurUserAdmin) &&  $isEditModePageOn && $isEditModePageForPatientInfoDataOn  ? "" : " disabled readonly " ?>>
            <?php foreach ($statusLists as $status): ?>
                <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                <option value="<?= htmlspecialchars($status['id']); ?>"  <?= $patient[0]['status_id'] == $status['id'] ? "selected" : ""; ?> ><?= htmlspecialchars($status['des']); ?></option>
            <?php endforeach; ?>
        </select>     
    </div>
    
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="pspecimen_id" class="" >สิ่งส่งตรวจ(To be remove)</label>
        <select name="pspecimen_id" id="pspecimen_id" class="form-select" disabled readonly >
<!--            <option value="กรุณาเลือก">กรุณาเลือก</option>-->
            <?php foreach ($specimens as $specimen): ?>
                <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>    ?>
                <option value="<?= $specimen['id']; ?>" <?= $patient[0]['pspecimen_id'] == $specimen['id'] ? "selected" : ""; ?>   ><?= htmlspecialchars($specimen['specimen']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

        <label for="date_20000" class="form-label">รายงานผลแล้วเมื่อวันที่</label>
        <input name="date_20000" type="text" class="form-control border" id="date_20000" placeholder="This Field will Auto Generate" <?= $isEditModePageForFinResultDataOn && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_20000']; ?>">

    </div>

</div>


