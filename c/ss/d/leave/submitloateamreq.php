<?php
    include '../../../../config/conn.php';
	include '../../../../config/function.php';
    include '../session.php';

$lid = addslashes($_REQUEST['lid']);
$val = addslashes($_REQUEST['val']);
$rmrks = addslashes($_REQUEST['rmrks']);
$thisdate = addslashes($_REQUEST['date']);

$i9=0; $acntarr = array();
$empsql=$link->query("SELECT `gy_acc_id` FROM `gy_employee` WHERE `gy_emp_supervisor`=$user_id OR `gy_emp_code`='$user_code' ");
    while($emprow=$empsql->fetch_array()){
        if(!in_array($emprow['gy_acc_id'], $acntarr)){ $acntarr[$i9]=$emprow['gy_acc_id']; $i9++; }
    }
$ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` WHERE `gy_leave`.`gy_user_id`!='$user_id' AND `gy_leave`.`gy_acc_id` IN (".implode(',',$acntarr).") AND `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_leave`.`gy_leave_id`='$lid' AND `gy_leave`.`gy_leave_status`=0 AND `gy_user`.`gy_user_type`!=".$_SESSION['fus_user_type']);
$tmlvcnt=$ctlsql->num_rows;
$leave=$ctlsql->fetch_array();
$gyaccid=$leave['gy_acc_id'];
$levsql=$link->query("SELECT `gy_leave_avail_id`,`gy_leave_avail_plotted`,`gy_leave_avail_approved` From `gy_leave_available` WHERE `gy_acc_id`='$gyaccid' AND `gy_leave_avail_plotted`>0 AND `gy_leave_avail_date`<='$thisdate' AND `gy_leave_avail_dateto`>='$thisdate' ");
$levcnt=$levsql->num_rows;

if($tmlvcnt==1){
$levrow=$levsql->fetch_array();

if($val==1){
if((($leave['gy_leave_type']!=2&&$leave['gy_leave_type']!=9) && strtotime($leave['gy_leave_date_from'])>strtotime(date("Y-m-d 00:00:00"))) || (($leave['gy_leave_type']==2||$leave['gy_leave_type']==9) && strtotime(date("Y-m-d H:i:s"))<=strtotime($leave['gy_leave_filed']."+3 days")) ){
if($levcnt>0 && $levrow['gy_leave_avail_approved']<$levrow['gy_leave_avail_plotted']){
$levavailid=$levrow['gy_leave_avail_id'];

    $update_leave=$link->query("UPDATE `gy_leave` SET `gy_leave_status`='1', `gy_leave_approver`='$user_id',`gy_leave_date_approved`='$datenow',`gy_leave_remarks`='approved' Where `gy_user_id`!='$user_id' AND `gy_acc_id`='$gyaccid' AND `gy_leave_date_from`='$thisdate' AND `gy_leave_id`='$lid'");
    if($update_leave){
        if($leave['gy_leave_type']!=2&&$leave['gy_leave_type']!=9){
            $update_available=$link->query("UPDATE `gy_leave_available` SET `gy_leave_avail_approved`=`gy_leave_avail_approved` + 1 Where `gy_leave_avail_id`='$levavailid' ");
            if($update_available){ echo"sucaprv"; }else{ echo"erraprv"; }
        }else{ echo"sucaprv"; }
    }else{ echo'invalidreq'; }
}else{ echo"noslot";  }
}else{ echo"xprdreq"; }
}else if($val==0 && $rmrks!=""){
    $update_leave=$link->query("UPDATE `gy_leave` SET `gy_leave_approver`='$user_id',`gy_leave_date_approved`='$datenow',`gy_leave_status`='2',`gy_leave_remarks`='$rmrks' Where `gy_user_id`!='$user_id' AND `gy_leave_date_from`='$thisdate' AND `gy_leave_id`='$lid'");
    if($update_leave){ echo"sucrej"; }else{ echo "erraprv"; }
}else{ echo"invalidrejreason"; }
}else{ echo"reqnotfound"; }

$link->close();
?>