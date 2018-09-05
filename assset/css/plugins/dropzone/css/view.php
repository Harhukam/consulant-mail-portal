<?php
error_reporting( ~E_NOTICE );
require_once 'db/db.php';
if(isset($_GET['blogurl']) && !empty($_GET['blogurl'])) 
{
$id = base64_decode(urldecode($_GET['blogurl']));//encryption
$stmt_edit = $DB_con->prepare('SELECT * FROM blog WHERE blog_id =:bid');
$stmt_edit->execute(array(':bid'=>$id));
$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
extract($edit_row);
}
else
{
header("Location: blogs.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $blogtitle." ".$blog_id ;?></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

</head>
<body>
<div class="container">
<div class="row">

<div class="col-sm-8">
<?php echo $blogtitle ;?>
<hr>
<?php echo $blogtxt ;?>
</div>

<div class="col-sm-4">
<img class="img img-responsive" src="images/<?php echo $blogpic ;?>">
<hr>
<a class="btn btn-default" href="blogs">  Bacl to blog</a>
</div>

</div>
</div>
</body>
</html>