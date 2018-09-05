<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.user.php';
$user_compose = new user();

if(!$user_compose->is_logged_in())
{
$user_compose->redirect('index');
}

$stmt = $user_compose->runQuery("SELECT * FROM  tbl_user WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//extract($row);


//use for count total messages
$stmt = $user_compose->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_usertrash='N' ");
$stmt->execute(array(":abc"=>$row['userID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_compose->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_usertrash='N'  AND app_user_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['userID']));
$countnotify = $stmt->fetchColumn();


if(isset($_POST['submit']))
{


$app_client_id     =  $row['userKey'];
$app_name          =  $row['userName'];
$app_mobile        =  $row['userMobile'];
$app_email         =  $row['userEmail'];
$app_cat           =  $row['userCat'];
$app_status        =  $row['userStatus'];
$app_subject       =  "$app_cat file related document ";
$app_message       =  $_POST['appmessage'];
$app_senderid      =  $row['userID'];
$app_sendername    =  $row['userName'];
$app_senderemail    =  $row['userEmail'];
$app_date          =  $date;
$app_datetime      =  $datetime;
$app_admin_read_status = "unread-message";


$document = $_FILES['doc_file']['name'];
$tmp_dir = $_FILES['doc_file']['tmp_name'];
$fileSize = $_FILES['doc_file']['size'];  

if(empty($app_message)){
$errMSG = "Message Required.";
}

if(empty($document)){
    $document= "0.png";
//$errMSG = "Please choose attachedment file";
}
else
{

$upload_dir = 'uploads/'; // upload directory
$documentExt = strtolower(pathinfo($document,PATHINFO_EXTENSION)); 
$valid_extensions = array("jpg","txt","pdf","psd", "jpeg","docs","docx","rar","mp3","zip","zip7", "gif", "png", "mp4", "wma"); 

$document = $num= rand(10000, 999999).".".$documentExt;
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
    
$stmt = $user_compose->runQuery("INSERT INTO userdatafeed(app_client_id,app_name,app_mobile,app_email,app_cat,app_subject,app_message,app_document,app_status,app_date,app_datetime,app_senderID,app_senderName,app_senderEmail,app_admin_read_status) VALUES(:b1,:b2,:b3,:b4,:b5,:b6,:b7,:b8,:b9,:b10,:b11,:b12,:b13,:b14,:b15)");
$stmt->bindParam(':b1',$app_client_id);
$stmt->bindParam(':b2',$app_name);
$stmt->bindParam(':b3',$app_mobile);
$stmt->bindParam(':b4',$app_email);
$stmt->bindParam(':b5',$app_cat);
$stmt->bindParam(':b6',$app_subject);
$stmt->bindParam(':b7',$app_message);
$stmt->bindParam(':b8',$document);
$stmt->bindParam(':b9',$app_status);
$stmt->bindParam(':b10',$app_date);
$stmt->bindParam(':b11',$app_datetime);
$stmt->bindParam(':b12',$app_senderid);
$stmt->bindParam(':b13',$app_sendername);
$stmt->bindParam(':b14',$app_senderemail);
$stmt->bindParam(':b15',$app_admin_read_status);
if($stmt->execute())
{
    
        
        $email = $app_email;
        $subject = "$app_name sent new message via Portal";
        $message = '<html><body>';
        $message .= "
        <p>Hello,  <span style='font-size:bold;color:blue';> $app_name </span></p>
        <p>$app_message</p>
        <br>
        <p>Please findout <span style='color:red;font-weight:bold'>attached document(s)</span> in <span style='color:green;font-weight:bold'>WebSite Portal</span>.</p>
        
        ";
        $message .= '</body></html>';
        
        $user_compose->send_mail($email,$message,$subject); 
        /***********************************************
        end email code
        **********************************************/
$successMSG = "Document Upload Successfully.";
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
<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
<div class="page-title">
<ol class="breadcrumb">
<li><i class="fa fa-dashboard"></i>  <a href="dashboard">Dashboard</a>
</li>
<li class="active">Upload Document</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->


<div class="row">
<!-- begin LEFT COLUMN -->
<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
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


<form  method="post" enctype="multipart/form-data" accept-charset="utf-8" >
<div class="portlet portlet-default">
<div class="portlet-heading">
<div class="portlet-title">
<h4>Upload Document</h4>
</div>

<div class="portlet-widgets">
<a data-toggle="collapse" data-parent="#accordion" href="#basicFormExample"><i class="fa fa-chevron-down"></i></a>
</div>
<div class="clearfix"></div>
</div>
<div id="basicFormExample" class="panel-collapse collapse in">
<div class="portlet-body">


<div class="form-group">
<label >Message </label>
<textarea type="text"  name="appmessage" class="form-control" rows="5" required/>
</textarea>
</div>

</div>

</div>
</div>
</div>

<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 well well-info">
<div class="form-group">
<label>Select Attachedment file </label>
<input   type="file"   name="doc_file"  id="upload_file" >
</div>


<hr> <hr>
<div class="panel pane-body">
<input  type="submit" class="btn btn-default btn-block " name="submit" value="Send" >
</div>
</div>


</form>

</div> <!-- row end -->
</div> <!-- /.page-content -->
<!-- footer -->
<!-- footer -->
<?php include('include/footer.php'); ?>
