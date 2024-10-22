

 
<div class="row <?= $isBorder ? "border" : "" ?>">
    <h3 align="center"> การประเมินคุณภาพสไลด์ชิ้นเนื้อ (กำลังพัฒนา ยังไม่พร้อมใช้งาน) </h3>
    <div class="col-xl-12 col-md-6 <?= $isBorder ? "border" : "" ?> ">

      <form id="formq" name="formq" method="post" action="q_db.php">
        <table  border="1" align="center" cellpadding="0" cellspacing="0" style="width:500px">
          <tr class="border border-dark">
            <td width="60%" rowspan="2" align="center"><strong>Slide Evaluation Checklist</strong></td>
            <td colspan="5" align="center"><strong>ระดับคะแนน</strong></td>
          </tr>
          <tr class="border border-dark">
            <td width="8%" align="center"><strong>1</strong></td>
            <td width="8%" align="center"><strong>2</strong></td>
            <td width="8%" align="center"><strong>3</strong></td>
            <td width="8%" align="center"><strong>4</strong></td>
            <td width="8%" align="center"><strong>5</strong></td>
          </tr>

          <tr class="border border-dark">
            <td height="30">&nbsp; 1.Thickness/Integrity</td>
            <td height="30" align="center"><input type="radio" name="a1"  value="1"  <?= $quesSN[0]['score_thickness']==1?"checked":"";?> required="required" /></td>
            <td height="30" align="center"><input type="radio" name="a1"  value="2"  <?= $quesSN[0]['score_thickness']==2?"checked":"";?> /></td>
            <td height="30" align="center"><input type="radio" name="a1"  value="3"  <?= $quesSN[0]['score_thickness']==3?"checked":"";?> /></td>
            <td height="30" align="center"><input type="radio" name="a1"  value="4"  <?= $quesSN[0]['score_thickness']==4?"checked":"";?> /></td>
            <td height="30" align="center"><input type="radio" name="a1"  value="5"  <?= $quesSN[0]['score_thickness']==5?"checked":"";?> /></td>
          </tr>
          <tr class="border border-dark">
            <td height="30">&nbsp; 2.Staining</td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2" <?= $quesSN[0]['score_staining']==1?"checked":"";?> value="1" required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2" <?= $quesSN[0]['score_staining']==2?"checked":"";?> value="2"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2" <?= $quesSN[0]['score_staining']==3?"checked":"";?> value="3"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2" <?= $quesSN[0]['score_staining']==4?"checked":"";?> value="4"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2" <?= $quesSN[0]['score_staining']==5?"checked":"";?> value="5"/></td>
          </tr>
          <tr class="border border-dark">
            <td height="30">&nbsp; 3.Mounting/Covering</td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3" <?= $quesSN[0]['score_mounting']==1?"checked":"";?> value="1" required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3" <?= $quesSN[0]['score_mounting']==2?"checked":"";?> value="2"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3" <?= $quesSN[0]['score_mounting']==3?"checked":"";?> value="3"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3" <?= $quesSN[0]['score_mounting']==4?"checked":"";?> value="4"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3" <?= $quesSN[0]['score_mounting']==5?"checked":"";?> value="5"/></td>
          </tr>
          <tr class="border border-dark">
            <td height="30">&nbsp; 4.Labeling</td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4" <?= $quesSN[0]['score_labeling']==1?"checked":"";?> value="1" required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4" <?= $quesSN[0]['score_labeling']==2?"checked":"";?> value="2"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4" <?= $quesSN[0]['score_labeling']==3?"checked":"";?> value="3"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4" <?= $quesSN[0]['score_labeling']==4?"checked":"";?> value="4"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4"  <?= $quesSN[0]['score_labeling']==5?"checked":"";?>  value="5"/></td>
          </tr>
          <tr class="border border-dark">
            <td height="30">&nbsp; 5.Contaminate</td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5" <?= $quesSN[0]['score_contaminate']==1?"checked":"";?> value="1" required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5" <?= $quesSN[0]['score_contaminate']==2?"checked":"";?> value="2"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5" <?= $quesSN[0]['score_contaminate']==3?"checked":"";?> value="3"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5" <?= $quesSN[0]['score_contaminate']==4?"checked":"";?> value="4"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5" <?= $quesSN[0]['score_contaminate']==5?"checked":"";?> value="5"/></td>
          </tr>
          
        </table>
    <!-- Force next columns to break to new line -->
    <div class="w-100"></div><br>
    <b> Comment </b><br>
    <textarea name="detail"  rows="3" id="qdetail" style="width:100%;"></textarea>
        <br>
        <br>
        <button type="submit" name="save_quessn" id="save_quessn" class="btn btn-primary" style="align-content:center;" disabled > Save </button>
      </form>

    </div>
</div>

