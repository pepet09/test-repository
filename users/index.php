<?php

session_start();

// Security check: Only allow the Chief to manage user profiles
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Chief') {
    header("Location: ../dashboard.php");
    exit();
}

include "../config/database.php";
include "../includes/header.php";
include "../includes/navbar.php";
include "../includes/sidebar.php";

// Fetch all registered users from the database
$query = mysqli_query($conn, "SELECT id, username, fullname, role FROM users ORDER BY fullname ASC");

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>User Accounts Directory</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Registered DPWH Personnel Profiles</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Designation Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['fullname']); ?></td>
                                    <td><?= htmlspecialchars($row['username']); ?></td>
                                    <td>
                                        <?php 
                                        if ($row['role'] === 'Administrator') {
                                            echo '<span class="badge badge-dark">Administrator</span>';
                                        } elseif ($row['role'] === 'Chief') {
                                            echo '<span class="badge badge-danger">Chief Engineer</span>';
                                        } elseif ($row['role'] === 'Materials Engineer') {
                                            echo '<span class="badge badge-primary">Materials Engineer</span>';
                                        } else {
                                            echo '<span class="badge badge-secondary">Laboratory Technician</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
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