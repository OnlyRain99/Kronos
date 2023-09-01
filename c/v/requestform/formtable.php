<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$rtyp = addslashes($_REQUEST['rtyp']);
$esqlrtp = ""; $ssqlrtp = ""; $lyvrtyp = " ";
if($rtyp==7 || $rtyp==6 || $rtyp==5){ $esqlrtp=" and `gy_esc_type`=".$rtyp." "; $ssqlrtp=" and `gy_sched_mode`<0 ";$lyvrtyp=" and `gy_leave_id`='' "; }
else if($rtyp==2){ $ssqlrtp=" and `gy_sched_mode`=".$rtyp." "; $esqlrtp=" and `gy_esc_type`<0 "; }
else if($rtyp==8){ $ssqlrtp=" and `gy_sched_mode`>=0 and `gy_sched_mode`<=1 ";$esqlrtp=" and `gy_esc_type`<0 ";$lyvrtyp=" and `gy_leave_id`='' ";$lyvrtyp=" and `gy_leave_id`=''";}
else if($rtyp=="loa"){ $ssqlrtp=" and `gy_sched_mode`<0 "; $esqlrtp=" and `gy_esc_type`<0 "; $lyvrtyp=" "; }

$rstt = addslashes($_REQUEST['rstt']);
$sqlrstt = ""; $eqlrstt = "";
if($rstt==0){ $sqlrstt="`gy_esc_status`!=2".$esqlrtp; $eqlrstt="`gy_req_status`!=2".$ssqlrtp; $lyvrstt="`gy_leave_status`!=2 "; }
else if($rstt==1){ $sqlrstt="`gy_esc_status`=1".$esqlrtp; $eqlrstt="`gy_req_status`=1".$ssqlrtp; $lyvrstt="`gy_leave_status`=1 "; }
else if($rstt==2){ $sqlrstt="`gy_esc_status`=0".$esqlrtp; $eqlrstt="`gy_req_status`=0".$ssqlrtp; $lyvrstt="`gy_leave_status`=0 "; }

$opt = addslashes($_REQUEST['opt']);
$sqlwhr = "";

 if(date("d")<=5){ $fdotco = date("Y-m-15", strtotime("-1 month")); }else if(date("d")<21){ $fdotco = date("Y-m-01"); }else if(date("d")>15){ $fdotco = date("Y-m-16"); }

if($opt==0){
 $sqlwhr="where `gy_tracker_date`>='".$fdotco."' and ".$sqlrstt." and `gy_publish`=0 order by `gy_esc_date` desc";
 $sqlswhr="where `gy_sched_day`>='".$fdotco."' and ".$eqlrstt." and `gy_publish`=0 order by `gy_req_date` desc";
 $lyvwhr="where `gy_leave_date_from`>='".$fdotco."' and ".$lyvrstt." and `gy_publish`=0 order by `gy_leave_date_from` desc";
}else if($opt==1){
$page = addslashes($_REQUEST['pg']);
$pg = ($page*10)-10;
if($pg>0){ $sqlpg = $pg.", 10"; }else{ $sqlpg="10"; }

 $ecntsql=$link->query("SELECT * From `gy_escalate` where `gy_tracker_date`<'".$fdotco."' and ".$sqlrstt." and `gy_publish`=0");
 $count=$ecntsql->num_rows;

 $scntsql=$link->query("SELECT * From `gy_schedule_escalate` where `gy_sched_day`<'".$fdotco."' and ".$eqlrstt." and `gy_publish`=0");
 if($scntsql->num_rows>$count){ $count = $scntsql->num_rows; }

$lyvtsql=$link->query("SELECT * From `gy_leave` where `gy_leave_date_from`<'".$fdotco."' and ".$lyvrstt." and `gy_publish`=0");
 if($lyvtsql->num_rows>$count){ $count = $lyvtsql->num_rows; }

 $sqlwhr="where `gy_tracker_date`<'".$fdotco."' and ".$sqlrstt." and `gy_publish`=0 order by `gy_esc_date` desc limit ".$sqlpg;
 $sqlswhr="where `gy_sched_day`<'".$fdotco."' and ".$eqlrstt." and `gy_publish`=0 order by `gy_req_date` desc limit ".$sqlpg;
 $lyvwhr="where `gy_leave_date_from`<'".$fdotco."' and ".$lyvrstt." and `gy_publish`=0 order by `gy_leave_date_from` desc limit ".$sqlpg;
}else if($opt==2){
$page = addslashes($_REQUEST['pg']);
$pg = ($page*10)-10;
if($pg>0){ $sqlpg = $pg.", 10"; }else{ $sqlpg="10"; }

 $ecntsql=$link->query("SELECT * From `gy_escalate` where ".$sqlrstt." and `gy_publish`=1");
 $count=$ecntsql->num_rows;

 $scntsql=$link->query("SELECT * From `gy_schedule_escalate` where ".$eqlrstt." and `gy_publish`=1");
 if($scntsql->num_rows>$count){ $count = $scntsql->num_rows; }

 $lyvtsql=$link->query("SELECT * From `gy_leave` where ".$lyvrstt." and `gy_publish`=1");
 if($lyvtsql->num_rows>$count){ $count = $lyvtsql->num_rows; }

 $sqlwhr="where ".$sqlrstt." and `gy_publish`=1 order by `gy_esc_date` desc";
 $sqlswhr="where ".$eqlrstt." and `gy_publish`=1 order by `gy_req_date` desc";
 $lyvwhr="where ".$lyvrstt." and `gy_publish`=1 order by `gy_leave_date_from` desc";
}

