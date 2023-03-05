

<?php $hidden_data2dom = true; ?>
<span id="data2DOM">
<!--Write Data to DOM pass value to java script-->
<?php if (isset($curstatus[0]['id'])): ?>
    <li class="cur_status" tabindex="<?= $curstatus[0]['id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >cur_status::$curstatus[0]['id']::<?= $curstatus[0]['id'] ?> </li>
    <li class="isset_date_first_report" tabindex="<?= $isset_date_first_report ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isset_date_first_report::$isset_date_first_report::<?= $isset_date_first_report ?> </li>
<?php endif; ?>
    
<?php if (isset($isCurrentPathoIsOwnerThisCase)): ?>
    <li class="isCurrentPathoIsOwnerThisCase" tabindex="<?= $isCurrentPathoIsOwnerThisCase?true:false; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isCurrentPathoIsOwnerThisCase::<?= $isCurrentPathoIsOwnerThisCase?"1":"0"; ?> </li>
    <li class="isCurrentPathoIsSecondOwneThisCaseLastest" tabindex="<?= $isCurrentPathoIsSecondOwneThisCase?"1":"0"; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isCurrentPathoIsSecondOwneThisCaseLastest::<?= $isCurrentPathoIsSecondOwneThisCase?"1":"0"; ?> </li>
<?php endif; ?>
    
<?php if (isset($isSecondPathoDefined)): ?>
<!--$isSecondPathoDefined-->
    <li class="isSecondPathoDefined" tabindex="<?= $isSecondPathoDefined?true:false; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >$isSecondPathoDefined::<?= $isSecondPathoDefined?"true":"false"; ?> </li>
<?php endif; ?>
        
<?php //List of index result (all) ?>
<ul class="uresultinxlist" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM f?>
        <li tabindex="<?= $prsu['id'] ?>">uresultinxlist::prsu['id']::<?= $prsu['id'] ?></li>
    <?php endforeach; ?> 
</ul>
  
    
<!--$presultupdate1s-->
<?php //List of index result (group1) ?>
<ul class="uresultinxlist1" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdate1s as $prsu1): // record uresultid to DOM ?>
        <li tabindex="<?= $prsu1['id'] ?>">uresultinxlist1::prsu1['id']::<?= $prsu1['id'] ?></li>
    <?php endforeach; ?> 
</ul>     

    
<!--$presultupdate2s-->
<?php //List of index result (group2) ?>
<ul class="uresultinxlist2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdate2s as $prsu2): // record uresultid to DOM ?>
        <li tabindex="<?= $prsu2['id'] ?>">uresultinxlist2::prsu2['id']::<?= $prsu2['id'] ?></li>
    <?php endforeach; ?> 
</ul> 

<?php //isLastReleaseGroup2SecondPathoAval?>   
<li class="isLastReleaseGroup2SecondPathoAval" tabindex="<?= $isLastReleaseGroup2SecondPathoAval; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$isLastReleaseGroup2SecondPathoAval::<?= $isLastReleaseGroup2SecondPathoAval; ?> </li>

    
    
<?php //List of index result ?>
<ul class="uresultinxlist2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >

</ul>

<?php //List of time result ?>
<?php $isLastedResultReleaseDateNULL = FALSE; ?>
<ul class="uresultReleaseSetlist" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
         <?php $isLastedResultReleaseDateNULL = isset($prsu['release_time']) ? FALSE : TRUE ; ?>
        <li tabindex="<?= isset($prsu['release_time']) ? 1 : 0 ?>">uresultReleaseSetlist::prsu['release_time']::<?= $prsu['release_time'] ?></li>
    <?php endforeach; ?> 
</ul> 
    
    
<?php $isLastReleaseGroup2DateNull = "blank"; ?>
<ul class="uresultReleaseSetlistGroup2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdate2s as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= isset($prsu['release_time']) ? 1 : 0 ?>">uresultReleaseSetlist2::prsu['release_time']::<?= $prsu['release_time'] ?></li>
        <?php $isLastReleaseGroup2DateNull = $prsu['release_time']; ?>
    <?php endforeach; ?> 
</ul> 

<?php //$isLastReleaseGroup2DateNull  ?>   
<li class="isLastReleaseDateNull" tabindex="<?= $isLastReleaseGroup2DateNull; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  > $isLastReleaseGroup2DateNull::<?= $isLastReleaseGroup2DateNull; ?> </li>
<?php //var_dump($isLastReleaseGroup2DateNull); ?>

