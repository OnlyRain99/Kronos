<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$id = addslashes($_REQUEST['id']);
$date = addslashes($_REQUEST['date']);
$login = addslashes($_REQUEST['login']);
$logout = addslashes($_REQUEST['logout']);
$bin = addslashes($_REQUEST['bin']);
$bout = addslashes($_REQUEST['bout']);
$acc = $_REQUEST['acc'];

$trali = date("Y-m-d H:i:s", strtotime($date." ".$login));
$tralo = date("Y-m-d H:i:s", strtotime($date." ".$logout));

if(strtotime($trali)>strtotime($tralo)){ $tralo = date("Y-m-d H:i:s", strtotime($tralo.' +1 day')); }
if($bin!="" && $bout!=""){
    $trabo = date("Y-m-d H:i:s", strtotime($bout));
    $trabin = date("Y-m-d H:i:s", strtotime($bin));
    if(strtotime($trali)>strtotime($trabo)){ $trabo = date("Y-m-d H:i:s", strtotime($trabo.' +1 day')); }
    if(strtotime($trabo)>strtotime($trabin)){ $trabin = date("Y-m-d H:i:s", strtotime($trabin.' +1 day')); }
    if(strtotime($trabin)>strtotime($tralo)){ $tralo = date("Y-m-d H:i:s", strtotime($tralo.' +1 day')); }
}else{
    $trabo="";
    $trabin="";
}

$empsql=$link->query("SELECT `gy_emp_email`,`gy_emp_fullname`,`gy_emp_account`,`gy_assignedloc` FROM `gy_employee` Where `gy_emp_code`='$id' AND `gy_acc_id`='$acc' LIMIT 1");
$count=$empsql->num_rows;
if($count>0 && $acc==20){
$emprow=$empsql->fetch_array();
$empemail = $emprow['gy_emp_email'];
$empfullname = $emprow['gy_emp_fullname'];
$accnm = $emprow['gy_emp_account'];
$history = "Uploaded by ".$user_info." at ".date("H:m:i m/d/Y")." <br>";
$remarks = "DTR Upload";
$asgnloc = $emprow['gy_assignedloc'];
$breakhours = floatval(get_breakhours($trabo, $trabin));
$wkhrs=floatval(getwh($trali, $tralo));

    $trackercode = latest_code("gy_tracker", "gy_tracker_code", "10001");
    $insertdata=$link->query("INSERT INTO `gy_tracker`(`gy_tracker_code`, `gy_tracker_date`, `gy_emp_code`, `gy_emp_email`, `gy_emp_fullname`, `gy_emp_account`, `gy_tracker_login`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_logout`, `gy_tracker_status`, `gy_tracker_om`,`gy_tracker_history`,`gy_tracker_remarks`,`gy_tracker_loc`,`gy_tracker_bh`,`gy_tracker_wh`)Values('$trackercode','$trali','$id','$empemail','$empfullname','$accnm','$trali','$trabo','$trabin','$tralo',1,$user_id,'$history','$remarks',$asgnloc,$breakhours,$wkhrs)");
    if($insertdata){ echo "1"; }else{ echo "2"; }
}else{ echo "3"; }
$link->close();
?>