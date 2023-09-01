<?php
    include '../../config/conn.php';
    $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 order by `gy_holiday_calendar`.`gy_hol_date` asc");
	$hccount=$hcsql->num_rows;

    $hc1sql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=0 order by `gy_holiday_calendar`.`gy_hol_date` asc");
    $hc1count=$hc1sql->num_rows;

	$i=1;
    echo '[';
    while($hcrow=$hcsql->fetch_array()){
		$loc="";
		if($hcrow['gy_hol_loc']==0){$loc="(Tagum)";}else if($hcrow['gy_hol_loc']==1){$loc="(Davao)";}
    	echo '{';
    	echo '"id": "'.$hcrow['gy_hol_id'].'",';
    	echo '"title": "'.$hcrow['gy_hol_title'].'",';
    	echo '"start": "'.date("Y-m-d", strtotime($hcrow['gy_hol_date'])).'",';
    	echo '"end": "'.date("Y-m-d", strtotime($hcrow['gy_hol_date'])).'",';
    	echo '"description": "'.$hcrow['gy_hol_type_name'].' '.$loc.'",';
    	if($hcrow['gy_hol_type_id']==1){ echo '"color": "#04B87C"'; }else{ echo '"color": "#0377D1"'; }
    	if(($hccount>1 && $i<$hccount)||$hc1count>0){ echo '},'; }else{ echo '}'; }
    	$i++;
	}

	$i=1;
    while($hcrow=$hc1sql->fetch_array()){
		$loc="";
		if($hcrow['gy_hol_loc']==0){$loc="(Tagum)";}else if($hcrow['gy_hol_loc']==1){$loc="(Davao)";}
    	echo '{';
    	echo '"id": "'.$hcrow['gy_hol_id'].'",';
    	echo '"title": "'.$hcrow['gy_hol_title'].'",';
		echo '"description": "'.$hcrow['gy_hol_type_name'].' '.$loc.'",';

    	if($hcrow['gy_hol_type_id']==1){ echo '"color": "#CD4307",'; }else{ echo '"color": "#CD1907",'; }
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
