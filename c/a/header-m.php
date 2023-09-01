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
                    <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                        <li>
                            <a href="#" data-toggle="modal" data-target="#profile"><i class="fa fa-file-text"></i> My Profile</a>
                        </li>
                        <li>
                            <a href="logout" title="click to logout ..."><i class="fa fa-chevron-right"></i> Logout</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="./">
                        <i class="fas fa-clock"></i>Time Logs</a>
                </li>
                <li>
                    <a href="masterlist">
                        <i class="fa fa-list"></i>Masterlist</a>
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
                            <i class="fas fa-briefcase"></i>Company Accounts</a>
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
        </div>
    </nav>
</header>
<!-- END HEADER MOBILE-->