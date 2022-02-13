<?php
$isBorder = false;
$addResultButton = false;
?>

<h4 align="center"><b>ผลการตรวจ(เพิ่มเติม)</b></h4> 

<?php if (isset($presultupdates)): ?>
    <?php foreach ($presultupdates as $presultupdate): ?>
        <hr>
        <form  id="save_u_result" name="" method="post">
            <input name="id" class="" type="text" class="" id="" style="display: none;"  value="<?= $presultupdate['id']; ?>">
            <div align=""  class="mb-3">
                <label for="p_rsu_diagnosis"><?= $presultupdate['result_type'] ?></label><br>
                <textarea name="p_rsu_diagnosis" cols="100" rows="5" class="form-control" id="p_rs_diagnosis" <?= $canEditModePage && $canEditResult_d_group && $canEditResult_d_status ? "" : " disabled readonly " ?> ><?= $presultupdate['result_message'] ?></textarea>
            </div>

            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-6 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                    <label for="ppathologist_u_id" class="col-form-label">พยาธิแพทย์ผู้ออกผลเพิ่มเติม</label>
                    <select name="ppathologist_u_id" class="form-select" <?= $canEditModePage && $canEditResult_d_group && $canEditResult_d_status ? "" : " disabled readonly " ?> >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userPathos as $user): ?>
                            <option value="<?= $user['uid']; ?>" <?= $presultupdate['pathologist_id'] == $user['uid'] ? "selected" : ""; ?> > 
                                <?= $user['name']; ?>
                                &nbsp;
                                <?= $user['lastname']; ?>
                            </option>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                    <label for="date_14000" class="form-label">รายงานผลแล้วเมื่อวันที่</label>
                    <input name="date_14000"  type="text" class="form-control border" id="date_14000"  placeholder="This Field will Auto Generate"  <?= $canEditModePage && $canEditResult_d_group && $canEditResult_d_status && TRUE ? "" : " disabled readonly " ?> value="<?= $presultupdate['release_time']; ?>">
                    <?php
                    if ($presultupdate['release_time'] == NULL) {
                        $addResultButton = false;
                    } else {
                        $addResultButton = true;
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <p align="center"><button name="save_result" type="submit" class="btn btn-primary">&nbsp;SAVE&nbsp;&nbsp;</button></p>
            </div>
        </form>
    <?php endforeach; ?>

<?php else: ?>
    <?php $addResultButton = true; ?>  
<?php endif; ?>


<?php if ($patient[0]["status_id"] == 14000): ?>
    <?php if ($addResultButton): ?>
        <hr>
        <form  id="add_u_result" name="" method="post">
            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                    <label for="result_type" class="">เลือกชนิดของผลเพิ่มเติม</label>
                    <select name="result_type" class="form-select" id="result_type" >
                        <option value="0">ยังไม่ได้เลือก</option>
                        <option value="ADDENDUM" >ADDENDUM</option>
                        <option value="REVISED" >REVISED</option>
                    </select>
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                    <button name="save_result" type="submit" class="btn btn-primary">c;&nbsp;ADD&nbsp;&nbsp;</button>
                </div>
            </div>
        </form>
    <?php endif; ?>
<?php else: ?>

<?php endif; ?>




