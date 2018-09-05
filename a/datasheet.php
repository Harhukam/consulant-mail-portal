 <?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.admin.php';
$user_sheet = new admin();

if(!$user_sheet->is_logged_in())
{
$user_sheet->redirect('index');
}

$stmt = $user_sheet->runQuery(" SELECT * FROM  tbl_admin WHERE adminID=:uid ");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_GET['delkey']))
{
    $muteid = base64_decode(urldecode($_GET['delkey']));
    $stmt = $user_sent->runQuery("UPDATE userdatafeed SET app_admintrash='Y' WHERE app_id=:muid");
    $stmt->bindParam(":muid",$muteid);
    $stmt->execute();
    $successMSG = "Move to Trash Sucessfully.";
    header('Refresh:1; datasheet');
    
}

//use for count total messages
$stmt = $user_sheet->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N' ");
$stmt->execute(array(":abc"=>$_SESSION['adminSession']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_sheet->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N'  AND app_admin_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$_SESSION['adminSession']));
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
<h4>List of messages you sent to this user : </h4>
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
  

</thead>
<tbody>
<?php
if(isset($_GET['v']) && !empty($_GET['v'])) 
{
$sid = urldecode(base64_decode($_GET['v']));

//print_r($sid);
$stmt = $user_sheet->runQuery("SELECT * FROM userdatafeed WHERE app_client_id = :uid AND app_senderID= :sid AND app_admintrash='N' ORDER BY app_id DESC");
$stmt->execute(array(":uid"=>$sid,":sid"=>$_SESSION['adminSession']));
}
if($stmt->rowCount() > 0)
{
while($datarow=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($datarow);
$key = urlencode(base64_encode($datarow['app_id']));
?>


<style>
    .nodeco{
        text-decoration:none !important;
    }
</style>
<tr class="<?php echo $datarow['app_admin_read_status']; ?>  clickableRow">
<td class="checkbox-col">
<input type="checkbox" class="selectedId" name="selectedId">
</td>
<td class="from-col"><a class="nodeco" href="read?message=<?php echo $key; ?>" > <?php echo $datarow['app_senderName']; ?> </a></td>
<td><a class="nodeco" href="read?message=<?php echo $key; ?>" >
<span class="label gray"><?php echo $datarow['app_datetime']; ?></span> <?php echo $datarow['app_subject']; ?> </a>
<!--<span class="text-muted">- This order ...</span>-->
</td>
<td class="date-col"><i class="fa fa-paperclip"></i><a href="download.php?file=<?php echo $datarow['app_document'];?>"> Download Attachement</a> </td>
</tr>


<?php
}
}
else
{
?>

<tr> <td >
<span style="color: red" class="glyphicon glyphicon-info-sign"></span> &nbsp; No data found.
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