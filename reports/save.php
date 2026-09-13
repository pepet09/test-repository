<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Hard blockade preventing Lab Tech backend processing bypasses
if(!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) === 'laboratory technician')
{
    header("Location: /proposed_dpwh_repository/reports/index.php");
    exit();
}


include "../config/database.php";


// ==========================================
// GET REPORT DATA
// ==========================================

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


$prepared_by = mysqli_real_escape_string(
    $conn,
    $_POST['prepared_by']
);




// ==========================================
// PDF UPLOAD
// ==========================================

$pdf_file = "";


if(isset($_FILES['pdf_file']) && $_FILES['pdf_file']['name'] != "")
{

    $folder = "../uploads/";


    if(!is_dir($folder))
    {
        mkdir($folder, 0777, true);
    }


    $filename = time()
    . "_"
    . basename($_FILES['pdf_file']['name']);


    $target = $folder . $filename;


    if(move_uploaded_file(
        $_FILES['pdf_file']['tmp_name'],
        $target
    ))
    {
        $pdf_file = $filename;
    }

}




// ==========================================
// SAVE REPORT
// ==========================================

$sql = "
INSERT INTO reports
(
report_number,
project_id,
date_tested,
prepared_by,
pdf_file,
status
)
VALUES
(
'$report_number',
'$project_id',
'$date_tested',
'$prepared_by',
'$pdf_file',
'Pending'
)
";



if(mysqli_query($conn, $sql))
{

    $report_id = mysqli_insert_id($conn);


    // ==========================================
    // SAVE TEST DETAILS
    // ==========================================

    if(isset($_POST['sample_no']))
    {

        foreach($_POST['sample_no'] as $key => $value)
        {

            $sample_identification = mysqli_real_escape_string(
                $conn,
                $_POST['sample_no'][$key]
            );


            $material_type = mysqli_real_escape_string(
                $conn,
                $_POST['material_type'][$key]
            );


            $test_type = mysqli_real_escape_string(
                $conn,
                $_POST['test_type'][$key]
            );


            $specification = mysqli_real_escape_string(
                $conn,
                $_POST['specification'][$key]
            );


            $quantity = mysqli_real_escape_string(
                $conn,
                $_POST['quantity'][$key]
            );


            $status = mysqli_real_escape_string(
                $conn,
                $_POST['status'][$key]
            );


            mysqli_query($conn, "
            INSERT INTO test_details
            (
            report_id,
            sample_identification,
            material_type,
            test_type,
            specification,
            quantity,
            status
            )
            VALUES
            (
            '$report_id',
            '$sample_identification',
            '$material_type',
            '$test_type',
            '$specification',
            '$quantity',
            '$status'
            )
            ");

        }

    }


    // ==========================================
    // SUCCESS REDIRECTION (FIXED 404 PATH)
    // ==========================================

    echo "
    <script>
    alert('Materials Testing Report Successfully Saved!');
    window.location='/proposed_dpwh_repository/reports/index.php';
    </script>
    ";

}
else
{

    echo "
    <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #dc3545; background-color: #f8d7da; color: #721c24; margin: 20px; border-radius: 5px;'>
        <h3>Database Transaction Error:</h3>
        <p>" . htmlspecialchars(mysqli_error($conn)) . "</p>
        <a href='/proposed_dpwh_repository/reports/index.php' style='color: #0056b3; font-weight: bold;'>&larr; Return to Repository</a>
    </div>
    ";

}

?>
