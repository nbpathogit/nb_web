<?php
$isBorder = false;
$isSetShowaddResultButton = true;

$isPersonOwnThisCase = ($isCurrentCytologistIsOwnerThisCase || $isCurrentPathoIsOwnerThisCase);
?>

<?php if ($isPersonOwnThisCase) : ?>
    <p align="center" > <span id="owner_job7a" class="owner_job7" style="font-size:20px"><span class="badge rounded-pill bg-secondary" id="">NA</span> </span> คุณคือผู้ออกผลของผู้ป่วยท่านนี้  </p>
<?php else : ?>
    <p align="center" style="color: firebrick">คุณไม่ไช่ผู้ออกผลของผู้ป่วยท่านนี้ คุณสามารถดูข้อมูลได้เท่านั้น</p>
<?php endif; ?>

<span id="result_list_display">
<?php if (isset($presultupdates)) : //start if (isset($presultupdates)): ?>
    <?php $count_presultupdates = count($presultupdates) ?>
    <?php //==START FOR EACH LOOP of $presultupdates ========================================================================================================================= ?>
    <?php foreach ($presultupdates as $key => $presultupdate) : ?>
        <?php //echo '<br>'.'$presultupdate[\'id\']::'.$presultupdate['id'].'<br>'; ?>
        <?php
        $isCurResultReleased = false;
        if($presultupdate['release_time']==NULL){
            $isCurResultReleased = false;
        }else{
            $isCurResultReleased = true;
        }
        $is_show_edit_btn = !$isCurResultReleased && $isPersonOwnThisCase;
        $is_show_delete_btn = $is_show_edit_btn && ($presultupdate['group_type']==2);
        $is_show_save_btn = false;
        $is_show_template_btn = !$isCurResultReleased && $isPersonOwnThisCase;
        ?>
        <hr style="height:1px;border-width:0;color:black;background-color:black;">
        <div class="row <?= $isBorder ? "border" : "" ?>">

            <div class="col-6 <?= $isBorder ? "border" : "" ?>">
                <label for="result_message"><b><?= $presultupdate['result_type'] ?></b></label>   <?= $isCurResultReleased ? "ออกผลแล้วเมื่อ[" . $presultupdate['release_time'] . "] ไม่สามารถแก้ไขได้" : "ยังไม่ออกผล" ?>
                <a class="btn btn-outline-primary btn-sm me-1 " id="edit_result_<?= $presultupdate['id'] ?>" onclick="edit_txt_rs(<?= $presultupdate['id'] ?>);" title="Edit" <?= ($is_show_edit_btn) ? '' : 'style="display: none;"'; ?> ><i class="fa-solid fa-marker"></i>Edit</a>
                <a class="btn btn-outline-primary btn-sm me-1 " id="save_result_<?= $presultupdate['id'] ?>" onclick="save_txt_rs(<?= $presultupdate['id'] ?>);" title="Save"<?= ($is_show_save_btn) ? '' : 'style="display: none;"'; ?> ><i class="fa-solid fa-floppy-disk"></i>Save</a>
                <a class="btn btn-outline-primary btn-sm me-1 " id="btn_template_<?= $presultupdate['id'] ?>" onclick="add_tp_2_txt_rs(<?= $presultupdate['id'] ?>,<?= $presultupdate['result_type_id'] ?>,<?= Auth::getUserId(); ?>);" title="Template" <?= ($is_show_template_btn) ? '' : 'style="display: none;"'; ?> ><i class="fa-solid fa-marker"></i>Template</a>
                <a class="btn btn-outline-primary btn-sm me-1 " id="delete_result_<?= $presultupdate['id'] ?>" onclick="delete_txt_rs(<?= $presultupdate['id'] ?>);" title="Delete" <?= ($is_show_delete_btn) ? '' : 'style="display: none;"'; ?> ><i class="fa-solid fa-marker"></i>Delete</a>
            
            </div>

            <?php // if last result id of 3 ?>
            <?php if( $presultupdate['group_type']== 3 ): ?>
            <div class="col-6 <?= $isBorder ? "border" : "" ?>">
                <b>พยาธิแพทย์คอนเฟิร์มผล:</b>
                <span id="owner_job6_<?= $presultupdate['id']?>" class="owner_job6_<?= $presultupdate['id']?>" style="font-size:20px">
                    <!--<span class="badge rounded-pill bg-primary" id="">Data not update</span>-->
                </span> 
                <?php if(!$isCurResultReleased && ($count_presultupdates == $key + 1) && true): // ================ Checking for the lastest entry ================================================== ?>
                    <?php

                        $is_show_add_btn = $isPersonOwnThisCase;
                        $is_show_refresh_btn = $isPersonOwnThisCase;
                        $is_show_detail_btn = $isPersonOwnThisCase;
                    ?>
                    <a class="btn btn-outline-primary btn-sm me-1 " onclick="add_job6(<?= $presultupdate['id']?>)" id="add_job6" <?= ($is_show_add_btn)? '':'style="display: none;"'; ?>   data-bs-toggle="modal"  data-bs-target="#add_modal_job6" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i></a>
                    <a class="btn btn-outline-primary btn-sm me-1 " onclick="refresh_job6(<?= $patient[0]['id']?>,<?= $presultupdate['id']?>)"  id="refresh_job6" <?= ($is_show_add_btn)? '':'style="display: none;"'; ?> title="Refresh" ><i class="fa-solid fa-rotate-right"></i></a>
                    <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#owner_tbl_job6" <?= ($is_show_add_btn)? '':'style="display: none;"'; ?> title="View/Detail" ><i class="fa-solid fa-table"></i></a>

                     <?php //var_dump(Url::currentURL()); ?>
                     <?php //var_dump($_SERVER['DOCUMENT_ROOT']); ?>

                     <?php require 'patient_from_080_job6__tbl_modal.php';      ?>
                     <?php require 'patient_from_080_job6__select_modal.php';      ?>
                     
                 <?php endif;?>
            </div>
            <?php endif; ?>

            <textarea name="txt_rs_<?= $presultupdate['id']?>" cols="100" rows="5" class="form-control" id="txt_rs_<?= $presultupdate['id']?>" readonly ><?= $presultupdate['result_message'] ?> </textarea>

        </div>

        <?php // Second patho review section ?>
        <?php if(!$isCurResultReleased && ($count_presultupdates == $key + 1) && $presultupdate['group_type']== 3): ?>

            <?php if ($curstatusid == "13000") :  ?><?php endif; ?>
            <hr>
            <h4 id="confirm_result_section" align="center"><b>แพทย์คนที่สองรีวิว</b><?php if($curstatusid == "13000"): ?><span style="color:orange;"><-ขั้นตอนปัจจุบัน<?php endif; ?></h4>
            <p align="center">
                <span id="owner_job6_<?= $presultupdate['id']?>" class="owner_job6_<?= $presultupdate['id']?>" style="font-size:20px">
                <span class="badge rounded-pill bg-primary" id="">Data not update</span>
                </span>  
            </p>

            <?php if ($isCurrentPathoIsSecondOwneThisCaseForPN) : ?>
                <p align="center" > คุณคือแพทย์คนที่สองช่วยดับเบิ้ลคอนเฟิร์มผลของผู้ป่วยท่านนี้ กรุณาคลิกเลือกปุ่มคอนเฟิร์ม  </p>
            <?php else : ?>
                <p align="center" style="color: firebrick">คุณไม่ไช่แพทย์คนที่สองช่วยดับเบิ้ลคอนเฟิร์มผลของผู้ป่วยท่านนี้</p>
            <?php endif; ?>

            <?php

                $is_enable_reject_btn = $isCurrentPathoIsSecondOwneThisCaseForPN && $curstatus[0]['id'] == 13000 && $patient[0]['second_patho_review'] == 1; 
                $is_enable_approve_btn = $isCurrentPathoIsSecondOwneThisCaseForPN && $curstatus[0]['id'] == 13000&& $patient[0]['second_patho_review'] == 1; 

            ?>
            <p align="center">
                <button name="btnrejto12000" id="btnrejto12000" type="" class="btn btn-primary" <?= ($is_enable_reject_btn)? '':'disabled'; ?> >&nbsp;&nbsp;Reject to originator&nbsp;&nbsp;</button>
                <button name="btnagreeto20000" id="btnagreeto20000" type="" class="btn btn-primary" <?= ($is_enable_approve_btn)? '':'disabled'; ?>>&nbsp;&nbsp;Agree with result and release report&nbsp;&nbsp;</button>
            </p>            
        <?php endif; ?>
    
    <?php endforeach; ?>
    <?php //==END FOR EACH LOOP of $presultupdates ========================================================================================================================= ?>
