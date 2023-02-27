<?php
$isBorder = false;
$isSetShowaddResultButton = true;
?>
<?php if ($isCurrentPathoIsOwnerThisCase) : ?>
    <p align="center" > คุณคือผู้ออกผลของผู้ป่วยท่านนี้  </p>
<?php else : ?>
    <p align="center" style="color: firebrick">คุณไม่ไช่ผู้ออกผลของผู้ป่วยท่านนี้ คุณสามารถดูข้อมูลได้เท่านั้น</p>
<?php endif; ?>

<span id="result_list_display">
<?php if (isset($presultupdates)) : //start if (isset($presultupdates)): ?>
    <?php foreach ($presultupdates as $key => $presultupdate) : ?>

    <?php
    $isCurResultReleased = false;
    if($presultupdate['release_time']==NULL){
        $isCurResultReleased = false;
    }else{
        $isCurResultReleased = true;
    }
    $is_show_edit_btn = !$isCurResultReleased && $isCurrentPathoIsOwnerThisCase;
    $is_show_save_btn = false;
    $is_show_template_btn = !$isCurResultReleased && $isCurrentPathoIsOwnerThisCase;
    ?>
    <div class="mb-3">
        <label for="result_message"><b><?= $presultupdate['result_type'] ?></b></label>   <?= $isCurResultReleased ?  "ออกผลแล้วเมื่อ[".$presultupdate['release_time']."] ไม่สามารถแก้ไขได้" : "ยังไม่ออกผล"  ?>
        <a class="btn btn-outline-primary btn-sm me-1 " id="edit_result_<?= $presultupdate['id']?>" onclick="edit_txt_rs(<?= $presultupdate['id']?>);" title="Edit" <?= ($is_show_edit_btn)? '':'style="display: none;"'; ?> ><i class="fa-solid fa-marker"></i>Edit</a>
        <a class="btn btn-outline-primary btn-sm me-1 " id="save_result_<?= $presultupdate['id']?>" onclick="save_txt_rs(<?= $presultupdate['id']?>);" title="Save"<?= ($is_show_save_btn)? '':'style="display: none;"'; ?> ><i class="fa-solid fa-floppy-disk"></i>Save</a>
        <!--<a class="btn btn-outline-primary btn-sm me-1 " id="btn_template_<?= $presultupdate['id']?>" onclick="alert('Under construction. \nThe feature will avalable soon.');" title="Template" <?= ($is_show_template_btn)? '':'style="display: none;"'; ?> ><i class="fa-solid fa-marker"></i>Template</a>-->
        <textarea name="txt_rs_<?= $presultupdate['id']?>" cols="100" rows="5" class="form-control" id="txt_rs_<?= $presultupdate['id']?>" readonly ><?= $presultupdate['result_message'] ?> </textarea>
    </div>

    <?php endforeach; ?>
<?php endif;  ?>
</span>





<!--</form>-->

<hr>
<div class="row <?= $isBorder ? "border" : "" ?>">
     <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
         <b>แพทย์ผู้ออกผล:</b>
         <span id="owner_job5a" style="font-size:20px">
             <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
         </span>  
     </div>
     <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <b>พยาธิแพทย์คอนเฟิร์มผล:</b>
        <span id="owner_job6a" style="font-size:20px">
            <span class="badge rounded-pill bg-primary" id="">Data not update</span>
        </span>  
        <?php
          
            $is_show_add_btn = $isCurrentPathoIsOwnerThisCase;
            $is_show_refresh_btn = $isCurrentPathoIsOwnerThisCase;
            $is_show_detail_btn = $isCurrentPathoIsOwnerThisCase;
        ?>
         <a class="btn btn-outline-primary btn-sm me-1 " id="add_job6" <?= ($is_show_add_btn)? '':'style="display: none;"'; ?>   data-bs-toggle="modal"  data-bs-target="#add_modal_job6" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i></a>
         <a class="btn btn-outline-primary btn-sm me-1 "  id="refresh_job6" <?= ($is_show_add_btn)? '':'style="display: none;"'; ?> title="Refresh" ><i class="fa-solid fa-rotate-right"></i></a>
         <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#owner_tbl_job6" <?= ($is_show_add_btn)? '':'style="display: none;"'; ?> title="View/Detail" ><i class="fa-solid fa-table"></i></a>

         <?php //var_dump(Url::currentURL()); ?>
         <?php //var_dump($_SERVER['DOCUMENT_ROOT']); ?>
         
         <?php require 'patient_from_080_job6__tbl_modal.php';      ?>
         <?php require 'patient_from_080_job6__select_modal.php';      ?>
     </div>


    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

        <label for="date_14000" class="form-label">รายงานผลแล้วเมื่อวันที่</label>
        <input name="date_14000" type="text" class="form-control border" id="date_14000" placeholder="This Field will Auto Generate" <?= $isEditModePageForFinResultDataOn && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) && FALSE ? "" : " disabled readonly " ?> value="<?= $presultupdate['release_time']; ?>">

    </div>

 </div>

