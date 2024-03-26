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

<span id="sp_slide_requested">
    <div class="bg-nb  bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <p align="center"><b>รายการที่ xx ส่งตรวจพิเศษแล้วเมื่อ xx</b>
        </p>
<!--                <p align="left">
            <b>พนักงานเตรียมสไลด์พิเศษ:</b>
            <span id="owner_job4_rq" style="font-size:20px">
                <span class="badge rounded-pill bg-primary" id="">Aaaaaa</span>
                <span class="badge rounded-pill bg-primary" id="">Bbbbbb</span>
                <span class="badge rounded-pill bg-primary" id="">Cccccc</span>
            </span>
        </p>        -->
        <p align="left">
            <b>order ตรวจพิเศษ:</b>
            <span id="spcimen_list2_rq" style="font-size:20px">
                <span class="badge rounded-pill bg-primary" id="">Aaaaaa</span>
                <span class="badge rounded-pill bg-primary" id="">Bbbbbb</span>
                <span class="badge rounded-pill bg-primary" id="">Cccccc</span>
            </span>  
        </p>
    </div>
</span>

<hr noshade="noshade" width="" size="6">
        
<div class="bg-nb  bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
<hr>
<h5 align="center"><b>ใส่ข้อมูลเพื่อร้องขอตรวจพิเศษ</b><span style="color:orange;"><?= ""; // ($curstatusid == "8000") ? "<b> <-ขั้นตอนปัจจุบัน</b>" : ""    ?></span></h5>
     
<div class="row ">
    
    <div class="col-xl-4 col-md-6 ">
        <div id="phospital_select_for_price2_div" class=" <?= $isBorder ? "border" : "" ?>">
            <label for="phospital_select_for_price2" class="">เลือกโรงพยาบาล</label>
            <select name="phospital_select_for_price2" id="phospital_select_for_price2" class="" <?= ( true ) ? "" : " disabled readonly " ?> >
                <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
            <?php foreach ($hospitals as $hospital): ?>
                <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option>  ?>
                                        <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= ($patient[0]['phospital_id'] == ($hospital['id'])) ? "selected" : ""; ?> ><?= ($hospital['id'] == 0) ? "โรงพยาบาลราคามาตรฐาน" : $hospital['hospital']; ?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 ">
        <div id="nb_price2_type_div" class=" <?= $isBorder ? "border" : "" ?>">
            <label for="nb_price2_type" class="">เลือกชนิดตรวจพิเศษ</label>
            <select name="nb_price2_type" id="nb_price2_type" class="">
<!--                <option value="0">กรุณาเลือกชนิด</option>
                                    <option value="1" order_list="1">1 ตรวจชิ้นเนื้อศัลยพยาธิวิทยา (SN or IN)</option>
                                    <option value="4" order_list="2">2 ตรวจเซลล์วิทยา (CN)</option>
                                    <option value="5" order_list="3">3 ตรวจเซลล์มะเร็งปากมดลูก (PN or LN)</option>
                                    <option value="6" order_list="4">4 ตรวจชิ้นเนื้อโดยอุณหภูมิเยือกแข็ง(Frozen Staining)</option>
                                    <option value="2" order_list="5">5 ตรวจพิเศษ (Special Staining)</option>
                                    <option value="3" order_list="6">6 ตรวจพิเศษ (Immuno Staining)</option>
                                    <option value="7" order_list="7">7 ตรวจพิเศษอณูชีววิทยา(Molecular Staining)</option>-->
                <!--                    <option value="0">กรุณาเลือกชนิด</option>
                    <option value="1">สิ่งส่งตรวจ</option>
                    <option value="2">ย้อมพิเศษ</option>-->
                    
                    
            </select>
            
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 ">
        <div id="pspecimen_for_select2_div" class=" <?= $isBorder ? "border" : "" ?>">
            <label for="pspecimen_for_select2" class="" >เลือกรายการสไลด์ย้อมพิเศษ</label>
            <select name="pspecimen_for_select2" id="pspecimen_for_select2" class=""  >
                <!--            <option value="กรุณาเลือก">กรุณาเลือก</option>-->
                <?php foreach ($specimen2s as $specimen): ?>
                    <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>    ?>

                <?php endforeach; ?>
            </select>
        </div>
   </div>
    
    
    <div class="col-xl-4 col-md-6 ">
        <div id="" class=" <?= $isBorder ? "border" : "" ?>">
            <?php require 'includes/sp2dropdownlist.php'; ?>
        </div>
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
        <input name="price_for_specimen2" id="price_for_specimen2" type="text" class="form-control"    value=""   readonly>
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> " >
        <label for="comment_for_specimen2" class="form-label">comment</label>
        <input name="comment_for_specimen2" id="comment_for_specimen2" type="text" class="form-control"    value=""   readonly>
    </div>
    
<!--                    $('#comment_note').val(datajson[i].comment);
                $('#job_type').val(datajson[i].jobtype);-->
    
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> " >
        <label for="comment_note" class="form-label">note</label>
        <input name="comment_note" id="comment_note" type="text" class="form-control"    value=""   readonly>
    </div>
    
    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> " >
        <label for="job_type" class="form-label">job_type</label>
        <input name="job_type" id="job_type" type="text" class="form-control"    value=""   readonly>
    </div>
    
