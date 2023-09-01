<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$sellvl = "";
$selby = addslashes($_REQUEST['selby']);
if($selby==1){
    $sellvl = " AND `gy_emp_type`=".addslashes($_REQUEST['lvlsel']);
}else if($selby==2){
    $sellvl = " AND `gy_acc_id`=".addslashes($_REQUEST['lvlsel']);
}

    $i = 0; $lstid = array(); $lstnm = array();
if($selby>=0 && $selby<=2){
    $empsql=$link->query("SELECT `gy_emp_code`, `gy_emp_fullname` From `gy_employee` WHERE `gy_emp_code`!='' ".$sellvl ." ORDER By `gy_emp_fullname` asc");
    while($emprow=$empsql->fetch_array()){
        $lstid[$i] = $emprow['gy_emp_code'];
        $lstnm[$i] = $emprow['gy_emp_fullname'];
        $i++;
    }
}else if($selby==3){
    $dptid = addslashes($_REQUEST['lvlsel']);
    $accsql=$link->query("SELECT * From `gy_accounts` WHERE `gy_acc_status`=0 AND `gy_dept_id`='$dptid' ORDER BY `gy_acc_name` ASC");
    while($accrow=$accsql->fetch_array()){
        $lstid[$i] = $accrow['gy_acc_id'];
        $lstnm[$i] = $accrow['gy_acc_name'];
        $i++;
    }
}

$link->close();
?>

<select class="form-select minwid-120" id="empname" onchange="autosearch()">
    <option value=""></option>
    <?php if($selby>=0 && $selby<=2){ ?><option value="all">Select All</option><?php } ?>
    <?php for($i=0;$i<count($lstid);$i++){ ?>
    <option value="<?php echo $lstid[$i]; ?>"><?php echo $lstnm[$i]; ?></option>
    <?php } ?>
</select>
<label for="empname"><?php if($selby>=0 && $selby<=2){echo "Employee List"; }else if($selby==3){echo "Account List"; } ?></label>
