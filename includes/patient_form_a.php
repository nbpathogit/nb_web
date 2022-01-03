<h4 align="center"><b>ข้อมูลผู้ป่วย</b></h4>


<div class="row mb-3">
    <label for="pnum" class="col-sm-2 col-form-label">เลขที่ผู้ป่วย</label>
    <div class="col-sm-10">
        <input name="pnum" type="text" id="pnum" class="form-control" placeholder=""  <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?>  value="<?= $patients[0]['pnum']; ?>">
    </div>
      </div>



<div class="row mb-3">

    <label for="pname" class="col-sm-2 col-form-label">ชื่อผู้ป่วย</label>
    <div class="col-sm-10">
        <input name="pname" type="text" class="form-control" id="pname" placeholder="" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?> value="<?= $patients[0]['pname']; ?>">
    </div>
</div>

            

<div class="row mb-3">

    <label for="plastname" class="col-sm-2 col-form-label">นามสกุล</label>
    <div class="col-sm-10">
        <input name="plastname" type="text" class="form-control" id="plastname" placeholder="" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?> value="<?= $patients[0]['plastname']; ?>">
    </div>
</div>

<div class="row mb-3">

    <label for="pgender" class="col-sm-2 col-form-label">เพศ</label>
    <div class="col-sm-10">
        <select name="pgender" class="form-select" id="pgender" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?>>
                <option value="กรุณาเลือก">กรุณาเลือก</option>
            <option value="ชาย" <?= ($patients[0]['pgender'] === "ชาย") ? "selected" : ""; ?> >ชาย</option>
            <option value="หญิง" <?= ($patients[0]['pgender'] === "หญิง") ? "selected" : ""; ?> >หญิง</option>
            </select>
    </div>
</div>

<div class="row mb-3">

    <label for="pedge" class="col-sm-2 col-form-label">อายุ(ปี)</label>
    <div class="col-sm-10">
        <input name="pedge" type="text" class="form-control" class="" id="pedge"  placeholder="" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?> value="<?= $patients[0]['pedge']; ?>">

    </div>
</div>


<div class="row mb-3">

    <label for="" class="col-sm-2 col-form-label">วันที่รับ</label>
    <div class="col-sm-10">
        <input name="import_date" class="form-control" type="date" class="" id="import_date" placeholder="" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?> value="<?= $patients[0]['import_date']; ?>">
    </div>
</div>

<div class="row mb-3">

    <label for="" class="col-sm-2 col-form-label">เลขที่โรงพยาบาล</label>
    <div class="col-sm-10">
        <input name="phospital_num" type="text" class="form-control" id=""  placeholder="" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?> value="<?= $patients[0]['phospital_num']; ?>" >
    </div>
</div>

<div class="row mb-3">

    <label for="phospital_id" class="col-sm-2 col-form-label">โรงพยาบาล</label>
    <div class="col-sm-10">
        <select name="phospital_id" class="form-select" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?>>
            <option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>
            <?php foreach ($hospitals as $hospital): ?>
                <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= ($patients[0]['phospital_id'] == ($hospital['id'])) ? "selected" : ""; ?> ><?= htmlspecialchars($hospital['hospital']); ?></option>
                <?php endforeach; ?>
            </select>
    </div>
</div>



<div class="row mb-3">

    <label for="" class="col-sm-2 col-form-label">ความสำคัญ</label>
    <div class="col-sm-10">

        <select name="priority_id" class="form-select" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?>>
            
            <?php foreach ($prioritys as $priority): ?>
                <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                <option value="<?= htmlspecialchars($priority['id']); ?>"  <?= $patients[0]['priority_id'] == $priority['id'] ? "selected" : ""; ?> ><?= htmlspecialchars($priority['priority']); ?></option>
                <?php endforeach; ?>
            </select>
        
        
        
        
    </div>
</div>

<div class="row mb-3">

    <label for="pclinician_id" class="col-sm-2 col-form-label">แพทย์ผู้ส่ง</label>
    <div class="col-sm-10">
        <select name="pclinician_id" class="form-select" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?>>
            <option value="กรุณาเลือก" selected>กรุณาเลือก</option>
            <?php foreach ($clinicians as $clinician): ?>
                <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                <option value="<?= htmlspecialchars($clinician['id']); ?>"  <?= $patients[0]['pclinician_id'] == $clinician['id'] ? "selected" : ""; ?> ><?= htmlspecialchars($clinician['name']); ?></option>
            <?php endforeach; ?>
        </select>                                    
    </div>
</div>



<div align="" class="row mb-3">
    <label for="uspecimen_id" class="col-sm-2 col-form-label" >สิ่งส่งตรวจ</label>
    <div class="col-sm-10">
        <select name="uspecimen_id" class="form-select" <?= $isDisableEditPatientInfo ? " disabled readonly " : ""?>>
            <option value="กรุณาเลือก">กรุณาเลือก</option>
            <?php foreach ($specimens as $specimen): ?>
                <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>    ?>
                <option value="<?= $specimen['id']; ?>" <?= $patients[0]['pspecimen_id'] == $specimen['id'] ? "selected" : ""; ?>   ><?= htmlspecialchars($specimen['specimen']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

            


