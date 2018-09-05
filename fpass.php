<?php
session_start();
require_once 'class.user.php';
$user = new USER();


if($user->is_logged_in()!="")
{
	$user->redirect('dashboard');
}

if(isset($_POST['btn-submit']))
{
	$email = $_POST['txtemail'];
	
	$stmt = $user->runQuery("SELECT userID FROM tbl_user WHERE userEmail=:email LIMIT 1");
	$stmt->execute(array(":email"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);	
	if($stmt->rowCount() == 1)
	{
		$id = base64_encode($row['userID']);
		$code = md5(uniqid(rand()));
		
		$stmt = $user->runQuery("UPDATE tbl_user SET tokenCode=:token WHERE userEmail=:email");
		$stmt->execute(array(":token"=>$code,"email"=>$email));
		
		$message= "
				   Hello , $email
				   <br /><br />
				   We got requested to reset your password, if you do this then just click the following link to reset your password, if not just ignore                   this email,
				   <br /><br />
				   Click Following Link To Reset Your Password 
				   <br /><br />
				   <a href='http://site0.mywebdeal.in/resetpass.php?id=$id&code=$code'>click here to reset your password</a>
				   <br /><br />
				   thank you :)
				   ";
		$subject = "Password Reset";
		
		$user->send_mail($email,$message,$subject);
		
		$msg = "<div class='alert alert-success'>
					<button class='close' data-dismiss='alert'>&times;</button>
					We've sent an email to $email.
                    Please click on the password reset link in the email to generate new password. 
			  	</div>";
	}
	else
	{
		$msg = "<div class='alert alert-danger'>
					<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Sorry!</strong>  this email not found. 
			    </div>";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>Forgot Password</title>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<link href="assset/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assset/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="assset/css/style.css" rel="stylesheet">
</head>
  <body id="login">
    <div class="container">
<div class="col-sm-4"> </div>
    		<div class="col-sm-4">
    			<div style="height: 100px"></div>
 <div class="portlet portlet-red">
<div class="portlet-heading login-heading">
<div class="portlet-title">
<h4>Forgot Password!</h4>
</div>
<div class="portlet-widgets">
<!-- <button class="btn btn-white btn-xs"><i class="fa fa-plus-circle"></i> New User</button> -->
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
      <form class="form-signin" method="post">
         
        	<?php
			if(isset($msg))
			{
				echo $msg;
			}
			else
			{
				?>
              	<div class='alert alert-warning'>
				Please enter your email address. You will receive a link to create a new password via email.!
				</div>  
                <?php
			}
			?>
        <div class="form-group">
        <input type="email" class="input-block-level form-control" placeholder="Email address" name="txtemail" required />
         </div>
     	<hr />
        <button class="btn btn-danger btn-primary" type="submit" name="btn-submit">Reset Password</button>
      </form>
</div></div></div></div>
    </div> <!-- /container -->
    <script src="assset/js/plugins/bootstrap/bootstrap.min.js"></script>
  </body>
</html>