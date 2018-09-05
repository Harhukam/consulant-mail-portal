<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.admin.php';
$user_cat = new admin();

if(!$user_cat->is_logged_in())
{
$user_cat->redirect('index');
}

$stmt = $user_cat->runQuery("SELECT * FROM  tbl_admin WHERE adminID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);



if(isset($_GET['delkey']))
{
$id = base64_decode(urldecode($_GET['delkey']));//encryption
$stmt = $user_cat->runQuery('DELETE FROM category WHERE catID =:bid');
$stmt->execute(array(':bid'=>$id));
header('location: category');
}


function slugifyUrl($str, $delimiter = '_')
{
$slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter,
 preg_replace('/[^A-Za-z0-9-]+/', $delimiter,
 preg_replace('/[&]/', 'and',
 preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
 return $slug;
} 


if(isset($_POST['save']))
{

$catName = $_POST['catname'];
$catValue = slugifyUrl($catName);

if(!isset($errMSG))
{
$stmt = $user_cat->runQuery("INSERT INTO category(catName,catValue) VALUES(:b1,:b2)");
$stmt->bindParam(':b1',$catName);
$stmt->bindParam(':b2',$catValue);
if($stmt->execute())
{
$successMSG = "Category added.";
header('refresh:1; category');
}
else
{
$errMSG = "error something wrong ! please try later...";
}
}
}

?> 

<?php include_once'include/header.php'; ?>

<div class="page-content">
<!-- begin PAGE TITLE ROW -->
<div class="row">
<div class="col-xs-12">
<div class="page-title">
<ol class="breadcrumb">
<li><i class="fa fa-dashboard"></i>  <a href="dashboard">Dashboard</a>
</li>
<li class="active">Category</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->


<div class="row">
<!-- begin LEFT COLUMN -->
<div class="col-sm-6">
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
<div class="portlet-heading">
<div class="portlet-title">
<h4>Add category </h4>
</div>
<div class="portlet-widgets">
<a data-toggle="collapse" data-parent="#accordion" href="#basicFormExample"><i class="fa fa-chevron-down"></i></a>
</div>
<div class="clearfix"></div>
</div>
<div id="basicFormExample" class="panel-collapse collapse in">
<div class="portlet-body">
<form method="post" enctype="multipart/form-data">

<div class="form-group">
<label >Category Name </label>
<input type="text" class="form-control" value="" name="catname" required="required" maxlength="45">
</div>


<hr>
<input  type="submit" class="btn btn-default " name="save" value="Add">

</form>
</div>

</div></div></div>


<div class="col-sm-6">
<h4> Availble  Categories</h3>
<?php
$stmt = $user_cat->runQuery("SELECT * FROM category ORDER BY catID DESC");
$stmt->execute();
if($stmt->rowCount() > 0)
{
while($result=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($result);
$key = urlencode(base64_encode($result['catID']));
?>


<div style="background-color: #fff; height: 30px; width: 100%; margin-top: 5px;margin-bottom: 5px; padding: 5px 5px 5px 5px;">
<?php echo $result['catName']; ?> &nbsp;&nbsp;&nbsp;<a style="float: right;" class="btn btn-xs btn-warning" href="category?delkey=<?php echo $key; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><span class="glyphicon glyphicon-trash"></span> delete</a>
</div>
<?php
}
}
else
{
?>
<div class="col-sm-12">
<span class="glyphicon glyphicon-info-sign"></span> &nbsp; 0
</div>
<?php
}  
?>
</div>


</div> <!-- row end -->
</div> <!-- /.page-content -->



</div>
</div>
</div>
</div> <!-- row end -->
</div> <!-- /.page-content -->
<!-- footer -->
<?php include('include/footer.php'); ?>
