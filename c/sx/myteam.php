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
$supcode = "";
if($daagent!=""){
    $supid = get_supervisor($daagent);
    $supsql=$link->query("SELECT `gy_full_name`,`gy_user_code` From `gy_user` Where `gy_user_id`='$supid'");
    $suprow=$supsql->fetch_array();
    $supcode = $suprow['gy_user_code'];
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
                            <h2 class="title-1 m-b-25"> <?php echo $title; ?> <i class="fas fa-paper-plane"></i></h2>
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
                                <label>Select Name</label>
                                <input id="daagent" type="hidden" value="<?php echo $daagent; ?>">
                                <select name="selectnames0" id="selectnames0" class="form-control" onchange="updtmysup(this)" required>
                                    <option></option>
                                    <?php
                                    if($user_type==18){
                                    $agtnm=$link->query("SELECT `gy_emp_fullname`,`gy_emp_code` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_employee`.`gy_emp_supervisor`='$user_id'");
                                    }else{
                                    $agtnm=$link->query("SELECT `gy_emp_fullname`,`gy_emp_code` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND (`gy_employee`.`gy_emp_supervisor`='$user_id' OR (`gy_employee`.`gy_emp_type`=2 AND `gy_employee`.`gy_acc_id`='$myaccount'))"); }
                                    while ($agtlst=$agtnm->fetch_array()){ ?>
                                    <option value="<?= $agtlst['gy_emp_code']; ?>" <?php if($supcode==$agtlst['gy_emp_code'] || $daagent==$agtlst['gy_emp_code']){ echo "selected"; }?>><?= $agtlst['gy_emp_fullname']; ?></option>
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
                            <div class="card">
                            <div class="table-responsive m-b-40">
                                <form method="post" enctype="multipart/form-data">
                                    <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                    <thead>
                                        <tr class="mybg" id="thisisthetableheadid">
                                            <th style="padding: 10px;" class="text-center">Name</th>
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
                                            <th style="padding: 10px;" class="text-center"><i class="fa fa-arrow-circle-up"></i></th>
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
                '<th style="padding: 10px;" class="text-center"><i class="fa fa-eye"></i></th>'+
                '<th style="padding: 10px;" class="text-center"><i class="fa fa-thumbs-up"></i></th>'+
                '<th style="padding: 10px;" class="text-center"><i class="fa fa-thumbs-down"></i></th>'+
                '<th style="padding: 10px;" class="text-center"><i class="fa fa-arrow-circle-up"></i></th>';
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

        function stsrspns(status){
            var title="";
            if(status=="cancelgood"){ status="info"; title="Escalation Request has been cancelled"; }
            else if(status=="cancelerror"){ status="error"; title="Error processing the request"; }
            toastalert(status, title);
            procsearch("");
        }

        function cancelesc(trkid, cdate){
            const Toast = Swal.mixin({ toast: true, })
            Toast.fire({
                title: 'Cancel '+cdate+' Escalation?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fa-solid fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa-solid fa-thumbs-down"></i> No'
            }).then((result) => {
                if(result.isConfirmed){
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function(){
                    if (this.readyState == 4 && this.status == 200){
                        stsrspns(this.responseText);
                    }};
                    xhttp.open("POST", "team/cancelescreq.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("trkid="+trkid+"&cdate="+cdate);
                }
            })
        }

        function updtsb(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("sb_issuestatus").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "team/sb_issueres.php", true);
        xhttp.send();
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
        xhttp.open("POST", "team/search_hrmembers.php", true);
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
        xhttp.send("datefrom="+datefrom+"&dateto="+dateto+"&nameid0="+nameid0+"&nameid="+nameid);
        }

        function approvetrig(trackid){
        document.getElementById("c2_"+trackid).innerText = "Processing...";
        var nameid0 = document.getElementById("selectnames0").value;
        var nameid = document.getElementById("selectnames").value;
        let athval = document.getElementById("wh_"+trackid).value;
        let athmax = document.getElementById("wh_"+trackid).max;
        if(parseFloat(athval) <= parseFloat(athmax)){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
				var status = this.responseText;
				var title="";
				if(status=="approve"){ status="success"; title="DTR Logs has been approve!"; }
				else if(status=="duporerr"){ status="warning"; title="DTR logs is invalid, please check if the logs/scedule is complete or the date is not duplicated to the already approved date."; }
				else{ status="error"; title="Processing error! Try again."; }
				toastalert(status, title);
                procsearch("");
            }
        };
        xhttp.open("POST", "team/approvedtr.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("trackid="+trackid+"&nameid0="+nameid0+"&nameid="+nameid+"&athval="+athval);
        }
        }

        function rejecttrig(trackid){
        document.getElementById("c2_"+trackid).innerText = "Processing...";
        var reason = document.getElementById("myreason_"+trackid).value;
        var remarks = document.getElementById("remarks_"+trackid).value;
        var nameid0 = document.getElementById("selectnames0").value;
        var nameid = document.getElementById("selectnames").value;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("divqry").innerHTML = this.responseText;
                procsearch("");
            }
        };
        xhttp.open("POST", "team/rejectdtr.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("trackid="+trackid+"&reason="+reason+"&remarks="+remarks+"&nameid0="+nameid0+"&nameid="+nameid);         
        }
        
        function escatrig(elem, trackid, curdate){
        var urlx = "";
        var empid = document.getElementById("selectnames").value;
            var btnint = 0;
            if(elem.name=="btnesa"){ btnint = 1; }
            else if(elem.name=="btneml"){ btnint = 2; }
        if(trackid == 0){ urlx = "ml="+curdate+"&empid="+empid+"&bname="+btnint; trackid = ""; }
        else{ urlx = "cd="+trackid+"&ml="+curdate+"&empid="+empid+"&bname="+btnint; }
            var wintmp = window.open("escalate?"+urlx, 'mywin', 'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0');
            wintmp.onload = function(){
                wintmp.onunload = function () {
                    updtsb();
                    procsearch("Loading...");
                };
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

        function init(){
            if(document.getElementById("selectnames0").value!=""){
                document.getElementById("selectnames0").onchange();
            }
        }

        init();
    </script>
</body>
</html>