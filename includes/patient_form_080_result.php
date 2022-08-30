<?php
$isBorder = false;
$isSetShowaddResultButton = true;
?>



<?php if (isset($presultupdates)): ?>
    <?php foreach ($presultupdates as $key => $presultupdate): ?>

        <?php
        $isReleased = false;
        if ($presultupdate['release_time'] == NULL) {
            $isSetShowaddResultButton = false;
            $isReleased = false;
        } else {
            $isSetShowaddResultButton = true;
            $isReleased = true;
        }
        ?>
        <?php $isUResultNotReleased = ($presultupdate['release_time'] == NULL); ?>
        <?php $pathoOwner2NameObj = User::getByID($conn, $presultupdate['pathologist2_id']);  ?>
        <?php
        $isShowEditBTNuResult = $isCurUserAdmin ||
                ($curstatus[0]['id'] == 12000  //current status is 12000 will can edit
                && $isCurrentPathoIsOwnerThisCase // First patho is ownder this patient id (Cur_user == First patho)
                && !$isEditModePageOn

                );
        ?>
        <?php
        $isShowSendToReviewbtn = $isCurrentPathoIsOwnerThisCase // First patho is ownder this patient id (Cur_user == First patho)
                && $presultupdate['pathologist2_id'] != 0 //If Second Patho is select 
                && $curstatus[0]['id'] == 12000; //and Status == 12000
        ?>
        <?php $isEditableUResult = $isEditModePageForFinResultDataOn && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) && !$isReleased ?>


        <?php if ($key === array_key_last($presultupdates)) : ?>
             <hr id="uresultLastSection" noshade="noshade" width="" size="4">
        <?php else: ?>
             <hr noshade="noshade" width="" size="4"> 
        <?php endif; ?>
        
        <form  id="save_u_result" name="" method="post">
            <input name="id" class="" type="text" class="" id="" style="display: none;"  value="<?= $presultupdate['id']; ?>">
            <div align=""  class="mb-3">
                <label for="result_message"><?= $presultupdate['result_type'] ?></label><br>
                <textarea name="result_message" cols="100" rows="5" class="form-control" id="rs_diagnosis" <?= $isEditableUResult ? "" : " disabled readonly " ?> ><?= $presultupdate['result_message'] ?></textarea>
            </div>

            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="pathologist_id" class="col-form-label">พยาธิแพทย์ผู้ออกผล</label>
                    <select name="pathologist_id" class="form-select" <?= $isEditableUResult ? "" : " disabled readonly " ?> >
                        <?php foreach ($userPathos as $user): ?>
                            <option value="<?= $user['uid']; ?>" <?= $presultupdate['pathologist_id'] == $user['uid'] ? "selected" : ""; ?> > 
            <?= $user['name'] . ' ' . $user['lastname'] ?><?php if ($user['uid'] != 0 && $isCurUserAdmin): ?> <?= ' (' . $user['username'] . '::' . $user['ugroup'] . ')'; ?><?php endif; ?>
                            </option>
        <?php endforeach; ?>                                     
                    </select> 
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="pathologist2_id" class="col-form-label">พยาธิแพทย์คอนเฟิร์มผล</label>
                    <select name="pathologist2_id" class="form-select" <?= $isEditableUResult ? "" : " disabled readonly " ?> >
                        <?php foreach ($userPathos as $user): ?>
                            <option value="<?= $user['uid']; ?>" <?= $presultupdate['pathologist2_id'] == $user['uid'] ? "selected" : ""; ?> >           
            <?= $user['name'] . ' ' . $user['lastname'] ?><?php if ($user['uid'] != 0 && $isCurUserAdmin): ?> <?= ' (' . $user['username'] . '::' . $user['ugroup'] . ')'; ?><?php endif; ?>
                            </option>
        <?php endforeach; ?>                                     
                    </select> 
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                    <label for="date_14000" class="form-label">รายงานผลแล้วเมื่อวันที่</label>
                    <input name="date_14000"  type="text" class="form-control border" id="date_14000"  placeholder="This Field will Auto Generate"  <?= $isEditModePageForFinResultDataOn && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) && FALSE ? "" : " disabled readonly " ?> value="<?= $presultupdate['release_time']; ?>">

                </div>
            </div>

            <div class="row  <?= $isBorder ? "border" : "" ?> ">
                <div class=" <?= $isBorder ? "border" : "" ?> ">
        <?php if (!$isReleased): //If not released show edit assigned second patho botton  ?>
                        <?php if ($isEditModePageForFinResultDataOn):  // To show Save and Discard Btn  ?>
                            <br><p align="center">
                                <button name="save_u_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                                <button name="discard_u_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Discard&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                            </p>
                            <?php else: ?>
                            <br><p align="center">

                                <?php if ($isShowEditBTNuResult)://To show edit button?>
                                    <button name="edit_u_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                                    <button name="btnmove8000" id="btnmove8000" type="submit" class="btn btn-primary">&nbsp;&nbsp;สั่งย้อมพิเศษ&nbsp;&nbsp;</button>
                                <?php endif; ?>
                                <?php if ($isShowSendToReviewbtn): ?><button name="btn2review13000" id="btn2review13000" type="" class="btn btn-primary">&nbsp;&nbsp;Send to Second Patho Review&nbsp;&nbsp;</button><?php endif; ?>
                            </p>
<?php if($curstatusid == "13000"):  ?>
                            <hr noshade="noshade" width="" size="4">
                            <h4 align="center"><b>แพทย์คนที่สองรีวิว</b><span style="color:orange;"><-ขั้นตอนปัจจุบัน</span></h4>
<?php endif; ?>
                            
                <?php
                 
                if ($isCurrentPathoIsSecondOwneThisCaseLastest // Second patho is ownder this patient id (Cur_user == Second patho)
                        && $curstatus[0]['id'] == 13000): //and CurrentStatus == 13000
                    ?>
                               
                                    <p align="center">คุณ  <?= $pathoOwner2NameObj->name_e . " " . $pathoOwner2NameObj->lastname_e. " "; ?> </p>
                                    <p align="center">คุณคือแพทย์คนที่สองช่วยดับเบิ้ลคอนเฟิร์มผลของผู้ป่วยท่านนี้ กรุณาคลิกเลือกปุ่มคอนเฟิร์ม </p>
                                    <p align="center"><button name="btnrejto12000" id="btnrejto12000" type="" class="btn btn-primary">&nbsp;&nbsp;Reject to originator&nbsp;&nbsp;</button>
                                    <button name="btnagreeto20000" id="btnagreeto20000" type="" class="btn btn-primary">&nbsp;&nbsp;Agree with result and release report&nbsp;&nbsp;</button>
                                                        </p>

                                        <?php endif; ?>

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




