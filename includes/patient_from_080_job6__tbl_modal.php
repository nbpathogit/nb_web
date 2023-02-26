            
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
                    
             