<p align="center">
<?php $isShow_btnmove12000 = $curstatusid != "12000" && $isCurrentPathoIsOwnerThisCase; ?> 
<button name="btnmove12000" id="btnmove12000" type="submit" class="btn btn-primary" <?= $isShow_btnmove12000 ? '' : 'disabled'; ?>>&nbsp;&nbsp;Start Diagnostic&nbsp;&nbsp;</button>
<?php $isShow_newReportSection = $curstatusid == "12000" && $isCurrentPathoIsOwnerThisCase; ?> 
<button class="btn btn-primary"  data-bs-toggle="modal"  data-bs-target="#add_result_type_modal" <?= ($isShow_newReportSection) ? '' : 'disabled'; ?> title="เพิ่มส่วนของการรีพอร์ท">&nbsp;&nbsp;Add New Report Section.&nbsp;&nbsp;</button>

    


    


    <!--<button name="save_u_result" type="submit" class="btn btn-primary" style="display: none;">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>-->
    <!--<button name="discard_u_result" type="submit" class="btn btn-primary" style="display: none;">&nbsp;&nbsp;Discard&nbsp;&nbsp;</button>-->
    <!--<button name="edit_u_result" type="submit" class="btn btn-primary" style="display: none;">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>-->
      <?php
        $isShowSendToReviewbtn = $isCurrentPathoIsOwnerThisCase  // First patho is ownder this patient id (Cur_user == First patho)
            && $isSecondPathoDefined                             //If Second Patho is select 
            && $curstatus[0]['id'] == 12000;                     //and Status == 12000
        $isShow_btn_release = $isCurrentPathoIsOwnerThisCase  // First patho is ownder this patient id (Cur_user == First patho)
            && $curstatus[0]['id'] == 12000;                     //and Status == 12000
        ?>
    <button name="btn2review13000" id="btn2review13000"  <?= ($isShowSendToReviewbtn)? '':'disabled'; ?>  class="btn btn-primary">&nbsp;&nbsp;Request Second Pathologist Review&nbsp;&nbsp;</button>
    <button name="btn_release" id="btn_release" type="submit" class="btn btn-primary" <?= ($isShow_btn_release)? '':'disabled'; ?> >&nbsp;&nbsp;Release Report&nbsp;&nbsp;</button>
      
</p>

    <?php require 'patient_form_080_job6__select_rs_modal.php'; ?>








<?php if ($curstatusid == "13000") :  ?><?php endif; ?>
<hr>
<h4 id="confirm_result_section" align="center"><b>แพทย์คนที่สองรีวิว</b><?php if($curstatusid == "13000"): ?><span style="color:orange;"><-ขั้นตอนปัจจุบัน<?php endif; ?></h4>
<p align="center"><span id="owner_job6b" style="font-size:20px"><span class="badge rounded-pill bg-primary" id="">Data not update</span></span> </p>
     
<?php if ($isCurrentPathoIsSecondOwneThisCase) : ?>
    <p align="center" > คุณคือแพทย์คนที่สองช่วยดับเบิ้ลคอนเฟิร์มผลของผู้ป่วยท่านนี้ กรุณาคลิกเลือกปุ่มคอนเฟิร์ม  </p>
<?php else : ?>
    <p align="center" style="color: firebrick">คุณไม่ไช่แพทย์คนที่สองช่วยดับเบิ้ลคอนเฟิร์มผลของผู้ป่วยท่านนี้</p>
<?php endif; ?>
    
<?php

    $is_enable_reject_btn = $isCurrentPathoIsSecondOwneThisCase && $curstatus[0]['id'] == 13000 && $patient[0]['second_patho_review'] == 1; 
    $is_enable_approve_btn = $isCurrentPathoIsSecondOwneThisCase && $curstatus[0]['id'] == 13000&& $patient[0]['second_patho_review'] == 1; 

?>
<p align="center">
    <button name="btnrejto12000" id="btnrejto12000" type="" class="btn btn-primary" <?= ($is_enable_reject_btn)? '':'disabled'; ?> >&nbsp;&nbsp;Reject to originator&nbsp;&nbsp;</button>
    <button name="btnagreeto20000" id="btnagreeto20000" type="" class="btn btn-primary" <?= ($is_enable_approve_btn)? '':'disabled'; ?>>&nbsp;&nbsp;Agree with result and release report&nbsp;&nbsp;</button>
