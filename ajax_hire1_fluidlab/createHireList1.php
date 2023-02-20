<?php

require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//Auth::requireLogin("../patient_edit.php", $_POST['id']);
//var_dump($_POST);die();

//$specimens = Specimen::getAll($conn, $_POST['specimen_id']);


$hirelist = HireList::getInitObj();

//$hirelist->id = NULL; //          int(11)		
$hirelist->outside_id = (int) $_POST['outside_id'];		
$hirelist->name = $_POST['name'];
$hirelist->cost = $_POST['cost'];
$hirelist->patient_id = (int) $_POST['patient_id'];
$hirelist->patient_number = $_POST['patient_number'];
$hirelist->comment = $_POST['comment'];
$hirelist->accept_time = $_POST['accept_time'];
$hirelist->insert_time = NULL;
$hirelist->finish_time = NULL;

$hirelist->create($conn);

$hirelists = HireList::getAll($conn, (int) $_POST['patient_id']);

echo json_encode($hirelists);


