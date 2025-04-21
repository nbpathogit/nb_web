

<?php $hidden_data2dom = TRUE; ?>


<span id="data2DOM">
<!--Write Data to DOM pass value to java script-->

<?php if (isset($cur_user_id)): ?>
    <li class="cur_user_id" tabindex="<?= $cur_user_id ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$cur_user_id::<?= $cur_user_id ?> </li>
<?php endif; ?>

    
    
<?php if (isset($cur_user_id_name_lastname)): ?>
    <li class="cur_user_id_name_lastname" tabindex="<?= $cur_user_id_name_lastname ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$cur_user_id_name_lastname::<?= $cur_user_id_name_lastname ?> </li>
<?php endif; ?>
<?php if (isset($curstatus[0]['id'])): ?>
    <li class="cur_status" tabindex="<?= $curstatus[0]['id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >cur_status::$curstatus[0]['id']::<?= $curstatus[0]['id'] ?> </li>
    <li class="isset_date_first_report" tabindex="<?= $isset_date_first_report ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isset_date_first_report::$isset_date_first_report::<?= $isset_date_first_report ?> </li>
<?php endif; ?>
    
<?php if (isset( $presultupdates   )): ?>
<?php //List of index result (all) ?>
<ul class="uresultinxlist" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM f?>
        <li tabindex="<?= $prsu['id'] ?>">uresultinxlist::prsu['id']::<?= $prsu['id'] ?></li>
    <?php endforeach; ?> 
</ul>    
<?php endif; ?>   
  
<?php if (isset( $presultupdate1s   )): ?>  
<!--$presultupdate1s-->
<?php //List of index result (group1) ?>
<ul class="uresultinxlist1" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdate1s as $prsu1): // record uresultid to DOM ?>
        <li tabindex="<?= $prsu1['id'] ?>">uresultinxlist1::prsu1['id']::<?= $prsu1['id'] ?></li>
    <?php endforeach; ?> 
</ul>     
<?php endif; ?>


<?php if (isset( $presultupdate2s   )): ?>
<!--$presultupdate2s-->
<?php //List of index result (group2) 
$isCurrentPathoIsSecondOwneThisCase = false;?>
<ul class="uresultinxlist2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdate2s as $prsu2): // record uresultid to DOM ?>
        <li tabindex="<?= $prsu2['id'] ?>">uresultinxlist2::prsu2['id']::<?= $prsu2['id'] ?></li>
        <?php $isCurrentPathoIsSecondOwneThisCase = $_SESSION['user']->id == $prsu2['pathologist2_id']; ?>
    <?php endforeach; ?> 
</ul> 
<?php endif; ?>  


<?php if (isset( $presultupdate3s   )): ?>
<!--$presultupdate3s-->
<?php //List of index result (group3) 
$isCurrentPathoIsSecondOwneThisCaseForPN = false;?>
<ul class="uresultinxlist3" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdate3s as $prsu3): // record uresultid to DOM ?>
        <li tabindex="<?= $prsu3['id'] ?>">uresultinxlist3::prsu3['id']::<?= $prsu3['id'] ?></li>
        <?php $isCurrentPathoIsSecondOwneThisCaseForPN = $_SESSION['user']->id == $prsu3['pathologist2_id']; ?>
    <?php endforeach; ?> 
</ul> 
<?php endif; ?>  


<?php if (isset($isCurrentPathoIsOwnerThisCase)): ?>
    <li class="isCurrentPathoIsOwnerThisCase" tabindex="<?= $isCurrentPathoIsOwnerThisCase?true:false; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isCurrentPathoIsOwnerThisCase::<?= $isCurrentPathoIsOwnerThisCase?"1":"0"; ?> </li>
   <?php endif; ?>
    
