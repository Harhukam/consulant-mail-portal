<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>logged in as - <?php echo $row['adminName']; ?></title>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
  
     <script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
    <link href="assset/css/plugins/pace/pace.css" rel="stylesheet">
    <!-- GLOBAL STYLES - Include these on every page. -->
    <link href="assset/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assset/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- PAGE LEVEL PLUGIN STYLES -->
   
    <link href="assset/css/plugins/bootstrap-tokenfield/tokenfield-typeahead.css" rel="stylesheet">
    <link href="assset/css/plugins/bootstrap-tokenfield/bootstrap-tokenfield.css" rel="stylesheet">
    <!-- THEME STYLES - Include these on every page. -->
    <link href="assset/css/style.css" rel="stylesheet">
    <link href="assset/css/plugins.css" rel="stylesheet">
    <link href="assset/css/demo.css" rel="stylesheet">
    
    <style>
    .progress 
{
  display:none; 
  position:relative; 
  width:300px; 
  border: 1px solid #ddd; 
  padding: 1px; 
  border-radius: 3px; 
}
.bar 
{ 
  background-color: #B4F5B4; 
  width:0%; 
  height:20px; 
  border-radius: 3px; 
}
.percent 
{ 
  position:absolute; 
  display:inline-block; 
  top:3px; 
  left:48%; 
}
</style>


   <script type="text/javascript">

    function upload_file() 
{
  var bar = $('#bar');
  var percent = $('#percent');
  $('#postForm').ajaxForm({
    beforeSubmit: function() {
      document.getElementById("progress_div").style.display="block";
      var percentVal = '0%';
      bar.width(percentVal)
      percent.html(percentVal);
    },

    uploadProgress: function(event, position, total, percentComplete) {
      var percentVal = percentComplete + '%';
      bar.width(percentVal)
      percent.html(percentVal);
    },
    
	success: function() {
      var percentVal = '100%';
      bar.width(percentVal)
      percent.html(percentVal);
    },

   /* complete: function(xhr) {
      if(xhr.responseText)
      {
        document.getElementById("output_image").innerHTML=xhr.responseText;
      }
    }*/
    
  }); 
}
</script> 




<script type="text/javascript">
    function disableRightClick() {
        alert("ohh! hoo!, right click is not allowed !!");
        return false;
    }
</script> 
</head>
<body  oncontextmenu=" return disableRightClick();">
<div id="wrapper">
<?php include('include/top-navbar.php'); ?> <!-- top navbar -->
<?php include('include/side-navbar.php'); ?> <!-- begin SIDE NAVIGATION -->
<div id="page-wrapper"> <!-- begin MAIN PAGE CONTENT -->