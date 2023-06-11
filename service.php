<?php

require 'includes/init.php';
?>
<?php require 'user_auth.php'; ?>
<?php require 'includes/header.php'; ?>
<h3>บริการของเรา</h3>
<ul>
    <li>การตรวจชิ้นเนื้อศัลย์พยาธิ</li>
    <ul>
        <li>การตรวจชิ้นเนื้อ (Surgical pathology)</li>
        <li>การตรวจชันสูตรพิเศษ Histochemistry และ Immunohistochemistry </li>
        <li>การตรวจด้วยเทคนิคพิเศษอื่น ๆ ที่ไม่ได้ดำเนินการภายในคลินิกเวชกรรมเฉพาะทางนี้ ได้แก่ PCR, Cytogenetic, Liquid based pap smear และ HPV DNA testing เป็นต้น จะถูกดำเนินการส่งตรวจต่อในสถาบันที่รับตรวจ</li>
    </ul>

    <li>การตรวจทางเซลล์วิทยา</li>
    <ul>
        <li>การตรวจเซลล์วิทยาระบบสืบพันธุ์สตรี (Gynecologic cytology) รวมทั้งการรับปรึกษา pap smear ในรายที่ผลการตรวจคัดกรองผิดปกติ</li>
        <li>การตรวจเซลล์วิทยาระบบอื่น (Non-gynecologic cytology)</li>
    </ul>
    <li>การตรวจเซลล์วิทยาน้ำคัดหลั่ง (Fluid cytology)</li>
</ul>
<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
//set active tab
    $( "#about_main" ).addClass( "active" );
    $( "#service" ).addClass( "active" );
</script>