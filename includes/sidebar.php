<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 100vh;">

    <!-- Brand Logo Container Header -->
    <a href="/proposed_dpwh_repository/dashboard.php" class="brand-link" style="display: flex; flex-direction: column; align-items: center; text-align: center; white-space: normal; padding: 25px 15px; height: auto; border-bottom: 1px solid #4b545c;">

        <!-- Scaled up x2 larger to 85px with a beautiful white circular backing card -->
        <div style="background: #ffffff; padding: 6px; border-radius: 50%; box-shadow: 0 4px 8px rgba(0,0,0,0.2); width: 85px; height: 85px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">

            <!-- FIXED LOGO PATH -->
            <img src="/proposed_dpwh_repository/assets/images/dpwh-logo.png"
                 alt="DPWH Logo"
                 style="width: 75px; height: 75px; object-fit: contain; opacity: 1;">

        </div>

        <!-- Clean, bold text headers centered beneath the x2 enlarged gear logo -->
        <span class="brand-text" style="line-height: 1.3; display: block; width: 100%;">

            <b style="font-weight: 700; display: block; font-size: 19px; letter-spacing: 0.5px; color: #ffffff;">
                DPWH Aurora
            </b>

            <span style="font-size: 13.5px; color: #d2d7df; display: block; font-weight: 400; margin-top: 3px; letter-spacing: 0.2px;">
                Materials Repository
            </span>

        </span>

    </a>

    <!-- Sidebar Navigation Contents -->
    <div class="sidebar">

        <!-- Sidebar Menu Navigation tree -->
        <nav class="mt-4">

            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                <!-- Dashboard Link -->
                <li class="nav-item">

                    <a href="/proposed_dpwh_repository/dashboard.php" class="nav-link">

                        <i class="nav-icon fas fa-home"></i>

                        <p>Dashboard</p>

                    </a>

                </li>

                <!-- Repository Document Database Link -->
                <li class="nav-item">

                    <a href="/proposed_dpwh_repository/reports/index.php" class="nav-link">

                        <i class="nav-icon fas fa-folder-open"></i>

                        <p>Repository</p>

                    </a>

                </li>

                <!-- Upload Report Entry Form -->
                <?php if (strtolower($_SESSION['role']) !== 'laboratory technician'): ?>

                <li class="nav-item">

                    <a href="/proposed_dpwh_repository/reports/add.php" class="nav-link">

                        <i class="nav-icon fas fa-upload"></i>

                        <p>Upload Report</p>

                    </a>

                </li>

                <?php endif; ?>

                <!-- Project Profiles Registry Link -->
                <li class="nav-item">

                    <a href="/proposed_dpwh_repository/projects/index.php" class="nav-link">

                        <i class="nav-icon fas fa-road"></i>

                        <p>Projects</p>

                    </a>

                </li>

                <!-- System User Accounts Directory Management Panel -->
                <?php if (strtolower($_SESSION['role']) === 'chief'): ?>

                <li class="nav-item">

                    <a href="/proposed_dpwh_repository/users/index.php" class="nav-link">

                        <i class="nav-icon fas fa-users"></i>

                        <p>Users</p>

                    </a>

                </li>

                <?php endif; ?>

                <!-- System Audit Activity Logs -->
                <?php if (strtolower($_SESSION['role']) === 'chief'): ?>

                <li class="nav-item">

                    <a href="/proposed_dpwh_repository/activity_logs/index.php" class="nav-link">

                        <i class="nav-icon fas fa-history"></i>

                        <p>Activity Logs</p>

                    </a>

                </li>

                <?php endif; ?>

                <!-- Advanced Operational Analytics -->
                <li class="nav-item">

                    <a href="/proposed_dpwh_repository/reports/analytics.php" class="nav-link">

                        <i class="nav-icon fas fa-chart-bar"></i>

                        <p>Analytics</p>

                    </a>

                </li>

            </ul>

        </nav>

    </div>

</aside>