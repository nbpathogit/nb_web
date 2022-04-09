<?php
$isBorder = true;
$disableMoveButton = true;
$hiddom = true;
?>

<!--Write Data to DOM pass value to java script-->
<?php if (isset($curstatus[0]['id'])): ?>
    <li class="cur_status" tabindex="<?= $curstatus[0]['id'] ?>" style="display: none;" > <?= $curstatus[0]['id'] ?> </li>
    <li class="isset_date_first_report" tabindex="<?= $isset_date_first_report ?>" style="display: none;" > <?= $isset_date_first_report ?> </li>

<?php endif; ?>
<ul class="movelist" style="display: none;">
    <?php if (isset($curstatus[0]['back2'])): ?>
        <li tabindex="<?= $curstatus[0]['back2'] ?>"><?= $curstatus[0]['back2'] ?></li>
    <?php endif; ?>

    <?php if (isset($curstatus[0]['back1'])): ?>
        <li tabindex="<?= $curstatus[0]['back1'] ?>"><?= $curstatus[0]['back1'] ?></li>
    <?php endif; ?>



    <?php if (isset($curstatus[0]['next1'])): ?>
        <li tabindex="<?= $curstatus[0]['next1'] ?>"><?= $curstatus[0]['next1'] ?></li>
    <?php endif; ?>

    <?php if (isset($curstatus[0]['next2'])): ?>
        <li tabindex="<?= $curstatus[0]['next2'] ?>"><?= $curstatus[0]['next2'] ?></li>
    <?php endif; ?>

    <?php if (isset($curstatus[0]['next3'])): ?>
        <li tabindex="<?= $curstatus[0]['next3'] ?>"><?= $curstatus[0]['next3'] ?></li>
    <?php endif; ?>
</ul>


<ul class="uresultinxlist" style="<?= $hiddom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex=" <?= $prsu['id'] ?>"><?= $prsu['id'] ?></li>
    <?php endforeach; ?> 
</ul>

<ul class="uresultReleaseSetlist" style="<?= $hiddom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex=" <?= isset($prsu['release_time']) ? 1 : 0 ?>"><?= $prsu['release_time'] ?></li>
    <?php endforeach; ?> 
</ul>

<ul class="uresultSecondPatho" style="<?= $hiddom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= ($prsu['pathologist2_id']) ?>"><?= $prsu['pathologist2_id'] ?></li>
    <?php endforeach; ?> 
</ul>

<!--p_slide_prep_sp_id-->
<ul class="p_slide_prep_sp_id" style="<?= $hiddom ? "display: none;":"" ?>" >
    <li tabindex="<?= $patient[0]['p_slide_prep_sp_id'] ?>"><?= $patient[0]['p_slide_prep_sp_id'] ?></li>
</ul>


<h4 align="center"><b>สถานะงาน ของผู้ป่วยลำดับที่ &nbsp; <?= $_GET['id'] ?> &nbsp; คือ &nbsp; <?= $curstatus['0']["des"] ?> </b></h4>
<span align="left">
    <?php if (!$disableMoveButton) : ?> 
        <form  id="" name="" method="post"> 
            สถานะงานปัจจุบัน : 
            <button name="" class="btn btn-warning" disabled><b> <?= $curstatus['0']["des"] ?> </b> </button>

            <?php if (!$isEditModePageOn) : ?> 
                <b> &nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เปลี่ยนสถานะไปเป็น >> </b>

                <?php if ($back2status != null) : ?> 
                    <button name="status" value="<?= $back2status['0']["id"] ?>" class="btn btn-primary" ><b> <?= $back2status['0']["des"] ?> </b> </button>
                <?php endif; ?>

                <?php if ($back1status != null) : ?> 
                    <button name="status" value="<?= $back1status['0']["id"] ?>" class="btn btn-primary" ><b> <?= $back1status['0']["des"] ?> </b>  </button>
                <?php endif; ?>

                <?php if ($next1status != null) : ?> 
                    <button name="status" value="<?= $next1status['0']["id"] ?>" class="btn btn-primary" ><b> <?= $next1status['0']["des"] ?> </b> </button>
                <?php endif; ?>

                <?php if ($next2status != null) : ?> 
                    <button name="status" value="<?= $next2status['0']["id"] ?>"class="btn btn-primary" ><b> <?= $next2status['0']["des"] ?> </b> </button>
                <?php endif; ?>

                <?php if ($next3status != null) : ?> 
                    <button name="status" value="<?= $next3status['0']["id"] ?>"class="btn btn-primary" ><b> <?= $next3status['0']["des"] ?> </b> </button>
                <?php endif; ?>
                <input type="hidden" name="cur_status" value="<?= $curstatus[0]['id'] ?>" />
            <?php endif; ?>

        </form>
    <?php endif; ?>
</span>


