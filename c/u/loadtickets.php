<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

	$login = "";
	$email = 0;
	$phone = 0;
	$lchat = 0;
	$total = 0;

	$gettrk=$link->query("SELECT MAX(gy_tracker_login) AS the_login From `gy_tracker` Where `gy_emp_code`='".$user_code."' LIMIT 1");
    	while ($trkrow=$gettrk->fetch_array()){ $login = $trkrow['the_login']; }
	$link->close();
	if($login != ""){ ?>
			<table id="tickettable" class="table table-bordered" style=" font-size: 14px;">
				<tr>
					<th>Date</th>
					<th>Time</th>
					<th>Ticket ID</th>
					<th>Channel</th>
				</tr>
<?php
    include '../../config/connnk.php';
    $i = 0; $tktdarr = array(array());
		$tktq=$dbticket->query("SELECT `ticket_date`,`ticket_id`,`channel` From `ticket` Where `emp_code`='$user_code' AND `ticket_date`>='$login' ORDER BY `ticket_date` desc");
		while ($tktrow=$tktq->fetch_array()){
			$tktdarr[$i][0] = $tktrow['ticket_date'];
			$tktdarr[$i][1] = $tktrow['ticket_id'];
			$tktdarr[$i][2] = $tktrow['channel'];
	$i++; }
	$dbticket->close();
	for($i=0;$i<count($tktdarr);$i++){
?>
				<tr>
					<td><?php echo date("Y-m-d", strtotime($tktdarr[$i][0])); ?></td>
					<td><?php echo date("h:i A", strtotime($tktdarr[$i][0])); ?></td>
					<td><?php echo $tktdarr[$i][1]; ?></td>
					<td><?php echo $tktdarr[$i][2]; ?></td>
				</tr>
<?php
			if($tktdarr[$i][2]=="Email"){ $email++; }
			else if($tktdarr[$i][2]=="Phone"){ $phone++; }
			else if($tktdarr[$i][2]=="Live Chat"){ $lchat++; }
			$total++;
		}

    include '../../config/connnk.php';
		date_default_timezone_set('Asia/Taipei');
		$datets = date("Y-m-d H:i:s");
		$dbticket->query("UPDATE `vidaxl_masterlist` SET `today_email`='$email',`today_phone`='$phone',`today_chat`='$lchat',`last_update`='$datets' Where `mr_emp_code`='".$user_code."'");
	}

	$dbticket->close();
?>
			</table>
			
<input id="tbemail" type="hidden" value="<?php echo $email; ?>">
<input id="tbphone" type="hidden" value="<?php echo $phone; ?>">
<input id="tblchat" type="hidden" value="<?php echo $lchat; ?>">
<input id="tbtotal" type="hidden" value="<?php echo $total; ?>">