<?php if (isset($isCurrentPathoIsSecondOwneThisCase)): ?>
      <li class="isCurrentPathoIsSecondOwneThisCaseLastest" tabindex="<?= $isCurrentPathoIsSecondOwneThisCase?"1":"0"; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isCurrentPathoIsSecondOwneThisCaseLastest::<?= $isCurrentPathoIsSecondOwneThisCase?"1":"0"; ?> </li>
<?php endif; ?>
      
<?php if (isset($isCurrentPathoIsSecondOwneThisCaseForPN)): ?>
      <li class="isCurrentPathoIsSecondOwneThisCaseLastestForPN" tabindex="<?= $isCurrentPathoIsSecondOwneThisCaseForPN?"1":"0"; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isCurrentPathoIsSecondOwneThisCaseLastestForPN::<?= $isCurrentPathoIsSecondOwneThisCaseForPN ?"1":"0"; ?> </li>
<?php endif; ?>
    
<?php if (isset($isSecondPathoDefined)): ?>
<!--$isSecondPathoDefined-->
    <li class="isSecondPathoDefined" tabindex="<?= $isSecondPathoDefined?true:false; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >$isSecondPathoDefined::<?= $isSecondPathoDefined?"true":"false"; ?> </li>
<?php endif; ?>


    
<?php if (isset( $isLastReleaseGroup2SecondPathoAval   )): ?> 
<?php //isLastReleaseGroup2SecondPathoAval?>   
<li class="isLastReleaseGroup2SecondPathoAval" tabindex="<?= $isLastReleaseGroup2SecondPathoAval; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$isLastReleaseGroup2SecondPathoAval::<?= $isLastReleaseGroup2SecondPathoAval; ?> </li>
<?php endif; ?> 
    
    
<?php //List of index result ?>
<ul class="uresultinxlist2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >

</ul>

<?php if (isset( $presultupdates   )): ?>
<?php //List of time result ?>
<?php $isLastedResultReleaseDateNULL = FALSE; ?>
<ul class="uresultReleaseSetlist" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
         <?php $isLastedResultReleaseDateNULL = isset($prsu['release_time']) ? FALSE : TRUE ; ?>
        <li tabindex="<?= isset($prsu['release_time']) ? 1 : 0 ?>">uresultReleaseSetlist::prsu['release_time']::<?= $prsu['release_time'] ?></li>
    <?php endforeach; ?> 
</ul> 
<?php endif; ?> 
    
<?php if (isset( $presultupdate2s   )): ?> 
<?php $isLastReleaseGroup2DateNull = false; ?>
<ul class="uresultReleaseSetlistGroup2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdate2s as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= isset($prsu['release_time']) ? 1 : 0 ?>">uresultReleaseSetlist2::prsu['release_time']::<?= $prsu['release_time'] ?></li>
        <?php $isLastReleaseGroup2DateNull = ($prsu['release_time']==NULL)?TRUE:FALSE; ?>
    <?php endforeach; ?> 
</ul> 
<?php endif; ?>

<?php if (isset( $isLastReleaseGroup2DateNull   )): ?>
<?php //$isLastReleaseGroup2DateNull  ?>   
<li class="isLastReleaseDateNull" tabindex="<?= $isLastReleaseGroup2DateNull; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  > $isLastReleaseGroup2DateNull::<?= $isLastReleaseGroup2DateNull; ?> </li>
<?php //var_dump($isLastReleaseGroup2DateNull); ?>
<?php endif; ?> 

<?php if (isset( $presultupdate3s   )): ?> 
<?php $isLastReleaseGroup3DateNull = false; ?>
<ul class="uresultReleaseSetlistGroup3" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdate3s as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= isset($prsu['release_time']) ? 1 : 0 ?>">uresultReleaseSetlist3::prsu['release_time']::<?= $prsu['release_time'] ?></li>
        <?php $isLastReleaseGroup3DateNull = ($prsu['release_time']==NULL)?TRUE:FALSE; ?>
    <?php endforeach; ?> 
</ul> 
<?php endif; ?>

