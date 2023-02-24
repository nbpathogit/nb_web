<!--job4-->


<!--Table Modal Job4-->
<hr>
<!--<h5>
    <span align="center">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal_job4"> เพิ่มพนักงานเตรียมสใลด์พิเศษ </button>
    <button id="refresh_job4" class="btn btn-primary" >Refresh</button>
    </span>
    <span align="center"><b>(พนักงานเตรียมสใลด์พิเศษ)</b></span>
</h5>-->

<p align="left">
    
    <b>พนักงานเตรียมสใลด์พิเศษ:</b>
    <span id="owner_job4" style="font-size:20px">
        <span class="badge rounded-pill bg-primary" id="">Aaaaaa</span>
        <span class="badge rounded-pill bg-primary" id="">Bbbbbb</span>
        <span class="badge rounded-pill bg-primary" id="">Cccccc</span>
    </span>  

    <a class="btn btn-outline-primary btn-sm me-1 " data-bs-toggle="modal"  data-bs-target="#add_modal_job4"><i class="fa-sharp fa-solid fa-plus"></i> Add</a>
    <a class="btn btn-outline-primary btn-sm me-1 "  id="refresh_job4"><i class="fa-solid fa-rotate-right"></i> refresh </a>
    <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#owner_tbl_job4"><i class="fa-solid fa-table"></i> detail </a>
    

</p>
    

<?php if ($show) : ?>
<!-- Modal -->
<div class="modal fade" id="owner_tbl_job4" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">พนักงานเตรียมสใลด์พิเศษ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<?php endif; ?>

<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered border-dark" id="table_body_job4">
        <thead>
            <tr>
                <th >Id</th>
                <th >พนักงานเตรียมสไลด์พิเศษ</th>
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
            <?php foreach ($job4s as $joblist): ?>
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
                        <a  jobid="<?= $joblist['id'] ?>" onclick="deljob4(<?= $joblist['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
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
<div class="modal fade" id="add_modal_job4" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">เลือกพนักงานเตรียมสใลด์พิเศษ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php //$userTechnic ?>      
                <div class="<?= $isBorder ? "border" : "" ?> ">
                    <label for="p_slide_prep_id_job4"  class="form-label">เลือกพนักงานเตรียมสใลด์พิเศษ</label>
                    <select name="p_slide_prep_id_job4" id="select_job4" class="form-select"  >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userTechnic as $user): ?>
                            <option value="<?= ($user['uid']); //user id     ?>"  
                                    job_role_id="4"
                                    patient_id="<?= $patient[0]['id']; //patient id     ?>"
                                    patient_number="<?= $patient[0]['pnum']; //Sergical number     ?>"
                                    user_id="<?= ($user['uid']); //user id     ?>"
                                    pre_name="<?= ($user['pre_name']); //pre name     ?>"
                                    name="<?= ($user['name']); //name     ?>"
                                    lastname="<?= ($user['lastname']); //name     ?>"
                                    jobname="<?= $jobRoles[3]['name']; //     ?>"
                                    pay="<?= $jobRoles[3]['cost_per_job']; //     ?>"
                                    cost_count_per_day="<?= $jobRoles[3]['cost_count_per_day']; //     ?>"
                                    comment=""
                                    >  <?= $user['pre_name'] . ' ' . $user['name'] . ' ' . $user['lastname'] ?>
                            </option>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>   
                <div>
                    <br>
                    <button type="button" id="add_job_list4" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>