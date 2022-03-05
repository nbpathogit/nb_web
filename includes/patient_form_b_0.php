<?php
//กำหนด คน ตัดเนื้อ
$isBorder = false;
?>
<hr>
<hr noshade="noshade" width="" size="5" >
<h4 align="center"><b>กำหนดทีมวินิจฉัย โดยสถายัน เอ็น แอน บี</b></h4>

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

        <fieldset>
            <legend>เลือกชนิดสิ่งส่งตรวจ</legend>
            <input type="radio" name="p_speciment_type" id="lump" value="lump" <?= $patient[0]['p_speciment_type'] == "lump" ? "checked" : ""; ?> >ชิ้นเนื้อ&nbsp;
            <input type="radio" name="p_speciment_type" id="fluid" value="fluid" <?= $patient[0]['p_speciment_type'] == "fluid" ? "checked" : ""; ?>>ของเหลว
        </fieldset>

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_2000" class="">กำหนดทีมตรวจวินิจฉัยแล้วเมื่อวันที่</label>
        <input name="date_2000" id="date_2000" class="form-control border" type="text" placeholder="This Field will Auto Generate" <?= $canEditModePage && $canEditPlaning_b_1_status && $canEditPlaning_b_1_group && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_2000']; ?>">
    </div>


</div>

