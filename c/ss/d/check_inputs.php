<?php

function tfcheck($userid, $lv, $max, $user_id){
    include '../../../config/conn.php';
    $lv++;
    echo $lv." ";
    if($userid == $user_id){
        return true; break;
    }else if($lv > $max ||  $lv <= 0){
        return false; break;
    }else{
        $userid = get_supervisor(get_emp_code($userid));
        return tfcheck($userid, $lv, $max, $user_id);
    }
}

$tfval = false;
$ifcor=$link->query("SELECT `gy_emp_code`, `gy_emp_type` From `gy_employee` Where `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$nameid' LIMIT 1");
$ifcornr = mysqli_num_rows($ifcor);
if($ifcornr == 0){
 $ifcor=$link->query("SELECT `gy_emp_supervisor` From `gy_employee` Where `gy_emp_code`='$nameid' LIMIT 1");
if(mysqli_num_rows($ifcor) > 0){
 $fcrow=$ifcor->fetch_array();
 $tfval = tfcheck($fcrow['gy_emp_supervisor'], 1, 20, $user_id);
}

}else{ $tfval = true; }
?>