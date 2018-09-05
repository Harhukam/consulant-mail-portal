<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.admin.php';
$user_allusers = new admin();

if(!$user_allusers->is_logged_in())
{
$user_allusers->redirect('index');
}

$stmt = $user_allusers->runQuery("SELECT * FROM  tbl_admin WHERE adminID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);


//use for count total messages
$stmt = $user_allusers->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_allusers->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N'  AND app_admin_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countnotify = $stmt->fetchColumn();



if(isset($_GET['delkey']))
{

$delid = base64_decode(urldecode($_GET['delkey']));
//$stmt_delete = $user_allusers->runQuery("DELETE FROM tbl_user WHERE userID =:userid");
//$stmt_delete = $user_allusers->runQuery("DELETE tbl_user,userdatafeed FROM tbl_user INNER JOIN userdatafeed ON userdatafeed.app_client_id = tbl_user.userID  WHERE tbl_user.userID =:userid");
//$stmt_delete->execute(array(":userid"=>$delid));
//$successMSG = "User Delete Successfully ";
//header('refresh:1; allusers');


    $stmt = $user_allusers->runQuery("UPDATE tbl_user SET userStatus='N'  WHERE userID=:udid");
    $stmt->bindParam(":udid",$delid);
    $stmt->execute();
    
    $stmt = $user_allusers->runQuery("UPDATE userdatafeed SET app_usertrash	='Y' , app_admintrash ='Y'  WHERE app_client_id=:chid");
    $stmt->bindParam(":chid",$delid);
    $stmt->execute();
    
    $stmt = $user_allusers->runQuery("UPDATE userdatafeed SET app_admintrash ='Y' , app_usertrash ='Y' WHERE app_senderID=:chid");
    $stmt->bindParam(":chid",$delid);
    $stmt->execute();
    
    $successMSG = "User Deactivate ";
    header('refresh:1; allusers');

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
<li class="active">All User(s)</li>
   
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->
<div class="row">



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
<h4>List of Register Users : <?php echo $allusercount; ?></h4>
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
  
<tr>
<!-- <th>User_id</th> -->
<th>Client ID</th>
<th>Client Name</th>
<th>Mobile No.</th>
<th>E-mail ID</th>
<th>File Type</th>
<th>Account Status</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>
<tbody>
<?php
$stmt = $user_allusers->runQuery("SELECT * FROM tbl_user  ORDER BY userID DESC");
$stmt->execute();
if($stmt->rowCount() > 0)
{
while($usersrow=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($usersrow);
$key = urlencode(base64_encode($usersrow['userID']));
?>
<tr>
<td><?php echo $usersrow['userID']; ?></td>
<td><?php echo $usersrow['userName']; ?></td>
<td><?php echo $usersrow['userMobile']; ?></td>
<td><?php echo $usersrow['userEmail']; ?></td>
<td><?php echo $usersrow['userCat']; ?></td>

<?php 
if($usersrow['userStatus']=="Y"){
?>
<td> <span style="color:green; font-weight:bold;"> <?php echo $usersrow['userStatus']; ?>  </span></td>
<?php }  else{
 ?>
 <td> <span style="color:red; font-weight:bold;"> <?php echo $usersrow['userStatus']; ?>  </span></td>
<?php
}
?>

<td><a style="float: right;" class="btn btn-xs btn-info" href="updateuser?uptkey=<?php echo $key; ?>"><span class="glyphicon glyphicon-edit"></span> edit</a></td>
<td><a style="float: right;" class="btn btn-xs btn-danger" href="allusers?delkey=<?php echo $key; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><span class="glyphicon glyphicon-trash"></span> delete</a></td>
</tr>
<?php
}
}
else
{
?>

<tr> <td colspan="9">
<span style="color: red" class="glyphicon glyphicon-info-sign"></span> &nbsp; No User found.
</td></tr>

<?php
}  
?>
</tbody>
</table>
</div>
</div>
</div>
<!-- /.portlet -->
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
