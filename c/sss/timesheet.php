<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "My Timesheet";
if($user_type == 3 || $user_type == 4 || $user_type == 18 || $user_type == 10){
	$i=0;
	$acntarr=array();
    $myagtsql=$link->query("SELECT `gy_acc_id` FROM `gy_employee` WHERE `gy_emp_supervisor`='$user_id'");
    while($myagtrow=$myagtsql->fetch_array()){
        if(!in_array($myagtrow['gy_acc_id'], $acntarr)){ $acntarr[$i]=$myagtrow['gy_acc_id']; $i++; }
    }
?>
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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class='fas fa-address-book'></i></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($myaccount == 22 || $user_dept == 9){ ?>
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong>Timesheet</strong></a></li>
                                <li class="nav-item"><a class="nav-link active" href="vxl_reports">VidaXL Scheduled Reports</a></li>
                                <li class="nav-item"><a class="nav-link active" href="vxl_logs">VidaXL Ticket Logs</a></li>
                            </ul>
                            <?php } ?>
                            <div class="card">
                               
                                    <div class="input-group">
<div class="input-group-prepend">
    <div class="form-floating minwid-120">
        <select class="form-select" id="slt_depact" onchange="upd_usrlst(this)" required>
            <option></option>
            <option value="all">All</option>
            <?php $dptidarr = array(); $dptnmarr = array();
            $dptsql=$link->query("SELECT * From `gy_department` ORDER BY `id_department` ASC");
             while($dptrow=$dptsql->fetch_array()){ ?>
                <optgroup label="<?php echo $dptrow['name_department']; ?>">
            <?php $depsql=$link->query("SELECT * From `gy_accounts` Where `gy_dept_id`='".$dptrow['id_department']."' AND `gy_acc_status`=0 ORDER BY `gy_acc_name` ASC");
             while($deprow=$depsql->fetch_array()){ ?>
                <option value="<?php echo $deprow['gy_acc_id']; ?>"><?php echo $deprow['gy_acc_name']; ?></option>
            <?php } ?>
                </optgroup>
            <?php } ?>
        </select>
        <label for="slt_depact">Account</label>
    </div>
    <div class="form-floating minwid-120">
        <select class="form-select" id="slt_users" onchange="dsply_timesheet()" required>
            <option></option>
        </select>
        <label class="slt_users">Agent Name</label>
    </div>    
    <div class="form-floating">
        <input type="date" name="from" id="datefrom" onchange="daterange()" class="form-control" required>
        <label class="datefrom">Date From</label>
    </div>
    <div class="form-floating">
        <input type="date" name="to" id="dateto" onchange="daterange()" class="form-control" required>
        <label class="datefrom">Date To</label>
    </div>
    <button class="btn btn-outline-secondary btn-lg float-right" title="Refresh" id="rfrsh" onclick="dsply_timesheet()"><i class="fas fa-refresh faa-wrench animated faa-slow"></i></button>
    <button class="btn btn-outline-secondary btn-lg float-right" title="Download" id="dwnld" onclick="dlemprep()"><i class="fas fa-download faa-float animated faa-slow"></i></button>
</div>
                                    </div>
                                
                                    <div class="table-responsive">
                                        <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">HRID</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Name</th>
													<th scope="col" style="padding: 2px;" class="text-center text-nowrap">Account</th>
													<th scope="col" style="padding: 2px;" class="text-center text-nowrap">Team lead</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Shift Date</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">LogIn Time</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">BreakOut Time</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">BreakIn Time</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">LogOut Time</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Schedule Start</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Schedule End</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Schedule Hours</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Actual Hours</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Break Hours</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbltimesheet"></tbody>
                                        </table>
                                    </div>
                        </div>
                    </div>
                    <?php include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
<?php include 'scripts.php'; ?>
<script type="text/javascript">
    function daterange(){
        var from = _getID("datefrom").value;
        var to = _getID("dateto").value;
        if (from) { _getID("dateto").min = from; }
        if (to) { _getID("datefrom").max = to; }
        dsply_timesheet();
    }

    function upd_usrlst(elem){
        if(elem.value!=""){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("slt_users").innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "timesheet_searchuser.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("accid="+elem.value);
        }else{ document.getElementById("slt_users").innerHTML=""; }
        dsply_timesheet();
    }

    function dsply_timesheet(){
        document.getElementById("rfrsh").innerHTML = '<i class="fas fa-refresh faa-spin animated faa-fast"></i>';
        document.getElementById("rfrsh").disabled = true;
        document.getElementById("dwnld").disabled = true;
        var dept = document.getElementById("slt_depact").value;
        var user = document.getElementById("slt_users").value;
        var dfro = document.getElementById("datefrom").value;
        var dato = document.getElementById("dateto").value;
        if(dept!="" && user!="" && dfro!="" && dato!=""){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    document.getElementById("tbltimesheet").innerHTML = this.responseText;
                    document.getElementById("rfrsh").innerHTML = "<i class='fas fa-refresh faa-wrench animated faa-slow'></i>";
                    document.getElementById("dwnld").innerHTML = '<i class="fas fa-download  faa-float animated faa-slow"></i>';
                    document.getElementById("rfrsh").disabled = false;
                    document.getElementById("dwnld").disabled = false;
                }
            };
            xhttp.open("POST", "timesheet_search.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("dept="+dept+"&user="+user+"&dfro="+dfro+"&dato="+dato);            
        }else{ document.getElementById("tbltimesheet").innerHTML="";  }
    }

    function dlemprep(){
        document.getElementById("rfrsh").innerHTML = '<i class="fas fa-refresh  faa-spin animated faa-fast"></i>';
        document.getElementById("dwnld").innerHTML = '<i class="fas fa-download faa-bounce faa-reverse faa-fast animated"></i>';
        document.getElementById("rfrsh").disabled = true;
        document.getElementById("dwnld").disabled = true;
        var dept = document.getElementById("slt_depact").value;
        var user = document.getElementById("slt_users").value;
        var dfro = document.getElementById("datefrom").value;
        var dato = document.getElementById("dateto").value;
        if(dept!="" && user!="" && dfro!="" && dato!=""){
            var urlx = "dept="+dept+"&user="+user+"&dfro="+dfro+"&dato="+dato;
            var wintmp = window.open("timesheet_csv?"+urlx, "_blank");
            wintmp.onload = function(){
                wintmp.onunload = function () {
                    document.getElementById("rfrsh").innerHTML = "<i class='fas fa-refresh faa-wrench animated faa-slow'></i>";
                    document.getElementById("dwnld").innerHTML = '<i class="fas fa-download faa-float animated faa-slow"></i>';
                    document.getElementById("rfrsh").disabled = false;
                    document.getElementById("dwnld").disabled = false;
                };
            }
        }
    }
</script>
</body>
</html>
<?php } $link->close(); ?>