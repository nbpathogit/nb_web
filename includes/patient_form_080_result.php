<?php
$isBorder = false;
$isSetShowaddResultButton = true;
?>

<h4 align="center"><b>ผลการตรวจ</b></h4> 

<?php if (isset($presultupdates)): ?>
    <?php foreach ($presultupdates as $presultupdate): ?>


        <?php $isUResultNotReleased = ($presultupdate['release_time'] == NULL); ?>

        <hr noshade="noshade" width="" size="3">
        <form  id="save_u_result" name="" method="post">
            <input name="id" class="" type="text" class="" id="" style="display: none;"  value="<?= $presultupdate['id']; ?>">
            <div align=""  class="mb-3">
                <label for="result_message"><?= $presultupdate['result_type'] ?></label><br>
                <textarea name="result_message" cols="100" rows="5" class="form-control" id="rs_diagnosis" <?= $canEditModePage2 && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) ? "" : " disabled readonly " ?> ><?= $presultupdate['result_message'] ?></textarea>
            </div>

            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                    <label for="pathologist_id" class="col-form-label">พยาธิแพทย์ผู้ออกผล</label>
                    <select name="pathologist_id" class="form-select" <?= $canEditModePage2 && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) ? "" : " disabled readonly " ?> >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userPathos as $user): ?>
                            <option value="<?= $user['uid']; ?>" <?= $presultupdate['pathologist_id'] == $user['uid'] ? "selected" : ""; ?> > 
                                <?= $user['name'] . ' ' . $user['lastname'] ?><?php if ($user['uid'] != 0): ?> <?= ' (' . $user['username'] . '::' . $user['ugroup'] . ')'; ?><?php endif; ?>
                            </option>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                    <label for="pathologist2_id" class="col-form-label">พยาธิแพทย์คอนเฟิร์มผล</label>
                    <select name="pathologist2_id" class="form-select" <?= $canEditModePage2 && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) ? "" : " disabled readonly " ?> >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userPathos as $user): ?>
                            <option value="<?= $user['uid']; ?>" <?= $presultupdate['pathologist2_id'] == $user['uid'] ? "selected" : ""; ?> >           
                                <?= $user['name'] . ' ' . $user['lastname'] ?><?php if ($user['uid'] != 0): ?> <?= ' (' . $user['username'] . '::' . $user['ugroup'] . ')'; ?><?php endif; ?>
                            </option>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                    <label for="date_14000" class="form-label">รายงานผลแล้วเมื่อวันที่</label>
                    <input name="date_14000"  type="text" class="form-control border" id="date_14000"  placeholder="This Field will Auto Generate"  <?= $canEditModePage2 && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) && FALSE ? "" : " disabled readonly " ?> value="<?= $presultupdate['release_time']; ?>">
                    <?php
                    if ($presultupdate['release_time'] == NULL) {
                        $isSetShowaddResultButton = false;
                    } else {
                        $isSetShowaddResultButton = true;
                    }
                    ?>
                </div>
            </div>

            <div class="row  <?= $isBorder ? "border" : "" ?> ">
                <div class=" <?= $isBorder ? "border" : "" ?> ">
                    <?php if ($presultupdate['release_time'] == NULL): //If released not show any edit assigned  botton ?>
                        <?php
                        // To show Save and Discard Btn
                        if ($canEditModePage2):
                            ?>
                            <br><p align="center"><button name="save_u_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;SAVE&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;                   
                                <a  class="btn btn-primary" href="patient_edit.php?id=<?= $patient[0]['id']; ?>" >Discard</a></p>
                        <?php else: ?>
                            <br><p align="center">

                                <?php
                                //To show edit button
                                if ($isCurUserAdmin ||
                                        ($curstatus[0]['id'] == 12000  //current status is 12000 will can edit
                                        && $isCurrentPathoIsOwnerThisCase // First patho is ownder this patient id (Cur_user == First patho)
                                        && !$isEditModePageOn)
                                ):
                                    ?>
                                    <button name="edit_u_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                                <?php endif; ?>

                                <?php
                                // to show botton move to 13000 for second patho to review
                                if ($isCurrentPathoIsOwnerThisCase // First patho is ownder this patient id (Cur_user == First patho)
                                        && $presultupdate['pathologist2_id'] != 0 //If Second Patho is select 
                                        && $curstatus[0]['id'] == 12000): //and Status == 12000
                                    ?>
                                    <button name="move13000" id="btnmove13000" type="" class="btn btn-primary">&nbsp;&nbsp;Send to Second Patho Review&nbsp;&nbsp;</button>
                                <?php endif; ?>

                                <?php
                                if ($isCurrentPathoIsSecondOwneThisCaseLastest // Second patho is ownder this patient id (Cur_user == Second patho)
                                        && $curstatus[0]['id'] == 13000): //and CurrentStatus == 13000
                                    ?>
                                    <button name="move12000" id="btnmove12000" type="" class="btn btn-primary">&nbsp;&nbsp;Reject to originator&nbsp;&nbsp;</button>
                                    <button name="move20000" id="btnmove20000" type="" class="btn btn-primary">&nbsp;&nbsp;Agree with result and release report&nbsp;&nbsp;</button>
                            <?php endif; ?>

                            </p>
            <?php endif; ?>
        <?php endif; ?>
                </div>
            </div>

        </form>
    <?php endforeach; ?>

<?php else: ?>
    <?php $isSetShowaddResultButton = true; ?>  
<?php endif; ?>

        
<?php if ($patient[0]["status_id"] == 12000): ?>
    <?php if ($isSetShowaddResultButton && $isCurrentPathoIsOwnerThisCase): ?>
        <hr>
        <form  id="add_u_result" name="" method="post">
            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                    <label for="result_type" class="">เลือกชนิดของการรายงานผล</label>
                    <select name="result_type" class="form-select" id="result_type" >
                        <option value="0">ยังไม่ได้เลือก</option>
                        <option value="Preliminary" >Preliminary</option>
                        <option value="Pathological Diagnosis" >Pathological Diagnosis</option>
                        <option value="Addendum" >Addendum</option>
                        <option value="Revised" >Revised</option>
                    </select>
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                    <button name="add_u_result" type="submit" class="btn btn-primary">&nbsp;ADD&nbsp;&nbsp;</button>
                </div>
            </div>
            <input type="hidden" name="pathologist_id"  value="<?= $patient[0]['ppathologist_id'] ?>" >
        </form>
    <?php endif; ?>
<?php else: ?>

<?php endif; ?>




