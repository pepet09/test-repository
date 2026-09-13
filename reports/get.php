<?php

include "../config/database.php";


$id = $_GET['id'];


$query = mysqli_query($conn,

"
SELECT *

FROM projects

WHERE id='$id'

"

);


$project = mysqli_fetch_assoc($query);


echo json_encode($project);


?>