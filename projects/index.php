<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);


// ==========================================================
// HANDLE ADD / EDIT / DELETE
// ==========================================================

// DELETE
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    if ($id > 0) {

        $sql = "DELETE FROM projects WHERE id = $id LIMIT 1";

        if (mysqli_query($conn, $sql)) {

            echo "
            <script>
                alert('Project successfully deleted!');
                window.location='index.php';
            </script>
            ";

            exit();

        } else {

            die("Delete Error: " . mysqli_error($conn));

        }
    }
}


// ADD OR EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $project_name = mysqli_real_escape_string(
        $conn,
        $_POST['project_name'] ?? ''
    );

    $location = mysqli_real_escape_string(
        $conn,
        $_POST['location'] ?? ''
    );

    $contractor = mysqli_real_escape_string(
        $conn,
        $_POST['contractor'] ?? ''
    );

    $project_engineer = mysqli_real_escape_string(
        $conn,
        $_POST['project_engineer'] ?? ''
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status'] ?? ''
    );


    // ======================================================
    // EDIT EXISTING PROJECT
    // ======================================================

    if ($id > 0) {

        $sql = "
            UPDATE projects
            SET
                project_name = '$project_name',
                location = '$location',
                contractor = '$contractor',
                project_engineer = '$project_engineer',
                status = '$status'
            WHERE id = $id
            LIMIT 1
        ";

        if (mysqli_query($conn, $sql)) {

            $user_id = (int) $_SESSION['user_id'];

            $activity = mysqli_real_escape_string(
                $conn,
                "Updated project: " . $project_name
            );

            mysqli_query($conn, "
                INSERT INTO activity_logs
                (
                    user_id,
                    activity
                )
                VALUES
                (
                    $user_id,
                    '$activity'
                )
            ");

            echo "
            <script>
                alert('Project successfully updated!');
                window.location='index.php';
            </script>
            ";

            exit();

        } else {

            die("Update Error: " . mysqli_error($conn));

        }

    }


    // ======================================================
    // ADD NEW PROJECT
    // ======================================================

    else {

        $sql = "
            INSERT INTO projects
            (
                project_name,
                location,
                contractor,
                project_engineer,
                status
            )
            VALUES
            (
                '$project_name',
                '$location',
                '$contractor',
                '$project_engineer',
                '$status'
            )
        ";

        if (mysqli_query($conn, $sql)) {

            $user_id = (int) $_SESSION['user_id'];

            $activity = mysqli_real_escape_string(
                $conn,
                "Added new project: " . $project_name
            );

            mysqli_query($conn, "
                INSERT INTO activity_logs
                (
                    user_id,
                    activity
                )
                VALUES
                (
                    $user_id,
                    '$activity'
                )
            ");

            echo "
            <script>
                alert('Project successfully added!');
                window.location='index.php';
            </script>
            ";

            exit();

        } else {

            die("Insert Error: " . mysqli_error($conn));

        }

    }
}


// ==========================================================
// EDIT MODE
// ==========================================================

$edit_mode = false;
$edit_project = null;

if (isset($_GET['edit'])) {

    $edit_id = (int) $_GET['edit'];

    if ($edit_id > 0) {

        $edit_query = mysqli_query(
            $conn,
            "SELECT * FROM projects WHERE id = $edit_id LIMIT 1"
        );

        if ($edit_query && mysqli_num_rows($edit_query) > 0) {

            $edit_mode = true;
            $edit_project = mysqli_fetch_assoc($edit_query);

        }

    }

}


// ==========================================================
// GET ALL PROJECTS
// ==========================================================

$query = mysqli_query(
    $conn,
    "SELECT * FROM projects ORDER BY id DESC"
);


if (!$query) {
    die("Database Error: " . mysqli_error($conn));
}


// ==========================================================
// PAGE HEADER
// ==========================================================

include "../includes/header.php";
include "../includes/navbar.php";
include "../includes/sidebar.php";

?>


<div class="content-wrapper">


    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <section class="content-header">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-6">

                    <h1>
                        Projects Management
                    </h1>

                </div>


                <div class="col-sm-6 text-right">

                    <a
                        href="#projectForm"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-plus"></i>

                        New Project

                    </a>

                </div>

            </div>

        </div>

    </section>



    <!-- =====================================================
         CONTENT
    ====================================================== -->

    <section class="content">

        <div class="container-fluid">


            <!-- =================================================
                 ADD / EDIT FORM
            ================================================== -->

            <div
                class="card"
                id="projectForm"
            >

                <div class="card-header">

                    <h3 class="card-title">

                        <?php if ($edit_mode): ?>

                            Edit Project

                        <?php else: ?>

                            Add New Project

                        <?php endif; ?>

                    </h3>

                </div>


                <form method="POST" action="index.php">

                    <div class="card-body">


                        <!-- ID -->

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $edit_mode
                                ? htmlspecialchars($edit_project['id'])
                                : '0'; ?>"
                        >


                        <!-- PROJECT NAME -->

                        <div class="form-group">

                            <label>
                                Project Name
                            </label>

                            <input
                                type="text"
                                name="project_name"
                                class="form-control"
                                value="<?= $edit_mode
                                    ? htmlspecialchars($edit_project['project_name'])
                                    : ''; ?>"
                                required
                            >

                        </div>


                        <!-- LOCATION -->

                        <div class="form-group">

                            <label>
                                Location
                            </label>

                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                value="<?= $edit_mode
                                    ? htmlspecialchars($edit_project['location'])
                                    : ''; ?>"
                                required
                            >

                        </div>


                        <!-- CONTRACTOR -->

                        <div class="form-group">

                            <label>
                                Contractor
                            </label>

                            <input
                                type="text"
                                name="contractor"
                                class="form-control"
                                value="<?= $edit_mode
                                    ? htmlspecialchars($edit_project['contractor'])
                                    : ''; ?>"
                                required
                            >

                        </div>


                        <!-- ENGINEER -->

                        <div class="form-group">

                            <label>
                                Project Engineer
                            </label>

                            <input
                                type="text"
                                name="project_engineer"
                                class="form-control"
                                value="<?= $edit_mode
                                    ? htmlspecialchars($edit_project['project_engineer'])
                                    : ''; ?>"
                                required
                            >

                        </div>


                        <!-- STATUS -->

                        <div class="form-group">

                            <label>
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                                required
                            >

                                <option
                                    value="Ongoing"
                                    <?= (
                                        $edit_mode &&
                                        $edit_project['status'] === 'Ongoing'
                                    )
                                        ? 'selected'
                                        : ''; ?>
                                >
                                    Ongoing
                                </option>


                                <option
                                    value="Completed"
                                    <?= (
                                        $edit_mode &&
                                        $edit_project['status'] === 'Completed'
                                    )
                                        ? 'selected'
                                        : ''; ?>
                                >
                                    Completed
                                </option>


                                <option
                                    value="Suspended"
                                    <?= (
                                        $edit_mode &&
                                        $edit_project['status'] === 'Suspended'
                                    )
                                        ? 'selected'
                                        : ''; ?>
                                >
                                    Suspended
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- BUTTONS -->

                    <div class="card-footer">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-save"></i>

                            <?php if ($edit_mode): ?>

                                Update Project

                            <?php else: ?>

                                Add Project

                            <?php endif; ?>

                        </button>


                        <?php if ($edit_mode): ?>

                            <a
                                href="index.php"
                                class="btn btn-secondary"
                            >

                                Cancel

                            </a>

                        <?php endif; ?>

                    </div>

                </form>

            </div>



            <!-- =================================================
                 PROJECT LIST
            ================================================== -->

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">

                        DPWH Project List

                    </h3>

                </div>


                <div class="card-body">


                    <table
                        class="table table-bordered table-hover datatable"
                    >

                        <thead>

                            <tr>

                                <th>
                                    Project Name
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Contractor
                                </th>

                                <th>
                                    Engineer
                                </th>

                                <th>
                                    Status
                                </th>

                                <th width="150">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php if (mysqli_num_rows($query) > 0): ?>


                                <?php while ($row = mysqli_fetch_assoc($query)): ?>


                                    <tr>


                                        <td>

                                            <?= htmlspecialchars(
                                                $row['project_name']
                                            ); ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $row['location']
                                            ); ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $row['contractor']
                                            ); ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $row['project_engineer']
                                            ); ?>

                                        </td>


                                        <td>


                                            <?php if (
                                                $row['status'] === 'Completed'
                                            ): ?>

                                                <span class="badge badge-success">
                                                    Completed
                                                </span>


                                            <?php elseif (
                                                $row['status'] === 'Suspended'
                                            ): ?>

                                                <span class="badge badge-danger">
                                                    Suspended
                                                </span>


                                            <?php else: ?>

                                                <span class="badge badge-warning">
                                                    Ongoing
                                                </span>

                                            <?php endif; ?>


                                        </td>


                                        <td>


                                            <!-- EDIT -->

                                            <a
                                                href="index.php?edit=<?= $row['id']; ?>#projectForm"
                                                class="btn btn-warning btn-sm"
                                                title="Edit"
                                            >

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            <!-- DELETE -->

                                            <a
                                                href="index.php?delete=<?= $row['id']; ?>"
                                                class="btn btn-danger btn-sm"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this project?');"
                                            >

                                                <i class="fas fa-trash"></i>

                                            </a>


                                        </td>


                                    </tr>


                                <?php endwhile; ?>


                            <?php else: ?>


                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center"
                                    >

                                        No projects found.

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
