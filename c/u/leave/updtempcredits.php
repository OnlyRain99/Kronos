<?php
    include '../../../config/conn.php';
	include '../../../config/function.php';
    include '../session.php';
if($user_type == 1 && $user_dept == 2){

function cmpllog($dbn, $old, $new){
    $arw = " ";
    $rnd = rand(0,3);
    if($rnd==0){ $arw=" <i class='fas fa-angle-double-right faa-horizontal faa-reverse faa-fast animated'></i> "; }
    else if($rnd==1){ $arw=" <i class='fas fa-angle-double-right faa-passing faa-slow animated'></i> "; }
    else if($rnd==2){ $arw=" <i class='fas fa-angle-double-right faa-passing faa-fast animated'></i> "; }
    else if($rnd==3){ $arw=" <i class='fas fa-angle-double-right faa-horizontal animated'></i> "; }
    return $dbn." : ".$old.$arw.$new." <br> ";
}

$opt = addslashes($_REQUEST['opt']);

if($opt==1){
$emcd = addslashes($_REQUEST['emcd']);
$lvcd = addslashes($_REQUEST['lvcd']);

$cmpsql = $link->query("SELECT `gy_emp_leave_credits` From `gy_employee` where `gy_emp_code`='$emcd'");
$cmprow=$cmpsql->fetch_array();
$cng=cmpllog("Leave Credits Manual", $cmprow['gy_emp_leave_credits'], $lvcd);
if($lvcd!="" && $lvcd>=0){
    $cnfrmupt=$link->query("UPDATE `gy_employee` SET `gy_emp_leave_credits`='$lvcd' Where `gy_emp_code`='$emcd'");
    if($cnfrmupt){ echo "success";
        //updatelogs
        $myfile = "../../../hr_logs/pdsupdatelogs.php";
        if(!file_exists($myfile)){
            $handle = fopen($myfile, "w") or die("Unable to open file!");
            fwrite($handle, '');
            fclose($handle);
        }
            clearstatcache();
$newmsg = '
{
"by":"'.$user_code.'",
"datetime":"'.$datenow.'",
"owner":"'.$emcd.'",
"changes":"'.$cng.'"
}
';
        $fp = fopen($myfile,'r+');
        $filesize = filesize($myfile);
        $content = fread($fp, $filesize);
        if(strlen($content)>0){
            fseek($fp, -3, SEEK_END);
            fwrite($fp, ','.ltrim($newmsg).']');
        }else{
            fwrite($fp, '['.$newmsg.']');
        }
        fclose($fp);
    }else{ echo "error"; }
}else{ echo "invcredit"; }
}else if($opt==2){
$empcd = explode(",",addslashes($_REQUEST['empcd']));
$clval = addslashes($_REQUEST['clval']);
for($i=0;$i<count($empcd);$i++){
    $cmpsql = $link->query("SELECT `gy_emp_leave_credits` From `gy_employee` where `gy_emp_code`='".$empcd[$i]."'");
    $cmprow=$cmpsql->fetch_array();
    $cng=cmpllog("Leave Credits Manual", $cmprow['gy_emp_leave_credits'], $clval);

    $link->query("UPDATE `gy_employee` SET `gy_emp_leave_credits`='$clval' Where `gy_emp_code`='".$empcd[$i]."'");

        //updatelogs
        $myfile = "../../../hr_logs/pdsupdatelogs.php";
        if(!file_exists($myfile)){
            $handle = fopen($myfile, "w") or die("Unable to open file!");
            fwrite($handle, '');
            fclose($handle);
        }
            clearstatcache();
$newmsg = '
{
"by":"'.$user_code.'",
"datetime":"'.$datenow.'",
"owner":"'.$empcd[$i].'",
"changes":"'.$cng.'"
}
';
        $fp = fopen($myfile,'r+');
        $filesize = filesize($myfile);
        $content = fread($fp, $filesize);
        if(strlen($content)>0){
            fseek($fp, -3, SEEK_END);
            fwrite($fp, ','.ltrim($newmsg).']');
        }else{
            fwrite($fp, '['.$newmsg.']');
        }
        fclose($fp);
} echo "dnupdtlvcrdt"; }
}
$link->close();
?>