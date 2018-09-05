<?php
function siteURL()   
{
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'].'/';
return $protocol.$domainName;
}


 $myTimeZone = date_default_timezone_set("Asia/Kolkata");
    $date = date("d/m/Y "); //output:  22/08/2018
    $datetime = date("d/m/Y h:i:s a"); //output:  22/08/2018 09:52:15 pm
    
?>