<?php
$isBorder = false;


$userAuthEdit = (
        $isCurUserAdmin || $isCurUserPatho || $isCurUserPathoAssis || $isCurUserLabOfficerNB || $isCurUserAdminStaff
        //|| $isCurUserClinicianCust 
        //|| $isCurUserHospitalCust
        );

$curStatusAuthEdit = (
        $isCurStatus_1000 || $isCurStatus_2000 || $isCurStatus_3000 || $isCurStatus_6000 || $isCurStatus_8000 || $isCurStatus_10000 || $isCurStatus_12000 || $isCurStatus_13000 || $isCurStatus_20000
        );

if ($debug) {
    echo "$userAuthEdit and $curStatusAuthEdit";
    var_dump($userAuthEdit);
    var_dump($curStatusAuthEdit);
}
?>

<!-- Content here -->



<div class="container-fluid pt-4 px-4">
    <hr noshade="noshade" width="" size="8">
    <h4 align="center"><b>เพิ่มข้อมูลผู้ป่วยใหม่ลงในระบบ</b></h4>

    <br>

    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">


        <div class=" <?= $isBorder ? "border" : "" ?>">
            <input type="radio" id="autogen" name="is_autogen" value="yes" checked>
            <label for="autogen"><b>Auto generate number.(โปรแกรมสร้างตัวเลขให้)</b></label>
        </div><br>

        <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
            <label for="snprefix">อักษรย่อชนิดส่งตรวจ</label>
            <select class="form-select" name="snprefix" id="snprefix" >
                <!--<option value="#">กรุณาเลือก</option>-->
                <?php foreach ($snPrefixs as $snPrefix) : ?>
                    <option value="<?= ($snPrefix['name']); ?>" ><?= $snPrefix['name'] . "   ::" . $snPrefix['description']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>"></div>
        <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>"></div>
    </div>
    <br>




    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div class=" <?= $isBorder ? "border" : "" ?>">
            <input type="radio" id="manualgen" name="is_autogen" value="no" >
            <label for="manualgen"><b>Manual input number.(ใส่ตัวเลขด้วยตัวเอง)</b></label>
        </div><br>


        <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
            <label for="prenum" align="right" class="">ใส่อักษรย่อชนิดส่งตรวจ(สองตัว)</label>
            <input name="prenum" type="text" id="prenum" list="prenumlist" class="form-control" maxlength="2" placeholder="เช่น: SN"    value=""  maxlength="2" size="4">
            <datalist id="prenumlist">
                <option>SN</option>
                <option>CN</option>
            </datalist>
        </div>
        <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
            <label for="yearnum" align="right" class="">ใส่เลขปี(สองหลัก)</label>
            <input name="yearnum" type="text" id="yearnum" list="yearnumlist" class="form-control" maxlength="2"  placeholder="เช่น: 23"    value="" maxlength="2" size="4">
            <datalist id="yearnumlist">
                <option>23</option>
            </datalist>
        </div>
        <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
            <label for="runnum" align="right" class="">ใส่เลขรันนิ่งนัมเบอร์(จำนวนห้าหลัก)</label>
            <input name="runnum" type="text" id="runnum" class="form-control" maxlength="5"  placeholder="เช่น: 00001"    value="">
        </div>
        <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>"></div>

    </div>
    <br>
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">


        <div class="row <?= $isBorder ? "border" : "" ?>">
            
            <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                <label for="ppre_name" class="">คำนำหน้าชื่อ</label>
                <input name="ppre_name" type="text" list="pre_name_list_add" class="form-control border" id="ppre_name_add" placeholder=""  value="<?= $patient[0]['ppre_name']; ?>">
                <datalist id="pre_name_list_add">
                    <?php require 'includes/prenameOption.php'; ?>
                </datalist>
            </div>

            <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                <label for="pname" class="">ชื่อผู้ป่วย</label>
                <input name="pname" type="text" class="form-control border" id="pname" placeholder=""  value="<?= $patient[0]['pname']; ?>">
            </div>

            <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?>">
                <label for="plastname" class="">นามสกุล</label>
                <div class="col">
                    <input name="plastname" type="text" class="form-control" id="plastname" placeholder=""  value="<?= $patient[0]['plastname']; ?>">
                </div>
            </div>

            <div class="col-xl-4 col-md-6 ">
                <label for="pgender" class="">เพศ</label>
                <div class="col">
                    <select name="pgender" class="form-select" id="pgender_add">
                        <option value="กรุณาเลือก">กรุณาเลือก</option>
                        <option value="NA" >NA</option>
                        <option value="Male" >Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

        </div>

        <p align="center">
            <!--<button>ตกลง</button>-->
            <br>
            <button name="Submit2" type="submit" class="btn btn-primary">เพิ่มข้อมูลใหม่ลงในระบบ</button>
        </p>
        <input type="hidden" name="create_by" value="<?= $cur_user->name . ' ' . $cur_user->lastname ?>">


    </div>
</div>