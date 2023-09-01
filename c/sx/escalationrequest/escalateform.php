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

$torid = addslashes($_REQUEST['stid']);
$mode = addslashes($_REQUEST['sctm']);
$i=0;
if($mode==0){
	$schdsql=$link->query("SELECT * From `gy_schedule_escalate` LEFT JOIN `gy_employee` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_employee`.`gy_emp_code` Where `gy_employee`.`gy_emp_supervisor`=$user_id AND `gy_schedule_escalate`.`gy_sched_esc_id`=$torid LIMIT 1");

    while($scdrow=$schdsql->fetch_array()){ $i++;
        $torname = typeofsched($scdrow['gy_sched_mode']);
        if($scdrow['gy_req_status']==0){ $reqstatus="Pending"; }
        else if($scdrow['gy_req_status']==1){ $reqstatus="Approved"; }
        else if($scdrow['gy_req_status']==2){ $reqstatus="Rejected"; }
        $oldlogin = $scdrow['old_sched_login'];
        $newlogin = $scdrow['gy_sched_login'];
        $mname = $scdrow['gy_emp_fullname'];
        $oldlogout = $scdrow['old_sched_logout'];
        $newlogout = $scdrow['gy_sched_logout'];
        $reqdate = date("F d, Y", strtotime($scdrow['gy_sched_day']));
        $flddate = date("F d, Y", strtotime($scdrow['gy_req_date']));
        $reqreason = $scdrow['gy_req_reason'];
        $dnyreason = $scdrow['gy_req_deny'];
        $thefile = $scdrow['gy_req_photodir'];
    }
}else if($mode==1){
    $logsql=$link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_employee` ON `gy_escalate`.`gy_usercode`=`gy_employee`.`gy_emp_code` Where `gy_employee`.`gy_emp_supervisor`=$user_id AND `gy_escalate`.`gy_esc_id`=$torid");

    while($scdrow=$logsql->fetch_array()){ $i++;
        if($scdrow['gy_esc_type']==6){ $torname = "Escalate My Overtime (OT)"; }
        else{ $torname = escalate_type($scdrow['gy_esc_type']); }
        if($scdrow['gy_esc_status']==0){ $reqstatus="Pending"; }
        else if($scdrow['gy_esc_status']==1){ $reqstatus="Approved"; }
        else if($scdrow['gy_esc_status']==2){ $reqstatus="Rejected"; }
        $oldlogin = $scdrow['old_tracker_login'];
        $newlogin = $scdrow['gy_tracker_login'];
        $mname = $scdrow['gy_emp_fullname'];
        $oldlogout = $scdrow['old_tracker_logout'];
        $newlogout = $scdrow['gy_tracker_logout'];
        $oldbrkout = $scdrow['old_tracker_breakout'];
        $newbrkout = $scdrow['gy_tracker_breakout'];
        $oldbrkin = $scdrow['old_tracker_breakin'];
        $newbrkin = $scdrow['gy_tracker_breakin'];
        $reqdate = date("F d, Y", strtotime($scdrow['old_tracker_date']));
        $flddate = date("F d, Y", strtotime($scdrow['gy_esc_date']));
        $reqreason = $scdrow['gy_esc_reason'];
        $dnyreason = $scdrow['gy_esc_deny'];
        $thefile = $scdrow['gy_esc_photodir'];
    }
}

 $link->close();
 if($i>0){
?>
<div class="table-responsive">
    <table class="table table-hover text-center text-nowrap" style="font-family: 'Calibri'; font-size: 16px;">
        <thead>
            <tr> 
                <th class="bg-dark text-white" colspan="2"><?php echo $reqstatus; ?> Escalation Details</th>
                <th > </th>
                <th class="bg-dark text-white">Time Logs</th>
                <th class="bg-dark text-white">Old</th>
                <th class="bg-dark text-white"><i class='fas fa-chevron-circle-right faa-wrench faa-slow animated'></i></th>
                <th class="bg-dark text-white">New</th>
            </tr>
        </thead>
        <tbody>
            <tr> 
                <th class="bg-secondary text-white">Request Type</th>
                <td><?php echo $torname; ?></td>
                <td > </td>
                <th class="bg-secondary text-white">Login</th>
                <td title="<?php echo simpdate($oldlogin); ?>"><?php echo chktime($oldlogin); ?></td>
                <td><i class='fas fa-angle-double-right faa-horizontal animated'></i></td>
                <td title="<?php echo simpdate($newlogin); ?>"><?php echo chktime($newlogin); ?></td>
            </tr>
            <tr> 
                <th class="bg-secondary text-white">Manager's Name</th>
                <td><?php echo $mname; ?></td>
                <td > </td>
                <?php if($mode==0){ ?>
                <th class="bg-secondary text-white">Logout</th>
                <td title="<?php echo simpdate($oldlogout); ?>"><?php echo chktime($oldlogout); ?></td>
                <td><i class='fas fa-angle-double-right faa-horizontal faa-reverse faa-fast animated'></i></td>
                <td title="<?php echo simpdate($newlogout); ?>"><?php echo chktime($newlogout); ?></td>
                <?php }else if($mode==1){ ?>
                <th class="bg-secondary text-white">Break-Out</th>
                <td title="<?php echo simpdate($oldbrkout); ?>"><?php echo chktime($oldbrkout); ?></td>
                <td><i class='fas fa-angle-double-right faa-passing faa-fast animated'></i></td>
                <td title="<?php echo simpdate($newbrkout); ?>"><?php echo chktime($newbrkout); ?></td>
                <?php } ?>
            </tr>
            <tr>
                <th class="bg-secondary text-white">Date Requested</th>
                <td><?php echo $reqdate; ?></td>
                <?php if($mode==1){ ?>
                <td > </td>
                <th class="bg-secondary text-white">Break-In</th>
                <td title="<?php echo simpdate($oldbrkin); ?>"><?php echo chktime($oldbrkin); ?></td>
                <td><i class='fas fa-angle-double-right faa-passing faa-slow animated'></i></td>
                <td title="<?php echo simpdate($newbrkin); ?>"><?php echo chktime($newbrkin); ?></td>
                <?php } ?>
            </tr>
            <tr>
                <th class="bg-secondary text-white">Date Filed</th>
                <td><?php echo $flddate; ?></td>
                <?php if($mode==1){ ?>
                <td > </td>
                <th class="bg-secondary text-white">Logout</th>
                <td title="<?php echo simpdate($oldlogout); ?>"><?php echo chktime($oldlogout); ?></td>
                <td><i class='fas fa-angle-double-right faa-horizontal faa-reverse faa-fast animated'></i></td>
                <td title="<?php echo simpdate($newlogout); ?>"><?php echo chktime($newlogout); ?></td>
                <?php } ?>
            </tr>
            <tr></tr>
            <tr>
                <th colspan="2" class="bg-dark text-white">Reason of Escalation</th>
                <?php if($dnyreason!=""){?>
                <td > </td>
                <th colspan="4" class="bg-dark text-white">Reason for Rejection</th>
                <?php } ?>
            </tr>
            <tr>
                <td colspan="2" class="text-left text-wrap"><?php echo $reqreason; ?></td>
                <?php if($dnyreason!=""){?>
                <td > </td>
                <td colspan="4" class="text-left text-wrap"><?php echo $dnyreason; ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td colspan="7"><iframe src="../../kronos_file_store/<?php echo $thefile; ?>" width="100%" height="500px"></iframe></td>
            </tr>
        </tbody>
    </table>
</div>
 <?php }else{ echo "Request not found or has been cancelled by the employee."; } ?>