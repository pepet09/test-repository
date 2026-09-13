<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);


// =====================================================
// GET PROJECT ID
// =====================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid project ID.");
}

$id = (int) $_GET['id'];


// =====================================================
// UPDATE PROJECT WHEN FORM IS SUBMITTED
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];

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


    // UPDATE existing project
    $sql = "
        UPDATE projects
        SET
            project_name = '$project_name',
            location = '$location',
            contractor = '$contractor',
            project_engineer = '$project_engineer',
            status = '$status'
        WHERE id = $id
    ";


    if (mysqli_query($conn, $sql)) {

        // Activity log
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
                '$user_id',
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

        die("Database Error: " . mysqli_error($conn));

    }

}


// =====================================================
// GET CURRENT PROJECT INFORMATION
// =====================================================

$query = mysqli_query(
    $conn,
    "SELECT * FROM projects WHERE id = $id"
);

if (!$query) {
    die("Database Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($query) == 0) {
    die("Project not found.");
}

$project = mysqli_fetch_assoc($query);


// =====================================================
// PAGE HEADER
// =====================================================

include "../includes/header.php";
include "../includes/navbar.php";
include "../includes/sidebar.php";

?>


<div class="content-wrapper">


    <!-- PAGE HEADER -->
    <section class="content-header">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-6">

                    <h1>
                        Edit Project
                    </h1>

                </div>

            </div>

        </div>

    </section>



    <!-- CONTENT -->
    <section class="content">

        <div class="container-fluid">

            <div class="card">


                <!-- CARD HEADER -->
                <div class="card-header">

                    <h3 class="card-title">
                        Edit Project Information
                    </h3>

                </div>



                <!-- FORM -->
                <form method="POST" action="edit.php?id=<?= $id; ?>">

                    <div class="card-body">


                        <!-- PROJECT ID -->
                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars($project['id']); ?>"
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
                                value="<?= htmlspecialchars($project['project_name']); ?>"
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
                                value="<?= htmlspecialchars($project['location']); ?>"
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
                                value="<?= htmlspecialchars($project['contractor']); ?>"
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
                                value="<?= htmlspecialchars($project['project_engineer']); ?>"
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
                                    <?= ($project['status'] == 'Ongoing') ? 'selected' : ''; ?>
                                >
                                    Ongoing
                                </option>


                                <option
                                    value="Completed"
                                    <?= ($project['status'] == 'Completed') ? 'selected' : ''; ?>
                                >
                                    Completed
                                </option>


                                <option
                                    value="Suspended"
                                    <?= ($project['status'] == 'Suspended') ? 'selected' : ''; ?>
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

                            Save Changes

                        </button>


                        <a
                            href="index.php"
                            class="btn btn-secondary"
                        >

                            <i class="fas fa-arrow-left"></i>

                            Cancel

                        </a>

                    </div>


                </form>


            </div>

        </div>

    </section>

</div>


<?php

include "../includes/footer.php";

?>
