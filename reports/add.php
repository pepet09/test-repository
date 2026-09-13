<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}


include "../config/database.php";

include "../includes/header.php";
include "../includes/navbar.php";
include "../includes/sidebar.php";


// Get projects

$projects = mysqli_query($conn,"
SELECT *
FROM projects
ORDER BY project_name ASC
");


$project_data = [];

$result = mysqli_query($conn,"
SELECT *
FROM projects
");


while($row = mysqli_fetch_assoc($result))
{
    $project_data[$row['id']] = $row;
}

?>


<div class="content-wrapper">


<section class="content-header">

<div class="container-fluid">

<h1>

New Materials Testing Report

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



<form method="POST" action="save.php" enctype="multipart/form-data">



<div class="form-group">

<label>
Report Number
</label>

<input

type="text"

name="report_number"

class="form-control"

required>

</div>




<div class="form-group">

<label>
Project
</label>


<select 
name="project_id" 
id="project_id"
class="form-control"
onchange="loadProject(this.value)"
required>


<option value="">

-- Select Project --

</option>


<?php

while($p=mysqli_fetch_assoc($projects))

{

?>

<option value="<?= $p['id']; ?>">

<?= htmlspecialchars($p['project_name']); ?>

</option>


<?php

}

?>


</select>


</div>
<div class="card mt-3">

<div class="card-header">

<h3 class="card-title">

Project Information

</h3>

</div>


<div class="card-body">


<div class="row">


<div class="col-md-4">

<label>
Location
</label>

<input 
type="text"
id="location"
class="form-control"
readonly>

</div>



<div class="col-md-4">

<label>
Contractor
</label>

<input 
type="text"
id="contractor"
class="form-control"
readonly>

</div>



<div class="col-md-4">

<label>
Project Engineer
</label>

<input 
type="text"
id="project_engineer"
class="form-control"
readonly>

</div>


</div>


</div>


</div>




<div class="form-group">

<label>
Prepared By
</label>

<input

type="text"

class="form-control"

value="<?= $_SESSION['fullname']; ?>"

readonly>


<input

type="hidden"

name="prepared_by"

value="<?= $_SESSION['user_id']; ?>">

</div>





<div class="form-group">

<label>
Date Tested
</label>

<input

type="date"

name="date_tested"

class="form-control"

required>

</div>




<hr>


<h5>

Testing Details

</h5>
<button type="button" 
class="btn btn-primary mb-2"
onclick="addRow()">

<i class="fas fa-plus"></i>
Add Sample

</button>

<table class="table table-bordered" id="details">


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

<th>
Action
</th>

</tr>

</thead>


<tbody>


<tr>


<td>

<input type="text"
name="sample_no[]"
class="form-control">

</td>


<td>

<input type="text"
name="material_type[]"
class="form-control">

</td>


<td>

<input type="text"
name="test_type[]"
class="form-control">

</td>


<td>

<input type="text"
name="specification[]"
class="form-control">

</td>


<td>

<input type="text"
name="quantity[]"
class="form-control">

</td>


<td>

<select name="status[]" class="form-control">

<option>
Pending
</option>

<option>
Approved
</option>

<option>
Rejected
</option>

</select>

</td>


<td>

<button type="button"
class="btn btn-danger btn-sm"
onclick="removeRow(this)">

<i class="fas fa-trash"></i>

</button>

</td>


</tr>


</tbody>


</table>





<div class="form-group">

<label>

Upload PDF Report

</label>


<input

type="file"

name="pdf_file"

class="form-control"

accept=".pdf">

</div>





<button class="btn btn-success">

<i class="fas fa-save"></i>

Save Report

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


<script>

let projects = <?= json_encode($project_data); ?>;



function loadProject(id)
{

if(id=="")
{

document.getElementById("location").value="";
document.getElementById("contractor").value="";
document.getElementById("project_engineer").value="";

return;

}


let project = projects[id];


document.getElementById("location").value =
project.location ?? "";


document.getElementById("contractor").value =
project.contractor ?? "";


document.getElementById("project_engineer").value =
project.project_engineer ?? "";


}

function addRow()
{

let table = document
.getElementById("details")
.getElementsByTagName('tbody')[0];


let row = table.insertRow();


row.innerHTML = `

<td>

<input type="text"
name="sample_no[]"
class="form-control">

</td>


<td>

<input type="text"
name="material_type[]"
class="form-control">

</td>


<td>

<input type="text"
name="test_type[]"
class="form-control">

</td>


<td>

<input type="text"
name="specification[]"
class="form-control">

</td>


<td>

<input type="text"
name="quantity[]"
class="form-control">

</td>


<td>

<select name="status[]" class="form-control">

<option>Pending</option>

<option>Approved</option>

<option>Rejected</option>

</select>

</td>


<td>

<button type="button"
class="btn btn-danger btn-sm"
onclick="removeRow(this)">

<i class="fas fa-trash"></i>

</button>

</td>

`;

}




function removeRow(button)
{

let row = button.closest("tr");

row.remove();

}

</script>


<?php

include "../includes/footer.php";

?>


