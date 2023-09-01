<?php  
    include("../../../config/conn.php");
    include("../../../config/function.php");
    include("session.php");

    $my_project_header_title = "Searching ...";

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

    //search request
    if (isset($_POST['master_search'])) {
        $s = words($_POST['master_search']);
        $f = words($_POST['from']);
        $t = words($_POST['to']);

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
                    header("location: search_request?s=$s&f=$f&t=$t");
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
                header("location: search_request?s=$s&f=$f&t=$t");
            }
        }
    }

    //search schedule
    if (isset($_POST['s_datefrom'])) {
        $s_datefrom = words($_POST['s_datefrom']);
        $s_dateto = words($_POST['s_dateto']);

        if ($s_datefrom != "0000-00-00" && $s_dateto != "0000-00-00") {
            header("location: search_schedule?datef=$s_datefrom&datet=$s_dateto");
        }else{
            header("location: schedule?note=empty");
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
?>