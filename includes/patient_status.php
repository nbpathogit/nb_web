<h4 align="center"><b>สถานะงาน</b></h4>
<form  id="" name="" method="post">
    <div align="center">
        <button name="status" class="btn btn-warning" disabled><b> สถานะปัจจุบัน :  <?= $curstatus['0']["des"] ?> </b> </button>
    </div>
    <div align="center">
        <b> เปลี่ยนสถานะกลับไปเป็น >>  </b>
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



	<table id="WorkflowDiagramTable" class="workflow_diagram" cellspacing="0" cellpadding="0">
	<tbody>
		<!-- First row in workflow diagram with the main workflow -->
		<tr>

			<td colspan="50" class="completed state">รอรับเข้า</td>

			<input type="hidden" name="WflCurStateId" value="CRB / Impact Assessment">

			<td class="diagram_arrow">&nbsp;</td>

			<td colspan="7" class="completed state">รับเข้า</td>

			<input type="hidden" name="WflCurStateId" value="CRB / Impact Assessment">

			<td class="diagram_arrow">&nbsp;</td>

			<td colspan="14" class="current state">เตรียมชิ้นเนื้อ(ศัลยพยาธิ)</td>

			<input type="hidden" name="WflCurStateId" value="CRB / Impact Assessment">

			<td class="diagram_arrow">&nbsp;</td>

			<td colspan="7" class="held nohover state">เตรียมสไลด์&nbsp;(จุลพยาธิวิทยา)</td>

			<input type="hidden" name="WflCurStateId" value="CRB / Impact Assessment">

			<td class="diagram_arrow">&nbsp;</td>

			<td colspan="7" class="held nohover state">อ่านไสลด์วินิจฉัย</td>

			<input type="hidden" name="WflCurStateId" value="CRB / Impact Assessment">

			<td class="diagram_arrow">&nbsp;</td>

			<td colspan="7" class="held nohover state">แพทย์คนที่สองคอนเฟิร์ม</td>

			<input type="hidden" name="WflCurStateId" value="CRB / Impact Assessment">

			<td class="diagram_arrow">&nbsp;</td>

			<td colspan="7" class="held nohover state">ออกผล</td>

			<input type="hidden" name="WflCurStateId" value="CRB / Impact Assessment">

		</tr>	<!-- end of first row -->


	</tbody>
	</table>