$lyv2arr = array(array());
$lyvsql=$link->query("SELECT * From `gy_leave` ".$lyvwhr);
$i3=0;
while ($lyvrow=$lyvsql->fetch_array()){
    $lyv2arr[$i3][0] = $lyvrow['gy_leave_id'];
    $lyv2arr[$i3][1] = $lyvrow['gy_leave_filed'];
    $lyv2arr[$i3][2] = $lyvrow['gy_leave_type'];
    $lyv2arr[$i3][3] = $lyvrow['gy_leave_reason'];
    $lyv2arr[$i3][4] = $lyvrow['gy_user_id'];
    $lyv2arr[$i3][5] = $lyvrow['gy_leave_status'];
    $lyv2arr[$i3][6] = $lyvrow['gy_leave_date_from'];
    $lyv2arr[$i3][7] = $lyvrow['gy_leave_approver'];
    $lyv2arr[$i3][8] = $lyvrow['gy_leave_date_approved'];
    $lyv2arr[$i3][9] = $lyvrow['gy_publish'];
    $lyv2arr[$i3][10] = $lyvrow['msg_usercode'];
    $i3++;
}

$esc1arr = array(); $esc2arr = array(array());
$escsql=$link->query("SELECT * From `gy_escalate` ".$sqlwhr);
$i1=0;
while ($escrow=$escsql->fetch_array()){
    $esc1arr[$i1] = $escrow['gy_esc_id'];
    $esc2arr[$i1][0] = $escrow['gy_esc_date'];
    $esc2arr[$i1][1] = $escrow['gy_tracker_date'];
    $esc2arr[$i1][2] = $escrow['gy_tracker_id'];
    $esc2arr[$i1][3] = $escrow['gy_esc_type'];
    $esc2arr[$i1][4] = $escrow['gy_esc_status'];
    $esc2arr[$i1][5] = $escrow['gy_esc_to'];
    $esc2arr[$i1][6] = $escrow['msg_usercode'];
    $i1++;
}

$esch1arr = array(); $esch2arr = array(array());
$eschsql=$link->query("SELECT * From `gy_schedule_escalate` ".$sqlswhr);
$i2=0;
while ($eschrow=$eschsql->fetch_array()){
    $esch1arr[$i2] = $eschrow['gy_sched_esc_id'];
    $esch2arr[$i2][0] = $eschrow['gy_req_date'];
    $esch2arr[$i2][1] = $eschrow['gy_sched_day'];
    $esch2arr[$i2][2] = $eschrow['gy_emp_fullname'];
    $esch2arr[$i2][3] = $eschrow['gy_sched_mode'];
    $esch2arr[$i2][4] = $eschrow['gy_req_status'];
    $esch2arr[$i2][5] = $eschrow['gy_req_to'];
    $esch2arr[$i2][6] = $eschrow['msg_usercode'];
    $i2++;
}

