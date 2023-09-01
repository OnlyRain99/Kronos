<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$lid = addslashes($_REQUEST['lid']);
$date = addslashes($_REQUEST['date']);
$deletedata=$link->query("DELETE FROM `gy_leave_available` Where `gy_leave_avail_id`='$lid' AND `gy_leave_avail_approved`=0");
if($deletedata){ echo $date; }
$link->close();
?>