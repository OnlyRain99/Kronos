<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $mode = @$_GET['mode'];
    $s = @$_GET['s'];
    $f = @$_GET['f'];
    $t = @$_GET['t'];
    $a = @$_GET['a'];

    $myot = get_ot($redirect);
    $mywh = get_wh($redirect);

    if (isset($_POST['approve'])) {
        $otopt = words($_POST['otopt']);
        $overtime = words($_POST['overtime']);

        if ($otopt == "yes") {
            $opt_value = "overtime";
            $minus_wh = $mywh;
        }else{
            $opt_value = "approve";
            $minus_wh = number_format($mywh - $myot,2);
        }

        //approve
        $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_wh`='$minus_wh',`gy_tracker_ot`='$overtime',`gy_tracker_request`='$opt_value',`gy_tracker_om`='$user_id' Where `gy_tracker_id`='$redirect'");

        if ($mode == "normal") {
            if ($updatedata) {
                header("location: masterlist?note=app");
            }else{
                header("location: masterlist?note=error");
            }
        }else{
            if ($updatedata) {
                header("location: search_master?note=app&s=$s&f=$f&t=$t&a=$a");
            }else{
                header("location: search_master?note=app&s=$s&f=$f&t=$t&a=$a");
            }
        }
    }

    if (isset($_POST['update'])) {
        $reason = words($_POST['my_reason']);
        $remarks = words($_POST['remarks']);

        //reject
        $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_reason`='$reason',`gy_tracker_remarks`='$remarks',`gy_tracker_request`='reject',`gy_tracker_om`='$user_id' Where `gy_tracker_id`='$redirect'");

        if ($mode == "normal") {
            if ($updatedata) {
                header("location: masterlist?note=rej");
            }else{
                header("location: masterlist?note=error");
            }
        }else{
            if ($updatedata) {
                header("location: search_master?note=rej&s=$s&f=$f&t=$t&a=$a");
            }else{
                header("location: search_master?note=app&s=$s&f=$f&t=$t&a=$a");
            }
        }
    }
?>