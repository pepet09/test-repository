<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

include "../includes/header.php";
include "../includes/navbar.php";
include "../includes/sidebar.php";


$id = $_GET['id'];


// Get report information

$report_query = mysqli_query($conn,

"
SELECT

reports.*,

projects.project_name

FROM reports

LEFT JOIN projects

ON reports.project_id = projects.id

WHERE reports.id='$id'

"

);


$report = mysqli_fetch_assoc($report_query);



// Get test details

$details = mysqli_query($conn,

"
SELECT *

FROM test_details

WHERE report_id='$id'

"

);


?>


<div class="content-wrapper">


<section class="content-header">

<div class="container-fluid">

<h1>

View Materials Testing Report

</h1>

</div>

</section>



<section class="content">


<div class="container-fluid">


<div class="card">


<div class="card-header">

<h3 class="card-title">

Report Information

</h3>

</div>


<div class="card-body">


<table class="table table-bordered">


<tr>

<th width="200">

Report Number

</th>

<td>

<?= htmlspecialchars($report['report_number']); ?>

</td>

</tr>



<tr>

<th>

Project

</th>

<td>

<?= htmlspecialchars($report['project_name']); ?>

</td>

</tr>



<tr>

<th>

Date Tested

</th>

<td>

<?= htmlspecialchars($report['date_tested']); ?>

</td>

</tr>



<tr>

<th>

Prepared By

</th>

<td>

<?= htmlspecialchars($report['prepared_by']); ?>

</td>

</tr>



<tr>

<th>

Status

</th>

<td>


<?php

if($report['status']=="Approved")
{
echo '<span class="badge badge-success">Approved</span>';
}
elseif($report['status']=="Rejected")
{
echo '<span class="badge badge-danger">Rejected</span>';
}
else
{
echo '<span class="badge badge-warning">Pending</span>';
}

?>


</td>

</tr>



<?php if(!empty($report['pdf_file'])) { ?>


<tr>

<th>

PDF File

</th>

<td>


<a href="../uploads/<?= $report['pdf_file']; ?>"

target="_blank"

class="btn btn-primary btn-sm">

<i class="fas fa-file-pdf"></i>

View PDF

</a>


</td>

</tr>


<?php } ?>


</table>


</div>


</div>





<div class="card">


<div class="card-header">

<h3 class="card-title">

Testing Details

</h3>

</div>


<div class="card-body">


<table class="table table-bordered table-striped">


<thead>

<tr>

<th>
Sample Identification
</th>

<th>
Material Type
</th>

<th>
Test Type
</th>

<th>
Specification
</th>

<th>
Quantity
</th>

<th>
Status
</th>

</tr>

</thead>


<tbody>


<?php

while($row=mysqli_fetch_assoc($details))

{

?>


<tr>


<td>

<?= htmlspecialchars($row['sample_identification']); ?>

</td>


<td>

<?= htmlspecialchars($row['material_type']); ?>

</td>


<td>

<?= htmlspecialchars($row['test_type']); ?>

</td>


<td>

<?= htmlspecialchars($row['specification']); ?>

</td>


<td>

<?= htmlspecialchars($row['quantity']); ?>

</td>


<td>


<?= htmlspecialchars($row['status']); ?>


</td>


</tr>


<?php

}

?>


</tbody>


</table>


</div>


</div>



<a href="index.php"

class="btn btn-secondary">

Back

</a>


</div>


</section>


</div>



<?php

include "../includes/footer.php";

?>