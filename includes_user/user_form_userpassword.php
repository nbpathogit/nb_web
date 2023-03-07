
<div id="slide_prep_section" class="container-fluid pt-4 px-4">
    <div class="bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div><b>แก้ไขยูเซอร์เนมและพาสเวอร์ด</b></div>
        


            <hr>

            <?php $isUsernameBlank = !isset($user[0]['username']) || $user[0]['username'] == ""; ?>
            <input type="checkbox" id="username_enable" name="username_enable" value=""   >
            
            <form id="" method="post" >
            <label for="username_enable">Edit or add user/password.</label><br>

            <div class="row mb-3 g-3 align-items-center">
                <div class="col-auto">
                    <label for="username">ชื่อเข้าใช้</label>
                    <input class="form-control" name="username" type="text" id="username" size="20" maxlength="10" value="<?= (isset($user[0]['username']) ? $user[0]['username'] : ''); ?>">
                    <span class="form-text">*กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร</span>
                </div>
                <?php if ($canChangePassword): ?>
                    <!-- show when edit  -->
                    <?php if (isset($user[0]['password'])) : ?>
                        <?php if (!($user[0]['password'] == "")) : ?>
                            <div class="col-auto">
                                <label for="old_password">รหัสผ่านเก่า</label>
                                <input class="form-control" name="old_password" type="password" id="old_password" size="20" maxlength="10">
                                <span class="form-text">กรณีเปลี่ยนรหัสผ่านใหม่(ถ้าไม่เปลี่ยนไม่ต้องใส่)</span>
                            </div>
                        <?php endif; ?>
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
                <?php endif; ?>
            </div>

            <div><button id="save_userpass" name="save_userpass" class="btn btn-primary">Save</button></div>
        </form>
    </div>
</div>
