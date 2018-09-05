<?php
session_start();
require_once 'class.admin.php';
$user_settings = new  ADMIN();

if(!$user_settings->is_logged_in())
{
$user_settings->redirect('index.php');
}


$stmt = $user_settings->runQuery("SELECT * FROM  tbl_admin WHERE adminID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//extract($row);

//use for count total messages
$stmt = $user_settings->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_settings->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N'  AND app_admin_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countnotify = $stmt->fetchColumn();

if(isset($_POST['user-update']))
{

$adminName = $_POST['sname'];
$adminEmail = $_POST['semail'];

$adminPhoto = $_FILES['apic']['name'];
$tmp_dir = $_FILES['apic']['tmp_name'];
$imgSize = $_FILES['apic']['size'];



if($adminPhoto)
{
$upload_dir = 'images/'; // upload directory
$imgExt = strtolower(pathinfo($adminPhoto,PATHINFO_EXTENSION)); // get image extension
$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
$adminPhoto = rand(1000,1000000).".".$imgExt;// rename uploading image
if(in_array($imgExt, $valid_extensions))
{           
if($imgSize < 5000000)  // Check file size '5MB'            
{
move_uploaded_file($tmp_dir,$upload_dir.$adminPhoto);
}  
else
{
$errMSG = "Sorry, your file is too large it should be less then 5MB";
}
}
else
{
$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";        
}   
}
else
{
$adminPhoto = $row['adminPhoto']; // old image from database
}            


// if no error occured, continue ....
if(!isset($errMSG))
{
$stmt = $user_settings->runQuery('UPDATE tbl_admin SET adminName=:name, adminEmail=:email, adminPhoto=:photo WHERE adminID=:id');
$stmt->bindParam(':name',$adminName);
$stmt->bindParam(':email',$adminEmail);
$stmt->bindParam(':photo',$adminPhoto);
$stmt->bindParam(':id',$row['adminID']);
if($stmt->execute())
{
$successMSG = "update Successfully";
header('refresh:1;  settings');
}
else
{
$errMSG = "error something wrong ! please try later...";
}
}
}









if(isset($_POST['pass-update']))
{

$cpass = $_POST['cpass'];
$npass = $_POST['npass'];

if($row['adminPass']==md5($_POST['cpass']))
{


if(!isset($errMSG))
{
$newpass = md5($npass);
$stmt = $user_settings->runQuery('UPDATE tbl_admin SET adminPass=:npass WHERE adminID=:cpid');
$stmt->bindParam(':npass',$newpass);
$stmt->bindParam(':cpid',$row['adminID']);
if($stmt->execute())
{
$successMSG = "Password Successfully changed";
header('refresh:1;  logout');
}
else
{
$errMSG = "error something wrong ! please try later...";
}
}

}
else
{
$errMSG = "Currunt Password not match";
}

}

?> 
<?php include('include/header.php'); ?>
<div class="page-content">
<!-- begin PAGE TITLE ROW -->
<div class="row">
<div class="col-lg-12">
<div class="page-title">

<ol class="breadcrumb">
<li><i class="fa fa-dashboard"></i>  <a href="#">Dashboard</a>
</li>
<li class="active">user settings</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->


<!-- Pill Tabs Example -->
<div class="row">
<div class="col-lg-12">
<?php
if(isset($errMSG))
{
?>
<div class="alert alert-danger">
<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
</div>
<?php
}
else if(isset($successMSG))
{
?>
<div class="alert alert-success">
<strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
</div>
<?php
}
?> 
<div class="portlet portlet-green">
<div class="portlet-heading">
<div class="portlet-title">
<h4>Settings</h4>
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
<div class="row">
<div class="col-sm-5 shedow">
<form method="post" enctype="multipart/form-data">
<h4 class="page-header"><b>Personal Information:</b></h4>

<div class="row">
<div class="col-sm-6">
<img class="img-responsive" style="width: 100px; height: 100px; margin-right: : 100px; " src="images/<?php echo $row['adminPhoto']; ?>" >
</div>

<div class="col-sm-6" >
<img id="blah" src="#" alt="." onerror="this.style.display='none'"  style=" max-width: 100px; max-height: 100px;" />
</div>

</div>

<div class="form-group">
<label>Choose a New Image</label>
<input type="file" name="apic" id="imgInp" accept="images/*">
</div>

<div class="form-group">
<label>Full Name</label>

<input type="text" class="form-control input-sm" name="sname" required="required" value="<?php echo $row['adminName']; ?>">
</div>

<div class="form-group">
<label><i class="fa fa-envelope-o fa-fw"></i> Email Address</label>
<input  type="email" class="form-control input-sm" name="semail" required="required" value="<?php echo $row['adminEmail']; ?>">
</div>


<br>
<input type="submit" name="user-update"  class="btn btn-green btn-sm" value="Save">
<input type="submit"   class="btn btn-red btn-sm" value="Cancel">
</form>

</div> <!-- inner 6 col end -->




<div class=" col-sm-1 "> </div>
<div class=" col-sm-4 shedow">
<form method="post" enctype="multipart/form-data">
<h4 class="page-header"><b>Change Password :</b></h4>

<div class="form-group">
<label>Currunt Password</label>

<input type="password" class="form-control input-sm" name="cpass" required="required" >
</div>

<div class="form-group">
<label>New Password</label>

<input type="password" class="form-control input-sm" name="npass" required="required" >
</div>

<br>
<input type="submit" name="pass-update"  class="btn btn-green btn-sm" value="Save">
<input type="submit"   class="btn btn-red btn-sm" value="Cancel">
</form>

</div> <!-- inner 6 col end -->
</div> <!-- row end -->




</div>  <!-- /.portlet-body -->
</div><!-- /.portlet -->
</div>  <!-- /.col-lg-12 -->
</div> <!-- /.row -->

</div> <!-- /.page-content -->
</div><!-- end MAIN PAGE CONTENT -->  <!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<?php include('include/footer.php'); ?>