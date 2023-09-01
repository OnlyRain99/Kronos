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
            header("location: search_schedule?datef=$s_datef&datet=$s_datet");
        }else{
            header("location: schedule?note=empty");
        }
    }

    //search masterlist
    if (isset($_POST['master_search'])) {
        $s = words($_POST['master_search']);
        $f = words($_POST['from']);
        $t = words($_POST['to']);
        $a = words($_POST['account']);

        if ($f != "" || $t != "") {
            //check dates
            if ($f == "" || $t == "") {
                header("location: masterlist?note=dateinvalid");
            }else{
                if (ctype_space($s)) {
                    header("location: masterlist?note=s_space");
                }else if ($s == "0") {
                    header("location: masterlist?note=s_zero");
                }else if ($s == "" && $f == "" && $t == "" && $a == "") {
                    header("location: masterlist?note=empty");
                }else{
                    header("location: search_master?s=$s&f=$f&t=$t&a=$a");
                }
            }
        }else{
            if (ctype_space($s)) {
                header("location: masterlist?note=s_space");
            }else if ($s == "0") {
                header("location: masterlist?note=s_zero");
            }else if ($s == "" && $f == "" && $t == "" && $a == "") {
                header("location: masterlist?note=empty");
            }else{
                header("location: search_master?s=$s&f=$f&t=$t&a=$a");
            }
        }
    }

    //search confirms
    if (isset($_POST['filter'])) {
        $redirect = $_GET['cd'];
        $filter = $_POST['filter'];

        if ($filter) {
            header("location: search_confirmations?cd=$redirect&filter=$filter");
        }
    }

    //search archieve
    if (isset($_POST['arch_search'])) {
        $arch_search = $_POST['arch_search'];

        if ($arch_search != "") {
            header("location: search_archieve?search_text=$arch_search");
        }else{
            header("location: search_archieve?note=invalid");
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