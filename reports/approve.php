<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";


$id = $_GET['id'];



$sql = "

UPDATE reports

SET status='Approved'

WHERE id='$id'

";



if(mysqli_query($conn,$sql))
{


    // Activity log

    $activity = "Approved report ID: ".$id;

    $user_id = $_SESSION['user_id'];


    mysqli_query($conn,

    "

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

    "

    );



    echo "

    <script>

    alert('Report Approved!');

    window.location='index.php';

    </script>

    ";

}
else
{

echo mysqli_error($conn);

}


?>