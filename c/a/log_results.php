<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
?>

<?php  
	$logs=$link->query("SELECT * From `gy_logs` Order By `gy_log_date` DESC");
	$lgscntr = 0;
    while ($logrow=$logs->fetch_array()) {
	if($lgscntr <= 300){
        if ($logrow['gy_log_status'] == "Login") {
            $logstatus = "green";
        }else if ($logrow['gy_log_status'] == "Logout") {
            $logstatus = "red";
        }else if ($logrow['gy_log_status'] == "Break-Out") {
            $logstatus = "#343a40";
        }else{
            $logstatus = "#343a40";
        }

		//? format date == format date
        if (date("Y-m-d", strtotime($logrow['gy_log_date'])) == $onlydate) {
        	$mydatelog = date("g:i A", strtotime($logrow['gy_log_date']));
        }else{
        	$mydatelog = date("M d g:i A", strtotime($logrow['gy_log_date']));
        }
?>

<tr class="mybg">
    <td style="padding: 5px; color: <?php echo $logstatus; ?>;"><i class="fa fa-circle"></i> <?php echo $logrow['gy_log_status']; ?> - <?= $mydatelog; ?></td>
    <td style="padding: 5px; color: <?php echo $logstatus; ?>;"><?php echo $logrow['gy_log_fullname']." (".$logrow['gy_log_code'].")"; ?></td>
    <td style="padding: 5px; color: <?php echo $logstatus; ?>;"><?php echo $logrow['gy_log_account']; ?></td>
</tr>

<?php
	}else if($lgscntr > 300){
		$sqldt = $logrow['gy_log_id'];
		$link->query("DELETE FROM `gy_logs` WHERE `gy_log_id` = $sqldt");
	}
	$lgscntr++;
} ?>