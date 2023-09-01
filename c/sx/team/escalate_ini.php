<?php  
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

function esshtimecnvrt($keeptime){
    if($keeptime == "24:00:00"){ $keeptime = "00:00:00"; }
    if ($keeptime == "") { $simptime = "--:--"; }
    else{ $simptime = date("g:i A", strtotime($keeptime)); }
    return $simptime;
}

function matchsched($theemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
    include '../../../config/conn.php';
    $today = array(date("Y-m-d H:i:s", strtotime($dblogin)), date("Y-m-d H:i:s", strtotime($dblogin)), "");
    $yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
    $tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
    $datenow = date("Y-m-d", strtotime($dblogin));
    $today[2] = $datenow;
    $sqlemp="`gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."'";
    if($dblogo != "0000-00-00 00:00:00"){ $endday = $dblogo; }
    else if($dbbrei != "0000-00-00 00:00:00"){ $endday = $dbbrei; }
    else if($dbbreo != "0000-00-00 00:00:00"){ $endday = $dbbreo; }
    else { $endday = $dblogin; $sqlemp="`gy_sched_day`='".date("Y-m-d", strtotime($dblogin))."'";  }
    $empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout`,`gy_sched_mode` FROM `gy_schedule` WHERE ".$sqlemp." AND `gy_emp_id`='".$theemp."' ORDER BY `gy_sched_day` ASC");
    if(mysqli_num_rows($empsch) > 0){
        while ($scrow=$empsch->fetch_array()) {
            if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))) {
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
            }else{
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
            }
            $schedin = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))));
            if((strtotime($dblogin) < $schedlout && strtotime($endday) >= $schedin && $scrow['gy_sched_mode']!=0) || $sqlemp=="`gy_sched_day`='".$datenow."'"){
                $today[0] = $scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login'])));
                $today[1] = date("Y-m-d H:i:s", $schedlout);
                $today[2] = $scrow['gy_sched_day'];
            break; }
        }
    }
    return $today;
}

if(isset($_POST['submit'])){
    $redirect = @$_GET['cd'];
    $type = words($_POST['type']);
    $status = words($_POST['status']);
    $dateml =date("Y-m-d", strtotime(@$_GET['ml']));
    $trackerdate = date("Y-m-d H:i:s", strtotime($dateml));
if($redirect == ""){

    $empid = @$_GET['empid'];
    $empml=$link->query("SELECT `gy_emp_id`,`gy_emp_code`,`gy_emp_fullname`,`gy_emp_email`,`gy_emp_account`,`gy_emp_supervisor`,`gy_assignedloc` From `gy_employee` Where `gy_emp_code`='$empid' LIMIT 1");
    $mlrow=$empml->fetch_array();
    $gyempcode = $mlrow['gy_emp_code'];
    $gyempflname = $mlrow['gy_emp_fullname'];
    $gyempemail = $mlrow['gy_emp_email'];
    $gyempacc =  $mlrow['gy_emp_account'];
    $empsup = $mlrow['gy_emp_supervisor'];
    $emploc = $mlrow['gy_assignedloc'];
    $trackercode = latest_code("gy_tracker", "gy_tracker_code", "10001");
    $insertdata=$link->query("INSERT INTO `gy_tracker`(`gy_tracker_code`, `gy_tracker_date`, `gy_emp_code`, `gy_emp_email`, `gy_emp_fullname`, `gy_emp_account`, `gy_tracker_status`, `gy_tracker_om`,`gy_tracker_loc`)Values('$trackercode','$dateml','$gyempcode','$gyempemail','$gyempflname','$gyempacc','1','0','$emploc')");

    $lsttrck=$link->query("SELECT `gy_tracker_id` From `gy_tracker` Where `gy_tracker_code`='$trackercode' AND `gy_emp_code`='$gyempcode' ORDER BY `gy_tracker_id` DESC LIMIT 1");
    $lsttrow=$lsttrck->fetch_array();
    $redirect = $lsttrow['gy_tracker_id'];
}

    $error = 0;
    $gettrack=$link->query("SELECT * From `gy_tracker` Where `gy_tracker_id`='$redirect' AND `gy_tracker_request`=''");
    $trackrow=$gettrack->fetch_array();

//start check if allowed
    $infocnt = mysqli_num_rows($gettrack);
    $gyempcode = $trackrow['gy_emp_code'];
    $ifcor=$link->query("SELECT `gy_emp_code`,`gy_emp_supervisor` From `gy_employee` Where (`gy_acc_id`='$myaccount' OR `gy_emp_supervisor`='$user_id') AND `gy_emp_code`='$gyempcode' LIMIT 1");
    $ifcrow=$ifcor->fetch_array();
    $ifcornr = mysqli_num_rows($ifcor);
    if($ifcornr == 0){
    $ifcor=$link->query("SELECT `gy_emp_supervisor` From `gy_employee` Where `gy_emp_code`='$gyempcode' LIMIT 1");      
    $ifcrow=$ifcor->fetch_array();
    $gyempsup = get_emp_code($ifcrow['gy_emp_supervisor']);
    if(mysqli_num_rows($ifcor) > 0 && $ifcrow['gy_emp_supervisor']!= 0){
    $ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$gyempsup' LIMIT 1");  
    $ifcornr = mysqli_num_rows($ifcor);
    }
    }
    if($ifcornr == 0 && $infocnt == 0){ header("location: ../escalate?cd=$redirect&ml=$dateml&empid=$gyempcode&bname=2&note=error&daerr=Error"); }
//end check if allowed

    $empcode = words($trackrow['gy_emp_code']);
    $empname = words($trackrow['gy_emp_fullname']);
    $empsup = words($ifcrow['gy_emp_supervisor']);
    $emplcod = words($trackrow['gy_tracker_code']);
    $trackerdate = words($trackrow['gy_tracker_date']);
    $empid = getempid($trackrow['gy_emp_code']);
    $trackeronlydate = array($trackerdate, $trackerdate, "");
    $trackeronlydate = matchsched($empid, $trackrow['gy_tracker_date'], $trackrow['gy_tracker_breakout'], $trackrow['gy_tracker_breakin'], $trackrow['gy_tracker_logout']);
    $correctdate = date("Y-m-d", strtotime($trackeronlydate[0]));

        $mysuper = get_supervisor($user_code);

        if($type == 1 || $type == 5){
            $logindate = "";
            $logintime = "";
            $breakouttime = "";
            $breakintime = "";
            $logouttime = "";
        }else if($type == 2){
            $logindate = date("Y-m-d", strtotime($trackeronlydate[1]));
            $logintime = date("H:i:s", strtotime(words($_POST['logintime'])));
            $breakouttime = "";
            $breakintime = "";
            $logouttime = date("H:i:s", strtotime(words($_POST['logouttime'])));
        }else if($type == 3){
            $logindate = "";
            $logintime = "";
            $breakouttime = date("H:i:s", strtotime(words($_POST['breakouttime'])));
            $breakintime = date("H:i:s", strtotime(words($_POST['breakintime'])));
            $logouttime = "";
            $overtime = "";
        }else if($type == 4 || $type == 7 || $type == 8 || $type == 6){
            $logindate = date("Y-m-d", strtotime($trackeronlydate[1]));
            $logintime = date("H:i:s", strtotime(words($_POST['logintime'])));
            $breakouttime = date("H:i:s", strtotime(words($_POST['breakouttime'])));
            $breakintime = date("H:i:s", strtotime(words($_POST['breakintime'])));
            $logouttime = date("H:i:s", strtotime(words($_POST['logouttime'])));
        }

        if($type == 7 && $breakouttime!="" && $breakintime!="" && $breakouttime==$breakintime){ $error++; $daerr="Break time should not have the same value"; }

        $file = strtotime(date("Y-m-d H:i:s"))."_".$_FILES['file']['name'];

        if ($_FILES['file']['name'] != "") {
            $fileTmpLoc = $_FILES["file"]["tmp_name"];
            $fileSize = $_FILES["file"]["size"];
            $file_download_dir = "../../../kronos_file_store/".$file;

            if ($fileSize > 5000000) {
                header("location: ../escalate?cd=$redirect&ml=$dateml&empid=$empcode&bname=2&note=sizelimit");
            }//else{
                //move_uploaded_file($fileTmpLoc, $file_download_dir);
            //}
        }else{
            //$file_download_dir = "";
            header("location: ../escalate?cd=$redirect&ml=$dateml&empid=$empcode&bname=2&note=filename");
        }

        $reason = words($_POST['reason']);

    if($type!=8 && ($type !=6 || ($status != 2&&$status != 3))){
        if ($logindate == "" || $logintime == "") {
            $login = $trackrow['gy_tracker_login'];
        }else{
            if(strtotime($logindate." ".$logintime) > strtotime($trackeronlydate[1])){
                $login = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($logindate))." ".$logintime." -1 day"));
            }else{
                $login = date("Y-m-d H:i:s", strtotime($logindate." ".$logintime));
            }
        }

        if ($logouttime == "") {
            if(strtotime($trackrow['gy_tracker_logout']) > strtotime($login) || $trackrow['gy_tracker_logout']=="0000-00-00 00:00:00"){
                $logout = $trackrow['gy_tracker_logout'];
            }else{
                $logout = date("Y-m-d H:i:s", strtotime($trackrow['gy_tracker_logout']." +1 day"));
            }
        }else{
            if(strtotime(date("Y-m-d", strtotime($login))." ".$logouttime) > strtotime($login)){
                $logout = date("Y-m-d", strtotime($login))." ".$logouttime;
            }else{
                $logout = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($login))." ".$logouttime." +1 day"));
            }
        }

        if ($breakouttime == "") {
            if(strtotime($trackrow['gy_tracker_breakout']) > strtotime($login) || $trackrow['gy_tracker_breakout'] == "0000-00-00 00:00:00"){
                $breakin = $trackrow['gy_tracker_breakin'];
                $breakout = $trackrow['gy_tracker_breakout'];
            }else{
                $breakin = date("Y-m-d H:i:s", strtotime($trackrow['gy_tracker_breakin']." +1 day"));
                $breakout = date("Y-m-d H:i:s", strtotime($trackrow['gy_tracker_breakout']." +1 day"));
            }
        }else{
            if(strtotime(date("Y-m-d", strtotime($login))." ".$breakouttime) > strtotime($login) ){
                $breakout = date("Y-m-d", strtotime($login))." ".$breakouttime;
            }else{
                $breakout = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($login))." ".$breakouttime." +1 day"));
            }

            if(strtotime(date("Y-m-d", strtotime($breakout))." ".$breakintime) > strtotime($breakout) ){
                $breakin = date("Y-m-d", strtotime($breakout))." ".$breakintime;
            }else{
                $breakin = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($breakout))." ".$breakintime." +1 day"));
            }
        }

        if(strtotime($breakout) > strtotime($logout) && $type!=6){ $error++; $daerr="Break or Logout is invalid"; }

        $bh = get_breakhours($breakout, $breakin);
        $wh = getwh($trackeronlydate[0], $trackeronlydate[1]) - $bh;
        $ot = getwh($login, $logout) - getwh($trackeronlydate[0], $trackeronlydate[1]);
        if($type==6 && $ot<0.30){ $error++; $daerr="Total OT should have 30mins or more"; }
        if($wh < 0){ $wh = 0; }else{ $wh = round($wh, 2); }
        if($ot < 0){ $ot = 0; }else{ $ot = round($ot, 2); }
    }
    if($error == 0){
        $escdate = $trackeronlydate[2];
        $datenow = date("Y-m-d H:i:s");
            $tmsht1=$link->query("SELECT `gy_sched_mode`,`gy_sched_login`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_logout` From `gy_schedule` where `gy_sched_day`='$escdate' AND `gy_emp_id`=".getempid($empcode)." limit 1");
            $trrow1=$tmsht1->fetch_array();
            $gysmo = $trrow1['gy_sched_mode'];
            $gysli = $trrow1['gy_sched_login'];
            $gysbo = $trrow1['gy_sched_breakout'];
            $gysbi = $trrow1['gy_sched_breakin'];
            $gyslo = $trrow1['gy_sched_logout'];
        if($type != 8 && ($type !=6 || ($status != 2&&$status != 3))){
            $tmsht=$link->query("SELECT `gy_emp_code`,`gy_tracker_date`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout` From `gy_tracker` where `gy_tracker_id`='$redirect' limit 1");
            $trrow=$tmsht->fetch_array();
            $gyucode = $trrow['gy_emp_code'];
            $gytdate = date("Y-m-d", strtotime($escdate))." ".date("H:i:s", strtotime($trrow['gy_tracker_date']));
            $gytlin = $trrow['gy_tracker_login'];
            $gytlbo = $trrow['gy_tracker_breakout'];
            $gytlbi = $trrow['gy_tracker_breakin'];
            $gytlo = $trrow['gy_tracker_logout'];
		if($type==6&&$status==1){
		    if($tmsht1->num_rows>0){
            //if(date("Y-m-d H:i", strtotime($login))<date("Y-m-d H:i", strtotime($gytlin))&&date("Y-m-d H:i", strtotime($login))<date("Y-m-d H:i", strtotime($escdate." ".$gysli))){ $error++; $daerr="Pre Value Exceeded"; }
            //if(date("Y-m-d H:i", strtotime($logout))>date("Y-m-d H:i", strtotime($gytlo))&&date("Y-m-d H:i", strtotime($logout))>date("Y-m-d H:i", strtotime($escdate." ".$gyslo))){ $error++; $daerr.=" Post Value Exceeded"; }
			}else{ $error++; $daerr="No Schedule"; }
			if($tmsht->num_rows==0 || $gytlin=="0000-00-00 00:00:00" || $gytlo=="0000-00-00 00:00:00"){ $error++; $daerr="Invalid Logs"; }
        }
		if($error == 0){
        $insertdata=$link->query("INSERT INTO `gy_escalate`(`gy_esc_type`, `gy_esc_reason`, `gy_esc_photodir`, `gy_esc_status`, `gy_esc_date`, `gy_esc_by`, `gy_esc_to`, `gy_sup`, `gy_tracker_id`, `gy_tracker_date`, `gy_tracker_login`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_logout`, `gy_tracker_wh`, `gy_tracker_bh`, `gy_tracker_ot`, `gy_usercode`, `old_tracker_date`, `old_tracker_login`, `old_tracker_breakout`, `old_tracker_breakin`, `old_tracker_logout`) Values('$type','$reason','$file','0','$datenow','$user_id','$mysuper','$empsup','$redirect','$trackerdate','$login','$breakout','$breakin','$logout','$wh','$bh','$ot', '$gyucode', '$gytdate', '$gytlin', '$gytlbo', '$gytlbi', '$gytlo')");
        if ($insertdata) {
            move_uploaded_file($fileTmpLoc, $file_download_dir);
            if($type==6){ $history = "<br> OT Escalated from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>"; }
            else if($type==5){ $history = "<br> Escalated Early Out ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>"; }
            else if($type>6){ $history = "<br> Escalated Logs Update -> Login: ".date("h:i a", strtotime($login))." Breakout: ".date("h:i a", strtotime($breakout))." Breakin: ".date("h:i a", strtotime($breakin))." Logout: ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>"; }
            $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='escalate', `gy_tracker_om`='$user_id', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$redirect'");

            $notetext = "Escalation request for ".$trackrow['gy_emp_fullname']." dated -> ".$correctdate;
            $notetype = "insert";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype, $noteucode, $noteuser);
            header("location: ../escalate?cd=$redirect&note=success&text=$logindate");
        }else{ header("location: ../escalate?cd=$redirect&ml=$dateml&empid=$gyucode&bname=2&note=error&daerr=Error"); }
		}else{ header("location: ../escalate.php?cd=$redirect&ml=$dateml&empid=$gyucode&bname=2&note=error&daerr=$daerr"); }
        }else{
            $newin="0000-00-00 00:00:00";
            $newout="0000-00-00 00:00:00";
            $oldin="0000-00-00 00:00:00";
            $oldout="0000-00-00 00:00:00";
            if($status == 0){
                $login = "00:00:00";
                $breakout = "00:00:00";
                $breakin = "00:00:00";
                $logout = "00:00:00";
            }else{
                $login = words($_POST['logintime']);
                $breakout = words($_POST['breakouttime']);
                $breakin = words($_POST['breakintime']);
                $logout = words($_POST['logouttime']);
                if($status==2 && $breakout!="" && $breakin!=""){
                    $newin=date("Y-m-d H:i:s", strtotime(date("Y-m-d",strtotime($trackerdate))." ".date("H:i:s", strtotime($breakout))));
                    $newout=date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($trackerdate))." ".date("H:i:s", strtotime($breakin))));
                    if(strtotime($newin)>strtotime($newout)){ $newout=date("Y-m-d H:i:s", strtotime($newout."+1 day")); }
                    $tmpsin=date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($trackerdate))." ".date("H:i:s", strtotime($login))));
                    $tmpsout=date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($trackerdate))." ".date("H:i:s", strtotime($logout))));
                    if(strtotime($tmpsin)>strtotime($tmpsout)){ $tmpsout=date("Y-m-d H:i:s", strtotime($tmpsout."+1 day")); }
                    if(strtotime($newin)>=strtotime($tmpsout)){ $newin=date("Y-m-d H:i:s", strtotime($newin."-1 day")); }
                    if(strtotime($newout)<=strtotime($tmpsin) || strtotime($newout)<strtotime($newin)){ $newout=date("Y-m-d H:i:s", strtotime($newout."+1 day")); }
                  $breakout = "";
                  $breakin = "";

                  if($type==6){
                    $therr=getwh($newin, $newout)-getwh($tmpsin, $tmpsout);
                    if($therr>0 && $therr<0.30){ $error++; $daerr="Total OT should have 30mins or more"; }

                    $oldin = $trackrow['gy_tracker_login'];
                    $oldout = $trackrow['gy_tracker_logout'];

					//if(date("Y-m-d H:i", strtotime($newin))<date("Y-m-d H:i", strtotime($oldin))&&date("Y-m-d H:i", strtotime($newin))<date("Y-m-d H:i", strtotime($escdate." ".$gysli))){ $error++; $daerr="Pre Value Exceeded"; }
					//if(date("Y-m-d H:i", strtotime($newout))>date("Y-m-d H:i", strtotime($oldout))&&date("Y-m-d H:i", strtotime($newout))>date("Y-m-d H:i", strtotime($escdate." ".$gyslo))){ $error++; $daerr.=" Post Value Exceeded"; }
					if($gettrack->num_rows==0 || $oldin=="0000-00-00 00:00:00" || $oldout=="0000-00-00 00:00:00"){ $error++; $daerr="Invalid Logs"; }
                  }
                }
            }

            if($error==0){
            $insrtschesc = $link->query("INSERT INTO `gy_schedule_escalate`(`gy_sched_esc_code`, `gy_req_date`, `gy_req_status`, `gy_req_by`, `gy_req_to`, `gy_sup`, `gy_emp_code`, `gy_emp_fullname`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`,`gy_req_reason`,`gy_req_photodir`,`old_sched_mode`,`old_sched_login`,`old_sched_breakout`,`old_sched_breakin`,`old_sched_logout`,`gy_tracker_login`,`gy_tracker_logout`,`old_tracker_login`,`old_tracker_logout`) Values('$emplcod','$datenow',0,'$user_id','$mysuper','$empsup','$empcode','$empname','$escdate','$status','$login','$breakout','$breakin','$logout','$reason','$file','$gysmo','$gysli','$gysbo','$gysbi','$gyslo','$newin','$newout','$oldin','$oldout')");
            if($insrtschesc){
                move_uploaded_file($fileTmpLoc, $file_download_dir);
                $history = "<br> Escalated ".get_mode($status)." from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout))." logs duration from: ".esshtimecnvrt($newin)." - ".esshtimecnvrt($newout)." by ".$user_info." at ".$datenow."<br>";
                $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='escalate', `gy_tracker_om`='$user_id', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$redirect'");
                $notetext = "Escalation request for ".$trackrow['gy_emp_fullname']." dated -> ".$correctdate;
                $notetype = "insert";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype, $noteucode, $noteuser);
                header("location: ../escalate?cd=$redirect&note=success");
            }else{ header("location: ../escalate?cd=$redirect&ml=$dateml&empid=$empcode&bname=2&note=error&daerr=Error"); } }else{ header("location: ../escalate?cd=$redirect&ml=$dateml&empid=$empcode&bname=2&note=error&daerr=$daerr"); }
        }
    }else{ header("location: ../escalate?cd=$redirect&ml=$dateml&empid=$empcode&bname=2&note=error&daerr=$daerr"); }
}else{ header("location: ../escalate?cd=$redirect&ml=$dateml&empid=$empcode&bname=2&note=error&daerr=Error"); }
?>