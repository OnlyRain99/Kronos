<?php
	include '../../config/conn.php';
	include '../../config/function.php';
	include 'session.php';

	$title = "Employee Actual Time Logs";
 if($user_dept == 2){
$dadate = "";
$daagent = "";
if(isset($_REQUEST['dadate']) && isset($_REQUEST['daagent'])){
    $dadate = addslashes($_REQUEST['dadate']);
    $daagent = addslashes($_REQUEST['daagent']);
}
$datef = "";
$datet = "";
if(date("d")>15){ $datef=date("Y-m-16"); $datet=date("Y-m-t"); }
else{ $datef=date("Y-m-01"); $datet=date("Y-m-15"); }

if($dadate != ""){
    $datef = date("Y-m-d", strtotime($dadate));
    $datet = date("Y-m-d", strtotime($dadate));
}

$accid="";
if($daagent!=""){
    $accid=get_account_id($daagent);    
}

?>
	<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body>
	<div class="page-wrapper">
		<?php include 'header-m.php'; ?>
        <?php include 'sidebar.php'; ?>
        <div class="page-container">
        	<div class="main-content" style="padding: 20px;">
        		<div class="container-fluid">
        			<div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"> <?php echo $title; ?> <i class="fa-solid fa-business-time"></i></h2>
                        </div>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                        	<div class="col-lg-2">
                            	<div class="form-group">
                                <label>From <span style="color: red;">*required</span></label>
                                <input type="date" name="from" id="datefrom" onchange="daterange()" class="form-control" value="<?php echo $datef; ?>" required>
                            	</div>
                        	</div>
                        	<div class="col-lg-2">
                            	<div class="form-group">
                                <label>To <span style="color: red;">*required</span></label>
                                <input type="date" name="to" id="dateto" onchange="daterange()" class="form-control" value="<?php echo $datet; ?>" required>
                            	</div>
                        	</div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                <label>Select Department</label>
                                <input id="daagent" type="hidden" value="<?php echo $daagent; ?>">
                                <select class="form-select" id="selectnames0" onchange="updtmysup(this)" required>
                                <option></option>
                                <?php $dptidarr = array(); $dptnmarr = array();
                                    $dptsql=$link->query("SELECT * From `gy_department` ORDER BY `id_department` ASC");
                                    while($dptrow=$dptsql->fetch_array()){ ?>
                                        <optgroup label="<?php echo $dptrow['name_department']; ?>">
                                <?php $depsql=$link->query("SELECT * From `gy_accounts` Where `gy_dept_id`='".$dptrow['id_department']."' AND `gy_acc_status`=0 ORDER BY `gy_acc_name` ASC");
                                    while($deprow=$depsql->fetch_array()){ ?>
                                    <option value="<?php echo $deprow['gy_acc_id']; ?>"  <?php if($accid==$deprow['gy_acc_id']){echo"selected";} ?> ><?php echo $deprow['gy_acc_name']; ?></option>
                                <?php } ?>
                                        </optgroup>
                                <?php } ?>
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                <label>Select Member</label>
                                <select name="selectnames" id="selectnames" class="form-control" onchange="procsearch('Loading...')" required>
                                    <option></option>
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-1 text-right">
                            <div class="form-group">
                                <label><br></label><br>
                                <input type="hidden" id="hiddsplytyp" value="0">
                                <btn class="btn btn-light faa-parent animated-hover" onclick="switchdsply()" id="theidoftheswitchbtn" ><i class="fas fa-folder faa-shake"></i></btn></div>
                            </div>
                            <div class="col-lg-1 text-right">
                                <div class="form-group">
                                <label><br></label><br>
                                <btn class="btn btn-success" id="reficon" onclick="procsearch('')"><i class="fa fa-refresh"></i></btn></div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive m-b-40">
                            	<form method="post" enctype="multipart/form-data">
                            		<table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px; background-color:#FFF">
                            		<thead>
                                        <tr class="mybg" id="thisisthetableheadid">
                                            <th style="padding: 10px;">Name</th>
                                            <th style="padding: 10px;" class="text-center">Date</th>
                                            <th style="padding: 10px;" class="text-center">IN</th>
                                            <th style="padding: 10px;" class="text-center">BO</th>
                                            <th style="padding: 10px;" class="text-center">BI</th>
                                            <th style="padding: 10px;" class="text-center">OUT</th>
                                            <th style="padding: 10px;" class="text-center">SI</th>
                                            <th style="padding: 10px;" class="text-center">SO</th>
                                            <th style="padding: 10px;" class="text-center">SH</th>
                                            <th style="padding: 10px;" class="text-center">BH</th>
                                            <th style="padding: 10px;" class="text-center">OT</th>
                                            <th style="padding: 10px; color: red;" class="text-center">UT/L</th>
                                            <th style="padding: 10px;" class="text-center">ATH</th>
                                            <th style="padding: 10px;" class="text-center">Status</th>
                                            <th style="padding: 10px;" class="text-center"><i class="fa fa-eye"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="mytmsht"></tbody>
                            		</table>
                            	</form>
                                <div id="divqry"></div>
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
        function switchdsply(){
            var theswitchval=document.getElementById("hiddsplytyp").value;
            if(theswitchval==0){
                document.getElementById("theidoftheswitchbtn").innerHTML='<i class="fas fa-calendar faa-ring"></i>';
                document.getElementById("thisisthetableheadid").innerHTML='<th style="padding: 10px;" class="text-center">Name</th>'+
                '<th style="padding: 10px;" class="text-center">Date</th>'+
                '<th style="padding: 10px;" class="text-center">Day</th>'+
                '<th style="padding: 10px;" class="text-center">Scheduled In</th>'+
                '<th style="padding: 10px;" class="text-center">Scheduled Out</th>'+
                '<th style="padding: 10px;" class="text-center">Scheduled to</th>'+
                '<th style="padding: 10px;" class="text-center">Event</th>';
                document.getElementById("hiddsplytyp").value=1;
            }else if(theswitchval==1){
                document.getElementById("theidoftheswitchbtn").innerHTML='<i class="fas fa-folder faa-shake"></i>';
                document.getElementById("thisisthetableheadid").innerHTML='<th style="padding: 10px;" class="text-center">Name</th>'+
                '<th style="padding: 10px;" class="text-center">Date</th>'+
                '<th style="padding: 10px;" class="text-center">IN</th>'+
                '<th style="padding: 10px;" class="text-center">BO</th>'+
                '<th style="padding: 10px;" class="text-center">BI</th>'+
                '<th style="padding: 10px;" class="text-center">OUT</th>'+
                '<th style="padding: 10px;" class="text-center">SI</th>'+
                '<th style="padding: 10px;" class="text-center">SO</th>'+
                '<th style="padding: 10px;" class="text-center">SH</th>'+
                '<th style="padding: 10px;" class="text-center">BH</th>'+
                '<th style="padding: 10px;" class="text-center">OT</th>'+
                '<th style="padding: 10px; color: red;" class="text-center">UT/L</th>'+
                '<th style="padding: 10px;" class="text-center">ATH</th>'+
                '<th style="padding: 10px;" class="text-center">Status</th>'+
                '<th style="padding: 10px;" class="text-center"><i class="fa fa-eye"></i></th>';
                document.getElementById("hiddsplytyp").value=0;
            }
            procsearch("Loading...");
        }

        function daterange(){
            var from = _getID("datefrom").value;
            var to = _getID("dateto").value;

            if (from) {
                _getID("dateto").min = from;
            }

            if (to) {
                _getID("datefrom").max = to;
            }
            procsearch("Loading...");
        }

        function updtmysup(cntrlval){
        var daagent = document.getElementById("daagent").value;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("selectnames").innerHTML = this.responseText;
                procsearch("Loading...");
            }
        };
        xhttp.open("POST", "team/search_members.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("cntrlval="+cntrlval.value+"&daagent="+daagent);
        }

        function procsearch(txt){
            if(txt!=""){ document.getElementById("mytmsht").innerText = txt; }
            var datefrom = document.getElementById("datefrom").value;
            var dateto = document.getElementById("dateto").value;
            var nameid0 = document.getElementById("selectnames0").value;
            var nameid = document.getElementById("selectnames").value;
            if(datefrom != "" && dateto != "" && nameid0 != "" && nameid != ""){
            searchdate(datefrom, dateto, nameid0, nameid);
            }else{
                 document.getElementById("mytmsht").innerText = "No Result";
            }
        }

        function searchdate(datefrom, dateto, nameid0, nameid){
        document.getElementById("reficon").innerHTML = '<i class="fa fa-refresh fa-spin"></i>';
		document.getElementById("reficon").disabled = true;
        var loc = "team/search_myteam.php";
        var switchval = document.getElementById("hiddsplytyp").value;
        if(switchval==0){ loc="team/search_myteam.php"; }else if(switchval==1){ loc="team/search_thesched.php"; }
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("mytmsht").innerHTML = this.responseText;
                document.getElementById("reficon").innerHTML = '<i class="fa fa-refresh"></i>';
                document.getElementById("reficon").disabled = false;
            }
        };
        xhttp.open("POST", loc, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("datefrom="+datefrom+"&dateto="+dateto+"&nameid0="+nameid0+"&nameid="+nameid);
        }

        function init(){
            if(document.getElementById("selectnames0").value!=""){
                document.getElementById("selectnames0").onchange();
            }
        }

        init();
    </script>
</body>
</html>
<?php }  $link->close(); ?>