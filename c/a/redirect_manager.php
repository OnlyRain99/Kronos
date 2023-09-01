<?php  
    include("../../config/conn.php");
    include("../../config/function.php");
    include("session.php");
//trckstatus
    $my_project_header_title = "Searching ..."; 

    //search masterlist
    if (isset($_POST['master_search'])) {
        $s = words($_POST['master_search']);
        $f = words($_POST['from']);
        $t = words($_POST['to']);
        $a = words($_POST['account']);
        $b = words($_POST['trckstatus']);

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
                    header("location: search_master?s=$s&f=$f&t=$t&a=$a&b=$b");
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
                header("location: search_master?s=$s&f=$f&t=$t&a=$a&b=$b");
            }
        }
    }

    //search employee
    if (isset($_POST['emp_search'])) {
        $emp_search = words($_POST['emp_search']);

        if (ctype_space($emp_search)) {
            header("location: stats?note=s_space");
        }else if ($emp_search == "0") {
            header("location: stats?note=s_zero");
        }else if ($emp_search) {
            header("location: search_stats?search_text=$emp_search");
        }
    }

    //search user
    if (isset($_POST['user_search'])) {
        $user_search = words($_POST['user_search']);

        if (ctype_space($user_search)) {
            header("location: users?note=s_space");
        }else if ($user_search == "0") {
            header("location: users?note=s_zero");
        }else if ($user_search) {
            header("location: search_user?search_text=$user_search");
        }
    }

    //search system logs
    if (isset($_POST['search_log'])) {
        $search_log = words($_POST['search_log']);

        if (ctype_space($search_log)) {
            header("location: logs?note=s_space");
        }else if ($search_log == "0") {
            header("location: logs?note=s_zero");
        }else if ($search_log) {
            header("location: search_log?search_text=$search_log");
        }
    }

    //search system logs
    if (isset($_POST['search_custom_log'])) {
        $date_f = words($_POST['date_f']);
        $date_t = words($_POST['date_t']);
        $filter = words($_POST['filter']);

        if ($date_f != "0000-00-00" && $date_t != "0000-00-00") {
            header("location: search_custom_log?datef=$date_f&datet=$date_t&filter=$filter");
        }else{
            header("location: logs?note=empty");
        }
    }

    //search schedule
    if (isset($_POST['datefrom'])) {
        $datefrom = words($_POST['datefrom']);
        $dateto = words($_POST['dateto']);
        $redirect = @$_GET['cd'];

        if ($datefrom != "0000-00-00" && $dateto != "0000-00-00" && $redirect != "") {
            header("location: search_schedule?cd=$redirect&datef=$datefrom&datet=$dateto");
        }else{
            header("location: view_schedule?cd=$redirect&note=empty");
        }
    }
$link->close();
?>