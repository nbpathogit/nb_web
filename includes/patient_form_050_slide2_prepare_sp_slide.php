<?php
//เตรียมสไลด์พิเศษ 8000
$isBorder = false;



$userAuthEdit = (
        $isCurUserAdmin 
    || $isCurUserPatho 
    || $isCurUserPathoAssis 
    || $isCurUserLabOfficerNB 
    || $isCurUserAdminStaff 
    //|| $isCurUserClinicianCust 
    //|| $isCurUserHospitalCust
        );

$curStatusAuthEdit = (
        $isCurStatus_1000 
    || $isCurStatus_2000 
    || $isCurStatus_3000 
    || $isCurStatus_6000 
    || $isCurStatus_8000
    || $isCurStatus_10000
    || $isCurStatus_12000
    || $isCurStatus_13000
    || $isCurStatus_20000
        );

?>

<?php if ($hide) : ?>


<div class="row <?= $isBorder ? "border" : "" ?>">

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="p_slide_prep_sp_id"  class="form-label" >พนักงานเตรียมไลด์พิเศษ(To be remove)</label>

        <select name="p_slide_prep_sp_id" id="p_slide_prep_sp_id"  class="form-select" <?= $isEditModePageOn && $isEditModePageForSpSlidePrepDataOn && ($userAuthEdit && $curStatusAuthEdit) ? "" : " disabled readonly " ?> >
            <!--<option value="">กรุณาเลือก</option>-->
            <?php foreach ($userTechnic as $user): ?>
                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                <option value="<?= htmlspecialchars($user['uid']); ?>" <?= $patient[0]['p_slide_prep_sp_id'] == htmlspecialchars($user['uid']) ? "selected" : ""; ?> > 
                    <?=$user['name'] . ' ' . $user['lastname']?><?php if($user['uid']!=0 && $isCurUserAdmin):?> <?=' (' . $user['username'] . '::' . $user['ugroup'] . ')';  ?><?php endif; ?>
                </option>
            <?php endforeach; ?>
        </select> 

    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="pspprice" class="form-label">ราคาค่าตรวจพิเศษ(บาท)(To be remove)</label>
        <input name="pspprice" id="pspprice" type="text" class="form-control"   <?= $isEditModePageOn && $isEditModePageForSpSlidePrepDataOn && ($userAuthEdit && $curStatusAuthEdit)  ? "" : " disabled  " ?>  value="<?= $patient[0]['pspprice']; ?>"  >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
        <label for="date_8000" class="">เตรียมสไลด์พิเศษแล้วเมื่อวันที่(To be remove)</label>
        <input name="date_8000" id="date_8000" class="form-control border" type="text" class=""  placeholder="This Field will Auto Generate" <?= $isEditModePageOn && $isEditModePageForSpSlidePrepDataOn  && ($userAuthEdit && $curStatusAuthEdit) ? "" : " disabled readonly " ?> value="<?= $patient[0]['date_8000']; ?>">
    </div>
    
    <div align=""  class="mb-3">
        <label for="p_sp_patho_comment">Comment</label><br>

        <textarea name="p_sp_patho_comment" cols="100" rows="5" class="form-control" id="p_sp_patho_comment" <?= $isEditModePageOn && $isEditModePageForSpSlidePrepDataOn && ($userAuthEdit && $curStatusAuthEdit) ? "" : " disabled readonly " ?> ><?= htmlspecialchars($patient[0]['p_sp_patho_comment']); ?></textarea>
    </div>
   
</div>

<?php endif; ?>

<hr>
<div class="row <?= $isBorder ? "border" : "" ?>"></div>
<!--<h5 align="left">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecimenModa2"> Add Special Slide </button>
    <button id="refresh_spcimen_list2" class="btn btn-primary" >Refresh</button>
    <b>(รายการขอสไลดด์พิเศษ)</b>
