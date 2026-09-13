<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}


include "../config/database.php";


// Get form data

$project_name = mysqli_real_escape_string(
    $conn,
    $_POST['project_name']
);


$location = mysqli_real_escape_string(
    $conn,
    $_POST['location']
);


$contractor = mysqli_real_escape_string(
    $conn,
    $_POST['contractor']
);


$project_engineer = mysqli_real_escape_string(
    $conn,
    $_POST['project_engineer']
);


$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);



// Insert project

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



if(mysqli_query($conn,$sql))
{


    // Optional activity log

    if(isset($_SESSION['user_id']))
    {

        $user_id = $_SESSION['user_id'];

        $activity = "Added new project: ".$project_name;


        mysqli_query($conn,"

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

    }



    echo "

    <script>

    alert('Project successfully added!');

    window.location='index.php';

    </script>

    ";


}
else
{

    echo "

    Database Error:

    ".mysqli_error($conn);

}


?>