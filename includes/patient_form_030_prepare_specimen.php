<?php
//กำหนด คน ตัดเนื้อ
$isBorder = false;

?>
<hr id="p_cross_section_id_hr">

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_cross_section_id"  class="form-label">พนักงานตัดเนื้อ</label>

        <select name="p_cross_section_id" id="p_cross_section_id" class="form-select" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['p_cross_section_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=$user['name'] . ' ' . $user['lastname']?><?php if($user['uid']!=0 && $isCurUserAdmin):?> <?=' (' . $user['username'] . '::' . $user['ugroup'] . ')';  ?><?php endif; ?>
                </option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_cross_section_ass_id"  class="form-label">พนักงานผู้ช่วยตัดเนื้อ</label>
        <select name="p_cross_section_ass_id" id="p_cross_section_ass_id" class="form-select" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['p_cross_section_ass_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=$user['name'] . ' ' . $user['lastname']?><?php if($user['uid']!=0 && $isCurUserAdmin):?> <?=' (' . $user['username'] . '::' . $user['ugroup'] . ')';  ?><?php endif; ?>
                </option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_3000" class="">เตรียมชิ้นเนื้อแล้วแล้วเมื่อวันที่</label>
        <input name="date_3000" id="date_3000" class="form-control border" type="text"  placeholder="This Field will Auto Generate" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && FALSE && ($userAuthEdit && $curStatusAuthEdit) ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_3000']; ?>">
    </div>
    
</div>





<hr>
<div class="row <?= $isBorder ? "border" : "" ?>"></div>
<h5 align="left"><b>พนักงานตัดเนื้อ</b></h4>

<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered" id="table_body_job1">
        <thead>
            <tr>
                <th >Id</th>
                <th >Patient Number</th>
                <th >Owner</th>
                <th >Job Name</th>
                <th >Cost</th>
                <th >Remark/comment</th>
                <th >Insert time</th>
                <th >Finish time</th>
                <th >Manage</th>
                
            </tr>
        </thead>
        <tbody id="">
            <?php foreach ($job_crosss as $joblist): ?>
                <tr>
                    <td ><?= $joblist['id'] ?></td>
                    <td ><?= $joblist['patient_number'] ?></td>
                    <td ><?= $joblist['pre_name'] ?> <?= $joblist['name'] ?> <?= $joblist['lastname'] ?></td>
                    <td ><?= $joblist['jobname'] ?></td>
                    <td ><?= $joblist['pay'] ?></td>
                    <td ><?= $joblist['comment'] ?></td>
                    <td ><?= $joblist['insert_time'] ?></td>
                    <td ><?= is_null($joblist['finish_date'])?"Not Specific":$joblist['finish_date'] ?></td>
                    <td >
                        <a  jobid="<?= $joblist['id'] ?>" onclick="deljob1(<?= $joblist['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal_job1"> เพิ่มพนักงานตัดเนื้อ </button>
    <button id="refresh_job1" class="btn btn-primary" >Refresh</button>
    <br><br>
</div>
<button id="btntest" class="btn btn-primary" <?= true ? "hidden" : "" ?> >test</button>




<!-- Modal -->
<div class="modal fade" id="add_modal_job1" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">เลือกพนักงานตัดเนื้อ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php //$userTechnic ?>      
                <div class="<?= $isBorder ? "border" : "" ?> ">
                    <label for="p_cross_section_id_job1"  class="form-label">เลือกพนักงานตัดเนื้อ</label>
                    <select name="p_cross_section_id_job1" id="p_cross_section_id_job1" class="form-select"  >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userTechnic as $user): ?>
                            <option value="<?= ($user['uid']); //user id     ?>"  
                                    job_role_id="1"
                                    patient_id="<?= $patient[0]['id']; //patient id     ?>"
                                    patient_number="<?= $patient[0]['pnum']; //Sergical number     ?>"
                                    user_id="<?= ($user['uid']); //user id     ?>"
                                    pre_name="<?= ($user['pre_name']); //pre name     ?>"
                                    name="<?= ($user['name']); //name     ?>"
                                    lastname="<?= ($user['lastname']); //name     ?>"
                                    jobname="<?= $jobRoles[0]['name']; //     ?>"
                                    pay="<?= $jobRoles[0]['cost_per_job']; //     ?>"
                                    cost_count_per_day="<?= $jobRoles[0]['cost_count_per_day']; //     ?>"
                                    comment=""
                                    >  <?= $user['pre_name'] . ' ' . $user['name'] . ' ' . $user['lastname'] ?>
                            </option>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>   
                <div>
                    <br>
                    <button type="button" id="add_job_list1" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>








<hr>
<div class="row <?= $isBorder ? "border" : "" ?>"></div>
<h5 align="left"><b>พนักงานผู้ช่วยตัดเนื้อ</b></h4>

<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered" id="table_body_job2">
        <thead>
            <tr>
                <th >Id</th>
                <th >Patient Number</th>
                <th >Owner</th>
                <th >Job Name</th>
                <th >Cost</th>
                <th >Remark/comment</th>
                <th >Insert time</th>
                <th >Finish time</th>
                <th >Manage</th>
                
            </tr>
        </thead>
        <tbody id="">
            <?php foreach ($job_assis_crosss as $joblist): ?>
                <tr>
                    <td ><?= $joblist['id'] ?></td>
                    <td ><?= $joblist['patient_number'] ?></td>
                    <td ><?= $joblist['pre_name'] ?> <?= $joblist['name'] ?> <?= $joblist['lastname'] ?></td>
                    <td ><?= $joblist['jobname'] ?></td>
                    <td ><?= $joblist['pay'] ?></td>
                    <td ><?= $joblist['comment'] ?></td>
                    <td ><?= $joblist['insert_time'] ?></td>
                    <td ><?= is_null($joblist['finish_date'])?"Not Specific":$joblist['finish_date'] ?></td>
                    <td >
                        <a  jobid="<?= $joblist['id'] ?>" onclick="deljob2(<?= $joblist['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal_job2"> เพิ่มพนักงานช่วยตัดเนื้อ </button>
    <button id="refresh_job2" class="btn btn-primary" >Refresh</button>
    <br><br>
</div>
<button id="btntest" class="btn btn-primary" <?= true ? "hidden" : "" ?> >test</button>




<!-- Modal -->
<div class="modal fade" id="add_modal_job2" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">เลือกพนักงานช่วยตัดเนื้อ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php //$userTechnic ?>      
                <div class="<?= $isBorder ? "border" : "" ?> ">
                    <label for="p_cross_section_id_job2"  class="form-label">เลือกพนักงานช่วยตัดเนื้อ</label>
                    <select name="p_cross_section_id_job2" id="p_cross_section_id_job2" class="form-select"  >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userTechnic as $user): ?>
                            <option value="<?= ($user['uid']); //user id     ?>"  
                                    job_role_id="2"
                                    patient_id="<?= $patient[0]['id']; //patient id     ?>"
                                    patient_number="<?= $patient[0]['pnum']; //Sergical number     ?>"
                                    user_id="<?= ($user['uid']); //user id     ?>"
                                    pre_name="<?= ($user['pre_name']); //pre name     ?>"
                                    name="<?= ($user['name']); //name     ?>"
                                    lastname="<?= ($user['lastname']); //name     ?>"
                                    jobname="<?= $jobRoles[1]['name']; //     ?>"
                                    pay="<?= $jobRoles[1]['cost_per_job']; //     ?>"
                                    cost_count_per_day="<?= $jobRoles[1]['cost_count_per_day']; //     ?>"
                                    comment=""
                                    >  <?= $user['pre_name'] . ' ' . $user['name'] . ' ' . $user['lastname'] ?>
                            </option>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>   
                <div>
                    <br>
                    <button type="button" id="add_job_list2" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>

