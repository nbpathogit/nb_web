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