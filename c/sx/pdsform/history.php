<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    $contents = "";
    if($user_type == 6 && $user_dept == 2){
        $myfile = "../../../hr_logs/pdsupdatelogs.php";
        if(file_exists($myfile)){
            $handle = fopen($myfile, "rb") or die("Unable to open file!");
            $filesize = filesize($myfile);
            if($filesize > 0){ $contents = fread($handle, $filesize); }
        }else{
            $handle = fopen($myfile, "w") or die("Unable to open file!");
            fwrite($handle, '');
        }
        fclose($handle);
    }

echo $contents;
$link->close();
?>