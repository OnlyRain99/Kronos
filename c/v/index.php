<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "My Kronos";

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

    //get employee info
    $getemp=$link->query("SELECT `gy_emp_id`,`gy_emp_code`,`gy_emp_email`,`gy_emp_fullname`,`gy_work_from` From `gy_employee` Where `gy_emp_code`='$user_code'");
    $emprow=$getemp->fetch_array();

        $bstatus = array("", "", "", "");
        $bstatus = buttonstatus($user_code, $myaccount);
        $checklogin = $bstatus[0];
        $checkbreakout = $bstatus[1];
        $checkbreakin = $bstatus[2];
        $checklogout = $bstatus[3];

function get_client_ip() {
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
if($divprop == "hidden"){ $profile_mark = "ON LEAVE - "; if($bstatus[4]!=""){ $divprop="visible"; } }
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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="far fa-clock"></i></h2>
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
                                    <strong class="card-title mb-3"><center><span style="color: blue;"><?= $profile_mark; ?></span> <span id="date-part"></span> - <span id="time-part"></span></center></strong>
                                </div>
                                <div class="card-body">
                                    <div class="login-logo">
                                        <h5 class="text-sm-center mt-2 mb-1"><?php echo sibsid($user_code)." - ".$emprow['gy_emp_fullname']; ?></h5>
                                        <div class="location text-sm-center">
                                            <i class="fa fa-envelope"></i> <?php echo $emprow['gy_emp_email']; ?></div>
                                    </div>
                                    <hr>
                                    <div class="row" style="visibility: <?= $divprop; ?>;">
                                    <?php if($if_userip==1 && $divprop=="visible"){ ?>
                                        <div class="col-md-3">
                                            <?php if($checklogin == "disabled"){ ?>
                                                <center><button class="btn btn-success" disabled>Login</button></center>
                                            <?php }else{ ?>
                                            <form method="post" enctype="multipart/form-data" action="dtrauth?cd=login" onsubmit="return validateForm(this);">
                                                <center><button type="submit" name="login" class="btn btn-success">Login</button></center>
                                            </form>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if($checkbreakout == "disabled" || $checklogin == ""){ ?>
                                                <center><button class="btn btn-primary" disabled>Break-Out</button></center>
                                            <?php }else{ ?>
                                            <form method="post" enctype="multipart/form-data" action="dtrauth?cd=breakout" onsubmit="return validateForm(this);">
                                                <center><button type="submit" name="breakout" class="btn btn-primary" >Break-Out</button></center>
                                            </form>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if($checkbreakin == "disabled" || $checkbreakout == ""){ ?>
                                                <center><button class="btn btn-primary" disabled>Break-In</button></center>
                                            <?php }else{ ?>
                                            <form method="post" enctype="multipart/form-data" action="dtrauth?cd=breakin" onsubmit="return validateForm(this);">
                                                <center><button type="submit" name="breakin" class="btn btn-primary" >Break-In</button></center>
                                            </form>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if($checklogout == "disabled" || $checkbreakin == ""){ ?>
                                                <center><button class="btn btn-success" disabled>Logout</button></center>
                                            <?php }else{ ?>
                                            <form method="post" enctype="multipart/form-data" action="dtrauth?cd=logout" onsubmit="return validateForm(this);">
                                                <center><button type="submit" name="logout" class="btn btn-success" >Logout</button></center>
                                            </form>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title">Announcements <i class="fa fa-bell"></i></strong>
                                </div>
                                <div class="card-body" id="load_data">
                                    
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
    <input type="hidden" id="schedout" value="<?php echo $bstatus[4]; ?>">
    <?php include 'scripts.php'; ?>

    <script type="text/javascript">
        function deftimenowfromsched(){
            var schedout = document.getElementById("schedout").value;
            if(schedout!=""){
            var today = new Date();
            var month = today.getMonth()+1;
            var datenow = today.getFullYear()+"-"+month+"-"+today.getDate()+" "+today.getHours()+":"+today.getMinutes()+":"+today.getSeconds();
            var timediff = timeDiffCalc(new Date(schedout), new Date(datenow));
            var hoursonly = timediff.split(":")[0]+"."+timediff.split(":")[1];
            if(parseFloat(hoursonly) > 9){  location.reload(); }
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

        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "sending email ...";
            return true;  
        }  

        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });

        var tmout="";
        $(document).ready(function(){    
            tmout = setTimeout(loadstation, 1000);
        });

        function loadstation(){
            clearTimeout(tmout);
            $("#load_data").load("ann_res");
            deftimenowfromsched();
            tmout=setTimeout(loadstation, 300000);
        }

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
<!-- end document-->
