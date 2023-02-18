
<div class="row <?= $isBorder ? "border" : "" ?>"></div>
<h4 align="center"><b>รายการขอสไลดด์พิเศษ</b></h4>

<div class=" <?= $isBorder ? "border" : "" ?>">

    <table class="table table-bordered" id="spcimen_list_table2">
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
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecimenModa2"> Add Special Slide </button>
    <button id="refresh_spcimen_list2" class="btn btn-primary" >Refresh</button>
    <br><br>
</div>
<!--<button id="btntest" class="btn btn-primary" <?= true ? "hidden" : "" ?> >test</button>-->




<!-- Modal -->
<div class="modal fade" id="addSpecimenModa2" tabindex="-1" aria-labelledby="exampleaddSpecialSlideModal" aria-hidden="true">
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

