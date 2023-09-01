<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$rfid = addslashes($_REQUEST['rfid']);
$opt = addslashes($_REQUEST['opt']);

if($opt==7 || $opt==6 || $opt==5){
$dynsql=$link->query("SELECT * From `gy_escalate` where `gy_esc_id`=".$rfid." limit 1");
$sqlrow=$dynsql->fetch_array();
?>
<div class="table-responsive">
	<table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
		<thead>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Request Type</th>
				<th colspan="3"><?php echo check_esc($sqlrow['gy_esc_type']); ?></th>
			</tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Requested For</th>
				<th colspan="3"><?php echo check_escname($sqlrow['gy_usercode']); ?></th>
			</tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Direct Supervisor</th>
				<th colspan="3"><?php echo get_supervisor_name($sqlrow['gy_sup']); ?></th>
			</tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Request Reason</th>
				<th colspan="3"><?php echo $sqlrow['gy_esc_reason']; ?></th>
			</tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Requested Date</th>
				<th colspan="3"><?php echo Date("F d, Y", strtotime($sqlrow['gy_tracker_date'])); ?></th>
			</tr>
			<tr><th colspan="4"></th></tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Time Logs</th>
				<th class="bg-secondary text-white">Old</th>
				<th class="bg-secondary text-white"><i class='fas fa-chevron-circle-right faa-wrench faa-slow animated'></i></th>
				<th class="bg-secondary text-white">New</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th class="bg-secondary text-white">Login</th>
				<td title="<?php echo simpdate($sqlrow['old_tracker_login']); ?>"><?php echo chktime($sqlrow['old_tracker_login']); ?></td>
				<td><i class='fas fa-angle-double-right faa-horizontal animated'></i></td>
				<td title="<?php echo simpdate($sqlrow['gy_tracker_login']); ?>"><?php echo chktime($sqlrow['gy_tracker_login']); ?></td>
			</tr>
			<tr>
				<th class="bg-secondary text-white">Break-Out</th>
				<td title="<?php echo simpdate($sqlrow['old_tracker_breakout']); ?>"><?php echo chktime($sqlrow['old_tracker_breakout']); ?></td>
				<td><i class='fas fa-angle-double-right faa-passing faa-fast animated'></i></td>
				<td title="<?php echo simpdate($sqlrow['gy_tracker_breakout']); ?>"><?php echo chktime($sqlrow['gy_tracker_breakout']); ?></td>
			</tr>
			<tr>
				<th class="bg-secondary text-white">Break-In</th>
				<td title="<?php echo simpdate($sqlrow['old_tracker_breakin']); ?>"><?php echo chktime($sqlrow['old_tracker_breakin']); ?></td>
				<td><i class='fas fa-angle-double-right faa-passing faa-slow animated'></i></td>
				<td title="<?php echo simpdate($sqlrow['gy_tracker_breakin']); ?>"><?php echo chktime($sqlrow['gy_tracker_breakin']); ?></td>
			</tr>
			<tr>
				<th class="bg-secondary text-white">Logout</th>
				<td title="<?php echo simpdate($sqlrow['old_tracker_logout']); ?>"><?php echo chktime($sqlrow['old_tracker_logout']); ?></td>
				<td><i class='fas fa-angle-double-right faa-horizontal faa-reverse faa-fast animated'></i></td>
				<td title="<?php echo simpdate($sqlrow['gy_tracker_logout']); ?>"><?php echo chktime($sqlrow['gy_tracker_logout']); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<?php
}else if($opt==0 || $opt==1 || $opt==2){
$dynsql=$link->query("SELECT * from `gy_schedule_escalate` where `gy_sched_esc_id`=".$rfid." limit 1");
$sqlrow=$dynsql->fetch_array();
?>
<div class="table-responsive">
	<table class="table table table-bordered text-center text-nowrap" style="font-family: 'Calibri'; font-size: 16px;">
		<thead>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Request Type</th>
				<th colspan="3"><?php echo check_esc($sqlrow['gy_sched_mode']); ?></th>
			</tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Requested For</th>
				<th colspan="3"><?php echo $sqlrow['gy_emp_fullname']; ?></th>
			</tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Direct Supervisor</th>
				<th colspan="3"><?php echo get_supervisor_name($sqlrow['gy_sup']); ?></th>
			</tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Request Reason</th>
				<th colspan="3"><?php echo $sqlrow['gy_req_reason']; ?></th>
			</tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Requested Date</th>
				<th colspan="3"><?php echo Date("F d, Y", strtotime($sqlrow['gy_sched_day'])); ?></th>
			</tr>
			<tr><th colspan="4"></th></tr>
			<tr style="padding:4px;" >
				<th class="bg-secondary text-white">Time Logs</th>
				<th class="bg-secondary text-white">Old</th>
				<th class="bg-secondary text-white"><i class='fas fa-chevron-circle-right faa-wrench faa-slow animated'></i></th>
				<th class="bg-secondary text-white">New</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th class="bg-secondary text-white">Login</th>
				<td title="<?php echo simpdate($sqlrow['old_sched_login']); ?>"><?php echo chktime($sqlrow['old_sched_login']); ?></td>
				<td><i class='fas fa-angle-double-right faa-horizontal animated'></i></td>
				<td title="<?php echo simpdate($sqlrow['gy_sched_login']); ?>"><?php echo chktime($sqlrow['gy_sched_login']); ?></td>
			</tr>
			<tr>
				<th class="bg-secondary text-white">Break-Out</th>
				<td title="<?php echo simpdate($sqlrow['old_sched_breakout']); ?>"><?php echo chktime($sqlrow['old_sched_breakout']); ?></td>
				<td><i class='fas fa-angle-double-right faa-passing faa-fast animated'></i></td>
				<td title="<?php echo simpdate($sqlrow['gy_sched_breakout']); ?>"><?php echo chktime($sqlrow['gy_sched_breakout']); ?></td>
			</tr>
			<tr>
				<th class="bg-secondary text-white">Break-In</th>
				<td title="<?php echo simpdate($sqlrow['old_sched_breakin']); ?>"><?php echo chktime($sqlrow['old_sched_breakin']); ?></td>
				<td><i class='fas fa-angle-double-right faa-passing faa-slow animated'></i></td>
				<td title="<?php echo simpdate($sqlrow['gy_sched_breakin']); ?>"><?php echo chktime($sqlrow['gy_sched_breakin']); ?></td>
			</tr>
			<tr>
				<th class="bg-secondary text-white">Logout</th>
				<td title="<?php echo simpdate($sqlrow['old_sched_logout']); ?>"><?php echo chktime($sqlrow['old_sched_logout']); ?></td>
				<td><i class='fas fa-angle-double-right faa-horizontal faa-reverse faa-fast animated'></i></td>
				<td title="<?php echo simpdate($sqlrow['gy_sched_logout']); ?>"><?php echo chktime($sqlrow['gy_sched_logout']); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<?php
}

$link->close();

function check_esc($escid){
    if($escid==7){ return "Escalate Missed Log (ML)"; }
    else if($escid==2){ return "Escalate Rest Day OT (RDOT)"; }
    else if($escid==8 || $escid==0 || $escid==1){ return "Escalate Schedule Adjustment (SA)"; }
    else if($escid==6){ return "Escalate Overtime (OT)"; }
    else if($escid==5){ return "Escalate Early Out (EO)"; }
}

function check_escname($usercode){
    include '../../../config/conn.php';
    $trksql=$link->query("SELECT `gy_full_name` From `gy_user` where `gy_user_code`='".$usercode."' ");
    $trkrow=$trksql->fetch_array();
    $name = $trkrow['gy_full_name'];
    $link->close();
    return $name;
}

?>