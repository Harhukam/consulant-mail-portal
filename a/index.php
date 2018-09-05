<?php
session_start();
require_once 'class.admin.php';
$admin_login = new ADMIN();

if($admin_login->is_logged_in()!="")
{
$admin_login->redirect('dashboard');
}

if(isset($_POST['btn-login']))
{
$uemail = trim($_POST['txtemail']);
$upass = trim($_POST['txtupass']);
$ip = $_POST['ip'];
$loginsecure = $_POST['logindatetime'];
$browserdetail = $_POST['ue'];



if($admin_login->login($uemail,$upass,$ip,$loginsecure))
{
$admin_login->redirect('dashboard');
    //------------------------email code start------------------------------
    if($admin_login==true){
        
        $email = $uemail;
        $subject = "Security Notification";
        $message = '<html><body>';
        $message .= "  <br>
        -------------------------------------------------------------------
       <p>  site0.mywebdeal.in </p>
       <p>  Activity Notification  </p>
        -------------------------------------------------------------------
        <br>
        <p>Dear <b>$uemail,</b></p> 
        <p> There was some activity in your [Sitename] account. Information on what
        type of change occurred is available below.</p> 

        Notification For     : LOGIN   <br><br>
        Date                 : <span style='color:red'>$loginsecure </span>     <br>
        IP Address           : <span style='color:red'>$ip  </span>             <br>
        Browser / OS         : <p> $browserdetail </p>
        ";
        $message .= '</body></html>';
        
        $admin_login->send_mail($email,$message,$subject); 
    }
  
//--------------------email code end----------------------------


}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="admin login">
<meta name="author" content="">
<title>Admin Login</title>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<link href="assset/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assset/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="assset/css/style.css" rel="stylesheet">
</head>
<body style="background-image: url('images/bc.jpg') !important;
background-color: #cccccc; height: 100%;"    oncontextmenu=" return disableRightClick();">
<div class="container">
<div class="row">
<div class="col-md-4 col-md-offset-4 shedowlogin">
<div class="login-banner text-center ">
<h1><i class="fa fa-user"></i> LogIn</h1>
<p>  
<?php
 function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Trident/i',$u_agent)) 
    { // this condition is for IE11
        $bname = 'Internet Explorer'; 
        $ub = "rv"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    // Added "|:"
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
     ')[/|: ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

// now try it
$ua=getBrowser();
$yourbrowser = "detected browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " Reports: <br >" . $ua['userAgent'];


?>
</p>
</div>
<div class="portlet portlet-red">
<div class="portlet-heading login-heading">
<div class="portlet-title">
</div>
<div class="portlet-widgets">
<!-- <button class="btn btn-white btn-xs"><i class="fa fa-plus-circle"></i> New User</button> -->
</div>
<div class="clearfix"></div>
</div>
<div class="portlet-body">
<?php 
if(isset($_GET['inactive']))
{
?>
<div class='alert alert-error'>
<button class='close' data-dismiss='alert'>&times;</button>
<strong>Sorry!</strong> This Account is not Activated Go to your Inbox and Activate it. 
</div>
<?php
}
?>

<form class="form-signin" method="post">
<input type="hidden" name="ue"  value="<?php print $yourbrowser;?>" />
<input type="hidden" name="ip" value="<?php echo $_SERVER["REMOTE_ADDR"];?>" />
<input type="hidden" name="logindatetime" value="<?php echo $datetime;?>" />

<?php
if(isset($_GET['error']))
{
?>
<div class='alert alert-warning'>
<button class='close' data-dismiss='alert'>&times;</button>
<strong>Invalid UserName Password!</strong> 
</div>
<?php
}
?>
<fieldset>
<div class="form-group">
<input class="form-control" placeholder="Username"  type="email" name="txtemail" required />
</div>
<div class="form-group">
<input class="form-control" placeholder="Password"  type="password" name="txtupass" required />
</div>
<br>
<input type="submit" value="LogIn" name="btn-login" class=" btn btn-lg btn-red btn-block" />
<hr />
</fieldset>
<br>
<!--<a href="#" style="float:right;" >Sign Up</a>-->
<p class="small">
<a href="fpass">lost your password?</a>
</p>
</form>
</div>
</div>
</div>
</div>
</div>
<script src="assset/js/plugins/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript">
    function disableRightClick() {
       // alert("ohh! hoo!, right click is not allowed !!");
        return false;
    }
</script>
</body>
</html>