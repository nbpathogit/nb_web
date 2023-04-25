<!--slide1-->
<hr id="">
<div class="row <?= $isBorder ? "border" : "" ?>"></div>
<p align="left">
    <b>รายการสิ่งส่งตรวจ:</b>
    <span id="spcimen_list1" style="font-size:20px">
        <span class="badge rounded-pill bg-primary" id="">Aaaaaa</span>
        <span class="badge rounded-pill bg-primary" id="">Bbbbbb</span>
        <span class="badge rounded-pill bg-primary" id="">Cccccc</span>
    </span>  
    <!--<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecimenModal1"> Add Specimen </button>-->
    <!--<button id="refresh_spcimen_list1" class="btn btn-primary" >Refresh</button>-->
    <?php if (!$isCurUserCust): ?>
        <a class="btn btn-outline-primary btn-sm me-1 " data-bs-toggle="modal"  data-bs-target="#addSpecimenModal1" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i></a>
        <a class="btn btn-outline-primary btn-sm me-1 "  id="refresh_spcimen_list1" title="Refresh" ><i class="fa-solid fa-rotate-right"></i></a>
        <a class="btn btn-outline-primary btn-sm me-1 "  data-bs-toggle="modal"  data-bs-target="#spcimen_tbl_list1" title="View/Detail" ><i class="fa-solid fa-table"></i></a>
    <?php endif; ?>

</p>

<?php $dbgshowTable1 = false; ?>
<?php if (!($dbgshowTable1)) : ?>
    <!-- Modal -->
    <div class="modal fade" id="spcimen_tbl_list1" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">เลือกสิ่งส่งตรวจ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <?php endif; ?>


                <div class=" <?= $isBorder ? "border" : "" ?>">

                    <table class="table table-bordered border-dark" id="spcimen_list_table1">
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
                        <tbody id="spcimen_list_table_body1">
                            <?php foreach ($billings as $billing): ?>
                                <tr>
                                    <td ><?= $billing['id'] ?></td>
                                    <td ><?= $billing['number'] ?></td>
                                    <td ><?= $billing['code_description'] ?></td>
                                    <td ><?= $billing['description'] ?></td>
                                    <td ><?= $billing['cost'] ?></td>
                                    <td ><?= $billing['comment'] ?></td>
                                    <td >
                                        <a  billid="<?= $billing['id'] ?>" onclick="delbill1(<?= $billing['id'] . ',' . $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
                <button id="btntest" class="btn btn-primary" <?= true ? "hidden" : "" ?> >test</button>






                <?php if (!($dbgshowTable1)) : ?>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>









<!-- Modal -->
<div class="modal fade" id="addSpecimenModal1" tabindex="-1" aria-labelledby="exampleAddSpecimenModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">เลือกสิ่งส่งตรวจ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">



                <div class=" <?= $isBorder ? "border" : "" ?>">
                    <label for="phospital_select_for_price1" class="">เลือกโรงพยาบาล</label>
                    <select name="phospital_select_for_price1" id="phospital_select_for_price1" class="form-select" <?= ( true ) ? "" : " disabled readonly " ?> >
                        <option value="-1">ใส่ข้อมูลเอง</option>
                        <?php foreach ($hospitals as $hospital): ?>
                            <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                            <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= ($patient[0]['phospital_id'] == ($hospital['id'])) ? "selected" : ""; ?> ><?= ($hospital['id'] == 0) ? "โรงพยาบาลราคามาตรฐาน" : $hospital['hospital']; ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <div class=" <?= $isBorder ? "border" : "" ?>">
                    <label for="pspecimen_for_select1" class="" >เลือกสิ่งส่งตรวจ</label>
                    <select name="pspecimen_for_select1" id="pspecimen_for_select1" class="form-select"  >
                        <!--            <option value="กรุณาเลือก">กรุณาเลือก</option>-->
                        <?php foreach ($specimens as $specimen): ?>
                            <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>    ?>

                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="specimen_num1" class="form-label">รหัสรายการ</label>
                    <input name="specimen_num1" id="specimen_num1" type="text" class="form-control"    value=""   readonly>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="specimen_for_specimen1" class="form-label">รายการส่งตรวจ</label>
                    <input name="specimen_for_specimen1" id="specimen_for_specimen1" type="text" class="form-control"    value=""   readonly>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="price_for_specimen1" class="form-label">ราคาค่าตรวจ(บาท)</label>
                    <input name="price_for_specimen1" id="price_for_specimen1" type="text" class="form-control"    value=""   readonly>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> " >
                    <label for="comment_for_specimen1" class="form-label">comment</label>
                    <input name="comment_for_specimen1" id="comment_for_specimen1" type="text" class="form-control"    value=""   readonly>
                </div>
                <div>
                    <br>
                    <button type="button" id="add_spcimen_list1" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>








        </div>
        <div class="modal-footer">

        </div>
    </div>
</div>


<script>


</script>