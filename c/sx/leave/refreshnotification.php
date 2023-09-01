<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$type = addslashes($_REQUEST['type']);
if($type==0){
    $empsql = $link->query("SELECT `gy_emp_leave_credits` From `gy_employee` WHERE `gy_emp_code`='$user_code' LIMIT 1");;
    $emprow=$empsql->fetch_array();
    echo $emprow['gy_emp_leave_credits'];
}else if($type==1){
    $leavecount = get_lv5plusleave($user_id, $myaccount, $_SESSION['fus_user_type'])+get_leave_pending_requests($user_id); ?>
    <i class="fa-solid fa-calendar-day faa-pulse"></i> My Leave Calendar <?php if($leavecount>0){ ?><span class="badge badge-danger" title="<?php echo $leavecount." LOA pending request"; ?>"><?php echo $leavecount; ?></span><?php }
}else if($type==2){
    $leavecount = get_lv5plusleave($user_id, $myaccount, $_SESSION['fus_user_type'])+get_leave_pending_requests($user_id); ?>
    <i class="fas fa-newspaper faa-flash"></i>My Issue Resolution Center <?php $issue=count_issue($user_code, $user_id, $user_type)+$leavecount; if($issue>0){ ?><span class="position-absolute badge rounded-pill bg-danger"><?php echo $issue; ?><span class="visually-hidden">unread messages</span></span><?php } ?>
<?php
}

$link->close();
?>