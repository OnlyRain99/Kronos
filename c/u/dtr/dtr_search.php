<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';
?>
    <div class="table-responsive">
       <table class="table table-hover" style="font-family: 'Calibri'; font-size: 14px;">
            <thead>
                <tr class="table-dark">
                   <th scope="col" style="padding:2px;" class="text-center">Status</th>
                   <th scope="col" style="padding:2px;" class="text-center">SiBS ID</th>
                   <th scope="col" style="padding:2px;" class="text-center">Employee Name</th>
                   <th scope="col" style="padding:2px;" class="text-center">Level</th>
                   <th scope="col" style="padding:0px;" class="text-center">Schedule Type</th>
                   <th scope="col" style="padding:0px;" class="text-center">Rate</th>
                   <th scope="col" style="padding:2px;" class="text-center">Publish</th>
                   <th scope="col" style="padding:0px;" class="text-center"><i class="fa-solid fa-eye"></i></th>
                </tr>
            </thead>
        	<tbody>
<?php
$seachword = addslashes($_REQUEST['sval']);
$status = addslashes($_REQUEST['status']);
$selby = addslashes($_REQUEST['selby']);
$year = addslashes($_REQUEST['year']);
$month = addslashes($_REQUEST['month']);
$cutoff = addslashes($_REQUEST['cutoff']);
$sqlsts = "";
$sqlby = "";

	if($seachword=="all"){ $seachword=""; }
	$likename = "(`gy_user`.`gy_full_name` LIKE '%$seachword%' OR `gy_employee`.`gy_emp_code`='$seachword')";
	
	if($selby==1){ $sqlby = " AND `gy_user`.`gy_user_type`=".addslashes($_REQUEST['selop']); }
	else if($selby==2){ $sqlby = " AND `gy_employee`.`gy_acc_id`=".addslashes($_REQUEST['selop']); }
	else if($selby==3){ $sqlby = " `gy_employee`.`gy_acc_id`=".$seachword; $likename=""; }

	if($status==0){ $sqlsts = " AND `gy_user`.`gy_user_status`=".$status; }
	else if($status==1){ $sqlsts = " AND `gy_user`.`gy_user_status`=".$status; }

	$empsql=$link->query("SELECT * From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where ".$likename." ".$sqlby." ".$sqlsts." ORDER BY length(`gy_employee`.`gy_emp_code`) asc, `gy_employee`.`gy_emp_code` asc");
	while($emprow=$empsql->fetch_array()){
        $dmrate=$emprow['gy_emp_rate'];
        $suplv = 0;
        if($emprow['gy_emp_code']==294 || $emprow['gy_emp_code']==800){ $suplv=10; }
		if((($emprow['gy_user_type']>5&&$emprow['gy_user_type']<14)&&($emprow['gy_user_type']!=10))||($emprow['gy_user_type']==3&&$suplv!=10)){$cmpute=1;}
		else if($emprow['gy_user_type']==10||$emprow['gy_user_type']>14){ $cmpute=2; }
		else{ $cmpute=0; }
		$publish=0;
		$dtrsql=$link->query("SELECT * FROM `dtr_publish` WHERE `dtr_year`=$year AND `dtr_month`=$month AND `dtr_cutoff`=$cutoff AND `gy_emp_code`='".$emprow['gy_user_code']."' ");
		if($dtrsql->num_rows>0){ $publish=1; $dtrrow=$dtrsql->fetch_array(); $dmrate=$dtrrow['dtr_mdrate']; $cmpute=$dtrrow['dtr_cmpute']; }
		cmpltbl($emprow['gy_user_code'], $emprow['gy_full_name'], $emprow['gy_user_type'], $emprow['gy_user_status'], $publish, $dmrate, $cmpute);
	}

$link->close(); ?>

<?php function cmpltbl($sibsid, $fullname, $level, $status, $publish, $dmrate, $cmpute){
if($status==0){ $status="Active"; }else if($status==1){ $status="Deactivated"; }
$pblshtxt=""; if($publish==0){ $pblshtxt="Pending DTR <i class='fa-solid fa-chalkboard-user'></i>"; }else if($publish==1){ $pblshtxt="DTR Published <i class='fa-solid fa-calendar-check'></i>"; }?>
<tr>
	<td style="padding:5px;" class="text-center"><?php echo $status; ?></td>
	<td style="padding:5px;" class="text-center"><?php echo $sibsid; ?></td>
	<td style="padding:5px;" class="text-center text-nowrap"><?php echo $fullname; ?></td>
	<td style="padding:5px;" class="text-center"><?php echo $level; ?></td>
	<td style="padding:0px;" class="text-center">
		<select class="form-select form-select-sm" id="dtrcmputetyp_<?php echo $sibsid; ?>">
			<option value="0" <?php if($cmpute==0){echo"selected";}?> >Standard</option>
			<option value="1" <?php if($cmpute==1){echo"selected";}?> >Semi-flexi</option>
			<option value="2" <?php if($cmpute==2){echo"selected";}?> >Full flexi</option>
		</select>
	</td>
	<td style="padding:0px;" class="text-center">
		<select class="form-select form-select-sm" id="dmrateid_<?php echo $sibsid; ?>">
			<option value="0" <?php if($dmrate==0){echo"selected";} ?> >Daily</option>
			<option value="1" <?php if($dmrate==1){echo"selected";} ?> >Monthly</option>			
		</select>
	</td>
	<td style="padding:5px;" class="text-center text-nowrap text-<?php if($publish==0){echo"secondary";}else if($publish==1){echo"success";}?>"><?php echo $pblshtxt; ?></td>
	<td style="padding:0px;" class="text-center"><btn class="btn btn-secondary btn-sm btn-block" <?php echo "onclick='viewdtr(\"".$sibsid."\")'"; ?>>View</td>
</tr>
<?php } ?>
			</tbody>
    	</table>
    </div>