<?php //List of release_type ยังไม่ออกผล/ออกผลแล้ว/ออกผลเบื้องต้น ?>
<ul class="uresultReleaseType" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= $prsu['release_type'] ?>">uresultReleaseType::prsu['release_type']::<?= $prsu['release_type'] ?></li>
    <?php endforeach; ?> 
</ul>
<ul class="uresultReleaseType2" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
</ul>  

<?php //List of result type  //Specimen ,Pathology , Revise Addendum ?>
<ul class="uresultTypeName" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= $prsu['result_type'] ?>">uresultTypeName::prsu['result_type']::<?= $prsu['result_type'] ?></li>
    <?php endforeach; ?> 
</ul>

<?php //List of special result ?>
<ul class="p_slide_prep_sp_id" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <li tabindex="<?= $patient[0]['p_slide_prep_sp_id'] ?>">p_slide_prep_sp_id::patient[0]['p_slide_prep_sp_id']::<?= $patient[0]['p_slide_prep_sp_id'] ?></li>
</ul>

<?php //List of second patho ?>
<ul class="uresultSecondPatho" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= ($prsu['pathologist2_id']) ?>">uresultSecondPatho::prsu['pathologist2_id']::<?= $prsu['pathologist2_id'] ?></li>
    <?php endforeach; ?> 
</ul>
    
<?php //List of lastest reported_as result ?>
<ul class="reported_as" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <li tabindex="<?= $patient[0]['reported_as'] ?>">reported_as::patient[0]['reported_as']::<?= $patient[0]['reported_as'] ?></li>
</ul>

<?php //List of lastest reported_name ?>
<ul class="reported_name" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <li tabindex="<?= $patient[0]['reported_name'] ?>">reported_name::patient[0]['reported_name']::<?= $patient[0]['reported_name'] ?></li>
</ul>

<?php //["pautoscroll"] ?>
<li class="pautoscroll" tabindex="<?= $patient[0]['pautoscroll'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pautoscroll']::<?= $patient[0]['pautoscroll'] ?> </li>

    
      
<?php //    $patient[0]['p_speciment_type']?>   
<li class="isautoeditmode" tabindex="<?= $patient[0]['isautoeditmode'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['isautoeditmode']::<?= $patient[0]['isautoeditmode'] ?> </li>
    
<?php //    $patient[0]['p_speciment_type']?>   
<li class="cur_speciment_type" tabindex="<?= $patient[0]['p_speciment_type'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['p_speciment_type']::<?= $patient[0]['p_speciment_type'] ?> </li>

<?php //    $patient[0]['id']?>   
<li class="cur_patient_id" tabindex="<?= $patient[0]['id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['id']::<?= $patient[0]['id'] ?> </li>

<?php //    $patient[0]['pnum']?>   
<li class="cur_pnum" tabindex="<?= $patient[0]['pnum'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pnum']::<?= $patient[0]['pnum'] ?> </li>

<?php //    $patient[0]['pname']?>   
<li class="cur_pname" tabindex="<?= $patient[0]['pname'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pname']::<?= $patient[0]['pname'] ?> </li>

<?php //    $patient[0]['plastname']?>   
<li class="cur_plastname" tabindex="<?= $patient[0]['plastname'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['plastname']::<?= $patient[0]['plastname'] ?> </li>

<?php //    $patient[0]['date_1000']?>   
<li class="cur_date_1000" tabindex="<?= $patient[0]['date_1000'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['date_1000']::<?= $patient[0]['date_1000'] ?> </li>

<?php //    $patient[0]['phospital_id']?>   
<li class="cur_phospital_id" tabindex="<?= $patient[0]['phospital_id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['phospital_id']::<?= $patient[0]['phospital_id'] ?> </li>

<?php //    $patient[0]['phospital_id']?>   
<li class="cur_phospital_num" tabindex="<?= $patient[0]['phospital_num'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['phospital_num']::<?= $patient[0]['phospital_num'] ?> </li>

<?php //    $patient[0]['pspecimen_id']?>   
<li class="cur_pspecimen_id" tabindex="<?= $patient[0]['pspecimen_id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pspecimen_id']::<?= $patient[0]['pspecimen_id'] ?> </li>


<?php //    $patient[0]['pclinician_id']?>   
<li class="cur_pclinician_id" tabindex="<?= $patient[0]['pclinician_id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >$patient[0]['pclinician_id']::<?= $patient[0]['pclinician_id'] ?> </li>





</span>
