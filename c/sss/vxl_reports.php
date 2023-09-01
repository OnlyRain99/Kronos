<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
    if($myaccount == 22 || $user_dept == 9){
	include '../../config/connnk.php';

    //array master list users
    $i = 0; $vxlmarr = array();
    $vxlmlist=$dbticket->query("SELECT `mr_emp_code` From `vidaxl_masterlist`"); 
        while($vxlmrow=$vxlmlist->fetch_array()){
            $vxlmarr[$i] = $vxlmrow['mr_emp_code'];
            $i++;
        }

    $dbticket->close();

    $title = "VidaXL Ticket Reports";
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
                            <ul class="nav nav-tabs">
								<li class="nav-item"><a class="nav-link active" href="timesheet">Timesheet</a></li>
                                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong>VidaXL Scheduled Reports</strong></a></li>
                                <li class="nav-item"><a class="nav-link active" href="vxl_logs">VidaXL Ticket Logs</a></li>
                            </ul>
                            <div class="card">
                                <div class="card-header">
                                    <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" >User: </span></div>
                <select class="custom-select" id="searchempsel">
                    <option></option>
                    <option value="all">All</option>
<?php $vxlemp=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname` From `gy_employee` Where `gy_emp_code` IN ('".implode("','",$vxlmarr)."') ORDER BY `gy_emp_fullname` ASC");
                while($vxlrow=$vxlemp->fetch_array()){ ?>
                    <option value="<?php echo $vxlrow['gy_emp_code']; ?>"><?php echo $vxlrow['gy_emp_fullname']; ?></option>
<?php } ?>
                </select>
                <div class="input-group-prepend"><span class="input-group-text" > From : </span></div>
                <input type="date" name="from" id="datefrom" onchange="daterange()" class="form-control" required>
                <div class="input-group-prepend"><span class="input-group-text" > To : </span></div>
                <input type="date" name="to" id="dateto" onchange="daterange()" class="form-control" required>
                <button class="btn btn-outline-secondary" id="btnsearchrpt" onclick="searchemprep(this)" >Search <i class="fas fa-search"></i></button>
                <button class="btn btn-outline-secondary" onclick="dlemprep()" >Download <i class="fas fa-download"></i></button>
                                    </div>
                                </div>
                                    <div class="table-responsive">
                                        <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead>
                                                <tr>
                                    <th scope="col" style="padding: 10px;" class="text-center">ID</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">Email</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">First Name</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">Last Name</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">Depertment</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">Date</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">LogIn</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">LogOut</th>
                                    <th scope="col" style="padding: 10px;" class="text-center" title="Sched In">SchIn</th>
                                    <th scope="col" style="padding: 10px;" class="text-center" title="Sched Out">SchOut</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">Chat</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">Email</th>
                                    <th scope="col" style="padding: 10px;" class="text-center">Phone</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblreport"></tbody>
                                        </table>
                                    </div>                               
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
    }

    function searchemprep(elem){
        var empcode = document.getElementById("searchempsel").value;
        var fdate = document.getElementById("datefrom").value;
        var tdate = document.getElementById("dateto").value;
        if(empcode != "" && fdate != "" && tdate != ""){
            document.getElementById(elem.id).innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
            document.getElementById(elem.id).disabled = true;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    document.getElementById("tblreport").innerHTML = this.responseText;
                    document.getElementById(elem.id).innerHTML = "Search <i class='fas fa-search'></i>";
                    document.getElementById(elem.id).disabled = false;
                }
            };
            xhttp.open("POST", "mr_searchreport.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("empcode="+empcode+"&fdate="+fdate+"&tdate="+tdate);
        }
    }

    function dlemprep(){
        var empcode = document.getElementById("searchempsel").value;
        var fdate = document.getElementById("datefrom").value;
        var tdate = document.getElementById("dateto").value;    
        if(empcode != "" && fdate != "" && tdate != ""){
            var urlx = "empcode="+empcode+"&fdate="+fdate+"&tdate="+tdate;
            window.open("mr_gentktrep?"+urlx, "_blank");
        }
    }
</script>
</body>
</html>
<?php } $link->close(); ?>