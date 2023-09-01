<?php  
    $title = "Kronos";

    session_start();

    if (isset($_SESSION['fus_user_id'])) {
        if($_SESSION['fus_user_type'] == "0"){
            header("location: c/a/");
        }else if($_SESSION['fus_user_type'] == "1"){
            header("location: c/u/");
        }else if($_SESSION['fus_user_type'] == "2"){
            header("location: c/s/");
        }else if($_SESSION['fus_user_type'] == "3"){
            header("location: c/ss/");
        }else if($_SESSION['fus_user_type'] == "4"){
            header("location: c/sss/");
        }else if($_SESSION['fus_user_type'] == "5"){
            header("location: c/v/");
        }else if($_SESSION['fus_user_type'] >= "6" && $_SESSION['fus_user_type'] <= "18"){
            header("location: c/sx/");
        }
    }

    $notify = @$_GET['note'];
    $input = @$_GET['input'];

    if ($notify == "notfound") {
        $note = "<i class='fa fa-warning'></i> Wrong username or password";
        $notec = "danger";
        $notes = "";
        $notei = $input;
    }else{
        $note = "";
        $notec = "";
        $notes = "display: none;";
        $notei = "";
    }
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
.fireworksxl
{
    position: fixed;
   max-height:580px;
   width: 33.33%;
}
.fireworksxr
{
    position: fixed;
   max-height:580px;
   width: 33.33%;
   left: 66.67%;
}
</style>
<?php include 'head.php'; ?>

<body name="remanms" class="animsition" style="background-color: #3cb2ff;">
    <div name="remanms" class="page-wrapper" style="background-color: #3cb2ff;">
        <div class="container">
        <div class="fireworksxl"></div>
        <div class="fireworksxr"></div>
            <div class="login-wrap draggable">
                <div class="login-content" style="border-radius: 12px;">
                    <div class="login-logo draggable1">
                        <a href="#">
                            <img src="images/icon/kronoslyv2.png" alt="CoolAdmin">
                        </a>
                        <center><label style="color: #1791d3; margin-top: 10px; text-transform: uppercase;">every second counts</label></center>
                    </div>
                    <div class="login-form" style="margin-top: -20px;">
                        <form method="post" enctype="multipart/form-data" action="config/login_conf" onsubmit="return validateForm(this);">
                            <div class="form-group">
                                <label>Username</label>
                                <input class="au-input au-input--full" type="text" name="username" id="username" maxlength="255" placeholder="Company Email or SiBS ID" value="<?php echo $notei; ?>" autofocus required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="au-input au-input--full" type="password" name="password" maxlength="16" placeholder="********" required>
                            </div>
                            <button class="au-btn au-btn--block au-btn--blue2 m-b-20" name="login" id="login" type="submit">sign in</button>

                            <div style="<?php echo $notes; ?>" class="alert alert-danger" role="alert"><center><?php echo $note; ?></center></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">
$( function() {
	$('.draggable').Dragging({
		speed: 500,
		vertical:true,
		horizontal:false,
		rotate:true
	});

	$('.draggable1').Dragging({
		speed: 500,
		vertical:true,
		horizontal:true,
		rotate:true

	});
});

        $( document ).ready(function() {
            $("#username").select();
        });

        function validateForm(formObj) {
            formObj.login.disabled = true; 
            return true;  
        }  

if(Math.floor(Math.random() * 10)==5){
    bubbly();
    const anm1 = document.getElementsByName("remanms")[0];
    const anm2 = document.getElementsByName("remanms")[1];

    anm1.className="";
    anm2.className="";
    anm1.removeAttribute("style");
    anm2.removeAttribute("style");
}

<?php if((date("d")=="25" && date("m")=="12")||(date("d")=="01" && date("m")=="01")){ ?>
const container = document.querySelector('.fireworksxl')
const fireworks = new Fireworks.default(container);
fireworks.start(); setTimeout(endfireworks, 60000);
function endfireworks(){ fireworks.stop(); fireworks.clear(); }

const containerr = document.querySelector('.fireworksxr')
const fireworksr = new Fireworks.default(containerr);
fireworksr.start(); setTimeout(endfireworksr, 60000);
function endfireworksr(){ fireworksr.stop(); fireworksr.clear(); }
<?php } if(date("d")=="31" && date("m")=="10"){ ?>
const balls = document.getElementsByClassName("ball");
document.onmousemove = function() {
    if(document.getElementById("eyesid").style.display=="none"){ document.getElementById("eyesid").style.display="inline"; }
  let x = event.clientX * 100 / window.innerWidth + "%";
  let y = (event.clientY * 100 / window.innerHeight)-30 + "%";

  for(let i = 0; i < 2; i++) {
    balls[i].style.left = x;
    balls[i].style.top = y;
    balls[i].style.transform = "translate(-"+x+", -"+y+")";
  }
}
<?php } ?>
    </script>

</body>

</html>
<!-- end document-->