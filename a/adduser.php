<?php
error_reporting( ~E_NOTICE );
session_start();
require_once 'class.admin.php';
$user_adduser = new admin();

if(!$user_adduser->is_logged_in())
{
$user_adduser->redirect('index');
}


//use display total number of records
$stmt = $user_adduser->runQuery("SELECT count(*) FROM tbl_user WHERE userStatus='Y' ORDER BY userID ");
$stmt->execute();
$allusercount = $stmt->fetchColumn();
//end

$stmt = $user_adduser->runQuery("SELECT * FROM  tbl_admin WHERE adminID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//use for count total messages
$stmt = $user_adduser->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countmails = $stmt->fetchColumn();

//show notification unread messages
$stmt = $user_adduser->runQuery("SELECT count(*) FROM userdatafeed WHERE app_client_id = :abc AND app_admintrash='N'  AND app_admin_read_status='unread-message' ");
$stmt->execute(array(":abc"=>$row['adminID']));
$countnotify = $stmt->fetchColumn();




if(isset($_POST['signup']))
{
	$uname = trim($_POST['txtuname']);
	$umob = trim($_POST['txtumob']);
	$umail = trim($_POST['txtumail']);
	$upass = rand(100000,1000000);
	$code = md5(uniqid(rand()));
	$ustatus="Y";
	$pic  = "000000.png";
	$ucat = trim($_POST['txtucat']);
	$uaddress = $_POST['txtuname']." ,".$_POST['txtumob'];
	$ukey = $row['adminID'];
	
	if(isset($umail)){
	$stmt = $user_adduser->runQuery("SELECT userEmail FROM  tbl_user WHERE userEmail=:mail");
    $row =  $stmt->execute(array(":mail"=>$umail));
    if($stmt->rowCount() > 0)
	{
    $errMSG = "Email already used.";
	}
	}
	

	if(isset($umob)){
	$stmt = $user_adduser->runQuery("SELECT userMobile FROM  tbl_user WHERE userMobile=:mob");
    $row = $stmt->execute(array(":mob"=>$umob));
    if($stmt->rowCount() > 0)
	{
    $errMSG = "Mobile Number already used";
	}
	}
	
	
	if(empty($uname)){
      $errMSG = "Fill Username";
    }

    if(empty($umob)){
      $errMSG = "Fill Mobile Number";
    }

    if(empty($umail)){
     $errMSG = "Fill E-mail ID";
    }

    if(empty($ucat)){
     $errMSG = "Select Category";
     }
	
    if(!isset($errMSG)){
	$password = md5($upass);
	$stmt = $user_adduser->runQuery("INSERT INTO tbl_user(userName,userEmail,userPass,userStatus,tokenCode,userPhoto,userMobile,userCat,userAddress,userJoiningDateTime,userJoiningDate,userKey) VALUES(:b1,:b2,:b3,:b4,:b5,:b6,:b7,:b8,:b9,:b10,:b11,:b12)");

        $stmt->bindParam(':b1',$uname);
        $stmt->bindParam(':b2',$umail);
        $stmt->bindParam(':b3',$password);
        $stmt->bindParam(':b4',$ustatus);
        $stmt->bindParam(':b5',$code);
        $stmt->bindParam(':b6',$pic);
        $stmt->bindParam(':b7',$umob);
        $stmt->bindParam(':b8',$ucat);
        $stmt->bindParam(':b9',$uaddress);
        $stmt->bindParam(':b10',$datetime);
        $stmt->bindParam(':b11',$date);
        $stmt->bindParam(':b12',$ukey);
        if($stmt->execute())
     {
    
        $email = $umail;
    	$subject = "Your Account Successfully Created";
    	$message = '<html><body>';
        $message .= "
    	<p>Hello   $uname  </p>
    	
    	<p> Visit Website to Portal login : <a target='_blank' href='http://site0.mywebdeal.in/'> 
    	 www.site0.mywebdeal.in </a> </p>
    	 
    	 ***************************************************************<br>
    	<p> Your username :  $umail </p>
    	<p> Password :  $upass </p>
    	 ***************************************************************<br>
    	 
    	 <h5>Company Name Pvt. Ltd.</h5>
    	 
    	";
    	$message .= '</body></html>';
    	
    	$user_adduser->send_mail($email,$message,$subject); 
    
    	/*  Email code end. */
    	
    	
     if(isset($_POST['signup'] )=== true){
$numbers =$umob;                          //$_POST['txtumob'];
$messagesms = "Hello $uname Your Login detail Username: $umail  Password : $upass  Login here : http://site0.mywebdeal.in  Company Name Pvt. Ltd. ";
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


$successMSG = "User Created Successfully ";
header('refresh:1; adduser');
}
else
{
$errMSG = "error! please try again...";
}
}
}


if(isset($_GET['delkey']))
{
    $delid = base64_decode(urldecode($_GET['delkey']));
    
/*
$stmt_delete = $user_adduser->runQuery("DELETE FROM tbl_user WHERE userID =:userid");
$stmt_delete->execute(array(":userid"=>$delid));
*/

    $stmt = $user_adduser->runQuery("UPDATE tbl_user SET userStatus='N'  WHERE userID=:udid");
    $stmt->bindParam(":udid",$delid);
    $stmt->execute();
    
    $stmt = $user_adduser->runQuery("UPDATE userdatafeed SET app_usertrash	='Y' , app_admintrash ='Y'  WHERE app_client_id=:chid");
    $stmt->bindParam(":chid",$delid);
    $stmt->execute();
    
    $stmt = $user_adduser->runQuery("UPDATE userdatafeed SET app_admintrash ='Y' , app_usertrash ='Y' WHERE app_senderID=:chid");
    $stmt->bindParam(":chid",$delid);
    $stmt->execute();
    
$successMSG = "User Delete Successfully ";
header('refresh:1; adduser');
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
<li class="active">Add User</li>
</ol>
</div>
</div>
<!-- /.col-lg-12 -->
</div><!-- /.row -->
<div class="row">


<!-- begin LEFT COLUMN -->
<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
<form method="post" enctype="multipart/form-data" >
<div class="portlet portlet-default">
<div class="portlet-heading">
<div class="portlet-title">
<h4>Create New user</h4>
</div>
<div class="portlet-widgets">
<a data-toggle="collapse" data-parent="#accordion" href="#basicFormExample"><i class="fa fa-chevron-down"></i></a>
</div>
<div class="clearfix"></div>
</div>
<div id="basicFormExample" class="panel-collapse collapse in">
<div class="portlet-body">

<div class="form-group">
<label for="exampleInputEmail1">Full Name</label>
<input type="text" class="form-control"  placeholder="Full name" name="txtuname" required="required">
</div>


<div class="form-group">
<label for="exampleInputEmail1">Mobile No. (10 digit)</label>
<input type="number" class="form-control"  placeholder="9815098150" name="txtumob" maxlength="10" required="required">
</div>


<div class="form-group">
<label for="exampleInputEmail1">E-mail ID</label>
<input type="email" class="form-control" id="exampleInputEmail1" name="txtumail" placeholder="eg : username@domain.com" required="required">
</div>


<div class="form-group">
<label for="exampleInputEmail1">Application Type</label>
<select class="form-control" type="text"  name="txtucat" value="" required="required">
<option value="none" selected disabled>Select</option>
<?php
$stmt = $user_adduser->runQuery("SELECT * FROM category ORDER BY catID ASC ");
$stmt->execute();
if($stmt->rowCount() > 0)
{
while($catrow=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($catrow);
?>
<option value="<?php echo $catrow['catValue']; ?>"><?php echo $catrow['catName']; ?></option>
<?php
}
}
else
{
?>
<div class="col-sm-12">
<span class="glyphicon glyphicon-info-sign"></span> &nbsp; Not found...
</div>
<?php
}  
?>
</select>
</div>

<!--   <div class="form-group">
<label for="exampleInputFile">File input</label>
<input type="file" id="exampleInputFile">
<p class="help-block">Example block-level help text here.</p>
</div> -->
<input  type="submit" class="btn btn-default " name="signup" value="Submit">
</form>
</div>

</div>
</div></div>


<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">

<div class="portlet portlet-green">
<div class="portlet-heading">
<div class="portlet-title">
<h4>List of Register Users  <?php echo $allusercount; ?></h4>
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<!-- <th>User_id</th> -->
<th>Student Name</th>
<th>Mobile No.</th>
<th>Email</th>
<th>Edit</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
$stmt = $user_adduser->runQuery("SELECT * FROM tbl_user WHERE userStatus='Y' ORDER BY userID DESC");
$stmt->execute();
if($stmt->rowCount() > 0)
{
while($usersrow=$stmt->fetch(PDO::FETCH_ASSOC))
{
extract($usersrow);
$key = urlencode(base64_encode($usersrow['userID']));
?>
<tr>
<td><?php echo $usersrow['userName']; ?></td>
<td><?php echo $usersrow['userMobile']; ?></td>
<td><?php echo $usersrow['userEmail']; ?></td>
<td><a style="float: right;" class="btn btn-xs btn-success" href="updateuser?uptkey=<?php echo $key; ?>"</a> <span class="glyphicon glyphicon-trash"></span> edit</a></td>
<td><a style="float: right;" class="btn btn-xs btn-warning" href="adduser?delkey=<?php echo $key; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><span class="glyphicon glyphicon-trash"></span> delete</a></td>
</tr>
<?php
}
}
else
{
?>
<tr> <td colspan="5">
<span style="color: red" class="glyphicon glyphicon-info-sign"></span> &nbsp; No User found.
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
