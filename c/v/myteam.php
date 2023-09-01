<?php
	include '../../config/conn.php';
	include '../../config/function.php';
	include 'session.php';

	$title = "My Team";

$dadate = "";
$daagent = "";
if(isset($_REQUEST['dadate']) && isset($_REQUEST['daagent'])){
    $dadate = addslashes($_REQUEST['dadate']);
    $daagent = addslashes($_REQUEST['daagent']);
}
$datef = "";
$datet = "";
if(date("d")>15){$datef=date("Y-m-16"); $datet=date("Y-m-t");}
else{$datef=date("Y-m-01"); $datet=date("Y-m-15");}

if($dadate != ""){
    $datef = date("Y-m-d", strtotime($dadate));
    $datet = date("Y-m-d", strtotime($dadate));
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
                            <h2 class="title-1 m-b-25"> <?php echo $title; ?></h2>
                        </div>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3">
                            	<div class="form-group">
                            	<label>Select Team Member</label>
                                <input id="daagent" type="hidden" value="<?php echo $daagent; ?>">
                                <select name="selectnames" id="selectnames" class="form-control" onchange="procsearch('Loading...')">
                                	<option></option>
                                	<?php
                                	$agtnm=$link->query("SELECT `gy_emp_fullname`,`gy_emp_code` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_employee`.`gy_emp_type`<$user_type AND (`gy_employee`.`gy_emp_supervisor`='$user_id' OR `gy_employee`.`gy_acc_id`=$myaccount) ");
                                	while ($agtlst=$agtnm->fetch_array()){ ?>
                                	<option value="<?= $agtlst['gy_emp_code']; ?>" <?php if($daagent==$agtlst['gy_emp_code']){ echo "selected"; }?> ><?= $agtlst['gy_emp_fullname']; ?></option>
                                	<?php } ?>
                                </select>
                            	</div>
                        	</div>
                        	<div class="col-lg-3">
                            	<div class="form-group">
                                <label>From <span style="color: red;">*required</span></label>
                                <input type="date" name="from" id="datefrom" onchange="daterange()" class="form-control" value="<?php echo $datef; ?>" required>
                            	</div>
                        	</div>
                        	<div class="col-lg-3">
                            	<div class="form-group">
                                <label>To <span style="color: red;">*required</span></label>
                                <input type="date" name="to" id="dateto" onchange="daterange()" class="form-control" value="<?php echo $datet; ?>" required>
                            	</div>
                        	</div>
                            <div class="col-lg-1 text-right">
                            <!--    <div class="form-group">
                                <label><br></label><br>
                            	<btn class="btn btn-success" name="btnapprove" onclick="getcbval()"><i class="fa fa-thumbs-up"></i> Approve Checked</btn>
								</div>-->
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
                                <btn class="btn btn-success" id="reficon" onclick="procsearch('')"><i class="fa fa-refresh"></i></btn>
								</div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                            <div class="table-responsive m-b-40">
                            	<form method="post" enctype="multipart/form-data">
                            	    <div class="card-header">
                            		</div>
                            		<table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                            		<thead>
                                        <tr class="mybg" id="thisisthetableheadid">
                                            <!--<th style="text-align: center;"><input type="checkbox" id="checkBoxAll" title="click to check all ..." /></th>-->
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
                                            <th style="padding: 10px;" class="text-center"><i class="fa fa-thumbs-up"></i></th>
                                            <th style="padding: 10px;" class="text-center"><i class="fa fa-thumbs-down"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="mytmsht"></tbody>
                            		</table>
                            	</form>
                            	<div id="divqry"></div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <?php include 'footer.php'; $link->close(); ?>
                </div>
        	</div>
        </div>
    </div>
    <?php include 'scripts.php'; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#checkBoxAll').click(function() {
                if ($(this).is(":checked"))
                    $('.chkCheckBoxId').prop('checked', true);
                else
                    $('.chkCheckBoxId').prop('checked', false);
            });
        });

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
                document.getElementById("thisisthetableheadid").innerHTML=
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
                '<th style="padding: 10px;" class="text-center"><i class="fa fa-eye"></i></th>'+
                '<th style="padding: 10px;" class="text-center"><i class="fa fa-thumbs-up"></i></th>'+
                '<th style="padding: 10px;" class="text-center"><i class="fa fa-thumbs-down"></i></th>';
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

        function searchdate(datefrom, dateto, nameid){
		document.getElementById("reficon").innerHTML = '<i class="fa fa-refresh fa-spin"></i>';
        var loc = "team/search_hrteam.php";
        var switchval = document.getElementById("hiddsplytyp").value;
        if(switchval==0){ loc="team/search_hrteam.php"; }else if(switchval==1){ loc="team/search_thesched.php"; }
		var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("mytmsht").innerHTML = this.responseText;
                document.getElementById("reficon").innerHTML = '<i class="fa fa-refresh"></i>';
            }
        };
        xhttp.open("POST", loc, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("datefrom="+datefrom+"&dateto="+dateto+"&nameid="+nameid);
        }
		
		function procsearch(txt){
            if(txt!=""){ document.getElementById("mytmsht").innerText = txt; }
			var datefrom = document.getElementById("datefrom").value;
			var dateto = document.getElementById("dateto").value;
			var nameid = document.getElementById("selectnames").value;
			if(datefrom != "" && dateto != "" && nameid != ""){
			searchdate(datefrom, dateto, nameid);
			}else{
                 document.getElementById("mytmsht").innerText = "No Result";
            }
		}

        function toastalert(status, title){
            const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                })
                Toast.fire({
                      icon: status,
                      title: title
                })
        }

        function approvetrig(empcode, trackid){
        document.getElementById("c2_"+trackid).innerText = "Processing...";
		let athval = document.getElementById("wh_"+trackid).value;
        let athmax = document.getElementById("wh_"+trackid).max;
        if(parseFloat(athval) <= parseFloat(athmax)){
		var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
				var status = this.responseText;
				var title="";
				if(status=="approve"){ status="success"; title="DTR Logs has been approve!"; }
				else if(status=="duporerr"){ status="warning"; title="DTR logs is invalid, please check if the logs/schedule is complete or the date is not duplicated to the already approved date."; }
				else{ status="error"; title="Processing error! Try again."; }
				toastalert(status, title);
                procsearch("");
            }
        };
        xhttp.open("POST", "team/approvedtr.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("empcode="+empcode+"&trackid="+trackid+"&athval="+athval);
        }
        }

        function rejecttrig(empcode, trackid){
        document.getElementById("c2_"+trackid).innerText = "Processing...";
        var reason = document.getElementById("myreason_"+trackid).value;
        var remarks = document.getElementById("remarks_"+trackid).value;
		var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("divqry").innerHTML = this.responseText;
                procsearch("");
            }
        };
        xhttp.open("POST", "team/rejectdtr.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("empcode="+empcode+"&trackid="+trackid+"&reason="+reason+"&remarks="+remarks);        	
        }

        function getcbval(){
            var i1 = 0, s = "";
            const arrckd = [];
            var nameele = document.getElementsByName("tracker_id");
            var empcode = document.getElementById("selectnames").value;
            for(var i=0; i<nameele.length; i++){
                if(nameele[i].checked && nameele[i].type == "checkbox" && nameele[i].disabled == false){
                    arrckd[i1] = nameele[i].value;
                    i1++;
                    if(i1 > 1){ s = "s"; }
                }
            }

            if(i1 > 0){
            Swal.fire({
                title: 'Approve All?',
                text: 'Apply to the '+i1+' checked item'+s+' in the table.',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Yes, Approved ATH',
                denyButtonText: `Yes, Excluding OT`,
                cancelButtonText: 'No, Close',
                confirmButtonColor: '#28a745',
                denyButtonColor: '#28a745',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if(result.isConfirmed){
                    approvedalllogs(empcode, arrckd, i1, 'ahtwh');
                }else if(result.isDenied) {
                    approvedalllogs(empcode, arrckd, i1, 'whwot');
                }
            })
            }else{
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select items to approved!',
                confirmButtonColor: '#3085d6'
                })
            }
        }

		function check_input(trackid){
            let intval = document.getElementById("wh_"+trackid).value;
            let intmax = document.getElementById("wh_"+trackid).max;
            if(intval < 0){
                document.getElementById("wh_"+trackid).value = 0; }
            else if(parseFloat(intval) > parseFloat(intmax)){
                document.getElementById("wh_"+trackid).value = intmax;
            }
        }

        function approvedalllogs(empcode, arrckd, i1, ahtnoot){
            for(var i=0; i<i1; i++){
                document.getElementById("c2_"+arrckd[i]).innerText = "Processing...";
            }
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("divqry").innerHTML = this.responseText;
                procsearch("");
            }
            };
            xhttp.open("POST", "team/approveallchk.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("empcode="+empcode+"&trackids="+arrckd+"&ahtnoot="+ahtnoot);
        }

        function init(){
            if(document.getElementById("selectnames").value!=""){
                document.getElementById("selectnames").onchange();
            }
        }

        init();
    </script>
</body>
</html>