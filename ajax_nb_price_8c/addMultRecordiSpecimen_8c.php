<?php   
require '../includes/init.php';
$conn = require '../includes/db.php';
//Auth::requireLogin("../patient_edit.php", $_GET['cur_patient_id']);

$data = $_POST['data'];
//var_dump($data);
//die();

$cur_thai_time = Util::get_curreint_thai_date_time();

$sql =  "".
        "INSERT INTO `service_price_list` ".
        "(`id`, `number`, `speciment_num`, `jobtype`, `specimen`, `hospital_id`, `unit_count`, `price`, `comment`, `create_date`, `add_user_id`, `edit_user_id`, `service_des`) ".
        " VALUES ";

//        " (NULL, '0', '0', '1', 'แแแแ', '0', 'แแแ', '0', 'ราคามาตรฐาน', '2023-03-19 11:57:17.000000', '0', '0'),".
//        " (NULL, '0', '0', '1', 'กกกก', '0', 'แกแก', '0', 'ราคามาตรฐาน', '2023-03-19 11:57:17.000000', '2', '3')";
$hospital_id = 0;
$type_id = 0;
foreach ($data as $key => $d) {
    $e = json_decode($d);
//    var_dump($e);
    $hospital_id = $e->hospital_id;
    $type_id = $e->jobtype;
    $sql = $sql .
            "\n(NULL, '" .
            $e->number . "', '" .
            $e->speciment_num . "', '" .
            $e->jobtype . "', '" .
            addslashes($e->specimen) . "', '" .
            $e->hospital_id . "', '" .
            $e->unit_count . "', '" .
            str_replace(',', '', $e->price) . "', '" .
            addslashes($e->comment) . "', '" .
            $cur_thai_time . "', '" .
            $e->add_user_id . "', '" .
            $e->edit_user_id . "', '".
            $e->service_des . "')";
    if (sizeof($data) == ($key + 1)) {
        //Last record
        $sql = $sql . ";";
    } else {
        //Still there next record
        $sql = $sql . ",";
    }
}
//var_dump($sql);

Util::writeFile('sql.txt', $sql);


ServicePriceList::createBySQL($conn, $sql);

$specimens = ServicePriceList::getSpecimenByHospitalID($conn, $hospital_id , $type_id);

echo json_encode($specimens);