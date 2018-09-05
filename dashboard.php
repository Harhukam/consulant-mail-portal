<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.user.php';
$user_dashboard = new USER();

if(!$user_dashboard->is_logged_in())
{
$user_dashboard->redirect('index');
}

$stmt = $user_dashboard->runQuery("SELECT * FROM  tbl_user WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

  $url1=$_SERVER['REQUEST_URI'];
    header("Refresh: 25; URL=$url1");
    
//use for count total messages
$stmt = $user_dashboard->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_usertrash='N' ");
$stmt->execute(array(":abc"=>$row['userID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_dashboard->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_usertrash='N'  AND app_user_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['userID']));
$countnotify = $stmt->fetchColumn();


if(isset($_GET['delkey']))
{
    $muteid = base64_decode(urldecode($_GET['delkey']));
    $stmt = $user_dashboard->runQuery("UPDATE userdatafeed SET app_usertrash='Y' WHERE app_id=:muid");
    $stmt->bindParam(":muid",$muteid);
    $stmt->execute();
    $successMSG = "Mail Delete Successfully ";
    header('Refresh:1; dashboard');

}

?> 

<?php include_once'include/header.php'; ?>
<div class="page-content">
<!-- begin PAGE TITLE ROW -->
<div class="row">
<div class="col-md-12 col-xs-12">
<div class="page-title">
<ol class="breadcrumb">
<li><i class="fa fa-dashboard"></i>  <a href="dashboard">Dashboard</a>
</li>
<li class="active">Inbox</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->
<div class="row">


<div class="col-md-12 col-sm-6 col-xs-12 visible-xs">
    <?php

$stmt = $user_dashboard->runQuery("SELECT * FROM userdatafeed WHERE app_client_id=:read AND app_usertrash='N' ORDER BY app_id DESC");
$stmt->execute(array(":read"=>$row['userID']));
if($stmt->rowCount() > 0)
{
while($datarow=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($datarow);
$key = urlencode(base64_encode($datarow['app_id']));
?>

<div class= "alert alert-info">
<p><span style="font-size:small; font-weight:bold;"><?php echo $datarow['app_subject']; ?></span></p>
<p><span><?php echo $datarow['app_message']; ?></span></p>
<p><span style="font-size:x-small; font-weight:bold;"><?php echo $datarow['app_senderName']; ?> </span>  </p>
<p><span style="font-size:x-small; font-weight:bold;"><?php echo $datarow['app_datetime']; ?> </span> </p> 
<p><span style="font-size:x-small;"><a  href="download.php?file=<?php echo $datarow['app_document'];?>" <i class="fa fa-paperclip"></i> Download Attachment</a>
</span>  <a style="float: right;"  href="dashboard?delkey=<?php echo $key; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><span class="glyphicon glyphicon-trash"></span></a></p>
 
 </div>
<?php
}
}
else
{
?>

<div class="well well-info">
<span style="color: red" class="glyphicon glyphicon-info-sign"></span> &nbsp; 0 Message 
</div>

<?php
}  
?>

</div>
    
<div class="col-md-12 col-sm-6 col-xs-12 hidden-xs">

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
<div class="portlet portlet-default">
<div class="portlet-body">

<nav class="navbar mailbox-topnav" role="navigation">
<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
<a class="navbar-brand" href="inbox"><i class="fa fa-inbox"></i> Inbox</a>
</div>

<!--  <div class="mailbox-nav">
<ul class="nav navbar-nav button-tooltips">
<li class="checkall">
<input type="checkbox" id="selectall" data-toggle="tooltip" data-placement="bottom" title="Select All">
</li>
<li class="message-actions">
<div class="btn-group navbar-btn">
<button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom" title="Archive"><i class="fa fa-archive"></i>
</button>
<button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom" title="Mark as Important"><i class="fa fa-exclamation-circle"></i>
</button>
<button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom" title="Trash"><i class="fa fa-trash-o"></i>
</button>
</div>
</li>
<li class="dropdown message-label">
<button type="button" class="btn btn-white navbar-btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tag"></i>  <i class="fa fa-caret-down text-muted"></i>
</button>
<ul class="dropdown-menu">
<li><a href="#"><i class="fa fa-square text-green"></i> Purchase Orders</a>
</li>
<li><a href="#"><i class="fa fa-square text-orange"></i> Current Projects</a>
</li>
<li><a href="#"><i class="fa fa-square text-purple"></i> Work Groups</a>
</li>
<li><a href="#"><i class="fa fa-square text-blue"></i> Personal</a>
</li>
<li><a href="#"><i class="fa fa-square-o"></i> None</a>
</li>
</ul>
</li>
</ul>
<form class="navbar-form navbar-right visible-lg" role="search">
<div class="form-group">
<input type="text" class="form-control" placeholder="Search Mail...">
</div>
<button type="submit" class="btn btn-default"><i class="fa fa-search"></i>
</button>
</form>
</div> -->
</nav>

<!--  <div id="mailbox"> 

<ul class="nav nav-pills nav-stacked mailbox-sidenav">
<li><a class="btn btn-white" href="compose-message.html"><i class="fa fa-edit"></i> Compose Message</a>
</li>
<li class="nav-divider"></li>
<li class="mailbox-menu-title text-muted">Folder</li>
<li class="active"><a href="#">Inbox (15)</a>
</li>
<li><a href="#">Sent</a>
</li>
<li><a href="#">Drafts</a>
</li>
<li><a href="#">Spam</a>
</li>
<li><a href="#">Trash</a>
</li>
<li class="nav-divider"></li>
<li class="mailbox-menu-title text-muted">Labels</li>
<li><a href="#"><i class="fa fa-square text-green"></i> Purchase Orders</a>
</li>
<li><a href="#"><i class="fa fa-square text-orange"></i> Current Projects</a>
</li>
<li><a href="#"><i class="fa fa-square text-purple"></i> Work Groups</a>
</li>
<li><a href="#"><i class="fa fa-square text-blue"></i> Personal</a>
</li>
<li><a href="#"><i class="fa fa-plus"></i> Create New Label</a>
</li>
</ul>

<div id="mailbox-wrapper"> -->

<div class="table-responsive mailbox-messages">
<table class="table table-bordered  table-hover">
<tbody>
<?php

$stmt = $user_dashboard->runQuery("SELECT * FROM userdatafeed WHERE app_client_id=:read AND app_usertrash='N' ORDER BY app_id DESC");
$stmt->execute(array(":read"=>$row['userID']));
if($stmt->rowCount() > 0)
{
while($datarow=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($datarow);
$key = urlencode(base64_encode($datarow['app_id']));
?>

<tr class=" <?php echo $datarow['app_user_read_status']; ?>   clickableRow">
<td class="checkbox-col">
<input type="checkbox" class="selectedId" name="selectedId">
</td>
<td class="from-col"><p><a style="text-decoration:none;" href="read?message=<?php echo $key; ?>"><?php echo $datarow['app_senderName']; ?></a></p></td>
<td>
<p><a style="text-decoration:none;" href="read?message=<?php echo $key; ?>"><span class="label gray"><?php echo $datarow['app_datetime']; ?> </span>&nbsp;  <?php echo $datarow['app_subject']; ?> </a></p>
<!--<span class="text-muted">- This order ...</span>-->
</td>
  <td><a style="float: right;" class="btn btn-xs btn-danger" href="dashboard?delkey=<?php echo $key; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><span class="glyphicon glyphicon-trash"></span></a></td>
</tr>

<?php
}
}
else
{
?>

<tr class="clickableRow">
<td class="from-col"><span style="color: red" class="glyphicon glyphicon-info-sign"></span> &nbsp; 0 Message </td>
</tr>

<?php
}  
?>

</table>
</div>

</div>
</div>

</div>
</div>
</div>


</div>
</div>
</div>
</div> <!-- row end -->
</div> <!-- /.page-content -->
<!-- footer -->
<?php include('include/footer.php'); ?>
