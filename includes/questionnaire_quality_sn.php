<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
<div class="container">
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-7">
      <h3 align="center"> การประเมินคุณภาพสไลด์ชิ้นเนื้อ  </h3>
      <form id="formq" name="formq" method="post" action="q_db.php">
        <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="75%" rowspan="2" align="center"><strong>Slide Evaluation Checklist</strong></td>
            <td colspan="5" align="center"><strong>ระดับคะแนน</strong></td>
          </tr>
          <tr>
            <td width="5%" align="center"><strong>1</strong></td>
            <td width="5%" align="center"><strong>2</strong></td>
            <td width="5%" align="center"><strong>3</strong></td>
            <td width="5%" align="center"><strong>4</strong></td>
            <td width="5%" align="center"><strong>5</strong></td>
          </tr>

          <tr>
            <td height="30">&nbsp; 1.Thickness/Integrity</td>
            <td height="30" align="center"><input type="radio" name="a1"  value="1" required="required" /></td>
            <td height="30" align="center"><input type="radio" name="a1"  value="2" /></td>
            <td height="30" align="center"><input type="radio" name="a1"  value="3" /></td>
            <td height="30" align="center"><input type="radio" name="a1"  value="4" /></td>
            <td height="30" align="center"><input type="radio" name="a1"  value="5" checked/></td>
          </tr>
          <tr>
            <td height="30">&nbsp; 2.Staining</td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2"  value="1" required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2"  value="2"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2"  value="3"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2"  value="4"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a2"  value="5"/></td>
          </tr>
          <tr>
            <td height="30">&nbsp; 3.Mounting/Covering</td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3"  value="1" required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3"  value="2"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3"  value="3"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3"  value="4"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a3"  value="5"/></td>
          </tr>
          <tr>
            <td height="30">&nbsp; 4.Labeling</td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4"  value="1" required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4"  value="2"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4"  value="3"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4"  value="4"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a4"  value="5"/></td>
          </tr>
          <tr>
            <td height="30">&nbsp; 5.Contaminate</td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5"  value="1" required="required" /></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5"  value="2"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5"  value="3"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5"  value="4"/></td>
            <td width="5%" height="30" align="center"><input type="radio" name="a5"  value="5"/></td>
          </tr>
          
        </table>
        <b> Note </b>
        <textarea name="detail" cols="90" rows="3" id="detail"></textarea>
        <br>
        <br>
        <button type="submit" name="save" class="btn btn-primary"> Save </button>
      </form>
      <br /><br />
    </div>
  </div>
</div>
</body>
</html>