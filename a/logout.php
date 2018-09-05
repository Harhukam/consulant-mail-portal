<?php
session_start();
require_once 'class.admin.php';

$user = new ADMIN();


if(!$user->is_logged_in())
{
	$user->redirect("http://site0.mywebdeal.in/a/");
}

if($user->is_logged_in()!="")
{
	$user->logout();	
	$user->redirect("http://site0.mywebdeal.in/a/");
}
?>