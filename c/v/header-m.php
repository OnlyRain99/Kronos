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
                            <i class="fa fa-clock faa-spin faa-fast"></i>My Kronos</a>
                    </li>
                    <li>
                        <a href="mydailylogs" class="faa-parent animated-hover">
                            <i class="fas fa-folder faa-shake faa-slow"></i>My Daily Logs</a>
                    </li>
                    <li>
                        <a href="schedule" class="faa-parent animated-hover">
                            <i class="fas fa-calendar faa-burst faa-fast"></i>My Schedule</a>
                    </li>
                    <li><?php $leavecount = get_lv5plusleave($user_id, $myaccount, $_SESSION['fus_user_type'])+get_leave_pending_requests($user_id); ?>
                        <a href="leavecalendar" class="faa-parent animated-hover" id="sbid-mlcntf"><i class="fa-solid fa-calendar-day faa-pulse"></i> My Leave Calendar <?php if($leavecount>0){ ?><span class="badge badge-danger" title="<?php echo $leavecount." LOA pending request"; ?>"><?php echo $leavecount; ?></span><?php } ?></a>
                    </li>
                    <li>
                        <a href="myteam" class="faa-parent animated-hover">
                            <i class="fas fa-paper-plane faa-pulse"></i>My Team</a>
                    </li>
					<li>
                        <a href="issueresolution_center" class="faa-parent animated-hover" id="sbid-ircntf">
							<i class="fas fa-newspaper faa-flash"></i>My Issue Resolution Center <?php $issue=count_issue($user_code, $user_id, $user_type)+$leavecount; if($issue>0){ ?><span class="position-absolute badge rounded-pill bg-danger"><?php echo $issue; ?><span class="visually-hidden">unread messages</span></span><?php } ?></a>
                    </li>
                    <?php  if ($user_type == 5 && $user_dept == 2) { ?>
                    <li class="has-sub">
                        <a class="js-arrow faa-parent animated-hover" href="#">
                            <i class="fas fa-user faa-pulse faa-slow"></i>Announcements</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list" <?php if($title=="Announcements" || $title=="Archieve"){ echo "style='display: block;'"; } ?>>
                            <li>
                                <a href="recent" class="faa-parent animated-hover"><i class="fa fa-file-text faa-bounce faa-fast"></i> Recent</a>
                            </li>
                            <li>
                                <a href="archieve" class="faa-parent animated-hover"><i class="fa fa-folder-open faa-bounce faa-reverse faa-fast"></i> Archieve</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="dtr" class="faa-parent animated-hover"><i class="fas fa-history faa-spin faa-reverse faa-slow"></i> Daily Time Record</a>
                    </li>
                    <li><a href="pds" class="faa-parent animated-hover"><i class="fa-solid fa-user-tie faa-horizontal faa-reverse faa-fast"></i> Personal Data Sheet <?php $cntpds=get_newemp(); if($cntpds>0){ ?> <span class="badge badge-danger" title="New PDS Entry"><?php echo $cntpds; ?></span> <?php } ?></a>
                    </li>
                    <li><a href="logviewer" class="faa-parent animated-hover"><i class="fa-solid fa-business-time faa-bounce faa-reverse faa-fast"></i>Employee Actual Logs</a></li>
                    <?php } ?>
            </ul>
        </div>
    </nav>
</header>
<!-- END HEADER MOBILE-->