<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$pltvw = addslashes($_REQUEST['pltvw']);
$startdate = addslashes($_REQUEST['startdate']);
$cmpltdate = date("Y-m-d H:i:s", strtotime($startdate." "."00:00:00"));
$day7after = strtotime($datenow."+6 days");
$plotslot = array(0,0);
$thisdate = date("Y-m-d", strtotime($startdate));

$ctctha=0;
    $empsql=$link->query("SELECT `gy_tagumdate`,`gy_davaodate` From `gy_employee` WHERE `gy_emp_code`='$user_code' ");
	$emprow=$empsql->fetch_array();
	$tagdate = $emprow['gy_tagumdate'];
	$davdate = $emprow['gy_davaodate'];
        if($tagdate!="0000-00-00" && $davdate=="0000-00-00"){
            if(date("Y-m-d", strtotime($tagdate))<=date("Y-m-d")){ $ctctha=0; }
        }else if($tagdate=="0000-00-00" && $davdate!="0000-00-00"){
            if(date("Y-m-d", strtotime($davdate))<=date("Y-m-d")){ $ctctha=1; }
        }else if($tagdate!="0000-00-00" && $davdate!="0000-00-00"){
            if(date("Y-m-d", strtotime($tagdate))<=date("Y-m-d")&&(date("Y-m-d", strtotime($tagdate))>date("Y-m-d", strtotime($davdate))||date("Y-m-d", strtotime($davdate))>date("Y-m-d")) ){ $ctctha=0; }
            else if(date("Y-m-d", strtotime($davdate))<=date("Y-m-d")&&(date("Y-m-d", strtotime($davdate))>date("Y-m-d", strtotime($tagdate))||date("Y-m-d", strtotime($tagdate))>date("Y-m-d")) ){ $ctctha=1; }
        }
$lvstat = getdaystatus($startdate);
$sqlhol = getdayhol($startdate, $ctctha);

$dssql=$link->query("SELECT * FROM `gy_leave` WHERE `gy_leave_date_from`='$thisdate' AND `gy_user_id`='$user_id' ORDER BY `gy_leave_id` desc");
   $ifreqalr=$dssql->num_rows;
    $i3=0; $dssarr = array(array());
    while($dssrow=$dssql->fetch_array()){
        $dssarr[$i3][0]=$dssrow['gy_leave_id'];
        $dssarr[$i3][1]=$dssrow['gy_leave_filed'];
        $dssarr[$i3][2]=$dssrow['gy_leave_type'];
        $dssarr[$i3][3]=$dssrow['gy_leave_reason'];
        $dssarr[$i3][4]=$dssrow['gy_leave_remarks'];
        $dssarr[$i3][5]=$dssrow['gy_leave_status'];
        $dssarr[$i3][6]=$dssrow['gy_leave_attachment'];
        $dssarr[$i3][7]=$dssrow['gy_leave_approver'];
        $dssarr[$i3][8]=$dssrow['gy_leave_date_approved'];
        $dssarr[$i3][9]=$dssrow['gy_acc_id'];
        $dssarr[$i3][10]=$dssrow['gy_leave_day'];
        $dssarr[$i3][11]=$dssrow['gy_publish'];
        $i3++;
    }

$wtdsql=$link->query("SELECT * From `gy_leave_available` Where `gy_leave_avail_date`<='$thisdate' AND `gy_leave_avail_dateto`>='$thisdate' ORDER BY `gy_leave_avail_date` asc");
    $i=0; $wtdarr = array(array());
    while($wtdrow=$wtdsql->fetch_array()){
        $wtdarr[$i][0]=$wtdrow['gy_leave_avail_id'];
        $wtdarr[$i][1]=$wtdrow['gy_leave_avail_date'];
        $wtdarr[$i][2]=$wtdrow['gy_leave_avail_dateto'];
        $wtdarr[$i][3]=$wtdrow['gy_leave_avail_plotted'];
        $wtdarr[$i][4]=$wtdrow['gy_leave_avail_approved'];
        $wtdarr[$i][5]=$wtdrow['gy_user_id'];
        $wtdarr[$i][6]=$wtdrow['gy_leave_avail_justify'];
        $wtdarr[$i][7]=$wtdrow['gy_acc_id'];
        if($myaccount==$wtdarr[$i][7]){ $plotslot[0]++;
            $plotslot[1] = $wtdarr[$i][3]-$wtdarr[$i][4];
        }
        $i++;
    }

