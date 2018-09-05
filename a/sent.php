<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.admin.php';
$user_sent = new admin();

if(!$user_sent->is_logged_in())
{
$user_sent->redirect('index');
}

$stmt = $user_sent->runQuery("SELECT * FROM  tbl_admin WHERE adminID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//use for count total messages
$stmt = $user_sent->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_sent->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N'  AND app_admin_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countnotify = $stmt->fetchColumn();


if(isset($_GET['delkey']))
{
    $muteid = base64_decode(urldecode($_GET['delkey']));
    $stmt = $user_sent->runQuery("UPDATE userdatafeed SET app_admintrash='Y' WHERE app_id=:muid");
    $stmt->bindParam(":muid",$muteid);
    $stmt->execute();
    $successMSG = "Move to Trash Sucessfully.";
    header('Refresh:1; sent');
    
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
<li class="active">Sent Messages</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->
<div class="row">



<div class="portlet portlet-default">
<div class="portlet-body">

<nav class="navbar mailbox-topnav" role="navigation">
<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
<a class="navbar-brand" href="inbox"><i class="fa fa-inbox"></i> Sent Docs </a>
</div>

 <!-- <div class="mailbox-nav">
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
    <!--
<tr class="unread-message clickableRow">
<td class="checkbox-col">
<input type="checkbox" class="selectedId" name="selectedId">
</td>
<td class="from-col">Jane Smith</td>
<td class="msg-col">
<span class="label green">Orders</span> Order Status Update: Order #231
<span class="text-muted">- Hi again! I wanted to let you know that the order...</span>
</td>
<td class="date-col"><i class="fa fa-paperclip"></i> 1/1/14</td>
</tr> -->

<?php

$stmt = $user_sent->runQuery("SELECT * FROM userdatafeed WHERE app_senderid=:read AND app_admintrash='N' ORDER BY app_id DESC");
$stmt->execute(array(":read"=>$row['adminID']));
if($stmt->rowCount() > 0)
{
while($datarow=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($datarow);
$key = urlencode(base64_encode($datarow['app_id']));
?>

<tr class="clickableRow">
<td class="checkbox-col">
<input type="checkbox" class="selectedId" name="selectedId">
</td>
<td class="from-col"><a style="text-decoration:none;" href="read?message=<?php echo $key; ?>"><?php echo $datarow['app_senderName']; ?> </a></td>
<td>
<p><a style="text-decoration:none;" href="read?message=<?php echo $key; ?>"><span class="label gray"><?php echo $datarow['app_datetime']; ?> </span>&nbsp;  <?php echo $datarow['app_subject']; ?>
</a><!--<span class="text-muted">- This order ...</span>-->
</td>
<td class="date-col"><i class="fa fa-paperclip"></i><a href="#"> Download Attachement</a> </td>
<td><a style="float: right;" class="btn btn-xs btn-danger" href="sent?delkey=<?php echo $key; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><span class="glyphicon glyphicon-trash"></span></a></td>
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
