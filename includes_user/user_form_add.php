<div id="slide_prep_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div><b>เพิ่มผู้ใช้งานระบบ</b></div>


        <form id="edituser" method="post" enctype="multipart/form-data">

            <div class="row mb-3">
                <div class="col-6 col-md-4">
                    <label for="ugroup_id_user_add">กลุ่มผู้ใช้งาน</label><span style="color:red"> *</span>
                    <select class="form-select  " name="ugroup_id_user_add" id="ugroup_id_user_add" <?= $canCurUserChangeUGroup ? "" : "disabled"; ?> >
                        <!--<option value="#">กรุณาเลือก</option>-->
                        <?php foreach ($ugroups as $ugroup) : ?>
                            <option value="<?= htmlspecialchars($ugroup['id']); ?>" ><?= htmlspecialchars($ugroup['ugroup']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-6 col-md-4">
                    <label for="uhospital_id_user_add">โรงพยาบาล</label><span style="color:red"> *</span>
                    <select name="uhospital_id_user_add" id="uhospital_id_user_add" class="form-select  " <?= $isEditModePageForInitialDataOn || $canEditPatientInfo ? " disabled readonly " : "" ?>>
                        <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
                        <?php foreach ($hospitals as $hospital) : ?>
                            <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> 
                            ?>
                            <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= (isset($user[0]['uhospital_id']) ? (($user[0]['uhospital_id'] == $hospital['id']) ? "selected" : "") : ""); ?>><?= htmlspecialchars($hospital['hospital']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>




            <div class="row pt-3 mb-3 g-5 align-items-center">
                <div class="col-3 col-md-2">
                    <label for="pre_name" class="form-label">คำนำหน้าชื่อ</label><!--span> *</span-->
                    <input name="pre_name" type="text" list="pre_name_list" class="form-control " id="pre_name"  value="<?= (isset($user[0]['pre_name']) ? $user[0]['pre_name'] : ''); ?>">
                    <datalist id="pre_name_list">
                        <?php   require 'includes/prenameOption.php'; ?>
                    </datalist>
                </div>
                <div class="col-5 col-md-4">
                    <label for="name" class="form-label">ชื่อ</label><span  style="color:red"> *</span>
                    <input name="name" type="text" class="form-control  " id="name" required value="<?= (isset($user[0]['name']) ? $user[0]['name'] : ''); ?>">
                </div>
                <div class="col-5 col-md-4">
                    <label for="lastname" class="form-label">นามสกุล</label><span style="color:red"> </span>
                    <input name="lastname" type="text" class="form-control  " id="lastname"  value="<?= (isset($user[0]['lastname']) ? $user[0]['lastname'] : ''); ?>">
                </div>
            </div>

            <div class="row pt-3 mb-3 g-5 align-items-center">
                <div class="col-auto">
                    <label for="username">ชื่อเข้าใช้(ห้ามมีเว้นวรรค์)</label>
                    <input class="form-control" name="username" type="text" id="username_add" size="20" maxlength="10" value="">
                    <span class="form-text">*กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร</span>
                </div>
                <div class="col-auto">
                    <label for="password">รหัสผ่าน</label>
                    <input class="form-control" name="password" type="text" id="password_add" size="20" maxlength="10" value="changeme">
                    <span class="form-text">*กรุณาเป็นภาษาอังกฤษ 5 ตัวอักษรขึ้นไป</span>
                </div>
            </div>

            <div><button id="add" name="add" class="btn btn-primary  ">Add</button></div>
            
            <input type="hidden" name="create_by" value="<?= $cur_user->name .' '.$cur_user->lastname ?>">
        </form>
    </div>
</div>