<?php endif;  ?>
</span>





<!--</form>-->
<span id="confirm_result_section_bottom"></span>
<hr>
<div class="row <?= $isBorder ? "border" : "" ?>">
     <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
         <b>นักเซลด์วิทยาผู้ออกผล:</b>
         <span id="owner_job7a" class="owner_job7" style="font-size:20px">
             <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
         </span>  
     </div>
     <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
         <b>แพทย์ผู้ออกผล:</b>
         <span id="owner_job5a" class="owner_job5" style="font-size:20px">
             <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
         </span>  
     </div>

    



    
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <!--<div class="col">-->
            <?php $isEnable_critical_report = $curstatusid == "12000" && $isPersonOwnThisCase; ?> 
            <?php if(!$isCurUserCust):  ?>
            <input class="form-check-input border-danger" type="checkbox" value="1" id="critical_report" name="critical_report" <?= ($isEnable_critical_report) ? '' : 'disabled'; ?>   <?= $patient[0]['iscritical'] ? "checked" :"" ?>>
            <label class="form-check-label text-danger" for="critical_report" ><B> Critical Report </B></label>
            <?php endif; ?>
        <!--</div>-->

    </div>
    <!--<button name="testbtn" id="testbtn" type="" class="btn btn-primary" >&nbsp;&nbsp;Test Button&nbsp;&nbsp;</button>-->


 </div>

