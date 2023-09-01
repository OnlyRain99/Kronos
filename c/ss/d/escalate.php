<?php  
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "Escalation";

    $suplv = 0;
    //if($user_code==294 || $user_code==800){ $suplv=10; }
    $redirect = @$_GET['cd'];
    $misslog = date("m/d/Y" ,strtotime(@$_GET['ml']));
    $empid = @$_GET['empid'];
    $bname = @$_GET['bname'];

    $cudate = array("", "", "", "", "", 0);

    $info=$link->query("SELECT * From `gy_tracker` Where `gy_tracker_id`='$redirect' AND `gy_tracker_request`=''");
    $trackrow=$info->fetch_array();

if($redirect != ""){
	$infocnt = mysqli_num_rows($info);
	$gyempcode = $trackrow['gy_emp_code'];
    $ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$gyempcode' LIMIT 1");
	$ifcornr = mysqli_num_rows($ifcor);
	if($ifcornr == 0){
	$ifcor=$link->query("SELECT `gy_emp_supervisor` From `gy_employee` Where `gy_emp_code`='$gyempcode' LIMIT 1");		
	$ifcrow=$ifcor->fetch_array();
	$gyempsup = get_emp_code($ifcrow['gy_emp_supervisor']);
	if(mysqli_num_rows($ifcor) > 0 && $ifcrow['gy_emp_supervisor']!= 0){
	$ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where (`gy_emp_supervisor`='$user_id' OR `gy_emp_type`=3) AND `gy_emp_code`='$gyempsup' LIMIT 1");	
	$ifcornr = mysqli_num_rows($ifcor);
	}
	}
	if($ifcornr == 0 && $infocnt == 0){ ?> <script> window.close(); </script> <?php }
    $notify = @$_GET['note'];
    $cudate = matchsched($trackrow['gy_emp_code'], $trackrow['gy_tracker_date'], $trackrow['gy_tracker_breakout'], $trackrow['gy_tracker_breakin'], $trackrow['gy_tracker_logout']);
}else{ $notify = "good";
    if($empid!=""){
        $cudate = matchsched($empid, $misslog, "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00");
    }
}

    if ($notify == "") {
        if ($trackrow['gy_tracker_request'] == "escalate") {
            $notify = "escalated";
        }
    }

    if ($notify == "success") {
        $note = "Escalation Request has been Submitted";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
        $btn_status = "disabled";
        $select_status = "disabled";
    }else if ($notify == "escalated") {
        $note = "Duplicate entry is not allowed";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
        $btn_status = "disabled";
        $select_status = "disabled";
    }else if ($notify == "sizelimit") {
        $note = "File size exceeded the limit of 5MB";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
        $btn_status = "";
        $select_status = "";
    }else if ($notify == "error") {
        $note = @$_GET['daerr'];
        $notec = "danger";
        $notes = "";
        $noteid = "activate-alert";
        $btn_status = "";
        $select_status = "";
    }else{
        $note = "";
        $notec = "";
        $notes = "display: none;";
        $noteid = "";
        $btn_status = "";
        $select_status = "";
    }

function ifempty($cudate){
    if($cudate[1] == "" || $cudate[2] == "" || $cudate[3] == "" || $cudate[4] == ""){ return "disabled"; }
}

