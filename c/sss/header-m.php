<!-- HEADER MOBILE-->
<header class="header-mobile d-block d-lg-none">
    <div class="header-mobile__bar">
        <div class="container-fluid">
            <div class="header-mobile-inner">
                <a class="logo" href="./" style="width: 90%;">
                    <img src="../../images/icon/kronoslyv2.png" alt="CoolAdmin" />
                </a>
                <button class="hamburger hamburger--slider" type="button">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <nav class="navbar-mobile">
        <div class="container-fluid">
            <ul class="navbar-mobile__list list-unstyled">
                <li class="has-sub">
                        <a class="js-arrow" href="#" style="font-weight: bold;">
                            <i class="fas fa-user"></i><?php echo $user_info; ?></a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li>
                                <a href="#" data-toggle="modal" data-target="#profile"><i class="fa fa-file-text"></i> My Profile</a>
                            </li>
                            <li>
                                <a href="logout" title="click to logout ..."><i class="fa fa-chevron-right"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="./" class="faa-parent animated-hover">
                            <i class="fa fa-clock faa-spin"></i>My Kronos</a>
                    </li>
                    <li>
                        <a href="mydailylogs" class="faa-parent animated-hover">
                            <i class="fas fa-folder faa-shake"></i>My Daily Logs</a>
                    </li>
                    <li>
                        <a href="myschedule" class="faa-parent animated-hover">
                            <i class="fas fa-calendar faa-ring"></i>My Schedule</a>
                    </li>
                    <li>
                        <a href="status" class="faa-parent animated-hover">
                            <i class="fas fa-group faa-pulse"></i>MyTeam Status</a>
                    </li>
                    <li><?php $leavecount = get_leave_pending_requests(getall_leave_level3($user_id))+get_leave_pending_requests($user_id); ?>
                        <a href="leavecalendar" class="faa-parent animated-hover" id="sbid-mlcntf"><i class="fa-solid fa-calendar-day faa-passing faa-reverse"></i> My Leave Calendar <?php if($leavecount>0){ ?><span class="badge badge-danger" title="<?php echo $leavecount." LOA pending request"; ?>"><?php echo $leavecount; ?></span><?php } ?></a>
                    </li>
                    <li class="has-sub">
                        <a class="js-arrow faa-parent animated-hover" href="#"><i class="fas fa-upload faa-vertical"></i> Upload File</a>
                        <ul class="navbar-mobile-sub__list list-unstyled navbar__sub-list js-sub-list" <?php if($title=="Upload Schedules"||$title=="Upload DTR"){ echo "style='display: block;'"; } ?>>
                            <li>
                                <a class="faa-parent animated-hover" href="upload_sched"><i class="fa-solid fa-calendar-plus faa-burst"></i> Upload Schedule</a>
                            </li>
                            <li>
                                <a class="faa-parent animated-hover" href="upload_lilo"><i class="fa-solid fa-business-time faa-flash"></i> Upload DTR</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <?php $esctime = count_escalate($user_id); ?>
                        <a class="js-arrow faa-parent animated-hover" href="#">
                            <i class="fas fa-arrow-circle-up faa-float"></i>Escalated Timekeep <?php if($esctime>0){ ?><span class="badge badge-danger"><?php echo $esctime; ?></span><?php } ?></a>
                        <ul class="navbar-mobile-sub__list list-unstyled navbar__sub-list js-sub-list" <?php if($title=="Escalate Requests" || $title=="Escalate Request History"){ echo "style='display: block;'"; } ?>>
                            <li>
                                <a href="esc_summary" class="faa-parent animated-hover"><i class="fa fa-arrow-circle-up faa-vertical"></i> Requests <?php if($esctime>0){ ?><span class="badge badge-danger"><?php echo $esctime; ?></span><?php } ?></a>
                            </li>
                            <li>
                                <a href="esc_history" class="faa-parent animated-hover"><i class="fa fa-folder-open faa-ring"></i> History</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <?php $escsched = count_sched_escalate($user_id); ?>
                        <a class="js-arrow faa-parent animated-hover" href="#">
                            <i class="fas fa-arrow-circle-up faa-float"></i>Escalated Schedule <?php if($escsched>0){ ?><span class="badge badge-danger"><?php echo $escsched; ?></span><?php } ?></a>
                        <ul class="navbar-mobile-sub__list list-unstyled navbar__sub-list js-sub-list" <?php if($title=="Schedule Escalate Requests" || $title=="Escalate Schedule Summary"){ echo "style='display: block;'"; } ?>>
                            <li>
                                <a href="sched_esc_summary" class="faa-parent animated-hover"><i class="fa fa-arrow-circle-up faa-vertical"></i> Requests <?php if($escsched>0){ ?><span class="badge badge-danger"><?php echo $escsched; ?></span><?php } ?></a>
                            </li>
                            <li>
                                <a href="sched_esc_history" class="faa-parent animated-hover"><i class="fa fa-folder-open faa-ring"></i> History</a>
                            </li>
                        </ul>
                    </li>
					<li>
                        <a href="issueresolution_center" class="faa-parent animated-hover" id="sbid-ircntf">
							<i class="fas fa-newspaper faa-flash"></i>My Issue Resolution Center <?php $issue=count_issue($user_code, $user_id, $user_type)+$leavecount; if($issue>0){ ?><span class="position-absolute badge rounded-pill bg-danger"><?php echo $issue; ?><span class="visually-hidden">unread messages</span></span><?php } ?></a>
                    </li>
                    <li>
                        <a href="timesheet" class="faa-parent animated-hover"><i class='fas fa-address-book faa-horizontal faa-reverse'></i>My Timesheet</a>
                    </li>
            </ul>
        </div>
    </nav>
</header>
<!-- END HEADER MOBILE-->