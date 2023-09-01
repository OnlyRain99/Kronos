<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $change = @$_GET['c'];
    $mode = @$_GET['mode'];
    $pn = @$_GET['pn'];
    $s = @$_GET['s'];
    $f = @$_GET['f'];
    $t = @$_GET['t'];
    $fil = @$_GET['fil'];

    $myot = get_ot($redirect);
    $mywh = get_wh($redirect);

    if (isset($_POST['approve'])) {
        $otopt = words($_POST['otopt']);
        $overtime = words($_POST['overtime']);

        if ($otopt == "yes") {
            $opt_value = "overtime";
            $wh = $mywh;
        }else{
            $opt_value = "approve";

            if ($mywh > 8) {
                $wh = 8;
            }else{
                $wh = $mywh;
            }
        }

        //approve
        $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_wh`='$wh',`gy_tracker_ot`='$overtime',`gy_tracker_request`='$opt_value',`gy_tracker_om`='$user_id' Where `gy_tracker_id`='$redirect'");

        if ($mode == "normal") {
            if ($updatedata) {
                header("location: requests?note=app&pn=$pn");
            }else{
                header("location: requests?note=error&pn=$pn");
            }
        }else{
            if ($updatedata) {
                header("location: search_request?note=app&s=$s&f=$f&t=$t&fil=$fil&pn=$pn");
            }else{
                header("location: search_request?note=app&s=$s&f=$f&t=$t&fil=$fil&pn=$pn");
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
                header("location: requests?note=rej&pn=$pn");
            }else{
                header("location: requests?note=error&pn=$pn");
            }
        }else{
            if ($updatedata) {
                header("location: search_request?note=rej&s=$s&f=$f&t=$t&fil=$fil&pn=$pn");
            }else{
                header("location: search_request?note=app&s=$s&f=$f&t=$t&fil=$fil&pn=$pn");
            }
        }
    }
?>