
<div class="row <?= $isBorder ? "border" : "" ?>">
    <h4 align="center"><b>รายการส่งตรวจ</b></h4>

    <div class=" <?= $isBorder ? "border" : "" ?>">

        <table class="table table-bordered" id="spcimen_list_table">
            <thead>
                <tr>
                    <th >Id</th>
                    <th >Patient Number</th>
                    <th >description</th>
                    <th >Price</th>
                    <th >Remark/comment</th>
                </tr>
            </thead>
            <tbody id="spcimen_list_table_body">
                <?php foreach ($billings as $billing): ?>
                    <tr>
                        <td ><?= $billing['id'] ?></td>
                        <td ><?= $billing['number'] ?></td>
                        <td ><?= $billing['description'] ?></td>
                        <td ><?= $billing['cost'] ?></td>
                        <td ><?= $billing['comment'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <botton id="refresh_spcimen_list" class="btn btn-primary" >Refresh</botton>
        <br><br>
    </div>
    
    <hr>


    <div class=" <?= $isBorder ? "border" : "" ?>">
        <label for="pspecimen_for_select" class="" >เลือกสิ่งส่งตรวจ</label>
        <select name="pspecimen_for_select" id="pspecimen_for_select" class="form-select"  >
            <!--            <option value="กรุณาเลือก">กรุณาเลือก</option>-->
            <?php foreach ($specimens as $specimen): ?>
                <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>    ?>
                <option value="<?= $specimen['id']; ?>" <?= $patient[0]['pspecimen_id'] == $specimen['id'] ? "selected" : ""; ?>   ><?= htmlspecialchars($specimen['specimen']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>



    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="price_for_specimen" class="form-label">ราคาค่าตรวจ(บาท)</label>
        <input name="price_for_specimen" id="price_for_specimen" type="text" class="form-control"    value=""   >
    </div>

    <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
        <label for="comment_for_specimen" class="form-label">comment</label>
        <input name="comment_for_specimen" id="comment_for_specimen" type="text" class="form-control"    value=""   >
    </div>
    <div>
        <br>
        <botton id="add_spcimen_list" class="btn btn-primary" >Add</botton>
        <botton id="btntest" class="btn btn-primary" hidden>test</botton>
    </div>
</div>
