<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">

        <li class="nav-item">

            <a class="nav-link"
               data-widget="pushmenu"
               href="#"
               role="button">

                <i class="fas fa-bars"></i>

            </a>

        </li>

        <li class="nav-item d-none d-sm-inline-block">

            <a href="/proposed_dpwh_repository/dashboard.php"
               class="nav-link">

                Dashboard

            </a>

        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Date -->
        <li class="nav-item">

            <span class="nav-link text-secondary">

                <i class="far fa-calendar-alt"></i>

                <?= date("F d, Y"); ?>

            </span>

        </li>

        <!-- Logged-in User -->
        <li class="nav-item dropdown">

            <a class="nav-link"
               data-toggle="dropdown"
               href="#">

                <i class="fas fa-user-circle"></i>

                <strong>

                    <?= isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : "Guest"; ?>

                </strong>

            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <span class="dropdown-header">

                    Logged in as

                </span>

                <div class="dropdown-divider"></div>

                <div class="dropdown-item">

                    <strong>Name:</strong>

                    <?= isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : "-"; ?>

                </div>

                <div class="dropdown-item">

                    <strong>Username:</strong>

                    <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "-"; ?>

                </div>

                <div class="dropdown-item">

                    <strong>Role:</strong>

                    <?= isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : "-"; ?>

                </div>

                <div class="dropdown-divider"></div>

                <a href="/proposed_dpwh_repository/logout.php"
                   class="dropdown-item text-danger">

                    <i class="fas fa-sign-out-alt mr-2"></i>

                    Logout

                </a>

            </div>

        </li>

    </ul>

</nav>