<?php
require_once 'class.admin.php';
$user = new ADMIN();

if(empty($_GET['id']) && empty($_GET['code']))
{
	$user->redirect('index.php');
}

if(isset($_GET['id']) && isset($_GET['code']))
{
	$id = base64_decode($_GET['id']);
	$code = $_GET['code'];
	
	$stmt = $user->runQuery("SELECT * FROM tbl_admin WHERE adminID=:uid AND tokenCode=:token");
	$stmt->execute(array(":uid"=>$id,":token"=>$code));
	$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if($stmt->rowCount() == 1)
	{
		if(isset($_POST['btn-reset-pass']))
		{
			$pass = $_POST['pass'];
			$cpass = $_POST['confirm-pass'];
			
			if($cpass!==$pass)
			{
				$msg = "<div class='alert alert-block'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Sorry!</strong>  Password Doesn't match. 
						</div>";
			}
			else
			{
				$password = md5($cpass);
				$stmt = $user->runQuery("UPDATE tbl_admin SET adminPass=:upass WHERE adminID=:uid");
				$stmt->execute(array(":upass"=>$password,":uid"=>$rows['adminID']));
				
				$msg = "<div class='alert alert-success'>
						<button class='close' data-dismiss='alert'>&times;</button>
						Password Changed.
						</div>";
				header("refresh:1; index.php");
			}
		}	
	}
	else
	{
		$msg = "<div class='alert alert-success'>
				<button class='close' data-dismiss='alert'>&times;</button>
				No Account Found, Try again
				</div>";
				
	}
	
	
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Password Reset</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>Forgot Password</title>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<link href="<?= siteURL() ?>assset/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= siteURL() ?>assset/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?= siteURL() ?>assset/css/style.css" rel="stylesheet">
  </head>
  <body id="login">
    <div class="container">
    	<div style="height: 100px"></div>
    	<div class="col-sm-4"> </div>
    		<div class="col-sm-4">
 <div class="portlet portlet-red">
<div class="portlet-heading login-heading">
<div class="portlet-title">
<!-- <h4>Admin Area!</h4> -->
</div>
<div class="portlet-widgets">
<!-- <button class="btn btn-white btn-xs"><i class="fa fa-plus-circle"></i> New User</button> -->
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
    	<div class='alert alert-success'>
			<strong>Hello !</strong>  <?php echo $rows['adminName'] ?> you are here to reset your forgetton password.
		</div>
        <form class="form-signin" method="post">
        <h3 class="form-signin-heading">Password Reset.</h3><hr />
        <?php
        if(isset($msg))
		{
			echo $msg;
		}
		?>
		<div class="form-group">
        <input type="password" class="form-control input-block-level" placeholder="New Password" name="pass" required />
    </div>
    <div class="form-group">
        <input type="password" class="form-control input-block-level" placeholder="Confirm New Password" name="confirm-pass" required />
    </div>
     	<hr />
        <button class="btn btn-large btn-primary" type="submit" name="btn-reset-pass">Reset Your Password</button>
        
      </form>

    </div> <!-- /container -->
    <script src="<?= siteURL() ?>assset/js/plugins/bootstrap/bootstrap.min.js"></script>
  </body>
</html>