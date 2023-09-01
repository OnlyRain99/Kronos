<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$trkid = addslashes($_REQUEST['trkid']);
$cdate = date("Y-m-d", strtotime(addslashes($_REQUEST['cdate'])));

$esqsql=$link->query("SELECT `gy_esc_photodir` From `gy_escalate` Where `gy_tracker_id`=$trkid AND `gy_esc_status`=0");
if(mysqli_num_rows($esqsql)>0){
    $row1=$esqsql->fetch_array();
    $filename=$row1['gy_esc_photodir'];
    $dltqry=$link->query("DELETE FROM `gy_escalate` Where `gy_tracker_id`=$trkid AND `gy_esc_status`=0");
    if($dltqry){
        $history = "<br> Escalation cancelled at ".$datenow."<br>";
		$updsql=$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$trkid'");
        unlink('../../../kronos_file_store/'.$filename);
        if($updsql){echo"cancelgood";}else{ echo"cancelerror"; }
    }else{ echo"cancelerror"; }
}else{
	$trkcdsql=$link->query("SELECT `gy_tracker_code` From `gy_tracker` Where `gy_tracker_id`='$trkid'");
	if(mysqli_num_rows($trkcdsql)>0){
	$row1=$trkcdsql->fetch_array();
    $trkcdrow=$row1['gy_tracker_code'];

    $dytsql=$link->query("SELECT `gy_req_photodir`,`gy_sched_esc_id` From `gy_schedule_escalate` Where `gy_sched_esc_code`=$trkcdrow AND `gy_sched_day`='$cdate' AND `gy_req_status`=0");
    if(mysqli_num_rows($dytsql)>0){
        $row2=$dytsql->fetch_array();
        $escid=$row2['gy_sched_esc_id'];
        $filename=$row2['gy_req_photodir'];
        $dltqry=$link->query("DELETE FROM `gy_schedule_escalate` Where `gy_sched_esc_id`=$escid");
        if($dltqry){
            $history = "<br> Escalation cancelled at ".$datenow."<br>";
            $updsql=$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$trkid'");
            unlink('../../../kronos_file_store/'.$filename);
            if($updsql){echo"cancelgood";}else{ echo"cancelerror"; }
        }else{ echo"cancelerror"; }
    }
	}
}

$link->close();
?>