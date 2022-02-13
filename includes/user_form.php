<div class="row mb-3 g-5 align-items-center">
    <div class="col-auto">
        <label for="name" class="form-label">ชื่อ</label><span> *</span>
        <input name="name" type="text" class="form-control" id="name" required value="<?= (isset($user[0]['name']) ? $user[0]['name'] : ''); ?>">
    </div>
    <div class="col-auto">
        <label for="lastname" class="form-label">นามสกุล</label><span> *</span>
        <input name="lastname" type="text" class="form-control" id="lastname" required value="<?= (isset($user[0]['lastname']) ? $user[0]['lastname'] : ''); ?>">
    </div>
    <div class="col-auto">
        <label for="umobile" class="form-label">เบอร์โทรศัพท์</label><span> *</span>
        <input name="umobile" type="text" class="form-control" id="umobile" required value="<?= (isset($user[0]['umobile']) ? $user[0]['umobile'] : ''); ?>">
    </div>
    <div class="col-auto">
        <label for="uemail" class="form-label">อีเมล</label><span> *</span>
        <input name="uemail" type="email" class="form-control" id="uemail" size="40" required value="<?= (isset($user[0]['uemail']) ? $user[0]['uemail'] : ''); ?>">
    </div>
</div>

<div class="mb-3">
    <label for="ugroup_id">กลุ่มผู้ใช้งาน</label><span> *</span>
    <select class="form-select" name="ugroup_id">
        <!--<option value="#">กรุณาเลือก</option>-->
        <?php foreach ($ugroups as $ugroup) : ?>
            <option value="<?= htmlspecialchars($ugroup['id']); ?>" <?= (isset($user[0]['ugroup_id']) ? (($user[0]['ugroup_id'] == $ugroup['id']) ? "selected" : "") : ""); ?>><?= htmlspecialchars($ugroup['ugroup']); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="uhospital_id">สถานที่ทำงาน</label><span> *</span>
    <select name="uhospital_id" class="form-select" <?= $canEditModePage || $canEditPatientInfo ? " disabled readonly " : "" ?>>
        <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
        <?php foreach ($hospitals as $hospital) : ?>
            <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
            <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= (isset($user[0]['uhospital_id']) ? (($user[0]['uhospital_id'] == $hospital['id']) ? "selected" : "") : ""); ?>><?= htmlspecialchars($hospital['hospital']); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="udetail">รายละเอียด</label>
    <textarea class="form-control" name="udetail" cols="60" rows="4" id="udetail"><?= (isset($user[0]['udetail']) ? htmlspecialchars($user[0]['udetail']) : ''); ?></textarea>
</div>

<div class="row mb-3 g-3 align-items-center">
    <div class="col-auto">
        <label for="username">ชื่อเข้าใช้</label>
        <input class="form-control" name="username" type="text" id="username" size="20" maxlengtd="10" value="<?= (isset($user[0]['username']) ? $user[0]['username'] : ''); ?>">
        <span class="form-text">*กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร</span>
    </div>
    <div class="col-auto">
        <label for="password"><?= (isset($user[0]['password']) ? "ตั้งรหัสผ่านใหม่" : "รหัสผ่าน"); ?></label><span> *</span>
        <input class="form-control" name="password" type="password" id="password" size="20" maxlengtd="10" value="<?= (isset($user[0]['password']) ? $user[0]['password'] : '');?>">
        <span class="form-text"><?= (isset($user[0]['password']) ? "เปลี่ยนรหัสผ่านใหม่(ถ้าไม่เปลี่ยนไม่ต้องแก้ไข)" : "ตั้งรหัสผ่าน"); ?></span>
    </div>
    <div class="col-auto">
        <label for="set_password_confirm"><?= (isset($user[0]['password']) ? "ยืนยันรหัสผ่านผ่านใหม่" : "ยืนยันรหัสผ่าน"); ?></label><span> *</span>
        <input class="form-control" name="set_password_confirm" type="password" id="" size="20" maxlengtd="10" value="<?= (isset($user[0]['password']) ? $user[0]['password'] : '');?>">
        <span class="form-text"><?= (isset($user[0]['password']) ? "ยีนยันรหัสผ่านใหม่(ถ้าไม่เปลี่ยนไม่ต้องแก้ไข)" : "ยืนยันรหัสผ่านอีกรอบ"); ?></span>
    </div>
</div>