<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}


include "../config/database.php";



$id = $_POST['id'];

$report_number = mysqli_real_escape_string(
    $conn,
    $_POST['report_number']
);


$project_id = mysqli_real_escape_string(
    $conn,
    $_POST['project_id']
);


$date_tested = mysqli_real_escape_string(
    $conn,
    $_POST['date_tested']
);




// ==============================
// PDF UPDATE
// ==============================

$pdf_update = "";


if(isset($_FILES['pdf_file']) && $_FILES['pdf_file']['name'] != "")
{


    $folder="../uploads/";


    if(!is_dir($folder))
    {
        mkdir($folder,0777,true);
    }


    $filename = time()
    . "_"
    . basename($_FILES['pdf_file']['name']);


    $target=$folder.$filename;



    if(move_uploaded_file(
        $_FILES['pdf_file']['tmp_name'],
        $target
    ))
    {

        $pdf_update = ", pdf_file='$filename'";

    }


}



// ==============================
// UPDATE REPORT
// ==============================


$sql="

UPDATE reports

SET

report_number='$report_number',

project_id='$project_id',

date_tested='$date_tested'

$pdf_update


WHERE id='$id'

";



if(mysqli_query($conn,$sql))
{



// ==============================
// UPDATE TEST DETAILS
// ==============================


if(isset($_POST['detail_id']))
{


foreach($_POST['detail_id'] as $key=>$detail_id)
{


$sample =
mysqli_real_escape_string(
$conn,
$_POST['sample_identification'][$key]
);


$material =
mysqli_real_escape_string(
$conn,
$_POST['material_type'][$key]
);


$test =
mysqli_real_escape_string(
$conn,
$_POST['test_type'][$key]
);


$spec =
mysqli_real_escape_string(
$conn,
$_POST['specification'][$key]
);


$qty =
mysqli_real_escape_string(
$conn,
$_POST['quantity'][$key]
);


$status =
mysqli_real_escape_string(
$conn,
$_POST['status'][$key]
);




mysqli_query($conn,

"

UPDATE test_details

SET

sample_identification='$sample',

material_type='$material',

test_type='$test',

specification='$spec',

quantity='$qty',

status='$status'


WHERE id='$detail_id'

"

);



}



}





echo "

<script>

alert('Report Updated Successfully!');

window.location='index.php';

</script>

";



}

else
{

echo "

Database Error:

<br>

".mysqli_error($conn);

}



?>