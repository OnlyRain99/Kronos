<?php
    include '../../../../config/conn.php';
	include '../../../../config/function.php';
    include '../session.php';

	$ctctha=0;
    $empsql=$link->query("SELECT `gy_tagumdate`,`gy_davaodate` From `gy_employee` WHERE `gy_emp_code`='$user_code' ");
	$emprow=$empsql->fetch_array();
	$tagdate = $emprow['gy_tagumdate'];
	$davdate = $emprow['gy_davaodate'];
        if($tagdate!="0000-00-00" && $davdate=="0000-00-00"){
            if(date("Y-m-d", strtotime($tagdate))<=date("Y-m-d")){ $ctctha=0; }
        }else if($tagdate=="0000-00-00" && $davdate!="0000-00-00"){
            if(date("Y-m-d", strtotime($davdate))<=date("Y-m-d")){ $ctctha=1; }
        }else if($tagdate!="0000-00-00" && $davdate!="0000-00-00"){
            if(date("Y-m-d", strtotime($tagdate))<=date("Y-m-d")&&(date("Y-m-d", strtotime($tagdate))>date("Y-m-d", strtotime($davdate))||date("Y-m-d", strtotime($davdate))>date("Y-m-d")) ){ $ctctha=0; }
            else if(date("Y-m-d", strtotime($davdate))<=date("Y-m-d")&&(date("Y-m-d", strtotime($davdate))>date("Y-m-d", strtotime($tagdate))||date("Y-m-d", strtotime($tagdate))>date("Y-m-d")) ){ $ctctha=1; }
        }

    $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 AND (`gy_holiday_calendar`.`gy_hol_loc`=$ctctha OR `gy_holiday_calendar`.`gy_hol_loc`=2) order by `gy_holiday_calendar`.`gy_hol_date` asc");
	$hccount=$hcsql->num_rows;

    $hc1sql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=0 AND (`gy_holiday_calendar`.`gy_hol_loc`=$ctctha OR `gy_holiday_calendar`.`gy_hol_loc`=2) order by `gy_holiday_calendar`.`gy_hol_date` asc");
    $hc1count=$hc1sql->num_rows;

    $levsql=$link->query("SELECT * From `gy_leave_available` WHERE `gy_acc_id`=$myaccount order by `gy_leave_avail_date` asc");
	$levcnt=$levsql->num_rows;
    $i4=0; $lvearr = array(array());
    while($levrow=$levsql->fetch_array()){
    	$lvearr[$i4][0]=$levrow['gy_acc_id'];
    	$lvearr[$i4][1]=$levrow['gy_leave_avail_id'];
    	$lvearr[$i4][2]=$levrow['gy_leave_avail_approved'];
    	$lvearr[$i4][3]=$levrow['gy_leave_avail_plotted'];
    	$lvearr[$i4][4]=$levrow['gy_leave_avail_date'];
    	$lvearr[$i4][5]=$levrow['gy_leave_avail_dateto'];
    	$lvearr[$i4][6]=$levrow['gy_leave_avail_justify'];
    	$i4++;
    }

   $dssql=$link->query("SELECT * FROM `gy_leave` WHERE `gy_user_id`='$user_id' ORDER BY `gy_leave_date_from` asc");
    $i5=0; $dssarr = array(array());
    while($dssrow=$dssql->fetch_array()){
    	$dssarr[$i5][0]=$dssrow['gy_leave_id'];
    	$dssarr[$i5][1]=$dssrow['gy_leave_type'];
    	$dssarr[$i5][2]=$dssrow['gy_leave_date_from'];
    	$dssarr[$i5][3]=$dssrow['gy_leave_date_to'];
    	$dssarr[$i5][4]=$dssrow['gy_leave_status'];
    	$i5++;
    }

    echo '[';

    for($i=0;$i<$i4;$i++){
        echo '{';
        echo '"id": "'.$lvearr[$i][1].'",';
        echo '"title": "Available Slot: '.($lvearr[$i][3]-$lvearr[$i][2]).'",';
        echo '"start": "'.date("Y-m-d", strtotime($lvearr[$i][4])).'",';
        echo '"end": "'.date("Y-m-d", strtotime($lvearr[$i][5]."+1 day")).'",';
        echo '"description": "'.$lvearr[$i][6].'",';
        echo '"color": "#D4AC0D"';
        if(($levcnt>1 && $i<($levcnt-1))||$i5>0||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
    }

    for($i=0;$i<$i5;$i++){
    	if($dssarr[$i][4]==1){$rmks="LOA Approved";}
    	else if($dssarr[$i][4]==0){$rmks="Pending LOA";}
        else if($dssarr[$i][4]==2){$rmks='Rejected LOA';}
        else {$rmks='LOA Rejected';}
    	echo '{';
    	echo '"id": "'.$dssarr[$i][0].'",';
    	echo '"title": "'.$rmks.'",';
    	echo '"start": "'.date("Y-m-d", strtotime($dssarr[$i][2])).'",';
    	echo '"end": "'.date("Y-m-d", strtotime($dssarr[$i][3])).'",';
    	echo '"description": "'.get_leave_type($dssarr[$i][1]).'",';
    	if($dssarr[$i][4]==1){ echo '"color": "#168E61"'; }else if($dssarr[$i][4]==0){ echo '"color": "#16A0DC"'; }else if($dssarr[$i][4]==2){ echo '"color": "#D81010"'; }else{ echo '"color": "#8C8181"'; }
    	if($i<($i5-1)||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
    }

	$i=1;
    while($hcrow=$hcsql->fetch_array()){
    	echo '{';
    	echo '"id": "'.$hcrow['gy_hol_id'].'",';
    	echo '"title": "'.$hcrow['gy_hol_type_name'].'",';
    	echo '"start": "'.date("Y-m-d", strtotime($hcrow['gy_hol_date'])).'",';
    	echo '"end": "'.date("Y-m-d", strtotime($hcrow['gy_hol_date'])).'",';
    	echo '"description": "'.$hcrow['gy_hol_title'].'",';
    	if($hcrow['gy_hol_type_id']==1){ echo '"color": "#04B87C"'; }else{ echo '"color": "#0377D1"'; }
    	if(($hccount>1 && $i<$hccount)||$hc1count>0){ echo '},'; }else{ echo '}'; }
    	$i++;
	}

	$i=1;
    while($hcrow=$hc1sql->fetch_array()){
    	echo '{';
    	echo '"id": "'.$hcrow['gy_hol_id'].'",';
    	echo '"title": "'.$hcrow['gy_hol_type_name'].'",';
		echo '"description": "'.$hcrow['gy_hol_title'].'",';
    	if($hcrow['gy_hol_type_id']==1){ echo '"color": "#E19210",'; }else{ echo '"color": "#6D07CD",'; }
    	
    	echo '"rrule": {
    		"freq": "yearly",
    		"dtstart": "'.date("Y-m-d", strtotime($hcrow['gy_hol_date'])).'"';
    	if($hcrow['gy_hol_lastday']!="0000-00-00"){
    		echo ', "until": "'.date("Y-m-d", strtotime($hcrow['gy_hol_lastday'])).'" }';
    	}else{
    		echo '}';
    	}
    	if($hc1count>1 && $i<$hc1count){ echo '},'; }else{ echo '}'; }
    	$i++;
    }

	echo ']';
	$link->close();
?>
