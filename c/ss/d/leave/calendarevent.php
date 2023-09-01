<?php
    include '../../../../config/conn.php';
	include '../../../../config/function.php';
    include '../session.php';

    $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 order by `gy_holiday_calendar`.`gy_hol_date` asc");
	$hccount=$hcsql->num_rows;

    $hc1sql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=0 order by `gy_holiday_calendar`.`gy_hol_date` asc");
    $hc1count=$hc1sql->num_rows;

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

$i9=0; $acntarr = array();
$empsql=$link->query("SELECT `gy_acc_id` FROM `gy_employee` WHERE `gy_emp_supervisor`=$user_id OR `gy_emp_code`='$user_code' ");
    while($emprow=$empsql->fetch_array()){
        if(!in_array($emprow['gy_acc_id'], $acntarr)){ $acntarr[$i9]=$emprow['gy_acc_id']; $i9++; }
    }

$glsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE `gy_leave`.`gy_user_id`!='$user_id' AND `gy_leave`.`gy_acc_id` IN (".implode(',',$acntarr).") AND `gy_user`.`gy_user_type`!=".$_SESSION['fus_user_type']." ORDER BY `gy_leave_date_from` asc");
   $i6=0; $glsarr = array(); $gldar = array();
   $i7=0; $tmsarr = array(); $tmarr = array();
   $i8=0; $aprarr = array(); $aparr = array();
    while($glsrow=$glsql->fetch_array()){
        //if(!in_array($glsrow['gy_acc_id'], $acntarr)){ $acntarr[$i9]=$glsrow['gy_acc_id']; $i9++; }
        if($glsrow['gy_leave_status']==0){
            if(in_array($glsrow['gy_leave_date_from'], $tmsarr)){
                $idx=array_search($glsrow['gy_leave_date_from'], $tmsarr);
                if($idx!=""){ $tmarr[$idx]++; }
            }
            if(in_array($glsrow['gy_leave_date_from'], $glsarr)){
                $idx=array_search($glsrow['gy_leave_date_from'], $glsarr);
                if($idx!=""){ $gldar[$idx]++; }
            }else{
                $glsarr[$i6]=$glsrow['gy_leave_date_from'];
                $gldar[$i6]=1;
                $i6++;
            }
        }else if($glsrow['gy_leave_status']>1){
            if(in_array($glsrow['gy_leave_date_from'], $tmsarr)){
                $idx=array_search($glsrow['gy_leave_date_from'], $tmsarr);
                if($idx!=""){ $tmarr[$idx]++; }
            }else{
                $tmsarr[$i7]=$glsrow['gy_leave_date_from'];
                $tmarr[$i7]=1;
                $i7++;
            }
        }else if($glsrow['gy_leave_status']==1){
            if(in_array($glsrow['gy_leave_date_from'], $tmsarr)){
                $idx=array_search($glsrow['gy_leave_date_from'], $tmsarr);
                if($idx!=""){ $tmarr[$idx]++; }
            }
                $aprarr[$i8]=$glsrow['gy_leave_date_from'];
                $aparr[$i8]=getuserfullname($glsrow['gy_user_id']);
                $i8++;
        }
    }

    $levsql=$link->query("SELECT * From `gy_leave_available` WHERE `gy_acc_id` IN (".implode(',',$acntarr).") order by `gy_leave_avail_date` asc");
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
    	$lvearr[$i4][7]=get_acc_name($levrow['gy_acc_id']);
    	$i4++;
    }

    echo '[';

    for($i=0;$i<$i8;$i++){
        echo '{';
        echo '"id": "aprvloa'.$i.'",';
        echo '"title": "'.$aparr[$i].'",';
        echo '"start": "'.date("Y-m-d", strtotime($aprarr[$i])).'",';
        echo '"end": "'.date("Y-m-d", strtotime($aprarr[$i]."+1 day")).'",';
        echo '"description": "Approved LOA request in your team",';
        echo '"color": "#168E61"';
        if($i<($i8-1)||$i6>0||$i7>0||$i5>0||$levcnt>0||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
    }

    for($i=0;$i<$i6;$i++){
        echo '{';
        echo '"id": "pndgloa'.$i.'",';
        echo '"title": "Team Pending : '.$gldar[$i].'",';
        echo '"start": "'.date("Y-m-d", strtotime($glsarr[$i])).'",';
        echo '"end": "'.date("Y-m-d", strtotime($glsarr[$i]."+1 day")).'",';
        echo '"description": "Pending LOA request in your team",';
        echo '"color": "#896B6B"';
        if($i<($i6-1)||$i7>0||$i5>0||$levcnt>0||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
    }

    for($i=0;$i<$i7;$i++){
        echo '{';
        echo '"id": "teamloa'.$i.'",';
        echo '"title": "Team Request : '.$tmarr[$i].'",';
        echo '"start": "'.date("Y-m-d", strtotime($tmsarr[$i])).'",';
        echo '"end": "'.date("Y-m-d", strtotime($tmsarr[$i]."+1 day")).'",';
        echo '"description": "LOA request in your team",';
        echo '"color": "#565353"';
        if($i<($i7-1)||$i5>0||$levcnt>0||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
    }

    for($i=0;$i<$i4;$i++){
        echo '{';
        echo '"id": "'.$lvearr[$i][1].'",';
        echo '"title": "Available Slot: '.($lvearr[$i][3]-$lvearr[$i][2]).'",';
        echo '"start": "'.date("Y-m-d", strtotime($lvearr[$i][4])).'",';
        echo '"end": "'.date("Y-m-d", strtotime($lvearr[$i][5]."+1 day")).'",';
        echo '"description": "('.$lvearr[$i][7].') '.$lvearr[$i][6].'",';
        echo '"color": "#D4AC0D"';
        if(($levcnt>1 && $i<($levcnt-1))||$i5>0||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
    }

    for($i=0;$i<$i5;$i++){
    	if($dssarr[$i][4]==1){$rmks="My LOA Approved";}
    	else if($dssarr[$i][4]==0){$rmks='My Pending LOA';}
        else if($dssarr[$i][4]==2){$rmks='My Rejected LOA';}
        else {$rmks='My LOA Rejected';}
    	echo '{';
    	echo '"id": "'.$dssarr[$i][0].'",';
    	echo '"title": "'.$rmks.'",';
    	echo '"start": "'.date("Y-m-d", strtotime($dssarr[$i][2])).'",';
    	echo '"end": "'.date("Y-m-d", strtotime($dssarr[$i][3])).'",';
    	echo '"description": "'.get_leave_type($dssarr[$i][1]).'",';
    	if($dssarr[$i][4]==1){ echo '"color": "#0FAF44"'; }else if($dssarr[$i][4]==0){ echo '"color": "#16A0DC"'; }else if($dssarr[$i][4]==2){ echo '"color": "#D81010"'; }else{ echo '"color": "#8C8181"'; }
    	if($i<($i5-1)||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
    }

	$i=1;
    while($hcrow=$hcsql->fetch_array()){
    	echo '{';
    	echo '"id": "'.$hcrow['gy_hol_id'].'",';
    	echo '"title": "'.$hcrow['gy_hol_type_name'].'",';
    	echo '"start": "'.date("Y-m-d", strtotime($hcrow['gy_hol_date'])).'",';
    	echo '"end": "'.date("Y-m-d", strtotime($hcrow['gy_hol_date'])).'",';
		$gyloc="";
		if($hcrow['gy_hol_loc']==0){$gyloc="(Tagum Only)";}else if($hcrow['gy_hol_loc']==1){$gyloc="(Davao Only)";}
    	echo '"description": "'.$hcrow['gy_hol_title'].' '.$gyloc.'",';
    	if($hcrow['gy_hol_type_id']==1){ echo '"color": "#46D212"'; }else{ echo '"color": "#0377D1"'; }
    	if(($hccount>1 && $i<$hccount)||$hc1count>0){ echo '},'; }else{ echo '}'; }
    	$i++;
	}

	$i=1;
    while($hcrow=$hc1sql->fetch_array()){
		$gyloc="";
		if($hcrow['gy_hol_loc']==0){$gyloc="(Tagum Only)";}else if($hcrow['gy_hol_loc']==1){$gyloc="(Davao Only)";}
    	echo '{';
    	echo '"id": "'.$hcrow['gy_hol_id'].'",';
    	echo '"title": "'.$hcrow['gy_hol_type_name'].'",';
		echo '"description": "'.$hcrow['gy_hol_title'].' '.$gyloc.'",';
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