</h5>-->
<p align="left">
    <b>รายการขอสไลดด์พิเศษ:</b>
    <span id="spcimen_list2" style="font-size:20px">
        <span class="badge rounded-pill bg-primary" id="">Aaaaaa</span>
        <span class="badge rounded-pill bg-primary" id="">Bbbbbb</span>
        <span class="badge rounded-pill bg-primary" id="">Cccccc</span>
    </span>  
    <!--<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecimenModal1"> Add Specimen </button>-->
    <!--<button id="refresh_spcimen_list1" class="btn btn-primary" >Refresh</button>-->
    <a class="btn btn-outline-primary btn-sm me-1 " data-bs-toggle="modal"  data-bs-target="#addSpecimenModal2"><i class="fa-sharp fa-solid fa-plus"></i> Add</a>
    <a class="btn btn-outline-primary btn-sm me-1 "  id="refresh_spcimen_list2"><i class="fa-solid fa-rotate-right"></i> refresh </a>
    <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#spcimen_tbl_list2"><i class="fa-solid fa-table"></i> detail </a>
    
    
</p>


<?php if ($show) : ?>
<!-- Modal -->
<div class="modal fade" id="spcimen_tbl_list2" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">เลือกพนักงานช่วยตัดเนื้อ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<?php endif; ?>


<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered border-dark" id="spcimen_list_table2">
        <thead>
            <tr>
                <th >Id</th>
                <th >Patient Number</th>
                <th >Code Name</th>
                <th >Description</th>
                <th >Price</th>
                <th >Remark/comment</th>
                <th >Manage</th>

            </tr>
        </thead>
        <tbody id="spcimen_list_table_body2">
<?php foreach ($billing2s as $billing): ?>
                <tr>
                    <td ><?= $billing['id'] ?></td>
                    <td ><?= $billing['number'] ?></td>
                    <td ><?= $billing['code_description'] ?></td>
                    <td ><?= $billing['description'] ?></td>
                    <td ><?= $billing['cost'] ?></td>
                    <td ><?= $billing['comment'] ?></td>
                    <td >
                        <a  billid="<?= $billing['id'] ?>" onclick="delbill2(<?= $billing['id'] . ',' . $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!--<button id="btntest" class="btn btn-primary" <?= true ? "hidden" : "" ?> >test</button>-->
                
                
                
                
                
                
                

                <?php if ($show) : ?>
        </div>
        <div class="modal-footer">

        </div>
    </div>
</div>
</div>
<?php endif; ?>




<!-- Modal -->
<div class="modal fade" id="addSpecimenModal2" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">เลือกรายการสไลด์พิเศษ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">



                <div class=" <?= $isBorder ? "border" : "" ?>">
                    <label for="phospital_select_for_price2" class="">เลือกโรงพยาบาล</label>
                    <select name="phospital_select_for_price2" id="phospital_select_for_price2" class="form-select" <?= ( true ) ? "" : " disabled readonly " ?> >
                        <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
<?php foreach ($hospitals as $hospital): ?>
    <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option>  ?>
                            <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= ($patient[0]['phospital_id'] == ($hospital['id'])) ? "selected" : ""; ?> ><?= ($hospital['id'] == 0) ? "โรงพยาบาลราคามาตรฐาน" : $hospital['hospital']; ?></option>
<?php endforeach; ?>
                    </select>

                </div>

                <div class=" <?= $isBorder ? "border" : "" ?>">
                    <label for="pspecimen_for_select" class="" >เลือกรายการสไลด์ย้อมพิเศษ</label>
                    <select name="pspecimen_for_select" id="pspecimen_for_select2" class="form-select"  >
                        <!--            <option value="กรุณาเลือก">กรุณาเลือก</option>-->
                        <?php foreach ($specimen2s as $specimen): ?>
                            <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>    ?>

                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="specimen_num2" class="form-label">รหัสรายการ</label>
                    <input name="specimen_num2" id="specimen_num2" type="text" class="form-control"    value=""   readonly>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="specimen_for_specimen2" class="form-label">รายการส่งตรวจ</label>
                    <input name="specimen_for_specimen2" id="specimen_for_specimen2" type="text" class="form-control"    value=""   readonly>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="price_for_specimen2" class="form-label">ราคาค่าตรวจ(บาท)</label>
                    <input name="price_for_specimen2" id="price_for_specimen2" type="text" class="form-control"    value=""   readonly
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> " >
                    <label for="comment_for_specimen2" class="form-label">comment</label>
                    <input name="comment_for_specimen2" id="comment_for_specimen2" type="text" class="form-control"    value=""   readonly>
                </div>
                <div>
                    <br>
                    <button type="button" id="add_spcimen_list2" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>


        </div>
        <div class="modal-footer">        </div>
        </div>
    </div>
</div>


