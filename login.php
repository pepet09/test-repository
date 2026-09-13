<?php
session_start();

if(isset($_SESSION['user_id']))
{
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>DPWH Repository Login</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="dist/css/adminlte.min.css">

</head>

<body class="hold-transition login-page">

<div class="login-box">

<div class="login-logo">

<!-- Increased width from 90px to 140px for a larger display -->
<div class="text-center mb-3">
    <img src="assets/images/dpwh-logo.png" alt="DPWH Logo" class="brand-image" style="width: 140px; height: auto; display: block; margin: 0 auto;">
</div>

<b>DPWH Aurora</b>

<br>

Materials Testing Repository

</div>

<div class="card">

<div class="card-body login-card-body">

<p class="login-box-msg">

Sign in to start your session

</p>

<form action="login_process.php" method="POST">

<div class="input-group mb-3">

<input
type="text"
name="username"
class="form-control"
placeholder="Username"
required>

<div class="input-group-append">

<div class="input-group-text">

<span class="fas fa-user"></span>

</div>

</div>

</div>

<div class="input-group mb-3">

<input
type="password"
name="password"
class="form-control"
placeholder="Password"
required>

<div class="input-group-append">

<div class="input-group-text">

<span class="fas fa-lock"></span>

</div>

</div>

</div>

<button
type="submit"
class="btn btn-primary btn-block">

Login

</button>

</form>

<?php

if(isset($_GET['error']))
{

echo '

<div class="alert alert-danger mt-3">

Invalid Username or Password

</div>

';

}

?>

</div>

</div>

</div>

<script src="plugins/jquery/jquery.min.js"></script>

<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="dist/js/adminlte.min.js"></script>

</body>

</html>
