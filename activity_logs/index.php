<?php
session_start();

// Strict Access Constraint Guard Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Security Check: Section Chief profile credentials required
if (isset($_SESSION['role']) && strtolower($_SESSION['role']) !== 'chief') {
    echo "<script>alert('Access Denied. Authorized Section Chief profile credentials required.'); window.location='../dashboard.php';</script>";
    exit();
}

// Keep error reporting on for debugging stability
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config/database.php";
include "../includes/header.php";
include "../includes/navbar.php";
include "../includes/sidebar.php";

// --- SMART SCHEMA DETECTOR FOR REPOSITORY AUDITS ---
$columns_query = mysqli_query($conn, "SHOW COLUMNS FROM activity_logs");
$columns = [];
while ($row = mysqli_fetch_assoc($columns_query)) {
    $columns[] = strtolower($row['Field']);
}

// Determine best matching fallback keys based on typical structures
$id_key     = in_array('id', $columns) ? 'id' : (isset($columns[0]) ? $columns[0] : 'id');
$time_key   = in_array('created_at', $columns) ? 'created_at' : (in_array('timestamp', $columns) ? 'timestamp' : (in_array('log_time', $columns) ? 'log_time' : (in_array('date_logged', $columns) ? 'date_logged' : '')));
$name_key   = in_array('fullname', $columns) ? 'fullname' : (in_array('name', $columns) ? 'name' : (in_array('user_id', $columns) ? 'user_id' : (in_array('username', $columns) ? 'username' : '')));
$action_key = in_array('action_performed', $columns) ? 'action_performed' : (in_array('action', $columns) ? 'action' : (in_array('activity', $columns) ? 'activity' : (in_array('details', $columns) ? 'details' : '')));
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-history text-secondary mr-2"></i>System Activity Logs</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">User Audit Trail Registry Logs</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover m-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Log ID</th>
                                <th>Timestamp</th>
                                <th>Staff Name / ID</th>
                                <th>Action Logged</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Check if the name key is pointing to user_id to perform a database join
                            if ($name_key === 'user_id') {
                                $query_string = "SELECT al.*, u.fullname AS staff_name 
                                                 FROM activity_logs al 
                                                 LEFT JOIN users u ON al.user_id = u.id 
                                                 ORDER BY al.$id_key DESC LIMIT 50";
                            } else {
                                $query_string = "SELECT * FROM activity_logs ORDER BY $id_key DESC LIMIT 50";
                            }

                            $log_result = mysqli_query($conn, $query_string);

                            if ($log_result && mysqli_num_rows($log_result) > 0):
                                while ($log_row = mysqli_fetch_assoc($log_result)):
                                    $raw_id     = isset($log_row[$id_key]) ? $log_row[$id_key] : '0';
                                    $raw_time   = (!empty($time_key) && isset($log_row[$time_key])) ? $log_row[$time_key] : 'Recent';
                                    $raw_action = (!empty($action_key) && isset($log_row[$action_key])) ? $log_row[$action_key] : 'No details provided';
                                    
                                    // If we pulled the staff name using our LEFT JOIN, display it. Otherwise, use standard column content.
                                    if (isset($log_row['staff_name']) && !empty($log_row['staff_name'])) {
                                        $raw_name = $log_row['staff_name'];
                                    } else {
                                        $raw_name = (!empty($name_key) && isset($log_row[$name_key])) ? $log_row[$name_key] : 'System Event';
                                    }
                            ?>
                            <tr>
                                <td>#<?= htmlspecialchars((string)$raw_id); ?></td>
                                <td>
                                    <span class="badge badge-light text-muted">
                                        <i class="far fa-clock mr-1"></i><?= htmlspecialchars((string)$raw_time); ?>
                                    </span>
                                </td>
                                <td><strong><?= htmlspecialchars((string)$raw_name); ?></strong></td>
                                <td><?= htmlspecialchars((string)$raw_action); ?></td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted p-4">
                                    <i class="fas fa-info-circle fa-2x d-block mb-2 text-gray"></i>
                                    No system audit activity entries logged in the database yet.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php 
include "../includes/footer.php"; 
?>