</div>

    <br>   
     <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> " >  
        <button type="button" id="add_spcimen_list2" class="btn btn-primary" <?php if ($isOverColsingDate){echo ' disabled ';} ?>>Add</button>
        <?php if ($isOverColsingDate): ?>
            <span id="spcimen_list1" style="font-size:16px"> รายการนี้เกินวันปิดยอดบิลแล้ว (<?= $statementClosingDate->format('Y-m-d'); ?>) หากต้องการสร้างกรุณาสร้างกรุณารายการไหม่เป็นชนิด IN</span>
        <?php endif; ?>
    </div>

<br>
    
    
    


<!--<div class="row <?= $isBorder ? "border" : "" ?>"></div>-->

<!--<h5 align="left">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecimenModa2"> Add Special Slide </button>
    <button id="refresh_spcimen_list2" class="btn btn-primary" >Refresh</button>
    <b>(รายการขอสไลดด์พิเศษ)</b>
</h5>-->
<!--<p align="left">
    <b>order ย้อมพิเศษ:</b>
    <span id="spcimen_list2" style="font-size:20px">
        <span class="badge rounded-pill bg-primary" id="">Aaaaaa</span>
        <span class="badge rounded-pill bg-primary" id="">Bbbbbb</span>
        <span class="badge rounded-pill bg-primary" id="">Cccccc</span>
    </span>  
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecimenModal1"> Add Specimen </button>
    <button id="refresh_spcimen_list1" class="btn btn-primary" >Refresh</button>
    <?php //  $is_SP = $patient[0]['request_sp_slide'] > 0;  ?>
    <?php //$is_SP = FALSE;// Intend to not use this falg in the future  ?>
    <?php //$cur_request_sp_slide_status = $patient[0]['request_sp_slide'];  ?>
    <?php //if (!$isCurUserCust): ?>
    <a class="btn btn-outline-primary btn-sm me-1 sp_slide_req_btn <?= $is_SP ? 'disabled':'';?>"  aria-disabled="true"  data-bs-toggle="modal"  data-bs-target="#addSpecimenModal2" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i></a>
    <a class="btn btn-outline-primary btn-sm me-1 sp_slide_req_btn <?= $is_SP ? 'disabled':'';?>"  id="refresh_spcimen_list2" title="Refresh" ><i class="fa-solid fa-rotate-right"></i></a>
    <a class="btn btn-outline-primary btn-sm me-1 sp_slide_req_btn <?= $is_SP ? 'disabled':'';?>"  data-bs-toggle="modal"  data-bs-target="#spcimen_tbl_list2" title="View/Detail" ><i class="fa-solid fa-table"></i></a>
    <?php //endif; ?>
    
</p>-->

<div class="bg-nb  bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">


<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered border-dark" id="spcimen_list_table2">
        <thead>
            <tr>
                <th >Bill id</th>
                <th >Survice Type</th>
                <th >Patient id</th>
                <th >Patient Number</th>
                <th >Code Name</th>
                <th >Description</th>
                <th >block</th>
                <th >Price</th>
                <th >Remark/comment</th>
                <th >DATE</th>
                <th >Manage</th>

            </tr>
        </thead>
        <tbody id="spcimen_list_table_body2">
<?php foreach ($billing2s as $billing): ?>
                <tr>
                    <td ><?= $billing['id'] ?></td>
                    <td ><?= $billing['number'] ?></td>
                    <td ><?= $billing['number'] ?></td>
                    <td ><?= $billing['code_description'] ?></td>
                    <td ><?= $billing['description'] ?></td>
                    <td ><?= $billing['sp_slide_block'] ?></td>
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




    <div align=""  class="mb-3">
        <label for="p_sp_patho_comment">Comment</label><br>

        <textarea name="p_sp_patho_comment" cols="100" rows="5" class="form-control" id="p_sp_patho_comment"  ></textarea>
        <?php if (!$isCurUserCust): ?>
        <a hidden class="btn btn-outline-primary btn-sm me-1 " id="edit_sp_patho_comment" onclick="edit_sp_patho_comment();" title="Edit" <?= (TRUE) ? '' : 'style="display: none;"'; ?> ><i class="fa-solid fa-marker"></i>Edit</a>
        <a hidden class="btn btn-outline-primary btn-sm me-1 " id="save_sp_patho_comment" onclick="save_sp_patho_comment();" title="Save"<?= (TRUE) ? '' : 'style="display: none;"'; ?> ><i class="fa-solid fa-floppy-disk"></i>Save</a>
        <?php endif; ?>
    </div>

        <?php if (!$isCurUserCust): ?>
       <button name="btnmove8000" id="btnmove8000" type="submit" class="btn btn-primary" <?= ( !$isCurrentPathoIsOwnerThisCase) ? "disabled" : ""; ?>>&nbsp;&nbsp;สั่งย้อมพิเศษ&nbsp;&nbsp;</button>
       <button hidden="" name="btnfinish8000" id="btnfinish8000" type="submit" class="btn btn-primary" <?= !($cur_request_sp_slide_status == 1) ? "disabled" : ""; ?>>&nbsp;&nbsp;ย้อมพิเศษเสร็จสิ้น&nbsp;&nbsp;</button>
       <?php endif; ?>
       <!--</form>-->

</div>
</div>