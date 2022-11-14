
<?php $hidden_data2dom = true; ?>
<!--Write Data to DOM pass value to java script-->
<?php if (isset($curstatus[0]['id'])): ?>
    <li class="cur_status" tabindex="<?= $curstatus[0]['id'] ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>"  >cur_status::$curstatus[0]['id']::<?= $curstatus[0]['id'] ?> </li>
    <li class="isset_date_first_report" tabindex="<?= $isset_date_first_report ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isset_date_first_report::$isset_date_first_report::<?= $isset_date_first_report ?> </li>
<?php endif; ?>
    
<?php if (isset($isCurrentPathoIsOwnerThisCase)): ?>
    <li class="isCurrentPathoIsOwnerThisCase" tabindex="<?= $isCurrentPathoIsOwnerThisCase?"1":"0"; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isCurrentPathoIsOwnerThisCase::<?= $isCurrentPathoIsOwnerThisCase?"1":"0"; ?> </li>
    <li class="isCurrentPathoIsSecondOwneThisCaseLastest" tabindex="<?= $isCurrentPathoIsSecondOwneThisCaseLastest?"1":"0"; ?>" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >isCurrentPathoIsSecondOwneThisCaseLastest::<?= $isCurrentPathoIsSecondOwneThisCaseLastest?"1":"0"; ?> </li>
<?php endif; ?>
    
<?php //List of index result ?>
<ul class="uresultinxlist" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex=" <?= $prsu['id'] ?>">uresultinxlist::prsu['id']::<?= $prsu['id'] ?></li>
    <?php endforeach; ?> 
</ul>

<?php //List of time result ?>
<ul class="uresultReleaseSetlist" style="<?= $hidden_data2dom ? "display: none;":"" ?>" >
    <?php foreach ($presultupdates as $prsu): // record uresultid to DOM for update released date when move from 14000 to 20000?>
        <li tabindex="<?= isset($prsu['release_time']) ? 1 : 0 ?>">uresultReleaseSetlist::prsu['release_time']::<?= $prsu['release_time'] ?></li>
    <?php endforeach; ?> 
</ul>

<?php //List of result type ?>
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