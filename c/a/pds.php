<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

$title = "Personal Data Sheet";
$notify = @$_GET['note'];
    if ($notify == "added") {
        $note = "Employee Added";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else{
        $note = "";
        $notec = "";
        $notes = "display: none;";
        $noteid = "";
    }

    $i = 0; $dptar = array(); $dptarr = array();
    $dptsql = $link->query("SELECT * From `gy_department` ORDER BY `name_department`");
    while ($dptrow=$dptsql->fetch_array()){
    	$dptar[$i] = $dptrow['id_department'];
    	$dptarr[$i] = $dptrow['name_department'];
    	$i++;
    }

    $i = 0; $omar = array(); $omarr = array();
    $omsql = $link->query("SELECT `gy_emp_code`,`gy_emp_fullname` From `gy_employee` where `gy_emp_type`>=3");
    while ($omrow=$omsql->fetch_array()){
    	$omar[$i] = $omrow['gy_emp_code'];
    	$omarr[$i] = $omrow['gy_emp_fullname'];
    	$i++;
    }

    $idcode = ""; $emparr = array();
    if (isset($_POST['emphidcode'])){
		$idcode = words($_POST['emphidcode']);
		$empsql = $link->query("SELECT * From `gy_employee` where `gy_emp_code`='$idcode'");
		while ($emprow=$empsql->fetch_array()){
			$emparr[0] = $emprow['gy_emp_code'];
			$emparr[1] = $emprow['gy_emp_lname'];
			$emparr[2] = $emprow['gy_emp_fname'];
			$emparr[3] = $emprow['gy_emp_mname'];
			$emparr[4] = $emprow['gy_dob'];
			$emparr[5] = $emprow['gy_civilstatus'];
			$emparr[6] = $emprow['gy_home_address'];
			$emparr[7] = $emprow['gy_gov_id'];
			$emparr[8] = $emprow['gy_gov_idnum'];
			$emparr[9] = $emprow['gy_personal_email'];
			$emparr[10] = $emprow['gy_contact_num'];
			$emparr[11] = $emprow['gy_ecperson'];
			$emparr[12] = $emprow['gy_ecnumber'];
			$emparr[13] = $emprow['gy_gender'];
			$emparr[14] = $emprow['gy_emp_hiredate'];
			$emparr[15] = $emprow['gy_acc_id'];
			$emparr[17] = $emprow['gy_emp_id'];
			$emparr[18] = $emprow['gy_emrg_address'];
			$emparr[19] = $emprow['gy_mail_address'];
			$emparr[20] = $emprow['gy_second_address'];
			$emparr[21] = $emprow['gy_assignedloc'];
			$emparr[22] = $emprow['gy_emp_om'];
			$emparr[23] = $emprow['gy_accjoin'];
		    $emparr[24] = $emprow['gy_emp_lastedit'];
    		$emparr[25] = $emprow['gy_lastedit_by'];

    		$emparr[26] = $emprow['gy_nhodate'];
    		$emparr[27] = $emprow['gy_fststartdate'];
    		$emparr[28] = $emprow['gy_fstenddate'];
    		$emparr[29] = $emprow['gy_pststartdate'];
    		$emparr[30] = $emprow['gy_pstenddate'];
    		$emparr[31] = $emprow['gy_certification'];
    		$emparr[32] = $emprow['gy_gradbaystartdate'];
    		$emparr[33] = $emprow['gy_gradbayenddate'];
    		$emparr[34] = $emprow['gy_fullgolivedate'];
    		$emparr[35] = $emprow['gy_promotiondate'];

			$emparr[36] = $emprow['gy_projempdate'];
			$emparr[37] = $emprow['gy_probempdate'];
			$emparr[38] = $emprow['gy_regempdate'];

			$emparr[39] = $emprow['gy_tagumdate'];
			$emparr[40] = $emprow['gy_davaodate'];
			$emparr[41] = $emprow['gy_hybriddate'];

		}
		$depsql = $link->query("SELECT `gy_dept_id` From `gy_accounts` where `gy_acc_id`=".$emparr[15]);
		$deprow=$depsql->fetch_array();
		$emparr[16] = $deprow['gy_dept_id'];

    $usnmsql=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_code`='$emparr[25]'");
    $lsteby="";
    while ($usrrow=$usnmsql->fetch_array()){ $lsteby = $usrrow['gy_full_name']; }
    }else{ header("location: stats"); }

    $i = 0; $accar = array(); $accarr = array();
    $accsql = $link->query("SELECT * From `gy_accounts` where `gy_dept_id`=".$emparr[16]." and `gy_acc_status`=0 ORDER BY `gy_acc_name` ASC");
    while ($accrow=$accsql->fetch_array()){
    	$accar[$i] = $accrow['gy_acc_id'];
    	$accarr[$i] = $accrow['gy_acc_name'];
    	$i++;
    }

$link->close(); ?>

<!DOCTYPE html>
<html lang="en">
<?php  include 'head.php'; ?>
<body>
    <div class="page-wrapper">
        <?php include 'header-m.php'; ?>
        <?php include 'sidebar.php'; ?>
        <div class="page-container">
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><b>[PDS]</b> <?php echo $title; ?></h2>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" action="update_pds" >
                    <div class="row">
                    	<div class="col-lg-3">
                    		<div class="card">
								<div class="card-header"><b>Profile Card</b></div>
								<div class="card-body">
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="flname" name="flname" placeholder=" " value="<?php echo $emparr[1]; ?>" required>
										<label for="flname">Last Name</label>
									</div>
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="ffname" name="ffname" placeholder=" " value="<?php echo $emparr[2]; ?>" required>
										<label for="ffname">First Name</label>
									</div>
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="fmname" name="fmname" placeholder=" " value="<?php echo $emparr[3]; ?>">
										<label for="fmname">Middle Name</label>
									</div>
								</div>
                    		</div>

                    		<div class="card">
								<div class="card-header"><b>Personal Card</b></div>
								<div class="card-body">
									<div class="form-floating mb-1">
										<select class="form-select" id="perg" name="perg">
											<option value="Male" <?php if($emparr[13]=="Male"){echo"selected";} ?>>Male</option>
											<option value="Female" <?php if($emparr[13]=="Female"){echo"selected";} ?>>Female</option>
										</select>
										<label for="perg">Gender</label>
									</div>
									<div class="form-floating mb-1">
										<input type="date" class="form-control" id="perdob" name="perdob" value="<?php echo $emparr[4];?>">
										<label for="perdob">Date of Birth</label>
									</div>
									<div class="form-floating mb-1">
										<select class="form-select" id="percs" name="percs">
											<option value="Single" <?php if($emparr[5]=="Single"){echo"selected";} ?>>Single</option>
											<option value="Married" <?php if($emparr[5]=="Married"){echo"selected";} ?>>Married</option>
											<option value="Widowed" <?php if($emparr[5]=="Widowed"){echo"selected";} ?>>Widowed</option>
											<option value="Separated" <?php if($emparr[5]=="Separated"){echo"selected";} ?>>Separated</option>
										</select>
										<label for="percs">Civil Status</label>
									</div>
								</div>
                    		</div>
                    	</div>

    <div class="col-lg-9">
        <div class="card">
        <div class="card-header"><b>Employee Card</b></div>
            <div class="card-body">
             <div class="row">
                <div class="col-md-2"><div class="form-floating mb-1">
                    <input type="text" class="form-control" id="empsi" name="empsi" placeholder=" " value="<?php echo $emparr[0]; ?>" readonly>
                    <label for="empsi">SiBS ID</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="empdh" name="empdh" placeholder=" " value="<?php echo $emparr[14]; ?>">
                    <label for="empdh">Date Hired</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <select class="form-select" id="empsta" name="empsta" disabled>
                        <option value="" ></option>
                        <option value="0" <?php if($emparr[36]!="0000-00-00" && date("Y-m-d", strtotime($emparr[36]))<=date("Y-m-d")){echo"selected";} ?> >Project Employment</option>
                        <option value="1" <?php if($emparr[37]!="0000-00-00" && date("Y-m-d", strtotime($emparr[37]))<=date("Y-m-d")){echo"selected";} ?> >Probationary Employment</option>
                        <option value="2" <?php if($emparr[38]!="0000-00-00" && date("Y-m-d", strtotime($emparr[38]))<=date("Y-m-d")){echo"selected";} ?> >Regular Employment</option>
                    </select>
                    <label for="empsta">Employment Status</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <select class="form-select" id="asloc" name="asloc" disabled>
                        <option value="0" <?php if($emparr[21]==0){echo"selected";} ?>>Tagum</option>
                        <option value="1" <?php if($emparr[21]==1){echo"selected";} ?>>Davao</option>
                    </select>
                    <label for="asloc">Location</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="proemp" name="proemp" placeholder=" " value="<?php echo $emparr[36]; ?>">
                    <label for="proemp">Project Employment</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="probemp" name="probemp" placeholder=" " value="<?php echo $emparr[37]; ?>">
                    <label for="probemp">Probationary Emp...</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="regemp" name="regemp" placeholder=" " value="<?php echo $emparr[38]; ?>">
                    <label for="regemp">Regular Employment</label>
                </div></div>

                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="tagdate" name="tagdate" placeholder=" " value="<?php echo $emparr[39]; ?>">
                    <label for="tagdate">Tagum</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="davdate" name="davdate" placeholder=" " value="<?php echo $emparr[40]; ?>">
                    <label for="davdate">Davao</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="hybdate" name="hybdate" placeholder=" " value="<?php echo $emparr[41]; ?>">
                    <label for="hybdate">Hybrid</label>
                </div></div>
             </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><b>Training Card</b></div>
            <div class="card-body">
                <div class="row">
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="tcnhod" name="tcnhod" placeholder=" " value="<?php echo $emparr[26]; ?>">
                    <label for="tcnhod">NHO Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="certdt" name="certdt" placeholder=" " value="<?php echo $emparr[31]; ?>">
                    <label for="certdt">Certification Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="fugold" name="fugold" placeholder=" " value="<?php echo $emparr[34]; ?>">
                    <label for="fugold">Full Go Live Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="promd" name="promd" placeholder=" " value="<?php echo $emparr[35]; ?>">
                    <label for="promd">Promotion Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="fstsd" name="fstsd" placeholder=" " value="<?php echo $emparr[27]; ?>">
                    <label for="fstsd">FST Start Date</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="date" class="form-control" id="fsted" name="fsted" placeholder=" " value="<?php echo $emparr[28]; ?>">
                    <label for="fsted">FST End Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="pstsd" name="pstsd" placeholder=" " value="<?php echo $emparr[29]; ?>">
                    <label for="pstsd">PST Start Date</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="date" class="form-control" id="psted" name="psted" placeholder=" " value="<?php echo $emparr[30]; ?>">
                    <label for="psted">PST End Date</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="grbasd" name="grbasd" placeholder=" " value="<?php echo $emparr[32]; ?>">
                    <label for="grbasd">Grad bay Start Date</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="date" class="form-control" id="grbaed" name="grbaed" placeholder=" " value="<?php echo $emparr[33]; ?>">
                    <label for="grbaed">Grad bay End Date</label>
                </div></div>
                </div>
            </div>
        </div>
    </div>

                    	<div class="col-lg-8">
                    		<div class="card">
								<div class="card-header"><b>Contact Card</b></div>
								<div class="card-body">
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctha" name="ctctha" placeholder=" " value="<?php echo $emparr[6]; ?>">
										<label for="ctctha">Home Address</label>
									</div>
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctea" name="ctctea" placeholder=" " value="<?php echo $emparr[18]; ?>">
										<label for="ctctha">Emergency Address</label>
									</div>
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctma" name="ctctma" placeholder=" " value="<?php echo $emparr[19]; ?>">
										<label for="ctctha">Mailing Address</label>
									</div>
									<div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctsa" name="ctctsa" placeholder=" " value="<?php echo $emparr[20]; ?>">
										<label for="ctctha">Secondary Address</label>
									</div>
								<div class="row">
									<div class="col-md-6"><div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctgid" name="ctctgid" placeholder=" " value="<?php echo $emparr[7];?>">
										<label for="ctctgid">Government ID</label>
									</div></div>
									<div class="col-md-6"><div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctgidn" name="ctctgidn" placeholder=" " value="<?php echo $emparr[8];?>">
										<label for="ctctgidn">Government ID #</label>
									</div></div>
								</div>
								<div class="row">
									<div class="col-md-6"><div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctpe" name="ctctpe" placeholder=" " value="<?php echo $emparr[9]; ?>">
										<label for="ctctpe">Personal Email</label>
									</div></div>
									<div class="col-md-6"><div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctcn" name="ctctcn" placeholder=" " value="<?php echo $emparr[10]; ?>">
										<label for="ctctcn">Contact Number</label>
									</div></div>
								</div>
								<div class="row">
									<div class="col-md-6"><div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctecp" name="ctctecp" placeholder=" " value="<?php echo $emparr[11]; ?>">
										<label for="ctctecp">Emergency Contact Person</label>
									</div></div>
									<div class="col-md-6"><div class="form-floating mb-1">
										<input type="text" class="form-control" id="ctctecn" name="ctctecn" placeholder=" " value="<?php echo $emparr[12]; ?>">
										<label for="ctctecn">Emergency Contact Number</label>
									</div></div>
								</div>
								</div>
                    		</div>
                    	</div>
                    	<div class="col-lg-4">
                    		<div class="card">
								<div class="card-header"><b>Project Card</b></div>
								<div class="card-body">
									<div class="form-floating mb-3">
										<input type="date" class="form-control" id="proajd" name="proajd" placeholder="Account Join Date" value="<?php echo $emparr[23]; ?>">
										<label for="proajd">Account Join Date</label>
									</div>
									<div class="form-floating mb-3">
										<select class="form-select" id="prodep" onchange="slctaccnt()">
											<option></option>
										<?php for($i=0;$i<count($dptar);$i++){ ?>
											<option value="<?php echo $dptar[$i]; ?>" <?php if($dptar[$i]==$emparr[16]){echo"selected";} ?>><?php echo $dptarr[$i]; ?></option>
										<?php } ?>
										</select>
										<label for="prodep">Department</label>
									</div>
									<div class="form-floating mb-3">
										<select class="form-select" id="proacc" name="proacc">
										<?php for($i=0;$i<count($accar);$i++){ ?>
											<option value="<?php echo $accar[$i]; ?>" <?php if($accar[$i]==$emparr[15]){echo"selected";} ?>><?php echo $accarr[$i]; ?></option>
										<?php } ?>
										</select>
										<label for="proacc">Account</label>
									</div>
									<div class="form-floating mb-3">
										<select class="form-select" id="promng" name="promng">
										<option value=""></option>
										<?php for($i=0;$i<count($omar);$i++){ ?>
											<option value="<?php echo $omar[$i]; ?>" <?php if($omar[$i]==$emparr[22]){echo"selected";} ?>><?php echo $omarr[$i]; ?></option>
										<?php } ?>
										</select>
										<label for="promng">Manager</label>
									</div>
								</div>
                    		</div>
                    		
                    		<div class="card">
          					<ul class="list-group list-group-flush">
            					<li class="list-group-item">Last Update: <span class="fst-italic"><?php if($emparr[24]!="0000-00-00 00:00:00"){echo date("M j, Y h:i a", strtotime($emparr[24])); } ?></span></li>
            					<li class="list-group-item">Edited By: <span class="fst-italic"><?php echo $lsteby; ?></span></li>
          					</ul>
        					</div>
                    	</div>
                    	<input type="hidden" name="id_gy" value="<?php echo $emparr[17]; ?>">
                    <div class="row">
                    	<div class="col-md-3"><a href="edit_employee?cd=<?php echo $emparr[17]; ?>" class="btn btn-info btn-block">Go to Eployee Information</a></div>
                    	<div class="col-md-3"><a href="add_employee" class="btn btn-success btn-block">Go to Add New Employee</a></div>
                    	<div class="col-md-3"><button class="btn btn-warning btn-block">Print ID</button></div>
                    	<div class="col-md-3"><button class="btn btn-primary btn-block">Save PDS</button></div>
                    </div>
                    </div>
                </form>
                <?php include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" id="hidaccnt" value="<?php echo $emparr[15]; ?>">
<?php include 'scripts.php'; ?>
<script type="text/javascript">
	function slctaccnt(){
        var elemacc=document.getElementById("hidaccnt").value;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("proacc").innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "search_account.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("dprtid="+elemsel.value+"&accid="+elemacc);
	}
</script>
</body>
