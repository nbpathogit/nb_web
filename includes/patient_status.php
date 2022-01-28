<?php
$isBorder = FALSE;
?>

<!--Write Data to DOM pass value to java script-->
<?php if (isset($curstatus[0]['id'])): ?>
    <li class="cur_status" tabindex="<?= $curstatus[0]['id'] ?>" style="display: none;" > <?= $curstatus[0]['id'] ?> </li>
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

<h4 align="center"><b>สถานะงาน</b></h4>
<form  id="" name="" method="post">
    <div align="center">
        สถานะงานปัจจุบัน : 
        <button name="status" class="btn btn-warning" disabled><b> <?= $curstatus['0']["des"] ?> </b> </button>
        <!--    </div>
            <div align="center">-->
        <?php if ($modePageEditDisable) : ?> 
            <b> &nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เปลี่ยนสถานะไปเป็น >> </b>

            <?php if ($back2status != null) : ?> 
                <button name="status" value="<?= $back2status['0']["id"] ?>" class="btn btn-secondary" ><b> <?= $back2status['0']["des"] ?> </b> </button>
            <?php endif; ?>

            <?php if ($back1status != null) : ?> 
                <button name="status" value="<?= $back1status['0']["id"] ?>" class="btn btn-secondary" ><b> <?= $back1status['0']["des"] ?> </b>  </button>
            <?php endif; ?>

            <?php if ($next1status != null) : ?> 
                <button name="status" value="<?= $next1status['0']["id"] ?>" class="btn btn-secondary" ><b> <?= $next1status['0']["des"] ?> </b> </button>
            <?php endif; ?>

            <?php if ($next2status != null) : ?> 
                <button name="status" value="<?= $next2status['0']["id"] ?>"class="btn btn-secondary" ><b> <?= $next2status['0']["des"] ?> </b> </button>
            <?php endif; ?>

            <?php if ($next3status != null) : ?> 
                <button name="status" value="<?= $next3status['0']["id"] ?>"class="btn btn-secondary" ><b> <?= $next3status['0']["des"] ?> </b> </button>
            <?php endif; ?>y
            <input type="hidden" name="cur_status" value="<?= $curstatus[0]['id'] ?>" />;
        <?php endif; ?>

    </div>
</form>


<?php if ($modePageEditDisable) : ?>
    <hr>
    <h4 align="center"><b>ไดอแกรม แสดงสถานะงาน</b></h4>

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

                <td colspan="7"  tabindex="14000" id="keep14000" class="<?= ($curstatus['0']["id"] == 14000) ? "current" : "held" ?> state" >ออกผลเพิ่มเติม<br>14000</td>



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

                <td colspan="7" class="diagram_arrow_u_d"></td>

            </tr>


            <!-- Second row in workflow diagram with the main workflow -->
            <tr border="1">
                <!--รอรับเข้า 1000-->
                <td colspan="7" tabindex="1000" id="keep1000" class=" <?= ($curstatus['0']["id"] == 1000) ? "current" : "held" ?> state">รับเข้า<br>1000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="2000" id="keep2000" class=" <?= ($curstatus['0']["id"] == 2000) ? "current" : "held" ?> state">กำหนดงาน<br>2000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7" tabindex="3000" id="keep3000" class="<?= ($curstatus['0']["id"] == 3000) ? "current" : "held" ?> state">เตรียมชิ้นเนื้อ<br>3000</td>

                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="6000" id="keep6000" class="<?= ($curstatus['0']["id"] == 6000) ? "current" : "held" ?> state">เตรียมสไลด์<br>6000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="12000" id="keep12000" class="<?= ($curstatus['0']["id"] == 12000) ? "current" : "held" ?> state">วินิจฉัย<br>12000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="13000" id="keep13000" class="<?= ($curstatus['0']["id"] == 13000) ? "current" : "held" ?> state">วินิจฉัย(คอนเฟิร์ม)<br>13000</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7"  tabindex="20000" id="keep20000" class="<?= ($curstatus['0']["id"] == 20000) ? "completed" : "held" ?> state">ออกผล<br>20000</td>
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

<?php endif; ?>