<p align="center">
<?php if(!$isCurUserCust):  ?>


<?php $isShow_btnmove12000 = $curstatusid != "12000" && ($isPersonOwnThisCase || $isCurrentPathoIsOwnerThisCase); ?> 
<button name="btnmove12000" id="btnmove12000" type="submit" class="btn btn-primary" <?= $isShow_btnmove12000 ? '' : 'disabled'; ?>>&nbsp;&nbsp;Start Diagnostic&nbsp;&nbsp;</button>

<?php $isShow_newReportSection = $curstatusid == "12000" && $isPersonOwnThisCase && !$isLastReleaseGroup3DateNull; ?> 
<button class="btn btn-primary" id="add_new_report_section_btn"  data-bs-toggle="modal"  data-bs-target="#add_result_type_modal" <?= ($isShow_newReportSection) ? '' : 'disabled'; ?> title="เพิ่มกล่องข้อความรายงานผลใหม่">&nbsp;&nbsp;Add New Report Section.&nbsp;&nbsp;</button>



<!--<button name="save_u_result" type="submit" class="btn btn-primary" style="display: none;">&nbsp;&nbsp;Save&nbsp;&nbsp;</button>-->
<!--<button name="discard_u_result" type="submit" class="btn btn-primary" style="display: none;">&nbsp;&nbsp;Discard&nbsp;&nbsp;</button>-->
<!--<button name="edit_u_result" type="submit" class="btn btn-primary" style="display: none;">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>-->
  <?php
    $isShowSendToReviewbtn = $isPersonOwnThisCase  // First patho is ownder this patient id (Cur_user == First patho)
        && $isLastReleaseGroup3SecondPathoAval                         //If Second Patho is select in last release group
        && $curstatus[0]['id'] == 12000;                     //and Status == 12000
//        && $isLastedResultReleaseDateNULL;                  //If Last report message block still not released
    $isShow_btn_release = $isPersonOwnThisCase  // First patho is ownder this patient id (Cur_user == First patho)
        && $curstatus[0]['id'] == 12000;                         //and Status == 12000
//        && $isLastReleaseGroup3DateNull;                     
    ?>
<button name="btn2review13000" id="btn2review13000"  <?= ($isShowSendToReviewbtn)? '':'disabled'; ?>  class="btn btn-primary">&nbsp;&nbsp;Request Second Pathologist Review&nbsp;&nbsp;</button>
<button name="btn_release" id="btn_release" type="submit" class="btn btn-primary" <?= ($isShow_btn_release)? '':'disabled'; ?> >&nbsp;&nbsp;Release Report&nbsp;&nbsp;</button>
<?php endif; ?>  


<?php require 'patient_form_080_job6__select_rs_modal.php'; ?>
<?php require 'patient_from_080_job6_template_select_modal.php'; ?>







    