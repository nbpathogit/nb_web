<!--job5-->

<?php
//แลปเซลวิทยา แลปน้ำ 10000
$isBorder = false;

?>









<!--Table Modal Job5-->
<hr>
<div class="row <?= $isBorder ? "border" : "" ?>"></div>
<!--<h5>
    <span align="center">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal_job5"> เพิ่มแพทย์ผู้ออกผล </button>
    <button id="refresh_job5" class="btn btn-primary" >Refresh</button>
    </span>
    <span align="center"><b>(แพทย์ผู้ออกผล)</b></span>
</h5>-->
<p align="left">
    
    <b>พยาธิแพทย์ผู้ออกผล(Pathologist):</b>
    <span id="owner_job5" class="owner_job5" style="font-size:20px">
        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
    </span>  
    
    <?php if (!$isCurUserCust): ?>
    <a class="btn btn-outline-primary btn-sm me-1 " data-bs-toggle="modal"  data-bs-target="#add_modal_job5" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i></a>
    <a class="btn btn-outline-primary btn-sm me-1 "  id="refresh_job5" title="Refresh" ><i class="fa-solid fa-rotate-right"></i></a>
    <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#owner_tbl_job5" title="View/Detail" ><i class="fa-solid fa-table"></i></a>
    <?php endif; ?>

</p>
    

<?php if ($show) : ?>
<!-- Modal -->
<div class="modal fade" id="owner_tbl_job5" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">เลือกแพทย์ย์ผู้ออกผล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<?php endif; ?>



<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered border-dark" id="table_body_job5">
        <thead>
            <tr>
                <th >Id</th>
                <th >เลือกแพทย์ผู้ออกผล</th>
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
            <?php foreach ($job5s as $joblist): ?>
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
                        <a  jobid="<?= $joblist['id'] ?>" onclick="deljob5(<?= $joblist['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
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
<div class="modal fade" id="add_modal_job5" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel5">เลือกแพทย์ผู้ออกผล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php //$userTechnic ?>      
                <div class="<?= $isBorder ? "border" : "" ?> ">
                    <label for="select_job5"  class="form-label">เลือกแพทย์ผู้ออกผล</label>
                    <select name="select_job5" id="select_job5" class="form-select"  >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userPathos as $user): ?>
                            <?php if($user['user_status'] == 1): ?>
                            <option value="<?= ($user['uid']); //user id     ?>"  
                                    job_role_id="5"
                                    patient_id="<?= $patient[0]['id']; //patient id     ?>"
                                    patient_number="<?= $patient[0]['pnum']; //Sergical number     ?>"
                                    user_id="<?= ($user['uid']); //user id     ?>"
                                    pre_name="<?= ($user['pre_name']); //pre name     ?>"
                                    name="<?= ($user['name']); //name     ?>"
                                    lastname="<?= ($user['lastname']); //name     ?>"
                                    jobname="<?= $jobRoles[-1 + 5]['name']; //     ?>"
                                    pay="<?= $jobRoles[-1 + 5]['cost_per_job']; //     ?>"
                                    cost_count_per_day="<?= $jobRoles[-1 + 5]['cost_count_per_day']; //     ?>"
                                    comment=""
                                    >  <?= $user['pre_name'] . ' ' . $user['name'] . ' ' . $user['lastname'] ?>
                            </option>
                            <?php endif; ?>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>   
                <div>
                    <br>
                    <button type="button" id="add_job_list5" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>