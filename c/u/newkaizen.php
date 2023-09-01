<?php 

    $title = "vidaXL Productivity Form";
    $notify = @$_GET['note'];
    if ($notify == "pro_update") {
        $note = "Profile Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "login") {
        $note = "Login Successful";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "breakout") {
        $note = "Break-Out Successful";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "breakin") {
        $note = "Break-In Successful";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "logout") {
        $note = "Logout Successful";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "invalid") {
        $note = "Action not Valid";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "mismatch") {
        $note = "Password Mismatch!";
        $notec = "danger";
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

    $getemp=$link->query("SELECT `gy_emp_id`,`gy_emp_code`,`gy_emp_email`,`gy_emp_fullname`,`gy_work_from` From `gy_employee` Where `gy_emp_code`='$user_code'");
    $emprow=$getemp->fetch_array();

    $pause = 0;
    include '../../config/connnk.php';
    $offtlst=$dbticket->query("SELECT `start_time` From `offline_task` WHERE `emp_code`='$user_code' AND `end_time`='0000-00-00 00:00:00' LIMIT 1");
    if(mysqli_num_rows($offtlst)>0){ $pause = 1; }
    $dbticket->close();

        $bstatus = array("", "", "", "", "");
        $bstatus = buttonstatus($user_code, $myaccount);
        $checklogin = $bstatus[0];
        $checkbreakout = $bstatus[1];
        $checkbreakin = $bstatus[2];
        $checklogout = $bstatus[3];

function get_client_ip(){
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) { $ipaddress = $_SERVER['HTTP_CLIENT_IP'];  }
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];  }
    else if(isset($_SERVER['HTTP_X_FORWARDED'])) { $ipaddress = $_SERVER['HTTP_X_FORWARDED'];  }
    else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) { $ipaddress = $_SERVER['HTTP_FORWARDED_FOR']; }
    else if(isset($_SERVER['HTTP_FORWARDED'])) { $ipaddress = $_SERVER['HTTP_FORWARDED']; }
    else if(isset($_SERVER['REMOTE_ADDR'])) { $ipaddress = $_SERVER['REMOTE_ADDR'];  }
    else { $ipaddress = 'UNKNOWN'; }

    include '../../config/conn.php';
    $ipadd = explode(', ', $ipaddress);
    $wlsql=$link->query("SELECT `ip` From `gy_whitelist` where `ip` IN ('".implode("','",$ipadd)."')");
    $numrow = 0;
    if(mysqli_num_rows($wlsql)>0){ $numrow=1; }

    $link->close();
    return $numrow;
}
$if_userip = 0;
if($emprow['gy_work_from']==0){ $if_userip = get_client_ip(); }
else if($emprow['gy_work_from']==1){ $if_userip=1; }

function matchsched($dbemp, $dblogin){
    include '../../config/conn.php';
    $today = date("Y-m-d", strtotime($dblogin));
    $yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
    $tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
    $theemp = getempid($dbemp);
    $arrsched = 0;
    $empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."' AND `gy_emp_id`='".$theemp."' AND `gy_sched_mode`!=0 ORDER BY `gy_sched_day` ASC");
    if(mysqli_num_rows($empsch) > 0){
        while ($scrow=$empsch->fetch_array()) {
            if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))) {
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
            }else{
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
            }

            if(strtotime($dblogin) < $schedlout){
            $arrsched = getwh(date("Y-m-d", strtotime($scrow['gy_sched_day']))." ".convert24to0($scrow['gy_sched_login']), date("Y-m-d H:i:s", $schedlout));
            break; }
        }
    }
    $link->close();
    return $arrsched;
}

