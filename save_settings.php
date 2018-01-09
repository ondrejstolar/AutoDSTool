<?php 
require('includes/config.php'); 
require('includes/connector.php'); 
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

$breakeEvent = $_GET["breakeEvent"];
$member_id = $_SESSION['username'];

if ($stmt = $conn->prepare("UPDATE members SET breakeEvent = ? WHERE username = ?")){
	$stmt->bind_param('si', $breakeEvent, $member_id);
	$stmt->execute();
	}
header("location: settings.php"); 
?>
