
<form id="formq" name="formq" method="post" action="q_db.php">
<input id="q_sn_id" value="<?=$quesCN[0]['id']  ?>" hidden="">
<div class="row <?= $isBorder ? "border" : "" ?>">
    <h3 align="center"> การประเมินคุณภาพสไลด์ชิ้นเนื้อ  </h3>
    <div class="col-xl-6 col-lg-12 col-md-12 <?= $isBorder ? "border" : "" ?> ">


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
            <td height="30">&nbsp; 1.Specimen Procressing</td>
            <td height="30" align="center"><input type="radio" class="cnqaprop"  name="a1"  value="1" <?= $quesCN[0]['score_specimen']==1?"checked":"";?> required="required" /></td>
            <td height="30" align="center"><input type="radio" class="cnqaprop"  name="a1"  value="2" <?= $quesCN[0]['score_specimen']==2?"checked":"";?> /></td>
            <td height="30" align="center"><input type="radio" class="cnqaprop"  name="a1"  value="3" <?= $quesCN[0]['score_specimen']==3?"checked":"";?> /></td>
            <td height="30" align="center"><input type="radio" class="cnqaprop"  name="a1"  value="4" <?= $quesCN[0]['score_specimen']==4?"checked":"";?> /></td>
            <td height="30" align="center"><input type="radio" class="cnqaprop"  name="a1"  value="5" <?= $quesCN[0]['score_specimen']==5?"checked":"";?> /></td>
          </tr>
          <tr class="border border-dark">
            <td height="30">&nbsp; 2.Staining</td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a2"  value="1" <?= $quesCN[0]['score_staining']==1?"checked":"";?> required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a2"  value="2" <?= $quesCN[0]['score_staining']==2?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a2"  value="3" <?= $quesCN[0]['score_staining']==3?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a2"  value="4" <?= $quesCN[0]['score_staining']==4?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a2"  value="5" <?= $quesCN[0]['score_staining']==5?"checked":"";?> /></td>
          </tr>
          <tr class="border border-dark">
            <td height="30">&nbsp; 3.Mounting/Covering</td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a3"  value="1" <?= $quesCN[0]['score_mounting']==1?"checked":"";?> required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a3"  value="2" <?= $quesCN[0]['score_mounting']==2?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a3"  value="3" <?= $quesCN[0]['score_mounting']==3?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a3"  value="4" <?= $quesCN[0]['score_mounting']==4?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a3"  value="5" <?= $quesCN[0]['score_mounting']==5?"checked":"";?> /></td>
          </tr>
          <tr class="border border-dark">
            <td height="30">&nbsp; 4.Labeling</td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a4"  value="1" <?= $quesCN[0]['score_labeling']==1?"checked":"";?> required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a4"  value="2" <?= $quesCN[0]['score_labeling']==2?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a4"  value="3" <?= $quesCN[0]['score_labeling']==3?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a4"  value="4" <?= $quesCN[0]['score_labeling']==4?"checked":"";?> /></td>
            <td width="5%" height="30" align="center"><input type="radio" class="cnqaprop"  name="a4"  value="5" <?= $quesCN[0]['score_labeling']==5?"checked":"";?> /></td>
          </tr>

        </table>
    <!-- Force next columns to break to new line -->
    </div>
    <div class="col-xl-6 col-lg-12 col-md-12 <?= $isBorder ? "border" : "" ?> ">
    <div class="w-100"></div>
    <b> Comment </b><br>
    <textarea name="qcomment"  rows="6" id="qcomment" class="cnqaprop" style="width:100%;" disabled="" ><?=$quesCN[0]['note'];?></textarea>
    </div>
    
    <div class="w-100"></div>
    <br>
    <div class="  text-center <?= $isBorder ? "border" : "" ?> " >
        <button  name="edit_quescn" id="edit_quescn" class="btn btn-primary" style="align-content:center;"  > Edit </button>
        <button type="submit" name="save_quescn" id="save_quescn" class="btn btn-primary" style="align-content:center;" disabled="" ><span id="span_save_quescn" role="status" aria-hidden="true"></span> Save </button>
    </div>

  </div>
      
</form>