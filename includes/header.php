<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>NB Phatho</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="NB Phatho" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <!-- <link href="img/favicon.ico" rel="icon"> -->

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- from old herder -->
    <link rel="stylesheet" href="css/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="css/flowchart.css">

</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- <div class="container-fluid position-relative bg-white d-flex p-0"> -->
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">กำลังโหลด</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-2 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="home.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa-solid fa-microscope"></i> NB Phatho</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <i class="fa-solid fa-hospital fa-lg"></i>
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?= $_SESSION['username'] ?></h6>
                        <span><?= isset($_SESSION['usergroup']->ugroup) ? $_SESSION['usergroup']->ugroup : "" ?></span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <div class="nav-item dropdown">
                        <a href="patient.php" id="patient_main" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-clone"></i>ข้อมูลผู้รักษา</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a id="patient" class="dropdown-item" href="patient.php">ข้อมูลผู้รักษา</a>
                            <a id="patient_add" class="dropdown-item" href="patient_add.php">เพิ่มข้อมูลผู้รักษา</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="user.php" id="user_main" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-user-doctor"></i>ผู้ใช้งาน</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a id="user" class="dropdown-item" href="user.php">ผู้ใช้งานระบบ</a>
                            <a id="user_add" class="dropdown-item" href="user_add.php">เพิ่มผู้ใช้งานระบบ</a>
                            <a id="user_edit" class="dropdown-item" href="user_edit.php">แก้ไขผู้ใช้งานระบบ</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="specimen.php" id="specimen_main" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-disease"></i>สิ่งส่งตรวจ</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a id="specimen" href="specimen.php" class="dropdown-item">ข้อมูลสิ่งส่งตรวจ</a>
                            <a id="specimen_add" href="specimen_add.php" class="dropdown-item">เพิ่มสิ่งส่งตรวจ</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="hospital.php" id="hospital_main" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-hospital-user"></i>โรงพยาบาล</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a class="dropdown-item" href="hospital.php">ข้อมูลโรงพยาบาล</a>
                            <a class="dropdown-item" href="hospital_add.php">เพิ่มโรงพยาบาล</a>
                        </div>
                    </div>
                    <a href="print.php" class="nav-item nav-link"><i class="fa-solid fa-print"></i>พิมพ์ข้อมูล</a>
                    <a href="billing.php" class="nav-item nav-link"><i class="fa-solid fa-file-invoice"></i>Billing</a>
                    <a href="log.php" class="nav-item nav-link"><i class="fa-solid fa-bars-staggered"></i>ข้อมูลการใช้งานระบบ</a>
                    <div class="nav-item dropdown">
                        <a href="about.php" id="about_main" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>เกี่ยวกับ</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a class="dropdown-item" id="about" href="about.php">เกียวกับเรา</a>
                            <a class="dropdown-item" id="webfeature" href="webfeature.php">เกียวกับเว็บแอฟฟลิเคชั่น</a>
                            <a class="dropdown-item" id="" href="#">ดาวน์โหลดเอกสาร</a>
                            <a class="dropdown-item" id="stuff" href="stuff.php">บุคลากร</a>
                            <a class="dropdown-item" id="service" href="service.php">บริการของเรา</a>
                        </div>
                    </div>

                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-3 py-2 mb-2">
                <a href="home.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0">NB</h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <!-- <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form> -->
                <div class="navbar-nav align-items-center ms-auto">
                    <!-- <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div> -->
                    <!-- <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div> -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-hospital fa-lg"></i>
                            <span class="d-none d-lg-inline-flex"><?= $_SESSION['username'] ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <!-- <a href="#" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a> -->
                            <a href="logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->
            <div class="container p-3">