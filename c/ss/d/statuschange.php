<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $change = @$_GET['c'];
    $mode = @$_GET['mode'];
    $pn = @$_GET['pn'];
    $s = @$_GET['s'];
    $f = @$_GET['f'];
    $t = @$_GET['t'];
    $l = @$_GET['l'];

    $myot = get_ot($redirect);
    $mywh = get_wh($redirect);

    if (isset($_POST['approve'])) {
        
        if ($mywh <= 8) {
            $workhours = $mywh;
        }else{
            $workhours = 8;
        }

        //approve
        $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_wh`='$workhours',`gy_tracker_ot`='0',`gy_tracker_request`='approve',`gy_tracker_om`='$user_id' Where `gy_tracker_id`='$redirect'");
        if ($mode == "normal") {
            if ($updatedata) {
                header("location: requests?note=app&pn=$pn");
            }else{
                header("location: requests?note=error&pn=$pn");
            }
        }else{
            if ($updatedata) {
                header("location: search_request?note=app&s=$s&f=$f&t=$t&l=$l");
            }else{
                header("location: search_request?note=app&s=$s&f=$f&t=$t&l=$l");
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
                header("location: search_request?note=rej&s=$s&f=$f&t=$t&l=$l");
            }else{
                header("location: search_request?note=app&s=$s&f=$f&t=$t&l=$l");
            }
        }
    }
?>