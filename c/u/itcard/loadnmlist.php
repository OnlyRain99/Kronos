<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$search = addslashes($_REQUEST['search']);
$tooltyp = addslashes($_REQUEST['tooltyp']);
$toolnm = addslashes($_REQUEST['toolnm']);
if($search==0&&$toolnm!=""){
    $link->query("INSERT INTO `team_toollist`(`team_name`, `team_owner`, `team_switch`) VALUES('$toolnm','$user_code','$tooltyp')");
}
$sqltmnm = "";
if($search==1&&$toolnm!=""){ $sqltmnm = " AND `team_name` LIKE '%".$toolnm."%' "; }
$sqlown = "";
if($tooltyp==0){ $sqlown = " AND `team_owner`='$user_code' "; }

    $row=1; $teamarr=array(array());
    $tmsql = $link->query("SELECT * FROM `team_toollist` WHERE `team_switch`='$tooltyp' ".$sqlown." ".$sqltmnm." ORDER BY `team_name` ASC");
    while($tmrow=$tmsql->fetch_array()){
        $teamarr[$row][0] = $tmrow['team_id'];
        $teamarr[$row][1] = $tmrow['team_name'];
        $teamarr[$row][2] = $tmrow['team_owner'];
        $teamarr[$row][3] = $tmrow['team_switch'];
        $row++;
    }

$link->close(); ?>
<div class="card-body" style="max-height: 450px; overflow: auto;">
    <div class="row">
<?php for($i=1;$i<$row;$i++){ ?>
        <div class="col-md-3" id="crdsz_<?php echo $i;?>">
            <div class="card hover-overlay shadow rounded" onmouseover="showcard(<?php echo $i; ?>)" onmouseout="lesscard(<?php echo $i; ?>)">
                <div class="card-header d-flex flex-nowrap maskp">
                    <div class="order-1 " style="cursor: pointer;" onclick="opentool(<?php echo $teamarr[$i][0]; ?>, 0); tmrtrig()" id="crdhdri_<?php echo $i;?>"><i class="fa-solid fa-file-import"></i></div>
                    <div class="order-2 ps-2 text-truncate" style="cursor: pointer;" onclick="opentool(<?php echo $teamarr[$i][0]; ?>, 0); tmrtrig()" id="crdhdr_<?php echo $i;?>"><?php echo $teamarr[$i][1]; ?></div>
                </div>
                <div class="card-footer maskp text-truncate" id="crdftr_<?php echo $i;?>"><i class="fa-solid fa-circle-user"></i> <?php echo get_emp_name($teamarr[$i][2]); if($teamarr[$i][2]==$user_code){ ?>
                    <div class="pull-right" style="display: none;" id="swtm_<?php echo $i;?>"><btn style="cursor: pointer;" title="Transfer Tool Records to <?php if($tooltyp==1){echo"My Tool Records";}else if($tooltyp==0){echo"Team Tool Records";}?>" onclick="switchteam(<?php echo $i; ?>, '<?php echo $teamarr[$i][1]; ?>')" class="btn-outline-dark btn-sm"><?php if($tooltyp==1){ ?><i class="fa-solid fa-user-secret"></i><?php }else if($tooltyp==0){ ?><i class="fa-solid fa-users-rectangle"></i><?php } ?></btn></div><?php } ?>
                </div>
        <form id="fromswtch_<?php echo $i;?>" method="post" enctype="multipart/form-data" action="<?php if($tooltyp==0){echo"teamcreds.php";}else if($tooltyp==1){echo"mycreds.php";} ?>" onsubmit="return validateForm(this);">
            <input type="hidden" name="teamid" value="<?php echo $teamarr[$i][0]; ?>">
        </form>
            </div>
        </div>
<?php } ?>
    </div>
</div>
<div class="card-footer text-muted"><?php echo ($row-1)." item"; if($row>1){echo "s";} ?></div>