$profile_mark = "";
$sched = 0;
$sched = matchsched($user_code, date("Y-m-d H:i:s"));
if($sched<=0){ $profile_mark="NOT SCHEDULED - "; }
if($if_userip!=1){ $profile_mark="ACCESS NOT ALLOWED - "; }
$divprop = check_leave_today($user_id);
if($divprop == "hidden"){ $profile_mark = "ON LEAVE - "; }

    $pause = 0;
    include '../../config/connnk.php';
    $offtlst=$dbticket->query("SELECT `start_time` From `offline_task` WHERE `emp_code`='$user_code' AND `end_time`='0000-00-00 00:00:00' LIMIT 1");
    if(mysqli_num_rows($offtlst)>0){ $pause = 1; }
    $dbticket->close();
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
                            <h2 class="title-1 m-b-25 faa-parent animated-hover"><?php echo $title; ?> <i class="far fa-clock faa-spin faa-fast"></i></h2>
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
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><strong class="card-title mb-3"><center><span style="color: blue;"></span><?= $profile_mark; ?> <span id="date-part"></span> - <span id="time-part"></span></center></strong></div>
                                <div class="card-body">
                                    <div class="row" style="visibility: <?= $divprop; ?>;">
                                    <?php if($if_userip==1 && $divprop=="visible"){ ?>
                                        <div class="col-md-6">
                                            <?php if($checklogin == "disabled"){ ?>
                                                <button class="btn btn-success btn-block" disabled>Login</button>
                                            <?php }else{ ?>
                                            <form method="post" enctype="multipart/form-data" action="dtrauth?cd=login" onsubmit="return validateForm(this);">
                                                <button type="submit" name="login" class="btn btn-outline-success btn-block faa-horizontal animated-hover faa-fast">Login</button>
                                            </form>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if($checkbreakin == "disabled" || $checkbreakout == ""){ ?>
                                                <button class="btn btn-primary btn-block" disabled>Break-In</button>
                                            <?php }else{ ?>
                                            <form method="post" enctype="multipart/form-data" action="dtrauth?cd=breakin" onsubmit="return validateForm(this);">
                                                <button type="submit" name="breakin" class="btn btn-outline-primary btn-block faa-float animated-hover" <?php if($pause==1){echo "disabled";} ?>>Break-In</button>
                                            </form>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if($checkbreakout == "disabled" || $checklogin == ""){ ?>
                                                <button class="btn btn-primary btn-block" disabled>Break-Out</button>
                                            <?php }else{ ?>
                                            <form method="post" enctype="multipart/form-data" action="dtrauth?cd=breakout" onsubmit="return validateForm(this);">
                                                <button type="submit" name="breakout" class="btn btn-outline-primary btn-block faa-vertical animated-hover faa-slow" <?php if($pause==1){echo "disabled";} ?>>Break-Out</button>
                                            </form>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if($checklogout == "disabled" || $checkbreakin == ""){ ?>
                                                <button class="btn btn-success btn-block" disabled>Logout</button>
                                            <?php }else{ ?>
                                            <form method="post" enctype="multipart/form-data" action="dtrauth?cd=logout" onsubmit="return validateForm(this);">
                                                <button type="submit" name="logout" class="btn btn-outline-success btn-block faa-bounce faa-reverse animated-hover faa-slow" <?php if($pause==1){echo "disabled";} ?>>Logout</button>
                                            </form>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card" id="ticketform">
                                <div class="card-header"><center><strong class="card-title">Ticket Form</strong></center></div>
                                <div class="card-body"></div>
                                <div class="card-footer text-muted"></div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title">Announcements <i class="fa fa-bell faa-ring animated faa-slow"></i></strong>
                                </div>
                                <div class="card-body" id="load_data"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><strong class="card-title mb-3"><center id="offlinetimer">Offline Task</center></strong></div>
                                <div class="card-body" id="offlinetb"></div>
                            </div>

                            <div class="row" id="disprec">
                                <div class="col-md-3 faa-ring animated-hover faa-fast">
                                    <div class="card ol-md-3">
                                    <div class="card-header"><center><strong class="card-title">Email</strong><br><span class="badge badge-success" id="cemail"></span></center></div>
                                    </div>
                                </div>
                                <div class="col-md-3 faa-pulse animated-hover faa-fast">
                                    <div class="card">
                                    <div class="card-header"><center><strong class="card-title">Phone</strong><br><span class="badge badge-danger" id="cphone"></span></center></div>
                                    </div>
                                </div>
                                <div class="col-md-3 faa-spin animated-hover faa-fast">
                                    <div class="card">
                                    <div class="card-header"><center><strong class="card-title">Chat </strong><br><span class="badge badge-secondary" id="cchat"></span></center></div>
                                    </div>
                                </div>
                                <div class="col-md-3 faa-tada animated-hover faa-fast">
                                    <div class="card">
                                    <div class="card-header"><center><strong class="card-title">Total </strong><br><span class="badge badge-primary" id="ctotal"></span></center></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title" id="trtitle">Ticket Records </strong>
                                </div>
                                <div class="card-body" id="tickrec"></div>
                            </div>
                        </div>
                    </div>
                <?php include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="pause" value="<?php echo $pause; ?>">
    <input type="hidden" id="schedout" value="<?php echo $bstatus[4]; ?>">
    <?php $link->close(); include 'scripts.php'; ?>

    <script type="text/javascript">  
var timerid;

    function offlinebtn(elem){
        var title = "Confirm end of "+elem.innerText;
        var id = elem.id.substring(elem.id.indexOf("_")+1);
        var text = "Set status to active now?";
        if(id==0){ title="Confirm start of "+elem.innerText; text = "You are about to go pause, proceed?"; }
        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
            if (result.isConfirmed){
            var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(){
                    if (this.readyState == 4 && this.status == 200){
                        document.getElementById("offlinetb").innerHTML = this.responseText;
                        if(id==0){
                            document.getElementById("ticketform").innerHTML = "";
                            if(document.getElementsByName("login").length>0){ document.getElementsByName("login")[0].disabled = true; }
                            else if(document.getElementsByName("logout").length>0){ document.getElementsByName("logout")[0].disabled = true; }
                            else if(document.getElementsByName("breakin").length>0){ document.getElementsByName("breakin")[0].disabled = true; }
                            else if(document.getElementsByName("breakout").length>0){ document.getElementsByName("breakout")[0].disabled = true; }
                            timerid = setInterval(offtimer, 1000);
                        }else{
                            loadticketform();
                            if(document.getElementsByName("login").length>0){ document.getElementsByName("login")[0].disabled = false; }
                            else if(document.getElementsByName("logout").length>0){ document.getElementsByName("logout")[0].disabled = false; }
                            else if(document.getElementsByName("breakin").length>0){ document.getElementsByName("breakin")[0].disabled = false; }
                            else if(document.getElementsByName("breakout").length>0){ document.getElementsByName("breakout")[0].disabled = false; }
                            clearInterval(timerid);
                            document.getElementById("offlinetimer").innerHTML = "Offline Task";
                        }
                    }
                };
                xhttp.open("POST", "offlinetaskbtn.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("btnid="+elem.id+"&btnname="+elem.innerText);
            }
        })
    }

    function loadofflinetaskbtn(){
        var btnid = "";
        var btnname = "";
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("offlinetb").innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "offlinetaskbtn.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("btnid="+btnid+"&btnname="+btnname);
    }

    function loadticketrec(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("tickrec").innerHTML = this.responseText;
                document.getElementById("cemail").innerHTML = document.getElementById("tbemail").value;
                document.getElementById("cphone").innerHTML = document.getElementById("tbphone").value;
                document.getElementById("cchat").innerHTML = document.getElementById("tblchat").value;
                document.getElementById("ctotal").innerHTML = document.getElementById("tbtotal").value;
                document.getElementById("trtitle").innerHTML = "Ticket Records ";
            }
        };
        xhttp.open("GET", "loadtickets.php", true);
        xhttp.send();
    }

    function loadticketform(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("ticketform").innerHTML = this.responseText;
                loadticketrec();
            }
        };
        xhttp.open("GET", "ticket_form.php", true);
        xhttp.send();
    }

    function checkif1(ticketid){
        var btnrad = document.getElementsByName("options-outlined");
        if(ticketid.value != ""){
            for(var i=0;i<btnrad.length;i++){
                btnrad[i].disabled = false;
                btnrad[i].checked = false;
                document.getElementById("submitticket").disabled = true;
            }
        }
        else{
            for(var i=0;i<btnrad.length;i++){
                btnrad[i].disabled = true;
                btnrad[i].checked = false;
                document.getElementById("submitticket").disabled = true;
            }
        }
    }

    function enablesubmit(){
        document.getElementById("submitticket").disabled = false;
    }

    function whensubmit(){
        var ticketid = document.getElementById("tickedid").value;
        var chname = "";
        var btnrad = document.getElementsByName("options-outlined");
            for(var i=0;i<btnrad.length;i++){
                if(btnrad[i].checked == true){
                    if(i == 0){ chname = "Email"; }
                    else if(i == 1){ chname = "Phone"; }
                    else if(i == 2){ chname = "Live Chat"; }
                    break;
                }
            }
        var text = "Is this correct? <table style='width:100%; border:1px solid black;'><tr><th style='border-right:1px solid black;'>Ticket ID</th><th>Channel</th></tr><tr><td style='border-right:1px solid black;'>"+ticketid+"</td><td>"+chname+"</td></tr></table>";
        Swal.fire({
            title: 'Confirmation',
            html: text,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
            if (result.isConfirmed && ticketid != "" && chname != "") {
                document.getElementById("tftitle").innerHTML = "Ticket Form <i class='fa fa-spinner fa-pulse'></i>";
                document.getElementById("trtitle").innerHTML = "Ticket Records <i class='fa fa-spinner fa-pulse'></i>";
                document.getElementById("tickedid").disabled = true;
                document.getElementById("submitticket").disabled = true;
                for(var i=0;i<btnrad.length;i++){
                    btnrad[i].disabled = true;
                }
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    document.getElementById("sqldiv").innerHTML = this.responseText;
                    loadticketform();
                }
                };
                xhttp.open("POST", "saveticket.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("ticketid="+ticketid+"&channel="+chname);
            }
        })
    }

        function loadfirst(){
            var btnbo = document.getElementsByName("breakout");
            var btnlo = document.getElementsByName("logout");
            var pause = document.getElementById("pause").value;
            if(btnbo.length > 0 || btnlo.length > 0){
                if(pause==0){ loadticketform(); }
                loadofflinetaskbtn();
                if(pause==1){ timerid = setInterval(offtimer, 1000); }
            }
        }

        function deftimenowfromsched(){
            var schedout = document.getElementById("schedout").value;
            if(schedout!=""){
            var today = new Date();
            var month = today.getMonth()+1;
            var datenow = today.getFullYear()+"-"+month+"-"+today.getDate()+" "+today.getHours()+":"+today.getMinutes()+":"+today.getSeconds();
            var timediff = timeDiffCalc(new Date(schedout), new Date(datenow));
            var hoursonly = timediff.split(":")[0]+"."+timediff.split(":")[1];
            if(parseFloat(hoursonly) > 9){ location.reload(); }
            }
        }

        function timeDiffCalc(dateFuture, dateNow) {
            let diffInMilliSeconds = Math.abs(dateFuture - dateNow) / 1000;
            const days = Math.floor(diffInMilliSeconds / 86400);
            diffInMilliSeconds -= days * 86400;
            const hours = Math.floor(diffInMilliSeconds / 3600) % 24;
            diffInMilliSeconds -= hours * 3600;
            const minutes = Math.floor(diffInMilliSeconds / 60) % 60;
            diffInMilliSeconds -= minutes * 60;

            var dayhours = (days * 24) + hours;
            let diff = '';
            diff = dayhours+':'+minutes+':'+diffInMilliSeconds;
            return diff;
        }

        function offtimer(){
            var today = new Date();
            var month = today.getMonth()+1;
            var datenow = today.getFullYear()+"/"+month+"/"+today.getDate()+" "+today.getHours()+":"+today.getMinutes()+":"+today.getSeconds();
            var datefrom = datenow;
            if(document.getElementById("startt")){ datefrom = document.getElementById("startt").value; }
            var timediff = timeDiffCalc(new Date(datefrom), new Date(datenow));
            document.getElementById("offlinetimer").innerHTML = "Offline Task - "+timediff;
        }

        loadfirst();

        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "sending email ...";
            return true;  
        }
    </script>

    <script type="text/javascript">
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });

        //$(document).ready(function(){    
        //    loadstation();
        //});

        function loadstation(){
            //$("#load_data").load("ann_res");
            //setTimeout(loadstation, 60000);
            deftimenowfromsched();
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    document.getElementById("load_data").innerHTML = this.responseText;
                    setTimeout(loadstation, 300000);
                }
            };
            xhttp.open("GET", "ann_res.php", true);
            xhttp.send();
        }
        
        setTimeout(loadstation, 60000);
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
        var interval = setInterval(function() {
            var momentNow = moment();
            $('#date-part').html(momentNow.format('YYYY MMMM DD') + ' '
                                + momentNow.format('dddd')
                                 .substring(0,3).toUpperCase());
            $('#time-part').html(momentNow.format('hh:mm:ss A'));
        }, 100);
    });
    </script>
</body>
</html>