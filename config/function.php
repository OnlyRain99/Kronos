<?php  
    include 'config.php';

    function cryptbsc($q){
        //$qEncoded = crypt($q, '$5$TheSiblingsSolutionsz$');
        
        $encryptionMethod = "AES-256-CBC";
        $secret_key = "FDSYF6YDSA9F8FD98F7DS98F7D9S";
        $secret_iv = "fsGZHasd0";
        $key = hash("sha256", $secret_key);
        $iv = substr(hash("sha256", $secret_iv), 0, 16);
        $encryptedMessage = base64_encode(openssl_encrypt($q, $encryptionMethod, $key, 0, $iv));
        
	    return( $encryptedMessage );
    }

	function encryptIt( $q ) {
	    //$cryptKey = 'Helper4webcall:9997772595';
	    //$qEncoded = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
        $encryptionMethod = "AES-256-CBC";
        $secret_key = "FDSYF6YDSA9F8FD98F7DS98F7D9S";
        $secret_iv = "fsGZHasd0";
        $key = hash("sha256", $secret_key);
        $iv = substr(hash("sha256", $secret_iv), 0, 16);
	    $encryptedMessage = base64_encode(openssl_encrypt($q, $encryptionMethod, $key, 0, $iv));
	    
	    return( $encryptedMessage );
	}

	function decryptIt( $q ) {
	    //$cryptKey  = 'Helper4webcall:9997772595';
	    //$qDecoded = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
        $encryptionMethod = "AES-256-CBC";
        $secret_key = "FDSYF6YDSA9F8FD98F7DS98F7D9S";
        $secret_iv = "fsGZHasd0";
        $key = hash("sha256", $secret_key);
        $iv = substr(hash("sha256", $secret_iv), 0, 16);
	    $decryptedMessage = openssl_decrypt(base64_decode($q), $encryptionMethod, $key, 0, $iv);
	    
	    return( $decryptedMessage );
	}

	function words($value){

		include("conn.php");
		
		$not_fake = mysqli_real_escape_string($link , $value);
        $link->close();
		return $not_fake;
	}

    function empstatus($empcode){
        include("conn.php");
            $sttsql=$link->query("SELECT `gy_user_status` FROM `gy_user` Where `gy_user_code`='$empcode' LIMIT 1");
        $res=$sttsql->fetch_array();
        $link->close();
        return $res['gy_user_status'];
    }

	function get_curr_age($birthday){
        //values
        $date_now = strtotime(date("Y-m-d"));
        $value = strtotime($birthday);

        //subtract in seconds
        $date_diff = $date_now-$value;
        //convert in days
        $days = $date_diff / 86400;
        //convert in years
        $years = $days / 365.25;

        //result
        $result = floor($years);

        return $result;
    }

    function get_year_two_param($before, $later){
        //values
        $value_one = strtotime($later);
        $value_two = strtotime($before);

        //subtract in seconds
        $date_diff = $value_one-$value_two;
        //convert in days
        $days = $date_diff / 86400;
        //convert in years
        $years = $days / 365.25;

        //result
        $result = floor($years);

        return $result;
    }

    function get_timeage($basetime, $currenttime){
        $secs = $currenttime - $basetime;
        $days = $secs / 86400;

        if ($days < 1 ) {
            $age = 1;
        }else{
            $age = 1 + $days;
        }

        //classify weither day, month or year
        if ($age < 30.5) {
            $creditage = floor($age)." day(s)";
        }else if ($age >= 30.5 && $age < 365.25) {
            $creditage = floor(($age / 30.5))." month(s)";
        }else{
            $creditage = floor(($age / 265.25))." year(s)";
        }

        return $creditage;
    }

    function my_notify($note_text, $ntype , $ucode , $user){

    	include("conn.php");

    	$note_now = date("Y-m-d H:i:s");
    	$my_notification_full = $note_text." - ".$user;
    	
    	$insert_data=$link->query("INSERT Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('$ntype','$ucode','$my_notification_full','$note_now')");
        $link->close();
    }

    function get_days($fromdate, $todate) {
        $fromdate = \DateTime::createFromFormat('Y-m-d', $fromdate);
        $todate = \DateTime::createFromFormat('Y-m-d', $todate);
        return new \DatePeriod(
            $fromdate,
            new \DateInterval('P1D'),
            $todate->modify('+1 day')
        );
    }

    function data_verify($my_ver_data){
        if ($my_ver_data == "") {
            $my_ver_data_value = "No Data";
        }else{
            $my_ver_data_value = $my_ver_data;
        }

        return $my_ver_data_value;
    }

    function my_rand_str( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";   

        $str="";
        
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    function my_rand_capstr( $length ) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";   

        $str="";
        
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    function my_rand_int( $length ) {
        $chars = "0123456789";   

        $str="";
        
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    function toAlpha($number){
        
        $alphabet = array('N', 'S', 'T', 'A', 'R', 'G', 'O', 'L', 'D', 'E');

        $count = count($alphabet);
        if ($number == 10){
            $alpha = "SN";
        } else if ($number <= $count) {
            return $alphabet[$number - 0];
        }
        $alpha = '';
        while ($number > 0) {
            $modulo = ($number - 0) % $count;
            $alpha  = $alphabet[$modulo] . $alpha;
            $number = floor((($number - $modulo) / $count));
        }
        return $alpha;
    }

    function latest_code($ltable, $lcolumn, $lfirstcount){

        include("conn.php");

        $getlatest=$link->query("SELECT `".$lcolumn."` FROM `".$ltable."` ORDER BY `".$lcolumn."` DESC LIMIT 1");
        $latestrow=$getlatest->fetch_array();
        $countl=$getlatest->num_rows;

        if ($countl == 0) {
            $mylatestcode = $lfirstcount;
        }else{
            $mylatestcode = $latestrow[$lcolumn] + 1;
        }
        $link->close();
        return $mylatestcode;
    }

    function compare_update($old_data , $new_data , $type_data){
        if ($old_data != $new_data) {
            $my_data_res = $type_data.": ".$old_data." -> ".$new_data." , ";
        }else{
            $my_data_res = "";
        }

        return $my_data_res;
    }

    
    //get button status
    function checkbutton($btncolumn, $yourucode){
        
        include 'conn.php';

        //check button column
        $checkbtn=$link->query("SELECT `".$btncolumn."`,`gy_tracker_status` FROM `gy_tracker` Where `gy_emp_code`='$yourucode' Order By `gy_tracker_date` DESC LIMIT 1");
        $countlog=$checkbtn->num_rows;
        $checkbtnrow=$checkbtn->fetch_array();

        if ($countlog == 0) {
            $checkbtnret = "";
        }else if ($checkbtnrow['gy_tracker_status'] == 1) {
            $checkbtnret = "";
        }else{
            if ($checkbtnrow[$btncolumn] == "0000-00-00 00:00:00") {
                $checkbtnret = "";
            }else{
                $checkbtnret = "disabled";
            }
        }
        $link->close();
        return $checkbtnret;
    }

    function buttonstatus($ucode, $accnt){
        include 'conn.php';
        $chkbtnarr = array("","","","","");
        $starttime ="";
        $endtime="";
        $trackid="";
        $checkbtn=$link->query("SELECT `gy_tracker_id`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout`,`gy_tracker_status` FROM `gy_tracker` Where `gy_emp_code`='$ucode' AND `gy_tracker_status`=0 Order By `gy_tracker_date` DESC LIMIT 1");
             if(mysqli_num_rows($checkbtn)>0){
                $checkbtnrow=$checkbtn->fetch_array();
                if($checkbtnrow['gy_tracker_status']==0){
                $trackid = $checkbtnrow['gy_tracker_id'];
                if($checkbtnrow['gy_tracker_login']!="0000-00-00 00:00:00"){
                    $chkbtnarr[0]="disabled"; $starttime=$checkbtnrow['gy_tracker_login']; $endtime=$starttime; }
                if($checkbtnrow['gy_tracker_breakout']!="0000-00-00 00:00:00"){
                    $chkbtnarr[1]="disabled"; $endtime=$checkbtnrow['gy_tracker_breakout']; }
                if($checkbtnrow['gy_tracker_breakin']!="0000-00-00 00:00:00"){
                    $chkbtnarr[2]="disabled"; $endtime=$checkbtnrow['gy_tracker_breakin']; }
                if($checkbtnrow['gy_tracker_logout']!="0000-00-00 00:00:00"){
                    $chkbtnarr[3]="disabled"; $endtime=$checkbtnrow['gy_tracker_logout']; }
                }
             }
        $link->close();
        if($starttime!=""&&$endtime!=""&&$trackid!=""){ $chkbtnarr[4] = nologs($trackid, $ucode, $accnt, $starttime, $endtime); }
        return $chkbtnarr;
    }

    function nologs($trackid, $ucode, $accnt, $starttime, $endtime){
        include 'conn.php';
        $today = date("Y-m-d", strtotime($starttime));
        $yesterday = date("Y-m-d", strtotime($starttime.' -1 day'));
        $tomorrow = date("Y-m-d", strtotime($starttime.' +1 day'));
        $theemp = getempid($ucode);
        $schedlout = "";
        $theschedlout = date("Y-m-d H:i:s", strtotime($starttime));
        $ext = getwh($theschedlout, date("Y-m-d H:i:s"));
        $maxext = 17;
        $empsch=$link->query("SELECT `gy_sched_day`,`gy_sched_login`,`gy_sched_logout`,`gy_sched_breakout`,`gy_sched_breakin` FROM `gy_schedule` WHERE `gy_sched_mode`!=0 AND `gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."' AND `gy_emp_id`=".$theemp." ORDER BY `gy_sched_day` ASC");
        if(mysqli_num_rows($empsch) > 0){
            while($scrow=$empsch->fetch_array()){
                if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))){
                    $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
                }else{
                    $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
                }
        $schedin = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))));
        if(strtotime($starttime) < $schedlout){
            if(strtotime(date("Y-m-d H:i:s")) > $schedlout){
                    $ext = getwh(date("Y-m-d H:i:s", $schedlout), date("Y-m-d H:i:s"));
                    $theschedlout = date("Y-m-d H:i:s", $schedlout);
                    $maxext = 8;
            }
                    break;
                }
            }
        }
        if($ext > $maxext){
            $link->query("UPDATE `gy_tracker` SET `gy_tracker_status`='1' Where `gy_tracker_id`='$trackid'");
                if($accnt==22){
                include '../../config/connnk.php';
                    $dbticket->query("UPDATE `vidaxl_masterlist` SET `today_email`='0',`today_phone`='0',`today_chat`='0' Where `mr_emp_code`='".$ucode."'");
                $dbticket->close();
                header("Refresh:0");
                }
        }
        $link->close();
        return $theschedlout;
    }

    function convert24to0($time){
        if($time == "24:00:00"){ $time = "00:00:00"; }
        return $time;
    }

    function chktime($keeptime){
    if($keeptime != "OFF" && $keeptime != "No Log" && $keeptime != "ABSENT"){
            if ($keeptime == "0000-00-00 00:00:00" || $keeptime == "") { $keeptime = "--:--";}
            else{ $keeptime = date("g:i A", strtotime($keeptime)); }
        }
        return $keeptime;
    }

    function simptime($keeptime){
        
        if ($keeptime == "0000-00-00 00:00:00") {
            $simptime = "--:--";
        }else{
            $simptime = date("g:i A", strtotime($keeptime));
        }

        return $simptime;

    }

    function simpdate($keepdate){
    if(strcmp($keepdate, "No Log") != 0 && strcmp($keepdate, "OFF") != 0 && strcmp($keepdate, "ABSENT") != 0){
        if ($keepdate == "0000-00-00 00:00:00") {
            $simpdate = "no_date";
        }else{
            $simpdate = date("m/d/Y", strtotime($keepdate));
        }
        return $simpdate;
    }else{ return $keepdate; }
    }

    function getmindif($sdate, $adate, $mod){
        $tosec = strtotime($adate) - strtotime($sdate);
        $hour = floor($tosec / 3600);
        if($mod == "in"){ $min = floor(($tosec - 3600 * $hour)/60); }
        else if($mod == "out"){ $min = ceil(($tosec - 3600 * $hour)/60); }
        
        if($min<10){ $min="0".$min; }
        return $hour.".".$min;
    }

    function getwh($sdate, $adate){
        $tosec = (strtotime($adate) - strtotime($sdate))/60;
        $hour = floor($tosec / 60);
        $min = floor($tosec % 60);
        if($min<10){ $min="0".$min; }
        return $hour.".".$min;
    }

    function gethours($hour1 , $hour2){

        if ($hour1 == "" || $hour2 == "") {
            $hours = 0;
        }else{
            $datetime1 = new DateTime($hour1);
            $datetime2 = new DateTime($hour2);
            $diff = $datetime2->diff($datetime1);
            $hours = round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
        }

        return $hours;
    }

    function get_breakhours($bo, $bi){

        if ($bi != "0000-00-00 00:00:00" && $bo != "0000-00-00 00:00:00") {

            $breakhoursdiff = getwh($bo, $bi);

            if ($breakhoursdiff < 1) {
                $breakhours = 1;
            }else{
                $breakhours = $breakhoursdiff;
            }

            if ($breakhours < 0) {
                $breakhours = 0;
            }
        }else{
            $breakhours = 1;
        }
        
        return $breakhours;

    }

    function get_workhours($sli, $slo, $wli, $wbo, $wbi, $wlo){

        if (strtotime($wli) <= strtotime($sli)) {
            $wli = $sli;
        }

        if (strtotime($wlo) >= strtotime($slo)) {
            $wlo = $slo;
        }

        if ($wbo == "0000-00-00 00:00:00" && $wbi == "0000-00-00 00:00:00") {
            $inihours = getwh($wli, $wlo);

            if ($inihours >= 5) {
                $workhours = $inihours - 1;
            }else{
                $workhours = $inihours;
            }

        }else{
            if (get_breakhours($wbo, $wbi) > 1) {
                $workhours = getwh($wli, $wbo) + getwh($wbi, $wlo);
            }else{
                $workhours = getwh($wli, $wlo) - 1;
            }
        }

        if ($workhours < 0) {
            $workhours = 0;
        }

        return $workhours;

    }

    function get_overtime($solo, $olo){

        $overtime = gethours($solo, $olo);

        if ($overtime < 0) {
            $overtime = 0;
        }else{
            $overtime = $overtime;
        }

        return $overtime;
    }

    function get_undertime($uli, $usli){

        $undertime = gethours($uli, $usli);

        if ($undertime < 0) {
            $undertime = 0;
        }else{
            $undertime = $undertime;
        }

        return $undertime;
    }

    function rd_workhours($wli, $wbo, $wbi, $wlo){

        //for half days
        $workhours = 0;
        if ($wbo == "0000-00-00 00:00:00" && $wbi == "0000-00-00 00:00:00") {
            $inihours = getwh($wli, $wlo);

            if ($inihours >= 5) {
                $workhours = $inihours - 1;
            }else{
                $workhours = $inihours;
            }

        }else{
            if (get_breakhours($wbo, $wbi) > 1) {
                $workhours = getwh($wli, $wbo) + getwh($wbi, $wlo);
            }else{
                $workhours = getwh($wli, $wlo) - 1;
            }
        }

        if ($workhours < 0) {
            $workhours = 0;
        }

        return $workhours;
    }

    function rd_overtime($oli, $obo, $obi, $olo){

        $works = rd_workhours($oli, $obo, $obi, $olo);
        $overtime = 0;
        if ($works >= 8.5) {
            $overtime = $works - 8;
        }

        if ($overtime < 0) {
            $overtime = 0;
        }

        return $overtime;
    }

    function get_ut($sli, $slo, $li, $lo){

        if ($li > $sli) {
            $late = gethours($sli, $li);
        }else{
            $late = 0;
        }

        if ($lo < $slo) {
            $undertime = gethours($slo, $lo);
        }else{
            $undertime = 0;
        }

        $ut = round($late + $undertime,2);

        return $ut;

    }

    function checklastedit($empid, $timekeepdate, $allow){

        include 'conn.php';

        if ($allow == 0) {

            $decide = "yes";

        }else{
            $limiter = $allow * 2;

            $getrecords=$link->query("SELECT `gy_edit_date` From `gy_editlog` Where `gy_emp_id`='$empid' Order By `gy_edit_date` DESC LIMIT $limiter");

            $edits=0;

            while ($rec_row=$getrecords->fetch_array()) {

                $year1 = date("y", strtotime($timekeepdate));
                $year2 = date("y", strtotime($rec_row['gy_edit_date']));

                $month1 = date("m", strtotime($timekeepdate));
                $month2 = date("m", strtotime($rec_row['gy_edit_date']));

                if ($year1 == $year2) {
                    if ($month1 == $month2) {
                        
                        $day1 = date("d", strtotime($timekeepdate));
                        $day2 = date("d", strtotime($rec_row['gy_edit_date']));

                        if ((1 <= $day1 && $day1 <= 15) == (1 <= $day2 && $day2 <= 15)) {
                            $edits = $edits + 1;
                        }else if ((16 <= $day1 && $day1 <= 31) == (16 <= $day2 && $day2 <= 31)) {
                            $edits = $edits + 1;
                        }else{
                            $edits = $edits + 0;
                        }
                    }else{
                        $edits = $edits + 0;
                    }
                }else{
                    $edits = $edits + 0;
                }
            }

            if ($edits >= $allow) {
                $decide = "no";
            }else{
                $decide = "yes";
            }
        }
        $link->close();
        return $decide;
    }

    function edit_remain($empid, $timekeepdate, $allowlimit){

        if ($allowlimit == 0) {
            $editsremaining = "infinite";
        }else{
            include 'conn.php';

            $day = date("d", strtotime($timekeepdate));
            $ym = date("Y-m-", strtotime($timekeepdate));

            if (1 <= $day && $day <= 15) {
                //look for 1 to 15 cut-off records

                $checkfrom = $ym."1";
                $checkto = $ym."15";

            }else{
                //look for 16 onwards cut-off records

                $checkfrom = $ym."16";
                $checkto = $ym."31";
            }

            $getremains=$link->query("SELECT `gy_edit_date` From `gy_editlog` Where `gy_emp_id`='$empid' AND `gy_edit_date` BETWEEN '$checkfrom' AND '$checkto'");
            $countremains=$getremains->num_rows;

            $editsremaining = $allowlimit - $countremains;

            if ($editsremaining < 0) {
                $editsremaining = 0;
            }else{
                $editsremaining = $editsremaining;
            }
        $link->close();
        }

        return $editsremaining;

    }

    function get_day($schedule){
        if ($schedule == 1) {
            $yourday = "Monday";
        }else if ($schedule == 2) {
            $yourday = "Tuesday";
        }else if ($schedule == 3) {
            $yourday = "Wednesday";
        }else if ($schedule == 4) {
            $yourday = "Thursday";
        }else if ($schedule == 5) {
            $yourday = "Friday";
        }else if ($schedule == 6) {
            $yourday = "Saturday";
        }else if ($schedule == 7) {
            $yourday = "Sunday";
        }else{
            $yourday = "unknown";
        }

        return $yourday;
    }

    function get_mode($mode){
        if ($mode == 1) {
            $yourmode = "WORK";
        }else if($mode == 2){
            $yourmode = "RDOT";
        }else if($mode == 3){
            $yourmode = "RD Duty";
        }else{
            $yourmode = "OFF";
        }

        return $yourmode;
    }

    function checktime($time){
        
        if ($time == "00:00:00") {
            $time = "";
        }else{
            $time = $time;
        }

        return $time;
    }

    function getshcedlogin($empid, $date){
        include 'conn.php';
		$rval = "";
        $date=date("Y-m-d", strtotime($date));
        $getlogin=$link->query("SELECT `gy_sched_login` From `gy_schedule` Where date(`gy_sched_day`)='$date' AND `gy_emp_id`='$empid'");
        $res=$getlogin->fetch_array();
		if($getlogin->num_rows>0){
			$rval = $res['gy_sched_login'];
		}
        $link->close();
        return $rval;
    }

    function getshcedlogout($empid, $date){
        include 'conn.php';
		$rval = "";
        $date=date("Y-m-d", strtotime($date));
        $getlogout=$link->query("SELECT `gy_sched_logout` From `gy_schedule` Where date(`gy_sched_day`)='$date' AND `gy_emp_id`='$empid'");
        $res=$getlogout->fetch_array();
		if($getlogout->num_rows>0){
			$rval = $res['gy_sched_logout'];
		}
        $link->close();
        return $rval;
    }

    function check_sched_exist($date, $empid){

        include 'conn.php';

        $check=$link->query("SELECT `gy_sched_id` From `gy_schedule` Where `gy_sched_day`='$date' AND `gy_emp_id`='$empid'");
        $count=$check->num_rows;
        $link->close();
        return $count;
    }

    function getempid($empcode){

        include 'conn.php';

        $getempid=$link->query("SELECT `gy_emp_id` From `gy_employee` Where `gy_emp_code`='$empcode'");
        $res=$getempid->fetch_array();
        $count=$getempid->num_rows;

        if ($count > 0) {
            $empid = $res['gy_emp_id'];
        }else{
            $empid = 0;
        }
        $link->close();
        return $empid;
    }

    function getuserfullname($userid){

        include 'conn.php';

        $getuserfullname=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_id`='$userid'");
        $res=$getuserfullname->fetch_array();
        $count=$getuserfullname->num_rows;

        if ($count > 0) {
            $result = $res['gy_full_name'];
        }else{
            $result = "";
        }
        $link->close();
        return $result;
    }

    function getuserid($empcode){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_user_id` From `gy_user` Where `gy_user_code`='$empcode'");
        $res=$statement->fetch_array();

        return $res['gy_user_id'];
        $link->close();
    }

    function getusertype($type){
        if ($type == 0) { $res = "Admin";
        }else if($type > 0){ $res = "L".$type;
        }else{ $res = "unknown";}
        return $res;
    }

    function get_user_type_num($user_id){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_user_type` From `gy_user` Where `gy_user_id`='$user_id'");
        $res=$statement->fetch_array();

        return $res['gy_user_type'];
        $link->close();
    }

    function checkempcode($empcode){

        include 'conn.php';

        $check=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_code`='$empcode'");
        $count=$check->num_rows;

        if ($count > 0) {
            $checked = "no";
        }else{
            $checked = "yes";
        }
        $link->close();
        return $checked;
    }

    function checkemail($email){

        include 'conn.php';

        $check=$link->query("SELECT `gy_emp_id` From `gy_employee` Where `gy_emp_email`='$email'");
        $count=$check->num_rows;

        if ($count > 0) {
            $checked = "no";
        }else{
            $checked = "yes";
        }
        $link->close();
        return $checked;
    }

    function getusername($userid){

        include 'conn.php';

        $getusername=$link->query("SELECT `gy_username` From `gy_user` Where `gy_user_id`='$userid'");
        $res=$getusername->fetch_array();

        return $res['gy_username'];
        $link->close();
    }

    function get_rate_type($rate){
        
        if ($rate == 0) {
            $my_rate = "Daily Rate";
        }else{
            $my_rate = "Monthly Rate";
        }

        return $my_rate;
    }

    function get_ot($trackid){

        include 'conn.php';

        $getot=$link->query("SELECT `gy_tracker_ot` From `gy_tracker` Where `gy_tracker_id`='$trackid'");
        $res=$getot->fetch_array();

        return $res['gy_tracker_ot'];
        $link->close();
    }

    function get_wh($trackid){

        include 'conn.php';

        $getwh=$link->query("SELECT `gy_tracker_wh` From `gy_tracker` Where `gy_tracker_id`='$trackid'");
        $res=$getwh->fetch_array();

        return $res['gy_tracker_wh'];
        $link->close();
    }

    function getath($out, $wh, $bh, $ot, $utl, $ath){
        if($out != "No Log" && $out != "OFF" && $out != "0000-00-00 00:00:00" && $out != "" && $wh > 0){
            //$theath = ((($wh - $bh) + $ot) - ($utl[0]+$utl[1]));
            //if($theath > 0){ return $theath; }else{return 0; }
            $theath = $wh - $bh;
            $theath += $ot;
            $theath-=($utl[0]+$utl[1]);
            if($theath > 0){ return $theath; }else{return 0; }
        }
        else{ return 0; }
    }

    function getathnoot($out, $wh, $bh, $utl, $ath){
        if($ath > 0){ return $ath; }
        else if($out != "No Log" && $out != "OFF" && $out != "0000-00-00 00:00:00" && $out != ""){
            return (($wh - $bh) - ($utl[0]+$utl[1])); }
        else{ return 0; }
    }

    function puretime($time){
        if ($time == "0000-00-00 00:00:00" || $time == "") {
            $time = "";
        }else{
            $time = date('H:i:s', strtotime($time));
        }
        return $time;
    }

    function puredate($date){
        if ($date == "0000-00-00 00:00:00") {
            $date = "";
        }else{
            $date = date("Y-m-d", strtotime($date));
        }
        return $date;
    }

    function sibsid($id){
        $sibsid = (int)$id;
        $count  = strlen("".$sibsid."");

        if ($id == "dev001") {
            $myid = "DEV001";
        }else{
            if ($sibsid <= 0) {
                $myid = $id;
            }else{
                if ($count == 1) {
                    $myid = "0000".$sibsid;
                }else if ($count == 2) {
                    $myid = "000".$sibsid;
                }else if ($count == 3) {
                    $myid = "00".$sibsid;
                }else if ($count == 4) {
                    $myid = "0".$sibsid;
                }else{
                    $myid = $sibsid;
                }
            }
        }
        
        return $myid;
        
    }

    function checkfile($file){

        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if ($ext == "csv") {
            $r_value = 1;
        }else{
            $r_value = 0;
        }

        return $r_value;
    }

    function getlilo($thisdate, $empcode){

        include 'conn.php';

        $empid = getempid($empcode);

        $statement=$link->query("SELECT `gy_sched_login`,`gy_sched_logout` From `gy_schedule` Where `gy_sched_day`='$thisdate' AND `gy_emp_id`='$empid'");
        $count=$statement->num_rows;
        $res=$statement->fetch_array();

        if ($count > 0) {
            $lilo = date("g:i A", strtotime($res['gy_sched_login']))." - ".date("g:i A", strtotime($res['gy_sched_logout']));
        }else{
            $lilo = "no_curr_sched";
        }
        $link->close();
        return $lilo;
    }

    function getbibo($thisdate, $empcode){

        include 'conn.php';

        $empid = getempid($empcode);

        $statement=$link->query("SELECT `gy_sched_breakout`,`gy_sched_breakin` From `gy_schedule` Where `gy_sched_day`='$thisdate' AND `gy_emp_id`='$empid'");
        $count=$statement->num_rows;
        $res=$statement->fetch_array();

        if ($count > 0) {
            $lilo = date("g:i A", strtotime($res['gy_sched_breakout']))." - ".date("g:i A", strtotime($res['gy_sched_breakin']));
        }else{
            $lilo = "no_curr_sched";
        }
        $link->close();
        return $lilo;

    }

    function count_requests(){

        include 'conn.php';

        $request=$link->query("SELECT DISTINCT `gy_req_code` From `gy_request` Where `gy_req_status`='1'");
        $count=$request->num_rows;
        $link->close();
        return $count;
    }

    function wordlimit($word, $limit){

        if (strlen($word) > $limit){
            $new = substr($word, 0, $limit - 2) . '..';
        }else{
            $new = $word;
        }
        return $new;
    }

    function escalate_type($mytype){
        if ($mytype == 1) {
            $type = "Error";
        }else if ($mytype == 2) {
            $type = "Escalate Login/Logout";
        }else if ($mytype == 3) {
            $type = "Escalate Break";
        }else if ($mytype == 4) {
            $type = "Escalate Time Logs";
        }else if ($mytype == 5) {
            $type = "Escalate Early Out (EO)";
        }else if ($mytype == 6) {
            $type = "Escalate My Overtime (OT, RDOT)";
        }else if ($mytype == 7) {
            $type = "Escalate My Missed Log (ML)";
        }else if ($mytype == 8) {
            $type = "Escalate Schedule Adjustment (SA)";
        }else{
            $type = "Unknown";
        }

        return $type;
    }

    function count_escalate($userid){

        include 'conn.php';

    $datestrt = date("Y-m-d H:i:s");
    if(date("d")<=5){ $datestrt = date("Y-m-16 00:00:00", strtotime("-1 Month")); }
    else if(date("d")>=1 && date("d")<=20){ $datestrt = date("Y-m-01 00:00:00"); }
    else if(date("d")>=16){ $datestrt = date("Y-m-16 00:00:00"); }

        $request=$link->query("SELECT `gy_esc_id` From `gy_escalate` LEFT JOIN `gy_user` ON `gy_escalate`.`gy_usercode`=`gy_user`.`gy_user_code` Where `gy_escalate`.`gy_esc_status`='0' AND `gy_user`.`gy_user_type`<=5 AND `gy_user`.`gy_user_type`!=3 AND `gy_escalate`.`gy_tracker_date`>='$datestrt'");
        $count=$request->num_rows;
        $link->close();
        return $count;
    }

    function count_sched_escalate($userid){

        include 'conn.php';

    $datestrt = date("Y-m-d");
    if(date("d")<=5){ $datestrt = date("Y-m-16", strtotime("-1 Month")); }
    else if(date("d")>=1 && date("d")<=20){ $datestrt = date("Y-m-01"); }
    else if(date("d")>=16){ $datestrt = date("Y-m-16"); }

        $request=$link->query("SELECT `gy_sched_esc_id` From `gy_schedule_escalate` LEFT JOIN `gy_user` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_schedule_escalate`.`gy_req_status`='0' AND `gy_user`.`gy_user_type`<=5 AND `gy_user`.`gy_user_type`!=3 AND `gy_schedule_escalate`.`gy_sched_day`>='$datestrt'");
        $count=$request->num_rows;
        $link->close();
        return $count;
    }

    function count_issue($empcode, $userid, $usertype){
        include 'conn.php';
        $count = 0;
    if($usertype<=13){
        $request = $link->query("SELECT `gy_esc_by`,`gy_esc_to`,`gy_sup`,`gy_tracker_id` From `gy_escalate` Where `gy_esc_status`='0'");
        while ($reqrow=$request->fetch_array()) {
            if($reqrow['gy_esc_by']==$userid || $reqrow['gy_esc_to']==$userid || $reqrow['gy_sup']==$userid){ $count++; }
            else{
                $trackid = $reqrow['gy_tracker_id'];
                $getcode=$link->query("SELECT `gy_emp_code` From `gy_tracker` Where `gy_tracker_id`='$trackid' AND `gy_emp_code`='$empcode'");
                if(mysqli_num_rows($getcode) > 0){ $count++; }
            }
        }

        $schsql = $link->query("SELECT `gy_req_by`,`gy_req_to`,`gy_sup`,`gy_emp_code` From `gy_schedule_escalate` Where `gy_req_status`='0' ");
        while ($scrow=$schsql->fetch_array()) {
            if($scrow['gy_req_by']==$userid || $scrow['gy_req_to']==$userid || $scrow['gy_sup']==$userid || $scrow['gy_emp_code']==$empcode){ $count++; }
        }
    }
    if($usertype==3 || $usertype==4){
        if(date("d")>15){ $fdotco = date("Y-m-01"); }else{ $fdotco = date("Y-m-15", strtotime("-1 month")); }
        $request = $link->query("SELECT `gy_esc_by`,`gy_esc_to`,`gy_sup`,`gy_tracker_id`,`msg_usercode` From `gy_escalate` Where `gy_esc_status`='1' AND `gy_publish`=0 AND `gy_tracker_date`>='$fdotco' AND `msg_usercode`!='' ");
        while ($reqrow=$request->fetch_array()) {
            if($reqrow['gy_esc_by']==$userid || $reqrow['gy_esc_to']==$userid || $reqrow['gy_sup']==$userid){
                if(in_array($empcode , explode(",", $reqrow['msg_usercode']))!=1){ $count++; }
            }else{
                $trackid = $reqrow['gy_tracker_id'];
                $getcode=$link->query("SELECT `gy_emp_code` From `gy_tracker` Where `gy_tracker_id`='$trackid' AND `gy_emp_code`='$empcode'");
                if(mysqli_num_rows($getcode) > 0){
                    if(in_array($empcode , explode(",", $reqrow['msg_usercode']))!=1){$count++;} }
            }
        }

        $schsql = $link->query("SELECT `gy_req_by`,`gy_req_to`,`gy_sup`,`gy_emp_code`,`msg_usercode` From `gy_schedule_escalate` Where `gy_req_status`='1' AND `gy_publish`=0 AND `gy_sched_day`>='$fdotco' AND `msg_usercode`!='' ");
        while ($scrow=$schsql->fetch_array()) {
            if($scrow['gy_req_by']==$userid || $scrow['gy_req_to']==$userid || $scrow['gy_sup']==$userid || $scrow['gy_emp_code']==$empcode){
                if(in_array($empcode , explode(",", $scrow['msg_usercode']))!=1){ $count++; } }
        }
    }

        $link->close();
        return $count;
    }

    function get_acc_name($acc_id){

        include 'conn.php';

        $accounts=$link->query("SELECT `gy_acc_name` From `gy_accounts` Where `gy_acc_id`='$acc_id'");
        $acc=$accounts->fetch_array();
        $count=$accounts->num_rows;

        if ($count > 0) {
            $result = $acc['gy_acc_name'];
        }else{
            $result = "unknown";
        }
        $link->close();
        return $result;
    }

    function getall($userid){

        //get all under level 4

        include 'conn.php';

        $mylevel3="";
        $level3=$link->query("SELECT `gy_user_id`,`gy_emp_fullname` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_emp_supervisor`='$userid'");
        while ($getlevel3=$level3->fetch_array()) {
            $mylevel3 .= $getlevel3['gy_user_id'].",";
        }

        $lvl3 = rtrim($mylevel3, ", ");
        $array3  = array_map('intval', explode(',', $lvl3));
        $array3 = implode("','",$array3);

        $mylevel2="";
        $level2=$link->query("SELECT `gy_user_id`,`gy_emp_fullname` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_emp_supervisor` IN ('".$array3."')");
        while ($getlevel2=$level2->fetch_array()) {
            $mylevel2 .= $getlevel2['gy_user_id'].",";
        }

        $lvl = rtrim($userid.",".$mylevel3."".$mylevel2, ", ");
        $array  = array_map('intval', explode(',', $lvl));
        $array = implode("','",$array);
        $link->close();
        return $array;
    }

    function getall_level3($userid){

        //get all under level 3

        include 'conn.php';

        $mylevel2="";
        $level2=$link->query("SELECT `gy_user_id`,`gy_emp_fullname` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_emp_supervisor`='$userid'");
        while ($getlevel2=$level2->fetch_array()) {
            $mylevel2 .= $getlevel2['gy_user_id'].",";
        }

        $lvl = rtrim($userid.",".$mylevel2, ", ");
        $array  = array_map('intval', explode(',', $lvl));
        $array = implode("','",$array);
        $link->close();
        return $array;
    }

    function getlevel2($supervisor){

        include 'conn.php';

        $getinfo=$link->query("SELECT `gy_user_id`,`gy_emp_fullname`,`gy_user_type` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user_id`='$supervisor'");
        $inforow=$getinfo->fetch_array();
        $infocount=$getinfo->num_rows;

        if ($infocount > 0) {
            if ($inforow['gy_user_type'] == 2) {
                $res = $inforow['gy_emp_fullname'];
            }else{
                $res = "-";
            }
        }else{
            $res = "-";
        }
        $link->close();
        return $res;
    }

    function getlevel3($supervisor){

        include 'conn.php';

        $getinfo=$link->query("SELECT `gy_user_id`,`gy_emp_fullname`,`gy_user_type`,`gy_emp_supervisor` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user_id`='$supervisor'");
        $inforow=$getinfo->fetch_array();
        $infocount=$getinfo->num_rows;

        if ($infocount > 0) {
            if ($inforow['gy_user_type'] == 3) {
                $res = $inforow['gy_emp_fullname'];
            }else{
                $super=words($inforow['gy_emp_supervisor']);
                $statement=$link->query("SELECT `gy_user_id`,`gy_emp_fullname`,`gy_user_type`,`gy_emp_supervisor` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user_id`='$super'");
                $result=$statement->fetch_array();
                $countresult=$statement->num_rows;

                if ($countresult > 0) {
                    $res = $result['gy_emp_fullname'];
                }else{
                    $res = "-";
                }
            }
        }else{
            $res = "-";
        }
        $link->close();
        return $res;
    }

    function req_filter($filter){

        if ($filter == "") {
            $myfilter = "Pending";
        }else if ($filter == "escalate") {
            $myfilter = "Escalating";
        }else if ($filter == "reject") {
            $myfilter = "Rejected";
        }else if ($filter == "approve") {
            $myfilter = "Approved";
        }else if ($filter == "all") {
            $myfilter = "All";
        }

        return $myfilter;

    }

    function get_supervisor($usercode){

        include 'conn.php';

        $supervisor=$link->query("SELECT `gy_emp_supervisor` From `gy_employee` Where `gy_emp_code`='$usercode'");
        $super=$supervisor->fetch_array();

        return $super['gy_emp_supervisor'];
        $link->close();
    }

    function get_supervisor_name($supervisor){

        include 'conn.php';

        $supervisor=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_id`='$supervisor'");
        $super=$supervisor->fetch_array();

        return $super['gy_full_name'];
        $link->close();
    }

    function get_escalate_req_name($trackid){

        include 'conn.php';

        $empcodes=$link->query("SELECT `gy_emp_fullname` From `gy_tracker` Where `gy_tracker_id`='$trackid'");
        $getcode=$empcodes->fetch_array();

        return $getcode['gy_emp_fullname'];
        $link->close();
    }

    function check_pending($tracker_id){

        include 'conn.php';

        $tracker=$link->query("SELECT `gy_tracker_request` From `gy_tracker` Where `gy_tracker_id`='$tracker_id'");
        $tracks=$tracker->fetch_array();

        if ($tracks['gy_tracker_request'] == "") {
            $ret = "yes";
        }else{
            $ret = "no";
        }
        $link->close();
        return $ret;

    }

    function check_confirm($ann_id, $conf_by){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_conf_id` From `gy_confirm` Where `gy_ann_id`='$ann_id' AND `gy_conf_by`='$conf_by'");
        $count=$statement->num_rows;

        if ($count > 0) {
            $res = "disabled";
        }else{
            $res = "";
        }
        $link->close();
        return $res;
    }

    function get_seen_date($ann_id, $conf_by){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_conf_date` From `gy_confirm` Where `gy_ann_id`='$ann_id' AND `gy_conf_by`='$conf_by'");
        $res=$statement->fetch_array();

        $link->close();
        return date("M d, Y g:i A", strtotime($res['gy_conf_date']));

    }

    function get_leave_type($leave){
        if($leave==1){ $res = "Vacation/Personal Leave"; }
        else if($leave==2){ $res = "Sick Leave"; }
        else if($leave==3){ $res = "Maternal Leave"; }
        else if($leave==4){ $res = "Paternal Leave"; }
        else if($leave==5){ $res = "Solo Parent Leave"; }
        else if($leave==6){ $res = "Force Leave"; }
        else if($leave==7){ $res = "Indifinite Leave"; }
        else if($leave==8){ $res = "Quarantine Leave"; }
        else if($leave==9){ $res = "Emergency Leave"; }
        return $res;
    }

    function get_leave_credits($user){
        include 'conn.php';
		$rval = "";
        $statement=$link->query("SELECT `gy_emp_leave_credits` From `gy_employee` Where `gy_emp_code`='$user'");
        $res=$statement->fetch_array();
		if($statement->num_rows>0){
			$rval = $res['gy_emp_leave_credits'];
		}
        $link->close();
        return $rval;
    }

    function get_no_of_days($from , $to){

        $datediff = strtotime($to) - strtotime($from);

        return round($datediff / (60 * 60 * 24) + 1);
    }

    function get_leave_pending_count($user_id){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_leave_id` From `gy_leave` Where `gy_user_id`='$user_id' AND `gy_leave_status`='0'");
        $count=$statement->num_rows;

        $link->close();
        return $count;
    }

    function get_account_id($empcode){
        include 'conn.php';
		$rval="";
        $statement=$link->query("SELECT `gy_acc_id` From `gy_employee` Where `gy_emp_code`='$empcode'");
        $row=$statement->fetch_array();
		if($statement->num_rows>0){
				$rval=$row['gy_acc_id'];
		}
        $link->close();
		return $rval;
    }

    function check_plotted($date, $account){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_leave_avail_id` From `gy_leave_available` Where `gy_leave_avail_date`='$date' AND `gy_acc_id`='$account'");
        $count=$statement->num_rows;

        if ($count > 0) {
            $res = "no";
        }else{
            $res = "yes";
        }
        $link->close();
        return $res;
    }

    function get_leave_pending_requests($user_id){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_leave_id` From `gy_leave` Where `gy_leave_status`='0' AND `gy_user_id` IN ('".$user_id."')");
        $count=$statement->num_rows;

        $link->close();
        return $count;
    }

    function get_lv5plusleave($usrid, $myacc, $usrlv){
        include 'conn.php';
        if($usrlv==5){
        $cntctlsql=$link->query("SELECT `gy_leave`.`gy_leave_id` FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE `gy_leave`.`gy_user_id`!='$usrid' AND (`gy_employee`.`gy_emp_supervisor`='$usrid' OR `gy_leave`.`gy_acc_id`='$myacc') AND `gy_leave`.`gy_leave_status`=0 AND `gy_user`.`gy_user_type`<".$usrlv);
        }else if($usrlv>5){
            $dptsql=$link->query("SELECT `gy_dept_id` From `gy_accounts` WHERE `gy_acc_id`='$myacc' limit 1");
            $gydrow=$dptsql->fetch_array();
            $user_dept=$gydrow['gy_dept_id'];
            $cntctlsql=$link->query("SELECT `gy_leave`.`gy_leave_id` FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` LEFT JOIN `gy_accounts` ON `gy_leave`.`gy_acc_id`=`gy_accounts`.`gy_acc_id` LEFT JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE `gy_leave`.`gy_user_id`!='$usrid' AND (`gy_employee`.`gy_emp_supervisor`='$usrid' OR `gy_accounts`.`gy_dept_id`='$user_dept') AND `gy_leave`.`gy_leave_status`=0 AND `gy_user`.`gy_user_type`<".$usrlv);
        }
        $count=$cntctlsql->num_rows;
        $link->close();
        return $count;
    }

    function get_newemp(){
        include 'conn.php';
        $empsql=$link->query("SELECT `gy_employee`.`gy_lastedit_by`as`gyeleb` FROM `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_employee`.`gy_lastedit_by`='' ");
        $count=$empsql->num_rows;
        $link->close();
        return $count;
    }

    function get_leave_request_history($user_id){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_leave_id` From `gy_leave` Where `gy_leave_status`!='0' AND `gy_user_id` IN ('".$user_id."')");
        $count=$statement->num_rows;

        $link->close();
        return $count;
    }

    function get_myteampendingloa($user_id, $accid){
        include 'conn.php';
        $statement=$link->query("SELECT `gy_leave_id` FROM `gy_leave` WHERE `gy_user_id`!='$user_id' AND `gy_acc_id`='$accid' AND `gy_leave_status`='0' ORDER BY `gy_leave_date_from` asc");
        $count=$statement->num_rows;
        $link->close();
        return $count;
    }

    function get_emp_code($user_id){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_user_code` From `gy_user` Where `gy_user_id`='$user_id'");
        $res=$statement->fetch_array();
        return $res['gy_user_code'];
        $link->close();
    }

    function check_leave_availability($from, $to, $account){
        
        include 'conn.php';

        $period = new DatePeriod(
                 new DateTime($from),
                 new DateInterval('P1D'),
                 new DateTime(date("Y-m-d", strtotime($to . "+1 day")))
            );
        $not_allowed = 0;
        foreach ($period as $dates) {

            $leave_date = $dates->format('Y-m-d');

            $statement=$link->query("SELECT `gy_leave_avail_plotted`,`gy_leave_avail_approved` From `gy_leave_available` Where `gy_leave_avail_date`='$leave_date' AND `gy_acc_id`='$account'");
            $count=$statement->num_rows;
            $res=$statement->fetch_array();

            if ($count == 0) {
                $not_allowed++;
            }else if ($res['gy_leave_avail_approved'] >= $res['gy_leave_avail_plotted']) {
                $not_allowed++;
            }else{
                //nothing to do here
            }
        }

        if ($not_allowed > 0) {
            return "not_allowed";
        }else{
            return "allow";
        }
        $link->close();
    }

    function getall_leave_level3($userid){

        //get all under level 3

        include 'conn.php';

        $mylevel2="";
        $level2=$link->query("SELECT `gy_user_id`,`gy_emp_fullname` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_emp_supervisor`='$userid'");
        while ($getlevel2=$level2->fetch_array()) {
            $mylevel2 .= $getlevel2['gy_user_id'].",";
        }

        $lvl = rtrim($mylevel2, ", ");
        $array  = array_map('intval', explode(',', $lvl));
        $array = implode("','",$array);

        $link->close();
        return $array;
    }

    function get_user_function($empcode){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_user_function` From `gy_user` Where `gy_user_code`='$empcode'");
        $res=$statement->fetch_array();

        return $res['gy_user_function'];
        $link->close();
    }

    function get_user_function_type($type){

        if ($type == 0) {
            $res = "default";
        }else if ($type == 1) {
            $res = "Scheduler";
        }else if ($type == 2) {
            $res = "CompBen";
        }else if ($type == 3) {
            $res = "IT/ETC";
        }else{
            $res = "unknown";
        }

        return $res;

    }

    function get_schedule_logout($empid, $date){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_sched_day`,`gy_sched_login`,`gy_sched_logout` From `gy_schedule` Where `gy_emp_id`='$empid' AND `gy_sched_day`='$date'");
        $res=$statement->fetch_array();

        if (strtotime($res['gy_sched_logout']) < strtotime($res['gy_sched_login'])) {
            $slogout = date("Y-m-d H:i:s", strtotime($res['gy_sched_day']."+1 day ".$res['gy_sched_logout']));
        }else{
            $slogout = date("Y-m-d H:i:s", strtotime($res['gy_sched_day']." ".$res['gy_sched_logout']));
        }

        $link->close();
        return $slogout;
    }

    function get_schedule_login($empid, $date){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_sched_day`,`gy_sched_login` From `gy_schedule` Where `gy_emp_id`='$empid' AND `gy_sched_day`='$date'");
        $res=$statement->fetch_array();

        $link->close();
        return date("Y-m-d H:i:s", strtotime($res['gy_sched_day']." ".$res['gy_sched_login']));
    }

    function regular_pay($date1,$date2) {

        if ($date1>$date2) { $tmp=$date1; $date1=$date2; $date2=$tmp; unset($tmp); $sign=-1; } else $sign = 1;
        if ($date1==$date2) return 0;

        $days = 0;
        $working_days = array(0,1,2,3,4,5,6); // Monday-->Friday
        $working_hours = array(6, 22); // from 6:00 to 22:00
        $current_date = $date1;
        $beg_h = floor($working_hours[0]); $beg_m = ($working_hours[0]*60)%60;
        $end_h = floor($working_hours[1]); $end_m = ($working_hours[1]*60)%60;

        // setup the very next first working timestamp

    if (!in_array(date('w',$current_date) , $working_days)) {
        // the current day is not a working day

        // the current timestamp is set at the begining of the working day
        $current_date = mktime( $beg_h, $beg_m, 0, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );
        // search for the next working day
        while ( !in_array(date('w',$current_date) , $working_days) ) {
            $current_date += 24*3600; // next day
        }

    } else {
        // check if the current timestamp is inside working hours

        $date0 = mktime( $beg_h, $beg_m, 0, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );
        // it's before working hours, let's update it
        if ($current_date<$date0) $current_date = $date0;

        $date3 = mktime( $end_h, $end_m, 59, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );
        if ($date3<$current_date) {
            // outch ! it's after working hours, let's find the next working day
            $current_date += 24*3600; // the day after
            // and set timestamp as the begining of the working day
            $current_date = mktime( $beg_h, $beg_m, 0, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );
            while ( !in_array(date('w',$current_date) , $working_days) ) {
                $current_date += 24*3600; // next day
            }
        }
    }

    // so, $current_date is now the first working timestamp available...

    // calculate the number of seconds from current timestamp to the end of the working day
    $date0 = mktime( $end_h, $end_m, 59, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );
    $seconds = $date0-$current_date+1;


    // calculate the number of days from the current day to the end day

    $date3 = mktime( $beg_h, $beg_m, 0, date('n',$date2), date('j',$date2), date('Y',$date2) );
    while ( $current_date < $date3 ) {
        $current_date += 24*3600; // next day
        if (in_array(date('w',$current_date) , $working_days) ) $days++; // it's a working day
    }

    $days--; //because we've allready count the first day (in $seconds)


    // check if end's timestamp is inside working hours
    $date0 = mktime( $beg_h, 0, 0, date('n',$date2), date('j',$date2), date('Y',$date2) );
    if ($date2<$date0) {
        // it's before, so nothing more !
    } else {
        // is it after ?
        $date3 = mktime( $end_h, $end_m, 59, date('n',$date2), date('j',$date2), date('Y',$date2) );
        if ($date2>$date3) $date2=$date3;
        // calculate the number of seconds from current timestamp to the final timestamp
        $tmp = $date2-$date0+1;
        $seconds += $tmp;
    }

        // calculate the working days in seconds

        $seconds += 3600*($working_hours[1]-$working_hours[0])*$days;

        $hours = round($seconds/3600, 2);

        if ($hours <= 0.02) {
            $hours = 0;
        }else{
            $hours = $hours - 0.02;
        }

        return $hours;

    }

    function get_holiday_date($date){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_hol_date` From `gy_holiday_calendar` Where `gy_hol_date`='$date'");
        $res=$statement->fetch_array();
        $count=$statement->num_rows;

        if ($count > 0) {
            $result = $res['gy_hol_date'];
        }else{
            $result = "";
        }

        $link->close();
        return $result;
    }

    function minus_if_more($value, $morethan, $minus){

        if ($value >= $morethan) {
            $value = $value - $minus;
        }else{
            $value = $value;
        }

        return $value;

    }

    function ymd_format($datetime){

        $res = date("Y-m-d", strtotime($datetime));

        return $res;

    }

    function get_nightdiff_overtime($empcode, $date){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_tracker_ot` From `gy_tracker` Where `gy_emp_code`='$empcode' AND `gy_tracker_date`='$date' AND `gy_tracker_request`='overtime'");
        $res=$statement->fetch_array();
        $count=$statement->num_rows;

        if ($count > 0) {
            $result = $res['gy_tracker_ot'];
        }else{
            $result = "";
        }

        $link->close();
        return $result;
    }

    function get_sched_status($scheds){
        
        if ($scheds == 1) {
            $res = "WORK";
        }else if ($scheds == 2) {
            $res = "RD DUTY OT";
        }else if ($scheds == 3) {
            $res = "RD DUTY";
        }else{
            $res = "OFF";
        }

        return $res;
    }

    function get_sched_mode($empid, $date){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_sched_mode` From `gy_schedule` Where `gy_emp_id`='$empid' AND `gy_sched_day`='$date'");
        $res=$statement->fetch_array();

        return $res['gy_sched_mode'];
        $link->close();
    }

    function get_cut_off_date_from($ref){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_process_date_from` From `gy_process` Where `gy_process_ref`='$ref'");
        $res=$statement->fetch_array();

        $a = date("m/d/Y", strtotime($res['gy_process_date_from']));

        $result = $a;

        $link->close();
        return $result;
    }

    function get_cut_off_date_to($ref){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_process_date_to` From `gy_process` Where `gy_process_ref`='$ref'");
        $res=$statement->fetch_array();

        $b = date("m/d/Y", strtotime($res['gy_process_date_to']));

        $result = $b;

        $link->close();
        return $result;
    }

    function get_cut_off_rows($ref){

        include 'conn.php';

        $statement=$link->query("SELECT `gy_process_id` From `gy_process` Where `gy_process_ref`='$ref'");
        $count=$statement->num_rows;

        $link->close();
        return $count;
    }

    function check_leave_today($userid){

        include 'conn.php';

        $mydatenow=words(date("Y-m-d"));

        $statement=$link->query("SELECT `gy_leave_id` From `gy_leave` Where `gy_user_id`='$userid' AND `gy_leave_status`='1' AND `gy_leave_day`='1' AND ('$mydatenow' between `gy_leave_date_from` and `gy_leave_date_to`)");
        $count=$statement->num_rows;

        if ($count > 0) {
            $res = "hidden";
        }else{
            $res = "visible";
        }

        $link->close();
        return $res;
    }
    
    function hourdisplay(){
        for ($i=0; $i < 24; $i++) {
            $mainval = date("H:i:s", strtotime($i.":00:00"));
            $displayval = date("g:i A", strtotime($i.":00:00"));
            echo "<option value=".$mainval.">".$displayval."</option>";
        }
    }
    
    function get_emp_name($empcode){
        include 'conn.php';
        $statement=$link->query("SELECT `gy_emp_fullname` From `gy_employee` Where `gy_emp_code`='$empcode'");
        $res=$statement->fetch_array();

        return $res['gy_emp_fullname'];
        $link->close();
    }

    function pendingescalationrequestl18($uid){
        include 'conn.php';

    $datestrt = date("Y-m-d");
    $datestre = date("Y-m-d H:i:s");
    if(date("d")<=5){ $datestrt = date("Y-m-16", strtotime("-1 Month")); $datestre = date("Y-m-16 00:00:00", strtotime("-1 Month")); }
    else if(date("d")>=1 && date("d")<=20){ $datestrt = date("Y-m-01"); $datestre = date("Y-m-01 00:00:00"); }
    else if(date("d")>=16){ $datestrt = date("Y-m-16"); $datestre = date("Y-m-16 00:00:00"); }

    $rqtschd=$link->query("SELECT * From `gy_schedule_escalate` LEFT JOIN `gy_employee` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_employee`.`gy_emp_code` Where `gy_schedule_escalate`.`gy_req_status`='0' AND `gy_schedule_escalate`.`gy_publish`=0 AND `gy_employee`.`gy_emp_supervisor`=$uid AND `gy_schedule_escalate`.`gy_sched_day`>='$datestrt' ");
    $count=$rqtschd->num_rows;
    $rqtlogs=$link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_employee` ON `gy_escalate`.`gy_usercode`=`gy_employee`.`gy_emp_code` Where `gy_escalate`.`gy_esc_status`='0' AND `gy_escalate`.`gy_publish`=0 AND `gy_employee`.`gy_emp_supervisor`=$uid AND `gy_escalate`.`gy_tracker_date`>='$datestre' ");
    $count+=$rqtlogs->num_rows;
        $link->close();
        return $count;
    }
?>