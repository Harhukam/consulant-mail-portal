<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.user.php';
$user_read = new user();

if(!$user_read->is_logged_in())
{
$user_read->redirect('index');
}


$stmt = $user_read->runQuery("SELECT * FROM  tbl_user WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);


//use for count total messages
$stmt = $user_read->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_usertrash='N' ");
$stmt->execute(array(":abc"=>$row['userID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_read->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_usertrash='N'  AND app_user_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['userID']));
$countnotify = $stmt->fetchColumn();


if(isset($_GET['message']) && !empty($_GET['message'])) 
{
    
$id = base64_decode(urldecode($_GET['message']));
$stmt = $user_read->runQuery("SELECT * FROM userdatafeed WHERE app_id =:read ");
$stmt->execute(array(":read"=>$id));
$urow = $stmt->fetch(PDO::FETCH_ASSOC);
extract($urow);

    $change="read";
    $stmt = $user_read->runQuery("UPDATE userdatafeed SET app_user_read_status=:c1 WHERE app_id=:chid");
    $stmt->bindParam(":c1",$change);
    $stmt->bindParam(":chid",$id);
    $stmt->execute();
    
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
<li class="active">Message</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->
<div class="row">


<div class="col-lg-12">
<div class="portlet portlet-default">
<div class="portlet-heading">
<div class="portlet-title">
<h6>From : <?php echo $urow['app_senderName'];?> < <?php echo $urow['app_senderEmail'];?> ></h6>
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
<b><span style="color: #34495E; font-weight: bold;">Subject</span> : </b> 
<b> <?php echo $urow['app_subject'];?> </b>
<hr>

 <div id="summernote" readonly="true">

<p>
<?php echo $urow['app_message'];?>
</p>

<br>
<br>------
<br>
<!--<img srcset="images/maillogo.png"><br> -->
 <p>
<strong><?php echo $urow['app_senderName'];?></strong><br>
<small><?php echo $urow['app_senderEmail'];?></small><br>
<small> Message date : <?php echo $urow['app_datetime']; ?></small> </p>
</div>
</div>
<div class="portlet-footer">
<div class="btn-toolbar" role="toolbar">
 <a href="compose" class="btn btn-green"><i class="fa fa-envelope"></i> Reply</a>
<!-- <button class="btn btn-red pull-right"><i class="fa fa-times"></i> Discard</button> -->
<?php
if($urow['app_document'] != "0.png"){
?>
<a class="btn btn-green pull-right" target="_blank" href="download.php?file=<?php echo $urow['app_document'];?>" <i class="fa fa-paperclip"></i> Download Attachment</a>
<?php
}
?>
</div>
</div>
</div>
</div>
</div> <!-- row end -->
</div> <!-- /.page-content -->
<!-- footer -->
<?php include('include/footer.php'); ?>