<?php if (isset( $isLastReleaseGroup3DateNull   )): ?>
<?php //$isLastReleaseGroup3DateNull  ?>   
<li class="isLastReleaseDateNull" tabindex="<?= $isLastReleaseGroup3DateNull; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  > $isLastReleaseGroup3DateNull::<?= $isLastReleaseGroup3DateNull; ?> </li>
<?php //var_dump($isLastReleaseGroup3DateNull); ?>
<?php endif; ?> 

<?php if (isset( $presultupdates   )): ?>
<?php //List of release_type ยังไม่ออกผล/ออกผลแล้ว/ออกผลเบื้องต้น ?>
<ul class="uresultReleaseType" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= $prsu['release_type'] ?>">uresultReleaseType::prsu['release_type']::<?= $prsu['release_type'] ?></li>
    <?php endforeach; ?> 
</ul>
<?php endif; ?> 

<ul class="uresultReleaseType2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
</ul>  

<?php if (isset( $presultupdates   )): ?> 
<?php //List of result type  //Specimen ,Pathology , Revise Addendum ?>
<ul class="uresultTypeName" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= $prsu['result_type'] ?>">uresultTypeName::prsu['result_type']::<?= $prsu['result_type'] ?></li>
    <?php endforeach; ?> 
</ul>
<?php endif; ?>

<?php if (isset( $patient[0]['p_slide_prep_sp_id']   )): ?>
<?php //List of special result ?>
<ul class="p_slide_prep_sp_id" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <li tabindex="<?= $patient[0]['p_slide_prep_sp_id'] ?>">p_slide_prep_sp_id::patient[0]['p_slide_prep_sp_id']::<?= $patient[0]['p_slide_prep_sp_id'] ?></li>
</ul>
<?php endif; ?> 

<?php if (isset( $presultupdates   )): ?>
<?php //List of second patho ?>
<ul class="uresultSecondPatho" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= ($prsu['pathologist2_id']) ?>">uresultSecondPatho::prsu['pathologist2_id']::<?= $prsu['pathologist2_id'] ?></li>
    <?php endforeach; ?> 
</ul>
<?php endif; ?> 

<ul class="uresultSecondPatho2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >

</ul>
 

<!--<ul class="uresultReleaseType2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >-->
<!--</ul>--> 
    
<?php if (isset( $patient[0]['reported_as']   )): ?>
<?php //List of lastest reported_as result ?>
<ul class="reported_as" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <li tabindex="<?= $patient[0]['reported_as'] ?>">reported_as::patient[0]['reported_as']::<?= $patient[0]['reported_as'] ?></li>
</ul>
<?php endif; ?> 

<?php if (isset( $patient[0]['reported_name']   )): ?>
<?php //List of lastest reported_name ?>
<ul class="reported_name" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <li tabindex="<?= $patient[0]['reported_name'] ?>">reported_name::patient[0]['reported_name']::<?= $patient[0]['reported_name'] ?></li>
</ul>
<?php endif; ?> 

<?php if (isset( $patient[0]['pautoscroll']   )): ?>
<?php //["pautoscroll"] ?>
<li class="pautoscroll" tabindex="<?= $patient[0]['pautoscroll'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pautoscroll']::<?= $patient[0]['pautoscroll'] ?> </li>
<?php endif; ?> 
    
      
<?php if (isset( $patient[0]['isautoeditmode']   )): ?>
<?php //    $patient[0]['p_speciment_type']?>   
<li class="isautoeditmode" tabindex="<?= $patient[0]['isautoeditmode'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['isautoeditmode']::<?= $patient[0]['isautoeditmode'] ?> </li>
<?php endif; ?>     

<?php if (isset( $patient[0]['p_speciment_type']   )): ?>
<?php //    $patient[0]['p_speciment_type']?>   
<li class="cur_speciment_type" tabindex="<?= $patient[0]['p_speciment_type'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['p_speciment_type']::<?= $patient[0]['p_speciment_type'] ?> </li>
<?php endif; ?> 

