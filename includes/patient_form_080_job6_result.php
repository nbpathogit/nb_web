<?php
$isBorder = false;
$isSetShowaddResultButton = true;
?>



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
        <?php
        $isShowSendToReviewbtn = $isCurrentPathoIsOwnerThisCase // First patho is ownder this patient id (Cur_user == First patho)
            && $presultupdate['pathologist2_id'] != 0 //If Second Patho is select 
            && $curstatus[0]['id'] == 12000; //and Status == 12000
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
                    <b>แพทย์ผู้ออกผล:</b>
                    <span id="owner_job6a" style="font-size:20px">
                        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
                        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
                        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
                    </span>  
                    <a class="btn btn-outline-primary btn-sm me-1 " data-bs-toggle="modal"  data-bs-target="#add_modal_job6"><i class="fa-sharp fa-solid fa-plus"></i> Add</a>
                    <a class="btn btn-outline-primary btn-sm me-1 "  id="refresh_job6"><i class="fa-solid fa-rotate-right"></i> refresh </a>
                    <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#owner_tbl_job6"><i class="fa-solid fa-table"></i> detail </a>
                
             
                    
                    
                    
                    
                    
                    
<?php if ($show) : ?>
<!-- Modal -->
<div class="modal fade" id="owner_tbl_job6" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">เลือกแพทย์ผู้คอนเฟริ์มผล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<?php endif; ?>



<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered border-dark" id="table_body_job6">
        <thead>
            <tr>
                <th >Id</th>
                <th >แพทย์ผู้คอนเฟริ์มผล</th>
                <th >Patient Number</th>
                <th >Job Name</th>
                <th >Cost</th>
                <th >Remark/comment</th>
                <th >Insert time</th>
                <th >Finish time</th>
                <th >Manage</th>
                
            </tr>
        </thead>
        <tbody id="">
            <?php foreach ($job6s as $joblist): ?>
                <tr>
                    <td ><?= $joblist['id'] ?></td>
                    <td ><b><?= $joblist['pre_name'] ?> <?= $joblist['name'] ?> <?= $joblist['lastname'] ?></b></td>
                    <td ><?= $joblist['patient_number'] ?></td>
                    <td ><?= $joblist['jobname'] ?></td>
                    <td ><?= $joblist['pay'] ?></td>
                    <td ><?= $joblist['comment'] ?></td>
                    <td ><?= $joblist['insert_time'] ?></td>
                    <td ><?= is_null($joblist['finish_date'])?"Not Specific":$joblist['finish_date'] ?></td>
                    <td >
                        <a  jobid="<?= $joblist['id'] ?>" onclick="deljob6(<?= $joblist['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<button id="btntest" class="btn btn-primary" <?= true ? "hidden" : "" ?> >test</button>



                <?php if ($show) : ?>
        </div>
        <div class="modal-footer">

        </div>
    </div>
</div>
</div>
<?php endif; ?>

                    




<!-- Modal -->
<div class="modal fade" id="add_modal_job6" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel6">เลือกแพทย์ผู้คอนเฟริ์มผล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php //$userTechnic ?>      
                <div class="<?= $isBorder ? "border" : "" ?> ">
                    <label for="select_job6"  class="form-label">เลือกแพทย์ผู้คอนเฟริ์มผล</label>
                    <select name="select_job6" id="select_job6" class="form-select"  >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userPathos as $user): ?>
                            <option value="<?= ($user['uid']); //user id     ?>"  
                                    job_role_id="6"
                                    patient_id="<?= $patient[0]['id']; //patient id     ?>"
                                    patient_number="<?= $patient[0]['pnum']; //Sergical number     ?>"
                                    user_id="<?= ($user['uid']); //user id     ?>"
                                    pre_name="<?= ($user['pre_name']); //pre name     ?>"
                                    name="<?= ($user['name']); //name     ?>"
                                    lastname="<?= ($user['lastname']); //name     ?>"
                                    jobname="<?= $jobRoles[-1 + 6]['name']; //     ?>"
                                    pay="<?= $jobRoles[-1 + 6]['cost_per_job']; //     ?>"
                                    cost_count_per_day="<?= $jobRoles[-1 + 6]['cost_count_per_day']; //     ?>"
                                    comment=""
                                    >  <?= $user['pre_name'] . ' ' . $user['name'] . ' ' . $user['lastname'] ?>
                            </option>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>   
                <div>
                    <br>
                    <button type="button" id="add_job_list6" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>








                    
                    
                    
                    
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
                                <?php if ($isShowSendToReviewbtn) : ?><button name="btn2review13000" id="btn2review13000" type="" class="btn btn-primary">&nbsp;&nbsp;Send to Second Pathologist Review&nbsp;&nbsp;</button><?php endif; ?>
                                <button name="btn_release" id="btn_release" type="submit" class="btn btn-primary">&nbsp;&nbsp;ออกผล&nbsp;&nbsp;</button>
                            </p>


                            <?php //ส่วนของแพทย์คนที่สอง รีวิว 
                            ?>
                            <?php if ($curstatusid == "13000") :  ?>
                                <hr noshade="noshade" width="" size="4">
                                <h4 align="center"><b>แพทย์คนที่สองรีวิว</b><span style="color:orange;"><-ขั้นตอนปัจจุบัน< /span></h4>
                            <?php endif; ?>

                            <?php
                            if (
                                $isCurrentPathoIsSecondOwneThisCaseLastest // Second patho is ownder this patient id (Cur_user == Second patho)
                                && $curstatus[0]['id'] == 13000
                            ) : //and CurrentStatus == 13000
                            ?>
                                <p align="center">คุณ <?= $pathoOwner2NameObj->name_e . " " . $pathoOwner2NameObj->lastname_e . " "; ?> </p>
                                <p align="center">คุณคือแพทย์คนที่สองช่วยดับเบิ้ลคอนเฟิร์มผลของผู้ป่วยท่านนี้ กรุณาคลิกเลือกปุ่มคอนเฟิร์ม </p>
                                <p align="center"><button name="btnrejto12000" id="btnrejto12000" type="" class="btn btn-primary">&nbsp;&nbsp;Reject to originator&nbsp;&nbsp;</button>
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