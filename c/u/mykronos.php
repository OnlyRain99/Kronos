<?php 

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

        $bstatus = array("", "", "", "", "");
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

$profile_mark = "";
if($if_userip!=1){ $profile_mark="ACCESS NOT ALLOWED - "; }
$divprop = check_leave_today($user_id);
if($divprop == "hidden"){ $profile_mark = "ON LEAVE - "; if($bstatus[4]!=""){ $divprop="visible"; } }
?>

<!DOCTYPE html>
<html lang="en">
<style type="text/css">
    .eyes {
  position: fixed;
  top: 100%;
  transform: translateY(-50%);
  width: 100%;
  text-align: center;
}

.eye {
  width: 240px;
  height: 120px;
  background: #fff;
  display: inline-block;
  margin: 40px;
  border-radius: 50%;
  position: relative;
  overflow: hidden;
}

.ball {
  width: 50px;
  height: 50px;
  background: #000;
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translate(-50%, -50%);
  border-radius: 50%;
  border: 10px solid #333;
}
.fireworksx
{
  position: fixed;
   max-height:580px;
   width: 100%;
   z-index: 1;
}
.trntxtprp{
	display: inline-block;
}
</style>
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
		<div class="fireworksx"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php if($user_dept!=3){ echo $title; } ?> <i class="far fa-clock"></i> <div id="pgttlm" class="trntxtprp"></div><div id="pgttly" class="trntxtprp"></div>
							&nbsp;
							<div id="pgttlk" class="trntxtprp"></div><div id="pgttlr" class="trntxtprp"></div><div id="pgttlo1" class="trntxtprp"></div><div id="pgttln" class="trntxtprp"></div><div id="pgttlo2" class="trntxtprp"></div><div id="pgttls" class="trntxtprp"></div></h2>
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
                        <div class="col-md-12" id="rotatable">
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
                                    <div class="row" style="visibility: <?=$divprop; ?>;">
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

                        <div class="col-md-12" id="rotatable1">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title">Announcements <i class="fa fa-bell"></i></strong>
                                </div>
                                <div class="card-body" id="load_data">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
 <div id="eyesid" class="eyes" style="display:none;">
    <div class="eye">
        <div class="ball"></div>
    </div>
    <div class="eye">
      <div class="ball"></div>
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
  $('.logodrag').Dragging();

if(<?php if($user_dept==3){echo"true";}else{echo"false";} ?>){
$(function(){
  $('.draggable').Dragging();

  $('#rotatable').propeller({
		inertia: 0.98,
		speed: 0,
		minimalSpeed: 0.001,
		step: 0,
		stepTransitionTime: 0,
		stepTransitionEasing:'linear',

  });

var spdrtt = Math.floor(Math.random() * 25);
if(spdrtt!=7){ spdrtt=0; }

  $('#rotatable1').propeller({
		inertia: 0.98,
		speed: spdrtt,
		minimalSpeed: 0.005,
  });


$("#pgttlm").digitalwrite({
  char:'P',
  height: 25,
  width: 25,
  background:'rgba(0, 0, 0, .1)',
  success: function() { $("#pgttlm").transformTo('M', function() { }); }
});
$("#pgttly").digitalwrite({
  char:'A',
  height: 25,
  width: 25,
  background:'rgba(0, 0, 0, .1)',
  animate: 'contract',
  success: function() { $("#pgttly").transformTo('Y', function() { }); }
});
$("#pgttlk").digitalwrite({
  char:'N',
  height: 25,
  width: 25,
  border: '1px solid gray',
  animate: 'motion',
  success: function() { $("#pgttlk").transformTo('K', function() { }); }
});
$("#pgttlr").digitalwrite({
  char:'G',
  height: 25,
  width: 25,
  border: '1px solid gray',
  animate: 'spiral',
  success: function() { $("#pgttlr").transformTo('R', function() { }); }
});
$("#pgttlo1").digitalwrite({
  char:'I',
  height: 25,
  width: 25,
  border: '1px solid gray',
  animate: 'contract',
  success: function() { $("#pgttlo1").transformTo('O', function() { }); }
});
$("#pgttln").digitalwrite({
  char:'T',
  height: 25,
  width: 25,
  border: '1px solid gray',
  animate: 'fade',
  success: function() { $("#pgttln").transformTo('N', function() { }); }
});
$("#pgttlo2").digitalwrite({
  char:'K',
  height: 25,
  width: 25,
  border: '1px solid gray',
  animate: 'motion',
  success: function() { $("#pgttlo2").transformTo('O', function() { }); }
});
$("#pgttls").digitalwrite({
  char:'A',
  height: 25,
  width: 25,
  border: '1px solid gray',
  animate: 'spiral',
  success: function() { $("#pgttls").transformTo('S', function() { }); }
});
});
}else if(Math.floor(Math.random() * 50)==25){
$(function(){
  $('.draggable').Dragging();
  $('#rotatable').propeller({
		inertia: 0.98,
		speed: 0,
		minimalSpeed: 0.001,
		step: 0,
		stepTransitionTime: 0,
		stepTransitionEasing:'linear',

  });

var spdrtt = Math.floor(Math.random() * 200);
if(spdrtt!=7){ spdrtt=0; }

  $('#rotatable1').propeller({
		inertia: 0.98,
		speed: spdrtt,
		minimalSpeed: 0.005,
  });
});
}

const container = document.querySelector('.fireworksx')
const fireworks = new Fireworks.default(container);

if(<?php if($notify=="logout" && $user_dept==3){echo"true";}else{echo"false";} ?>){ fireworks.start(); setTimeout(endfireworks, 60000); }

function endfireworks(){
	fireworks.stop(); fireworks.clear();
}

const balls = document.getElementsByClassName("ball");
document.onmousemove = function() {
    if(document.getElementById("eyesid").style.display=="none" && <?php if($user_dept==3){echo"true";}else{ echo"false";}?>){ document.getElementById("eyesid").style.display="inline"; }
if(document.getElementById("eyesid").style.display!="none"){
  let x = event.clientX * 100 / window.innerWidth + "%";
  let y = (event.clientY * 100 / window.innerHeight)-30 + "%";

  for(let i = 0; i < 2; i++) {
    balls[i].style.left = x;
    balls[i].style.top = y;
    balls[i].style.transform = "translate(-"+x+", -"+y+")";
  }
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
            tmout=setTimeout(loadstation, 1800000);
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
