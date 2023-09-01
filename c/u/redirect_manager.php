<?php  
    include("../../config/conn.php");
    include("../../config/function.php");
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

    //search schedule
    if (isset($_POST['datefrom'])) {
        $datefrom = words($_POST['datefrom']);
        $dateto = words($_POST['dateto']);

        if ($datefrom != "0000-00-00" && $dateto != "0000-00-00") {
            header("location: search_schedule?datef=$datefrom&datet=$dateto");
        }else{
            header("location: schedule?note=empty");
        }
    }
?>