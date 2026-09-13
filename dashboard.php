<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include "config/database.php";
include "includes/header.php";
include "includes/navbar.php";
include "includes/sidebar.php";

// Fetch Statistical Metric Totals
$total_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports"))['total'];
$pending_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports WHERE status='Pending'"))['total'];
$approved_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports WHERE status='Approved'"))['total'];
$rejected_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports WHERE status='Rejected'"))['total'];

$current_role = isset($_SESSION['role']) ? strtolower($_SESSION['role']) : '';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Operations Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <!-- Statistics Metric Display Matrix -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner"><h3><?= $total_reports; ?></h3><p>Total Materials Reports</p></div>
                        <div class="icon"><i class="fas fa-folder-open"></i></div>
                        <a href="/proposed_dpwh_repository/reports/index.php" class="small-box-footer">View Repository &rarr;</a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner"><h3><?= $pending_reports; ?></h3><p>Pending Verification</p></div>
                        <div class="icon"><i class="fas fa-clock"></i></div>
                        <a href="/proposed_dpwh_repository/reports/index.php" class="small-box-footer">More Info &rarr;</a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner"><h3><?= $approved_reports; ?></h3><p>Approved Batches</p></div>
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                        <a href="/proposed_dpwh_repository/reports/index.php" class="small-box-footer">More Info &rarr;</a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner"><h3><?= $rejected_reports; ?></h3><p>Rejected Quality Failures</p></div>
                        <div class="icon"><i class="fas fa-times-circle"></i></div>
                        <a href="/proposed_dpwh_repository/reports/index.php" class="small-box-footer">More Info &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Dynamic Quick Actions Segment Matrix -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Available Administrative Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-3">
                                
                                <a href="/proposed_dpwh_repository/reports/index.php" class="btn btn-info p-3 m-2">
                                    <i class="fas fa-search fa-2x d-block mb-2"></i> Retrieve Reports
                                </a>

                                <!-- Upload Access Constraint Check -->
                                <?php if ($current_role !== 'laboratory technician' && $current_role !== 'lab tech'): ?>
                                <a href="/proposed_dpwh_repository/reports/add.php" class="btn btn-primary p-3 m-2">
                                    <i class="fas fa-upload fa-2x d-block mb-2"></i> Upload New Report
                                </a>
                                <?php endif; ?>

                                <!-- User Management Directory - Protected for Chief Profile Access ONLY -->
                                <?php if ($current_role === 'chief'): ?>
                                <a href="/proposed_dpwh_repository/users/index.php" class="btn btn-danger p-3 m-2">
                                    <i class="fas fa-users fa-2x d-block mb-2"></i> User Directory
                                </a>
                                <a href="/proposed_dpwh_repository/activity_logs/index.php" class="btn btn-secondary p-3 m-2">
                                    <i class="fas fa-history fa-2x d-block mb-2"></i> System Activity Logs
                                </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Materials Test Reports Preview Block -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Uploaded PDF Files</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped m-0">
                                <thead>
                                    <tr>
                                        <th>Report No.</th>
                                        <th>Date Uploaded</th>
                                        <th>Status Badge</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $recent_query = mysqli_query($conn, "SELECT report_number, date_uploaded, status FROM reports ORDER BY created_at DESC LIMIT 5");
                                    if (mysqli_num_rows($recent_query) > 0):
                                        while ($recent = mysqli_fetch_assoc($recent_query)):
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($recent['report_number']); ?></td>
                                        <td><?= htmlspecialchars($recent['date_uploaded']); ?></td>
                                        <td>
                                            <?php if($recent['status'] == "Approved"): ?>
                                                <span class="badge badge-success">Approved</span>
                                            <?php elseif($recent['status'] == "Rejected"): ?>
                                                <span class="badge badge-danger">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr><td colspan="3" class="text-center text-muted p-3">No uploaded document entries logged yet.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<?php include "includes/footer.php"; ?>
