<?php
session_start();
require_once 'class.admin.php';
$user_update = new admin();

if(!$user_update->is_logged_in())
{
$user_update->redirect('index');
}



$stmt = $user_update->runQuery("SELECT * FROM  tbl_admin WHERE adminID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//use for count total messages
$stmt = $user_update->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_update->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N'  AND app_admin_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countnotify = $stmt->fetchColumn();


error_reporting( ~E_NOTICE );

if(isset($_GET['uptkey']) && !empty($_GET['uptkey'])) 
{
$id = base64_decode(urldecode($_GET['uptkey']));
$stmt = $user_update->runQuery("SELECT * FROM tbl_user WHERE userID =:pid");
$stmt->execute(array(':pid'=>$id));
$urow = $stmt->fetch(PDO::FETCH_ASSOC);
extract($urow);
}


if(isset($_POST['update']))
{
	$uname      = $_POST['uname'];
	$umob       = $_POST['umob'];
	$uemail     = $_POST['uemail'];
	$ustatus    = $_POST['ustatus'];
	$ucategory       = $_POST['ucategory'];
	$uaddress   = $_POST['uaddress'];
	$updatedate = $datetime;
	
/*
	if(isset($umail)){
	$stmt = $user_update->runQuery("SELECT userEmail FROM  tbl_user WHERE userEmail=:mail");
    $row =  $stmt->execute(array(":mail"=>$umail));
    if($stmt->rowCount() > 0)
	{
    $errMSG = "Email already used.";
	}
	}
	

	if(isset($umob)){
	$stmt = $user_update->runQuery("SELECT userMobile FROM  tbl_user WHERE userMobile=:mob");
    $row = $stmt->execute(array(":mob"=>$umob));
    if($stmt->rowCount() > 0)
	{
    $errMSG = "Mobile Number already used";
	}
	}   */
	
	
	if(empty($uname)){
      $errMSG = "Fill Username";
    }

    if(empty($umob)){
      $errMSG = "Fill Mobile Number";
    }

    if(empty($uemail)){
     $errMSG = "Fill E-mail ID";
    }

   /* if(empty($ucat)){
     $errMSG = "Select Category";
     }*/
	
        if(!isset($errMSG))
        {
$stmt = $user_update->runQuery("UPDATE tbl_user SET userName=:b1,userEmail=:b2,userMobile=:b3,userCat=:b4,userStatus=:b5,userAddress=:b6,userUpdateDateTime =:b7 WHERE userID=:uptid ");
        $stmt->bindParam(":b1",$uname);
        $stmt->bindParam(":b2",$uemail);
        $stmt->bindParam(":b3",$umob);
        $stmt->bindParam(":b4",$ucategory);
        $stmt->bindParam(":b5",$ustatus);
        $stmt->bindParam(":b6",$uaddress);
        $stmt->bindParam(":b7",$updatedate);
        $stmt->bindParam(":uptid",$id);
        if($stmt->execute()){
    
        $successMSG = "Update Successfully ";
        header('refresh:1; allusers');
         }
         else
        {
        $errMSG = "error! please try again...";
        }
        }
        }
        ?> 


<?php include_once'include/header.php'; ?>

<div class="page-content">
<!-- begin PAGE TITLE ROW -->
<div class="row">
<div class="col-lg-12">
<div class="page-title">
<ol class="breadcrumb">
<li><i class="fa fa-dashboard"></i>  <a href="dashboard">Dashboard</a>
</li>
<li class="active">Add User</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->
<div class="row">


<!-- begin LEFT COLUMN -->
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
<form method="post" enctype="multipart/form-data" >
<div class="portlet portlet-default">
<div class="portlet-heading">
<div class="portlet-title">
<h4>Update User Profile</h4>
</div>
<div class="portlet-widgets">
<a data-toggle="collapse" data-parent="#accordion" href="#basicFormExample"><i class="fa fa-chevron-down"></i></a>
</div>
<div class="clearfix"></div>
</div>
<div id="basicFormExample" class="panel-collapse collapse in">
<div class="portlet-body">

<div class="form-group">
<label for="exampleInputEmail1">Full Name</label>
<input type="text" class="form-control"  placeholder="Full name" value="<?php echo $urow['userName']; ?>" name="uname"
required="required">
</div>


<div class="form-group">
<label for="exampleInputEmail1">Mobile No. (10 digit)</label>
<input type="number" class="form-control"  placeholder="9815098150" name="umob" maxlength="10" required="required" value="<?php echo $urow['userMobile']; ?>">
</div>


<div class="form-group">
<label for="exampleInputEmail1">E-mail ID</label>
<input type="email" class="form-control" id="exampleInputEmail1" name="uemail" placeholder="eg : username@domain.com" required="required" value="<?php echo $urow['userEmail']; ?>">
</div>


<div class="form-group">
<label for="exampleInputEmail1">Application Type <span style="color: red;">Currunt is :</span><span style="color: #222;"> <?php echo $urow['userCat']; ?> </span></label>
<select class="form-control" type="text"  name="ucategory" value="<?php echo $urow['userCat']; ?>" required="required">
<option value="<?php echo $urow['userCat']; ?>">Select for change</option>
<?php
$stmt = $user_update->runQuery("SELECT * FROM category ORDER BY catID ASC ");
$stmt->execute();
if($stmt->rowCount() > 0)
{
while($catrow=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($catrow);
?>
<option value="<?php echo $catrow['catValue']; ?>"><?php echo $catrow['catName']; ?></option>
<?php
}
}
else
{
?>
<div class="col-sm-12">
<span class="glyphicon glyphicon-info-sign"></span> &nbsp; Not found...
</div>
<?php
}  
?>
</select>
</div>


<div class="form-group">
<label for="exampleInputEmail1">Account Status Y/N <span style="color: red;">Currunt is :</span> <span style="color:#222;"><?php echo $urow['userStatus']; ?></span></label>
<select class="form-control" type="text"  name="ustatus" value="<?php echo $urow['userStatus']; ?>"  required="required">
<option value="<?php echo $urow['userStatus']; ?>" >Select for change</option>
<option value="Y">Y</option>
<option value="N">N</option>
</select>
</div>


<div class="form-group">
<label for="exampleInputEmail1">Address</label>
<textarea name="uaddress" class="form-control" required="required"><?php echo $urow['userAddress']; ?></textarea>
</div>


<input  type="submit" class="btn btn-default " name="update" value="Submit">
</form>
</div>

</div>
</div></div>


<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

</div>
<!-- /.col-8 -->
<!-- /.col-lg-12 (nested) -->


</div>
</div>

</div>
</div> <!-- row end -->
</div> <!-- /.page-content -->
<!-- footer -->
<?php include('include/footer.php'); ?>