<?php if (isset( $patient[0]['id']   )): ?> 
<?php //    $patient[0]['id']?>   
<li class="cur_patient_id" tabindex="<?= $patient[0]['id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  > class="cur_patient_id" $patient[0]['id']::<?= $patient[0]['id'] ?> </li>
<?php endif; ?>

<?php if (isset( $patient[0]['pnum']   )): ?>
<?php //    $patient[0]['pnum']?>   
<li class="cur_pnum" tabindex="<?= $patient[0]['pnum'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pnum']::<?= $patient[0]['pnum'] ?> </li>
<?php endif; ?> 

<?php if (isset( $patient[0]['pname']   )): ?> 
<?php //    $patient[0]['pname']?>   
<li class="cur_pname" tabindex="<?= $patient[0]['pname'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pname']::<?= $patient[0]['pname'] ?> </li>
<?php endif; ?>

<?php if (isset( $patient[0]['plastname']   )): ?>
<?php //    $patient[0]['plastname']?>   
<li class="cur_plastname" tabindex="<?= $patient[0]['plastname'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['plastname']::<?= $patient[0]['plastname'] ?> </li>
<?php endif; ?> 

<?php if (isset( $patient[0]['date_1000']   )): ?> 
<?php //    $patient[0]['date_1000']?>   
<li class="cur_date_1000" tabindex="<?= $patient[0]['date_1000'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['date_1000']::<?= $patient[0]['date_1000'] ?> </li>
<?php endif; ?>

<?php if (isset( $patient[0]['phospital_id']   )): ?>
<?php //    $patient[0]['phospital_id']?>   
<li class="cur_phospital_id" tabindex="<?= $patient[0]['phospital_id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['phospital_id']::<?= $patient[0]['phospital_id'] ?> </li>
<?php endif; ?> 

<?php if (isset( $patient[0]['phospital_num']   )): ?> 
<?php //    $patient[0]['phospital_id']?>   
<li class="cur_phospital_num" tabindex="<?= $patient[0]['phospital_num'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['phospital_num']::<?= $patient[0]['phospital_num'] ?> </li>
<?php endif; ?>

<?php if (isset( $patient[0]['pspecimen_id']   )): ?>
<?php //    $patient[0]['pspecimen_id']?>   
<li class="cur_pspecimen_id" tabindex="<?= $patient[0]['pspecimen_id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pspecimen_id']::<?= $patient[0]['pspecimen_id'] ?> </li>
<?php endif; ?>

<?php if (isset( $patient[0]['pclinician_id']   )): ?>
<?php //    $patient[0]['pclinician_id']?>   
<li class="cur_pclinician_id" tabindex="<?= $patient[0]['pclinician_id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pclinician_id']::<?= $patient[0]['pclinician_id'] ?> </li>
<?php endif; ?> 

<?php if (isset( $job6s   )): ?>
<?php ///$job6s]?>  
<?php //List of second patho ?>
<ul class="job6_id" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($job6s as $job6): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= ($job6['id']) ?>">job6s::job6['id']::<?= $job6['id'] ?></li>
    <?php endforeach; ?> 
</ul>
<?php endif; ?> 

<ul class="job6_id2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >

</ul>


<?php // user id use in useredit.php ?>   
<?php if (isset( $user[0]['uid']   )): ?>
<li class="user_id_for_edit" tabindex="<?= $user[0]['uid'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >class::user_id_for_edit::$user[0]['uid']::<?= $user[0]['uid'] ?> </li>
<?php endif; ?>

<?php // user id use in useredit.php ?>   
<?php if (isset( $isCurUserCust   )): ?>
<li class="isCurUserCust" tabindex="<?= $isCurUserCust ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  > isCurUserCust::<?= $isCurUserCust; ?> </li>
<?php endif; ?>


</span>
