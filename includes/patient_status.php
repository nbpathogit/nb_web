<?php
$isBorder = true;
?>

<h4 align="center"><b>สถานะงาน</b></h4>
<form  id="" name="" method="post">
    <div align="center">
        <button name="status" class="btn btn-warning" disabled><b> สถานะปัจจุบัน :  <?= $curstatus['0']["des"] ?> </b> </button>
    </div>
    <div align="center">
        <b> เปลี่ยนสถานะไปเป็น >>  </b>

        <?php if ($back2status != null) { ?>
            <button name="status" value="<?= $back2status['0']["id"] ?>" class="btn btn-secondary" ><b> <?= $back2status['0']["des"] ?>  </b> </button>
        <?php } ?>
        <?php if ($back1status != null) { ?>
            <button name="status" value="<?= $back1status['0']["id"] ?>" class="btn btn-secondary" ><b> <?= $back1status['0']["des"] ?> </b>  </button>
        <?php } ?>

        <?php if ($next1status != null) { ?>
            <button name="status" value="<?= $next1status['0']["id"] ?>" class="btn btn-secondary" ><b> <?= $next1status['0']["des"] ?> </b> </button>
        <?php } ?>
        <?php if ($next2status != null) { ?>
            <button name="status" value="<?= $next2status['0']["id"] ?>"class="btn btn-secondary" ><b> <?= $next2status['0']["des"] ?> </b> </button>
        <?php } ?>
    </div>
</form>
<!--array(1) { [0]=> array(6) { ["id"]=> string(4) "2000" ["des"]=> string(21) "รับเข้า" ["net1"]=> string(4) "3000" ["next2"]=> string(5) "12000" ["back1"]=> NULL ["back2"]=> NULL } }-->

<form id="formflow">

    <table id="WorkflowDiagramTable" class="workflow_diagram" cellspacing="0" cellpadding="0" >
        <tbody>
            <!-- First row in workflow diagram with the main workflow -->
            <tr border="1">
                <td colspan="7" id="move1000" class=" <?= ($curstatus['0']["id"] == 1000) ? "current" : "held" ?> state">รอรับเข้า</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7" id="move2000" class=" <?= ($curstatus['0']["id"] == 2000) ? "current" : "held" ?> state">รับเข้า</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7" id="move3000" class="<?= ($curstatus['0']["id"] == 3000) ? "current" : "held" ?> state">เตรียมชิ้นเนื้อ(ศัลยพยาธิ)</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7" id="move6000" class="<?= ($curstatus['0']["id"] == 6000) ? "current" : "held" ?> state">เตรียมสไลด์&nbsp;(จุลพยาธิวิทยา)</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7" id="move12000" class="<?= ($curstatus['0']["id"] == 12000) ? "current" : "held" ?> state">อ่านไสลด์&nbsp;วินิจฉัย</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7" id="move13000" class="<?= ($curstatus['0']["id"] == 13000) ? "current" : "held" ?> state">แพทย์คนที่สองคอนเฟิร์ม</td>
                <td class="diagram_arrow">&nbsp;</td>
                <td colspan="7" id="move20000" class="<?= ($curstatus['0']["id"] == 20000) ? "completed" : "held" ?> state">ออกผล</td>
            </tr>	<!-- end of first row -->

            <tr>
                <td colspan="7"></td>

                <td>&nbsp;</td>

                <td style="border:1px">&nbsp;</td>
                <td style="border:1px">&nbsp;</td>
                <td style="border:1px">&nbsp;</td>
                <td class="diagram_connector">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>

            </tr>

            <!-- Second row in workflow diagram -->
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

                <td colspan="7" class="held state" >แลปเซลล์วิทยา</td>

                <td class="diagram_connector_horizontal">&nbsp;</td>

                <td colspan="7" class="diagram_connector_horizontal">&nbsp;</td>

                <td class="diagram_connector_horizontal">&nbsp;</td>

                <td colspan="7" class="diagram_connector_horizontal">&nbsp;</td>

                <td class="diagram_connector_horizontal">&nbsp;</td>

                <td colspan="7" class="diagram_connector_horizontal">&nbsp;</td>

                <td class="diagram_connector_horizontal">&nbsp;</td>



                <td class="diagram_connector_horizontal">&nbsp;</td>
                <td class="diagram_connector_horizontal">&nbsp;</td>
                <td class="diagram_connector_horizontal">&nbsp;</td>
                <td class="diagram_corner_right_up_arrow">&nbsp;</td>             
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>


            </tr>
            <!-- end of second row -->


        </tbody>
    </table>
</form>