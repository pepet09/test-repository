<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

$host = "localhost";
$user = "root";
$password = "";
$database = "proposed_dpwh_repository";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");


/*
|--------------------------------------------------------------------------
| HELPER FUNCTION
|--------------------------------------------------------------------------
*/

function getCount($conn, $sql)
{
    $result = $conn->query($sql);

    if (!$result) {
        return 0;
    }

    $row = $result->fetch_assoc();

    return isset($row['total']) ? (int)$row['total'] : 0;
}


/*
|--------------------------------------------------------------------------
| DASHBOARD STATISTICS
|--------------------------------------------------------------------------
*/

/* Total Projects */
$totalProjects = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM projects"
);


/* Ongoing Projects */
$ongoingProjects = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM projects WHERE status = 'Ongoing'"
);


/* Completed Projects */
$completedProjects = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM projects WHERE status = 'Completed'"
);


/* Suspended Projects */
$suspendedProjects = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM projects WHERE status = 'Suspended'"
);


/* Total Reports */
$totalReports = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM reports"
);


/* Total Test Details */
$totalTests = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM test_details"
);


/* Pending Test Details */
$pendingTests = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM test_details
     WHERE status = 'Pending'"
);


/* Approved Test Details */
$approvedTests = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM test_details
     WHERE status = 'Approved'"
);


/* Rejected Test Details */
$rejectedTests = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM test_details
     WHERE status = 'Rejected'"
);


/* Total Users */
$totalUsers = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM users"
);


/*
|--------------------------------------------------------------------------
| PROJECT STATUS DATA
|--------------------------------------------------------------------------
*/

$statusData = [
    'Ongoing' => 0,
    'Completed' => 0,
    'Suspended' => 0
];

$statusQuery = "
    SELECT status, COUNT(*) AS total
    FROM projects
    GROUP BY status
";

$statusResult = $conn->query($statusQuery);

if ($statusResult) {

    while ($row = $statusResult->fetch_assoc()) {

        $status = $row['status'];

        if (isset($statusData[$status])) {
            $statusData[$status] = (int)$row['total'];
        }
    }
}


/*
|--------------------------------------------------------------------------
| TEST RESULT DATA
|--------------------------------------------------------------------------
*/

$testData = [
    'Pending' => 0,
    'Approved' => 0,
    'Rejected' => 0
];

$testQuery = "
    SELECT status, COUNT(*) AS total
    FROM test_details
    GROUP BY status
";

$testResult = $conn->query($testQuery);

if ($testResult) {

    while ($row = $testResult->fetch_assoc()) {

        $status = $row['status'];

        if (isset($testData[$status])) {
            $testData[$status] = (int)$row['total'];
        }
    }
}


/*
|--------------------------------------------------------------------------
| MATERIAL TYPE DATA
|--------------------------------------------------------------------------
*/

$materialLabels = [];
$materialCounts = [];

$materialQuery = "
    SELECT material_type, COUNT(*) AS total
    FROM test_details
    GROUP BY material_type
    ORDER BY total DESC
";

$materialResult = $conn->query($materialQuery);

if ($materialResult) {

    while ($row = $materialResult->fetch_assoc()) {

        $materialLabels[] = $row['material_type'];
        $materialCounts[] = (int)$row['total'];
    }
}


/*
|--------------------------------------------------------------------------
| TEST TYPE DATA
|--------------------------------------------------------------------------
*/

$testTypeLabels = [];
$testTypeCounts = [];

$testTypeQuery = "
    SELECT test_type, COUNT(*) AS total
    FROM test_details
    GROUP BY test_type
    ORDER BY total DESC
";

$testTypeResult = $conn->query($testTypeQuery);

if ($testTypeResult) {

    while ($row = $testTypeResult->fetch_assoc()) {

        $testTypeLabels[] = $row['test_type'];
        $testTypeCounts[] = (int)$row['total'];
    }
}


/*
|--------------------------------------------------------------------------
| RECENT REPORTS
|--------------------------------------------------------------------------
*/

$recentReports = [];

$recentQuery = "
    SELECT
        r.id,
        r.report_number,
        r.created_at,
        p.project_name,
        p.contractor
    FROM reports r
    LEFT JOIN projects p
        ON r.project_id = p.id
    ORDER BY r.created_at DESC
    LIMIT 10
";

$recentResult = $conn->query($recentQuery);

