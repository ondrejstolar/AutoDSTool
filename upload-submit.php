<?php 
require('includes/config.php');  
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// get data from form
$member_id = $_SESSION['username'];
$sourceID = $_POST["sourceID"];
$ebayID = $_POST["destination_site"];
$pic = $_POST["pic"];
$sellPrice = $_POST["sell_price"];
$sourcePrice = $_POST["source_price"];
$profit = $_POST["estimated_profit"];
$title = $_POST["title"];
$status = $_POST["availability"];

//insert in to DB
if ($stmt = $conn->prepare("INSERT INTO listings (user, sourceID, ebayID, pic, sellPrice, sourcePrice, profit, title, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")){
$stmt->bind_param("ssssiisss", $member_id, $sourceID, $ebayID, $pic, $sellPrice, $sourcePrice, $profit, $title, $status);
$stmt->execute();
print_r(mysqli_error($conn));
}
// call home
header("location: uploader.php");
?>