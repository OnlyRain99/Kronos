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
							<i class="fas fa-newspaper faa-flash"></i>My Issue Resolution Center <?php $issue=count_issue($user_code, $user_id, $user_type)+$leavecount+$escsched+$esctime; if($issue>0){ ?><span class="position-absolute badge rounded-pill bg-danger"><?php echo $issue; ?><span class="visually-hidden">unread messages</span></span><?php } ?></a>
                    </li>
                    <li>
                        <a href="timesheet" class="faa-parent animated-hover"><i class='fas fa-address-book faa-horizontal faa-reverse'></i>My Timesheet</a>
                    </li>
					<li>
                        <a href="formdownload">
							<i class="fas fa-folder-open"></i>Download Form</a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <!-- END MENU SIDEBAR-->

    <div class="modal fade" id="profile" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
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
                        <input type="password" class="form-control" maxlength="16" name="password1" value="<?php //echo decryptIt($user_lock); ?>" placeholder="********" required>
                    </div>
                    <div class="form-group">
                        <label>Re-Type Password</label>
                        <input type="password" class="form-control" maxlength="16" name="password2" value="<?php// echo decryptIt($user_lock); ?>" placeholder="********" required>
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