function matchsched($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
    include '../../../config/conn.php';
    $today = array(date("Y-m-d", strtotime($dblogin)), "", "", "", "", 0);
    $yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
    $tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
    $datenow = date("Y-m-d", strtotime($dblogin));
    $theemp = getempid($dbemp);

	$sqlemp="`gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."'";
    if($dblogo != "0000-00-00 00:00:00"){ $endday = $dblogo; }
    else if($dbbrei != "0000-00-00 00:00:00"){ $endday = $dbbrei; }
    else if($dbbreo != "0000-00-00 00:00:00"){ $endday = $dbbreo; }
    else { $endday = $dblogin; $sqlemp="`gy_sched_day`='".$datenow."'"; }

    $empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_mode` FROM `gy_schedule` WHERE ".$sqlemp." AND `gy_emp_id`='".$theemp."' ORDER BY `gy_sched_day` ASC");
    if(mysqli_num_rows($empsch) > 0){
        while ($scrow=$empsch->fetch_array()) {
            if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))) {
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
            }else{
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
            }
            $schedin = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))));
            if((strtotime($dblogin) < $schedlout && strtotime($endday) >= $schedin && $scrow['gy_sched_mode']!=0) || $sqlemp=="`gy_sched_day`='".$datenow."'"){ $today[0] = date("m/d/Y", strtotime($scrow['gy_sched_day']));
                $today[1] = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".$scrow['gy_sched_login']));
                $today[2] = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".$scrow['gy_sched_logout']));
                $today[3] = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".$scrow['gy_sched_breakout']));
                $today[4] = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".$scrow['gy_sched_breakin']));
                $today[5] = $scrow['gy_sched_mode'];
            break; }
        }
    }
	$link->close();
    return $today;
}

function pyrtymns($time){
    if($time == "0000-00-00 00:00:00" || $time == "") { $time = ""; }
    else{ $time = date('H:i', strtotime($time)); }
    return $time;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>

<style type="text/css">
    .files input {
        outline: 2px dashed #92b0b3;
        outline-offset: -10px;
        -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
        transition: outline-offset .15s ease-in-out, background-color .15s linear;
        padding: 120px 0px 85px 35%;
        text-align: center !important;
        margin: 0;
        width: 100% !important;
    }
    .files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
        -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
        transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
     }
    .files{ position:relative}
    .files:after {  pointer-events: none;
        position: absolute;
        top: 60px;
        left: 0;
        width: 50px;
        right: 0;
        height: 56px;
        content: "";
        background-image: url(../../../images/upload.png);
        display: block;
        margin: 0 auto;
        background-size: 100%;
        background-repeat: no-repeat;
    }
    .color input{ background-color:#f1f1f1;}
    .files:before {
        position: absolute;
        bottom: 10px;
        left: 0;  pointer-events: none;
        width: 100%;
        right: 0;
        height: 57px;
        content: " or drag it here. ";
        display: block;
        margin: 0 auto;
        color: #2ea591;
        font-weight: 600;
        text-transform: capitalize;
        text-align: center;
    }
</style>

<body>
    <div class="page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <br>
                    <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show no-print">
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
                            <strong><?php if($redirect != "" || $bname == 2){ echo $trackrow['gy_emp_code']." - ".$trackrow['gy_emp_fullname']." </strong>time log"; }else if($bname == 2){ echo "Missing </strong>time log"; }else if($bname == 1){ echo "Schedule Adjustment "; } ?> <span class="pull-right">Shift Date: <?php echo $cudate[0]; ?></span>
                        </div>
                        <form method="post" enctype="multipart/form-data" action="escalate_ini?<?php if($redirect!="" || $bname == 2){echo "cd=".$redirect."&empid=".$empid."&ml=".$misslog;}else{echo "empid=".$empid."&ml=".$misslog;} ?>" >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                        <?php if($redirect != "" || $bname == 2){ ?>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label>Select Type</label>
                                        <select name="type" id="type" class="form-control" onchange="esctype(this.value);" <?= $select_status; ?> required>
                                            <option></option>
                                            <option value="7" <?php echo ifempty($cudate); ?>>Escalate My Missed Log (ML)</option> 
                                            <option value="8" >Escalate Schedule Adjustment (SA)</option>
                                            <?php if($suplv==10 || $empid!=$user_code){ ?>
                                            <option value="6" >Escalate My Overtime (OT, RDOT)</option>
                                            <?php } ?>
                                            <option value="5" <?php echo ifempty($cudate); ?>>Escalate Early Out (EO)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label style="font-style: italic;">Status</label>
                                            <select class="form-control" name="status" id="status" onchange="work_off()" required disabled>
                                                <option value="1">WORK</option>
                                                <option value="0">OFF</option>
                                                <?php if($suplv==10 || $empid!=$user_code){ ?>
                                                <option value="2">RD DUTY</option>
                                                <?php } ?>
                                            </select>
                                    </div>
                                </div>
                                <input type="hidden" id="hidlogintime" value="<?php echo pyrtymns($trackrow['gy_tracker_login']); ?>">
                                <input type="hidden" id="hidlogouttime" value="<?php echo pyrtymns($trackrow['gy_tracker_logout']); ?>">
                                <input type="hidden" id="hidbreakouttime" value="<?php echo pyrtymns($trackrow['gy_tracker_breakout']); ?>">
                                <input type="hidden" id="hidbreakintime" value="<?php echo pyrtymns($trackrow['gy_tracker_breakin']); ?>" >
                                <input type="hidden" id="hidschin" value="<?php echo pyrtymns($cudate[1]); ?>">
                                <input type="hidden" id="hidschout" value="<?php echo pyrtymns($cudate[2]); ?>">
                                <input type="hidden" id="hidschbin" value="<?php echo pyrtymns($cudate[3]); ?>">
                                <input type="hidden" id="hidschbout" value="<?php echo pyrtymns($cudate[4]); ?>">
                        <?php }else if($bname == 1){ echo "<input type='hidden' id='type' name='type' value='8'>"; ?>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label style="font-style: italic;">Status</label>
                                            <select class="form-control" name="status" id="status" onchange="work_off()" required>
                                                <option value=""></option>
                                                <option value="1">WORK</option>
                                                <option value="0">OFF</option>
                                                <?php if($suplv==10 || $empid!=$user_code){ ?>
                                                <option value="2">RD DUTY</option>
                                                <?php } ?>
                                            </select>
                                    </div>
                                </div>
                        <?php } ?>

                                        <div class="col-md-12">
                                            <div id="border-lilo" class="card border border-secondary">
                                                <div id="lilo" class="card-header bg-dark">
                                                    <strong id="text-lilo" class="card-title text-light">Login/Logout</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label id="txt-login">Login</label>
                                                            <div class="form-group">
                                                                <input type="time" class="form-control" name="logintime" id="logintime" value="" disabled required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label id="txt-logout">Logout</label>
                                                            <div class="form-group">
                                                                <input type="time" class="form-control" name="logouttime" id="logouttime" value="" step="any" disabled required>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div id="border-break" class="card border border-secondary">
                                                <div id="break" class="card-header bg-dark">
                                                    <strong id="text-break" class="card-title text-light">Break</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label id="txt-brkschout">Break-Out</label>
                                                            <div class="form-group">
                                                                <input type="time" class="form-control" name="breakouttime" id="breakouttime" value="" disabled step="any">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label id="txt-brkschin">Break-In</label>
                                                            <div class="form-group">
                                                                <input type="time" class="form-control" name="breakintime" id="breakintime" value="" disabled step="any">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 10px;"></div>
                                        <div class="col-md-12">
                                            <div class="card border border-primary">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Reason</label>
                                                                <textarea class="form-control" name="reason" id="reason" placeholder="type your reason here ..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="form-group files">
                                                                <label>Attachment (Required)</label>
                                                              <input type="file" name="file" class="form-control-file" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" onchange="readURL(this);" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group text-center">
                                                                <label>Photo Preview</label>
                                                                <img src="#" style="width: 100px; height: 100px;" id="my-image" onerror="this.onerror=null; this.src='../../../images/icon/image.png'" style="decora">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <button type="submit" name="submit" id="submit" onclick="trigonce(this)" class="btn btn-lg btn-primary" title="click to submit ..." <?= $select_status; ?> >Submit Escalation Request <i class="fa fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">
		function trigonce(elem){
			document.getElementById(elem.id).style.visibility = "hidden";
		}

        function work_off(){
            var mode =document.getElementById("status").value;
            document.getElementById("txt-login").innerHTML="Login";
            document.getElementById("txt-logout").innerHTML="Logout";
            if (mode == 1 || mode == 2 || mode == 3) {
                $("#logintime").prop("disabled", false);
                $("#breakouttime").prop("disabled", false);
                $("#breakintime").prop("disabled", false);
                $("#logouttime").prop("disabled", false);
                $("#lilo").attr('class', 'card-header bg-primary');
                $("#text-lilo").attr('class', 'card-title text-light');
                $("#border-lilo").attr('class', 'card border border-primary');
                $("#break").attr('class', 'card-header bg-primary');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-primary');
                $("#ot").attr('class', 'card-header bg-dark');
                $("#text-ot").attr('class', 'card-title text-light');
                $("#border-ot").attr('class', 'card border border-secondary');
            if(document.getElementById("type").value==6 && mode==2){
                document.getElementById("logintime").value = document.getElementById("hidschin").value;
                document.getElementById("logouttime").value = document.getElementById("hidschout").value;
                if(document.getElementById("hidlogintime").value<document.getElementById("hidschin").value){
                document.getElementById("breakouttime").value = document.getElementById("hidlogintime").value;
                }else{ document.getElementById("breakouttime").value = document.getElementById("hidschin").value; }
                if(document.getElementById("hidlogouttime").value>document.getElementById("hidschout").value){
                document.getElementById("breakintime").value = document.getElementById("hidlogouttime").value;
                }else{ document.getElementById("breakintime").value = document.getElementById("hidschout").value; }
                document.getElementById("text-lilo").innerHTML="Schedule In/Out";
                document.getElementById("text-break").innerHTML="Approve OT Duration In/Out";
                document.getElementById("txt-brkschout").innerHTML="Pre Shift OT";
                document.getElementById("txt-brkschin").innerHTML="Post Shift OT";
            }else if(document.getElementById("type").value==6 && mode==3){
                document.getElementById("logintime").value = document.getElementById("hidschin").value;
                document.getElementById("logouttime").value = document.getElementById("hidschout").value;
                if(document.getElementById("hidlogintime").value<document.getElementById("hidschin").value){
                document.getElementById("breakouttime").value = document.getElementById("hidlogintime").value;
                }else{ document.getElementById("breakouttime").value = document.getElementById("hidschin").value; }
                if(document.getElementById("hidlogouttime").value>document.getElementById("hidschout").value){
                document.getElementById("breakintime").value = document.getElementById("hidlogouttime").value;
                }else{ document.getElementById("breakintime").value = document.getElementById("hidschout").value; }
                document.getElementById("text-lilo").innerHTML="Schedule In/Out";
                document.getElementById("text-break").innerHTML="Approve OT Duration In/Out";
                document.getElementById("txt-brkschout").innerHTML="Pre Shift OT";
                document.getElementById("txt-brkschin").innerHTML="Post Shift OT";
                $("#breakouttime").prop("disabled", true);
                $("#breakintime").prop("disabled", true);
                $("#break").attr('class', 'card-header bg-dark');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-secondary');
            }else if(document.getElementById("type").value==6 && mode==1){
                if(document.getElementById("hidlogintime").value<document.getElementById("hidschin").value){
                document.getElementById("logintime").value = document.getElementById("hidlogintime").value;
                }else{ document.getElementById("logintime").value = document.getElementById("hidschin").value; }
                if(document.getElementById("hidlogouttime").value>document.getElementById("hidschout").value){
                document.getElementById("logouttime").value = document.getElementById("hidlogouttime").value;
                }else{ document.getElementById("logouttime").value = document.getElementById("hidschout").value; }
                document.getElementById("breakouttime").value = document.getElementById("hidbreakouttime").value;
                document.getElementById("breakintime").value = document.getElementById("hidbreakintime").value;
                document.getElementById("text-lilo").innerHTML="Overtime Duration";
                document.getElementById("text-break").innerHTML="Break";
                document.getElementById("txt-login").innerHTML="Pre Shift OT";
                document.getElementById("txt-logout").innerHTML="Post Shift OT";
                document.getElementById("txt-brkschout").innerHTML="Break-Out";
                document.getElementById("txt-brkschin").innerHTML="Break-In";
                $("#breakouttime").prop("disabled", true);
                $("#breakintime").prop("disabled", true);
                $("#break").attr('class', 'card-header bg-dark');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-secondary');
            }else if(document.getElementById("type").value==8 && (mode==1 || mode==2)){
                document.getElementById("text-lilo").innerHTML="Schedule In/Out";
                $("#breakouttime").prop("disabled", true);
                $("#breakintime").prop("disabled", true);
                $("#break").attr('class', 'card-header bg-dark');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-secondary');
            }
            }else{
                $("#logintime").prop("disabled", true);
                document.getElementById("logintime").disabled = true;
                $("#breakouttime").prop("disabled", true);
                $("#breakintime").prop("disabled", true);
                $("#logouttime").prop("disabled", true);
                $("#lilo").attr('class', 'card-header bg-dark');
                $("#text-lilo").attr('class', 'card-title text-light');
                $("#border-lilo").attr('class', 'card border border-secondary');
                $("#break").attr('class', 'card-header bg-dark');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-secondary');
                $("#ot").attr('class', 'card-header bg-primary');
                $("#text-ot").attr('class', 'card-title text-light');
                $("#border-ot").attr('class', 'card border border-primary');
            }
        }

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#my-image')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(150);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function daterange(date){
            const d = new Date(date);
            const d1 = new Date(date);
            var today = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
                d.setDate(d.getDate() + 1);
                d1.setDate(d1.getDate() - 1);
                var tomorrow = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
                var yesterday = d1.getFullYear()+"-"+(d1.getMonth()+1)+"-"+d1.getDate();
                document.getElementById("logindate").min = yesterday;
                document.getElementById("logindate").max = tomorrow;
                document.getElementById("logoutdate").min = today;
                document.getElementById("logoutdate").max = tomorrow;
        }

        function esctype(esctype){
            var btnstatus = document.getElementById("status");
            var option1 = document.createElement("option");
            var option2 = document.createElement("option");
            var option3 = document.createElement("option");
            document.getElementById("status").innerHTML = "";
            document.getElementById("logintime").value = document.getElementById("hidlogintime").value;
            document.getElementById("logouttime").value = document.getElementById("hidlogouttime").value;
            document.getElementById("breakouttime").value = document.getElementById("hidbreakouttime").value;
            document.getElementById("breakintime").value = document.getElementById("hidbreakintime").value;
            document.getElementById("status").disabled = true;

            if(document.getElementById("type").value==8){
                if(document.getElementById("hidschin")!=null){
                document.getElementById("logintime").value = document.getElementById("hidschin").value;
                document.getElementById("logouttime").value = document.getElementById("hidschout").value;
                document.getElementById("breakouttime").value = document.getElementById("hidschbin").value;
                document.getElementById("breakintime").value = document.getElementById("hidschbout").value;
                document.getElementById("status").disabled = false;
                option1.text = "Work";
                option1.value = 1;
                btnstatus.add(option1);
                option2.text = "Rest Day";
                option2.value = 0;
                btnstatus.add(option2);
                }
                work_off();
            }else if(document.getElementById("type").value==6){
                document.getElementById("status").disabled = false;
                <?php if($cudate[5]==1){ ?>
                option1.text = "Regular OT";
                option1.value = 1;
                btnstatus.add(option1);
                <?php } ?>
                option3.text = "RD Duty";
                option3.value = 3;
                btnstatus.add(option3);
                option2.text = "RD Duty OT";
                option2.value = 2;
                btnstatus.add(option2);
                work_off();
            }else{
                escalation(esctype);                
            }
        }

        function escalation(esctype){
            document.getElementById("txt-login").innerHTML="Login";
            document.getElementById("txt-logout").innerHTML="Logout";
            document.getElementById("text-lilo").innerHTML="Login/Logout";
            document.getElementById("text-break").innerHTML="Break";
            document.getElementById("txt-brkschout").innerHTML="Break-Out"; 
            document.getElementById("txt-brkschin").innerHTML="Break-In";
            if (esctype == 1 || esctype == 5 || esctype == "") {
                $("#logintime").prop("disabled", true);
                document.getElementById("logintime").disabled = true;
                $("#breakouttime").prop("disabled", true);
                $("#breakintime").prop("disabled", true);
                $("#logouttime").prop("disabled", true);
                $("#lilo").attr('class', 'card-header bg-dark');
                $("#text-lilo").attr('class', 'card-title text-light');
                $("#border-lilo").attr('class', 'card border border-secondary');
                $("#break").attr('class', 'card-header bg-dark');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-secondary');
                $("#ot").attr('class', 'card-header bg-primary');
                $("#text-ot").attr('class', 'card-title text-light');
                $("#border-ot").attr('class', 'card border border-primary');
            }else if (esctype == 2) {
                $("#logintime").prop("disabled", false);
                $("#breakouttime").prop("disabled", true);
                $("#breakintime").prop("disabled", true);
                $("#logouttime").prop("disabled", false);
                $("#lilo").attr('class', 'card-header bg-primary');
                $("#text-lilo").attr('class', 'card-title text-light');
                $("#border-lilo").attr('class', 'card border border-primary');
                $("#break").attr('class', 'card-header bg-dark');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-secondary');
                $("#ot").attr('class', 'card-header bg-dark');
                $("#text-ot").attr('class', 'card-title text-light');
                $("#border-ot").attr('class', 'card border border-secondary');
            }else if (esctype == 3) {
                $("#logintime").prop("disabled", true);
                $("#breakouttime").prop("disabled", false);
                $("#breakintime").prop("disabled", false);
                $("#logouttime").prop("disabled", true);
                $("#lilo").attr('class', 'card-header bg-dark');
                $("#text-lilo").attr('class', 'card-title text-light');
                $("#border-lilo").attr('class', 'card border border-secondary');
                $("#break").attr('class', 'card-header bg-primary');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-primary');
                $("#ot").attr('class', 'card-header bg-dark');
                $("#text-ot").attr('class', 'card-title text-light');
                $("#border-ot").attr('class', 'card border border-secondary');
            }else if (esctype == 4 || esctype == 7 || esctype == 8) {
                $("#logintime").prop("disabled", false);
                $("#breakouttime").prop("disabled", false);
                $("#breakintime").prop("disabled", false);
                $("#logouttime").prop("disabled", false);
                $("#lilo").attr('class', 'card-header bg-primary');
                $("#text-lilo").attr('class', 'card-title text-light');
                $("#border-lilo").attr('class', 'card border border-primary');
                $("#break").attr('class', 'card-header bg-primary');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-primary');
                $("#ot").attr('class', 'card-header bg-dark');
                $("#text-ot").attr('class', 'card-title text-light');
                $("#border-ot").attr('class', 'card border border-secondary');
            }else{
                $("#logintime").prop("disabled", false);
                $("#breakouttime").prop("disabled", false);
                $("#breakintime").prop("disabled", false);
                $("#logouttime").prop("disabled", false);
                $("#lilo").attr('class', 'card-header bg-dark');
                $("#text-lilo").attr('class', 'card-title text-light');
                $("#break").attr('class', 'card-header bg-dark');
                $("#text-break").attr('class', 'card-title text-light');
                $("#border-break").attr('class', 'card border border-secondary');
                $("#ot").attr('class', 'card-heade bg-dark');
                $("#text-ot").attr('class', 'card-title text-light');
                $("#border-ot").attr('class', 'card border border-secondary');
            }
        }

        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });

        function validateForm(formObj) {
            formObj.submit.disabled = true; 
            return true;  
        }  
    </script>

</body>

</html>