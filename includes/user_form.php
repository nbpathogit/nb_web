
        <div>

            <label for="name">ชื่อ</label>
            <input name="name" type="text"  id="name" >
            <span>*</span>

        </div>
        <div>

            <label for="lastname">นามสกุล</label>
            <input name="lastname" type="text"  id="lastname">
            <span>*</span>

        </div>
        <div>

            <label for="umobile">เบอร์โทรศัพย์</label>
            <input name="umobile" type="text"  id="umobile" size="50">
            <span  >*</span>
        </div>

        <div>

            <label for="uemail">อีเมล</label>
            <input name="uemail" type="text" id="uemail" size="50">
            <span>*</span>
        </div>
        <div>
            <label for="ugroup_id">กลุ่มผู้ใช้งาน</label>
            <select name="ugroup_id" >
                <option value="#">กรุณาเลือก</option>
                <?php foreach ($ugroups as $ugroup): ?>
                    <?php //Target Format : <option value="1">เจ้าหน้าที่ ร.พ.</option> ?>
                    <option value="<?= htmlspecialchars($ugroup['id']); ?>"><?= htmlspecialchars($ugroup['ugroup']); ?></option>
                <?php endforeach; ?>    
            </select> 
            <span>*</span>

        </div>
        <div>
            <label for="uhospital_id">สถานที่ทำงาน</label>
            <select name="uhospital_id" >
                <option value="#">กรุณาเลือก</option>
                <?php foreach ($hospitals as $hospital): ?>
                    <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                    <option value="<?= htmlspecialchars($hospital['id']); ?>"><?= htmlspecialchars($hospital['hospital']); ?></option>
                <?php endforeach; ?>
            </select>                                        
            <span  >*</span>
        </div>
        <div>
            <label for="udetail">รายละเอียด</label>
            <textarea name="udetail" cols="60" rows="4" id="udetail"></textarea>
        </div>
        <div>
            <label for="username">ชื่อเข้าใช้</label>
            <input name="username" type="text"  id="username" size="20" maxlengtd="10">
            *กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร 
        </div>
        <div>
            <label for="password">รหัสผ่าน</label>
            <input name="password" type="password"  id="password" size="20" maxlengtd="10">
            <span>*</span>
        </div>
        <div>
            <label for="user_password2">ยืนยันรหัสผ่าน</label>
            <input name="user_password2" type="password" id="user_password2" size="20" maxlengtd="10">
            <span>*</span>
        </div>



