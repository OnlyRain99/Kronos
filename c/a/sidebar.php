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
                        <a class="js-arrow" href="#" style="font-weight: bold;">
                            <i class="fas fa-user"></i><?php echo $user_info; ?></a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li>
                                <a href="#" data-toggle="modal" data-target="#profile"><i class="fa fa-file-text"></i> My Profile</a>
                            </li>
                            <li>
                                <a href="logout" title="click to logout ..."><i class="fa fa-chevron-right"></i> Sign Out</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="./">
                            <i class="fas fa-clock"></i>Time Logs</a>
                    </li>
                    <li>
                        <a href="masterlist">
                            <i class="fa fa-list"></i>Masterlogs</a>
                    </li>
                    <li>
                        <a href="stats">
                            <i class="fas fa-folder"></i>Employee Records</a>
                    </li>
                    <li>
                        <a href="users">
                            <i class="fas fa-unlock"></i>System Users</a>
                    </li>
                    <li>
                        <a href="accounts">
                            <i class="fas fa-briefcase"></i>Company Division</a>
                    </li>
                    <li>
                        <a href="whitelisting">
                            <i class="fas fa-compass"></i>Whitelisting</a>
                    </li>
                    <li>
                        <a href="logs">
                            <i class="fas fa-globe"></i>System Logs</a>
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
                        <center><label>SiBS<span style="color: blue;"><?= sibsid($user_code); ?></span></label></center>
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
                        <input type="password" class="form-control" maxlength="16" name="password2" value="<?php //echo decryptIt($user_lock); ?>" placeholder="********" required>
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