$link->close();
?>
<div class="table-responsive">
    <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
        <thead>
            <tr style="padding:4px;" class="text-center text-nowrap bg-secondary text-white">
                <th scope="col" >Request Type</th>
                <th scope="col" >Name</th>
                <th scope="col" >Forwarded Date</th>
                <th scope="col" >Requested Date</th>
                <th scope="col" >Status</th>
                <th scope="col" >Approved By</th>
                <th scope="col" title="More Informations"><i class="fa fa-info-circle" aria-hidden="true"></i></th>
                <th scope="col" title="Message the Approver"><i class='fas fa-comment'></i></th>
                <th scope="col">Publish</th>
            </tr>
        </thead>
        <tbody>
<?php
    for($i=0;$i<$i3;$i++){ rqftbl($lyv2arr[$i][0], $lyv2arr[$i][2], $lyv2arr[$i][4], date("F d, Y", strtotime($lyv2arr[$i][1])), date("F d, Y", strtotime($lyv2arr[$i][6])), check_status($lyv2arr[$i][5]), getuserfullname($lyv2arr[$i][7]), date("F d, Y", strtotime($lyv2arr[$i][8])), $lyv2arr[$i][9], $opt, $user_code, $lyv2arr[$i][10]); }

    for($i=0;$i<count($esch1arr);$i++){ rndrtbl(date("F d, Y", strtotime($esch2arr[$i][0])), date("F d, Y", strtotime($esch2arr[$i][1])), $esch2arr[$i][2], $esch2arr[$i][3], check_status($esch2arr[$i][4]), check_approveby($esch2arr[$i][5], $esch2arr[$i][4]), $opt, $esch1arr[$i], $user_code, $esch2arr[$i][6]); }

    for($i=0;$i<count($esc1arr);$i++){ rndrtbl(date("F d, Y", strtotime($esc2arr[$i][0])), date("F d, Y", strtotime($esc2arr[$i][1])), check_trackname($esc2arr[$i][2]), $esc2arr[$i][3], check_status($esc2arr[$i][4]), check_approveby($esc2arr[$i][5], $esc2arr[$i][4]), $opt, $esc1arr[$i], $user_code, $esc2arr[$i][6]); }