if ($recentResult) {

    while ($row = $recentResult->fetch_assoc()) {
        $recentReports[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Analytics - DPWH Aurora Materials Testing Repository</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        body {
            background-color: #f4f6f9;
            font-family: Arial, Helvetica, sans-serif;
        }

        .topbar {
            background-color: #4b351d;
            color: white;
            padding: 15px 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .topbar h4 {
            margin: 0;
            font-weight: 600;
        }

        .container-main {
            padding: 25px;
        }

        .page-title {
            font-weight: 700;
            color: #343a40;
        }

        .page-subtitle {
            color: #6c757d;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: 0.2s;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
        }

        .stat-icon {
            font-size: 32px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: 700;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }

        .chart-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            background: white;
        }

        .chart-card .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
        }

        .chart-container {
            position: relative;
            height: 300px;
            padding: 15px;
        }

        .table-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .table-card .card-header {
            background: white;
            font-weight: 600;
        }

        .back-button {
            text-decoration: none;
        }

        .badge-ongoing {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-completed {
            background-color: #198754;
        }

        .badge-suspended {
            background-color: #dc3545;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-approved {
            background-color: #198754;
        }

        .badge-rejected {
            background-color: #dc3545;
        }

    </style>

</head>

<body>


<!-- TOP BAR -->
<div class="topbar">

    <div class="container-fluid">

        <h4>
            <i class="fas fa-chart-line me-2"></i>
            DPWH Aurora Materials Testing Repository
        </h4>

    </div>

</div>


<!-- MAIN CONTENT -->
<div class="container-fluid container-main">


    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="page-title">
                Analytics
            </h2>

            <p class="page-subtitle mb-0">
                Repository and materials testing records overview
            </p>

        </div>

        <div>

            <a href="dashboard.php"
               class="btn btn-secondary back-button">

                <i class="fas fa-arrow-left me-1"></i>

                Back to Dashboard

            </a>

        </div>

    </div>


    <!-- STATISTICS -->

    <div class="row g-4 mb-4">


        <!-- TOTAL PROJECTS -->
        <div class="col-xl-3 col-md-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-label">
                                TOTAL PROJECTS
                            </div>

                            <div class="stat-number">
                                <?php echo number_format($totalProjects); ?>
                            </div>

                        </div>

                        <div class="stat-icon text-primary">

                            <i class="fas fa-building"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- TOTAL REPORTS -->
        <div class="col-xl-3 col-md-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-label">
                                MATERIAL TEST REPORTS
                            </div>

                            <div class="stat-number">
                                <?php echo number_format($totalReports); ?>
                            </div>

                        </div>

                        <div class="stat-icon text-success">

                            <i class="fas fa-file-pdf"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- TEST DETAILS -->
        <div class="col-xl-3 col-md-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-label">
                                TEST RECORDS
                            </div>

                            <div class="stat-number">
                                <?php echo number_format($totalTests); ?>
                            </div>

                        </div>

                        <div class="stat-icon text-info">

                            <i class="fas fa-flask"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- USERS -->
        <div class="col-xl-3 col-md-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-label">
                                SYSTEM USERS
                            </div>

                            <div class="stat-number">
                                <?php echo number_format($totalUsers); ?>
                            </div>

                        </div>

                        <div class="stat-icon text-secondary">

                            <i class="fas fa-users"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- SECOND ROW -->

    <div class="row g-4 mb-4">


        <!-- ONGOING -->
        <div class="col-md-4">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-label">
                                ONGOING PROJECTS
                            </div>

                            <div class="stat-number text-warning">
                                <?php echo number_format($ongoingProjects); ?>
                            </div>

                        </div>

                        <div class="stat-icon text-warning">

                            <i class="fas fa-spinner"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- COMPLETED -->
        <div class="col-md-4">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-label">
                                COMPLETED PROJECTS
                            </div>

                            <div class="stat-number text-success">
                                <?php echo number_format($completedProjects); ?>
                            </div>

                        </div>

                        <div class="stat-icon text-success">

                            <i class="fas fa-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- SUSPENDED -->
        <div class="col-md-4">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-label">
                                SUSPENDED PROJECTS
                            </div>

                            <div class="stat-number text-danger">
                                <?php echo number_format($suspendedProjects); ?>
                            </div>

                        </div>

                        <div class="stat-icon text-danger">

                            <i class="fas fa-pause-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- CHARTS -->

    <div class="row g-4 mb-4">


        <!-- PROJECT STATUS -->
        <div class="col-lg-6">

            <div class="card chart-card">

                <div class="card-header">

                    <i class="fas fa-chart-pie me-2"></i>

                    Project Status

                </div>

                <div class="chart-container">

                    <canvas id="projectStatusChart"></canvas>

                </div>

            </div>

        </div>


        <!-- TEST STATUS -->
        <div class="col-lg-6">

            <div class="card chart-card">

                <div class="card-header">

                    <i class="fas fa-chart-doughnut me-2"></i>

                    Materials Testing Status

                </div>

                <div class="chart-container">

                    <canvas id="testStatusChart"></canvas>

                </div>

            </div>

        </div>

    </div>


    <!-- MATERIAL / TEST TYPE -->

    <div class="row g-4 mb-4">


        <!-- MATERIAL TYPES -->
        <div class="col-lg-6">

            <div class="card chart-card">

                <div class="card-header">

                    <i class="fas fa-layer-group me-2"></i>

                    Test Records by Material Type

                </div>

                <div class="chart-container">

                    <canvas id="materialChart"></canvas>

                </div>

            </div>

        </div>


        <!-- TEST TYPES -->
        <div class="col-lg-6">

            <div class="card chart-card">

                <div class="card-header">

                    <i class="fas fa-vial me-2"></i>

                    Test Records by Test Type

                </div>

                <div class="chart-container">

                    <canvas id="testTypeChart"></canvas>

                </div>

            </div>

        </div>

    </div>


    <!-- TEST SUMMARY -->

    <div class="card table-card mb-4">

        <div class="card-header">

            <i class="fas fa-flask me-2"></i>

            Materials Testing Summary

        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-4">

                    <h3>
                        <?php echo number_format($pendingTests); ?>
                    </h3>

                    <span class="badge badge-pending">
                        Pending
                    </span>

                </div>

                <div class="col-md-4">

                    <h3>
                        <?php echo number_format($approvedTests); ?>
                    </h3>

                    <span class="badge badge-approved">
                        Approved
                    </span>

                </div>

                <div class="col-md-4">

                    <h3>
                        <?php echo number_format($rejectedTests); ?>
                    </h3>

                    <span class="badge badge-rejected">
                        Rejected
                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- RECENT REPORTS -->

    <div class="card table-card">

        <div class="card-header">

            <i class="fas fa-clock me-2"></i>

            Recent Materials Testing Reports

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Report Number</th>

                            <th>Project</th>

                            <th>Contractor</th>

                            <th>Date Uploaded</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (count($recentReports) > 0): ?>

                        <?php $counter = 1; ?>

                        <?php foreach ($recentReports as $report): ?>

                            <tr>

                                <td>
                                    <?php echo $counter++; ?>
                                </td>

                                <td>
                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $report['report_number']
                                        );
                                        ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $report['project_name'] ?? 'N/A'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $report['contractor'] ?? 'N/A'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $report['created_at']
                                    );
                                    ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="5"
                                class="text-center text-muted py-4">

                                No reports found.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


</div>


<!-- CHART SCRIPTS -->

<script>

    /*
    |--------------------------------------------------------------------------
    | PROJECT STATUS CHART
    |--------------------------------------------------------------------------
    */

    const projectStatusCtx =
        document.getElementById('projectStatusChart');

    new Chart(projectStatusCtx, {

        type: 'doughnut',

        data: {

            labels: [
                'Ongoing',
                'Completed',
                'Suspended'
            ],

            datasets: [{

                data: [
                    <?php echo $statusData['Ongoing']; ?>,
                    <?php echo $statusData['Completed']; ?>,
                    <?php echo $statusData['Suspended']; ?>
                ],

                borderWidth: 1

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    position: 'bottom'
                }

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TEST STATUS CHART
    |--------------------------------------------------------------------------
    */

    const testStatusCtx =
        document.getElementById('testStatusChart');

    new Chart(testStatusCtx, {

        type: 'doughnut',

        data: {

            labels: [
                'Pending',
                'Approved',
                'Rejected'
            ],

            datasets: [{

                data: [
                    <?php echo $testData['Pending']; ?>,
                    <?php echo $testData['Approved']; ?>,
                    <?php echo $testData['Rejected']; ?>
                ],

                borderWidth: 1

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    position: 'bottom'
                }

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | MATERIAL TYPE CHART
    |--------------------------------------------------------------------------
    */

    const materialCtx =
        document.getElementById('materialChart');

    new Chart(materialCtx, {

        type: 'bar',

        data: {

            labels:
                <?php echo json_encode($materialLabels); ?>,

            datasets: [{

                label: 'Number of Test Records',

                data:
                    <?php echo json_encode($materialCounts); ?>,

                borderWidth: 1

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    }

                }

            },

            plugins: {

                legend: {
                    display: false
                }

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TEST TYPE CHART
    |--------------------------------------------------------------------------
    */

    const testTypeCtx =
        document.getElementById('testTypeChart');

    new Chart(testTypeCtx, {

        type: 'bar',

        data: {

            labels:
                <?php echo json_encode($testTypeLabels); ?>,

            datasets: [{

                label: 'Number of Test Records',

                data:
                    <?php echo json_encode($testTypeCounts); ?>,

                borderWidth: 1

            }]

        },

        options: {

            indexAxis: 'y',

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                x: {

                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    }

                }

            },

            plugins: {

                legend: {
                    display: false
                }

            }

        }

    });

</script>


</body>

</html>

<?php

$conn->close();

?>