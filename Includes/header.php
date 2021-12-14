<!DOCTYPE html>

<html  lang="th">

<head>
    <title>NB Phatho</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">

    <!--        <style>
            textarea {
                max-width: 100%;
            }
        </style>-->

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">

            <header class="d-flex flex-wrap align-items-center justify-content-md-between py-3 mb-4 border-bottom">

                <a class="navbar-brand" href="/"><img src="image/logo.png" alt="NB Phatho logo" width="150"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="main_nav">

                    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                        <li class="nav-item"><a class="nav-link px-2 link-dark" href="/">หน้าหลัก</a></li>

                        <li class="nav-item dropdown">
                            <a id="nav-menu" class="nav-link dropdown-toggle dropdown-toggle-split link-dark" href="user.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">ข้อมูลผู้ใช้ระบบ</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="user_add.php">เพิ่มผู้ใช้งานระบบ</a></li>
                                <li><a class="dropdown-item" href="user_edit.php">แก้ไขผู้ใช้งานระบบ</a></li>
                            </ul>
                        </li>


                        <li class="nav-item dropdown">
                            <a id="nav-menu" class="nav-link dropdown-toggle dropdown-toggle-split link-dark" href="hospital.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">ข้อมูลโรงพยาบาล</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="hospital_add.php">เพิ่มโรงพยาบาล</a></li>
                            </ul>
                        </li>


                        <li class="nav-item dropdown">
                            <a id="nav-menu" class="nav-link dropdown-toggle dropdown-toggle-split link-dark" href="patient.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">ข้อมูลผู้รักษาและผลการรักษา</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="patient_add.php">เพิ่มข้อมูลผู้รักษา</a></li>
                                <li><a class="dropdown-item" href="patient_edit_profile.php">แก้ไขข้อมูลผู้รักษา</a></li>
                                <li><a class="dropdown-item" href="patient_edit_result.php">แก้ไขผลการรักษา</a></li>
                            </ul>
                        </li>


                        <li class="nav-item"><a class="nav-link px-2 link-dark" href="">พิมพ์ข้อมูล</a></li>
                        <li class="nav-item"><a class="nav-link px-2 link-dark" href="">Billing</a></li>
                        <li class="nav-item"><a class="nav-link px-2 link-dark" href="">ข้อมูลการใช้งานระบบ</a></li>
                        <li class="nav-item"><a class="nav-link px-2 link-dark" href="logout.php">ออกระบบ</a></li>
                        <li class="nav-item"><a class="nav-link px-2 link-dark" href="login.php">ล็อกอิน</a></li>
                    </ul>

                </div>
            </header>
        </div>
    </nav>

    <div class="container">
        <main>