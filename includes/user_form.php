<tbody><tr>
        <th height="40" bgcolor="#FFCCFF" scope="col"><div align="center" class="style6">
                <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="style17">&nbsp;เพิ่มผู้ใช้งานระบบ</span></div>
            </div></th>
    </tr>
    <tr>
        <th height="264" scope="col">
            <form id="formUser" method="post" >
                <table width="97%" border="0" cellspacing="9" cellpadding="2">
                    <tbody><tr>
                            <th scope="col">&nbsp;</th>
                            <th width="29%" class="style14" scope="col"><div align="right">ชื่อ</div></th>
                            <th width="2%" scope="col">&nbsp;</th>
                            <th width="65%" class="style200" scope="col"><div align="left">
                                    <input name="name" type="text" class="text4" id="name" size="50">
                                    <span class="comment">*</span></div></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th class="style14" scope="col"><div align="right">นามสกุล</div></th>
                            <th scope="col">&nbsp;</th>
                            <th class="style200" scope="col"><div align="left">
                                    <input name="lastname" type="text" class="text4" id="lastname" size="50">
                                    <span class="comment">*</span></div>
                                <label></label></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        
                        
                        
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th class="style14" scope="col"><div align="right">เบอร์โทรศัพย์</div></th>
                            <th scope="col">&nbsp;</th>
                            <th class="style200" scope="col"><div align="left">
                                    <input name="umobile" type="text" class="text4" id="umobile" size="50">
                                    <span class="comment">*</span></div>
                                <label></label></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th class="style14" scope="col"><div align="right">อีเมล</div></th>
                            <th scope="col">&nbsp;</th>
                            <th class="style200" scope="col"><div align="left">
                                    <input name="uemail" type="text" class="text4" id="uemail" size="50">
                                    <span class="comment">*</span></div>
                                <label></label></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th class="style14" scope="col"><div align="right">กลุ่มผู้ใช้งาน</div></th>
                            <th scope="col">&nbsp;</th>
                            <th class="style200" scope="col"><div align="left">
                                    <select name="ugroup_id" class="text4">
                                        <?php foreach ($ugroups as $ugroup): ?>
                                            <?php //Target Format : <option value="1">เจ้าหน้าที่ ร.พ.</option> ?>
                                            <option value="<?= htmlspecialchars($ugroup['id']); ?>"><?= htmlspecialchars($ugroup['ugroup']); ?></option>
                                        <?php endforeach; ?>                                     
                                    <span class="comment">*</span></div></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th class="style14" scope="col"><div align="right">สถานที่ทำงาน</div></th>
                            <th scope="col">&nbsp;</th>
                            <th class="style200" scope="col"><div align="left">
                                    <select name="uhospital_id" class="text4">
                                        <?php foreach ($hospitals as $hospital): ?>
                                            <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                                            <option value="<?= htmlspecialchars($hospital['id']); ?>"><?= htmlspecialchars($hospital['hospital']); ?></option>
                                        <?php endforeach; ?>

                                    </select>                                        
                                    <span class="comment">*</span></div></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th class="style14" scope="col"><div align="right">รายละเอียด</div></th>
                            <th scope="col">&nbsp;</th>
                            <th class="style200" scope="col"><div align="left">
                                    <textarea name="udetail" cols="60" rows="4" class="text4" id="udetail"></textarea>
                                </div>
                            </th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th class="style14" scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th class="style200" scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                            <th bgcolor="#F6F6F7" class="style14" scope="col"><div align="right">ชื่อเข้าใช้</div></th>
                            <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                            <th bgcolor="#F6F6F7" class="style200" scope="col"><div align="left" class="comment">
                                    <input name="username" type="text" class="text4" id="username" size="20" maxlength="10">
                                    *กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร </div>
                                <label></label></th>
                            <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                            <th bgcolor="#F6F6F7" class="style14" scope="col"><div align="right">รหัสผ่าน</div></th>
                            <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                            <th bgcolor="#F6F6F7" class="style200" scope="col"><div align="left">
                                    <input name="password" type="password" class="text4" id="password" size="20" maxlength="10">
                                    <span class="comment">*</span></div></th>
                            <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                            <th bgcolor="#F6F6F7" class="style14" scope="col"><div align="right">ยืนยันรหัสผ่าน</div></th>
                            <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                            <th bgcolor="#F6F6F7" class="style200" scope="col"><div align="left">
                                    <input name="user_password2" type="password" class="text4" id="user_password2" size="20" maxlength="10">
                                    <span class="comment">*</span></div></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th width="2%" scope="col">&nbsp;</th>
                            <th colspan="3" class="style200" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
                                            <th width="32%" scope="col"><br></th>
                                            <th width="12%" scope="col"><button>Add</button></th>
                                            <th width="12%" scope="col"></th>
                                            <th width="44%" scope="col"><div align="left"></div></th>
                                        </tr>
                                    </tbody></table></th>
                            <th width="2%" scope="col">&nbsp;</th>
                        </tr>
                    </tbody></table>
            </form></th>
    </tr>
</tbody>