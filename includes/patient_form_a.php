

    <h4 class="mb-3">
        เพิ่มข้อมูลผู้ป่วย
</h4>

    <div align="">
        <span>
            <label for="pnum">เลขที่ผู้ป่วย</label>
            <input name="pnum" type="text" id="pnum" size="20" maxlength="20" value="<?= $patients[0]['pnum']; ?>">
        </span>
    </div>

    <div align=""> 
        <span class="">
            <label for="plabnum">LAB Number</label>
            <input name="plabnum" type="text" class="" id="plabnum" size="20" maxlength="30" value="<?= $patients[0]['plabnum']; ?>">
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="pname">ชื่อผู้ป่วย</label>
            <input name="pname" type="text" class="" id="pname" size="30" maxlength="30" value="<?= $patients[0]['pname']; ?>">
        </span>
    </div>

    <div alignleft"><span class="">นามสกุล</span></div>

    <div align="">
        <span class="">
            <label for="plastname">นามสกุล</label>
            <input name="plastname" type="text" class="" id="plastname" size="30" maxlength="30" value="<?= $patients[0]['plastname']; ?>">
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="pgender">เพศ</label>
            <select name="pgender" class="" id="pgender">
                <option value="กรุณาเลือก">กรุณาเลือก</option>
                <option value="ชาย" <?= ($patients[0]['pgender'] === "ชาย") ? "selected" : ""; ?> >ชาย</option>
                <option value="หญิง" <?= ($patients[0]['pgender'] === "หญิง") ? "selected" : ""; ?> >หญิง</option>
            </select>
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="pedge">อายุ</label>
            <input name="pedge" type="text" class="" id="pedge" size="5" maxlength="3" value="<?= $patients[0]['pedge']; ?>">
            ปี
        </span>
    </div>


    <div align="">
        <span class="">
            <label for="">วันที่รับ</label>
            <input name="import_date" type="text" class="" id="import_date" value="<?= $patients[0]['import_date']; ?>">
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="phospital_id">โรงพยาบาล</label>
            <select name="phospital_id" class="">
                <option value="กรุณาเลือก">กรุณาเลือก</option> 
                <?php foreach ($hospitals as $hospital): ?>
                    <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                    <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= ($patients[0]['phospital_id'] == ($hospital['id'])) ? "selected" : ""; ?> ><?= htmlspecialchars($hospital['hospital']); ?></option>
                <?php endforeach; ?>
            </select>                                    
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="">เลขที่โรงพยาบาล</label>
            <input name="phospital_num" type="text" class="" id="" size="20" maxlength="20" value="<?= $patients[0]['phospital_num']; ?>" >
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="">ความสำคัญ</label>
            <label> 
                <input name="priority_id" type="radio" value="<?= htmlspecialchars($prioritys[0]['id']); ?>" <?= ($patients[0]['priority_id'] == ($prioritys[0]['id'])) ? "checked" : ""; ?>  >
            </label>
            <?= htmlspecialchars($prioritys[0]['priority']); ?>
            <label>
                <input name="priority_id" type="radio" value="<?= htmlspecialchars($prioritys[1]['id']); ?>" <?= ($patients[0]['priority_id'] == ($prioritys[1]['id'])) ? "checked" : ""; ?> >
            </label>
            <?= htmlspecialchars($prioritys[1]['priority']); ?>
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="pclinician_id">แพทย์ผู้ส่ง</label>
            <select name="pclinician_id" class="">
                <option value="กรุณาเลือก" selected>กรุณาเลือก</option>
                <?php foreach ($clinicians as $clinician): ?>
                    <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                    <option value="<?= htmlspecialchars($clinician['id']); ?>"  <?= $patients[0]['pclinician_id'] == $clinician['id'] ? "selected" : ""; ?> ><?= htmlspecialchars($clinician['name']); ?></option>
                <?php endforeach; ?>
            </select>                                    
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="ppathologist_id">พยาธิแพทย์</label>
            <select name="ppathologist_id" >
                <option value="">กรุณาเลือก</option>
                <?php foreach ($userPathos as $user): ?>
                    <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                    <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patients[0]['ppathologist_id'] == htmlspecialchars($user['id']) ? "selected" : ""; ?> > 
                        <?=
                        htmlspecialchars($user['name']);
                        echo ' ';
                        ?> <?= htmlspecialchars($user['lastname']); ?></option>
                <?php endforeach; ?>                                     
                <span>*</span>
            </select> 
        </span>
    </div>

    <div align="">
        <span class="">
            <label for="report_date">วันที่รายงานผล</label>
            <input name="report_date" type="text" class="" id="report_date" value="<?= $patients[0]['report_date']; ?>">
            </script>
        </span>
    </div>

    <div align="left">
        <span class="">
            <label for="">ราคาค่าตรวจ</label>
            <input name="pprice" type="text" class="" id="pprice" size="15" maxlength="30"  value="<?= $patients[0]['pprice']; ?>"   >
            บาท
        </span>
    </div>

    <div align="">
        <label for="uspecimen_id">สิ่งส่งตรวจ</label>
        <select name="uspecimen_id" class="">
            <option value="กรุณาเลือก">กรุณาเลือก</option>
            <?php foreach ($specimens as $specimen): ?>
                <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>    ?>
                <option value="<?= $specimen['id']; ?>" <?= $patients[0]['pspecimen_id'] == $specimen['id'] ? "selected" : ""; ?>   ><?= htmlspecialchars($specimen['specimen']); ?></option>
            <?php endforeach; ?>
        </select>   
    </div>

    <div align="">
        <label for="pspprice">ราคาค่าตรวจพิเศษ</label>
        <span class="">
            <input name="pspprice" type="text" class="" id="pspprice" size="15" maxlength="30"  value="<?= $patients[0]['pspprice']; ?>"  >
            บาท
        </span>
    </div>

    <div align="">
        <label for="">สถานะ</label>
        <span class=""><input name="status" type="text" class="" id="status" size="15" maxlength="30" value="<?= $patients[0]['status']; ?>"></span>
    </div>



