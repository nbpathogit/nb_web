


    <table border="1" align="center">
        <tbody>
            <tr>
                <td height="40" >
                    <!--div align="center" -->
                    <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;

                    </div>
                    <!--/div-->
                </td>
            </tr>
            <tr>
                <td>


                    <table   border="1" cellspacing="9" cellpadding="2">
                        <!--tbody-->
                        <tr>
                            <td >&nbsp;</td>
                            <td widtd="29%"  ><div align="right">ชื่อ</div></td>
                            <td widtd="2%" >&nbsp;</td>
                            <td widtd="65%"  ><div align="left">
                                    <input name="name" type="text"  id="name" size="50">
                                    <span>*</span></div></td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><div align="right">นามสกุล</div></td>
                            <td>&nbsp;</td>
                            <td><div align="left">
                                    <input name="lastname" type="text"  id="lastname" size="50">
                                    <span>*</span></div>
                                <label></label></td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td >&nbsp;</td>
                            <td  ><div align="right">เบอร์โทรศัพย์</div></td>
                            <td >&nbsp;</td>
                            <td  ><div align="left">
                                    <input name="umobile" type="text"  id="umobile" size="50">
                                    <span  >*</span></div>
                                <label></label></td>
                            <td >&nbsp;</td>
                        </tr>

                        <tr>
                            <td >&nbsp;</td>
                            <td  ><div align="right">อีเมล</div></td>
                            <td >&nbsp;</td>
                            <td  ><div align="left">
                                    <input name="uemail" type="text" id="uemail" size="50">
                                    <span>*</span></div>
                                <label></label></td>
                            <td >&nbsp;</td>
                        </tr>


                        <tr>
                            <td>&nbsp;</td>
                            <td><div align="right">กลุ่มผู้ใช้งาน</div></td>
                            <td>&nbsp;</td>
                            <td><div align="left">
                                    <select name="ugroup_id" >
                                        <?php foreach ($ugroups as $ugroup): ?>
                                            <?php //Target Format : <option value="1">เจ้าหน้าที่ ร.พ.</option> ?>
                                            <option value="<?= htmlspecialchars($ugroup['id']); ?>"><?= htmlspecialchars($ugroup['ugroup']); ?></option>
                                        <?php endforeach; ?>                                     
                                        <span>*</span>
                                    </select> 
                                </div>

                            </td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td >&nbsp;</td>
                            <td ><div align="right">สถานที่ทำงาน</div></td>
                            <td >&nbsp;</td>
                            <td ><div align="left">
                                    <select name="uhospital_id" >
                                        <?php foreach ($hospitals as $hospital): ?>
                                            <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                                            <option value="<?= htmlspecialchars($hospital['id']); ?>"><?= htmlspecialchars($hospital['hospital']); ?></option>
                                        <?php endforeach; ?>

                                    </select>                                        
                                    <span  >*</span></div></td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td >&nbsp;</td>
                            <td ><div align="right">รายละเอียด</div></td>
                            <td >&nbsp;</td>
                            <td ><div align="left">
                                    <textarea name="udetail" cols="60" rows="4" id="udetail"></textarea>
                                </div>
                            </td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><div align="right">ชื่อเข้าใช้</div></td>
                            <td>&nbsp;</td>
                            <td><div align="left"  >
                                    <input name="username" type="text"  id="username" size="20" maxlengtd="10">
                                    *กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร </div>
                                <label></label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><div align="right">รหัสผ่าน</div></td>
                            <td>&nbsp;</td>
                            <td><div align="left">
                                    <input name="password" type="password"  id="password" size="20" maxlengtd="10">
                                    <span>*</span></div></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><div align="right">ยืนยันรหัสผ่าน</div></td>
                            <td>&nbsp;</td>
                            <td><div align="left">
                                    <input name="user_password2" type="password" id="user_password2" size="20" maxlengtd="10">
                                    <span  >*</span></div></td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td widtd="2%" >&nbsp;</td>
                            <td colspan="3"  >
                                <table widtd="100%" border="1" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td widtd="32%" ><br></td>
                                            <td widtd="12%" ></td>
                                            <td widtd="12%" ></td>
                                            <td widtd="44%" ><div align="right"></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td widtd="2%" >&nbsp;</td>
                        </tr>
                        <!--/tbody-->
                    </table>

                </td>
            </tr>
        </tbody>
    </table>
