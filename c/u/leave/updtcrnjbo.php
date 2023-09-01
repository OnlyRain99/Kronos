<?php
    include '../../../config/conn.php';
	include '../../../config/function.php';
    include '../session.php';
    date_default_timezone_set('Asia/Taipei');
if($user_type == 1 && $user_dept == 2){
$func = addslashes($_REQUEST['func']);
if($func==0){
$cnval = addslashes($_REQUEST['cnval']);
$lyvcrd = addslashes($_REQUEST['lyvcrd']);
if($lyvcrd!="" && $lyvcrd>=0){
    $actflt = addslashes($_REQUEST['actflt']);
    $fltid = addslashes($_REQUEST['fltid']);
    $prop=$link->query("UPDATE `cronjob` SET `cronval`='$cnval',`leave_credits`='$lyvcrd',`active_filter`='$actflt',`filter_id`='$fltid',`crondate`='".date("Y-m-d H:i:s")."' Where `cronid`=2");
    if($prop){echo"success";}else{echo"error";}
}else{ echo "invalidcredit"; }
}else if($func==1){
$fnc = addslashes($_REQUEST['fnc']);
    if($fnc==0||$fnc==1){
        $prop=$link->query("UPDATE `cronjob` SET `status`='$fnc',`crondate`='".date("Y-m-d H:i:s")."' Where `cronid`=2");
        if($prop){ if($fnc==1){echo"run";}else{echo"stop";} }else{ echo "error"; }
    }else{echo"waschange";}
}
}
$link->close();
?>