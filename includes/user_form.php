<div class="row pt-3 mb-3 g-5 align-items-center">
    <div class="col-3 col-md-2">
        <label for="pre_name" class="form-label">คำนำหน้าชื่อ</label><span> *</span>
        <input name="pre_name" type="text" list="pre_name_list"  class="form-control" id="pre_name" required value="<?= (isset($user[0]['pre_name']) ? $user[0]['pre_name'] : ''); ?>">
        <datalist id="pre_name_list">
            <option>นาย</option>
            <option>นาง</option>
            <option>นางสาว</option>
        </datalist>
    </div>
    <div class="col-5 col-md-4">
        <label for="name" class="form-label">ชื่อ</label><span> *</span>
        <input name="name" type="text" class="form-control" id="name" required value="<?= (isset($user[0]['name']) ? $user[0]['name'] : ''); ?>">
    </div>
    <div class="col-5 col-md-4">
        <label for="lastname" class="form-label">นามสกุล</label><span> *</span>
        <input name="lastname" type="text" class="form-control" id="lastname" required value="<?= (isset($user[0]['lastname']) ? $user[0]['lastname'] : ''); ?>">
    </div>
</div>

<div class="row mb-3 g-5 align-items-center">
    <div class="col-3 col-md-2">
        <label for="pre_name_e" class="form-label">Name Title</label>
        <input name="pre_name_e" type="text" list="pre_name_e_list" class="form-control" id="pre_name_e" value="<?= (isset($user[0]['pre_name_e']) ? $user[0]['pre_name_e'] : ''); ?>">
        <datalist id="pre_name_e_list">
            <option>Mr.</option>
            <option>Mrs.</option>
            <option>Miss.</option>
            <option>Ms.</option>
        </datalist>
    </div>
    <div class="col-5 col-md-4">
        <label for="name_e" class="form-label">Name</label>
        <input name="name_e" type="text" class="form-control" id="name_e" value="<?= (isset($user[0]['name_e']) ? $user[0]['name_e'] : ''); ?>">
    </div>
    <div class="col-5 col-md-4">
        <label for="lastname_e" class="form-label">Lastname</label>
        <input name="lastname_e" type="text" class="form-control" id="lastname" value="<?= (isset($user[0]['lastname_e']) ? $user[0]['lastname_e'] : ''); ?>">
    </div>
</div>

<div class="row mb-3 g-5 align-items-center">
    <div class="col-3 col-md-2">
        <label for="educational_bf" class="form-label">วุฒิการศึกษา</label>
        <input name="educational_bf"  list="educational_bf_list"  type="text" class="form-control" id="educational_bf" value="<?= (isset($user[0]['educational_bf']) ? $user[0]['educational_bf'] : ''); ?>" placeholder="MD.">
        <datalist id="educational_bf_list">
            <option>MD.</option>
        </datalist>
    </div>
    <div class="col-3 col-md-2">
        <label for="role" class="form-label">ตำแหน่ง</label>
        <input name="role" list="role_list" type="text" class="form-control" id="role" value="<?= (isset($user[0]['role']) ? $user[0]['role'] : ''); ?>" placeholder="Pathologist">
        <datalist id="role_list">
            <option>Pathologist</option>
        </datalist>
    </div>
    <div class="col-4 col-md-3">
        <label for="umobile" class="form-label">เบอร์โทรศัพท์</label><span> *</span>
        <input name="umobile" type="text" class="form-control" id="umobile" required value="<?= (isset($user[0]['umobile']) ? $user[0]['umobile'] : ''); ?>">
    </div>
    <div class="col-4 col-md-3">
        <label for="uemail" class="form-label">อีเมล</label><span> *</span>
        <input name="uemail" type="email" class="form-control" id="uemail" size="40" required value="<?= (isset($user[0]['uemail']) ? $user[0]['uemail'] : ''); ?>">
    </div>
</div>

