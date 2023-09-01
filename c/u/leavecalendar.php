<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "My Leave Calendar";

$inimlcdate = $onlydate;
if(isset($_REQUEST['lcdate'])){
$inimlcdate = addslashes($_REQUEST['lcdate']);
}

$cjitm = array();
$cnjsql=$link->query("SELECT * From `cronjob` WHERE `cronid`=2");
$cjrow=$cnjsql->fetch_array();
$cjitm[0]=$cjrow['status'];
$cjitm[1]=$cjrow['leave_credits'];
$cjitm[2]=$cjrow['active_filter'];
$cjitm[3]=$cjrow['cronval'];

$empsql = $link->query("SELECT `gy_emp_leave_credits` From `gy_employee` WHERE `gy_emp_code`='$user_code' LIMIT 1");;
$emprow=$empsql->fetch_array();
$lvcredit=$emprow['gy_emp_leave_credits'];
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
                            <h2 class="float-left title-1 m-b-25"><i class="fa-solid fa-calendar-day"></i> <?php echo $title; ?></h2>
                            <div class="float-right m-t-35 faa-tada animated faa-slow"><b id="lvccngme"><?php echo $lvcredit; ?></b></div>
                            <div class="float-right m-t-35 m-r-5"><b>Leave Credits : </b></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="card">
                            <div class="card-body"><div id="calendar"></div></div>
                            <div id="clrsql"></div><div id="sbmtloa"></div>
                        </div>
                        </div>
                    </div>
                    <?php  if($user_type == 1 && $user_dept == 2){ ?>
                    <div class="accordion">
                        <div class="card">
                            <div class="card-header" id="creditmanage">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#moreopt" aria-expanded="true" aria-controls="moreopt" onclick="colpsmngcrdts()"> Manage Leave Credits</button>
                                </h5>
                            </div>
                            <div id="moreopt" class="collapse" aria-labelledby="creditmanage" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="card bg-secondary">
                                            <btn class="btn btn-block"><h5>Manual Manage</h5></btn>
                                            <div class="form-floating mb-2" >
                                            <select class="form-select" id="dptcrdmng" onchange="managecreditbl(1)" >
                                                <option value="">Page</option>
                                                <option value="all">ALL</option>
                                                <?php $dptsql=$link->query("SELECT * From `gy_department` ORDER BY `id_department` ASC");
                                                while($dptrow=$dptsql->fetch_array()){ ?>
                                                <optgroup label="<?php echo $dptrow['name_department']; ?>">
                                                <?php $depsql=$link->query("SELECT * From `gy_accounts` Where `gy_dept_id`='".$dptrow['id_department']."' AND `gy_acc_status`=0 ORDER BY `gy_acc_name` ASC");
                                                while($deprow=$depsql->fetch_array()){ ?>
                                                <option value="<?php echo $deprow['gy_acc_id']; ?>"><?php echo $deprow['gy_acc_name']; ?></option>
                                                <?php } ?>
                                                </optgroup>
                                                <?php } $link->close(); ?>
                                            </select>
                                            <label for="dptcrdmng">Department List</label>
                                            </div>
                                            <div class="form-floating mb-2">
                                                <input type="text" id="srchnmcrdmng" class="form-control" oninput="managecreditbl(1)" placeholder="Employee Name">
                                                <label for="srchnmcrdmng">Employee Name/SiBS ID</label>
                                            </div>
                                            <hr>
                                            <btn class="btn btn-block"><h5>Manage Automatically</h5></btn>
                                            <span id="cjtrnonoff">
                                            <?php if($cjitm[0]==0){ ?>
                                                <button class="btn btn-danger btn-block" onclick="turnonoff(1)"><i class="fa-solid fa-power-off"></i> OFF</button>
                                                <?php }else if($cjitm[0]==1){ ?>
                                                <button class="btn btn-success btn-block" onclick="turnonoff(0)" ><i class="fa-solid fa-person-running"></i> ON</button>
                                            <?php } ?>
                                            </span>
                                            <div class="input-group">
                                                    <select class="form-select" id="automntlc" onchange="trigdisshw()">
                                                        <option value="0" <?php if($cjitm[3]==0){echo"selected";} ?> title="First Day of the Month">Monthly</option>
                                                        <option value="1" <?php if($cjitm[3]==1){echo"selected";} ?> title="Every 12 AM">Everyday</option>
                                                    </select>
                                                    <span class="input-group-text">+</span>
                                                    <input type="number" min="0" class="form-control" id="autolvcrdts" placeholder="Leave Credits" oninput="trigdisshw(); inplyvcrdt(this);" value="<?php echo $cjitm[1]; ?>" >
                                                    <span class="input-group-text">Leave Credit</span>
                                            </div>
                                            <div class="form-floating">
                                                <select class="form-select" id="autoslctby" onchange="trigdisshw(); moreoptforaulcboc(this);" >
                                                    <option value="0" <?php if($cjitm[2]==0){echo"selected";} ?>>All Employees</option>
                                                    <option value="1" <?php if($cjitm[2]==1){echo"selected";} ?>>Specific Department</option>
                                                    <option value="2" <?php if($cjitm[2]==2){echo"selected";} ?>>Specific Account</option>
                                                    <option value="3" <?php if($cjitm[2]==3){echo"selected";} ?>>Regular Employment</option>
                                                </select>
                                                <label for="autoslctby">Auto Update Leave Credits By : </label>
                                            </div>
                                            <div id="moreoptforaulcb"></div>
                                            <button class="btn btn-primary btn-block" style="display: none;" id="autolvupt" onclick="uptprt()">Save Changes <i class="fa-solid fa-floppy-disk"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-7"><div class="card bg-secondary">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" style="font-family:'Calibri'; font-size: 14px;">
                                                    <thead class="table-dark">
                                                        <tr style="" class="text-center text-nowrap">
                                                            <th scope="col" >
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" title="Select All" id="cchckall" onclick="allchkbx(this, 'cchck')">
                                                                    <label class="custom-control-label" for="cchckall"></label>
                                                                </div>
                                                            </th>
                                                            <th scope="col" >ID</th>
                                                            <th scope="col" >Name</th>
                                                            <th scope="col" >Leave Credits</th>
                                                            <th scope="col" style="padding:0px;">
                                                                <button class="btn btn-outline-light btn-block" title="Update All Selected Item(s)" onclick="updallchkbtn()"><b>Update Selected</b></button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="scndcrdempmc"></tbody>
                                                </table>
                                            </div>
                                        </div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'scripts.php'; ?>
    <script src='https://cdn.jsdelivr.net/npm/rrule@2.6.4/dist/es5/rrule.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/rrule@5.5.0/main.global.min.js'></script>
    <script type="text/javascript">
        var calendar="";

        function turnonoff(fnc){
            sendpost("storetheprop", "leave/updtcrnjbo.php", "fnc="+fnc+"&func=1");
            if(fnc==0){
                document.getElementById("cjtrnonoff").innerHTML='<button class="btn btn-danger btn-block" onclick="turnonoff(1)"><i class="fa-solid fa-power-off"></i> OFF</button>';
            }else if(fnc==1){
                document.getElementById("cjtrnonoff").innerHTML='<button class="btn btn-success btn-block" onclick="turnonoff(0)" ><i class="fa-solid fa-person-running"></i> ON</button>';
            }
        }

        function uptprt(){
            var cnval = document.getElementById("automntlc").value;
            var lyvcrd= document.getElementById("autolvcrdts").value;
            if(lyvcrd!="" && lyvcrd>=0){
                var actflt = document.getElementById("autoslctby").value;
                var fltid = [];
                var i1=0;
                if(actflt==1 || actflt==2){
                    var clssnm = document.getElementsByName("dptacc");
                    for(var i=0;i<clssnm.length;i++){
                        if(clssnm[i].checked==true){ fltid[i1]=document.getElementById(clssnm[i].id).value; i1++; }
                    }
                }
                sendpost("storetheprop", "leave/updtcrnjbo.php", "cnval="+cnval+"&lyvcrd="+lyvcrd+"&actflt="+actflt+"&fltid="+fltid+"&func=0");
            }
        }function aftruptpropresponse(status){
            var title="";
            if(status=="success"){ title="Automated Leave Credit Management Properties has been updated."; document.getElementById("autolvupt").style.display="none"; document.getElementById("autolvcrdts").classList.remove("bg-warning"); }
            else if(status=="error"){ title="Error Updating the Automated management Leave Credits Properties."; }
            else if(status=="invalidcredit"){ status="warning"; title="Leave Credits Value Not Valid."; }
            else if(status=="run"){ status="success"; title="Auto Managing Leave Credits is now running."; }
            else if(status=="stop"){ status="info"; title="Auto Managing Leave Credits has been stop."; }
            else if(status=="waschange"){ status="error"; title="Do not change the value."; }
            toastalert(status, title);
        }

        function trigdisshw(){
            if(document.getElementById("autolvcrdts").value=="" || document.getElementById("autolvcrdts").value<0){
                document.getElementById("autolvupt").style.display="none";
            }else{
                document.getElementById("autolvupt").style.display="block";
            }
        }

        function moreoptforaulcboc(elem){
            if(elem.value==0){ document.getElementById("moreoptforaulcb").innerHTML=""; }
            else if(elem.value==1){ sendpost("moreoptforaulcb", "leave/slctdptacc.php", "dsply=1"); }
            else if(elem.value==2){ sendpost("moreoptforaulcb", "leave/slctdptacc.php", "dsply=2"); }
        }

        function updallchkbtn(){
            const Toast = Swal.mixin({
                      toast: true,
                })
            Toast.fire({
                      icon: 'question',
                      confirmButtonColor: '#3085d6',
                      showCancelButton: true,
                      position: 'center-end',
                      cancelButtonColor: '#d33',
                      cancelButtonText: '<i class="fa-solid fa-xmark"></i> Close',
                      confirmButtonText: '<i class="fa-solid fa-check-double"></i> Update',
                      html: '<div class="form-floating mb-2"><input type="number" min="0" value="0" id="jslyvcrdt" class="form-control" placeholder="Leave Credits" oninput="inplyvcrdt(this)"><label for="jslyvcrdt">Leave Credits</label></div>'
                }).then((result) => {
                    if(result.isConfirmed){
                        var clssnm = document.getElementsByName("cchck");
                        var clval = document.getElementById("jslyvcrdt").value;
                        if(clval!="" && clval>=0){
                        var empcd = [];
                        var i1=0;
                        for(var i=0;i<clssnm.length;i++){
                            if(clssnm[i].checked==true){
                                empcd[i1]=document.getElementById(clssnm[i].id).value;
                                i1++;
                            }
                        }
                        if(i1>0){ sendpost("uptgempcrdt", "leave/updtempcredits.php", "empcd="+empcd+"&clval="+clval+"&opt=2"); }
                        }else{ updallchkbtn(); }
                    }
                })
        }function inplyvcrdt(elem){
            if(document.getElementById(elem.id).value=="" || document.getElementById(elem.id).value<0){
                document.getElementById(elem.id).classList.remove("bg-warning");
                document.getElementById(elem.id).classList.add("bg-danger");
            }else{
                document.getElementById(elem.id).classList.remove("bg-danger"); 
                document.getElementById(elem.id).classList.add("bg-warning");
            }
        }

        function uptcrdresponse(status){
            calendar.refetchEvents();
            var title="";
            if(status=="success"){ title="Leave Credit Updated"; }
            else if(status=="error"){ title="Error Updating the Leave Credits"; }
            else if(status=="invcredit"){ status="error"; title="Leave Credits Value Not Valid"; }
            else if(status=="dnupdtlvcrdt"){ status="info"; title="Selected Item(s) has been Updated"; }
            toastalert(status, title);
        }

        function updtlvcrdts(idx, pg){
            var emcd = document.getElementById("cchck_"+idx).value;
            var lvcd = document.getElementById("lyvcrdts_"+idx).value;
            if(lvcd!="" && lvcd>=0){
                sendpost("uptempcrdts", "leave/updtempcredits.php", "emcd="+emcd+"&lvcd="+lvcd+"&opt=1");
                setTimeout(function(){ managecreditbl(pg); }, 1000);
            }
        }

        function allchkbx(elem, itmnm){
            var clssnm = document.getElementsByName(itmnm);
            var tfval = elem.checked;
            for(var i=0;i<clssnm.length;i++){ clssnm[i].checked=tfval; }
        }

        function lvcrdtsedt(idx){
            var elem = document.getElementById("lyvcrdts_"+idx);
            var btne = document.getElementById("updtlvcrdts_"+idx);
            if(document.getElementById(elem.id).value=="" || document.getElementById(elem.id).value<0){
                document.getElementById(elem.id).classList.remove("bg-warning");
                document.getElementById(elem.id).classList.add("bg-danger");
                document.getElementById(btne.id).disabled = true; }
            else {
                document.getElementById(btne.id).disabled = false;
                document.getElementById(elem.id).classList.remove("bg-danger"); 
                document.getElementById(elem.id).classList.add("bg-warning"); }
        }

        function managecreditbl(pgn){
            var dptslct = document.getElementById("dptcrdmng").value;
            var sibsemp = document.getElementById("srchnmcrdmng").value;
            document.getElementById("cchckall").checked=false;
            sendpost("scndcrdempmc", "leave/managecreditstbl.php", "dptslct="+dptslct+"&sibsemp="+sibsemp+"&pgn="+pgn);
        }

        function colpsmngcrdts(){
            var clssnm = document.getElementById("moreopt");
            //if(clssnm.classList.contains('show')==true){
                //document.getElementById("moreopt").classList.remove("show");
            //}else{
                //document.getElementById("moreopt").classList.add("show");
            if(clssnm.classList.contains('show')==false){
                managecreditbl(1);
                var dprtacc = document.getElementById("autoslctby").value;
                if(dprtacc==1 || dprtacc==2){ sendpost("moreoptforaulcb", "leave/slctdptacc.php", "dsply="+dprtacc); }
            }
        }

        function removeplan(date, lid){
            sendpost("rtrntoplnlst", "leave/removeplan.php", "lid="+lid+"&date="+date);
        }

        function showapprloa(lid){
            sendpost("acrdbody_"+lid, "leave/showtblaprvloa.php", "lid="+lid);
        }

        function confrmcancelloa(date, lid){
            const Toast = Swal.mixin({
                      toast: true,
                })
                Toast.fire({
                      icon: 'warning',
                      title: 'Proceed Removing this LOA Request?',
                      text: 'Action cannot be undo once done',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: '<i class="fa-solid fa-eraser"></i> Remove LOA Request',
                      cancelButtonText: '<i class="fa-solid fa-person-running"></i> Do not Remove LOA'
                }).then((result) => {
                    if(result.isConfirmed){
                        sendpost("sbmtloa", "leave/removeloarequest.php", "lid="+lid);
                    }
                })
        }

        function filealeave(date){
            var lvtyp = document.getElementById("leaveid").value;
            var lvres = document.getElementById("leavereason").value;
            var lvfyl = document.getElementById("leavefile").files;
            var hiddt = document.getElementById("hiddate").value;
            var lvfhd = "";
            if(document.getElementById("fdid").checked==true){ lvfhd="fdid"; }
            else if(document.getElementById("hdid").checked==true){ lvfhd="hdid"; }
            if(hiddt==date && lvtyp!="" && lvres!="" && lvfyl.length>0){
                var formData = new FormData();
                formData.append("lvtyp", lvtyp);
                formData.append("lvres", lvres);
                formData.append("lvfyl", lvfyl[0]);
                formData.append("hiddt", date);
                formData.append("lvfhd", lvfhd);
                //sendpost("sbmtloa","leave/submitloarequest.php", formData);

                var xhttp = new XMLHttpRequest();
                    xhttp.open("POST", "leave/submitloarequest.php", true);
                    xhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200) {
                        loaresponse(this.responseText);
                    }
                    };
                xhttp.send(formData);
            }
        }

        function switchlvplt(date, tabnm){
            sendpost("pltdtyl","leave/plotview.php", "startdate="+date+"&pltvw="+tabnm);
        }

        function refrshnotif(){
            sendpost("lvccngme","leave/refreshnotification.php", "type=0");
            sendpost("sbid-mlcntf","leave/refreshnotification.php", "type=1");
            sendpost("sbid-ircntf","leave/refreshnotification.php", "type=2");
        }

        function loaresponse(status){
            calendar.refetchEvents();
            var title="";
            if(status=="success"){ title="LOA request submitted to your supervisor"; }
            else if(status=="danger"){ status="error"; title="Error on request, try again"; }
            else if(status=="sizelimit"){ status="warning"; title="The attached file exceeded the file size limit"; }
            else if(status=="fileinvalid"){ status="warning"; title="The attached file is invalid"; }
            else if(status=="invalidaction"){ status="error"; title="invalid Action"; }
            else if(status=="loaremove"){ status='success'; title="LOA Request Removed"; }
            else if(status=="loanotremove"){ status="error"; title="Encountered an Error on Removing LOA Request, please try again"; }
            else if(status=="loacancel"){ status='info'; title="Approved LOA Request has been cancel"; }
            else if(status=="loanotcancel"){ status="error"; title="Encountered an Error on Cancelling LOA Request, please try again"; }
            toastalert(status, title);
        }

        function showresponse(status){
            calendar.refetchEvents();

            var title = "";
            if(status=="success"){ title="Calendar plotted"; }
            else if(status=="error"){ title= "Error plotting"; }
            else if(status=="duplicate"){ title= "Duplicatte or conflict dates, please try again on a vacant dates"; status="warning"; }
            toastalert(status, title);
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

        function plottrigger(){
            var accntid = document.getElementById("empdepartment").value;
            var dtfrom = document.getElementById("hiddate").value;
            var dtto = document.getElementById("dateend").value;
            var lslot = document.getElementById("leaveslot").value;
            var justfy = document.getElementById("leavejstfy").value;

            sendpost("clrsql", "leave/plotdates.php", "accntid="+accntid+"&dtfrom="+dtfrom+"&dtto="+dtto+"&lslot="+lslot+"&justfy="+justfy)
        }

      document.addEventListener('DOMContentLoaded', function(){
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          initialDate: '<?php echo $inimlcdate; ?>',
        dateClick: function(info){
            Swal.fire({
                width: 'auto',
                showCancelButton: false,
                showConfirmButton: false,
                html: '<div id="pltdtyl"></div>'
            })
            info.dayEl.style.backgroundColor = '#ECE9E8';
            sendpost("pltdtyl","leave/plotview.php", "startdate="+info.dateStr+"&pltvw=2");
        },
        eventDidMount: function(info) {
            var tooltip = new bootstrap.Tooltip(info.el, {
            title: info.event.extendedProps.description,
            placement: 'top',
            trigger: 'hover',
            container: 'body'
          });
        },
          events: { url: 'leave/calendarevent' }
      });
      calendar.render();
      });

        function sendpost(sid, loc, post){
            if(sid=="scndcrdempmc"){ document.getElementById(sid).innerHTML=""; }
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200 && this.responseText != ""){
                    if(sid=="clrsql"){ showresponse(this.responseText); }
                    else if(sid=="sbmtloa"){ loaresponse(this.responseText); }
                    else if(sid=="rtrntoplnlst"){ calendar.refetchEvents(); switchlvplt(this.responseText, 1); }
                    else if(sid=="uptempcrdts"){ uptcrdresponse(this.responseText); }
                    else if(sid=="uptgempcrdt"){ uptcrdresponse(this.responseText); managecreditbl(document.getElementById("actpage").value); }
                    else if(sid=="storetheprop"){ aftruptpropresponse(this.responseText); }
                    else{ document.getElementById(sid).innerHTML = this.responseText; }
                }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }
    </script>
</body>
</html>