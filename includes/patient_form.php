<tr>
    <td>       
        <form id="" name="" method="post" onsubmit="" action="">
            <table width="870" height="68" border="1" align="center" cellpadding="2" cellspacing="2" >
                <tbody><tr>
                        <td height="40" bgcolor=""><strong class="">เพิ่มข้อมูลผู้ป่วย</strong></td>
                    </tr>
                    <tr>
                        <td height="26" bgcolor=""><table width="100%" border="0" cellpadding="3" cellspacing="3">
                                <tbody><tr>
                                        <td class=""><div align="left"><span class="">เลขที่ผู้ป่วย</span></div></td>
                                        <td><div align="left">
                                                <span class="">
                                                    <input name="num" type="text" class="" id="num" size="15" maxlength="15">
                                                </span></div></td>
                                        <td class=""><div align="left"><span class="">LAB Number</span></div></td>
                                        <td><div align="left"> <span class="">
                                                    <input name="lab_num" type="text" class="" id="lab_num" size="15" maxlength="30">
                                                </span></div></td>
                                    </tr>
                                    <tr>
                                        <td width="115" class=""><div align="left"><span class="">ชื่อผู้ป่วย</span></div></td>
                                        <td width="210"><div align="left">
                                                <span class="">
                                                    <input name="name" type="text" class="" id="name" size="30" maxlength="30">
                                                </span></div></td>
                                        <td width="102" class=""><div align="left"><span class="">นามสกุล</span></div></td>
                                        <td width="214"><div align="left">
                                                <span class="">
                                                    <input name="surname" type="text" class="" id="surname" size="30" maxlength="30">
                                                </span></div></td>
                                    </tr>
                                    <tr>
                                        <td class=""><div align="left"><span class="">เพศ</span></div></td>
                                        <td><div align="left">
                                                <span class="">
                                                    <select name="sex" class="" id="sex">
                                                        <option value="กรุณาเลือก">กรุณาเลือก</option>
                                                        <option value="ชาย">ชาย</option>
                                                        <option value="หญิง">หญิง</option>
                                                    </select>
                                                </span></div></td>
                                        <td class=""><div align="left"><span class="">อายุ</span></div></td>
                                        <td><div align="left">
                                                <span class="">
                                                    <input name="age" type="text" class="" id="age" size="5" maxlength="3">
                                                    ปี</span></div></td>
                                    </tr>
                                    <tr>
                                        <td width="115" class=""><div align="left"><span class="">วันที่รับ</span></div></td>
                                        <td width="210" class=""><div align="left">
                                                <span class="">
                                                    <input name="dateInput" type="text" class="" id="dateInput" value="">

                                                    </script>
                                                </span></div></td>
                                        <td width="102" class=""><div align="left"><span class="">โรงพยาบาล</span></div></td>
                                        <td width="214"><div align="left">
                                                <span class="">
                                                    <select name="hospital_id1" class="">

                                                        <?php foreach ($hospitals as $hospital): ?>
                                                            <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                                                            <option value="<?= htmlspecialchars($hospital['id']); ?>"><?= htmlspecialchars($hospital['hospital']); ?></option>
                                                        <?php endforeach; ?>

                                                    </select>                                    
                                                </span></div></td>
                                    </tr>
                                    <tr>
                                        <td class=""><div align="left"><span class="">เลขที่โรงพยาบาล</span></div></td>
                                        <td><div align="left">
                                                <span class="">
                                                    <input name="hospital_num" type="text" class="" id="" size="20" maxlength="20">
                                                </span></div></td>
                                        <td class=""><div align="left"><span class="">ความสำคัญ</span></div></td>
                                        <td><div align="left">
                                                <span class="">
                                                    <label> 
                                                        <input name="importance" type="radio" value="1">
                                                    </label>
                                                    ด่วน
                                                    <label>
                                                        <input name="importance" type="radio" value="0" checked="">
                                                    </label>
                                                    ปกติ </span></div></td>
                                    </tr>
                                    <tr>
                                        <td width="115" class=""><div align="left"><span class="">แพทย์ผู้ส่ง</span></div></td>
                                        <td width="210"><div align="left">
                                                <span class="">
                                                    <select name="clinician" class="">
                                                         <option value="กรุณาเลือก">กรุณาเลือก</option>
                                                         <?php foreach ($clinicians as $clinician): ?>
                                                         <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                                                            <option value="<?= htmlspecialchars($clinician['id']); ?>"><?= htmlspecialchars($clinician['clinician']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>                                    
                                                </span></div></td>
                                        <td width="102" class=""><div align="left"><span class="">พยาธิแพทย์</span></div></td>
                                        <td width="214"><div align="left">
                                                <span class="">
                                                    <select name="pathologist" class=""><option value="">กรุณาเลือก</option><option value="37">นายแพทย์สุชาติ</option><option value="38">Julintorn</option><option value="43">Apichat</option><option value="42">Peerayut</option><option value="485">Poosit </option><option value="597">Siroratt </option><option value="706">Chaidan  </option><option value="723">Unnop</option><option value="799">Nun </option></select>                                    </span></div></td>
                                    </tr>
                                    <tr>
                                        <td width="115" class=""><div align="left"><span class="">วันที่รายงานผล</span></div></td>
                                        <td width="210"><div align="left">
                                                <span class="">
                                                    <input name="reportdate" type="text" class="" id="reportdate">
                                                    </script>
                                                </span></div></td>
                                        <td width="102" class=""><div align="left"><span class="">ราคาค่าตรวจ</span></div></td>
                                        <td width="214"><div align="left">
                                                <span class="">
                                                    <input name="price" type="text" class="" id="price" size="15" maxlength="30">
                                                    บาท</span></div></td>
                                    </tr>
                                    <tr>
                                        <td width="102" class=""><div align="left">สิ่งส่งตรวจ</div></td>

                                        <td width="210"><div align="left">
                                                <div align="left">
                                                    <div align="left">
                                                        <div align="left">
                                                            <select name="organ" class=""><option value="กรุณาเลือก">กรุณาเลือก</option>
                                                                <option value="กรุณาเลือก">กรุณาเลือก</option>
                                                                <?php foreach ($specimens as $specimen): ?>
                                                                    <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option> ?>
                                                                    <option value="<?= htmlspecialchars($specimen['id']); ?>"><?= htmlspecialchars($specimen['specimen']); ?></option>
                                                                <?php endforeach; ?>
                                                            </select>   

                                                        </div>
                                                    </div>
                                                </div>
                                            </div></td>
                                        <td width="102" class=""><div align="left">ราคาค่าตรวจพิเศษ</div></td>
                                        <td width="214"><div align="left">
                                                <div align="left"> <span class="">
                                                        <input name="sprice" type="text" class="" id="sprice" size="15" maxlength="30">
                                                        บาท</span></div>
                                            </div></td>
                                    </tr>
                                </tbody></table></td>
                    </tr>
                </tbody></table>
            <br>
            <br>
            <table width="870" height="54" border="1" align="center" cellpadding="2" cellspacing="2" bgcolor="">
                <tbody><tr>
                        <td height="40" bgcolor=""><strong class="">ผลการตรวจ</strong></td>
                    </tr>
                    <tr>
                        <td height="26" bgcolor=""><table width="870" border="0" cellpadding="1" cellspacing="3">
                                <tbody><tr>
                                        <td width="10%" class=""><div align="left"><span class="">SPECIMEN</span></div></td>
                                        <td rowspan="3"><span class="">
                                                <label>
                                                    <textarea name="specimen" cols="100" rows="5" class="" id="specimen"></textarea>
                                                </label>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="">                                <span class="">&nbsp;</span></td>
                                    </tr>
                                    <tr>
                                        <td class=""><div align="left"><span class="">CLINICAL DIAGNOSIS <br>
                                                </span></div></td>
                                        <td rowspan="3"><span class="">
                                                <label>
                                                    <textarea name="clinician_d" cols="100" rows="5" class="" id="clinician_d"></textarea>
                                                </label>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class=""><div align="left"><span class="">GROSS DESCRIPTION </span></div></td>
                                        <td rowspan="3"><span class="">
                                                <label>
                                                    <textarea name="gross" cols="100" rows="5" class="" id="gross"></textarea>
                                                </label>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class=""><div align="left"><span class="">MICROSCOPIC DESCRIPTION </span></div></td>
                                        <td rowspan="3"><span class="">
                                                <label>
                                                    <textarea name="microscope" cols="100" rows="5" class="" id="microscope"></textarea>
                                                </label>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class=""><div align="left"><span class="">DIAGNOSIS</span></div></td>
                                        <td rowspan="3"><span class="">
                                                <label>
                                                    <textarea name="diagnosis" cols="100" rows="5" class="" id="specimen3"></textarea>
                                                    <br>
                                                </label>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                </tbody></table>
                            <p>
                                <label></label>
                            </p>
                            <p align="center">
                                <input name="Submit" type="submit" class="" id="Submit" value="เพิ่ม">
                                <input name="Submit2" type="reset" class="" id="Submit2" value="ยกเลิก">
                            </p></td>
                    </tr>
                </tbody></table>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </form></td>
</tr>