$ifdatetrue=false;
$wbksql=$link->query("SELECT `gy_tracker_login` FROM `gy_tracker` WHERE `gy_emp_code`='$user_code' AND `gy_tracker_login`>='$cmpltdate' ORDER BY `gy_tracker_login` ASC LIMIT 1");
    $wbkrow=$wbksql->fetch_array();
if($wbksql->num_rows>0){
    if(strtotime($datenow)<=strtotime($wbkrow['gy_tracker_login']."+48 hours")){ $ifdatetrue=true; }
} if(strtotime($cmpltdate)>=strtotime(date("Y-m-d 00:00:00"))){ $ifdatetrue=true; }
if(strtotime($cmpltdate)>strtotime($datenow)){ $ifdatetrue=false; }

$ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE `gy_leave`.`gy_user_id`!='$user_id' AND `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_employee`.`gy_emp_supervisor`='$user_id'");
$tmlvcnt=$ctlsql->num_rows;

echo "<h3>".date("F j, Y", strtotime($startdate))."</h3>";
if($lvstat==1){
if((($ifdatetrue || ($plotslot[0]>0 && $plotslot[1]>0)) && $ifreqalr==0 && $pltvw==2)&&$sqlhol==1){ ?>

<div class="card">
<ul class="nav nav-tabs mb-1">
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong><i class="fa-solid fa-calendar-plus"></i> File Leave Of Absence</strong></a></li>
    <?php if($tmlvcnt>0){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 4)" href="#"><i class="fa-solid fa-sitemap"></i> Team LOA Request</a></li>
    <?php } if(strtotime($cmpltdate)>=$day7after){ ?>
        <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 0)" href="#"><i class="fa-solid fa-compass-drafting"></i> Plan Slots</a></li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 1)" href="#"><i class="fa-solid fa-list"></i> Plan List</a></li>
</ul>

<div class="form-floating  mb-2">
    <select class="form-select" id="leaveid">
        <?php if(strtotime($cmpltdate)<strtotime($datenow)){ ?>
        <option value="2">Sick Leave</option>
        <option value="9">Emergency Leave</option>
        <?php } if(strtotime($cmpltdate)>strtotime(date("Y-m-d 00:00:00"))){ if(strtotime($cmpltdate)>$day7after){ ?>
        <option value="1">Vacation/Personal Leave</option>
        <?php } ?>
        <option value="3">Maternal Leave</option>
        <option value="4">Paternal Leave</option>
        <option value="5">Solo Parent Leave</option>
        <option value="6">Force Leave</option>
        <option value="7">Indifinite Leave</option>
        <option value="8">Quarantine Leave</option>
        <?php } ?>
    </select>
    <label for="leaveid">Leave Type</label>
</div>
<div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
    <label id="opday-fdid" class="btn btn-secondary active" >
        <input type="radio" name="opday" id="fdid" autocomplete="off" checked> Full Day
    </label>
    <label id="opday-hdid" class="btn btn-secondary" >
        <input type="radio" name="opday" id="hdid" autocomplete="off" > Half Day
    </label>
</div>
<div class="form-floating mb-2">
    <textarea id="leavereason" class="form-control" row="3" placeholder="type your reason here ..." required></textarea>
    <label for="leavereason">Leave Reason</label>
</div>
<div class="form-floating">
    <input type="file" id="leavefile" required class="form-control form-control-sm" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" placeholder="Attachment (required for applying Sick Leave)">
    <label for="leavefile">Format: Doc, Pdf or Image.</label>
</div>
</div>
<button class="btn btn-outline-primary btn-block btn-lg" onclick="filealeave('<?php echo $startdate; ?>')" ><i class="fa-solid fa-stamp"></i> Publish</button>

<?php }else if($ifreqalr>=1 && $pltvw==3){ ?>

<div class="card" style="max-width: 500px;">
<ul class="nav nav-tabs mb-2">
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong><i class="fa-solid fa-receipt"></i> LOA Request</strong></a></li>
    <?php if($tmlvcnt>0){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 4)" href="#"><i class="fa-solid fa-sitemap"></i> Team LOA Request</a></li>
    <?php } if(strtotime($cmpltdate)>=$day7after){ ?>
        <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 0)" href="#"><i class="fa-solid fa-compass-drafting"></i> Plan Slots</a></li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 1)" href="#"><i class="fa-solid fa-list"></i> Plan List</a></li>