<div class="row mb-3">
    <div class="col-6 col-md-4">
        <label for="ugroup_id">กลุ่มผู้ใช้งาน</label><span> *</span>
        <select class="form-select" name="ugroup_id">
            <!--<option value="#">กรุณาเลือก</option>-->
            <?php foreach ($ugroups as $ugroup) : ?>
                <option value="<?= htmlspecialchars($ugroup['id']); ?>" <?= (isset($user[0]['ugroup_id']) ? (($user[0]['ugroup_id'] == $ugroup['id']) ? "selected" : "") : ""); ?>><?= htmlspecialchars($ugroup['ugroup']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-6 col-md-4">
        <label for="uhospital_id">สถานที่ทำงาน</label><span> *</span>
        <select name="uhospital_id" class="form-select" <?= $isEditModePageForInitialDataOn || $canEditPatientInfo ? " disabled readonly " : "" ?>>
            <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
            <?php foreach ($hospitals as $hospital) : ?>
                <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> 
                ?>
                <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= (isset($user[0]['uhospital_id']) ? (($user[0]['uhospital_id'] == $hospital['id']) ? "selected" : "") : ""); ?>><?= htmlspecialchars($hospital['hospital']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="udetail">รายละเอียด</label>
    <textarea class="form-control" name="udetail" cols="60" rows="4" id="udetail"><?= (isset($user[0]['udetail']) ? htmlspecialchars($user[0]['udetail']) : ''); ?></textarea>
</div>

<div class="row mb-3 g-3 align-items-center">
    <div class="col-auto">
        <label for="username">ชื่อเข้าใช้</label>
        <input class="form-control" name="username" type="text" id="username" size="20" maxlength="10" value="<?= (isset($user[0]['username']) ? $user[0]['username'] : ''); ?>">
        <span class="form-text">*กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร</span>
    </div>
    <!-- show when edit  -->
    <?php if (isset($user[0]['password'])) : ?>
        <div class="col-auto">
            <label for="old_password">รหัสผ่านเก่า</label>
            <input class="form-control" name="old_password" type="password" id="old_password" size="20" maxlength="10">
            <span class="form-text">กรณีเปลี่ยนรหัสผ่านใหม่(ถ้าไม่เปลี่ยนไม่ต้องใส่)</span>
        </div>
    <?php endif; ?>
    <div class="col-auto">
        <label for="password"><?= (isset($user[0]['password']) ? "ตั้งรหัสผ่านใหม่" : "รหัสผ่าน"); ?></label><?= (isset($user[0]['password']) ? "" : "<span> *</span>"); ?>
        <input class="form-control" name="password" type="password" id="password" size="20" maxlength="10">
        <span class="form-text"><?= (isset($user[0]['password']) ? "เปลี่ยนรหัสผ่านใหม่(ถ้าไม่เปลี่ยนไม่ต้องใส่)" : "ตั้งรหัสผ่าน"); ?></span>
    </div>
    <div class="col-auto">
        <label for="set_password_confirm"><?= (isset($user[0]['password']) ? "ยืนยันรหัสผ่านผ่านใหม่" : "ยืนยันรหัสผ่าน"); ?></label><?= (isset($user[0]['password']) ? "" : "<span> *</span>"); ?>
        <input class="form-control" name="set_password_confirm" type="password" id="set_password_confirm" size="20" maxlength="10">
        <span class="form-text"><?= (isset($user[0]['password']) ? "ยีนยันรหัสผ่านใหม่(ถ้าไม่เปลี่ยนไม่ต้องใส่)" : "ยืนยันรหัสผ่านอีกรอบ"); ?></span>
    </div>
</div>

<div class="row mb-3 g-3 align-items-center">
    <?php if (isset($user[0]['signature_file'])) : ?>
        <?php if (!$user[0]['signature_file'] == "" && !is_null($user[0]['signature_file'])) : ?>

            <div class="card border-light mb-3" style="max-width: 18rem;">
                <div class="card-body text-center">
                    <img src="<?= $user[0]['signature_file'] ?>" class="img-fluid" style="max-height: 150px;">

                    <a id="signature-delete" href="user_signature_del.php?id=<?= $_GET['id']; ?>" class="btn btn-danger"><i class="fa-solid fa-trash-can me-2"></i>delete</a>

                </div>
            </div>

        <?php endif; ?>
    <?php endif; ?>
    <div class="col-4">
        <label for="signature" class="form-label">รูปลายเซ็น</label>
        <input class="form-control" type="file" name="signature" id="signature">
    </div>
</div>