<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.user.php';
$user_user = new user();

if(!$user_user->is_logged_in())
{
$user_user->redirect('index');
}

$stmt = $user_user->runQuery("SELECT * FROM  tbl_user WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);


//use for count total messages
$stmt = $user_user->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_usertrash='N' ");
$stmt->execute(array(":abc"=>$row['userID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_user->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_usertrash='N'  AND app_user_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['userID']));
$countnotify = $stmt->fetchColumn();

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
<li class="active">page</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->
<div class="row">






</div>
</div>
</div>
</div> <!-- row end -->
</div> <!-- /.page-content -->
<!-- footer -->
<?php include('include/footer.php'); ?>
