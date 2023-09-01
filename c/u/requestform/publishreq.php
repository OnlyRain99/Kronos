<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

 if($user_type == 1 && $user_dept == 2){
 	$rfid = addslashes($_REQUEST['rfid']);
	$opt = addslashes($_REQUEST['typid']);

 if(date("d")>15){ $fdotco = date("Y-m-01"); }else{ $fdotco = date("Y-m-15", strtotime("-1 month")); }

 if($opt==0 || $opt==1 || $opt==2){
 	$dynsql=$link->query("SELECT * From `gy_schedule_escalate` where `gy_sched_esc_id`=".$rfid." AND `gy_sched_day`>='".$fdotco."' AND `gy_req_status`!=2 AND `gy_publish`=0 ");
	$bif=$dynsql->num_rows;
	if($bif==1){
		$link->query("UPDATE `gy_schedule_escalate` SET `gy_publish`=1 Where `gy_sched_esc_id`='$rfid'");
	}
 }else if($opt==7 || $opt==6 || $opt==5){
 	$dynsql=$link->query("SELECT * From `gy_escalate` where `gy_esc_id`=".$rfid." AND `gy_tracker_date`>='".$fdotco."' AND `gy_esc_status`!=2 AND `gy_publish`=0 ");
	$bif=$dynsql->num_rows;
	if($bif==1){
		$link->query("UPDATE `gy_escalate` SET `gy_publish`=1 Where `gy_esc_id`='$rfid'");
	}
 }

 if($opt=="loa"){

 	$lyvusrsql = $link->query("SELECT `gy_user_id`,`gy_leave_day`,`gy_leave_date_from` FROM `gy_leave` WHERE `gy_leave_id`=$rfid and `gy_publish`=0 LIMIT 1");
    $lyvusrrw=$lyvusrsql->fetch_array();
    if($lyvusrsql->num_rows>0){
    $rytsql=$link->query("SELECT `gy_employee`.`gy_emp_code`AS`empcode`,`gy_employee`.`gy_emp_rate`AS`empryt`,`gy_employee`.`gy_emp_leave_credits`AS`emplc`,`gy_employee`.`gy_emp_id`AS`empid` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_id`=".$lyvusrrw['gy_user_id']." LIMIT 1");
    $lyvusrrow=$rytsql->fetch_array();
    $empid=$lyvusrrow['empid'];
    $loaday=$lyvusrrw['gy_leave_date_from'];
    $leave_days=$lyvusrrw['gy_leave_day'];
    $leave_credits=$lyvusrrow['emplc'];
    if(get_sched_mode($empid, $loaday)==1){
    if($leave_days==1 && $leave_credits==0.5){
        $leave_paid = 1;
        $leave_days = 0.5;
        $credit_update = "`gy_emp_leave_credits`='0'";
    }else if($leave_credits>=$leave_days && ($leave_credits-$leave_days)>=0){
        $leave_paid = 1;
        $credit_update = "`gy_emp_leave_credits`=`gy_emp_leave_credits`-$leave_days";
    }else{
        $leave_paid = 0;
        $credit_update = "`gy_emp_leave_credits`='0'";
    }
    //accrual
    $acrlsql=$link->query("SELECT * FROM `cronjob` WHERE `cronid`=2 AND `status`=1 AND `active_filter`=3");
    $acrlrow=$acrlsql->fetch_array();
    if($acrlsql->num_rows>0){
        $setlcpm = $acrlrow['lastmonth_lc'];
        if(date("Y-m-01")>date("Y-m-01", strtotime($loaday))){
            if(($leave_credits-$leave_days)>=$setlcpm){
                $leave_paid=1;
                $credit_update = "`gy_emp_leave_credits`=`gy_emp_leave_credits` - '$leave_days'";
            }else if($leave_days==1 && ($leave_credits-$setlcpm)>=0.5){
                $leave_days=0.5;
                $leave_paid=1;
                $credit_update = "`gy_emp_leave_credits`=`gy_emp_leave_credits` - '$setlcpm'";
            }else{
                $leave_paid=0;
                $credit_update = "`gy_emp_leave_credits`=`gy_emp_leave_credits`";                
            }
        }
    }
    
      $update_credits=$link->query("UPDATE `gy_employee` SET ".$credit_update." Where `gy_emp_code`='".$lyvusrrow['empcode']."'");
      $lyvstst=$link->query("UPDATE `gy_leave` SET `gy_publish`=1,`gy_emp_rate`='".$lyvusrrow['empryt']."',`gy_leave_day`='$leave_days',`gy_leave_paid`='$leave_paid' Where `gy_publish`=0 AND `gy_leave_id`=$rfid");
 		if($lyvstst && $update_credits){ echo "success"; }else{ echo "error"; }
    }else{ echo "notsched"; }
 } }

 }

$link->close();
?>