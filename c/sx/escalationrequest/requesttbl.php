<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    function typeofsched($typid){
        if($typid==1){ $torname = "Escalate My Schedule Adjustment (SA)"; }
        else if($typid==2){ $torname = "Escalate My Overtime (RDOT)"; }
        else { $torname = "Escalate My Schedule Adjustment (RD)"; }
        return $torname;
    }

    $datestrt = date("Y-m-d");
    $datestre = date("Y-m-d H:i:s");
    if(date("d")<=5){ $datestrt = date("Y-m-16", strtotime("-1 Month")); $datestre = date("Y-m-16 00:00:00", strtotime("-1 Month")); }
    else if(date("d")>=1 && date("d")<=20){ $datestrt = date("Y-m-01"); $datestre = date("Y-m-01 00:00:00"); }
    else if(date("d")>=16){ $datestrt = date("Y-m-16"); $datestre = date("Y-m-16 00:00:00"); }

	$rqtschd=$link->query("SELECT * From `gy_schedule_escalate` LEFT JOIN `gy_employee` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_employee`.`gy_emp_code` Where `gy_schedule_escalate`.`gy_req_status`='0' AND `gy_schedule_escalate`.`gy_publish`=0 AND `gy_employee`.`gy_emp_supervisor`=$user_id AND `gy_schedule_escalate`.`gy_sched_day`>='$datestrt' Order By `gy_schedule_escalate`.`gy_req_date` ASC, `gy_schedule_escalate`.`gy_sched_mode` ASC");
    $rqtlogs=$link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_employee` ON `gy_escalate`.`gy_usercode`=`gy_employee`.`gy_emp_code` Where `gy_escalate`.`gy_esc_status`='0' AND `gy_escalate`.`gy_publish`=0 AND `gy_employee`.`gy_emp_supervisor`=$user_id AND `gy_escalate`.`gy_tracker_date`>='$datestre' Order By `gy_esc_date` ASC, `gy_escalate`.`gy_esc_type` ASC");

    $i=0; $schdlst = array(array());
    while($schdrow=$rqtschd->fetch_array()){
    	$schdlst[$i][0] = $schdrow['gy_sched_esc_id'];
    	$schdlst[$i][1] = $schdrow['gy_emp_code'];
	   	$schdlst[$i][2] = $schdrow['gy_req_date'];
    	$schdlst[$i][3] = $schdrow['gy_sched_mode'];
    	$schdlst[$i][4] = $schdrow['gy_sched_day'];
    	$i++;
    }

    $i1=0; $esclst = array(array());
    while($logsrow=$rqtlogs->fetch_array()){
    	$esclst[$i1][0] = $logsrow['gy_esc_id'];
    	$esclst[$i1][1] = $logsrow['gy_esc_type'];
    	$esclst[$i1][2] = $logsrow['gy_usercode'];
    	$esclst[$i1][3] = $logsrow['gy_esc_date'];
    	$esclst[$i1][4] = $logsrow['old_tracker_date'];
    	$i1++;
    }

 $link->close();

for($i2=0;$i2<$i;$i2++){
?>
<tr class="mybg text-nowrap text-center">
    <td scope="row" style="padding:4px;"><?php echo typeofsched($schdlst[$i2][3]); ?></td>
    <td style="padding:4px;"><?php echo get_emp_name($schdlst[$i2][1]); ?></td>
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($schdlst[$i2][4])); ?></td>
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($schdlst[$i2][2])); ?></td>
    <td style="padding:0px;"><button class="btn btn-outline-secondary btn-sm btn-block" onclick="showmrdtls(<?php echo $schdlst[$i2][0]; ?>, 0)"><i class="fa-solid fa-box-open"></i> OPEN </button></td>
</tr>
<?php } for($i3=0;$i3<$i1;$i3++){ ?>
<tr class="mybg text-nowrap text-center">
    <td scope="row" style="padding:4px;"><?php if($esclst[$i3][1]==6){ echo "Escalate My Overtime (OT)"; }else{ echo escalate_type($esclst[$i3][1]); } ?></td>
    <td style="padding:4px;"><?php echo get_emp_name($esclst[$i3][2]); ?></td>
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($esclst[$i3][4])); ?></td>
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($esclst[$i3][3])); ?></td>
    <td style="padding:0px;"><button class="btn btn-outline-secondary btn-sm btn-block" onclick="showmrdtls(<?php echo $esclst[$i3][0]; ?>, 1)"><i class="fa-solid fa-box-open"></i> OPEN </button></td>
</tr>
<?php } ?>