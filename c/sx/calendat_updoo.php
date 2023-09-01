<?php
    include '../../config/conn.php';

$name = addslashes($_REQUEST['name']);
$type = addslashes($_REQUEST['type']);
$dateoo = addslashes($_REQUEST['dateoo']);
$eloc = addslashes($_REQUEST['eloc']);
if($name!=""&&$type!=""&&$dateoo!=""){
    $link->query("INSERT INTO `gy_holiday_calendar`(`gy_hol_title`,`gy_hol_type_id`,`gy_hol_date`,`gy_a_year`,`gy_hol_loc`)Values('$name','$type','$dateoo',1,'$eloc')");
}
    $link->close();
?>