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


$query = mysqli_query($conn,

"
SELECT

reports.id,

reports.report_number,

reports.date_tested,

reports.status,

projects.project_name,

users.fullname AS prepared_name,

test_details.material_type,

test_details.test_type


FROM reports


LEFT JOIN projects

ON reports.project_id = projects.id



LEFT JOIN users

ON reports.prepared_by = users.id



LEFT JOIN test_details

ON reports.id = test_details.report_id



GROUP BY reports.id


ORDER BY reports.created_at DESC

"

);



?>



<div class="content-wrapper">


<section class="content-header">

<div class="container-fluid">

<h1>

Materials Testing Reports

</h1>

</div>

</section>



<section class="content">


<div class="container-fluid">


<div class="card">


<div class="card-header">

<!-- Hides button completely from any technician role variant -->
<?php 
$current_role = isset($_SESSION['role']) ? strtolower($_SESSION['role']) : '';
if ($current_role !== 'laboratory technician' && $current_role !== 'lab tech'): 
?>
<a href="add.php"

class="btn btn-primary">

<i class="fas fa-plus"></i>

New Test Report

</a>
<?php endif; ?>


</div>



<div class="card-body">



<table class="table table-bordered table-striped">


<thead>

<tr>


<th>
Report No.
</th>


<th>
Project
</th>


<th>
Material
</th>


<th>
Test Type
</th>


<th>
Prepared By
</th>


<th>
Date Tested
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



<?php

while($row=mysqli_fetch_assoc($query))

{


?>


<tr>


<td>

<?= htmlspecialchars($row['report_number']); ?>

</td>



<td>

<?= htmlspecialchars($row['project_name']); ?>

</td>



<td>

<?= htmlspecialchars($row['material_type']); ?>

</td>



<td>

<?= htmlspecialchars($row['test_type']); ?>

</td>



<td>

<?= htmlspecialchars($row['prepared_name']); ?>

</td>



<td>

<?= htmlspecialchars($row['date_tested']); ?>

</td>



<td>


<?php

if($row['status']=="Approved")
{

echo '<span class="badge badge-success">
Approved
</span>';

}

elseif($row['status']=="Rejected")
{

echo '<span class="badge badge-danger">
Rejected
</span>';

}

else

{

echo '<span class="badge badge-warning">
Pending
</span>';

}

?>


</td>




<td>


<a href="view.php?id=<?= $row['id']; ?>"

class="btn btn-info btn-sm"

title="View/Retrieve">

<i class="fas fa-eye"></i>

</a>



<!-- Administrative Action Options - Locked Strictly to Chief Profile -->
<?php if (strtolower($_SESSION['role']) === 'chief'): ?>
<a href="edit.php?id=<?= $row['id']; ?>"

class="btn btn-warning btn-sm">

<i class="fas fa-edit"></i>

</a>




<a href="approve.php?id=<?= $row['id']; ?>"

class="btn btn-success btn-sm"

onclick="return confirm('Approve this report?');">

<i class="fas fa-check"></i>

</a>




<a href="reject.php?id=<?= $row['id']; ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Reject this report?');">

<i class="fas fa-times"></i>

</a>




<a href="delete.php?id=<?= $row['id']; ?>"

class="btn btn-secondary btn-sm"

onclick="return confirm('Delete this report?');">

<i class="fas fa-trash"></i>

</a>
<?php endif; ?>



</td>


</tr>


<?php

}

?>


</tbody>


</table>


</div>


</div>


</div>


</section>


</div>



<?php

include "../includes/footer.php";

?>
