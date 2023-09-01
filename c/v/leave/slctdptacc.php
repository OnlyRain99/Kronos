<?php
    include '../../../config/conn.php';
	include '../../../config/function.php';
    include '../session.php';

if($user_type == 5 && $user_dept == 2){ 
$dsply = addslashes($_REQUEST['dsply']);
$i=0; $dptaccarr = array(array());
if($dsply==1){
    $dptsql=$link->query("SELECT * From `gy_department` ORDER BY `id_department` ASC");
        while($dptrow=$dptsql->fetch_array()){
            $dptaccarr[$i][0]=$dptrow['id_department'];
            $dptaccarr[$i][1]=$dptrow['name_department'];
            $i++;
        }
}else if($dsply==2){
    $depsql=$link->query("SELECT * From `gy_accounts` Where `gy_acc_status`=0 ORDER BY `gy_dept_id` ASC, `gy_acc_name` ASC");
        while($deprow=$depsql->fetch_array()){
            $dptaccarr[$i][0]=$deprow['gy_acc_id'];
            $dptaccarr[$i][1]=$deprow['gy_acc_name'];
            $i++;
        }
}

$fltrid=array();
$cnjsql=$link->query("SELECT `filter_id` From `cronjob` WHERE `cronid`=2 AND `active_filter`=$dsply");
$cjrow=$cnjsql->fetch_array();
$fltrid=explode(",",$cjrow['filter_id']);
}
$link->close();
?>
<div class="table-responsive">
    <table class="table table-striped table-hover" style="font-family:'Calibri'; font-size: 14px;">
        <thead class="table-dark">
            <tr style="" class="text-center text-nowrap">
                <th scope="col" >
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="swtchallckbx" onclick="allchkbx(this, 'dptacc'); trigdisshw();">
                        <label class="form-check-label" for="swtchallckbx"></label>
                    </div>
                </th>
                <th scope="col"><?php if($dsply==1){echo"Department";}else if($dsply==2){echo"Account";} ?> Name</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i1=0;$i1<$i;$i1++){ ?>
                <tr class="text-nowrap text-center">
                    <td style="padding-bottom:3px; padding-top:3px;" scope="row">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="dptacc" id="swtchallckbx_<?php echo $dptaccarr[$i1][0]; ?>" value="<?php echo $dptaccarr[$i1][0]; ?>" onclick="trigdisshw()" <?php if(in_array($dptaccarr[$i1][0], $fltrid)){echo "checked";} ?> >
                            <label class="form-check-label" for="swtchallckbx_<?php echo$dptaccarr[$i1][0];?>"></label>
                        </div>
                    </td>
                    <td style="padding-bottom:2px;"><?php echo $dptaccarr[$i1][1]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>