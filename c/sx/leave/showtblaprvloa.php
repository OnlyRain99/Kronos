<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$i=0;
$lid = addslashes($_REQUEST['lid']);
$startdate = addslashes($_REQUEST['date']);
$wtdsql=$link->query("SELECT * From `gy_leave_available` Where `gy_leave_avail_id`='$lid'");
if($wtdsql->num_rows>0){
    $wtdrow=$wtdsql->fetch_array();
    $mindateend = $wtdrow['gy_leave_avail_date'];
    $curaccid=$wtdrow['gy_acc_id'];
    $curlvslt=$wtdrow['gy_leave_avail_plotted'];
    $curjstfy=$wtdrow['gy_leave_avail_justify'];
    $curdatto=$wtdrow['gy_leave_avail_dateto'];
    $dssql=$link->query("SELECT `gy_user_id`,`gy_leave_filed`,`gy_leave_type`,`gy_leave_date_from`,`gy_leave_date_approved` FROM `gy_leave` WHERE `gy_leave_date_from`>='".$wtdrow['gy_leave_avail_date']."' AND `gy_leave_date_from`<='".$wtdrow['gy_leave_avail_dateto']."' AND `gy_leave_status`=1 ORDER BY `gy_leave_date_from` asc");

    $aprusr = array(array());
    while($dssrow=$dssql->fetch_array()){
        if(getaccid($dssrow['gy_user_id'])==$wtdrow['gy_acc_id']){
            $aprusr[$i][0]=$dssrow['gy_user_id'];
            $aprusr[$i][1]=$dssrow['gy_leave_type'];
            $aprusr[$i][2]=$dssrow['gy_leave_date_from'];
            $aprusr[$i][3]=$dssrow['gy_leave_filed'];
            $aprusr[$i][4]=$dssrow['gy_leave_date_approved'];
            if(strtotime($dssrow['gy_leave_date_from'])>strtotime($mindateend)){ $mindateend=$dssrow['gy_leave_date_from']; }
            $i++;
        }
    }
$slterr="";
$dplct="";
if(addslashes($_REQUEST['s'])==1){
    $dtto = addslashes($_REQUEST['dtto']);
    $lslot = addslashes($_REQUEST['lslot']);
    $justfy = addslashes($_REQUEST['justfy']);
$cmpltdate = date("Y-m-d H:i:s", strtotime($startdate." "."00:00:00"));
$day7after = strtotime($datenow."+6 days");

$levsql=$link->query("SELECT * From `gy_leave_available` Where `gy_acc_id`='$curaccid' AND ((`gy_leave_avail_date`>='$startdate' AND `gy_leave_avail_dateto`<='$dtto') OR (`gy_leave_avail_date`<='$startdate' AND `gy_leave_avail_dateto`>='$dtto') OR (`gy_leave_avail_date`<='$startdate' AND (`gy_leave_avail_date`>='$dtto'AND`gy_leave_avail_dateto`<='$dtto') OR ((`gy_leave_avail_date`>='$startdate'AND`gy_leave_avail_dateto`<='$startdate')AND`gy_leave_avail_dateto`>='$dtto') ) ) ");
$levcnt=$levsql->num_rows;
$levid=0;
if($levcnt==1){ $levrow=$levsql->fetch_array(); if($levrow['gy_leave_avail_id']==$lid){ $levid=1; } }
if(strtotime($cmpltdate)>=$day7after && strtotime($dtto)>=strtotime($mindateend) && $levcnt==1 && $levid==1){
if($lslot>=$wtdrow['gy_leave_avail_approved']){
    $updtdata=$link->query("UPDATE `gy_leave_available` SET `gy_acc_id`='$curaccid',`gy_leave_avail_plotted`='$lslot',`gy_leave_avail_justify`='$justfy',`gy_leave_avail_dateto`='$dtto' Where `gy_leave_avail_id`='$lid'");
    if($updtdata){
        $curlvslt=$lslot;
        $curjstfy=$justfy;
        $curdatto=$dtto;
    }
}else{ $slterr="bg-danger"; }
}else{ $dplct="bg-danger"; }
}

?>
<div class="form-floating minwid-20">
    <input type="number" id="leaveslot" required min="<?php echo $wtdrow['gy_leave_avail_approved']; ?>" value="<?php echo $curlvslt; ?>" class="form-control <?php echo $slterr; ?>" onchange="showhidbtn(this)" oninput="showhidbtn(this)">
    <label for="leaveslot">Leave Slot</label>
</div>
<div class="form-floating">
    <textarea id="leavejstfy" class="form-control" row="3" placeholder="type your reason here ..." oninput="showhidbtn(this)" required><?php echo $curjstfy; ?></textarea>
    <label for="leavejstfy">Justification</label>
</div>
<div class="input-group">
<div class="form-floating">
    <input type="date" id="datestart" value="<?php echo $wtdrow['gy_leave_avail_date']; ?>" class="form-control" disabled>
    <label for="datestart">Date Start</label>
</div>
<div class="form-floating">
    <input type="date" id="dateend" required min="<?php echo $mindateend; ?>" value="<?php echo $curdatto; ?>" class="form-control <?php echo $dplct; ?>" onchange="showhidbtn(this)">
    <label for="dateend">Date End</label>
</div>

<button class="btn btn-outline-primary" style="display:none;" id="planbtn" onclick="updateplanprop(<?php echo $lid; ?>, '<?php echo $startdate; ?>')"><i class="fa-solid fa-pen-to-square"></i> Update</button>
</div>
<?php if($i>0){ ?>
<table class="table table-responsive table-striped table-bordered table-sm table-hover" style="margin: 0px; font-family: 'Calibri';">
  <thead class="thead-dark">
    <tr>
      <th>Name</th>
      <th>LOA Type</th>
      <th>Requested Date</th>
      <!--<th>Filed Date</th>-->
      <th>Approved Date</th>
    </tr>
  </thead>
<tbody>
<?php for($i1=0;$i1<$i;$i1++){ ?>
<tr>
    <td><?php echo getuserfullname($aprusr[$i1][0]); ?></td>
    <td><?php echo get_leave_type($aprusr[$i1][1]); ?></td>
    <td><?php echo date("F j, Y", strtotime($aprusr[$i1][2])); ?></td>
    <td title="<?php echo date("h:i:s a", strtotime($aprusr[$i1][4])); ?>"><?php echo date("F j, Y", strtotime($aprusr[$i1][4])); ?></td>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
<?php }} if($i<=0){ ?>
<button type="button" class="btn btn-outline-danger btn-sm btn-block" onclick="removeplan('<?php echo $startdate; ?>', <?php echo $lid; ?>)"><i class="fa-solid fa-trash"></i> Delete This Plan</button>
<?php } $link->close();

    function getaccid($usrid){
        include '../../../config/conn.php';
        $acisql=$link->query("SELECT `gy_employee`.`gy_acc_id`as`empacc` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_id`='$usrid'");
        $acirow=$acisql->fetch_array();
        $accid = $acirow['empacc'];
        $link->close();
        return $accid;
    }
?>