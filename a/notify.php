<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.admin.php';
$user_notify = new admin();

if(!$user_notify->is_logged_in())
{
$user_notify->redirect('index');
}

$stmt = $user_notify->runQuery("SELECT * FROM  tbl_admin WHERE adminID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//extract($row);




//use for count total messages
$stmt = $user_notify->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_notify->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N'  AND app_admin_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countnotify = $stmt->fetchColumn();



if(isset($_GET['u']) && !empty($_GET['u'])) 
{ 

$id = base64_decode(urldecode($_GET['u']));

$stmt_fetch = $user_notify->runQuery("SELECT userID,userName,userEmail,userStatus,userMobile,userAddress,userCat FROM tbl_user WHERE userID =:bid");
$stmt_fetch->execute(array(':bid'=>$id));
$userrow = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
extract($userrow);
}

if(isset($_GET['u'])!= $id)
{

$errMSG = "error!";     
header('Location: dashboard');
}


if(isset($_POST['submit']))
{


$app_client_id     =  $_POST['appclientid'];
$app_name          =  $_POST['appname'];
$app_mobile        =  $_POST['appmobile'];
$app_email         =  $_POST['appemail'];
$app_cat           =  $_POST['appcat'];
$app_status        =  $_POST['appstatus'];
$app_subject       =  "Your Application $app_cat Status under $app_status ";
$app_message       =  $_POST['appmessage'];
$app_senderid      =  $row['adminID'];
$app_sendername    =  $row['adminName'];
$app_senderemail   =  $row['adminEmail'];
$app_date          =  $date;
$app_datetime      =  $datetime;
$app_user_read_status = "unread-message";
//$app_usertrash        = "N";


$document = $_FILES['doc_file']['name'];
$tmp_dir = $_FILES['doc_file']['tmp_name'];
$fileSize = $_FILES['doc_file']['size'];  


if(empty($app_message)){
$errMSG = "Please choose attachedment file";
}

if(empty($document)){
    
    $document = "0.png";
//$errMSG = "Please choose attachedment file";
}
else
{

$upload_dir = '../uploads/'; // upload directory
$documentExt = strtolower(pathinfo($document,PATHINFO_EXTENSION)); 
$valid_extensions = array("jpg","txt","pdf","psd", "jpeg","docs","docx","rar","mp3","zip","zip7", "gif", "png", "mp4", "wma"); 

$document = $app_client_id."-".$num= rand(10000, 999999).".".$documentExt;
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
    
    
$stmt = $user_notify->runQuery("INSERT INTO userdatafeed(app_client_id,app_name,app_mobile,app_email,app_cat,app_subject,app_message,app_document,app_status,app_date,app_datetime,app_senderID,app_senderName,app_senderEmail,app_user_read_status) VALUES(:b1,:b2,:b3,:b4,:b5,:b6,:b7,:b8,:b9,:b10,:b11,:b12,:b13,:b14,:b15)");
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
$stmt->bindParam(':b15',$app_user_read_status);
if($stmt->execute())
{
       
        $email = $app_email;
        $subject = "Visa file Notification";
        $message = '<html><body>';
        $message .= "
        Hello,  <span style='font-size:bold;color:blue';> $app_name </span>
        <br><br>  
        <p>$app_message</p>
        <h4>Company Name Pvt. Ltd.</h4>
        ";
        $message .= '</body></html>';
        
        $user_notify->send_mail($email,$message,$subject); 
        /***********************************************
        end email code
        **********************************************/
        
        
        if(isset($_POST['submit'] )=== true){
$numbers = $_POST['appmobile'];
$messagesms = $_POST['appmessage'];
if(!empty($numbers)&&!empty($messagesms)){
$messagesms = urlencode($messagesms);
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=TyDqvfH3JI4&MobileNo=$numbers&SenderID=WEBLOG&Message=$messagesms&ServiceName=PROMOTIONAL_HIGH");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$output =curl_exec($ch);

/*if($output == true){
$successMSG = "Message sent.";
header('refresh:5; dashboard');
}*/
curl_close($ch);
}  //end second if 
}

 /***********************************************
        end sms code
 **********************************************/


$successMSG = "Notification Send Successfully ";
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
<div class="col-sm-9 col-xs-12">
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

<input type="hidden" name="appclientid" value="<?php echo $id; ?>" />
<input type="hidden" name="appname" value="<?php echo $userrow['userName']; ?>" />
<input type="hidden" name="appemail" value="<?php echo $userrow['userEmail']; ?>" />
<input type="hidden" name="appmobile" value="<?php echo $userrow['userMobile']; ?>" />
<input type="hidden" name="appcat" value="<?php echo $userrow['userCat']; ?>" />


<div class="portlet portlet-default">
<div class="portlet-heading">
<div class="portlet-title">
<h4>Send Notification</h4>
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
<textarea type="text"  name="appmessage" class="form-control" rows="5" required/>Dear <?php echo $userrow['userName']; ?>, </textarea>
</div>

</div>

</div>
</div>
</div>

<div class="col-sm-3 col-xs-12 well well-info">

 

<div class="form-group">
<label for="exampleInputEmail1">Change Application Status</label>
<select class="form-control" type="text"  name="appstatus" >
<!--<option value="" selected disabled>Choose</option>-->
<option value="Process">In Processing</option>
<option value="Complete">Complete</option>
</select>
</div>

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
<?php include('include/footer.php'); ?>
