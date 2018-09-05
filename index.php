<?php
session_start();
require_once 'class.user.php';
$user_login = new USER();

if($user_login->is_logged_in()!="")
{
$user_login->redirect('dashboard');
}

if(isset($_POST['btn-login']))
{
$email = trim($_POST['txtemail']);
$upass = trim($_POST['txtupass']);

if($user_login->login($email,$upass))
{
$user_login->redirect('dashboard');
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="admin login">
<meta name="author" content="">
<meta http-equiv="refresh" content="40; URL='index.php' ">
<title>User Login</title>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<link href="assset/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assset/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="assset/css/style.css" rel="stylesheet">
</head>
<body style="background-image: url('images/bc.jpg') !important;
background-color: #cccccc; height: 100%;"    oncontextmenu=" return disableRightClick();">
<div class="container">
<div class="row">
<div class="col-md-4 col-md-offset-4 shedowlogin">
<div class="login-banner text-center ">
<h1><i class="fa fa-user"></i>User LogIn</h1>
</div>
<div class="portlet portlet-green">
<div class="portlet-heading login-heading">
<div class="portlet-title">
</div>
<div class="portlet-widgets">
<!-- <button class="btn btn-white btn-xs"><i class="fa fa-plus-circle"></i> New User</button> -->
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
<?php 
if(isset($_GET['inactive']))
{
?>
<div class='alert alert-error'>
<button class='close' data-dismiss='alert'>&times;</button>
<strong>Sorry!</strong> This Account is not Activated Go to your Inbox and Activate it. 
</div>
<?php
}
?>

<form class="form-signin" method="post">
<?php
if(isset($_GET['error']))
{
?>
<div class='alert alert-warning'>
<button class='close' data-dismiss='alert'>&times;</button>
<strong>Invalid UserName Password!</strong> 
</div>
<?php
}
?>
<fieldset>
<div class="form-group">
<input class="form-control" placeholder="Username"  type="email" name="txtemail" required />
</div>
<div class="form-group">
<input class="form-control" placeholder="Password"  type="password" name="txtupass" required />
</div>
<br>
<input type="submit" value="LogIn" name="btn-login" class=" btn btn-lg btn-green btn-block" />
<hr />
</fieldset>
<br>
<!--<a href="signup" style="float:right;" >Sign Up</a>-->
<p class="small">
<a href="fpass">lost your password?</a>
</p>
</form>
</div>
</div>
</div>
</div>
</div>
<script src="assset/js/plugins/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript">
    function disableRightClick() {
       // alert("ohh! hoo!, right click is not allowed !!");
        return false;
    }
</script>
</body>
</html>