</ul>

<div id="ccslide" class="carousel slide" data-ride="carousel">
<div class="carousel-inner">
<?php for($i4=0;$i4<$i3;$i4++){ ?>
    <div class="carousel-item <?php if($i4==0){echo"active";}?>">
        <div class="card" style="margin: 0px;">
            <h4 class="card-header"><?php if($dssarr[$i4][10]==1){echo "Full Day | ";}else if($dssarr[$i4][10]==0.5){echo "Half Day | ";} echo get_leave_type($dssarr[$i4][2]); ?></h4>
            <div class="card-body">
                <div class="row">
                    <div class="col"><button type="button" class="btn btn-<?php if($dssarr[$i4][5]==0){echo"warning";}else if($dssarr[$i4][5]==1){echo"success";}else if($dssarr[$i4][5]==2){echo"danger";}else{echo"secondary";} ?> btn-lg btn-block" disabled><?php if($dssarr[$i4][5]==0){echo'<i class="fa-solid fa-chalkboard-user"></i> Request Pending';}else if($dssarr[$i4][5]==1){echo'<i class="fa-solid fa-calendar-check"></i> LOA Approved';}else if($dssarr[$i4][5]==2){echo'<i class="fa-solid fa-handshake-slash"></i> LOA Request Rejected';}else{echo'<i class="fa-solid fa-circle-xmark"></i> LOA Request Cancelled';} ?></button></div>
                </div>

                <table class="table table-responsive table-striped">
                    <tbody>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">LOA Reason :</th>
                            <td class="text-left"><?php echo $dssarr[$i4][3]; ?></td>
                        </tr>
                        <?php if($dssarr[$i4][5]==1 || $dssarr[$i4][5]==2){ ?>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">Response By :</th>
                            <td class="text-left"><?php echo getuserfullname($dssarr[$i4][7]); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">Date :</th>
                            <td class="text-left"><?php echo date("F j, Y", strtotime($dssarr[$i4][8])); ?></td>
                        </tr>
                        <?php } if($dssarr[$i4][5]==2){ ?>
                        <tr>
                            <th scope="row" class="text-nowrap text-right"><span class="text-danger"><i class="fa-solid fa-handshake-slash"></i></span> Response :</th>
                            <td class="text-left"><?php echo $dssarr[$i4][4]; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col" style="padding-right: 0px;">
                        <a type="button" href="../../kronos_file_store/<?php echo $dssarr[$i4][6]; ?>" target="_blank" class="btn btn-outline-primary btn-block"><i class="fa-solid fa-eye"></i> View File</a>
                    </div>
                    <div class="col" style="padding-left: 0px; <?php if($dssarr[$i4][11]==0 && ($dssarr[$i4][5]!=1 || ($dssarr[$i4][5]==1 && ((date("d")>18 && $thisdate>=date("Y-m-16")) || (date("d")<4 && $thisdate>=date("Y-m-16", strtotime("-1 month"))) || ((date("d")>3&&date("d")<19) && $thisdate>=date("Y-m-01"))))) ) { ?>padding-right: 0px;<?php } ?>">
                        <a type="button" href="leave/dl_attach?fid=<?php echo $dssarr[$i4][0]; ?>" target="_new" class="btn btn-outline-primary btn-block"><i class="fa-solid fa-file-arrow-down"></i> Download File</a>
                    </div>
                    <?php if($dssarr[$i4][11]==0 && ($dssarr[$i4][5]!=1 || ($dssarr[$i4][5]==1 && ((date("d")>18 && $thisdate>=date("Y-m-16")) || (date("d")<4 && $thisdate>=date("Y-m-16", strtotime("-1 month"))) || ((date("d")>3&&date("d")<19) && $thisdate>=date("Y-m-01"))))) ) { ?>
                    <div class="col" style="padding-left: 0px;">
                        <button type="button" class="btn btn-outline-danger btn-block" onclick="confrmcancelloa('<?php echo $startdate; ?>', <?php echo $dssarr[$i4][0]; ?>)"><i class="fa-solid fa-xmark"></i> Cancel Request</button>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="card-footer text-muted">Filed on <?php echo date("F j, Y h:i:s a", strtotime($dssarr[$i4][1])) ?></div>
        </div>
    </div>
<?php } if($i3>1){ ?>
</div>
  <a class="carousel-control-prev" href="#ccslide" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#ccslide" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
<?php } ?>
</div>

</div>
<?php }else if($tmlvcnt>0 && $pltvw==4){ ?>
<div class="card" style="max-width: 750px;">
<ul class="nav nav-tabs mb-1">
    <?php if(($ifreqalr==0 && ($ifdatetrue || ($plotslot[0]>0 && $plotslot[1]>0)))&& $sqlhol==1){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 2)" href="#"><i class="fa-solid fa-calendar-plus"></i> File Leave Of Absence</a></li>
    <?php }else if($ifreqalr>=1){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 3)" href="#"><i class="fa-solid fa-receipt"></i> LOA Request</a></li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong><i class="fa-solid fa-sitemap"></i> Team LOA Request</strong></a></li>
    <?php if(strtotime($cmpltdate)>=$day7after){ ?>
        <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 0)" href="#"><i class="fa-solid fa-compass-drafting"></i> Plan Slots</a></li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 1)" href="#"><i class="fa-solid fa-list"></i> Plan List</a></li>
</ul>

<div id="accordion">
<?php while($ctlrow=$ctlsql->fetch_array()){ $reqname=getuserfullname($ctlrow['gy_user_id']);
    $dptsql=$link->query("SELECT `gy_accounts`.`gy_acc_id`as`accid`,`gy_department`.`name_department`as`dptname`,`gy_accounts`.`gy_acc_name`as`accnm` From `gy_accounts` LEFT JOIN `gy_department` ON `gy_accounts`.`gy_dept_id`=`gy_department`.`id_department` WHERE `gy_accounts`.`gy_acc_id`='".$ctlrow['gy_acc_id']."' ");
    $dptarr = array();
    while($dptrow=$dptsql->fetch_array()){
        $dptarr[0]=$dptrow['accid'];
        $dptarr[1]=$dptrow['dptname'];
        $dptarr[2]=$dptrow['accnm'];
    }
?>
  <div class="card mb-0">
    <div class="card-header" id="heading_<?php echo $ctlrow['gy_leave_id']; ?>">
      <h5 class="mb-0">
        <button class="btn btn-link float-left" data-toggle="collapse" data-target="#collapse_<?php echo $ctlrow['gy_leave_id']; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $ctlrow['gy_leave_id']; ?>" onclick="">
        <?php echo $reqname; ?>
        </button>
        <span class="float-right text-<?php if($ctlrow['gy_leave_status']==0){echo"warning";}else if($ctlrow['gy_leave_status']==1){echo"success";}else if($ctlrow['gy_leave_status']==2){echo"danger";}else{echo"secondary";} ?>"><?php if($ctlrow['gy_leave_status']==0){echo'<i class="fa-solid fa-chalkboard-user"></i> Pending';}else if($ctlrow['gy_leave_status']==1){echo'<i class="fa-solid fa-calendar-check"></i> Approved';}else if($ctlrow['gy_leave_status']==2){echo'<i class="fa-solid fa-handshake-slash"></i> Reject';}else{echo'<i class="fa-solid fa-circle-xmark"></i> Cancelled';} ?></span>
      </h5>
    </div>
    <div id="collapse_<?php echo $ctlrow['gy_leave_id']; ?>" class="collapse" aria-labelledby="heading_<?php echo $ctlrow['gy_leave_id']; ?>" data-parent="#accordion">
      <div  id="acrdbody_<?php echo $ctlrow['gy_leave_id']; ?>" >
        <div class="card border-<?php if($ctlrow['gy_leave_status']==0){echo"warning";}else if($ctlrow['gy_leave_status']==1){echo"success";}else if($ctlrow['gy_leave_status']==2){echo"danger";}else{echo"secondary";} ?>" style="margin: 0px;">
            <h4 class="card-header bg-<?php if($ctlrow['gy_leave_status']==0){echo"warning";}else if($ctlrow['gy_leave_status']==1){echo"success";}else if($ctlrow['gy_leave_status']==2){echo"danger";}else{echo"secondary";} ?>"><?php if($ctlrow['gy_leave_day']==1){echo "Full Day | ";}else if($ctlrow['gy_leave_day']==0.5){echo "Half Day | ";} echo get_leave_type($ctlrow['gy_leave_type']); ?></h4>
            <div class="card-body">
                <div class="row">
                    <div class="col"><button type="button" class="btn btn-<?php if($ctlrow['gy_leave_status']==0){echo"warning";}else if($ctlrow['gy_leave_status']==1){echo"success";}else if($ctlrow['gy_leave_status']==2){echo"danger";}else{echo"secondary";} ?> btn-lg btn-block" disabled><?php if($ctlrow['gy_leave_status']==0){echo'<i class="fa-solid fa-chalkboard-user"></i> Request Pending';}else if($ctlrow['gy_leave_status']==1){echo'<i class="fa-solid fa-calendar-check"></i> LOA Approved';}else if($ctlrow['gy_leave_status']==2){echo'<i class="fa-solid fa-handshake-slash"></i> LOA Request Rejected';}else{echo'<i class="fa-solid fa-circle-xmark"></i> LOA Request Cancelled';} ?></button>
                    </div>
                </div>
                <table class="table table-responsive table-striped">
                    <tbody>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">LOA Reason :</th>
                            <td class="text-left"><?php echo $ctlrow['gy_leave_reason']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">Department :</th>
                            <td class="text-left"><?php echo $dptarr[1]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">Account :</th>
                            <td class="text-left"><?php echo $dptarr[2]; ?></td>
                        </tr>
                        <?php if($ctlrow['gy_leave_status']==1 || $ctlrow['gy_leave_status']==2){ ?>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">Response By :</th>
                            <td class="text-left"><?php echo getuserfullname($ctlrow['gy_leave_approver']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">Date :</th>
                            <td class="text-left"><?php echo date("F j, Y", strtotime($ctlrow['gy_leave_date_approved'])); ?></td>
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <th scope="row" class="text-nowrap text-right">Slot :</th>
                            <td class="text-left"><?php for($i5=0;$i5<$i;$i5++){ if($wtdarr[$i5][7]==$ctlrow['gy_acc_id'] && $wtdarr[$i5][1]<=$ctlrow['gy_leave_date_from'] && $wtdarr[$i5][2]>=$ctlrow['gy_leave_date_from']){ echo $wtdarr[$i5][3]-$wtdarr[$i5][4]; break; } } if($i5>=$i){echo'No';} echo ' Remaining'; ?></td>
                        </tr>
                        <?php } if($ctlrow['gy_leave_status']==2){ ?>
                        <tr>
                            <th scope="row" class="text-nowrap text-right"><span class="text-danger"><i class="fa-solid fa-handshake-slash"></i></span> Response :</th>
                            <td class="text-left"><?php echo $ctlrow['gy_leave_remarks']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col" style="padding-right: 0px;">
                        <a type="button" href="../../kronos_file_store/<?php echo $ctlrow['gy_leave_attachment']; ?>" target="_blank" class="btn btn-outline-primary btn-block"><i class="fa-solid fa-eye"></i> View File</a>
                    </div>
                    <div class="col" style="padding-left: 0px;">
                        <a type="button" href="leave/dl_attach?fid=<?php echo $ctlrow['gy_leave_id']; ?>" target="_new" class="btn btn-outline-primary btn-block"><i class="fa-solid fa-file-arrow-down"></i> Downoad File</a>
                    </div>
                </div>
                <?php if($ctlrow['gy_leave_status']==0){ ?>
                <div class="row">
                    <div class="col">
                        <btn type="button" class='btn btn-outline-danger btn-block' onclick="cnfrmdbysup(<?php echo $ctlrow['gy_leave_id']; ?>, 0, '<?php echo $reqname; ?>')"><i class="fa-solid fa-handshake-slash"></i> Reject Request</btn>
                    </div>
                </div>
                <?php } ?>
            <div class="card-footer text-muted">Filed on <?php echo date("F j, Y h:i:s a", strtotime($ctlrow['gy_leave_filed'])) ?></div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
</div>

</div>
</div>
<?php }else if(strtotime($cmpltdate)>=$day7after && $pltvw==0){ ?>

<div class="card" id="leavecard">
<ul class="nav nav-tabs mb-1">
    <?php if(($ifreqalr==0 && ($ifdatetrue || ($plotslot[0]>0 && $plotslot[1]>0)))&& $sqlhol==1){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 2)" href="#"><i class="fa-solid fa-calendar-plus"></i> File Leave Of Absence</a></li>
    <?php }else if($ifreqalr>=1){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 3)" href="#"><i class="fa-solid fa-receipt"></i> LOA Request</a></li>
    <?php } if($tmlvcnt>0){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 4)" href="#"><i class="fa-solid fa-sitemap"></i> Team LOA Request</a></li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong><i class="fa-solid fa-compass-drafting"></i> Plan Slots</strong></a></li>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 1)" href="#"><i class="fa-solid fa-list"></i> Plan List</a></li>
</ul>

<div class="form-floating mb-2">
    <select class="form-select" id="empdepartment">
        <option value=""></option>
        <?php $dptsql=$link->query("SELECT * From `gy_department` ORDER BY `id_department` ASC");
            while($dptrow=$dptsql->fetch_array()){ ?>
                <optgroup label="<?php echo $dptrow['name_department']; ?>">
                    <?php $depsql=$link->query("SELECT * From `gy_accounts` Where `gy_dept_id`='".$dptrow['id_department']."' AND `gy_acc_status`=0 ORDER BY `gy_acc_name` ASC");
                        while($deprow=$depsql->fetch_array()){ ?>
                            <option value="<?php echo $deprow['gy_acc_id']; ?>"><?php echo $deprow['gy_acc_name']; ?></option>
                    <?php } ?>
                </optgroup>
        <?php } ?>
    </select>
    <label for="empdepartment">Account List</label>
</div>

<div class="input-group mb-2">

<div class="form-floating">
    <input type="date" id="datestart" value="<?php echo $startdate; ?>" class="form-control" disabled>
    <label for="datestart">Date Start</label>
</div>
<div class="form-floating">
    <input type="date" id="dateend" required min="<?php echo $startdate; ?>" value="<?php echo $startdate; ?>" class="form-control">
    <label for="dateend">Date End</label>
</div>

<div class="form-floating">
    <input type="number" id="leaveslot" required min="0" value="0" class="form-control">
    <label for="leaveslot">Leave Slot</label>
</div>

</div>

<div class="form-floating">
    <textarea id="leavejstfy" class="form-control" row="3" placeholder="type your reason here ..." required></textarea>
    <label for="leavejstfy">Justification</label>
</div>

</div>
<button class="btn btn-outline-primary btn-block btn-lg" onclick="plottrigger()"><i class="fa-solid fa-stamp"></i> Publish</button>

<?php }else if(strtotime($cmpltdate)<$day7after || $pltvw==1){ ?>
<div class="card">
<ul class="nav nav-tabs mb-1">
    <?php if(($ifreqalr==0 && ($ifdatetrue || ($plotslot[0]>0 && $plotslot[1]>0)))&& $sqlhol==1){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 2)" href="#"><i class="fa-solid fa-calendar-plus"></i> File Leave Of Absence</a></li>
    <?php }else if($ifreqalr>=1){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 3)" href="#"><i class="fa-solid fa-receipt"></i> LOA Request</a></li>
    <?php } if($tmlvcnt>0){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 4)" href="#"><i class="fa-solid fa-sitemap"></i> Team LOA Request</a></li>
    <?php } if(strtotime($cmpltdate)>=$day7after){ ?>
    <li class="nav-item"><a class="nav-link" onclick="switchlvplt('<?php echo $startdate; ?>', 0)" href="#"><i class="fa-solid fa-compass-drafting"></i> Plan Slots</a></li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong><i class="fa-solid fa-list"></i> Plan List</strong></a></li>
</ul>
<?php
    $dptsql=$link->query("SELECT `gy_accounts`.`gy_acc_id`as`accid`,`gy_department`.`name_department`as`dptname`,`gy_accounts`.`gy_acc_name`as`accnm` From `gy_accounts` LEFT JOIN `gy_department` ON `gy_accounts`.`gy_dept_id`=`gy_department`.`id_department` ");
    $i1=0; $dptarr = array(array());
    while($dptrow=$dptsql->fetch_array()){
        $dptarr[$i1][0]=$dptrow['accid'];
        $dptarr[$i1][1]=$dptrow['dptname'];
        $dptarr[$i1][2]=$dptrow['accnm'];
        $i1++;
    }
?>

<div id="accordion">
<?php for($i2=0;$i2<$i;$i2++){ ?>
  <div class="card mb-0">
    <div class="card-header" id="heading_<?php echo $wtdarr[$i2][0]; ?>">
      <h5 class="mb-0">
        <button class="btn btn-link float-left" data-toggle="collapse" data-target="#collapse_<?php echo $wtdarr[$i2][0]; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $wtdarr[$i2][0]; ?>" onclick="showapprloa(<?php echo $wtdarr[$i2][0]; ?>, '<?php echo $startdate; ?>', 0)">
          <?php for($i3=0;$i3<$i1;$i3++){if($wtdarr[$i2][7]==$dptarr[$i3][0]){echo $dptarr[$i3][1].' <i class="fa-solid fa-arrows-left-right"></i> '.$dptarr[$i3][2]; break;}}
          ?>
        </button>

      </h5>
    </div>

    <div id="collapse_<?php echo $wtdarr[$i2][0]; ?>" class="collapse" aria-labelledby="heading_<?php echo $wtdarr[$i2][0]; ?>" data-parent="#accordion">
      <div id="acrdbody_<?php echo $wtdarr[$i2][0]; ?>" ></div>
    </div>
</div>
<?php } ?>
</div>
</div>
<?php } }else{ echo '<span class="text-danger"><i class="fa-solid fa-calendar-xmark"></i> Not allowed on this day</span>'; ?>
<?php } $link->close();

