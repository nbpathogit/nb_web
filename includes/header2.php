<!DOCTYPE html>

<html lang="th">

<head>
    <title>NB Phatho</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="/css/flowchart.css">

    <style>
        /* textarea {
                max-width: 100%;
            } */

        /* div.transbox {
            margin: 20px;
            background-color: #ffffff;
            opacity: 0.8;
        } */

        .features-icons {
            padding-top: 5rem;
            padding-bottom: 2rem;
        }
    </style>


</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/"><img src="image/logo.png" alt="NB Phatho logo" width="150"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="main_nav">

                <ul class="nav col-12 col-md-auto mb-md-0 ms-auto">
                    <!--<li class="nav-item"><a class="nav-link px-2 link-dark" href="/">หน้าหลัก</a></li>-->

                    <?php if (Auth::isLoggedIn()) : ?>


                        <li class="nav-item dropdown">
                            <a id="nav-menu" class="nav-link dropdown-toggle dropdown-toggle-split link-dark" href="patient.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">ข้อมูลผู้รักษาและผลการรักษา</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="patient_add.php">เพิ่มข้อมูลผู้รักษา</a></li>
                                <!--<li><a class="dropdown-item" href="patient_edit_profile.php">แก้ไขข้อมูลผู้รักษา</a></li>-->
                                <!--<li><a class="dropdown-item" href="patient_edit_result.php">แก้ไขผลการรักษา</a></li>-->
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a id="nav-menu" class="nav-link dropdown-toggle dropdown-toggle-split link-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">จัดการข้อมูลด้านต่างๆ</a>
                            <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">ผู้ใช้งาน</h6></li>
                                <li><a class="dropdown-item" href="user.php">ผู้ใช้งานระบบ</a></li>
                                <li><a class="dropdown-item" href="user_add.php">เพิ่มผู้ใช้งานระบบ</a></li>
                                <li><a class="dropdown-item" href="user_edit.php">แก้ไขผู้ใช้งานระบบ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">สิ่งส่งตรวจ</h6></li>
                                <li><a class="dropdown-item" href="specimen.php">ข้อมูลสิ่งส่งตรวจ</a></li>
                                <li><a class="dropdown-item" href="specimen_add.php">เพิ่มสิ่งส่งตรวจ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">โรงพยาบาล</h6></li>
                                <li><a class="dropdown-item" href="hospital.php">ข้อมูลโรงพยาบาล</a></li>
                                <li><a class="dropdown-item" href="hospital_add.php">เพิ่มโรงพยาบาล</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">อื่นๆ</h6></li>
                                <li><a class="dropdown-item" href="hospital_add.php">พิมพ์ข้อมูล</a></li>
                                <li><a class="dropdown-item" href="hospital_add.php">Billing</a></li>
                                <li><a class="dropdown-item" href="hospital_add.php">ข้อมูลการใช้งานระบบ</a></li>
                            </ul>
                        </li>

                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <a id="nav-menu" class="nav-link dropdown-toggle dropdown-toggle-split link-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">เกี่ยวกับ</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="about.php">เกียวกับเรา</a></li>
                            <li><a class="dropdown-item" href="webfeature.php">เกียวกับเว็บแอฟฟลิเคชั่น</a></li>
                            <li><a class="dropdown-item" href="#">ดาวน์โหลดเอกสาร</a></li>
                            <li><a class="dropdown-item" href="stuff.php">บุคลากร</a></li>
                            <li><a class="dropdown-item" href="service.php">บริการของเรา</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav col-12 col-md-auto  mb-md-0">
                    <?php if (Auth::isLoggedIn()) : ?>
                        <li class="nav-item dropdown">
                            <a id="nav-menu" class="btn btn-outline-secondary nav-link dropdown-toggle dropdown-toggle-split link-dark" href="patient.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">สวัสดี <b><?= Auth::getUser() ?></b></a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="logout.php">ออกระบบ</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li class="nav-item"><a class="btn btn-outline-primary nav-link px-2 link-dark" href="login.php">ล็อกอิน</a></li>
                    <?php endif; ?>
                </ul>
            </div>

    </nav>


    <div class="container">

        <!--loading status-->
        <!--<div class="d-flex justify-content-center" id="lodingstatus" style="display:block;">-->
            <!--<div class="spinner-border" role="status" id="lodingstatus" style="display:block;">-->
            <!--</div>-->
        <!--</div>-->

        <!--Main section. show on loading finished.-->
        <!--<main id="mainpage" style="display:none;">-->
        <main id="mainpage" style="display:show;">