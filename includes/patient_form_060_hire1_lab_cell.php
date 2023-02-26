<?php
//ใส่ค่าแลปเซลวิทยา แลปน้ำ 
$isBorder = false;

?>

<?php if ($hide) : ?>
<hr>

<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_lab_id" class="form-label" >แลปเซลด์วิทยา(To be remove)</label>
        <select name="p_slide_lab_id" id="p_slide_lab_id" class="form-select" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && ($isCurUserAdmin || ($userAuthEdit && $curStatusAuthEdit) ) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($labFluids as $labFluid): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($labFluid['id']); ?>" <?= $patient[0]['p_slide_lab_id'] == $labFluid['id'] ? "selected" : ""; ?> > 
                    <?=    $labFluid['labname'] ;?> </option>
            <?php endforeach; ?>                                     
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_lab_price" class="form-label">ราคาค่าตรวจ(บาท)(To be remove)</label>
        <input name="p_slide_lab_price" id="p_slide_lab_price" type="text" class="form-control"   disabled readonly  value="<?= $patient[0]['p_slide_lab_price']; ?>"  >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_10000" class="">เตรียมสไลด์น้ำแล้วเมื่อวันที่(To be remove)</label>
        <input name="date_10000"  id="date_10000"  class="form-control border" type="text" placeholder="This Field will Auto Generate" <?= $isEditModePageOn && $isEditModePageForPlaningDataOn && FALSE ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_10000']; ?>">
    </div>

</div>

<?php endif; ?>
  




<!--Table Modal Hire1-->
<hr>

<!--<h5>
    <span align="center">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal_hire1"> เพิ่มผู้รับงานแลปเซลล์วิทยา </button>
    <button id="refresh_hire1" class="btn btn-primary" >Refresh</button>
    </span>
    <span align="center"><b>(ผู้รับงานแลปเซลล์วิทยา)</b></span>
</h5>-->
<p align="left">
    
    <b>ผู้รับงานแลปเซลล์วิทยา:</b>
    <span id="list_hire1" style="font-size:20px">
        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
        <span class="badge rounded-pill bg-primary" id="">Please Refresh</span>
    </span>  

    <a class="btn btn-outline-primary btn-sm me-1 " data-bs-toggle="modal"  data-bs-target="#add_modal_hire1" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i></a>
    <a class="btn btn-outline-primary btn-sm me-1 "  id="refresh_hire1" title="Refresh" ><i class="fa-solid fa-rotate-right"></i></a>
    <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#lab_tbl_hire1" title="View/Detail" ><i class="fa-solid fa-table"></i></a>

</p>







<?php if ($show) : ?>
<!-- Modal -->
<div class="modal fade" id="lab_tbl_hire1" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">ผู้รับงานแลปเซลล์วิทยา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<?php endif; ?>


<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered border-dark" id="table_body_hire1">
        <thead>
            <tr>
                <th >Id</th>
                <th >Name</th>
                <th >Job Name</th>
                <th >patient_id</th>
                <th >patient_number</th>
                <th >Cost</th>
                <th >Accept time</th>
                <th >Finish time</th>
                <th >Manage</th>
                
            </tr>
        </thead>
        <tbody id="">
            <?php foreach ($hires as $hires): ?>
                <tr>
                    <td ><?= $hires['id'] ?></td>
                    <td ><?= $hires['name'] ?></td>
                    <td > แลปเซลล์วิทยา </td>
                    <td ><?= $hires['patient_id'] ?></td>
                    <td ><?= $hires['patient_number'] ?></td>
                    <td ><?= $hires['cost'] ?></td>
                    <td ><?= $hires['accept_time'] ?></td>
                    <td ><?= $hires['finish_time'] ?></td>
                    <td >
                        <a  jobid="<?= $joblist['id'] ?>" onclick="delhire1(<?= $hires['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
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
<div class="modal fade" id="add_modal_hire1" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">เลือกผู้รับงานแลปเซลล์วิทยา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php //$userTechnic ?>
                <div class="<?= $isBorder ? "border" : "" ?> ">
                    <label for="select_hire1"  class="form-label">เลือกผู้รับงานแลปเซลล์วิทยา</label>
                    <select name="select_hire1" id="select_hire1" class="form-select"  >
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($outsideContracts as $outside): ?>
                            <option value="<?= ($outside['id']);     ?>"  
                                    name="<?= ($outside['name']); //compane name?>"
                                    cost="<?= ($outside['cost']); ?>"
                                    job_name="แลปเซลล์วิทยา"
                                    patient_id="<?= $patient[0]['id']; //patient id     ?>"
                                    patient_number="<?= $patient[0]['pnum']; //Sergical number     ?>"
                                    accept_time="<?= $patient[0]['date_1000']; //accept time     ?>"
                                    comment=""
                                    >  <?= $outside['name']; ?>
                            </option>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>   
                <div>
                    <br>
                    <button type="button" id="add_list_hire1" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>

