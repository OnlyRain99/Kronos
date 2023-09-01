<?php  
    //my scripts
    $check_req=$link->query("Select * From `gy_request` Where `gy_req_by`='$user_id' AND `gy_req_status`='0'");
    $countreq=$check_req->num_rows;

    if ($countreq > 0) {
        $getreq=$link->query("Select * From `gy_request` Where `gy_req_by`='$user_id' AND `gy_req_status`='0' Order By `gy_req_code` DESC");
        $reqrow=$getreq->fetch_array();

        $myreqcode = $reqrow['gy_req_code'];
    }else{
        $myreqcode = latest_code("gy_request", "gy_req_code", "1001");
    }
?>