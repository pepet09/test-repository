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


// Get report

$report_query = mysqli_query($conn,

"
SELECT *

FROM reports

WHERE id='$id'

"

);


$report = mysqli_fetch_assoc($report_query);



// Get projects

$projects = mysqli_query($conn,

"
SELECT *

FROM projects

ORDER BY project_name ASC

"

);



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

Edit Materials Testing Report

</h1>

</div>

</section>



<section class="content">


<div class="container-fluid">


<div class="card">


<div class="card-body">


<form method="POST"

action="update.php"

enctype="multipart/form-data">


<input type="hidden"

name="id"

value="<?= $report['id']; ?>">



<div class="form-group">

<label>

Report Number

</label>


<input

type="text"

name="report_number"

class="form-control"

value="<?= htmlspecialchars($report['report_number']); ?>"

required>

</div>



<div class="form-group">

<label>

Project

</label>


<select name="project_id"

class="form-control">


<?php

while($p=mysqli_fetch_assoc($projects))

{

$selected="";

if($p['id']==$report['project_id'])
{
$selected="selected";
}

?>


<option value="<?= $p['id']; ?>"

<?= $selected; ?>>

<?= htmlspecialchars($p['project_name']); ?>

</option>


<?php

}

?>


</select>


</div>



<div class="form-group">

<label>

Date Tested

</label>


<input

type="date"

name="date_tested"

class="form-control"

value="<?= $report['date_tested']; ?>">


</div>



<hr>


<h5>

Testing Details

</h5>


<table class="table table-bordered">


<thead>

<tr>

<th>Sample Identification</th>

<th>Material Type</th>

<th>Test Type</th>

<th>Specification</th>

<th>Quantity</th>

<th>Status</th>

</tr>

</thead>


<tbody>


<?php

while($detail=mysqli_fetch_assoc($details))

{


?>


<tr>


<input type="hidden"

name="detail_id[]"

value="<?= $detail['id']; ?>">



<td>

<input

type="text"

name="sample_identification[]"

class="form-control"

value="<?= $detail['sample_identification']; ?>">

</td>



<td>

<input

type="text"

name="material_type[]"

class="form-control"

value="<?= $detail['material_type']; ?>">

</td>



<td>

<input

type="text"

name="test_type[]"

class="form-control"

value="<?= $detail['test_type']; ?>">

</td>



<td>

<input

type="text"

name="specification[]"

class="form-control"

value="<?= $detail['specification']; ?>">

</td>



<td>

<input

type="text"

name="quantity[]"

class="form-control"

value="<?= $detail['quantity']; ?>">

</td>



<td>


<select

name="status[]"

class="form-control">


<option <?= $detail['status']=="Pending"?"selected":"";?>>

Pending

</option>


<option <?= $detail['status']=="Approved"?"selected":"";?>>

Approved

</option>


<option <?= $detail['status']=="Rejected"?"selected":"";?>>

Rejected

</option>


</select>


</td>


</tr>


<?php

}

?>


</tbody>


</table>



<div class="form-group">

<label>

Replace PDF (Optional)

</label>


<input

type="file"

name="pdf_file"

class="form-control"

accept=".pdf">


</div>




<button class="btn btn-success">

<i class="fas fa-save"></i>

Update Report

</button>


<a href="index.php"

class="btn btn-secondary">

Back

</a>



</form>


</div>


</div>


</div>


</section>


</div>



<?php

include "../includes/footer.php";

?>