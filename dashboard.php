<?php 
require('includes/config.php');
require('includes/connector.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

//define page title
$title = 'Dashboard';

//include header template
require('layout/header.php'); 

foreach($conn->query("SELECT COUNT(*) FROM listings WHERE user = '".$_SESSION['username']."'") as $row){
$active_listings = $row['COUNT(*)'];
}
?>
<div class="col-md-3">
	<div class="panel panel-success text-center">
	    <div class="panel-heading">
	        Active Listings
	    </div>
	    <div class="panel-body">
	        <h1 class="card-title"><? echo $active_listings?></h1>
	        <a href="active_listings.php"><button class="btn-danger">Active Listings</button></a>
	    </div>
	</div>
</div>
<div class="col-md-3">
	<div class="panel panel-primary text-center">
	    <div class="panel-heading">
	        Orders in last 24 hours
	    </div>
	    <div class="panel-body">
	        <h1 class="card-title">3</h1>
	        <button class="btn-danger">View Orders</button>
	    </div>
	</div>
</div>
<div class="col-md-3">
	<div class="panel panel-danger text-center">
	    <div class="panel-heading">
	        Untracked Listings
	    </div>
	    <div class="panel-body">
	        <h1 class="card-title">0</h1>
	        <button class="btn-danger">View Untracked</button>
	    </div>
	</div>
</div>
<div class="col-md-3">
	<div class="panel panel-warning text-center">
	    <div class="panel-heading">
	        Profit in last 24hours
	    </div>
	    <div class="panel-body">
	        <h1 class="card-title">£5</h1>
	        <button class="btn-danger">View Statistics</button>
	    </div>
	</div>
</div>

<?
//include header template
require('layout/footer.php'); 
?>
