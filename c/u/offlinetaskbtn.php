<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
    include '../../config/connnk.php';
    date_default_timezone_set('Asia/Taipei');

    $id = 0;
    $btnid = addslashes($_REQUEST['btnid']);
    $btnname = addslashes($_REQUEST['btnname']);
    $startt = "";
    if($btnname!="" && $btnid!=""){
        $tmpid = substr($btnid, strpos($btnid, "_") + 1);
        $datets = date("Y-m-d H:i:s");
        $btnlst=$dbticket->query("SELECT `id` From `offline_task` WHERE `id`='$tmpid' LIMIT 1");
        if(mysqli_num_rows($btnlst)>0){
            $dbticket->query("UPDATE `offline_task` SET `end_time`='".$datets."' Where `id`='".$tmpid."'");
            $id=0; $btnname=""; $btnid="";
        }else{
            $dbticket->query("INSERT INTO `offline_task`(`emp_code`,`start_time`,`task`)values('$user_code','$datets','$btnname')");
            $idlst=$dbticket->query("SELECT `id`,`start_time`,`task` From `offline_task` WHERE `emp_code`='$user_code' AND `end_time`='0000-00-00 00:00:00' LIMIT 1");
            if(mysqli_num_rows($idlst)>0){ $idrow=$idlst->fetch_array(); $id=$idrow['id']; $btnname=$idrow['task']; $startt=date("Y/m/d H:i:s", strtotime($idrow['start_time'])); }
        }
    }else{
        $btnlst=$dbticket->query("SELECT `id`,`start_time`,`task` From `offline_task` WHERE `emp_code`='$user_code' AND `end_time`='0000-00-00 00:00:00' LIMIT 1");
        if(mysqli_num_rows($btnlst)>0){ $btnidrow=$btnlst->fetch_array(); $id=$btnidrow['id']; $btnname=$btnidrow['task']; $startt=date("Y/m/d H:i:s", strtotime($btnidrow['start_time'])); }
    }

$dbticket->close(); $link->close();
?>
<input type="hidden" id="startt" value="<?php echo $startt; ?>">
<div class="row">
    <div class="col-md-6">
        <?php if($btnname=="" && $btnid=="" || $btnname=="Team Huddle"){ ?>
        <button class="btn btn-outline-secondary btn-block"  id="btnthuddle_<?php echo $id; ?>" onclick="offlinebtn(this)">Team Huddle</button>
    <?php }else{ ?><button class="btn btn-secondary btn-block" disabled>Team Huddle</button><?php } ?>
    </div>
    <div class="col-md-6">
        <?php if($btnname=="" && $btnid=="" || $btnname=="Coaching"){ ?>
        <button class="btn btn-outline-secondary btn-block"  id="btncoaching_<?php echo $id; ?>" onclick="offlinebtn(this)">Coaching</button>
    <?php }else{ ?><button class="btn btn-secondary btn-block" disabled>Coaching</button><?php } ?>
    </div>
    <div class="col-md-6">
        <?php if($btnname=="" && $btnid=="" || $btnname=="Refresher Training"){ ?>
        <button class="btn btn-outline-secondary btn-block"  id="btnrtraining_<?php echo $id; ?>" onclick="offlinebtn(this)">Refresher Training</button>
    <?php }else{ ?><button class="btn btn-secondary btn-block" disabled>Refresher Training</button><?php } ?>
    </div>
    <div class="col-md-6">
        <?php if($btnname=="" && $btnid=="" || $btnname=="Personal Quick Break"){ ?>
        <button class="btn btn-outline-secondary btn-block"  id="btnpqbreak_<?php echo $id; ?>" onclick="offlinebtn(this)">Personal Quick Break</button>
    <?php }else{ ?><button class="btn btn-secondary btn-block" disabled>Personal Quick Break</button><?php } ?>
    </div>
</div>