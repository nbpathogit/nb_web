            
<?php if ($show) : ?>
<!-- Modal -->
<div class="modal fade" id="add_result_type_modal" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">เลือกชนิดของการรายงานผล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<?php endif; ?>
                


<!--<form id="add_u_result" name="" method="post">-->
    <!--<div class="row <?= $isBorder ? "border" : "" ?>">-->
        <!--<div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">-->
            <div>
            <label for="result_type" class="">เลือกชนิดของการรายงานผล</label>
            <select name="result_type" class="form-select" id="result_type">
                <option value="0">ยังไม่ได้เลือก</option>
                <?php foreach($rsResultType2s as $rsResultType2): ?>
                <option value="<?=$rsResultType2['name'];?>" group_type="<?=$rsResultType2['group_type'];?>" type_id="<?=$rsResultType2['id'];?>" patient_id="<?= $patient[0]['id']; ?>" release_type="<?=$rsResultType2['release_type'];?>"  ><?=$rsResultType2['name'];?></option>
                <?php endforeach; ?>

            </select>
            </div>
        <!--</div>-->
        <!--<div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">-->
        <div>
            <button name="add_u_result" id="add_u_result" type="submit" class="btn btn-primary" data-bs-dismiss="modal">&nbsp;ADD&nbsp;&nbsp;</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        <!--</div>-->
    <!--</div>-->







               
                                <?php if ($show) : ?>
        </div>
        <div class="modal-footer">

        </div>
    </div>
</div>
</div>
<?php endif; ?>    
                    
             