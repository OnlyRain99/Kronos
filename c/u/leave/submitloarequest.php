<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';
$day7after = strtotime($datenow."+6 days");
if(isset($_REQUEST['lvtyp']) && isset($_REQUEST['lvres']) && isset($_REQUEST['hiddt'])){
$leave_type = addslashes($_REQUEST['lvtyp']);
$lvres = addslashes($_REQUEST['lvres']);
$hiddt = addslashes($_REQUEST['hiddt']);
$lvfhd = addslashes($_REQUEST['lvfhd']);
$ifreqalr = ifreqalready($hiddt, $user_id);
$plotslot = getplotslot($hiddt, $myaccount);
$cmpltdate = date("Y-m-d H:i:s", strtotime($hiddt." "."00:00:00"));

$ifdatetrue=false;
$wbksql=$link->query("SELECT `gy_tracker_login` FROM `gy_tracker` WHERE `gy_emp_code`='$user_code' AND `gy_tracker_login`>='$cmpltdate' ORDER BY `gy_tracker_login` ASC LIMIT 1");
    $wbkrow=$wbksql->fetch_array();
    $wbkcnt=$wbksql->num_rows;
if($wbksql->num_rows>0){
    if($onlydate==date("Y-m-d", strtotime($wbkrow['gy_tracker_login']))){
		if(strtotime($datenow)<=strtotime($wbkrow['gy_tracker_login']."+72 hours") && ($leave_type==2||$leave_type==9)){ $ifdatetrue=true; }
	}else if(strtotime($datenow)<=strtotime($wbkrow['gy_tracker_login']."+48 hours") && ($leave_type==2||$leave_type==9)){ $ifdatetrue=true; }
} if(strtotime($cmpltdate)>=strtotime(date("Y-m-d 00:00:00")) && ($leave_type==2||$leave_type==9)){ $ifdatetrue=true; }

if((strtotime($hiddt)>$day7after && $leave_type==1)|| ($leave_type>2 && $leave_type<9) || $ifdatetrue){

if($lvfhd=="fdid"){ $lvfhd=1; }else if($lvfhd=="hdid"){ $lvfhd=0.5; }
if($ifreqalr==0 && (($leave_type==2||$leave_type==9) || (($leave_type!=2&&$leave_type!=9) && $plotslot[0]>0 && $plotslot[1]>0)) ){
if(isset($_FILES['lvfyl']['name'])){
    $file = strtotime(date("Y-m-d H:i:s"))."_".$_FILES['lvfyl']['name'];
    if($_FILES['lvfyl']['name']!=""){
        $fileTmpLoc = $_FILES["lvfyl"]["tmp_name"];
        $fileSize = $_FILES["lvfyl"]["size"];
        $file_download_dir = "../../../kronos_file_store/".$file;
        if($fileSize <= 5000000){
            $insertdata=$link->query("INSERT INTO `gy_leave`(`gy_user_id`, `gy_acc_id`, `gy_leave_filed`, `gy_leave_type`, `gy_leave_paid`, `gy_leave_day`, `gy_leave_date_from`, `gy_leave_date_to`, `gy_leave_reason`, `gy_leave_status`, `gy_leave_approver`, `gy_leave_attachment`) Values('$user_id','$myaccount','$datenow','$leave_type','0', '$lvfhd','$hiddt','$hiddt','$lvres','0','0','$file')");
            if($insertdata){ move_uploaded_file($fileTmpLoc, $file_download_dir); echo "success"; }else{ echo "danger"; }
        }else{ echo "sizelimit"; }
    }else{ echo "fileinvalid"; }
}else{ echo "fileinvalid"; }
}else{ echo "invalidaction"; }
}else{ echo "invalidaction"; }
}
$link->close();

function ifreqalready($holdate, $usrid){
   include '../../../config/conn.php';
   $holid = 0;
   $hddate = date("Y-m-d",strtotime($holdate));   
   $dssql=$link->query("SELECT * FROM `gy_leave` WHERE `gy_leave_date_from`='$hddate' AND `gy_user_id`='$usrid' ORDER BY `gy_leave_id` desc");
   $holid=$dssql->num_rows;
    $link->close();
    return $holid;
}

function getplotslot($holdate, $acc){
    include '../../../config/conn.php';
    $holid = array(0, 0);
    $hddate = date("Y-m-d",strtotime($holdate));
    $wtdsql=$link->query("SELECT * From `gy_leave_available` Where `gy_leave_avail_date`<='$hddate' AND `gy_leave_avail_dateto`>='$hddate' AND `gy_acc_id`='$acc' ORDER BY `gy_leave_avail_date` asc LIMIT 1");
    if($wtdsql->num_rows>0){
        $wtdrow=$wtdsql->fetch_array();
        $holid[0]=$wtdsql->num_rows;
        $holid[1]=$wtdrow['gy_leave_avail_plotted']-$wtdrow['gy_leave_avail_approved'];
    }
    $link->close();
    return $holid;
}
?>