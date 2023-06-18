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
    <?php $is_SP = $patient[0]['request_sp_slide'] > 0;  ?>
    <?php $cur_request_sp_slide_status = $patient[0]['request_sp_slide'];  ?>
    <?php if (!$isCurUserCust): ?>
    <a class="btn btn-outline-primary btn-sm me-1 sp_slide_req_btn <?= $is_SP ? 'disabled':'';?>"  aria-disabled="true"  data-bs-toggle="modal"  data-bs-target="#addSpecimenModal2" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i></a>
    <a class="btn btn-outline-primary btn-sm me-1 sp_slide_req_btn <?= $is_SP ? 'disabled':'';?>"  id="refresh_spcimen_list2" title="Refresh" ><i class="fa-solid fa-rotate-right"></i></a>
    <a class="btn btn-outline-primary btn-sm me-1 sp_slide_req_btn <?= $is_SP ? 'disabled':'';?>"  data-bs-toggle="modal"  data-bs-target="#spcimen_tbl_list2" title="View/Detail" ><i class="fa-solid fa-table"></i></a>
    <?php endif; ?>
    
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
                <th >block</th>
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
                    <input name="price_for_specimen2" id="price_for_specimen2" type="text" class="form-control"    value=""   readonly>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> " >
                    <label for="comment_for_specimen2" class="form-label">comment</label>
                    <input name="comment_for_specimen2" id="comment_for_specimen2" type="text" class="form-control"    value=""   readonly>
                </div>
                
            <br>
            <span id="SP2_Scope">
                <input type="checkbox" class="form-check-input"  id="SP2_A" name="SP2_A" va="A"><label for="SP2_A"  class="form-check-label">A</label>
                <input type="checkbox" class="form-check-input"  id="SP2_B" name="SP2_B" va="B"><label for="SP2_B"  class="form-check-label">B</label>
                <input type="checkbox" class="form-check-input"  id="SP2_C" name="SP2_C" va="C"><label for="SP2_C"  class="form-check-label">C</label>
                <input type="checkbox" class="form-check-input"  id="SP2_D" name="SP2_D" va="D"><label for="SP2_D"  class="form-check-label">D</label>
                <input type="checkbox" class="form-check-input"  id="SP2_E" name="SP2_E" va="E"><label for="SP2_E"  class="form-check-label">E</label>
                <input type="checkbox" class="form-check-input"  id="SP2_F" name="SP2_F" va="F"><label for="SP2_F"  class="form-check-label">F</label>
                <input type="checkbox" class="form-check-input"  id="SP2_G" name="SP2_G" va="G"><label for="SP2_G"  class="form-check-label">G</label>
                <input type="checkbox" class="form-check-input"  id="SP2_H" name="SP2_H" va="H"><label for="SP2_H"  class="form-check-label">H</label>
                <input type="checkbox" class="form-check-input"  id="SP2_I" name="SP2_I" va="I"><label for="SP2_I"  class="form-check-label">I</label>
                <input type="checkbox" class="form-check-input"  id="SP2_J" name="SP2_J" va="J"><label for="SP2_J"  class="form-check-label">J</label>
                <input type="checkbox" class="form-check-input"  id="SP2_K" name="SP2_K" va="K"><label for="SP2_K"  class="form-check-label">K</label>
                <input type="checkbox" class="form-check-input"  id="SP2_L" name="SP2_L" va="L"><label for="SP2_L"  class="form-check-label">L</label>
                <input type="checkbox" class="form-check-input"  id="SP2_M" name="SP2_M" va="M"><label for="SP2_M"  class="form-check-label">M</label>
                <input type="checkbox" class="form-check-input"  id="SP2_N" name="SP2_N" va="N"><label for="SP2_N"  class="form-check-label">N</label>
                <input type="checkbox" class="form-check-input"  id="SP2_O" name="SP2_O" va="O"><label for="SP2_O"  class="form-check-label">O</label>
                <input type="checkbox" class="form-check-input"  id="SP2_P" name="SP2_P" va="P"><label for="SP2_P"  class="form-check-label">P</label>
                <input type="checkbox" class="form-check-input"  id="SP2_Q" name="SP2_Q" va="Q"><label for="SP2_Q"  class="form-check-label">Q</label>
                <input type="checkbox" class="form-check-input"  id="SP2_R" name="SP2_R" va="R"><label for="SP2_R"  class="form-check-label">R</label>
                <input type="checkbox" class="form-check-input"  id="SP2_S" name="SP2_S" va="S"><label for="SP2_S"  class="form-check-label">S</label>
                <input type="checkbox" class="form-check-input"  id="SP2_T" name="SP2_T" va="T"><label for="SP2_T"  class="form-check-label">T</label>
                <input type="checkbox" class="form-check-input"  id="SP2_U" name="SP2_U" va="U"><label for="SP2_U"  class="form-check-label">U</label>
                <input type="checkbox" class="form-check-input"  id="SP2_V" name="SP2_V" va="V"><label for="SP2_V"  class="form-check-label">V</label>
                <input type="checkbox" class="form-check-input"  id="SP2_W" name="SP2_W" va="W"><label for="SP2_W"  class="form-check-label">W</label>
                <input type="checkbox" class="form-check-input"  id="SP2_X" name="SP2_X" va="X"><label for="SP2_X"  class="form-check-label">X</label>
                <input type="checkbox" class="form-check-input"  id="SP2_Y" name="SP2_Y" va="Y"><label for="SP2_Y"  class="form-check-label">Y</label>
                <input type="checkbox" class="form-check-input"  id="SP2_Z" name="SP2_Z" va="Z"><label for="SP2_Z"  class="form-check-label">Z</label>
                
                <input type="checkbox" class="form-check-input"  id="SP2_A1" name="SP2_A1" va="A1"><label for="SP2_A1"  class="form-check-label">A1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_B1" name="SP2_B1" va="B1"><label for="SP2_B1"  class="form-check-label">B1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_C1" name="SP2_C1" va="C1"><label for="SP2_C1"  class="form-check-label">C1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_D1" name="SP2_D1" va="D1"><label for="SP2_D1"  class="form-check-label">D1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_E1" name="SP2_E1" va="E1"><label for="SP2_E1"  class="form-check-label">E1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_F1" name="SP2_F1" va="F1"><label for="SP2_F1"  class="form-check-label">F1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_G1" name="SP2_G1" va="G1"><label for="SP2_G1"  class="form-check-label">G1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_H1" name="SP2_H1" va="H1"><label for="SP2_H1"  class="form-check-label">H1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_I1" name="SP2_I1" va="I1"><label for="SP2_I1"  class="form-check-label">I1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_J1" name="SP2_J1" va="J1"><label for="SP2_J1"  class="form-check-label">J1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_K1" name="SP2_K1" va="K1"><label for="SP2_K1"  class="form-check-label">K1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_L1" name="SP2_L1" va="L1"><label for="SP2_L1"  class="form-check-label">L1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_M1" name="SP2_M1" va="M1"><label for="SP2_M1"  class="form-check-label">M1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_N1" name="SP2_N1" va="N1"><label for="SP2_N1"  class="form-check-label">N1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_O1" name="SP2_O1" va="O1"><label for="SP2_O1"  class="form-check-label">O1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_P1" name="SP2_P1" va="P1"><label for="SP2_P1"  class="form-check-label">P1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_Q1" name="SP2_Q1" va="Q1"><label for="SP2_Q1"  class="form-check-label">Q1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_R1" name="SP2_R1" va="R1"><label for="SP2_R1"  class="form-check-label">R1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_S1" name="SP2_S1" va="S1"><label for="SP2_S1"  class="form-check-label">S1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_T1" name="SP2_T1" va="T1"><label for="SP2_T1"  class="form-check-label">T1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_U1" name="SP2_U1" va="U1"><label for="SP2_U1"  class="form-check-label">U1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_V1" name="SP2_V1" va="V1"><label for="SP2_V1"  class="form-check-label">V1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_W1" name="SP2_W1" va="W1"><label for="SP2_W1"  class="form-check-label">W1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_X1" name="SP2_X1" va="X1"><label for="SP2_X1"  class="form-check-label">X1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_Y1" name="SP2_Y1" va="Y1"><label for="SP2_Y1"  class="form-check-label">Y1</label>
                <input type="checkbox" class="form-check-input"  id="SP2_Z1" name="SP2_Z1" va="Z1"><label for="SP2_Z1"  class="form-check-label">Z1</label>



            
            </span>
            
            
            <br>
                
                <div>
                    <br>
                    <button type="button" id="add_spcimen_list2" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>


        </div>
        <div class="modal-footer">        
        </div>
    </div>
</div>
<!-- End Modal -->


