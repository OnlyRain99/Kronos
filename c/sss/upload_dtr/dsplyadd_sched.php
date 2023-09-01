<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

	$idnm = addslashes($_REQUEST['idnm']);
	$selempid = ""; $flname = "";
	$idsql=$link->query("SELECT `gy_emp_id`,`gy_emp_fullname` FROM `gy_employee` WHERE `gy_emp_code`='$idnm' LIMIT 1");
	while($idrow=$idsql->fetch_array()){
		$selempid = $idrow['gy_emp_id'];
		$flname = ucwords(strtolower($idrow['gy_emp_fullname']));
	}
	
	$link->close();
?>
<div class="card-header"><?php echo $flname; ?></div>
<div class="card-body">
<div class="input-group">
	<div class="form-floating">
		<input type="date" class="form-control is-invalid" name="from" id="from" title="from" onchange="daterange(); chckifempty(this);" min="<?= date('Y-m-d', strtotime('+7 days')); ?>" required>
		<label for="from">Date From</label>
	</div>
	<div class="form-floating">
		<input type="date" class="form-control is-invalid" name="to" id="to" title="to" onchange="daterange(); chckifempty(this);" min="<?= date('Y-m-d', strtotime('+7 days')); ?>" required>
		<label for="to">Date To</label>
	</div>
	<div class="form-floating">
		<select class="form-select" id="seladdscdtyp" >
			<option value="1" >Working Day</option>
			<option value="0" >Rest Day</option>
			<option value="2" >Restday Duty</option>
		</select>
		<label for="seladdscdtyp">Scheduled Type</label>
	</div>
	<div class="form-floating">
		<input type="time" class="form-control is-invalid" id="seladdscdin" value="" step="any" onchange="chckifempty(this)">
		<label for="seladdscdin">Login</label>
	</div>
	<div class="form-floating">
		<input type="time" class="form-control is-invalid" id="seladdscdout" value="" step="any" onchange="chckifempty(this)">
		<label for="seladdscdout">Logout</label>
	</div>
	<button class="btn btn-success input-group-text faa-parent animated-hover" id="btnaplyscd" onclick="cnfmscd('<?php echo $idnm?>')" ><strong>APPLY</strong> <i class="fa-solid fa-calendar-plus faa-tada faa-fast"></i></button>
</div>
</div>