<?php  
    include("../../config/conn.php");
    include("../../config/function.php");
    include("session.php");

    $my_project_header_title = "Searching ...";

    //search my schedule 
    if (isset($_POST['s_datefrom'])) {
        $s_datef = words($_POST['s_datefrom']);
        $s_datet = words($_POST['s_dateto']);

        if ($s_datef != "0000-00-00" && $s_datet != "0000-00-00") {
            header("location: search_myschedule?datef=$s_datef&datet=$s_datet");
        }else{
            header("location: schedule?note=empty");
        }
    }

    //search records
    if (isset($_POST['dtrfrom'])) {
        $dtrfrom = words($_POST['dtrfrom']);
        $dtrto = words($_POST['dtrto']);

        if ($dtrfrom != "0000-00-00" && $dtrto != "0000-00-00") {
            header("location: search_record?datef=$dtrfrom&datet=$dtrto");
        }else{
            header("location: records?note=empty");
        }
    }

    //search schedule
    if (isset($_POST['sibsid'])) {
        $sibsid = words($_POST['sibsid']);
        $redirect = words($_GET['cd']);

        if ($sibsid != "" || $redirect != "") {
            header("location: search_schedule?cd=$redirect&search_text=$sibsid");
        }else{
            header("location: schedule?cd=$redirect&note=empty");
        }
    }



    //search schedule process
    if (isset($_POST['datefrom'])) {
        $datef = words($_POST['datefrom']);
        $datet = words($_POST['dateto']);

        if ($datef != "0000-00-00" && $datet != "0000-00-00") {
            header("location: search_summary?datef=$datef&datet=$datet");
        }else{
            header("location: schedule_summary?note=empty");
        }
    }

    //search request
    if (isset($_POST['master_search'])) {
        $s = words($_POST['master_search']);
        $f = words($_POST['from']);
        $t = words($_POST['to']);
        $fil = words($_POST['filter']);

        if ($f != "" || $t != "") {
            //check dates
            if ($f == "" || $t == "") {
                header("location: requests?note=dateinvalid");
            }else{
                if (ctype_space($s)) {
                    header("location: requests?note=s_space");
                }else if ($s == "0") {
                    header("location: requests?note=s_zero");
                }else if ($s == "" && $f == "" && $t == "") {
                    header("location: requests?note=empty");
                }else{
                    header("location: search_request?s=$s&f=$f&t=$t&fil=$fil");
                }
            }
        }else{
            if (ctype_space($s)) {
                header("location: requests?note=s_space");
            }else if ($s == "0") {
                header("location: requests?note=s_zero");
            }else if ($s == "" && $f == "" && $t == "") {
                header("location: requests?note=empty");
            }else{
                header("location: search_request?s=$s&f=$f&t=$t&fil=$fil");
            }
        }
    }

    //search status
    if (isset($_POST['s_datef'])) {
        $s_datef = words($_POST['s_datef']);
        $s_datet = words($_POST['s_datet']);
        $leader = words($_POST['leader']);

        if ($s_datef != "" && $s_datet != "" && $leader != "") {
            header("location: search_status?l=$leader&f=$s_datef&t=$s_datet");
        }else{
            header("location: status?note=invalid");
        }
    }

    //search escalate request history
    if (isset($_POST['esc_datefrom'])) {
        $esc_datefrom = words($_POST['esc_datefrom']);
        $esc_dateto = words($_POST['esc_dateto']);
        $esc_filter = words($_POST['esc_filter']);

        if ($esc_datefrom != "" && $esc_dateto != "" && $esc_filter != "") {
            header("location: search_history?fil=$esc_filter&f=$esc_datefrom&t=$esc_dateto");
        }else{
            header("location: esc_history?note=invalid");
        }
    }

    //search escalate request history
    if (isset($_POST['sched_datefrom'])) {
        $sched_datefrom = words($_POST['sched_datefrom']);
        $sched_dateto = words($_POST['sched_dateto']);
        $sched_filter = words($_POST['sched_filter']);

        if ($sched_datefrom != "" && $sched_dateto != "" && $sched_filter != "") {
            header("location: search_esc_history?fil=$sched_filter&f=$sched_datefrom&t=$sched_dateto");
        }else{
            header("location: sched_esc_history?note=invalid");
        }
    }

    //search leave request history
    if (isset($_POST['search_leave_request'])) {
        $search_leave_request = words($_POST['search_leave_request']);

        if ($search_leave_request != "") {
            header("location: search_leave_request_history?search_text=$search_leave_request");
        }else{
            header("location: leave_request_history?note=empty");
        }
    }
$link->close(); ?>