<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}


include "../config/database.php";


$id=$_GET['id'];



// Delete test details first

mysqli_query($conn,

"

DELETE FROM test_details

WHERE report_id='$id'

"

);



// Delete report

mysqli_query($conn,

"

DELETE FROM reports

WHERE id='$id'

"

);



echo "

<script>

alert('Report Deleted!');

window.location='index.php';

</script>

";


?>