<?php 
require('includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

//define page title
$title = 'Monitor';

//include header template
require('layout/header.php'); 

?>
<h1>Monitor jak pica</h1>

<?
//include header template
require('layout/footer.php'); 
?>
