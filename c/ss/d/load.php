<?php

	include '../../../config/conn.php';
	include '../../../config/function.php';
	include 'session.php';

	$data = array();

	$limit = date("Y-m-d", strtotime("+ 14 days")); //14 days

	$statement=$link->query("SELECT `gy_leave_avail_id`, `gy_leave_avail_date`, `gy_leave_avail_plotted`, `gy_leave_avail_approved`, `gy_user_id`, `gy_leave_avail_justify` FROM `gy_leave_available` Where `gy_acc_id`='$myaccount' LIMIT 365");

	$result=$statement->fetch_all(MYSQLI_ASSOC);

	foreach($result as $row)
	{

		if ($row['gy_leave_avail_date'] <= $limit) {
			$slot = "0";
		}else{

			if (($row["gy_leave_avail_plotted"] - $row["gy_leave_avail_approved"]) <= 0) {
				$slot = "0";
			}else{
				$slot = ($row["gy_leave_avail_plotted"] - $row["gy_leave_avail_approved"]);
			}
		}

		$data[] = array(
		'id'   => $row["gy_leave_avail_id"],
		'title'   => "Plotted - ".$row["gy_leave_avail_plotted"],
		'start'   => $row["gy_leave_avail_date"],
		'end'   => $row["gy_leave_avail_date"]
		);

		$data[] = array(
		'id'   => $row["gy_leave_avail_id"],
		'title'   => "Approved - ".$row["gy_leave_avail_approved"],
		'start'   => $row["gy_leave_avail_date"],
		'end'   => $row["gy_leave_avail_date"]
		);

		$data[] = array(
		'id'   => $row["gy_leave_avail_id"],
		'title'   => "Available - ".$slot,
		'start'   => $row["gy_leave_avail_date"],
		'end'   => $row["gy_leave_avail_date"]
		);
	}

	echo json_encode($data);

?>