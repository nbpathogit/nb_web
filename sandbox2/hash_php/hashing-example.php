<?php

$password = '1';

$hash = password_hash($password, PASSWORD_DEFAULT);

echo $hash;

//$hash = '$2y$10$oB4moe9GzMBDu/ZDJMWryOltOq.rNydANfFFRRn4Ti2Aovx05hiqy';

//var_dump(password_verify($password, $hash));
