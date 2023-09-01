<?php
    include '../../../config/conn.php';
	include '../../../config/function.php';
    include '../session.php';

    $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 order by `gy_holiday_calendar`.`gy_hol_date` asc");
	$hccount=$hcsql->num_rows;

    $hc1sql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=0 order by `gy_holiday_calendar`.`gy_hol_date` asc");
    $hc1count=$hc1sql->num_rows;

    $levsql=$link->query("SELECT * From `gy_leave_available` order by `gy_leave_avail_date` asc");
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

	$dptsql=$link->query("SELECT `gy_accounts`.`gy_acc_id`as`accid`,`gy_department`.`name_department`as`dptname`,`gy_accounts`.`gy_acc_name`as`accnm` From `gy_accounts` LEFT JOIN `gy_department` ON `gy_accounts`.`gy_dept_id`=`gy_department`.`id_department` ");
    $i1=0; $dptarr = array(array());
    while($dptrow=$dptsql->fetch_array()){
        $dptarr[$i1][0]=$dptrow['accid'];
        $dptarr[$i1][1]=$dptrow['dptname'];
        $dptarr[$i1][2]=$dptrow['accnm'];
        $i1++;
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

$ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE `gy_leave`.`gy_user_id`!='$user_id' AND `gy_employee`.`gy_emp_supervisor`='$user_id'");
   $i6=0; $glsarr = array(); $gldar = array();
   $i7=0; $tmsarr = array(); $tmarr = array();
   $i8=0; $aprarr = array(); $aparr = array();
    while($glsrow=$ctlsql->fetch_array()){
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
    	if($i<($i5-1)||$levcnt>0||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
    }

    for($i=0;$i<$i4;$i++){
    	$title="";
	for($i3=0;$i3<$i1;$i3++){if($lvearr[$i][0]==$dptarr[$i3][0]){$title=$dptarr[$i3][2]; break;}}
    	echo '{';
    	echo '"id": "'.$lvearr[$i][1].'",';
    	echo '"title": "'.$title.' ('.$lvearr[$i][2].'/'.$lvearr[$i][3].')'.'",';
    	echo '"start": "'.date("Y-m-d", strtotime($lvearr[$i][4])).'",';
    	echo '"end": "'.date("Y-m-d", strtotime($lvearr[$i][5]."+1 day")).'",';
    	echo '"description": "'.$lvearr[$i][6].'",';
		echo '"color": "#D4AC0D"';
    	if(($levcnt>1 && $i<($levcnt-1))||$hc1count>0||$hccount>0){ echo '},'; }else{ echo '}'; }
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
