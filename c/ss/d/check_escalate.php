<?php  
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    // $check_req=$link->query("Select * From `gy_schedule_escalate` Where `gy_req_by`='$user_id' AND `gy_req_status`='0'");
    // $countreq=$check_req->num_rows;

    // if ($countreq > 0) {
    //     $getreq=$link->query("Select * From `gy_schedule_escalate` Where `gy_req_by`='$user_id' AND `gy_req_status`='0' Order By `gy_sched_esc_code` DESC");
    //     $reqrow=$getreq->fetch_array();

    //     $myreqcode = $reqrow['gy_sched_esc_code'];
    // }else{
    //     $myreqcode = latest_code("gy_schedule_escalate", "gy_sched_esc_code", "1001");
    // }

    $myreqcode = latest_code("gy_schedule_escalate", "gy_sched_esc_code", "1001");

    if ($myreqcode) {
        echo $myreqcode;
    }else{
        echo "";
    }
?>