</p>

    
    
    
    



<hr>
<div align="center">
    <!--<div class="row <?= $isBorder ? "border" : "" ?>">-->
        <!--<div class="col">-->
            <input class="form-check-input border-danger" type="checkbox" value="1" id="critical_report" name="critical_report"   <?= $patient[0]['iscritical'] ? "checked" :"" ?>>
            <label class="form-check-label text-danger" for="critical_report" ><B> Critical Report </B></label>
        <!--</div>-->
    <!--</div>-->
</div>
          















<?php if ($hide) : ?>



<?php if (isset($presultupdates)) : //start if (isset($presultupdates)): 
?>
    <?php foreach ($presultupdates as $key => $presultupdate) : ?>
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
        
        <?php $isEditableUResult = $isEditModePageForFinResultDataOn && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) && !$isReleased ?>

        <?php if ($key === array_key_last($presultupdates)) : 
        ?>
        <hr id="uresultLastSection" noshade="noshade" width="" size="4">
        <?php else: 
        ?>
        <hr noshade="noshade" width="" size="4"> 
        <?php endif; 
        ?>

        <form id="save_u_result" name="" method="post">
            <input name="id" class="" type="text" class="" id="" style="display: none;" value="<?= $presultupdate['id']; ?>">

            <?php if ($key == 0) : ?>
                <div class="container my-2">
                    <div class="row <?= $isBorder ? "border" : "" ?>">
                        <div class="col">
                            <input class="form-check-input border-danger" type="checkbox" value="1" id="critical_report" name="critical_report" <?= $isEditableUResult ? "" : "disabled readonly" ?>  <?= $patient[0]['iscritical'] ? "checked" :"" ?>>
                            <label class="form-check-label text-danger" for="critical_report"<?= $isEditableUResult ? "" : "disabled readonly" ?>><B> Critical Report </B></label>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="result_message"><?= $presultupdate['result_type'] ?></label><br>
                <textarea name="result_message" cols="100" rows="5" class="form-control" id="rs_diagnosis" <?= $isEditableUResult ? "" : " disabled readonly " ?>><?= $presultupdate['result_message'] ?></textarea>
            </div>

            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <b>แพทย์ผู้ออกผล:</b>
                    <span id="owner_job5a" style="font-size:20px">
                        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
                    </span>  
                </div>
                
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="pathologist2_id" class="col-form-label">พยาธิแพทย์คอนเฟิร์มผล</label>
                    <select name="pathologist2_id" class="form-select" <?= $isEditableUResult ? "" : " disabled readonly " ?>>
                        <?php foreach ($userPathos as $user) : ?>
                            <option value="<?= $user['uid']; ?>" <?= $presultupdate['pathologist2_id'] == $user['uid'] ? "selected" : ""; ?>>
                                <?= $user['name'] . ' ' . $user['lastname'] ?><?php if ($user['uid'] != 0 && $isCurUserAdmin) : ?> <?= ' (' . $user['username'] . '::' . $user['ugroup'] . ')'; ?><?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <b>พยาธิแพทย์คอนเฟิร์มผล:</b>
                    <span id="owner_job6a" style="font-size:20px">
                        <span class="badge rounded-pill bg-primary" id="">Data not update</span>
                    </span>  
                    <a class="btn btn-outline-primary btn-sm me-1 " id="add_job6" <?= ($isSecondPathoDefined)? 'style="display: none;"':''; ?>   data-bs-toggle="modal"  data-bs-target="#add_modal_job6" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i></a>
                    <a class="btn btn-outline-primary btn-sm me-1 "  id="refresh_job6" title="Refresh" ><i class="fa-solid fa-rotate-right"></i></a>
                    <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#owner_tbl_job6" title="View/Detail" ><i class="fa-solid fa-table"></i></a>

                    <?php require 'patient_from_080_job6__tbl_modal.php';      ?>
                    <?php require 'patient_from_080_job6__select_modal.php';      ?>


                </div>
              
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">

                    <label for="date_14000" class="form-label">รายงานผลแล้วเมื่อวันที่</label>
                    <input name="date_14000" type="text" class="form-control border" id="date_14000" placeholder="This Field will Auto Generate" <?= $isEditModePageForFinResultDataOn && ($isCurrentPathoIsOwnerThisCase || $isCurUserAdmin) && FALSE ? "" : " disabled readonly " ?> value="<?= $presultupdate['release_time']; ?>">

                </div>



            </div>

            <div class="row  <?= $isBorder ? "border" : "" ?> ">
                <div class=" <?= $isBorder ? "border" : "" ?> ">
                    <?php if (!$isReleased) : //If not released show edit assigned second patho botton  
                    ?>
                        <?php if ($isEditModePageForFinResultDataOn) :  // To show Save and Discard Btn  
                        ?>
                            <br>
                            <p align="center">
                                <button name="save_u_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                                <button name="discard_u_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Discard&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
                            </p>
                        <?php else : //Else if ($isEditModePageForFinResultDataOn): To show Edit , sent to special slide , send to second patho, Release report Btn 
                        ?>
                            <br>
                            <p align="center">

                                <?php if ($isShowEditBTNuResult) : //To show edit button
                                ?>
                                    <button name="edit_u_result" type="submit" class="btn btn-primary">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>

                                <?php endif; ?>
                                    
                                    
                                <?php
                                $isShowSendToReviewbtn = $isCurrentPathoIsOwnerThisCase // First patho is ownder this patient id (Cur_user == First patho)
                                    && $isSecondPathoDefined //If Second Patho is select 
                                    && $curstatus[0]['id'] == 12000; //and Status == 12000
                                ?>
                               
                                <button name="btn2review13000" id="btn2review13000"  <?= ($isShowSendToReviewbtn)? '':'style="display: none;"'; ?>  class="btn btn-primary">&nbsp;&nbsp;Send to Second Pathologist Review&nbsp;&nbsp;</button>
                                <button name="btn_release" id="btn_release" type="submit" class="btn btn-primary">&nbsp;&nbsp;ออกผล&nbsp;&nbsp;</button>
                            </p>


                            <?php //ส่วนของแพทย์คนที่สอง รีวิว ?>
                            <?php if ($curstatusid == "13000") :  ?>
                                <hr noshade="noshade" width="" size="4">
                                <h4 align="center"><b>แพทย์คนที่สองรีวิว</b><?php if(false): ?><span style="color:orange;"><-ขั้นตอนปัจจุบัน< /span><?php endif; ?></h4>
                            <?php endif; ?>

                            <?php
                            if (
                                $isCurrentPathoIsSecondOwneThisCase // Second patho is ownder this patient id (Cur_user == Second patho)
                                && $curstatus[0]['id'] == 13000
                            ) : //and CurrentStatus == 13000
                            ?>
                                <p align="center">คุณ <?= $pathoOwner2NameObj->name_e . " " . $pathoOwner2NameObj->lastname_e . " "; ?> </p>
                                <p align="center">คุณคือแพทย์คนที่สองช่วยดับเบิ้ลคอนเฟิร์มผลของผู้ป่วยท่านนี้ กรุณาคลิกเลือกปุ่มคอนเฟิร์ม </p>
                                <p align="center"><button name="btnrejto12000" id="btnrejto12000" type="" class="btn btn-primary"  >&nbsp;&nbsp;Reject to originator&nbsp;&nbsp;</button>
                                    <button name="btnagreeto20000" id="btnagreeto20000" type="" class="btn btn-primary">&nbsp;&nbsp;Agree with result and release report&nbsp;&nbsp;</button>
                                </p>

                            <?php endif; ?>
                        <?php endif; //End if ($isEditModePageForFinResultDataOn):  
                        ?>
                    <?php endif; //End of if (!$isReleased):  
                    ?>
                </div>
            </div>
        </form>
    <?php endforeach; ?>

