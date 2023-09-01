<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$accntid = addslashes($_REQUEST['accntid']);
$dtfrom = addslashes($_REQUEST['dtfrom']);
$dtto = addslashes($_REQUEST['dtto']);
$lslot = addslashes($_REQUEST['lslot']);
$justfy = addslashes($_REQUEST['justfy']);

$cmpltdate = date("Y-m-d H:i:s", strtotime($dtfrom." "."00:00:00"));
$day7after = strtotime($datenow."+6 days");

$levsql=$link->query("SELECT * From `gy_leave_available` Where `gy_acc_id`='$accntid' AND ((`gy_leave_avail_date`>='$dtfrom' AND `gy_leave_avail_dateto`<='$dtto') OR (`gy_leave_avail_date`<='$dtfrom' AND `gy_leave_avail_dateto`>='$dtto') OR (`gy_leave_avail_date`<='$dtfrom' AND (`gy_leave_avail_date`>='$dtto'AND`gy_leave_avail_dateto`<='$dtto') OR ((`gy_leave_avail_date`>='$dtfrom'AND`gy_leave_avail_dateto`<='$dtfrom')AND`gy_leave_avail_dateto`>='$dtto') ) ) ");
$levcnt=$levsql->num_rows;

if(strtotime($cmpltdate)>=$day7after && strtotime($dtto)>=strtotime($dtfrom) && $lslot>=0 && $levcnt<=0 && $accntid!=""){
    $insertdata=$link->query("INSERT INTO `gy_leave_available`(`gy_leave_avail_date`,`gy_leave_avail_dateto`, `gy_leave_avail_plotted`, `gy_leave_avail_approved`, `gy_user_id`, `gy_leave_avail_justify`, `gy_acc_id`) VALUES('$dtfrom','$dtto','$lslot','0','$user_id','$justfy','$accntid')");
    if($insertdata){ echo "success"; }else{ echo "error"; }
}else{ echo "duplicate"; }

$link->close();
?>