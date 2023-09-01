<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    $rqtschd=$link->query("SELECT * From `gy_schedule_escalate` LEFT JOIN `gy_employee` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_employee`.`gy_emp_code` Where `gy_schedule_escalate`.`gy_req_status`='0' AND `gy_schedule_escalate`.`gy_publish`=0 AND `gy_employee`.`gy_emp_supervisor`=$user_id");
    $count=$rqtschd->num_rows;
    $rqtlogs=$link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_employee` ON `gy_escalate`.`gy_usercode`=`gy_employee`.`gy_emp_code` Where `gy_escalate`.`gy_esc_status`='0' AND `gy_escalate`.`gy_publish`=0 AND `gy_employee`.`gy_emp_supervisor`=$user_id ");
    $count+=$rqtlogs->num_rows;
    if($count>0){ echo $count; }else{ echo ""; }
    
$link->close();
?>