<?php else :  //else if (isset($presultupdates)): 
?>
    <?php $isSetShowaddResultButton = true; ?>
<?php endif; //end if (isset($presultupdates)): 
?>



<?php if ($patient[0]["status_id"] == 12000) : ?>
    <?php if ($isSetShowaddResultButton && $isCurrentPathoIsOwnerThisCase) : ?>
        <hr>
        <form id="add_u_result" name="" method="post">
            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                    <label for="result_type" class="">เลือกชนิดของการรายงานผล</label>
                    <select name="result_type" class="form-select" id="result_type">
                        <option value="0">ยังไม่ได้เลือก</option>
                        <option value="Preliminary">Provisional Diagnosis</option>
                        <option value="Pathological Diagnosis">Pathological Diagnosis</option>
                        <option value="Addendum">Addendum</option>
                        <option value="Revised">Revised</option>
                    </select>
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                    <button name="add_u_result" type="submit" class="btn btn-primary">&nbsp;ADD&nbsp;&nbsp;</button>
                </div>
            </div>
            <input type="hidden" name="pathologist_id" value="<?= $patient[0]['ppathologist_id'] ?>">
        </form>
    <?php endif; ?>
<?php else : ?>

<?php endif; ?>


        
        
        
        
<?php endif; //////////////////?>