<?php if (!$isEditModePageOn) : ?>
    <hr>
    <h6 align="center"><b>ไดอแกรม แสดงสถานะงาน</b></h4>

    <form id="formflow">

        <table id="flowtab1" class="flowtab1" cellspacing="0" cellpadding="0" align="center">
            <!--<tbody>-->

            <!-- First row in workflow diagram -->
            <tr>
                <td colspan="7" ></td>

                <td>&nbsp;</td>

                <td colspan="7" ></td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7"  tabindex="8000" id="keep8000" class="<?= ($curstatus['0']["id"] == 8000) ? "current" : "held" ?> state" >เตรียมชิ้นเนื้อ<br>พิเศษ8000</td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7"></td>



            </tr>
            <!-- end of first row -->

            <tr>
                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7" class="diagram_arrow_u_d"></td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7"</td>

            </tr>


            <!-- Second row in workflow diagram with the main workflow -->
            <tr border="1">
                <!--รอรับเข้า 1000-->
                <td colspan="7" tabindex="1000" id="keep1000" class=" <?= ($curstatus['0']["id"] == 1000) ? "current" : "held" ?> state">รับเข้า<br>1000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="2000" id="keep2000" class=" <?= ($curstatus['0']["id"] == 2000) ? "current" : "held" ?> state">วางแผนงาน<br>2000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7" tabindex="3000" id="keep3000" class="<?= ($curstatus['0']["id"] == 3000) ? "current" : "held" ?> state">เตรียมชิ้นเนื้อ<br>3000</td>

                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="6000" id="keep6000" class="<?= ($curstatus['0']["id"] == 6000) ? "current" : "held" ?> state">เตรียมสไลด์<br>6000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="12000" id="keep12000" class="<?= ($curstatus['0']["id"] == 12000) ? "current" : "held" ?> state">วินิจฉัย<br>12000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="13000" id="keep13000" class="<?= ($curstatus['0']["id"] == 13000) ? "current" : "held" ?> state">วินิจฉัย(คอนเฟิร์ม)<br>13000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="20000" id="keep20000" class="<?= ($curstatus['0']["id"] == 20000 || $patient[0]['reported_as'] != '') ? "completed" : "held" ?> state">ออกผล<br>20000</td>
            </tr>	<!-- end of Second row -->

            <tr>
                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="diagram_connector"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td colspan="" class="diagram_arrow_u"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>

                <td>&nbsp;</td>

                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="diagram_arrow_u"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>

            </tr>



            <!-- Third row in workflow diagram -->
            <tr>
                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="diagram_corner">&nbsp;</td>
                <td class="diagram_connector_horizontal">&nbsp;</td>
                <td class="diagram_connector_horizontal">&nbsp;</td>
                <td class="diagram_connector_horizontal">&nbsp;</td>

                <td class="diagram_arrow">&nbsp;</td>

                <td colspan="7" tabindex="10000" id="keep10000" class="<?= ($curstatus['0']["id"] == 10000) ? "current" : "held" ?> state">แลปเซลล์วิทยา<br>10000</td>

                <td class="diagram_connector_horizontal">&nbsp;</td>

                <td colspan="7" class="diagram_connector_horizontal">&nbsp;</td>

                <td class="diagram_connector_horizontal">&nbsp;</td>

                <td class="diagram_connector_horizontal">&nbsp;</td>
                <td class="diagram_connector_horizontal">&nbsp;</td>
                <td class="diagram_connector_horizontal">&nbsp;</td>
                <td class="diagram_corner_right_up">&nbsp;</td>             
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>


            </tr>
            <!-- end of third row -->


            <!--</tbody>-->
        </table>
    </form>
    <br>
    <div>
        <table class="flow_his" align="center">
            <tr class="flow_his"><th>สถานะไอดี</th><th class="flow_his">สถานะ</th><th class="flow_his" flow_his>วันที่เสร็จ</th></tr>
            <?php if (isset($patient[0]['date_1000'])): ?> <tr class="flow_his"><td>1000 </td><td class="flow_his">รับเข้า</td><td class="flow_his"> <?= $patient[0]['date_1000']; ?> </td></tr> <?php endif; ?>
            <?php if (isset($patient[0]['date_2000'])): ?> <tr class="flow_his"><td>2000</td><td class="flow_his">วางแผนงาน</td><td class="flow_his"> <?= $patient[0]['date_2000']; ?> </td></tr> <?php endif; ?>
            <?php if (isset($patient[0]['date_3000'])): ?> <tr class="flow_his"><td>3000</td><td class="flow_his">เตรียมชิ้นเนื้อ</td><td class="flow_his"><?= $patient[0]['date_3000']; ?> </td></tr> <?php endif; ?>
            <?php if (isset($patient[0]['date_6000'])): ?> <tr class="flow_his"><td>6000</td><td class="flow_his">เตรียมสไลด์</td><td class="flow_his"><?= $patient[0]['date_6000']; ?> </td></tr> <?php endif; ?>
            <?php if (isset($patient[0]['date_8000'])): ?> <tr class="flow_his"><td>8000</td><td class="flow_his">เตรียมชิ้นเนื้อพิเศษ</td><td class="flow_his"><?= $patient[0]['date_8000']; ?> </td></tr> <?php endif; ?>
            <?php if (isset($patient[0]['date_10000'])): ?> <tr class="flow_his"><td>10000</td><td class="flow_his">แลปเซลวิทยา</td><td class="flow_his"><?= $patient[0]['date_10000']; ?> </td></tr> <?php endif; ?>
            <?php if (isset($patient[0]['date_12000'])): ?> <tr class="flow_his"><td>12000</td><td class="flow_his">วินิจฉัย</td><td class="flow_his"><?= $patient[0]['date_12000']; ?> </td></tr> <?php endif; ?>
            <?php if (isset($patient[0]['date_13000'])): ?> <tr class="flow_his"><td>13000</td><td class="flow_his">วินิจฉัย(คอนเฟิร์ม)</td><td class="flow_his"><?= $patient[0]['date_13000']; ?> </td></tr> <?php endif; ?>

            <?php foreach ($presultupdates as $presultupdate): ?>
                <?php if (isset($presultupdate['id'])): ?> <tr class="flow_his"><td>20000</td><td class="flow_his"> <?= $presultupdate['result_type']; ?> </td><td class="flow_his"><?= $presultupdate['release_time']; ?> </td></tr> <?php endif; ?>
            <?php endforeach; ?>


        </table>
    </div>
<?php else: ?>
    <p align="center"  style="font-size:20px" ><span >กรุณากรอกข้อมูล หลังจากเสร็จแล้วให้กดปุ่ม Save all</span></p>
<?php endif; ?>