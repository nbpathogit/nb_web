

 <form id="formq" name="formq" method="post" action="q_db.php">
     <input id="q_sn_id" value="<?=$quesSN[0]['id']  ?>" hidden="">
    <div class="row <?= $isBorder ? "border" : "" ?>">

        <h3 align="center"> การประเมินคุณภาพสไลด์ชิ้นเนื้อ </h3>
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
                    <td height="30">&nbsp; 1.Thickness/Integrity</td>
                    <td height="30" align="center"><input type="radio" name="a1" id="a1" class="snqaprop"  value="1"  <?= $quesSN[0]['score_thickness'] == 1 ? "checked" : ""; ?> required="required" /></td>
                    <td height="30" align="center"><input type="radio" name="a1" id="a1"  class="snqaprop" value="2"  <?= $quesSN[0]['score_thickness'] == 2 ? "checked" : ""; ?> /></td>
                    <td height="30" align="center"><input type="radio" name="a1" id="a1"  class="snqaprop" value="3"  <?= $quesSN[0]['score_thickness'] == 3 ? "checked" : ""; ?> /></td>
                    <td height="30" align="center"><input type="radio" name="a1" id="a1"  class="snqaprop" value="4"  <?= $quesSN[0]['score_thickness'] == 4 ? "checked" : ""; ?> /></td>
                    <td height="30" align="center"><input type="radio" name="a1" id="a1"  class="snqaprop" value="5"  <?= $quesSN[0]['score_thickness'] == 5 ? "checked" : ""; ?> /></td>
                </tr>
                <tr class="border border-dark">
                    <td height="30">&nbsp; 2.Staining</td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a2" <?= $quesSN[0]['score_staining'] == 1 ? "checked" : ""; ?> value="1" required="required" /></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a2" <?= $quesSN[0]['score_staining'] == 2 ? "checked" : ""; ?> value="2"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a2" <?= $quesSN[0]['score_staining'] == 3 ? "checked" : ""; ?> value="3"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a2" <?= $quesSN[0]['score_staining'] == 4 ? "checked" : ""; ?> value="4"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a2" <?= $quesSN[0]['score_staining'] == 5 ? "checked" : ""; ?> value="5"/></td>
                </tr>
                <tr class="border border-dark">
                    <td height="30">&nbsp; 3.Mounting/Covering</td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a3" <?= $quesSN[0]['score_mounting'] == 1 ? "checked" : ""; ?> value="1" required="required" /></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a3" <?= $quesSN[0]['score_mounting'] == 2 ? "checked" : ""; ?> value="2"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a3" <?= $quesSN[0]['score_mounting'] == 3 ? "checked" : ""; ?> value="3"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a3" <?= $quesSN[0]['score_mounting'] == 4 ? "checked" : ""; ?> value="4"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a3" <?= $quesSN[0]['score_mounting'] == 5 ? "checked" : ""; ?> value="5"/></td>
                </tr>
                <tr class="border border-dark">
                    <td height="30">&nbsp; 4.Labeling</td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a4" <?= $quesSN[0]['score_labeling'] == 1 ? "checked" : ""; ?> value="1" required="required" /></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a4" <?= $quesSN[0]['score_labeling'] == 2 ? "checked" : ""; ?> value="2"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a4" <?= $quesSN[0]['score_labeling'] == 3 ? "checked" : ""; ?> value="3"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a4" <?= $quesSN[0]['score_labeling'] == 4 ? "checked" : ""; ?> value="4"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a4"  <?= $quesSN[0]['score_labeling'] == 5 ? "checked" : ""; ?>  value="5"/></td>
                </tr>
                <tr class="border border-dark">
                    <td height="30">&nbsp; 5.Contaminate</td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a5" <?= $quesSN[0]['score_contaminate'] == 1 ? "checked" : ""; ?> value="1" required="required" /></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a5" <?= $quesSN[0]['score_contaminate'] == 2 ? "checked" : ""; ?> value="2"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a5" <?= $quesSN[0]['score_contaminate'] == 3 ? "checked" : ""; ?> value="3"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a5" <?= $quesSN[0]['score_contaminate'] == 4 ? "checked" : ""; ?> value="4"/></td>
                    <td width="5%" height="30" align="center"><input type="radio" class="snqaprop" name="a5" <?= $quesSN[0]['score_contaminate'] == 5 ? "checked" : ""; ?> value="5"/></td>
                </tr>

            </table>
            <!-- Force next columns to break to new line -->
            <!--<div class="w-100"></div><br>-->
        </div>
        <div class="col-xl-6 col-lg-12 col-md-12 <?= $isBorder ? "border" : "" ?> ">
            <b> Comment </b><br>
            <textarea class="snqaprop" name="qcomment"  rows="6" id="qcomment" style="width:100%;" disabled=""><?=$quesSN[0]['note'];?></textarea>

        </div>
        <div class="w-100"></div>
        <br>
        <br>

        <div class="  text-center <?= $isBorder ? "border" : "" ?> " >
            <button  name="edit_quessn" id="edit_quessn" class="btn btn-primary" style="align-content:center;"  > Edit </button>
            <button type="submit" name="save_quessn" id="save_quessn" class="btn btn-primary" style="align-content:center;" disabled="" ><span id="span_save_quessn" role="status" aria-hidden="true"></span> Save </button>
        </div>
  

    </div>
</form>