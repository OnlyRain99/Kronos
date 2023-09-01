<?php
    include 'conn.php';
    date_default_timezone_set('Asia/Taipei');

$ifok="not";
$fltrid=array();
$cnjsql=$link->query("SELECT * From `cronjob` WHERE `cronid`=2 AND `status`=1 ");
if($cnjsql->num_rows){
    $cjrow=$cnjsql->fetch_array();
    $fltrid[0]=$cjrow['cronval'];
    if(($fltrid[0]==0 && (date("d")==1&&date("Y-m-d")!=date("Y-m-d", strtotime($cjrow['crondate'])) ) ) || $fltrid[0]==1){
        $fltrid[1]=$cjrow['leave_credits'];
        $fltrid[2]=$cjrow['active_filter'];
        if($fltrid[2]==0){
            $ifsql=$link->query("UPDATE `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` SET `gy_employee`.`gy_emp_leave_credits`=`gy_employee`.`gy_emp_leave_credits`+".$fltrid[1]." WHERE `gy_user`.`gy_user_status`=0 ");
            if($ifsql){ $ifok="ok"; }
        }else if($fltrid[2]==1 || $fltrid[2]==2){
            $fltrid[3]=explode(",",$cjrow['filter_id']);
            if($fltrid[2]==1){
                $ifsql=$link->query("UPDATE `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` LEFT JOIN `gy_accounts` ON `gy_accounts`.`gy_acc_id`=`gy_employee`.`gy_acc_id` SET `gy_employee`.`gy_emp_leave_credits`=`gy_employee`.`gy_emp_leave_credits`+".$fltrid[1]." WHERE `gy_user`.`gy_user_status`=0 AND `gy_accounts`.`gy_dept_id` IN ('".implode("','", array_map('mysql_real_escape_string', $fltrid[3]))."') AND `gy_accounts`.`gy_acc_status`=0 ");
                if($ifsql){ $ifok="ok"; }
            }else if($fltrid[2]==2){
                $ifsql=$link->query("UPDATE `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` SET `gy_employee`.`gy_emp_leave_credits`=`gy_employee`.`gy_emp_leave_credits`+".$fltrid[1]." WHERE `gy_user`.`gy_user_status`=0 AND `gy_employee`.`gy_acc_id` IN (".implode(",", $fltrid[3]).") ");
                if($ifsql){ $ifok="ok"; }
            }
        }else if($fltrid[2]==3){
            $datenw = date("Y-m-d");
            $ifsql=$link->query("UPDATE `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` SET `gy_employee`.`gy_emp_leave_credits`=`gy_employee`.`gy_emp_leave_credits`+".$fltrid[1]." WHERE `gy_user`.`gy_user_status`=0 AND (`gy_employee`.`gy_regempdate`!='0000-00-00' && (`gy_employee`.`gy_regempdate`>'2000-01-01' && `gy_employee`.`gy_regempdate`<='$datenw')) ");
            if($ifsql){ $ifok="ok"; }
        }
    }
}

if($ifok=="ok"){
    $link->query("UPDATE `cronjob` SET `crondate`='".date("Y-m-d H:i:s")."', `lastmonth_lc`='".$fltrid[1]."' Where `status`=1 AND `cronid`=2 AND `active_filter`=3");
}

$link->close();
?>