function rqftbl($rid, $rtyp, $rusr, $rfld, $rdyt, $rsts, $raby, $radt, $opt, $opt1, $ucode, $msgcd){ ?>
<tr class="text-nowrap text-center">
    <td style="padding:4px;"><?php echo get_leave_type($rtyp); ?></td>
    <td style="padding:4px;"><?php echo getuserfullname($rusr); ?></td>
    <td style="padding:4px;"><?php echo $rfld; ?></td>
    <td style="padding:4px;"><?php echo $rdyt; ?></td>
    <td style="padding:4px;" title="<?php if($raby!=""){echo $radt;} ?>"><?php echo $rsts; ?></td>
    <td style="padding:4px;" title="<?php if($raby!=""){echo $radt;} ?>"><?php echo $raby; ?></td>
    <td style="padding:0px;"><btn class="btn btn-success btn-block btn-sm" title="More Informations" onclick="more_infoloa(<?php echo $rid; ?>)" ><i class="fa fa-info-circle" aria-hidden="true"></i></btn></td>
    <td style="padding:0px;">
        <btn class="btn btn-<?php if($opt==0){echo"warning";}else{echo"secondary";}?> btn-block btn-sm" title="Message the Approver" <?php if($opt==0){ echo "onclick='chat_panel(".$rid.", \"loa\", \"".$ucode."\")'"; } ?> ><i class='far fa-comment'></i>
        <?php if(in_array($ucode, explode(",", $msgcd))!=1 && $msgcd!=""){ ?>
            <span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle"><span class="visually-hidden">New alerts</span></span>
        <?php } ?>
        </btn>
    </td>
    <td style="padding:0px;"><btn class="btn btn-<?php if($opt==0 && $opt1==0){echo"primary";}else{echo"secondary";}?> btn-block btn-sm" <?php if($opt==0 && $opt1==0){ echo "onclick='publishloa(".$rid.")'"; } ?> ><i class="fas fa-cloud-upload-alt"></i></btn></td>
</tr>
<?php } function rndrtbl($fdate, $rdate, $name, $typeid, $status, $approve, $opt, $rfid, $ucode, $msgcd){
    $type = check_esc($typeid); ?>
<tr class="text-nowrap text-center">
    <td style="padding:4px;"><?php echo $type; ?></td>
    <td style="padding:4px;"><?php echo $name; ?></td>
    <td style="padding:4px;"><?php echo $fdate; ?></td>
    <td style="padding:4px;"><?php echo $rdate; ?></td>
    <td style="padding:4px;"><?php echo $status; ?></td>
    <td style="padding:4px;"><?php echo $approve; ?></td>
    <td style="padding:0px;"><btn class="btn btn-success btn-block btn-sm" title="More Informations" onclick="more_info(<?php echo $rfid; ?>, <?php echo $typeid; ?>)"><i class="fa fa-info-circle" aria-hidden="true"></i></btn></td>
    <td style="padding:0px;"><btn class="btn btn-<?php if($opt==0){echo"warning";}else{echo"secondary";}?> btn-block btn-sm" title="Message the Approver" <?php if($opt==0){ echo "onclick='chat_panel(".$rfid.", ".$typeid.", \"".$ucode."\")'"; } ?> ><i class='far fa-comment'></i>
	<?php if(!in_array($ucode, explode(",", $msgcd))==1 && $msgcd!=""){ ?>
  <span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle">
    <span class="visually-hidden">New alerts</span>
  </span>
	<?php } ?>
    </btn></td>
    <td style="padding:0px;"><btn class="btn btn-<?php if($opt==0){echo"primary";}else{echo"secondary";}?> btn-block btn-sm" <?php if($opt==0){ echo "onclick='publish(".$rfid.", ".$typeid.", \"".$ucode."\")'"; } ?> ><i class="fas fa-cloud-upload-alt"></i></btn></td>
</tr>
<?php } ?>
        </tbody>
    </table>
</div>
<nav aria-label="...">
    <ul class="pagination" id="pagelink" style="display: flex; flex-wrap: wrap;">
    <?php if($opt==1 || $opt==2){ for($i=1;$i<=($count/10);$i++){ ?>
        <li class="page-item <?php if($i==$page){echo 'active';} ?>"><a class="page-link" href="#" onclick="switch_page(<?php echo $i; ?>, <?php echo $opt; ?>)" ><?php echo $i; ?></a></li>
    <?php }} ?>
    </ul>
</nav>
<?php

function check_status($val){
    if($val==0){ return "Pending";}
    else if($val==1){ return "Approved";}
    else if($val==2){ return "Rejected";}
    else{ return ""; }
}

function check_approveby($usrid, $status){
    if($status>0){
        return getuserfullname($usrid);
    }else{ return ""; }
}

function check_esc($escid){
    if($escid==7){ return "Escalate Missed Log (ML)"; }
    else if($escid==2){ return "Escalate Rest Day OT (RDOT)"; }
    else if($escid==8 || $escid==0 || $escid==1){ return "Escalate Schedule Adjustment (SA)"; }
    else if($escid==6){ return "Escalate Overtime (OT)"; }
    else if($escid==5){ return "Escalate Early Out (EO)"; }
}

function check_trackname($trackid){
    include '../../../config/conn.php';
    $trksql=$link->query("SELECT `gy_emp_fullname` From `gy_tracker` where `gy_tracker_id`=".$trackid." limit 1");
    $trkrow=$trksql->fetch_array();
    $name = $trkrow['gy_emp_fullname'];
    $link->close();
    return $name;
}
 ?>