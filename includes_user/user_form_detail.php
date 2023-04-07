<div id="slide_prep_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div><b>แก้ไขผู้ใช้งานระบบ</b></div>
        

            <input type="checkbox" id="userdetail_enable" name="userdetail_enable" value=""   >
            <label for="userdetail_enable">Edit or add user detail.</label><br>

            <form id="edituser" method="post" enctype="multipart/form-data">

            <div class="row pt-3 mb-3 g-5 align-items-center">
                <div class="col-3 col-md-2">
                    <label for="pre_name" class="form-label">คำนำหน้าชื่อ</label><!--span> *</span-->
                    <input name="pre_name" type="text" list="pre_name_list" class="form-control userdetail_input" id="pre_name"  value="<?= (isset($user[0]['pre_name']) ? $user[0]['pre_name'] : ''); ?>">
                    <datalist id="pre_name_list">
                        <option>นาย</option>
                        <option>นาง</option>
                        <option>นางสาว</option>
                    </datalist>
                </div>
                <div class="col-5 col-md-4">
                    <label for="name" class="form-label">ชื่อ</label><span  style="color:red"> *</span>
                    <input name="name" type="text" class="form-control  userdetail_input" id="name" required value="<?= (isset($user[0]['name']) ? $user[0]['name'] : ''); ?>">
                </div>
                <div class="col-5 col-md-4">
                    <label for="lastname" class="form-label">นามสกุล</label><span style="color:red"> *</span>
                    <input name="lastname" type="text" class="form-control  userdetail_input" id="lastname" required value="<?= (isset($user[0]['lastname']) ? $user[0]['lastname'] : ''); ?>">
                </div>
            </div>

            <div class="row mb-3 g-5 align-items-center">
                <div class="col-3 col-md-2">
                    <label for="pre_name_e" class="form-label">Name Title</label>
                    <input name="pre_name_e" type="text" list="pre_name_e_list" class="form-control  userdetail_input" id="pre_name_e" value="<?= (isset($user[0]['pre_name_e']) ? $user[0]['pre_name_e'] : ''); ?>">
                    <datalist id="pre_name_e_list">
                        <option>Mr.</option>
                        <option>Mrs.</option>
                        <option>Miss.</option>
                        <option>Ms.</option>
                    </datalist>
                </div>
                <div class="col-5 col-md-4">
                    <label for="name_e" class="form-label">Name</label>
                    <input name="name_e" type="text" class="form-control  userdetail_input" id="name_e" value="<?= (isset($user[0]['name_e']) ? $user[0]['name_e'] : ''); ?>">
                </div>
                <div class="col-5 col-md-4">
                    <label for="lastname_e" class="form-label">Lastname</label>
                    <input name="lastname_e" type="text" class="form-control userdetail_input" id="lastname" value="<?= (isset($user[0]['lastname_e']) ? $user[0]['lastname_e'] : ''); ?>">
                </div>
                <div class="col-3 col-md-2">
                    <label for="short_name" class="form-label">ชื่อย่อ</label>
                    <input name="short_name" type="text" class="form-control userdetail_input" id="short_name" value="<?= (isset($user[0]['short_name']) ? $user[0]['short_name'] : ''); ?>">
                </div>
            </div>

            <div class="row mb-3 g-5 align-items-center">
                <div class="col-3 col-md-2">
                    <label for="educational_bf" class="form-label">วุฒิการศึกษา</label>
                    <input name="educational_bf" list="educational_bf_list" type="text" class="form-control userdetail_input" id="educational_bf" value="<?= (isset($user[0]['educational_bf']) ? $user[0]['educational_bf'] : ''); ?>" placeholder="">
                    <datalist id="educational_bf_list">
                        <option>MD.</option>
                    </datalist>
                </div>
                <div class="col-3 col-md-2">
                    <label for="role" class="form-label">ตำแหน่ง</label>
                    <input name="role" list="role_list" type="text" class="form-control userdetail_input" id="role" value="<?= (isset($user[0]['role']) ? $user[0]['role'] : ''); ?>" placeholder="">
                    <datalist id="role_list">
                        <option>Pathologist</option>
                    </datalist>
                </div>
                <div class="col-4 col-md-3">
                    <?php $isUMobileBlank = !isset($user[0]['umobile']) || $user[0]['umobile'] == ""; ?>
                    <input type="checkbox" id="umobile_enable" name="umobile_enable" value="" <?= $isUMobileBlank ? "" : " checked " ?>  >
                    <label for="umobile" class="form-label">เบอร์โทรศัพท์</label><span> *</span>
                    <input name="umobile" type="text" class="form-control" id="umobile" required value="<?= (isset($user[0]['umobile']) ? $user[0]['umobile'] : ''); ?>">
                </div>
                <div class="col-4 col-md-3">
                    <?php $isUEmailBlank = !isset($user[0]['uemail']) || $user[0]['uemail'] == ""; ?>
                    <input type="checkbox" id="uemail_enable" name="uemail_enable" value="" <?= $isUEmailBlank ? "" : " checked " ?>  >
                    <label for="uemail" class="form-label">อีเมล</label><span> *</span>
                    <input name="uemail" type="email" class="form-control" id="uemail" size="40" required value="<?= (isset($user[0]['uemail']) ? $user[0]['uemail'] : ''); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6 col-md-4">
                    <label for="ugroup_id">กลุ่มผู้ใช้งาน</label><span style="color:red"> *</span>
                    <select class="form-select  userdetail_input" name="ugroup_id" <?= $canCurUserChangeUGroup ? "" : "disabled"; ?> >
                        <!--<option value="#">กรุณาเลือก</option>-->
                        <?php foreach ($ugroups as $ugroup) : ?>
                            <?php if ($canCurUserChangeUGroup): ?>
                                <option value="<?= htmlspecialchars($ugroup['id']); ?>" <?= (isset($user[0]['ugroup_id']) ? (($user[0]['ugroup_id'] == $ugroup['id']) ? "selected" : "") : ""); ?>><?= htmlspecialchars($ugroup['ugroup']); ?></option>
                            <?php else : ?>
                                <option value="<?= htmlspecialchars($ugroup['id']); ?>" <?= (isset($user[0]['ugroup_id']) ? (($user[0]['ugroup_id'] == $ugroup['id']) ? "selected" : "disabled") : ""); ?>><?= htmlspecialchars($ugroup['ugroup']); ?></option>    
                            <?php endif; ?> 
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-4">
                    <label for="uhospital_id">สถานที่ทำงาน</label><span style="color:red"> *</span>
                    <select name="uhospital_id" class="form-select  userdetail_input" <?= $isEditModePageForInitialDataOn || $canEditPatientInfo ? " disabled readonly " : "" ?>>
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
                <textarea class="form-control  userdetail_input" name="udetail" cols="60" rows="4" id="udetail"><?= (isset($user[0]['udetail']) ? htmlspecialchars($user[0]['udetail']) : ''); ?></textarea>
            </div>



            <hr>

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
                    <input class="form-control  userdetail_input" type="file" name="signature" id="signature">
                </div>
            </div>



            <div><button type="submit"  id="save" name="save" class="btn btn-primary  userdetail_input">Save</button></div>
        </form>
    </div>
</div>