function getdaystatus($holdate){
    include '../../../config/conn.php';
    $hddate = date("Y-m-d",strtotime($holdate));
    $curyear = date("Y",strtotime($holdate));
    $curmonth = date("m",strtotime($holdate));
    $curday = date("d",strtotime($holdate));
    $dssql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` ON `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` WHERE ((`gy_holiday_calendar`.`gy_a_year`=1 AND `gy_holiday_calendar`.`gy_hol_date`='$hddate')OR(`gy_holiday_calendar`.`gy_a_year`=0 AND Year(`gy_holiday_calendar`.`gy_hol_date`)<='$curyear' AND (Year(`gy_holiday_calendar`.`gy_hol_lastday`)='0000' OR (Year(`gy_holiday_calendar`.`gy_hol_lastday`)!='0000' AND Year(`gy_holiday_calendar`.`gy_hol_lastday`)>='$curyear' ) ) )AND(MONTH(`gy_holiday_calendar`.`gy_hol_date`)='$curmonth'AND DAY(`gy_holiday_calendar`.`gy_hol_date`)='$curday'))AND(`gy_holiday_calendar`.`gy_hol_loc`=2) LIMIT 1");
        $dsrow=$dssql->fetch_array();
        $holid=$dsrow['leaves'];
        if($holid==""){ $holid=1; }
    $link->close();
    return $holid;
}
function getdayhol($holdate, $ctctha){
    include '../../../config/conn.php';
    $hddate = date("Y-m-d",strtotime($holdate));
    $curyear = date("Y",strtotime($holdate));
    $curmonth = date("m",strtotime($holdate));
    $curday = date("d",strtotime($holdate));
    $dssql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` ON `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` WHERE ((`gy_holiday_calendar`.`gy_a_year`=1 AND `gy_holiday_calendar`.`gy_hol_date`='$hddate')OR(`gy_holiday_calendar`.`gy_a_year`=0 AND Year(`gy_holiday_calendar`.`gy_hol_date`)<='$curyear' AND (Year(`gy_holiday_calendar`.`gy_hol_lastday`)='0000' OR (Year(`gy_holiday_calendar`.`gy_hol_lastday`)!='0000' AND Year(`gy_holiday_calendar`.`gy_hol_lastday`)>='$curyear' ) ) )AND(MONTH(`gy_holiday_calendar`.`gy_hol_date`)='$curmonth'AND DAY(`gy_holiday_calendar`.`gy_hol_date`)='$curday'))AND(`gy_holiday_calendar`.`gy_hol_loc`=$ctctha OR `gy_holiday_calendar`.`gy_hol_loc`=2)  LIMIT 1");
        $dsrow=$dssql->fetch_array();
        $holid=$dsrow['leaves'];
        if($holid==""){ $holid=1; }
    $link->close();
    return $holid;
}

function ifreqalready($holdate, $usrid){
   include '../../../config/conn.php';
   $holid = 0;
   $hddate = date("Y-m-d",strtotime($holdate));   
   $dssql=$link->query("SELECT * FROM `gy_leave` WHERE `gy_leave_date_from`='$hddate' AND `gy_user_id`='$usrid' ORDER BY `gy_leave_id` desc");
   $holid=$dssql->num_rows;
    $link->close();
    return $holid;
}

?>
<input type="hidden" id="hiddate" value="<?php echo $startdate; ?>" >