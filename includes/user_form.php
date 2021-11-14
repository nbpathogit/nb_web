<tbody><tr>
                            <th height="40" bgcolor="#FFCCFF" scope="col"><div align="center" class="style6">
                                <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="style17">&nbsp;เพิ่มผู้ใช้งานระบบ</span></div>
                            </div></th>
                          </tr>
                          <tr>
                            <th height="264" scope="col">
							 <form id="checkForm" name="checkForm" method="post" onsubmit="return check()" action="a_user_add2.php">
                                <table width="97%" border="0" cellspacing="9" cellpadding="2">
                                  <tbody><tr>
                                    <th scope="col">&nbsp;</th>
                                    <th width="29%" class="style14" scope="col"><div align="right">ชื่อ</div></th>
                                    <th width="2%" scope="col">&nbsp;</th>
                                    <th width="65%" class="style200" scope="col"><div align="left">
                                        <input name="user_name" type="text" class="text4" id="user_name" size="50">
                                        <span class="comment">*</span></div></th>
                                    <th scope="col">&nbsp;</th>
                                  </tr>
                                  <tr>
                                    <th scope="col">&nbsp;</th>
                                    <th class="style14" scope="col"><div align="right">นามสกุล</div></th>
                                    <th scope="col">&nbsp;</th>
                                    <th class="style200" scope="col"><div align="left">
                                        <input name="user_surname" type="text" class="text4" id="user_surname" size="50">
                                        <span class="comment">*</span></div>
                                        <label></label></th>
                                    <th scope="col">&nbsp;</th>
                                  </tr>
                                  <tr>
                                    <th scope="col">&nbsp;</th>
                                    <th class="style14" scope="col"><div align="right">กลุ่มผู้ใช้งาน</div></th>
                                    <th scope="col">&nbsp;</th>
                                    <th class="style200" scope="col"><div align="left">
                                        <select name="user_group" class="text4"><option value="1">เจ้าหน้าที่ ร.พ.</option><option value="2">แพทย์</option><option value="3">ผู้ดูแลระบบ</option><option value="4">พยาธิแพทย์</option><option value="5">Medtech PPC</option><option value="6">เจ้าหน้าที่ LAB PPC</option><option value="7">ผู้ดูแลโรงพยาบาล</option></select>                                        <span class="comment">*</span></div></th>
                                    <th scope="col">&nbsp;</th>
                                  </tr>
                                  <tr>
                                    <th scope="col">&nbsp;</th>
                                    <th class="style14" scope="col"><div align="right">สถานที่ทำงาน</div></th>
                                    <th scope="col">&nbsp;</th>
                                    <th class="style200" scope="col"><div align="left">
                                        <select name="hospital_num" class="text4"><option value="1">โรงพยาบาลรวมแพทย์</option><option value="18">โรงพยาบาลกำแพงเพชร</option><option value="4">โรงพยาบาลพุทธชินราช</option><option value="19"> โรงพยาบาลสุโขทัย</option><option value="14">โรงพยาบาลกรุงเทพพิษณุโลก</option><option value="7">โรงพยาบาลพิษณุเวช</option><option value="8">โรงพยาบาลมหาวิทยาลัยนเรศวร</option><option value="17">โรงพยาบาลอินเตอร์เวชการ</option><option value="20">คลินิกพ.วิเศรษฐ์</option><option value="21">คลินิกพ.ปรัชญา</option><option value="22">คลินิกแพทย์สมชาย</option><option value="23">คลินิกแพทย์วสันต์</option><option value="24">คลินิก พ.กัญจน์พรรณ</option><option value="25">คลินิกพ.ภาวิณี</option><option value="26">รพ.กรุงเทพพิษณุโลก2</option><option value="27">รพ.ค่ายสมเด็จพระนเรศวร</option><option value="28">รพ.พิจิตร</option><option value="29">รพ.ศรีสังวรสุโขทัย(ER)</option><option value="30">รพ.ศรีสังวรสุโขทัย (EENT)</option><option value="35">คลินิก พ.อดิศร</option><option value="32">คลินิก พ.วิวัฒน์</option><option value="33">ทิพย์รัตน์ LAB</option><option value="37">บรรพตคลินิคแล็บ</option><option value="38">คลินิก พ. ดารณี</option><option value="39">คลินิคแพทย์เจษฎา</option><option value="40">ร.พ.ค่ายพิชัยดาบหัก</option><option value="41">คลินิกสูติ-นรีเวช หมอปภาดา</option><option value="42">โรงพยาบาลชัยนาทนเรนทร</option><option value="43">คลินิคแพทย์วิภา</option><option value="44">Pathology lab  Lao , Lao PDR</option><option value="45">คลินิค พ.พัลลภา</option><option value="46">ยิ้มใส คลินิคทันตกรรม</option><option value="47">โรงพยาบาลหล่มสัก</option><option value="48">Sukhothai clinic lab</option><option value="49">ศรัณย์คลินิกศัลยกรรมตกแต่ง</option><option value="56">O'Clock</option><option value="57">เพชรบูรณ์ LAB(คลินิค)</option><option value="58">คลินิกเวชกรรมแพทย์อมฤทธิ์</option><option value="59">รังสันต์คลินิค(กำแพงเพชร)</option></select>                                        <span class="comment">*</span></div></th>
                                    <th scope="col">&nbsp;</th>
                                  </tr>
                                  <tr>
                                    <th scope="col">&nbsp;</th>
                                    <th class="style14" scope="col"><div align="right">รายละเอียด</div></th>
                                    <th scope="col">&nbsp;</th>
                                    <th class="style200" scope="col"><div align="left">
                                          <textarea name="user_detail" cols="60" rows="4" class="text4" id="user_detail"></textarea>
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
                                        <input name="user_username" type="text" class="text4" id="user_username" size="20" maxlength="10">
                                      *กรุณาเป็นภาษาอังกฤษ 6-10 ตัวอักษร </div>
                                        <label></label></th>
                                    <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                                  </tr>
                                  <tr>
                                    <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                                    <th bgcolor="#F6F6F7" class="style14" scope="col"><div align="right">รหัสผ่าน</div></th>
                                    <th bgcolor="#F6F6F7" scope="col">&nbsp;</th>
                                    <th bgcolor="#F6F6F7" class="style200" scope="col"><div align="left">
                                        <input name="user_password" type="password" class="text4" id="user_password" size="20" maxlength="10">
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
                                          <th width="12%" scope="col"><input name="Submit2" type="submit" class="BUT_G_78_23_L" value="เพิ่มข้อมูล"></th>
                                          <th width="12%" scope="col"><input name="Reset" type="reset" class="BUT_G_78_23_L" id="Reset" value="ยกเลิก"></th>
                                          <th width="44%" scope="col"><div align="left"></div></th>
                                        </tr>
                                    </tbody></table></th>
                                    <th width="2%" scope="col">&nbsp;</th>
                                  </tr>
                                </tbody></table>
                            </form></th>
                          </tr>
                        </tbody>