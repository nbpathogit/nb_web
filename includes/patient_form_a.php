<table border="1" align="center" width="1000">
    <tr>
        <td>       
            <form id="" name="" method="post" >
                <table border="1" align="center" width="100%">
                    <tbody>
                        <tr>
                            <td bgcolor=""><strong class="">เพิ่มข้อมูลผู้ป่วย</strong></td>
                        </tr>
                        <tr>
                            <td height="26" bgcolor="">
                                <table width="100%" border="1">
                                    <tbody>
                                        <tr>
                                            <td><div align="left"><span class="">เลขที่ผู้ป่วย</span></div></td>
                                            <td><div align="left">
                                                    <span>
                                                        <input name="pnum" type="text" id="pnum" size="20" maxlength="20" value="<?= $patients[0]['pnum'];?>">
                                                    </span></div></td>
                                            <td class=""><div align="left"><span class="">LAB Number</span></div></td>
                                            <td><div align="left"> <span class="">
                                                        <input name="plabnum" type="text" class="" id="plabnum" size="20" maxlength="30" value="<?= $patients[0]['plabnum'];?>">
                                                    </span></div></td>
                                        </tr>
                                        <tr>
                                            <td><div align="left"><span class="">ชื่อผู้ป่วย</span></div></td>
                                            <td>
                                                <div align="left">
                                                    <span class="">
                                                        <input name="pname" type="text" class="" id="pname" size="30" maxlength="30" value="<?= $patients[0]['pname'];?>">
                                                    </span>
                                                </div>
                                            </td>
                                            <td><div align="left"><span class="">นามสกุล</span></div></td>
                                            <td>
                                                <div align="left">
                                                    <span class="">
                                                        <input name="plastname" type="text" class="" id="plastname" size="30" maxlength="30" value="<?= $patients[0]['plastname'];?>">
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><div align="left"><span class="">เพศ</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <select name="pgender" class="" id="pgender">
                                                            <option value="กรุณาเลือก">กรุณาเลือก</option>
                                                            <option value="ชาย" <?= ($patients[0]['pgender']  === "ชาย") ? "selected" : "";?> >ชาย</option>
                                                            <option value="หญิง" <?= ($patients[0]['pgender'] === "หญิง") ? "selected" : "";?> >หญิง</option>
                                                        </select>
                                                    </span></div></td>
                                            <td><div align="left"><span class="">อายุ</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <input name="pedge" type="text" class="" id="pedge" size="5" maxlength="3" value="<?= $patients[0]['pedge'];?>">
                                                        ปี</span></div></td>
                                        </tr>
                                        <tr>
                                            <td><div align="left"><span class="">วันที่รับ</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <input name="import_date" type="text" class="" id="import_date" value="<?= $patients[0]['import_date'];?>">

                                                       
                                                    </span></div></td>
                                            <td><div align="left"><span class="">โรงพยาบาล</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <select name="phospital_id" class="">
                                                            <option value="กรุณาเลือก">กรุณาเลือก</option> 
                                                            <?php foreach ($hospitals as $hospital): ?>
                                                                <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option> ?>
                                                                <option value="<?= htmlspecialchars($hospital['id']); ?>" <?= ($patients[0]['phospital_id']==($hospital['id'])) ? "selected" : "";?> ><?= htmlspecialchars($hospital['hospital']); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>                                    
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><div align="left"><span class="">เลขที่โรงพยาบาล</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <input name="phospital_num" type="text" class="" id="" size="20" maxlength="20" value="<?= $patients[0]['phospital_num'];?>" >
                                                    </span>
                                                </div>
                                            </td>
                                            <td><div align="left"><span class="">ความสำคัญ</span></div></td>
                                            <td>
                                                <div align="left">
                                                    <span class="">
                                                        
                                                        <label> 
                                                            <input name="priority_id" type="radio" value="<?= htmlspecialchars($prioritys[0]['id']); ?>" <?= ($patients[0]['priority_id']==($prioritys[0]['id'])) ? "checked" : "";?>  >
                                                        </label>
                                                        <?= htmlspecialchars($prioritys[0]['priority']); ?>
                                                        <label>
                                                            <input name="priority_id" type="radio" value="<?= htmlspecialchars($prioritys[1]['id']); ?>" <?= ($patients[0]['priority_id']==($prioritys[1]['id'])) ? "checked" : "";?> >
                                                        </label>
                                                         <?= htmlspecialchars($prioritys[1]['priority']); ?>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><div align="left"><span class="">แพทย์ผู้ส่ง</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <select name="pclinician_id" class="">
                                                            <option value="กรุณาเลือก" selected>กรุณาเลือก</option>
                                                            <?php foreach ($clinicians as $clinician): ?>
                                                                <?php //Target Format : <option value="495">BOUNTHOME  SAMOUNTRY , MD.</option> ?>
                                                                <option value="<?= htmlspecialchars($clinician['id']); ?>"  <?= $patients[0]['pclinician_id'] == $clinician['id'] ? "selected" : "";?> ><?= htmlspecialchars($clinician['name']); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>                                    
                                                    </span></div></td>
                                            <td><div align="left"><span class="">พยาธิแพทย์</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <select name="ppathologist_id" >
                                                            <option value="">กรุณาเลือก</option>
                                                            <?php foreach ($userPathos as $user): ?>
                                                                <?php //Target Format : <option value="37">นายแพทย์สุชาติ</option> ?>
                                                                <option value="<?= htmlspecialchars($user['id']); ?>" <?= $patients[0]['ppathologist_id']==htmlspecialchars($user['id']) ? "selected" : "";?> > <?= htmlspecialchars($user['name']);
                                                            echo ' ';
                                                                ?> <?= htmlspecialchars($user['lastname']); ?></option>
                                                           <?php endforeach; ?>                                     
                                                            <span>*</span>
                                                        </select> 
                                                    </span></div></td>
                                        </tr>
                                        <tr>
                                            <td><div align="left"><span class="">วันที่รายงานผล</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <input name="report_date" type="text" class="" id="report_date" value="<?= $patients[0]['report_date'];?>">
                                                        </script>
                                                    </span></div></td>
                                            <td><div align="left"><span class="">ราคาค่าตรวจ</span></div></td>
                                            <td><div align="left">
                                                    <span class="">
                                                        <input name="pprice" type="text" class="" id="pprice" size="15" maxlength="30"  value="<?= $patients[0]['pprice'];?>"   >
                                                        บาท</span></div></td>
                                        </tr>
                                        <tr>
                                            <td><div align="left">สิ่งส่งตรวจ</div></td>

                                            <td><div align="left">
                                                    <div align="left">
                                                        <div align="left">
                                                            <div align="left">
                                                                <select name="uspecimen_id" class="">
                                                                    <option value="กรุณาเลือก">กรุณาเลือก</option>
                                                                    <?php foreach ($specimens as $specimen): ?>
                                                                        <?php //Target Format : <option value="1001">ชิ้นเนื้อขนาดเล็กกว่าหรือเท่ากับ 2 ซ.ม. (38001)</option>   ?>
                                                                        <option value="<?= $specimen['id']; ?>" <?= $patients[0]['pspecimen_id']==$specimen['id'] ? "selected" : "";?>   ><?= htmlspecialchars($specimen['specimen']); ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>   

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><div align="left">ราคาค่าตรวจพิเศษ</div></td>
                                            <td>
                                                <div align="left">
                                                    <div align="left"> 
                                                        <span class=""><input name="pspprice" type="text" class="" id="pspprice" size="15" maxlength="30"  value="<?= $patients[0]['pspprice'];?>"  >บาท</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><div align="left">สถานะ</div></td>

                                            <td><div align="left">
                                                    <div align="left">
                                                        <div align="left">
                                                            <div align="left">
                                                                <span class=""><input name="status" type="text" class="" id="status" size="15" maxlength="30" value="<?= $patients[0]['status'];?>"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><div align="left">   </div></td>
                                            <td>
                                                <div align="left">
                                                    <div align="left"> 
                                                        
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>




                <br>
                <br>



            </form>
        </td>
    </tr>
</table>