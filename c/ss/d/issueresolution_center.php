<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "Issue Resolution Center";
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body>
    <div class="page-wrapper">
    <?php include 'header-m.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="page-container">
        <div class="main-content" style="padding: 20px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fas fa-newspaper"></i></h2>
                </div>
            </div>
            <div class="row">
                <?php
    $datestrt = date("Y-m-d");
    if(date("d")<=3){ $datestrt = date("Y-m-16", strtotime("-1 Month")); }
    else if(date("d")>=1 && date("d")<=18){ $datestrt = date("Y-m-01"); }
    else if(date("d")>=16){ $datestrt = date("Y-m-16"); }

        $ntype = 0;
        $today = date("Y-m-d");
            while($ntype < 3){
                $lday = date("Y-m-d", strtotime($today.' -1 day'));
                if(date("w", strtotime($lday)) != 0 && date("w", strtotime($lday)) != 6){ $ntype++; }
                $today = $lday;
            }
            $i = 0;
                $schesql = $link->query("SELECT * From `gy_schedule_escalate` Where (`gy_req_by`='$user_id' OR `gy_req_to`='$user_id' OR `gy_sup`='$user_id' OR `gy_emp_code`='$user_code') AND `gy_req_status`='0' AND `gy_sched_day`>='$datestrt' ORDER BY `gy_sched_esc_id` desc");
                while ($scherow=$schesql->fetch_array()){
                    $own = 0; if($scherow['gy_emp_code']==$user_code){ $own = 1; }
                    cardcontent($i, 0, $own, $scherow['gy_req_date'], $scherow['gy_emp_code'], $scherow['gy_emp_fullname'], $scherow['gy_req_status'], $scherow['gy_req_deny'], $scherow['gy_req_by'], $scherow['gy_req_to'], $scherow['gy_sup'], $scherow['gy_sched_mode'], $scherow['gy_sched_day'], $scherow['gy_sched_login'], $scherow['gy_sched_breakout'], $scherow['gy_sched_breakin'], $scherow['gy_sched_logout'], $scherow['gy_req_reason'], $scherow['msg_usercode'], $scherow['gy_sched_esc_id'], $scherow['gy_publish'], $user_code); $i++; }

                $logssql = $link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_tracker` ON `gy_escalate`.`gy_tracker_id`=`gy_tracker`.`gy_tracker_id` Where (`gy_esc_by`='$user_id' OR `gy_esc_to`='$user_id' OR `gy_sup`='$user_id' OR `gy_tracker`.`gy_emp_code`='$user_code') AND `gy_esc_status`='0' AND `gy_escalate`.`gy_tracker_date`>='$datestrt' ORDER BY `gy_esc_id` desc");
                while ($logrow=$logssql->fetch_array()){
                    $own = 0; if($logrow['gy_emp_code']==$user_code){ $own = 1; }
                    cardcontent($i, 1, $own, $logrow['gy_esc_date'], $logrow['gy_emp_code'], $logrow['gy_emp_fullname'], $logrow['gy_esc_status'], $logrow['gy_esc_deny'], $logrow['gy_esc_by'], $logrow['gy_esc_to'], $logrow['gy_sup'], $logrow['gy_esc_type'], $logrow['gy_tracker_date'], $logrow['gy_tracker_login'], $logrow['gy_tracker_breakout'], $logrow['gy_tracker_breakin'], $logrow['gy_tracker_logout'], $logrow['gy_esc_reason'], $logrow['msg_usercode'], $logrow['gy_esc_id'], $logrow['gy_publish'], $user_code); $i++; }

                //loa Pending
                $ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE (`gy_leave`.`gy_user_id`='$user_id' OR `gy_employee`.`gy_emp_supervisor`='$user_id' OR `gy_leave`.`gy_acc_id`='$myaccount') AND `gy_leave`.`gy_leave_status`=0");
                while($glsrow=$ctlsql->fetch_array()){
                    $own='Team';
                    if($glsrow['gy_user_id']==$user_id){ $own='My';  }
                    cardloacont($glsrow['gy_leave_id'], $glsrow['gy_leave_status'], $own, $glsrow['gy_emp_fullname'], $glsrow['gy_leave_filed'], get_leave_type($glsrow['gy_leave_type']), $glsrow['gy_leave_date_from'], $glsrow['gy_leave_reason'], $glsrow['gy_leave_date_approved'], getuserfullname($glsrow['gy_leave_approver']), $glsrow['gy_leave_remarks'], $glsrow['msg_usercode'], $glsrow['gy_publish'], $user_code, strtotime($today));
                }

                $schesql = $link->query("SELECT * From `gy_schedule_escalate` Where (`gy_req_by`='$user_id' OR `gy_req_to`='$user_id' OR `gy_sup`='$user_id' OR `gy_emp_code`='$user_code') AND `gy_req_status`='1' AND `gy_sched_day`>='$today' ORDER BY `gy_sched_esc_id` desc");
                while ($scherow=$schesql->fetch_array()){
                    $own = 0; if($scherow['gy_emp_code']==$user_code){ $own = 1; }
                    cardcontent($i, 0, $own, $scherow['gy_req_date'], $scherow['gy_emp_code'], $scherow['gy_emp_fullname'], $scherow['gy_req_status'], $scherow['gy_req_deny'], $scherow['gy_req_by'], $scherow['gy_req_to'], $scherow['gy_sup'], $scherow['gy_sched_mode'], $scherow['gy_sched_day'], $scherow['gy_sched_login'], $scherow['gy_sched_breakout'], $scherow['gy_sched_breakin'], $scherow['gy_sched_logout'], $scherow['gy_req_reason'], $scherow['msg_usercode'], $scherow['gy_sched_esc_id'], $scherow['gy_publish'], $user_code); $i++; }

                $logssql = $link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_tracker` ON `gy_escalate`.`gy_tracker_id`=`gy_tracker`.`gy_tracker_id` Where `gy_esc_status`='1' AND `gy_escalate`.`gy_tracker_date`>='$today' AND (`gy_tracker`.`gy_emp_code`='$user_code' OR `gy_escalate`.`gy_esc_by`='$user_id' OR `gy_escalate`.`gy_esc_to`='$user_id' OR `gy_escalate`.`gy_sup`='$user_id') ORDER BY `gy_esc_id` desc");
                while ($logrow=$logssql->fetch_array()){
                    $own = 0; if($logrow['gy_emp_code']==$user_code){ $own = 1; }
                    cardcontent($i, 1, $own, $logrow['gy_esc_date'], $logrow['gy_emp_code'], $logrow['gy_emp_fullname'], $logrow['gy_esc_status'], $logrow['gy_esc_deny'], $logrow['gy_esc_by'], $logrow['gy_esc_to'], $logrow['gy_sup'], $logrow['gy_esc_type'], $logrow['gy_tracker_date'], $logrow['gy_tracker_login'], $logrow['gy_tracker_breakout'], $logrow['gy_tracker_breakin'], $logrow['gy_tracker_logout'], $logrow['gy_esc_reason'], $logrow['msg_usercode'], $logrow['gy_esc_id'], $logrow['gy_publish'], $user_code); $i++; }


            if(date("d")>15){ $fdotco = date("Y-m-01"); }else{ $fdotco = date("Y-m-15", strtotime("-1 month")); }
                $schesql = $link->query("SELECT * From `gy_schedule_escalate` Where (`gy_req_by`='$user_id' OR `gy_req_to`='$user_id' OR `gy_sup`='$user_id' OR `gy_emp_code`='$user_code') AND `gy_req_status`='1' AND `gy_sched_day`<'$today' AND `gy_sched_day`>='$fdotco' AND `gy_publish`=0 AND `msg_usercode`!='' ORDER BY `gy_sched_esc_id` desc");
                while ($scherow=$schesql->fetch_array()){
                    $own = 0; if($scherow['gy_emp_code']==$user_code){ $own = 1; }
                    cardcontent($i, 0, $own, $scherow['gy_req_date'], $scherow['gy_emp_code'], $scherow['gy_emp_fullname'], $scherow['gy_req_status'], $scherow['gy_req_deny'], $scherow['gy_req_by'], $scherow['gy_req_to'], $scherow['gy_sup'], $scherow['gy_sched_mode'], $scherow['gy_sched_day'], $scherow['gy_sched_login'], $scherow['gy_sched_breakout'], $scherow['gy_sched_breakin'], $scherow['gy_sched_logout'], $scherow['gy_req_reason'], $scherow['msg_usercode'], $scherow['gy_sched_esc_id'], $scherow['gy_publish'], $user_code); $i++; }

                $logssql = $link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_tracker` ON `gy_escalate`.`gy_tracker_id`=`gy_tracker`.`gy_tracker_id` Where `gy_esc_status`='1' AND `gy_escalate`.`gy_tracker_date`<'$today' AND `gy_escalate`.`gy_tracker_date`>='$fdotco' AND `gy_escalate`.`gy_publish`=0 AND `gy_escalate`.`msg_usercode`!='' AND (`gy_tracker`.`gy_emp_code`='$user_code' OR `gy_escalate`.`gy_esc_by`='$user_id' OR `gy_escalate`.`gy_esc_to`='$user_id' OR `gy_escalate`.`gy_sup`='$user_id') ORDER BY `gy_esc_id` desc");
                while ($logrow=$logssql->fetch_array()){
                    $own = 0; if($logrow['gy_emp_code']==$user_code){ $own = 1; }
                    cardcontent($i, 1, $own, $logrow['gy_esc_date'], $logrow['gy_emp_code'], $logrow['gy_emp_fullname'], $logrow['gy_esc_status'], $logrow['gy_esc_deny'], $logrow['gy_esc_by'], $logrow['gy_esc_to'], $logrow['gy_sup'], $logrow['gy_esc_type'], $logrow['gy_tracker_date'], $logrow['gy_tracker_login'], $logrow['gy_tracker_breakout'], $logrow['gy_tracker_breakin'], $logrow['gy_tracker_logout'], $logrow['gy_esc_reason'], $logrow['msg_usercode'], $logrow['gy_esc_id'], $logrow['gy_publish'], $user_code); $i++; }

                //loa Approved
                $ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE (`gy_leave`.`gy_user_id`='$user_id' OR `gy_employee`.`gy_emp_supervisor`='$user_id' OR `gy_leave`.`gy_acc_id`='$myaccount') AND `gy_leave`.`gy_publish`=0 AND `gy_leave`.`gy_leave_status`=1");
                while($glsrow=$ctlsql->fetch_array()){
                    $own='Team';
                    if($glsrow['gy_user_id']==$user_id){ $own='My';  }
                    cardloacont($glsrow['gy_leave_id'], $glsrow['gy_leave_status'], $own, $glsrow['gy_emp_fullname'], $glsrow['gy_leave_filed'], get_leave_type($glsrow['gy_leave_type']), $glsrow['gy_leave_date_from'], $glsrow['gy_leave_reason'], $glsrow['gy_leave_date_approved'], getuserfullname($glsrow['gy_leave_approver']), $glsrow['gy_leave_remarks'], $glsrow['msg_usercode'], $glsrow['gy_publish'], $user_code, strtotime($today));
                }

                $schesql = $link->query("SELECT * From `gy_schedule_escalate` Where (`gy_emp_code`='$user_code' OR `gy_req_by`='$user_id' OR `gy_req_to`='$user_id' OR `gy_sup`='$user_id') AND `gy_req_status`='2' AND `gy_sched_day`>='$today' ORDER BY `gy_sched_esc_id` desc");
                while ($scherow=$schesql->fetch_array()){
                    $own = 0; if($scherow['gy_emp_code']==$user_code){ $own = 1; }
                    cardcontent($i, 0, $own, $scherow['gy_req_date'], $scherow['gy_emp_code'], $scherow['gy_emp_fullname'], $scherow['gy_req_status'], $scherow['gy_req_deny'], $scherow['gy_req_by'], $scherow['gy_req_to'], $scherow['gy_sup'], $scherow['gy_sched_mode'], $scherow['gy_sched_day'], $scherow['gy_sched_login'], $scherow['gy_sched_breakout'], $scherow['gy_sched_breakin'], $scherow['gy_sched_logout'], $scherow['gy_req_reason'], $scherow['msg_usercode'], $scherow['gy_sched_esc_id'], $scherow['gy_publish'], $user_code); $i++; }

                $logssql = $link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_tracker` ON `gy_escalate`.`gy_tracker_id`=`gy_tracker`.`gy_tracker_id` Where `gy_esc_status`='2' AND `gy_escalate`.`gy_tracker_date`>='$today' AND (`gy_tracker`.`gy_emp_code`='$user_code' OR `gy_escalate`.`gy_esc_by`='$user_id' OR `gy_escalate`.`gy_esc_to`='$user_id' OR `gy_escalate`.`gy_sup`='$user_id') ORDER BY `gy_esc_id` desc");
                while ($logrow=$logssql->fetch_array()){
                    $own = 0; if($logrow['gy_emp_code']==$user_code){ $own = 1; }
                    cardcontent($i, 1, $own, $logrow['gy_esc_date'], $logrow['gy_emp_code'], $logrow['gy_emp_fullname'], $logrow['gy_esc_status'], $logrow['gy_esc_deny'], $logrow['gy_esc_by'], $logrow['gy_esc_to'], $logrow['gy_sup'], $logrow['gy_esc_type'], $logrow['gy_tracker_date'], $logrow['gy_tracker_login'], $logrow['gy_tracker_breakout'], $logrow['gy_tracker_breakin'], $logrow['gy_tracker_logout'], $logrow['gy_esc_reason'], $logrow['msg_usercode'], $logrow['gy_esc_id'], $logrow['gy_publish'], $user_code); $i++; }

                //loa Reject
                $ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE (`gy_leave`.`gy_user_id`='$user_id' OR `gy_employee`.`gy_emp_supervisor`='$user_id' OR `gy_leave`.`gy_acc_id`='$myaccount') AND `gy_leave`.`gy_publish`=0 AND `gy_leave`.`gy_leave_status`=2");
                while($glsrow=$ctlsql->fetch_array()){
                    $own='Team';
                    if($glsrow['gy_user_id']==$user_id){ $own='My';  }
                    cardloacont($glsrow['gy_leave_id'], $glsrow['gy_leave_status'], $own, $glsrow['gy_emp_fullname'], $glsrow['gy_leave_filed'], get_leave_type($glsrow['gy_leave_type']), $glsrow['gy_leave_date_from'], $glsrow['gy_leave_reason'], $glsrow['gy_leave_date_approved'], getuserfullname($glsrow['gy_leave_approver']), $glsrow['gy_leave_remarks'], $glsrow['msg_usercode'], $glsrow['gy_publish'], $user_code, strtotime($today));
                }

                $link->close();

            function cardloacont($cardn, $reqstatus, $own, $fname, $fyldte, $loanm, $loadate, $reason, $rspdate, $rspby, $rmrks, $msguc, $publish, $ucode, $tday){ if(in_array($ucode , explode(",", $msguc))!=1 && $msguc!=""){ $newmsg = 1; }else{ $newmsg = 0; }
                        if((strtotime($loadate)>=$tday||strtotime($rspdate)>=$tday)||$newmsg==1){
                $color="dark"; $ctitle=""; $perc="0"; $finalicon='<i class="fa fa-question"></i>'; $mask="maskp";
                $lasttitle=$loadate; $lastmessage="<b>".$loanm." Reason :</b><br>".$reason;
                    if($reqstatus==0){ $color="warning"; $ctitle='<i class="fa-solid fa-chalkboard-user faa-pulse"></i> '.$own.' Pending LOA'; $perc="50"; }
                    else if($reqstatus==1){ $color="success"; $ctitle='<i class="fa-solid fa-calendar-check faa-tada"></i> '.$own.' Approved LOA'; $perc="100"; $finalicon='<i class="fa fa-check"></i>'; $lasttitle=$rspdate; if($own=="My"){$lastmessage="Approved by ".$rspby;}else if($own=="Team"){$lastmessage="You approved this LOA request";} $mask="maska"; }
                    else if($reqstatus==2){ $color="danger"; $ctitle='<i class="fa-solid fa-handshake-slash faa-shake"></i> '.$own.' Rejected LOA'; $perc="100"; $finalicon='<i class="fas fa-exclamation"></i>'; $lasttitle=$rspdate; if($own=="My"){$lastmessage="Rejected by ".$rspby."<br><br><b>Reason :</b><br>".$rmrks;}else if($own=="Team"){$lastmessage="You rejected this LOA request <br><br><b>Reason :</b><br>".$rmrks;} $mask="maskr"; }
                    else{ $ctitle='<i class="fa-solid fa-circle-xmark"></i> '.$own.' Cancelled LOA'; }
                    if($newmsg==1 && $publish==0){ $ctitle = "<i class='fa-solid fa-envelope faa-tada faa-slow'></i> Request Verification"; }
                ?>
                <div class="col-md-4">
                <div class="card hover-overlay shadow rounded">
                    <div class="card-header mask <?php if($color=="warning"){echo "bg-secondary";}else{ echo "bg-".$color; } ?>"><h4 class="text-center text-light faa-parent animated-hover"><?php echo $ctitle; ?></h4></div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                        <div class="position-relative m-4">
                            <div class="progress" style="height: 1px;">
                                <div class="progress-bar <?php echo "bg-".$color; ?>" role="progressbar" style="width: <?php echo $perc."%"; ?>;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <button type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm <?php echo "btn-".$color; ?> rounded-pill" style="width: 2rem; height:2rem;" data-bs-toggle="popover" data-bs-placement="left" title="<?php echo date("F j, Y",strtotime($fyldte)); ?>" data-bs-html="true" data-bs-content="<?php if($own=="My"){echo "I requested for a ".str_replace('"', '\\"', $loanm);}else{ echo $fname." was requesting for a ".str_replace('"', '\\"', $loanm); } ?>">
                                <i class='far fa-user'></i>
                            </button>

                            <?php if($reqstatus==1 || $reqstatus==2){ ?>
                            <button type="button" class="position-absolute top-0 start-50 translate-middle btn btn-sm <?php echo "btn-".$color; ?> rounded-pill" style="width: 2rem; height:2rem;" data-bs-toggle="popover"data-bs-placement="left" title="<?php echo date("F j, Y",strtotime($loadate)); ?>" data-bs-html="true" data-bs-content="<?php echo "<b>".str_replace('"', '\\"', $loanm)." Reason:</b><br>".str_replace('"', '\\"', $reason); ?>">
                                <i class="fa-solid fa-file-signature"></i>
                            </button>
                            <?php }else{ ?>
                            <button type="button" class="position-absolute top-0 start-50 translate-middle btn btn-sm rounded-pill <?php if($msguc!="" && $publish==0){echo "btn-".$color;}?>" <?php if($msguc!="" && $publish==0){ echo 'onclick="chat_panel('.$cardn.', \'loa\', \''.$ucode.'\')"'; }else{ ?> data-bs-toggle="popover" data-bs-placement="top" data-trigger="focus" data-bs-content="Waiting for Response" <?php } ?> >
                                <?php if($msguc=="" || $publish==1){ ?>
                                <div class="spinner-grow text-warning" role="status"></div>
                                <?php }else if($newmsg==0 && $publish==0){ ?><i class='far fa-comment-alt'></i>
                                <?php }else if($newmsg==1 && $publish==0){ ?>
                                <i class='far fa-comment-alt faa-shake animated'><span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle"><span class="visually-hidden">New alerts</span></span></i>
                                <?php } ?>
                            </button>
                            <?php } ?>

                            <button type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm <?php if($color=="warning"){echo "btn-secondary";}else{ echo "btn-".$color; } ?> rounded-pill" style="width: 2rem; height:2rem;" <?php if($reqstatus==1 && $msguc!="" && $publish==0){ echo 'onclick="chat_panel('.$cardn.', \'loa\', \''.$ucode.'\')"'; }else{ ?> data-bs-toggle="popover" data-bs-placement="right" title="<?php echo date("F j, Y",strtotime($lasttitle)); ?>" data-bs-html="true" data-bs-content="<?php echo str_replace('"', '\\"', $lastmessage); ?>" <?php } ?> >
                                <?php if($msguc=="" || $reqstatus==0){ echo $finalicon; ?>
                                <?php }else if($reqstatus==1 && $newmsg==0){ ?><i class='far fa-comment-alt'></i>
                                <?php }else if($reqstatus==1 && $newmsg==1){ ?>
                                <i class='far fa-comment-alt faa-shake animated'><span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle"><span class="visually-hidden">New alerts</span></span></i>
                                <?php } ?>
                            </button>
                        </div>
                        </li>
                    </ul>
                    <div class="card-footer <?php echo $mask; ?>">
                        <div class="pull-left text-truncate" title="The requested date"><i class='fas fa-calendar-plus'></i> <?php echo date("F d, Y", strtotime($loadate));?> </div>
                        <div class="pull-right">
                            <btn class="btn-outline-dark btn-sm" title="View this to the My Leave Calendar?" <?php echo "onclick='cnfrmmylc($cardn)'"; ?> ><i class="fa-solid fa-calendar-days"></i></btn>
                        </div>
                    </div>
                    <form method="post" id="tomylc_<?php echo $cardn; ?>" action="leavecalendar" enctype="multipart/form-data">
                        <input type="hidden" name="lcdate" value="<?php echo date("Y-m-d", strtotime($loadate));?>">
                    </form>
                </div>
                </div>
            <?php }}
            
            function cardcontent($cardn, $esct, $own, $reqdate, $empid, $fname, $reqstatus, $reqdeny, $reqby, $reqto, $sup, $schmode, $schday, $schli, $schbo, $schbi, $schlo, $reqreson, $msguc, $rfid, $publish, $ucode){
                    if(in_array($ucode , explode(",", $msguc))!=1 && $msguc!=""){ $newmsg = 1; }else{ $newmsg = 0; }
                    if($schli=="0000-00-00 00:00:00"){ $schli="00:00:00"; }
                    if($schbo=="0000-00-00 00:00:00"){ $schbo="00:00:00"; }
                    if($schbi=="0000-00-00 00:00:00"){ $schbi="00:00:00"; }
                    if($schlo=="0000-00-00 00:00:00"){ $schlo="00:00:00"; }

                    $color="dark"; $ctitle=""; $perc="0"; $finalicon = ""; $fstatus=""; $fmess="";
                    if($reqstatus==0){ $color="warning"; $ctitle="<i class='fa-solid fa-clock-rotate-left faa-wrench'></i> Pending Escalation"; $mask="maskp"; $finalicon='<i class="fa fa-question"></i>'; if($own==0){ $perc="66"; }else if($own==1){ $perc="50"; } }
                    else if($reqstatus==1){ $color="success"; $fstatus="Request Approved"; $mask="maska"; $ctitle="<i class='fa-solid fa-thumbs-up faa-bounce faa-fast'></i> Approved Escalation"; $perc="100"; $finalicon='<i class="fa fa-check"></i>'; $fmess="Acknowledged By ".getuserfullname($reqto); }
                    else if($reqstatus==2){ $color="danger"; $fstatus="Rejected By ".getuserfullname($reqto); $ctitle="<i class='fa-solid fa-thumbs-down faa-bounce faa-reverse faa-fast'></i> Rejected Escalation"; $mask="maskr"; $perc="100"; $finalicon='<i class="fas fa-exclamation"></i>'; $fmess=$reqdeny; }
                    
                    if($schmode==0){ $nlog="Rest Day"; $nbreak="Rest Day"; }
                    else if($schmode==1){ $nlog="Working ".date("h:i A", strtotime($schli))." - ".date("h:i A", strtotime($schlo)); $nbreak="Working ".date("h:i A", strtotime($schbo))." - ".date("h:i A", strtotime($schbi)); }
                    else if($schmode==2){ $nlog="RDOT ".date("h:i A", strtotime($schli))." - ".date("h:i A", strtotime($schlo)); $nbreak="RDOT ".date("h:i A", strtotime($schbo))." - ".date("h:i A", strtotime($schbi)); }
                    else if($schmode==5){ $nlog="Early Out"; $nbreak="Early Out"; }
                    else if($schmode==6){ $nlog="OT ".date("h:i A", strtotime($schli))." - ".date("h:i A", strtotime($schlo)); $nbreak="OT ".date("h:i A", strtotime($schbo))." - ".date("h:i A", strtotime($schbi)); }
                    else if($schmode==7){ $nlog="Update to ".date("h:i A", strtotime($schli))." - ".date("h:i A", strtotime($schlo)); $nbreak="Update to ".date("h:i A", strtotime($schbo))." - ".date("h:i A", strtotime($schbi)); }

                    if($esct==0 && $schmode!=2){ $escname="Schedule Adjustment (SA)"; }
                    else if($esct==0 && $schmode==2){ $escname="Rest Day Overtime (RDOT)"; }
                    else if($esct==1 && $schmode==5){ $escname="Early Out (EO)"; }
                    else if($esct==1 && $schmode==6){ $escname="Overtime (OT)"; }
                    else if($esct==1 && $schmode==7){ $escname="Missed Log (ML)"; }
                    
                    if($newmsg==1 && $publish==0){ $ctitle = "<i class='fa-solid fa-envelope faa-tada faa-slow'></i> Request Verification"; }
                ?>
                <div class="col-md-4">
                <div class="card hover-overlay shadow rounded">
                    <div class="card-header mask <?php if($color=="warning"){echo "bg-secondary";}else{ echo "bg-".$color; } ?>"><h4 class="text-center text-light faa-parent animated-hover"><?php echo $ctitle; ?></h4></div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                        <div class="position-relative m-4">
                            <div class="progress" style="height: 1px;">
                                <div class="progress-bar <?php echo "bg-".$color; ?>" role="progressbar" style="width: <?php echo $perc."%"; ?>;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <?php if($own==0){ ?>
                            <button type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm <?php echo "btn-".$color; ?> rounded-pill" style="width: 2rem; height:2rem;" data-bs-toggle="popover" data-bs-placement="left" title="Employee Requested" data-bs-content="For <?php echo $fname; ?>">
                                <i class='fas fa-street-view'></i>
                            </button>                                
                            <?php } ?>
                            <button type="button" class="position-absolute top-0 start-<?php if(($own==0 && $reqstatus==0)||($reqstatus!=0&&$msguc!=""&&$publish==0)){echo "33";}else if($own==0 && $reqstatus!=0){echo "50";}else{echo "0"; } ?> translate-middle btn btn-sm <?php echo "btn-".$color; ?> rounded-pill" style="width: 2rem; height:2rem;" data-bs-toggle="popover" data-bs-placement="top" title="<?php echo $escname; ?>" data-bs-content="Requested By <?php echo getuserfullname($reqby); ?>">
                                <i class='far fa-user'></i>
                            </button>
                            <?php if($reqstatus==0){ ?>
                            <button type="button" class="position-absolute top-0 start-<?php echo $perc; ?> translate-middle btn btn-sm rounded-pill <?php if($msguc!="" && $publish==0){echo "btn-".$color;}?> " <?php if($msguc==""||$publish==1){ ?> data-bs-toggle="popover" data-bs-placement="top" data-trigger="focus" data-bs-content="Waiting for Response" <?php } if($msguc!="" && $publish==0){ echo 'onclick="chat_panel('.$rfid.', '.$schmode.', \''.$ucode.'\')"'; } ?> >
                                <?php if($msguc=="" || $publish==1){ ?>
                                <div class="spinner-grow text-warning" role="status"></div>
                                <?php }else if($newmsg==0 && $publish==0){ ?><i class='far fa-comment-alt'></i>
                                <?php }else if($newmsg==1 && $publish==0){ ?>
                                <i class='far fa-comment-alt faa-shake animated'><span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle"><span class="visually-hidden">New alerts</span></span></i>
                                <?php } ?>
                            </button>
                            <?php } ?>
                            <button type="button" class="position-absolute top-0 start-<?php if($reqstatus!=0&&$msguc!=""&&$publish==0){echo "66";}else{echo "100"; } ?> translate-middle btn btn-sm <?php if($color=="warning"){echo "btn-secondary";}else{ echo "btn-".$color; } ?> rounded-pill" style="width: 2rem; height:2rem;" <?php if($reqstatus!=0){ ?> data-bs-toggle="popover" data-bs-placement="right" title="<?php echo $fstatus; ?>" data-bs-content="<?php echo $fmess; ?>" <?php } ?>>
                                <?php echo $finalicon; ?>
                            </button>
                            <?php if($reqstatus==1 && $msguc!="" && $publish==0){ ?>
                            <button type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm <?php if($color=="warning"){echo "btn-secondary";}else{ echo "btn-".$color; } ?> rounded-pill" style="width: 2rem; height:2rem;" <?php echo 'onclick="chat_panel('.$rfid.', '.$schmode.', \''.$ucode.'\')"'; ?> >
                                <?php if($msguc==""){ ?>
                                <div class="spinner-grow text-warning" role="status"></div>
                                <?php }else if($newmsg==0){ ?><i class='far fa-comment-alt'></i>
                                <?php }else if($newmsg==1){ ?>
                                <i class='far fa-comment-alt faa-shake animated'><span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle"><span class="visually-hidden">New alerts</span></span></i>
                                <?php } ?>
                            </button>
                            <?php } ?>
                        </div>
                        </li>
                    </ul>
                    <div class="card-footer <?php echo $mask; ?>">
                        <div class="pull-left text-truncate" title="The requested date"><i class='fas fa-calendar-plus'></i> <?php echo date("F d, Y", strtotime($schday));?> </div>
                        <div class="pull-right">
                            <span class="btn-outline-dark" style="cursor: pointer;" data-bs-toggle="popover" data-bs-placement="left" title="Timekeep Requested" data-bs-content="<?php echo $nlog; ?>"><i class='fas fa-clock'></i></span>
                            <span class="btn-outline-dark" style="cursor: pointer;" data-bs-toggle="popover" data-bs-placement="bottom" title="Breaktime Requested" data-bs-content="<?php echo $nbreak; ?>"><i class='far fa-clock'></i></span>
                            <span class="btn-outline-dark" style="cursor: pointer;" data-bs-toggle="popover" data-bs-placement="right" title="The Reason" data-bs-content="<?php echo $reqreson; ?>"><i class='fas fa-exclamation-circle'></i></span>
                            <btn class="btn-outline-dark btn-sm" title="Check on My Team?" <?php echo "onclick='cnfrmmyteam($cardn, $own)'"; ?>><i class="fas fa-paper-plane"></i></btn>
                        </div>
                    </div>
                    <form method="post" id="tomyteam_<?php echo $cardn; ?>" action="<?php if($own==0){echo "myteam";}else{echo "mydailylogs";} ?>" enctype="multipart/form-data">
                        <input type="hidden" name="dadate" value="<?php echo date("Y-m-d", strtotime($schday));?>">
                        <input type="hidden" name="daagent" value="<?php echo $empid; ?>">
                    </form>
                </div>
                </div>
            <?php } ?>
            </div>

            <?php include 'footer.php'; $link->close(); ?>
        </div>
        </div>
    </div>
    </div>
    <?php include 'scripts.php'; ?>
    <script>
    var namarr = [];
        function chat_panel(rfid, typid, ucode){
            sref();
            get_namecon(rfid, typid);
            Swal.fire({
                title: '<i class="fas fa-comments faa-wrench animated-hover"></i> Request Status Confirmation',
                html: '<div id="pnlbdy" class="panel-body"><ul class="chat" id="ulchat">'+
                '</ul></div>'+
                '<div class="panel-footer">'+
                '<div class="input-group">'+
                '<input id="msgl3l4" type="text" class="form-control input-sm" autocomplete="off" placeholder=" Type message here..." />'+
                '<span class="input-group-btn"><button class="btn btn-warning" onclick="updchtmsg('+rfid+', \''+typid+'\', \''+ucode+'\')" id="btnsndico"><i class="far fa-comment-alt"></i> Send</button></span>'+
                '</div>'+ 
                '</div>',
                showConfirmButton: false,
                showCancelButton: false,
                showCloseButton: true,
                focusConfirm: true,
                width: '600px'
            }).then((result) => {
                if(!result.isConfirmed){
                    source.close();
                    location.reload();
                }
            })
            if(typeof(EventSource) !== "undefined"){
                var source = new EventSource("msg_part/get_livedate.php");
                source.onmessage = function(event){ get_xhttp(rfid, typid, ucode); };
            }
        }

        function updchtmsg(rfid, typid, ucode){
            var msg = document.getElementById("msgl3l4").value;
            document.getElementById("btnsndico").innerHTML = "<i class=\"fa fa-spinner faa-spin faa-fast animated\"></i> Loading ...";
            document.getElementById("msgl3l4").value="";
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){ get_xhttp(rfid, typid, ucode); }
            };
            xhttp2.open("POST", "msg_part/updatemsgfile.php", true);
            xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp2.send("file="+rfid+"&typid="+typid+"&ucode="+ucode+"&msg="+msg);            
        }

        function get_xhttp(rfid, typid, ucode){
            var xhttp1 = new XMLHttpRequest();
                xhttp1.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200 && this.responseText!=""){
                            var chtmsg = "";
                            var xarr = JSON.parse(this.responseText);
                            for(var i=xarr.length-1;i>=0;i--){
                                if(xarr[i].msgby==ucode){
                                    chtmsg='<li class="left clearfix"><div class="chat-body clearfix"><div class="header"><strong class="primary-font pull-right"> '+xarr[i].msgby+' </strong> <small class="pull-left text-muted"> <i class="fa-regular fa-clock"></i> '+timesince(xarr[i].datetime)+' </small></div><br><p class="pull-right text-left">'+xarr[i].message+'</p></div></li>'+chtmsg;
                                }else{
                                    chtmsg='<li class="right clearfix"><div class="chat-body clearfix"><div class="header"><strong class="primary-font pull-left"> '+xarr[i].msgby+' </strong> <small class="pull-right text-muted"> <i class="fa-regular fa-clock"></i> '+timesince(xarr[i].datetime)+' </small></div><br><p class="pull-left text-left">'+xarr[i].message+'</p></div></li>'+chtmsg;
                                }
                            }
                                changeto_name(ucode, chtmsg);
                    }
                };
            xhttp1.open("POST", "msg_part/read_file.php", true);
            xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp1.send("file="+rfid+"&typid="+typid);   
        }

        function timesince(date){
            const d = new Date(date);
            var vdt="";
            var seconds = Math.floor((new Date() - d) / 1000);
            var interval = seconds / 31536000;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" year ago";}else{vdt=" years ago";} return Math.floor(interval)+vdt; }
            interval = seconds / 2592000;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" month ago";}else{vdt=" months ago";} return Math.floor(interval)+vdt; }
            interval = seconds / 86400;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" day ago";}else{vdt=" days ago";} return Math.floor(interval)+vdt; }
            interval = seconds / 3600;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" hour ago";}else{vdt=" hours ago";} return Math.floor(interval)+vdt; }
            interval = seconds / 60;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" minute ago";}else{vdt=" minutes ago";} return Math.floor(interval)+vdt; }
            return Math.floor(seconds) + " seconds ago";
        }

        function changeto_name(ucode, chtmsg){
            var cycid = 1;
            for(var i=0;i<namarr.length;i++){
                if(namarr[i].ucode==ucode){
                    chtmsg = replaceAll(chtmsg, '<strong class="primary-font pull-right"> '+namarr[i].ucode+' </strong>', '<strong class="primary-font pull-right"> '+namarr[i].fullname+' </strong>');
                }else{
                    chtmsg = replaceAll(chtmsg, '<strong class="primary-font pull-left"> '+namarr[i].ucode+' </strong>', '<strong class="primary-font pull-left"> '+namarr[i].fullname+' </strong>');
                }
            }
            if(chtmsg.length>document.getElementById("ulchat").innerHTML.length){ cycid=0; }
            document.getElementById("ulchat").innerHTML = chtmsg;
            if(cycid==0){
                var obdv = document.getElementById("pnlbdy");
                    obdv.scrollTop = obdv.scrollHeight;
                document.getElementById("btnsndico").innerHTML = "<i class=\"far fa-comment-alt\"></i> Send";
            }
        }

        function replaceAll(str, find, replace){
            var escapedFind=find.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
            return str.replace(new RegExp(escapedFind, 'g'), replace);
        }

        function get_namecon(rfid, typid){
            namarr = [];
            var xhttp3 = new XMLHttpRequest();
                xhttp3.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){ namarr = JSON.parse(this.responseText); }
                };
            xhttp3.open("POST", "msg_part/get_namecon.php", true);
            xhttp3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp3.send("file="+rfid+"&typid="+typid);
        }

        function cnfrmmyteam(cnum, own){
            var titletxt = 'Show this in <b>My Team</b> page?';
            if(own==1){ titletxt = 'Show this in <b>My Daily Logs</b> page?'; }
           const Toast = Swal.mixin({
                      toast: true,
                })
            Toast.fire({
                title: titletxt,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fa-solid fa-truck-fast"></i> Proceed',
                cancelButtonText: '<i class="fas fa-newspaper"></i> Stay'
            }).then((result) => {
                if(result.isConfirmed){
                    document.getElementById("tomyteam_"+cnum).submit();
                }
            })
        }
        function cnfrmmylc(cnum){
            const Toast = Swal.mixin({
                      toast: true,
                })
                Toast.fire({
                      icon: 'question',
                      title: 'View this LOA request in to the <b>My Leave Calendar</b>?',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: '<i class="fa-solid fa-calendar-day"></i> Proceed',
                      cancelButtonText: '<i class="fas fa-newspaper"></i> Stay'
                }).then((result) => {
                    if(result.isConfirmed){
                        document.getElementById("tomylc_"+cnum).submit();
                    }
                })
        }

        const mtout = setTimeout(refr, 60000);

        function refr(){ location.reload(); }
        function sref(){ clearTimeout(mtout); }

        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    </script>
</body>
</html>