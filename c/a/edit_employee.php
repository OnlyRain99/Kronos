<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = words(@$_GET['cd']);

    if ($redirect == "") {
        header("location: 404");
    }

    $info=$link->query("SELECT * From `gy_employee` Where `gy_emp_id`='$redirect'");
    $inforow=$info->fetch_array();
    $accsql=$link->query("SELECT * From `gy_accounts` Where `gy_acc_id`='".$inforow['gy_acc_id']."'");
    $accrow=$accsql->fetch_array();

    $title = "Edit Employee - ".$inforow['gy_emp_fullname'];

	$i1=0; $svsarr = array(array());
	$slvs=$link->query("SELECT `gy_emp_id`,`gy_emp_fullname` From `gy_employee` Where `gy_emp_supervisor`=".getuserid($inforow['gy_emp_code']));
	while ($svrow=$slvs->fetch_array()){
		$svsarr[$i1][0]=$svrow['gy_emp_id'];
		$svsarr[$i1][1]=$svrow['gy_emp_fullname'];
		$i1++;
	}

    $notify = @$_GET['note'];

    if ($notify == "update") {
        $note = "Information Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "userupdatefail") {
        $note = "User Account Update Fail";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "exist") {
        $note = "SiBS ID already exist";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "email_exist") {
        $note = "Email already exist";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "error") {
        $note = "Something Error!";
        $notec = "danger";
        $notes = "";
        $noteid = "activate-alert";
    }else{
        $note = "";
        $notec = "";
        $notes = "display: none;";
        $noteid = "";
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php  
    include 'head.php';
?>

<body class="">
    <div class="page-wrapper">
        
        <?php include 'header-m.php'; ?>

        <?php include 'sidebar.php'; ?>

        <!-- PAGE CONTAINER-->
        <div class="page-container">

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><i class="fa fa-edit"></i> <?php echo $title; ?></h2>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <form method="POST" action="pds">
									<?php if($i1>0){ ?>
									<div class="input-group col-md-3" style="position: absolute; left:12%;">
									<span class="input-group-text"><i class="fa-solid fa-users-viewfinder"></i></span>
									<select id="myslaves" class="form-control" onchange="searchnw(this)">
										<option></option>
									<?php for($i2=0;$i2<$i1;$i2++){ ?>
										<option value="<?php echo $svsarr[$i2][0]; ?>"><?php echo $svsarr[$i2][1]; ?></option>
									<?php } ?>
									</select>
									</div>
									<?php } ?>
                                    <strong class="card-title mb-3"><a href="stats"><button type="button" class="btn btn-primary" title="go back to Employee Records"><i class="fa fa-chevron-circle-left"></i></button></a>
                                    <input type="hidden" name="emphidcode" value="<?php echo $inforow['gy_emp_code']; ?>">
                                    <button class="btn btn-primary" title="Go to Personal Data Sheet">PDS</button>
                                    <span class="pull-right">Employee Information</span></strong>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="update_emp?cd=<?= $redirect; ?>" onsubmit="return validateForm(this);">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>SiBS ID</label>
                                                <input type="text" class="form-control" name="idcode" id="idcode" maxlength="5" placeholder="Ex. 0000" value="<?= $inforow['gy_emp_code']; ?>" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email" maxlength="255" placeholder="sample@gmail.com" value="<?= $inforow['gy_emp_email']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><span style="color: red;">Hire Date</span></label>
                                                <input type="date" name="hire_date" class="form-control" value="<?= $inforow['gy_emp_hiredate']; ?>" >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Status</label><br>
                                                <?php $empstatus = empstatus($inforow['gy_emp_code']); ?>
                                                <btn onclick="updstbtn(this)" id="stbtn" class="btn btn-<?php if($empstatus==0){ echo "success"; }else if($empstatus==1){ echo "danger"; } ?> btn-block"><?php if($empstatus==0){ echo "Active"; }else if($empstatus==1){ echo "Inactive"; } ?></btn>
                                                <input type="hidden" id="hidstatus" name="hidstatus" value="<?php echo $empstatus; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>FirstName</label>
                                                <input type="text" class="form-control" name="fname" maxlength="255" value="<?= $inforow['gy_emp_fname']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>LastName</label>
                                                <input type="text" class="form-control" name="lname" maxlength="255" value="<?= $inforow['gy_emp_lname']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>MiddleName</label>
                                                <input type="text" class="form-control" name="mname" maxlength="255" value="<?= $inforow['gy_emp_mname']; ?>" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Department</label>
                                                <select name="department" id="emp_department" class="form-control" onchange="slctaccnt(this)">
                                                    <option></option>
                                                    <?php  
                                                        $getdprt=$link->query("SELECT * From `gy_department` Order By `name_department` ASC");
                                                        while ($dptrow=$getdprt->fetch_array()){
                                                    ?>
                                                    <option value="<?= $dptrow['id_department']; ?>" <?php if($accrow['gy_dept_id']==$dptrow['id_department']){echo "selected"; } ?>><?= $dptrow['name_department']; ?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Account</label>
                                                <select name="account" id="emp_account" class="form-control" required>
                                                <?php $getaccounts=$link->query("SELECT * From `gy_accounts` Where `gy_dept_id`='".$accrow['gy_dept_id']."' AND `gy_acc_status`=0 Order By `gy_acc_name` ASC");
                                                    while ($accrow=$getaccounts->fetch_array()) { ?>
                                                    <option value="<?php echo $accrow['gy_acc_id']; ?>" <?php if($accrow['gy_acc_id']==$inforow['gy_acc_id']){ echo "selected"; } ?>><?php echo $accrow['gy_acc_name']; ?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
										<div class="col-md-2">
											<div class="form-group">
												<label><span>Work From</span></label>
													<select name="workfrom_oh" class="form-control" required>
														<option value="0">Office</option>
														<option value="1" <?php if($inforow['gy_work_from']==1){ echo "selected"; } ?>>Home</option>
													</select>
											</div>
										</div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><span style="color: red;">Rate</span></label>
                                                <select name="rate_type" class="form-control" required>
                                                    <option value="<?= $inforow['gy_emp_rate']; ?>"><?= get_rate_type($inforow['gy_emp_rate']) ?></option>
                                                    <option value="0">Daily Rate</option>
                                                    <option value="1">Monthly Rate</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Level</label>
                                                <select name="type" id="emp_type" class="form-control" required>
                                                    <option value="<?= $inforow['gy_emp_type']; ?>"><?= getusertype($inforow['gy_emp_type']); ?></option>
                                                    <?php $contrlrval = 18; for($i=1;$i<=$contrlrval;$i++){ ?>
                                                    <option value="<?php echo $i; ?>"><?php echo "L".$i; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="hide">
                                            <div class="form-group">
                                                <label>Position Type</label>
                                                <select name="function_type" id="emp_function_type" class="form-control" required>
                                                    <option value="<?= get_user_function($inforow['gy_emp_code']); ?>"><?= get_user_function_type(get_user_function($inforow['gy_emp_code'])); ?></option>
                                                    <option value="0">default</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Controller</label>
                                                <select id="cntrllr" onchange="updtmysup(this)" class="form-control" required>
                                                    <?php for($i=$contrlrval;$i>=1;$i--){ ?>
                                                    <option value="<?php echo $i; ?>"><?php echo "L".$i; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Controller Name</label>
                                                <?php
                                                $getsuplvl=$link->query("SELECT `gy_user_type` From `gy_user` Where `gy_user_status`=0 AND `gy_user_id`!='1' AND `gy_user_id`=".$inforow['gy_emp_supervisor']." LIMIT 1"); 
                                                while ($suplvlrow=$getsuplvl->fetch_array()) { $contrlrval = $suplvlrow['gy_user_type']; }
                                                ?>
                                                <input type="hidden" id="hidgyusrtyp" value="<?php echo $contrlrval; ?>">
                                                <select id="cntrlnm" name="mysup" class="form-control">
                                                    <option value="<?php echo $inforow['gy_emp_supervisor']; ?>"><?= getuserfullname($inforow['gy_emp_supervisor']); ?></option>
                                                <?php  
                                                    //get supervisors
                                                    $getsup=$link->query("SELECT `gy_user_id`,`gy_full_name`,`gy_user_type` From `gy_user` Where `gy_user_status`=0 AND `gy_user_id`!='1' AND `gy_user_type`=".$contrlrval);
                                                    while ($suprow=$getsup->fetch_array()) {
                                                        if ($suprow['gy_user_type'] == 0) {
                                                            $optioncolor = "red";
                                                        }else if ($suprow['gy_user_type'] == 2) {
                                                            $optioncolor = "green";
                                                        }else if ($suprow['gy_user_type'] == 3) {
                                                            $optioncolor = "blue";
                                                        }else if ($suprow['gy_user_type'] == 4) {
                                                            $optioncolor = "#217777";
                                                        }else if ($suprow['gy_user_type'] == 5) {
                                                            $optioncolor = "#000";
                                                        }else{
                                                            $optioncolor = "#495057";
                                                        }
                                                ?>
                                                    <option style="color: <?php echo $optioncolor; ?>;" value="<?php echo $suprow['gy_user_id']; ?>"><?php echo $suprow['gy_full_name']; ?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <p style="text-align: center; font-style: italic; color: red; margin-bottom: 10px;">- Employee's user account will be sent on his/her registered email -</p>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" name="add" id="submit" class="btn btn-primary btn-block">Update Information</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include 'footer.php'; ?>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>
    <?php $link->close(); ?>
    <?php include 'scripts.php'; ?>

    <script type="text/javascript">
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });

	function searchnw(elem){
		var cd=document.getElementById(elem.id).value;
		if(cd!=""){ window.open("edit_employee?cd="+cd, "_blank"); }
	}

    function slctaccnt(dprtid){
        if(dprtid!=""){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("emp_account").innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "search_account.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("dprtid="+dprtid.value);
        }else{ document.getElementById("emp_account").innerHTML=""; }
    }

    function autosel(){
        var hidval = 18;
        for(var i=hidval;i>=1;i--){
            if(document.getElementById("hidgyusrtyp").value == i){
                document.getElementById("cntrllr").selectedIndex = hidval-i;
                break;
            }
        }
    }
    autosel();
    
        function updtmysup(cntrlval){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("cntrlnm").innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "search_users.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("cntrlval="+cntrlval.value);
        }

        function validateForm(formObj) {
            formObj.submit.disabled = true; 
            return true;  
        }
        
        $('#emp_type').change(function() {
            if ($('#emp_type').val() == 1 || $('#emp_type').val() == 4 || $('#emp_type').val() == 5) {
                $('#emp_function_type').val('0');

                $('#emp_function_type option[value="1"]').prop('disabled', true);
                $('#emp_function_type option[value="2"]').prop('disabled', true);
                $('#emp_function_type option[value="3"]').prop('disabled', true);
            }else{
                $('#emp_function_type option[value="1"]').prop('disabled', false);
                $('#emp_function_type option[value="2"]').prop('disabled', false);
                $('#emp_function_type option[value="3"]').prop('disabled', false);
            }
            
        });

        var timer;
        $(document).ready(function(){
            $("#idcode").keyup(function(){
                clearTimeout(timer);
                var ms = 200; // milliseconds
                $.get("live_search", {idcode: $(this).val()}, function(data){
                    timer = setTimeout(function() {

                        if (data == "no") {
                            $("#duplicate_alert").empty();
                            $("#duplicate_alert").css('color', 'red');
                            $("#duplicate_alert").html("<i class='fa fa-times'></i> id is already taken");
                            $("#submit").prop("disabled", true);
                        }else{
                            $("#duplicate_alert").empty();
                            $("#duplicate_alert").css('color', 'green');
                            $("#duplicate_alert").html("<i class='fa fa-check'></i> you can use this id");
                            $("#submit").prop("disabled", false);
                        }
                        
                    }, ms);
                });
            });
        });

        function updstbtn(elem){
            var hidval = document.getElementById("hidstatus").value;
            if(hidval==0){ elem.classList.remove("btn-success"); elem.classList.add("btn-danger"); elem.innerHTML="Inactive"; document.getElementById("hidstatus").value = 1; }
            else if(hidval==1){ elem.classList.add("btn-success"); elem.classList.remove("btn-danger"); elem.innerHTML="Active"; document.getElementById("hidstatus").value = 0; }
        }
    </script>

</body>

</html>
<!-- end document-->
