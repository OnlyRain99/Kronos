<?php
    include 'conn.php';
	include 'function.php';

	$ar4t = date("Y-m-d");
	if(date("H")<"04"){ $ar4t = date("Y-m-d", strtotime($ar4t." -1 day")); }
	$ta4n = 5;

$absntname = "";
$absentcnt = 0;
$pplcnt = 0;
$prncnt = 0;
$ttlabsnthrs = 0;
$ar=array(array());
$i=0;
$empar=$link->query("SELECT `gy_employee`.`gy_emp_code`as`gyec`,`gy_employee`.`gy_emp_fname`as`gyfnm`,`gy_employee`.`gy_emp_lname`as`gylnm`,`gy_employee`.`gy_emp_id`as`gyeid` FROM `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_employee`.`gy_acc_id`=$ta4n AND `gy_user`.`gy_user_status`=0 ");
while ($emprow=$empar->fetch_array()){
	$ar[$i][0]=$emprow['gyec'];
	$ar[$i][1]=$emprow['gyfnm'];
	$ar[$i][2]=$emprow['gylnm'];

	$ar[$i][3]="";
	$ar[$i][4]="";
	$empid=$emprow['gyeid'];
		$scdar=$link->query("SELECT `gy_sched_day`,`gy_sched_login`,`gy_sched_logout`,`gy_sched_mode` FROM `gy_schedule` WHERE `gy_sched_day`='".$ar4t."' AND `gy_emp_id`='$empid' ORDER BY `gy_sched_day` DESC LIMIT 1");
		while ($scdrow=$scdar->fetch_array()){
			if($scdrow['gy_sched_mode']==0){ $ar[$i][3]=""; $ar[$i][4]=""; 
			}else{
				$scdin = $scdrow['gy_sched_day']." ".convert24to0($scdrow['gy_sched_login']);
				$scdout = $scdrow['gy_sched_day']." ".convert24to0($scdrow['gy_sched_logout']);
				if($scdin>=$scdout){ $scdout = date("Y-m-d H:i:s", strtotime($scdout." +1 day")); }
				$ar[$i][3]=$scdin;
				$ar[$i][4]=$scdout;
				$pplcnt++;
			}
		}

	$ar[$i][5]="";
	$ar[$i][6]="";
	if($ar[$i][3]=="" || $ar[$i][4]==""){ $ystrdyi = date("Y-m-d H:i:s", strtotime($ar4t." 00:00:00")); }
	else { $ystrdyi = date("Y-m-d H:i:s", strtotime($ar4t." 00:00:00 -1 day")); }
	$trkar=$link->query("SELECT `gy_tracker_login`,`gy_tracker_logout` FROM `gy_tracker` WHERE `gy_tracker_login`>='$ystrdyi' AND `gy_emp_code`='".$ar[$i][0]."' ORDER BY `gy_tracker_date` ASC");
	while ($trkrow=$trkar->fetch_array()){
		if((($ar[$i][3]=="" || $ar[$i][4]=="")&&($trkrow['gy_tracker_login']>=date("H:i:s 00:00:00"))) || (($ar[$i][3]!="" && $ar[$i][4]!="")&&($trkrow['gy_tracker_login']<$ar[$i][4] && ($trkrow['gy_tracker_logout']>$ar[$i][3] || $trkrow['gy_tracker_logout']=="0000-00-00 00:00:00")))){
				$ar[$i][5]="";
	$ar[$i][6]="";
			if($trkrow['gy_tracker_login']!="0000-00-00 00:00:00"){ $ar[$i][5]=date("h:i:s a", strtotime($trkrow['gy_tracker_login'])); }else{ $ar[$i][5]=""; }
			if($trkrow['gy_tracker_logout']!="0000-00-00 00:00:00"){ $ar[$i][6]=date("h:i:s a", strtotime($trkrow['gy_tracker_logout'])); }else{ $ar[$i][6]=""; }			
		}
	}

	$w = intval(date("w", strtotime($ar4t)));
	$r1 = date("Y-m-d", strtotime($ar4t." -".$w." days"));
	$r2 = date("Y-m-d", strtotime($r1." +6 days"));
	$ar[$i][7]="";
	$ar[$i][8]="";
	$i1=7;
	$wekar=$link->query("SELECT `gy_sched_day` FROM `gy_schedule` WHERE `gy_sched_day`>='$r1' AND `gy_sched_day`<='$r2' AND `gy_emp_id`='$empid' AND (`gy_sched_mode`=0 OR `gy_sched_mode`=2) ORDER BY `gy_sched_day` asc LIMIT 2");
		while ($wekrow=$wekar->fetch_array()){
			$ar[$i][$i1]=date("l", strtotime($wekrow['gy_sched_day']));
			if($i1>8){ break; }else{ $i1++; }
		}

	$ar[$i][9]="";
	if(($ar[$i][3]!="" && $ar[$i][4]!="") && $ar[$i][5]!=""){ $ar[$i][9]="Present"; $prncnt++; }
	else if(($ar[$i][3]!="" && $ar[$i][4]!="") && $ar[$i][5]==""){
		if(date("Y-m-d H:i:s")>=$ar[$i][3]){ $absntname.="<li>".$ar[$i][2].", ".$ar[$i][1]."</li>"; $ar[$i][9]="Absent"; $absentcnt++;
		$tmpttlhr=0;
		$tmpttlhr=getdtrwh($ar[$i][3], $ar[$i][4]);
			if($tmpttlhr>=5){ $tmpttlhr-=1; }
			else if($tmpttlhr>4 && $tmpttlhr<5){ $tmpttlhr=4; }
		$ttlabsnthrs+=$tmpttlhr;
		}
	}
	
	$i++;
}
	$link->close();

