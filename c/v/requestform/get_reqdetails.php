<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$rfid = addslashes($_REQUEST['rfid']);
$opt = addslashes($_REQUEST['typid']);
?>
<div class="table-responsive">
	<table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
		<thead>
			<tr class="bg-secondary text-white" style="padding:4px;" >
				<th>Type</th>
				<th>Name</th>
				<th>Forwarded Date</th>
				<th>Requested Date</th>
				<th>Status</th>
				<th>Approved By</th>
			</tr>
		</thead>
	<tbody>
<?php
if($opt==0 || $opt==1 || $opt==2){
$dynsql=$link->query("SELECT * From `gy_schedule_escalate` where `gy_sched_esc_id`=".$rfid." limit 1");
$sqlrow=$dynsql->fetch_array(); ?>
		<tr>
			<td style="padding:4px;"><?php echo check_esc($sqlrow['gy_sched_mode']); ?></td>
			<td style="padding:4px;"><?php echo $sqlrow['gy_emp_fullname']; ?></td>
			<td style="padding:4px;"><?php echo date("F d, Y", strtotime($sqlrow['gy_req_date'])); ?></td>
			<td style="padding:4px;"><?php echo date("F d, Y", strtotime($sqlrow['gy_sched_day'])); ?></td>
			<td style="padding:4px;"><?php echo check_status($sqlrow['gy_req_status']); ?></td>
			<td style="padding:4px;"><?php echo check_approveby($sqlrow['gy_req_to'], $sqlrow['gy_req_status']); ?></td>
		</tr>
<?php }else if($opt==7 || $opt==6 || $opt==5){
$dynsql=$link->query("SELECT * From `gy_escalate` where `gy_esc_id`=".$rfid." limit 1");
$sqlrow=$dynsql->fetch_array(); ?>
		<tr>
			<td style="padding:4px;"><?php echo check_esc($sqlrow['gy_esc_type']); ?></td>
			<td style="padding:4px;"><?php echo check_trackname($sqlrow['gy_tracker_id']); ?></td>
			<td style="padding:4px;"><?php echo date("F d, Y", strtotime($sqlrow['gy_esc_date'])); ?></td>
			<td style="padding:4px;"><?php echo date("F d, Y", strtotime($sqlrow['gy_tracker_date'])); ?></td>
			<td style="padding:4px;"><?php echo check_status($sqlrow['gy_esc_status']); ?></td>
			<td style="padding:4px;"><?php echo check_approveby($sqlrow['gy_esc_to'], $sqlrow['gy_esc_status']); ?></td>
		</tr>
<?php } ?>
	</tbody>
	</table>
</div>
<?php
$link->close();

function check_esc($escid){
    if($escid==7){ return "Escalate Missed Log (ML)"; }
    else if($escid==2){ return "Escalate Rest Day OT (RDOT)"; }
    else if($escid==8 || $escid==0 || $escid==1){ return "Escalate Schedule Adjustment (SA)"; }
    else if($escid==6){ return "Escalate Overtime (OT)"; }
    else if($escid==5){ return "Escalate Early Out (EO)"; }
}

function check_status($val){
    if($val==0){ return "Pending";}
    else if($val==1){ return "Approved";}
    else if($val==2){ return "Rejected";}
    else{ return ""; }
}

function check_trackname($trackid){
    include '../../../config/conn.php';
    $trksql=$link->query("SELECT `gy_emp_fullname` From `gy_tracker` where `gy_tracker_id`=".$trackid." limit 1");
    $trkrow=$trksql->fetch_array();
    $name = $trkrow['gy_emp_fullname'];
    $link->close();
    return $name;
}

function check_approveby($usrid, $status){
    if($status>0){
        return getuserfullname($usrid);
    }else{ return ""; }
}
?>