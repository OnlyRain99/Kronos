<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $mode = @$_GET['mode'];
    $pn = @$_GET['pn'];
    $s = @$_GET['s'];
    $f = @$_GET['f'];
    $t = @$_GET['t'];

    if (isset($_POST['btnapprove'])) {

        if(isset($_POST['tracker_id'])) {

            $tracks = 0;

            foreach ($_POST['tracker_id'] as $tracker_id) {

                $mywh = get_wh($tracker_id);

                if ($mywh <= 8) {
                    $workhours = $mywh;
                }else{
                    $workhours = 8;
                }

                if (check_pending($tracker_id) == "yes") {
                    $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_wh`='$workhours',`gy_tracker_ot`='0',`gy_tracker_request`='approve',`gy_tracker_om`='$user_id' Where `gy_tracker_id`='$tracker_id'");
                }else{
                    $updatedata = "";
                }

                $tracks++;
            }
        }

        if ($mode == "normal") {

            if ($tracks > 0) {
                if ($updatedata) {
                    header("location: requests?note=app&pn=$pn");
                }else{
                    header("location: requests?note=error&pn=$pn");
                }
            }else{
                header("location: requests?note=nocheck&pn=$pn");
            }

            
        }else{
            if ($tracks > 0) {
                if ($updatedata) {
                    header("location: search_request?note=app&s=$s&f=$f&t=$t");
                }else{
                    header("location: search_request?note=app&s=$s&f=$f&t=$t");
                }
            }else{
                header("location: search_request?note=nocheck&s=$s&f=$f&t=$t");
            }
        }
    }
?>