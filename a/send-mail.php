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


//use for count total messages
$stmt = $user_admin->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_admin->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N'  AND app_admin_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countnotify = $stmt->fetchColumn();




function slugifyUrl($str, $delimiter = '-')
{
$slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter,
 preg_replace('/[^A-Za-z0-9-]+/', $delimiter,
 preg_replace('/[&]/', 'and',
 preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
 return $slug;
} 

if(isset($_POST['submit']))
{


$app_client_id ="12345";
$app_name      ="Harhukam Singh";
$app_mobile    ="9463710716";
$app_address   ="Vill jalpot";
 $app_cat      = $_POST['appcat'];
$app_subject   = $_POST['appsubject'];
$app_message   = $_POST['appmessage'];
$app_email     = $_POST['appemail'];
$app_status    = $_POST['appstatus'];
$app_permalink = slugifyUrl($app_subject);
$app_author    = $row['adminName'];


$document = $_FILES['doc_file']['name'];
$tmp_dir = $_FILES['doc_file']['tmp_name'];
$fileSize = $_FILES['doc_file']['size'];  

if(empty($document)){
$errMSG = "Please choose attachedment file";
}
else
{

$upload_dir = '../uploads/'; // upload directory
$documentExt = strtolower(pathinfo($document,PATHINFO_EXTENSION)); 
$valid_extensions = array("jpg","txt","pdf","psd", "jpeg","docs","docx","rar","mp3","zip","zip7", "gif", "png", "mp4", "wma"); 

$document = slugifyUrl($app_subject).".".$documentExt;
if(in_array($documentExt, $valid_extensions))
{  
if($fileSize < 5000000000)  // Check file size '5MB'
{

move_uploaded_file($tmp_dir, $upload_dir.$document);

}
else{
$errMSG = "Sorry, your file is too large.";
}
}
else
{
$errMSG = "Sorry, this file type not allow to upload";        
}
}

if(!isset($errMSG))
{
    
    
$stmt = $user_admin->runQuery("INSERT INTO userdatafeed(app_client_id,app_name,app_mobile,app_address,app_cat,app_email,app_subject,app_message,app_document,app_status,app_permalink,app_author) VALUES(:b1,:b2,:b3,:b4,:b5,:b6,:b7,:b8,:b9,:b10,:b11,:b12)");

$stmt->bindParam(':b1',$app_client_id);
$stmt->bindParam(':b2',$app_name);
$stmt->bindParam(':b3',$app_mobile);
$stmt->bindParam(':b4',$app_address);
$stmt->bindParam(':b5',$app_cat);
$stmt->bindParam(':b6',$app_email);
$stmt->bindParam(':b7',$app_subject);
$stmt->bindParam(':b8',$app_message);
$stmt->bindParam(':b9',$document);
$stmt->bindParam(':b10',$app_status);
$stmt->bindParam(':b11',$app_permalink);
$stmt->bindParam(':b12',$app_author);
//print_r($stmt);
if($stmt->execute())
{
    
        
        $email = $app_email;
        $subject = $app_subject;
        $message = '<html><body>';
        $message .= "
        Hello   $app_email ;  
        $app_message ";
        $message .= '</body></html>';
        
        $user_admin->send_mail($email,$message,$subject); 

$successMSG = "Operate Successfully ";
header('refresh:1; dashboard');
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
<div class="col-lg-12">
<div class="page-title">
<ol class="breadcrumb">
<li><i class="fa fa-dashboard"></i>  <a href="dashboard">Dashboard</a>
</li>
<li class="active">New Post</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->


<div class="row">
<!-- begin LEFT COLUMN -->
<div class="col-sm-9">
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
<form  id="postForm" onsubmit="return postForm()" method="post" enctype="multipart/form-data" accept-charset="utf-8" >
<div class="portlet portlet-default">
<div class="portlet-heading">
<div class="portlet-title">
<h4>New Post</h4>
</div>
<div class="portlet-widgets">
<a data-toggle="collapse" data-parent="#accordion" href="#basicFormExample"><i class="fa fa-chevron-down"></i></a>
</div>
<div class="clearfix"></div>
</div>
<div id="basicFormExample" class="panel-collapse collapse in">
<div class="portlet-body">



<div class="form-group">
<label >Subject </label>
<input type="text" class="form-control" name="appsubject" required/>
</div>


<div class="form-group">
<label >Message body</label>
<textarea type="text" id="summernote" name="appmessage" onkeyup='swap_val(this.value);' ></textarea>

</div>

</div>

</div>
</div>
</div>

<div class="col-sm-3 well well-info">

<div class="form-group">
<label for="exampleInputEmail1">Select Category</label>
<select class="form-control" type="text"  name="appcat">
<option value="" selected disabled>Choose</option>
<option value="Study_visa" >Study Visa</option>
<option value="work_visa" >Work Visa</option>
<option value="Tourist_visa" >Tourist Visa</option>

</select>
</div>

<div class="form-group">
<label >To email</label>
<input type="text" name="appemail" class="form-control" value="harhukams@gmail.com">
</div> 


<div class="form-group">
<label for="exampleInputEmail1">Set Status</label>
<select class="form-control" type="text"  name="appstatus">
<option value="" selected disabled>Choose</option>
<option value="Initlize" >Initlizing</option>
<option value="Process" >In Processing</option>
<option value="Complete" >Complete</option>
</select>
</div>




<div class="form-group">
<label>Select Attachedment file </label>
<input   type="file" class="form-control"  name="doc_file"  id="upload_file" >

<div class='progress' id="progress_div">
<div class='bar' id='bar1'></div>
<div class='percent' id='percent1'>0%</div>

</div>


<hr> <hr>
<div class="panel pane-body">
<input  type="submit" class="btn btn-default btn-block " name="submit" value="Post"  onclick='upload_file();'>
</div>

</div>

</form>
</div> <!-- row end -->
</div> <!-- /.page-content -->

<script type="text/javascript">
$(document).ready(function() {
$('#summernote').summernote({
height: "250px"
});
});

var postForm = function() {
var content = $('textarea[name="appmessage"]').html($('#summernote').code());
}
</script>


<!-- footer -->
<?php include('include/footer.php'); ?>
