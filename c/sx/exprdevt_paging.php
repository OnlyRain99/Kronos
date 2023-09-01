<?php
include '../../config/conn.php';

$pg = addslashes($_REQUEST['pg']);

 $hcsql=$link->query("SELECT `gy_hol_id` FROM `gy_holiday_calendar` ");
 $count=$hcsql->num_rows;
for($i=1;$i<=ceil($count/10);$i++){ ?>
<li class="page-item <?php if($i==$pg){echo 'active';} ?>"><a class="page-link" href="#" onclick="switchpage(<?php echo $i; ?>)"><?php echo $i; ?></a></li>
<?php
}
$link->close(); ?>