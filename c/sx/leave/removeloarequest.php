<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$lid = addslashes($_REQUEST['lid']);
$attachment=$link->query("SELECT `gy_leave_attachment`,`gy_leave_status`,`gy_acc_id`,`gy_leave_date_from`,`gy_leave_day`,`gy_user_id`,`gy_publish` From `gy_leave` Where `gy_leave_id`='$lid'");
$attach=$attachment->fetch_array();
$filename=$attach['gy_leave_attachment'];
$gy_acc_id=$attach['gy_acc_id'];
$ldatefrm=$attach['gy_leave_date_from'];
$gylvday=$attach['gy_leave_day'];
$publish=$attach['gy_publish'];
$leave_person = get_emp_code($attach['gy_user_id']);
if($attach['gy_leave_status']==1){
    if(date("d")>18){
        $scutoff = date("Y-m-16");
    }else if(date("d")<4){
        $scutoff = date("Y-m-16", strtotime("-1 month"));
    }else{
        $scutoff = date("Y-m-01");
    }
    if($ldatefrm>=$scutoff){
    $deletedata=$link->query("DELETE FROM `gy_leave` Where `gy_leave_id`='$lid' AND `gy_user_id`='$user_id' AND `gy_leave_status`=1");
    if($deletedata){
        unlink('../../../kronos_file_store/'.$filename); 
        $updtslt=$link->query("UPDATE `gy_leave_available` SET `gy_leave_avail_approved`=`gy_leave_avail_approved`-1 Where `gy_acc_id`=$gy_acc_id AND `gy_leave_avail_date`<='$ldatefrm' AND `gy_leave_avail_dateto`>='$ldatefrm' AND `gy_leave_avail_approved`>0");
        //if($publish==1){
        //$updeuc=$link->query("UPDATE `gy_employee` SET `gy_emp_leave_credits`=`gy_emp_leave_credits`+$gylvday Where `gy_emp_code`='$leave_person'");
        //}
        if($updtslt){ echo"loacancel"; }else{ echo"loanotcancel"; }
    }else{ echo"loanotremove"; }
    }
}else{
    $deletedata=$link->query("DELETE FROM `gy_leave` Where `gy_leave_id`='$lid' AND `gy_user_id`='$user_id' AND `gy_leave_status`!=1");
    if($deletedata){ unlink('../../../kronos_file_store/'.$filename); echo"loaremove";
    }else{ echo"loanotremove"; }
}
$link->close();
?>