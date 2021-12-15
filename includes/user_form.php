<div class="row mb-3 g-5 align-items-center">
    <div class="col-auto">
        <label for="name" class="form-label">ชื่อ</label><span> *</span>
        <input name="name" type="text" class="form-control" id="name">
    </div>
    <div class="col-auto">
        <label for="lastname" class="form-label">นามสกุล</label><span> *</span>
        <input name="lastname" type="text" class="form-control" id="lastname">
    </div>
    <div class="col-auto">
        <label for="umobile" class="form-label">เบอร์โทรศัพท์</label><span> *</span>
        <input name="umobile" type="text" class="form-control" id="umobile">
    </div>
    <div class="col-auto">
        <label for="uemail" class="form-label">อีเมล</label><span> *</span>
        <input name="uemail" type="email" class="form-control" id="uemail" size="40">
    </div>
</div>

<div class="mb-3">
    <label for="ugroup_id">กลุ่มผู้ใช้งาน</label><span> *</span>
    <select class="form-select" name="ugroup_id">
        <option value="#">กรุณาเลือก</option>
        <?php foreach ($ugroups as $ugroup) : ?>
            <?php //Target Format : <option value="1">เจ้าหน้าที่ ร.พ.</option> 
            ?>
            <option value="<?= htmlspecialchars($ugroup['id']); ?>"><?= htmlspecialchars($ugroup['ugroup']); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="uhospital_id">สถานที่ทำงาน</label><span> *</span>
    <select class="form-select" name="uhospital_id">
        <option value="#">กรุณาเลือก</option>
        <?php foreach ($hospitals as $hospital) : ?>
            <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> 
            ?>
            <option value="<?= htmlspecialchars($hospital['id']); ?>"><?= htmlspecialchars($hospital['hospital']); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="udetail">รายละเอียด</label>
    <textarea class="form-control" name="udetail" cols="60" rows="4" id="udetail"></textarea>
</div>

<div class="row mb-3 g-3 align-items-center">
    <div class="col-auto">
        <label for="username">ชื่อเข้าใช้</label>
        <input class="form-control" name="username" type="text" id="username" size="20" maxlengtd="10">
        <span class="form-text">*กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร</span>
    </div>
    <div class="col-auto">
        <label for="password">รหัสผ่าน</label><span> *</span>
        <input class="form-control" name="password" type="password" id="password" size="20" maxlengtd="10">
        <span class="form-text">ตั้งรหัสผ่าน</span>
    </div>
    <div class="col-auto">
        <label for="user_password2">ยืนยันรหัสผ่าน</label><span> *</span>
        <input class="form-control" name="user_password2" type="password" id="user_password2" size="20" maxlengtd="10">
        <span class="form-text">ยืนยันรหัสผ่านอีกรอบ</span>
    </div>
</div>