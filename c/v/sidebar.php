<!-- MENU SIDEBAR-->
    <aside class="menu-sidebar d-none d-lg-block">
        <div class="logo" style="background-color: transparent;">
            <center>
            <a href="./">
                <img src="../../images/icon/kronoslyv2.png" alt="Cool Admin" />
            </a>
        </div>
        <div class="menu-sidebar__content js-scrollbar1">
            <nav class="navbar-sidebar">
                <ul class="list-unstyled navbar__list">
                    <li class="has-sub">
                        <a class="js-arrow faa-parent animated-hover" href="#" style="font-weight: bold;">
                            <i class="fas fa-user faa-tada"></i><?php echo $user_info; ?></a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li>
                                <a href="#" data-toggle="modal" data-target="#profile" class="faa-parent animated-hover"><i class="fa fa-file-text faa-horizontal"></i> My Profile</a>
                            </li>
                            <li>
                                <a href="logout" title="click to logout ..." class="faa-parent animated-hover"><i class="fa fa-chevron-right faa-passing faa-fast"></i> Sign Out</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="./" class="faa-parent animated-hover">
                            <i class="fa fa-clock faa-spin faa-fast"></i>Kronos</a>
                    </li>
                    <li>
                        <a href="mydailylogs" class="faa-parent animated-hover">
                            <i class="fas fa-folder faa-shake faa-slow"></i>My Daily Logs</a>
                    </li>
                    <li>
                        <a href="schedule" class="faa-parent animated-hover">
                            <i class="fas fa-calendar faa-burst faa-fast"></i>Schedule</a>
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
                        <a href="dtr.php" class="faa-parent animated-hover"><i class="fas fa-history faa-spin faa-reverse faa-slow"></i> Daily Time Record</a>
                    </li>
                    <li><a href="request_forms" class="faa-parent animated-hover"><i class="fa-solid fa-file-export faa-horizontal faa-fast"></i> Requested Forms</a></li>
                    <li><a href="pds" class="faa-parent animated-hover"><i class="fa-solid fa-user-tie faa-horizontal faa-reverse faa-fast"></i> Personal Data Sheet <?php $cntpds=get_newemp(); if($cntpds>0){ ?> <span class="badge badge-danger" title="New PDS Entry"><?php echo $cntpds; ?></span> <?php } ?></a>
                    </li>
                    <li><a href="logviewer" class="faa-parent animated-hover"><i class="fa-solid fa-business-time faa-bounce faa-reverse faa-fast"></i>Employee Actual Logs</a></li>
                    <?php } ?>
					<li>
                        <a href="formdownload"><i class="fas fa-folder-open"></i>Download Form</a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <!-- END MENU SIDEBAR-->

    <div class="modal fade" id="profile" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true"data-backdrop="static">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticModalLabel"><?php echo $user_info; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="update_profile?cd=<?php echo $user_id; ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <center><label>SiBS-<span style="color: blue;"><?= $user_code; ?></span></label>
                        <br><?php echo $user_accnm; ?></center>
                    </div>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" maxlength="255" name="fullname" value="<?php echo $user_info; ?>" placeholder="..." readonly>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" maxlength="16" name="username" value="<?php echo $user_sign; ?>" placeholder="Ex. juandeluna" readonly>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" maxlength="16" name="password1" value="<?php echo decryptIt($user_lock); ?>" placeholder="********" required>
                    </div>
                    <div class="form-group">
                        <label>Re-Type Password</label>
                        <input type="password" class="form-control" maxlength="16" name="password2" value="<?php echo decryptIt($user_lock); ?>" placeholder="********" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_p" class="btn btn-primary">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>