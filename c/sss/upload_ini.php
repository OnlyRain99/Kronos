<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    if(isset($_FILES['file']['tmp_name'])){

        $file = $_FILES['file']['tmp_name'];
        $from = words($_POST['from']);
        $to = words($_POST['to']);

        $filename = $_FILES['file']['name'];
        $checkfile = checkfile($filename);

        if ($checkfile == 0) {
            header("location: upload_sched?note=file_not_allowed");
        }else{

            $handle = fopen($file, "r");
            $c = 0;

            while(($filesop = fgetcsv($handle, 1000, ",")) !== false){
				if(count($filesop)<5){ $error++; break; }
                $id = $filesop[0];
                $name = $filesop[1];
                $mylogin = $filesop[2];
                $mylogout = $filesop[3];
                $rd = $filesop[4];
				if(count($filesop)==5){ $spcfdt = ""; }
				else if(count($filesop)==6){ $spcfdt = $filesop[5]; }

                //get emp_code
                $empid = getempid($id);

                if ($empid == 0) { $c = $c + 1;
                }else if($mylogin=="" || $mylogout=="" || $rd==""){ $c = $c + 1; $error++; 
                }else{
                    $cond = explode("-", strtolower($rd));
                    $dsday= explode("-", strtolower($spcfdt));
                    
                        $period = new DatePeriod(
                            new DateTime($from),
                            new DateInterval('P1D'),
                            new DateTime(date("Y-m-d", strtotime($to . "+1 day")))
                        );

                        foreach ($period as $dates) {
                            $allowed = 1;
                            $sched_date =  $dates->format('Y-m-d');
							if($spcfdt=="" || in_array(strtolower(date("D", strtotime($sched_date))), $dsday) || in_array(strtolower(date("D", strtotime($sched_date))), $cond)){
                            if(in_array(strtolower(date("D", strtotime($sched_date))), $cond)){
                                $mode = 0;
                                $login = "00:00:00";
                                $breakout = "00:00:00";
                                $breakin = "00:00:00";
                                $logout = "00:00:00";
                            }else{
                                $mode = 1;
                                $login = date("H:i:s", strtotime($mylogin));
                                $breakout = "00:00:00";
                                $breakin = "00:00:00";
                                $logout = date("H:i:s", strtotime($mylogout));

                                $ystrday=date("Y-m-d", strtotime($sched_date."-1 day"));
                                $scdsql=$link->query("SELECT * From `gy_schedule` Where `gy_sched_day`='$ystrday' AND `gy_emp_id`='$empid' AND (`gy_sched_mode`='1' OR `gy_sched_mode`='2') ");
                                while($scdrow=$scdsql->fetch_array()){
                                    $scdin = $scdrow['gy_sched_day']." ".$scdrow['gy_sched_login'];
                                    $scdout = $scdrow['gy_sched_day']." ".$scdrow['gy_sched_logout'];
                                    if($scdin>$scdout){ $scdout = date("Y-m-d H:i:s", strtotime($scdout." +1 day")); }
                                    $scdin1 = $sched_date." ".$login;
                                    if(date("Y-m-d H:i:s", strtotime($scdout." +12 hours"))>$scdin1){ $error++; $allowed=0; }
                                }
                                if($allowed!=0){
                                $tmrday=date("Y-m-d", strtotime($sched_date."+1 day"));
                                $scdsql=$link->query("SELECT * From `gy_schedule` Where `gy_sched_day`='$tmrday' AND `gy_emp_id`='$empid' AND (`gy_sched_mode`='1' OR `gy_sched_mode`='2') ");
                                while($scdrow=$scdsql->fetch_array()){
                                    $scdin = $scdrow['gy_sched_day']." ".$scdrow['gy_sched_login'];
                                    $scdin1 = $sched_date." ".$login;
                                    $scdout1 = $sched_date." ".$logout;
                                    if($scdin1>$scdout1){ $scdout1 = date("Y-m-d H:i:s", strtotime($scdout1." +1 day")); }
                                    if(date("Y-m-d H:i:s", strtotime($scdout1." +12 hours"))>$scdin){ $error++; $allowed=0; }
                                }}
                            }
                            if($allowed==1){
                            //check if exist
                            $checkschedule=$link->query("SELECT `gy_sched_id` From `gy_schedule` Where `gy_sched_day`='$sched_date' AND `gy_emp_id`='$empid'");
                            $schedrow=$checkschedule->fetch_array();
                            $count=$checkschedule->num_rows;

                            if ($count > 0) {
                                $sql = "UPDATE `gy_schedule` SET `gy_sched_mode`='$mode',`gy_sched_login`='$login',`gy_sched_breakout`='$breakout',`gy_sched_breakin`='$breakin',`gy_sched_logout`='$logout',`gy_sched_reg`='$onlydate',`gy_sched_by`='$user_id' Where `gy_sched_id`='".$schedrow['gy_sched_id']."'";
                            }else{
                                $sql = "INSERT INTO `gy_schedule`(`gy_emp_id`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`, `gy_sched_reg`, `gy_sched_by`) VALUES ('$empid','$sched_date','$mode','$login','$breakout','$breakin','$logout','$onlydate','$user_id')";
                            }

                            $stmt = mysqli_prepare($link, $sql);
                            mysqli_stmt_execute($stmt);
							}}
                        }
                        $c = $c + 1;

                }
            }

            if($sql && $error==0){
                $notetext = "Schedule System Upload";
                $notetype = "insert";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype , $noteucode , $noteuser);

                header("location: upload_sched?note=upload_success");
            }else if($error>0){ header("location: upload_sched?note=missingdata");
            }else{ header("location: upload_sched?note=error"); }
            fclose($handle);
        }
        unlink($file);
    } $link->close();
?>