<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $mode = @$_GET['mode'];
    $s = @$_GET['s'];
    $f = @$_GET['f'];
    $t = @$_GET['t'];
    $a = @$_GET['a'];

    $filename = "kronos_time_logs_".date("M-d-Y", strtotime($f))." - ".date("M-d-Y", strtotime($t));

    $firstquery = "SELECT `gy_emp_code` As `ID`,`gy_emp_fullname` As `Name`,DATE(`gy_tracker_date`) As `Date`,TIME_FORMAT(`gy_tracker_login`, '%h:%i %p') As `Login`,TIME_FORMAT(`gy_tracker_breakout`, '%h:%i %p') As `Break-Out`,TIME_FORMAT(`gy_tracker_breakin`, '%h:%i %p') As `Break-In`,TIME_FORMAT(`gy_tracker_logout`, '%h:%i %p') As `Logout`,`gy_tracker_wh` As `Work H`,`gy_tracker_bh` As `Break H`,`gy_tracker_ot` As `Overtime`,IF(`gy_tracker_request` = '', 'Pending',`gy_tracker_request`) As `A/R/M`,`gy_tracker_remarks` As `Remarks`,`gy_emp_account` As `Account` From `gy_tracker`";

    if ($mode == "normal") {

        $result=$link->query($firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND date(`gy_tracker_date`)='$onlydate' Order By `gy_emp_fullname` ASC");  

    }else if ($mode == "search"){
        if ($s != "" && $f != "" && $t != "" && $a != "") {

            $search_query = $firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND CONCAT(`gy_emp_email`,`gy_emp_fullname`,`gy_emp_code`) LIKE '%$s%' AND `gy_emp_account`='$a' AND date(`gy_tracker_date`) BETWEEN '$f' AND '$t' Order By `gy_emp_fullname` ASC";
        }else if ($s != "" && $f != "" && $t != "" && $a == "") {

            $search_query = $firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND CONCAT(`gy_emp_email`,`gy_emp_fullname`,`gy_emp_code`) LIKE '%$s%' AND date(`gy_tracker_date`) BETWEEN '$f' AND '$t' Order By `gy_emp_fullname` ASC";
        }else if ($s != "" && $f == "" && $t == "" && $a != "") {

            $search_query = $firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND CONCAT(`gy_emp_email`,`gy_emp_fullname`,`gy_emp_code`) LIKE '%$s%' AND `gy_emp_account`='$a' Order By `gy_emp_fullname` ASC";
        }else if ($s == "" && $f != "" && $t != "" && $a != "") {

            $search_query = $firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND `gy_emp_account`='$a' AND date(`gy_tracker_date`) BETWEEN '$f' AND '$t' Order By `gy_emp_fullname` ASC";
        }if ($s != "" && $f == "" && $t == "" && $a == "") {

            $search_query = $firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND CONCAT(`gy_emp_email`,`gy_emp_fullname`,`gy_emp_code`) LIKE '%$s%' Order By `gy_emp_fullname` ASC";
        }else if ($s == "" && $f == "" && $t == "" && $a != "") {

            $search_query = $firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND `gy_emp_account`='$a' Order By `gy_emp_fullname` ASC";
        }if ($s == "" && $f != "" && $t != "" && $a == "") {

            $search_query = $firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND date(`gy_tracker_date`) BETWEEN '$f' AND '$t' Order By `gy_emp_fullname` ASC";
        }else{
            $search_query = $firstquery." Where `gy_tracker_request`='approve' OR `gy_tracker_request`='overtime' AND date(`gy_tracker_date`)='$onlydate'";
        }

        $result=$link->query($search_query);
    }else{
        $result=$link->query($firstquery." Where date(`gy_tracker_date`)='$onlydate'");
    }

      
    $file_ending = "xls";

    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=$filename.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");

    $sep = "\t"; //tabbed character

    for ($i = 0; $i < mysqli_num_fields($result); $i++) {
    echo mysqli_fetch_field_direct($result, $i)->name . "\t";
    }
    print("\n");    
   
        while($row = mysqli_fetch_row($result))
        {
            $schema_insert = "";
            for($j=0; $j<mysqli_num_fields($result);$j++)
            {
                if(!isset($row[$j]))
                    $schema_insert .= "NULL".$sep;
                elseif ($row[$j] != "")
                    $schema_insert .= "$row[$j]".$sep;
                else
                    $schema_insert .= "".$sep;
            }
            $schema_insert = str_replace($sep."$", "", $schema_insert);
            $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n";
        }   
?>