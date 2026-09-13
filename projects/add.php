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

?>


<div class="content-wrapper">


<section class="content-header">

<div class="container-fluid">

<h1>

Add New Project

</h1>

</div>

</section>



<section class="content">


<div class="container-fluid">


<div class="card">


<div class="card-header">

<h3 class="card-title">

Project Information

</h3>

</div>


<div class="card-body">



<form method="POST" action="save.php">



<div class="form-group">

<label>

Project Name

</label>

<input

type="text"

name="project_name"

class="form-control"

placeholder="Enter project name"

required>

</div>



<div class="form-group">

<label>

Project Location

</label>

<input

type="text"

name="location"

class="form-control"

placeholder="Example: Brgy. Diteki, San Luis, Aurora"

required>

</div>



<div class="form-group">

<label>

Contractor

</label>

<input

type="text"

name="contractor"

class="form-control"

placeholder="Enter contractor name"

required>

</div>



<div class="form-group">

<label>

Project Engineer

</label>

<input

type="text"

name="project_engineer"

class="form-control"

placeholder="Enter project engineer"

required>

</div>



<div class="form-group">

<label>

Project Status

</label>


<select

name="status"

class="form-control">


<option value="Ongoing">

Ongoing

</option>


<option value="Completed">

Completed

</option>


<option value="Suspended">

Suspended

</option>


</select>


</div>




<button

type="submit"

class="btn btn-success">


<i class="fas fa-save"></i>

Save Project


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