function getdtrwh($sdate, $adate){
    $tosec = (strtotime($adate) - strtotime($sdate))/60;
    $hour = floor($tosec / 60);
    $min = floor($tosec % 60);

    $total = $hour*60;
    $total += $min;
    return $total/60;
}

function filterData(&$str){
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

$emailsubject = "Coast Collect Attendance Report | ".date("F d, Y", strtotime($ar4t));
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$emailsubject."_".date("His").'.csv"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
ob_start();
$fields = array('#' ,'SiBS ID', 'FIRST NAME', 'LAST NAME', 'LOCATION', 'DESIGNATION', 'PRIMARY/BENCH', 'SCHEDULE (Log-in)', 'SCHEDULE (Logout)','Actual Log in','Actual Log out','REST DAY 1','REST DAY 2','STATUS');
$fp = fopen('php://output', 'w');
fputcsv($fp, $fields);

$emailto = array('peterjohn.florentino@thesiblingssolutions.com','aimee.nadela@thesiblingssolutions.com','wfm@thesiblingssolutions.com');
//$emailto = array('fortjam.palma@thesiblingssolutions.com');
//$emailmessage = "testmsg";
$emailfrom="notification@kronos.mysibs.info";
$emailpswd="t1e2s3t4@568PW";

require '../phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->IsSMTP(true);
$mail->isHTML(true); 
$mail->CharSet = "utf-8";

$mail->Host = 'smtp.hostinger.com'; // Which SMTP server to use.
$mail->Port = 465; // Which port to use, 587 is the default port for TLS security.
$mail->SMTPSecure = 'ssl'; // Which security method to use. TLS is most secure.
$mail->SMTPAuth = true; // Whether you need to login. This is almost always required.
$mail->Username = $emailfrom; // Your Gmail address.
$mail->Password = $emailpswd; // Your Gmail login password or App Specific Password.
//for attachment
$mail->From = $emailfrom;
$mail->FromName = 'Kronos';

$prjabs=0;
if($absentcnt>0 && $pplcnt>0){
 $prjabs=round(($absentcnt/$pplcnt)*100, 2);   
}
$noapvs=0;
if($prncnt>0 && $pplcnt>0){
 $noapvs=round(($prncnt/$pplcnt)*100, 2);   
}

$message='Projected Absenteeism: <b>'.$prjabs.'% </b><br>';
$message.='Projected Lost Hours: <b>'.$ttlabsnthrs.' </b><br>';
$message.='Number of Agents Present vs Scheduled: <b>'.$noapvs.'% </b><br>';
if(date("H")=="21"){
if($absntname!=""){ $message.='<p>Please call these reps who are not logged in yet:<ul style="color:red;">'.$absntname.'</ul></p>'; }
}
$message.='<table style="border: 1px solid #000; white-space: nowrap;" cellspacing="0">
            <thead style="background-color:#0b5394; font-size: 14px;">
                <tr style="color: #fff;">
                    <th style="border: 4px solid #0b5394;">#</th>
					<th style="border: 4px solid #0b5394;">SiBS ID</th>
					<th style="border: 4px solid #0b5394;">FIRST NAME</th>
					<th style="border: 4px solid #0b5394;">LAST NAME</th>
					<th style="border: 4px solid #0b5394;">LOCATION</th>
					<th style="border: 4px solid #0b5394;">DESIGNATION</th>
					<th style="border: 4px solid #0b5394;">PRIMARY/BENCH</th>
					<th style="border: 4px solid #0b5394;">SCHEDULE (Log-in)</th>
					<th style="border: 4px solid #0b5394;">SCHEDULE (Logout)</th>
					<th style="border: 4px solid #0b5394;">Actual Log in</th>
					<th style="border: 4px solid #0b5394;">Actual Log out</th>
					<th style="border: 4px solid #0b5394;">REST DAY 1</th>
					<th style="border: 4px solid #0b5394;">REST DAY 2</th>
					<th style="border: 4px solid #0b5394;">STATUS</th>
                </tr>
                <tr style="background-color:#9fc5e8;"><th colspan="14" style="padding: 3px; border: 1px solid #000; text-align: center;"></th></tr>
            </thead>
            <tbody style="font-size: 13px;">';
for($i2=0;$i2<$i;$i2++){ $brdclr = "#fff"; $tmpscdin=""; $tmpscdout="";
    $message.='<tr '; if($i2%2!=0){ $brdclr = "#f2f2f2"; $message.='style="background-color: '.$brdclr.';"'; }  $message.=' >';
    $message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">'.intval($i2+1).'</td>';
    $message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">SiBS-'.$ar[$i2][0].'</td>';
	$message.='<td style="border: 4px solid '.$brdclr.';">'.ucfirst(strtolower($ar[$i2][1])).'</td>';
	$message.='<td style="border: 4px solid '.$brdclr.';">'.ucfirst(strtolower($ar[$i2][2])).'</td>';
	$message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">PH</td>';
	$message.='<td style="border: 4px solid '.$brdclr.';"></td>';
	$message.='<td style="text-align: center; border: 4px solid '.$brdclr.';"></td>';
	$message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">'; if($ar[$i2][3]!=""){ $tmpscdin=date("h:i:s a", strtotime($ar[$i2][3])); $message.=$tmpscdin; } $message.='</td>';
    $message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">'; if($ar[$i2][4]!=""){ $tmpscdout=date("h:i:s a", strtotime($ar[$i2][4])); $message.=$tmpscdout; } $message.='</td>';
	$message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">'.$ar[$i2][5].'</td>';
	$message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">'.$ar[$i2][6].'</td>';
	$message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">'.$ar[$i2][7].'</td>';
	$message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">'.$ar[$i2][8].'</td>';
	$message.='<td style="text-align: center; border: 4px solid '.$brdclr.';">'.$ar[$i2][9].'</td>';
	$message.='</tr>';

    $lineData = array(intval($i2+1), 'SiBS-'.$ar[$i2][0], ucfirst(strtolower($ar[$i2][1])), ucfirst(strtolower($ar[$i2][2])), 'PH', '', '', $tmpscdin, $tmpscdout, $ar[$i2][5], $ar[$i2][6], $ar[$i2][7], $ar[$i2][8], $ar[$i2][9]);
    array_walk($lineData, 'filterData');
    fputcsv($fp, $lineData);
}
fclose($fp);
$csv_string = ob_get_contents();
ob_end_clean();

$mail->addStringAttachment($csv_string,$emailsubject."_".date("His").".csv");
//unlink($emailsubject."_".date("His").".csv");
$mail->Body = $message.'</tbody></table>';
$mail->Subject = $emailsubject;
while (list ($key, $val) = each ($emailto)) {
$mail->AddAddress($val);
}
$mail->AddCC('fortjam.palma@thesiblingssolutions.com');
$mail->send();

exit;
?>