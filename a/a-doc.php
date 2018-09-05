<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.admin.php';
$user_admin = new admin();

if(!$user_admin->is_logged_in())
{
$user_admin->redirect('index');
}

$stmt = $user_admin->runQuery("SELECT * FROM  tbl_admin WHERE adminID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

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
