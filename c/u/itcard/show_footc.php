<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$cha = addslashes($_REQUEST['cha']);
$status = addslashes($_REQUEST['status']);
$pgnum = addslashes($_REQUEST['pgnum']);
$blncnt = 0;
if($status==0){ $sttsql = " `gy_user`.`gy_user_status`='0' "; }
else if($status==1){ $sttsql = " `gy_user`.`gy_user_status`='1' "; }
else if($status==2){ $sttsql = " `gy_user`.`gy_user_status`>='0' AND `gy_user`.`gy_user_status`<='1' "; }
if($cha==""){
$blnsql=$link->query("SELECT * From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` WHERE ".$sttsql); 
$blncnt=$blnsql->num_rows;
}

$link->close();
?>
<nav aria-label="..."> 
    <ul class="pagination flex-wrap" id="pagelink">
<?php for($i=1;$i<=ceil($blncnt/10);$i++){ ?>
    <li class="page-item <?php if($pgnum==$i){echo "active";}?>"><a class="page-link" href="#" onclick="showstartc(document.getElementById('shw_a2z'), <?php echo $i; ?>)"><?php echo $i; ?></a></li>
<?php } ?>
    </ul>
</nav>

