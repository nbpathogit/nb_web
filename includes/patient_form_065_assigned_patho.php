<?php
//แลปเซลวิทยา แลปน้ำ 10000
$isBorder = false;

$userAuthEdit = (
        $isCurUserAdmin || $isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff
        //|| $isCurUserClinicianCust 
        //|| $isCurUserHospitalCust
        );

$curStatusAuthEdit = (
        //    $isCurStatus_1000 
        $isCurStatus_2000
        //|| $isCurStatus_3000 
        //|| $isCurStatus_6000 
        //|| $isCurStatus_10000
        //|| $isCurStatus_12000
        //|| $isCurStatus_13000
        //|| $isCurStatus_20000
        );

//  && ($userAuthEdit && $curStatusAuthEdit)
?>
<hr id="">

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="ppathologist_id" class="col-form-label">พยาธิแพทย์ผู้ออกผล</label>
        <select name="ppathologist_id" id="ppathologist_id" class="form-select" <?= $isEditModePageForInitialDataOn && ($isCurUserAdmin || ($userAuthEdit) && ($curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
<?php foreach ($userPathos as $user): ?>
    <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option>   ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['ppathologist_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                <?= $user['name'] . ' ' . $user['lastname'] ?><?php if ($user['uid'] != 0  && $isCurUserAdmin): ?> <?= ' (' . $user['username'] . '::' . $user['ugroup'] . ')'; ?><?php endif; ?>
                </option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
    </div>

</div>

