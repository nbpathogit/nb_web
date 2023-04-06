
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
                            <?php if($user['user_status'] == 1): ?>
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
                            <?php endif; ?>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>   
                <div>
                    <br>
                    <button type="button" id="add_job_list6" onclick="add_job_list6(<?= $presultupdate['id'] ?>)" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>

