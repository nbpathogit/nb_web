<?php
$isBorder = false;
?>

<h4 align="center"><b>ผลการตรวจ(เพิ่มเติม)</b></h4> 

<?php if (isset($presultupdates)): ?>
    <?php foreach ($presultupdates as $presultupdate): ?>
        <hr>
        <div align=""  class="mb-3">
            <label for="p_rsu_diagnosis"><?= $presultupdate['result_type'] ?></label><br>
            <textarea name="p_rsu_diagnosis" cols="100" rows="5" class="form-control" id="p_rs_diagnosis" <?= $modePageEditDisable || $isDisableEditResult_d_group ? " disabled readonly " : "" ?> ><?= $presultupdate['result_message'] ?></textarea>
        </div>

        <div class="row <?= $isBorder ? "border" : "" ?>">
            <div class="col-xl-6 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                <label for="ppathologist_u_id" class="col-form-label">พยาธิแพทย์ผู้ออกผลเพิ่มเติม</label>
                <select name="ppathologist_u_id" class="form-select" <?= $modePageEditDisable || $isDisableEditResult_d_group ? " disabled readonly " : "" ?> >
                    <!--<option value="">กรุณาเลือก</option>-->
                    <?php foreach ($userPathos as $user): ?>
                        <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patient[0]['ppathologist_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                            <?= htmlspecialchars($user['name']);
                            echo ' '; ?> <?= htmlspecialchars($user['lastname']); ?></option>
                     <?php endforeach; ?>                                     
                </select> 
            </div>

            <div class="col-xl-6 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                <label for="date_14000" class="form-label">รายงานผลเพิ่มเติมแล้วเมื่อวันที่</label>
                <input name="date_14000"  type="date" class="form-control" id="date_14000" <?= $modePageEditDisable || $isDisableEditResult_d_group ? " disabled readonly " : "" ?> value="<?= $patient[0]['date_14000']; ?>">

            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<?php if ($patient[0]["status_id"] == 14000): ?>
    <form  id="" name="" method="post">

        <div class="row <?= $isBorder ? "border" : "" ?>">
            <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                <label for="result_type" class="">เลือกชนิดของผลเพิ่มเติม</label>
                <select name="result_type" class="form-select" id="result_type" >
                    <option value="กรุณาเลือก">กรุณาเลือก</option>
                    <option value="ADDENDUM" >ADDENDUM</option>
                    <option value="REVISED" >REVISED</option>
                </select>
            </div>
            <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                <button name="add_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;ADD&nbsp;&nbsp;</button>
            </div>
        </div>

    </form>

<?php else: ?>

<?php endif; ?>




