<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

	$idnm = addslashes($_REQUEST['idnm']);
	$selempid = ""; $scdarr = array(array()); $i=0; $selname="";
	$idsql=$link->query("SELECT `gy_emp_id`,`gy_emp_fullname` FROM `gy_employee` WHERE `gy_emp_code`='$idnm' LIMIT 1");
	while($idrow=$idsql->fetch_array()){
		$selempid = $idrow['gy_emp_id'];
		$selname = ucwords(strtolower($idrow['gy_emp_fullname']));
		$day7ah = date("Y-m-d", strtotime($onlydate." + 7 days"));
		$scdsql=$link->query("SELECT * FROM `gy_schedule` WHERE `gy_emp_id`=$selempid AND `gy_sched_day`>='$day7ah' ORDER BY `gy_sched_day` asc");
		while($scdrow=$scdsql->fetch_array()){
			$scdarr[$i][0]=$scdrow['gy_sched_id'];
			$scdarr[$i][1]=$scdrow['gy_sched_day'];
			$scdarr[$i][2]=$scdrow['gy_sched_mode'];
			$scdarr[$i][3]=$scdrow['gy_sched_login'];
			$scdarr[$i][4]=$scdrow['gy_sched_logout'];
			$i++;
		}
	}
	
	$link->close();

for($i1=0;$i1<$i;$i1++){ ?>
<tr id="tblscdid_<?php echo $i1; ?>" class="<?php if($scdarr[$i1][2]==1){echo'table-bordered';}else{echo'table-striped bg-light';} ?>">
	<th class="text-center text-nowrap"><?php echo $selname; ?><input type="hidden" id="hidscdid_<?php echo $i1; ?>" value="<?php echo $scdarr[$i1][0]; ?>"></th>
	<td class="text-center text-nowrap" id="dsplysched_<?php echo $i1; ?>"><?php echo date("F d, Y D", strtotime($scdarr[$i1][1])); ?></td>
	<td class="text-center " style="padding: 0px;">
		<select class="form-select" id="selscdtyp_<?php echo $i1; ?>" onchange="changecolor(this, <?php echo $i1; ?>)">
			<option value="1" <?php if($scdarr[$i1][2]==1){echo"selected";} ?> >Working Day</option>
			<option value="0" <?php if($scdarr[$i1][2]==0){echo"selected";} ?> >Rest Day</option>
			<option value="2" <?php if($scdarr[$i1][2]==2){echo"selected";} ?> >Restday Duty</option>
		</select>
		<input type="hidden" id="hidselscdtyp_<?php echo $i1; ?>" value="<?php echo $scdarr[$i1][2]; ?>">
	</td>
	<td class="text-center " style="padding: 0px;">
		<input type="time" class="form-control" id="selscdin_<?php echo $i1; ?>" onchange="changecolor(this, <?php echo $i1; ?>)" value="<?php echo $scdarr[$i1][3]; ?>" step="any">
		<input type="hidden" id="hidselscdin_<?php echo $i1; ?>" value="<?php echo $scdarr[$i1][3]; ?>">
	</td>
	<td class="text-center " style="padding: 0px;">
		<input type="time" class="form-control" id="selscdout_<?php echo $i1; ?>" onchange="changecolor(this, <?php echo $i1; ?>)" value="<?php echo $scdarr[$i1][4]; ?>" step="any">
		<input type="hidden" id="hidselscdout_<?php echo $i1; ?>" value="<?php echo $scdarr[$i1][4]; ?>">
	</td>
	<td style="padding: 0px;"><button class="btn btn-block faa-parent animated-hover" title="Update Schedule" id="updschd_<?php echo $i1; ?>" onclick="updtscdnw(<?php echo $i1; ?>)" ><i class="fa-solid fa-shuffle fa-flip-vertical faa-horizontal faa-reverse faa-slow"></i></button></td>
	<td style="padding: 0px;"><button class="btn btn-danger btn-block faa-parent animated-hover" title="Remove Schedule" id="updschd_<?php echo $i1; ?>" onclick="remscdnw(<?php echo $i1; ?>)"><i class="fa-sharp fa-solid fa-delete-left faa-horizontal faa-slow"></i></button></td>
</tr>
<?php } ?>