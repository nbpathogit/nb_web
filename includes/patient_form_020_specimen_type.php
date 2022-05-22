<?php
//เลือกชนิดชิ้นเนื้อ
$isBorder = false;

?>



<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

        <fieldset>
            <legend>เลือกชนิดสิ่งส่งตรวจ</legend>
            <input type="radio" name="p_speciment_type" id="lumptype" value="lump"   <?= $patient[0]['p_speciment_type'] == "lump"  ? "checked" : ""; ?> <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >ชิ้นเนื้อ&nbsp;
            <input type="radio" name="p_speciment_type" id="fluidtype" value="fluid" <?= $patient[0]['p_speciment_type'] == "fluid" ? "checked" : ""; ?> <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >เซลวิทยา
        </fieldset>

    </div>  

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_2000" class="">กำหนดทีมตรวจวินิจฉัยแล้วเมื่อวันที่</label>
        <input name="date_2000" id="date_2000" class="form-control border" type="text" placeholder="This Field will Auto Generate" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_2000']; ?>">
    </div>


</div>

