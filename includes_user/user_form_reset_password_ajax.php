<div id="slide_prep_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div><b>Reset Password</b></div>
        <hr>


        <div class="row pt-3 mb-3 g-5 align-items-center">
            <div class="col-auto">
                <label for="username">ชื่อเข้าใช้(ห้ามมีเว้นวรรค์)</label>
                <input class="form-control" name="username_rst" type="text" id="username_rst" size="20" maxlength="10" readonly value="<?= (isset($user[0]['username']) ? $user[0]['username'] : ''); ?>">
                <span class="form-text">*กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร</span>
            </div>
            <div class="col-auto">
                <label for="password">รหัสผ่าน</label>
                <input class="form-control" name="password_rst" type="text" id="password_rst" size="20" maxlength="10" readonly value="changeme">
                <span class="form-text">*เมื่อล็อกอินครั้งแรก ด้วยพาสเวร์ด changeme ผู้ใช้จะถูกบังคับให้ตั้งพาสเวิร์ดของตัวเองใหม่ทันที</span>
            </div>
        </div>
        <?php
        $canClickReset = FALSE;
        if ($isCurUserAdmin) {
            //Admin can reset password for every one
            $canClickReset = TRUE;
        } elseif ($isCurUserGeneralNB && ($user[0]['ugroup_id'] == 5000 || $user[0]['ugroup_id'] == 5100)) {
            //non Admin cna reset password only customer
            $canClickReset = TRUE;
        } else {
            //
            $canClickReset = FALSE;
        }
        if(!$canClickReset){
            echo '<span style="color:red;">You have no authorize to reset password for this user.</span>';
        }
        ?>

        <div><button id="reset_password_btn" name="reset_password_btn" class="btn btn-primary  " <?= $canClickReset?'':'disabled'; ?> >Reset Password</button></div>

    </div>
</div>
