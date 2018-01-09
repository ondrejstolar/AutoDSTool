<?php
require('includes/config.php'); 
require('includes/connector.php'); 
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

//define page title
$title = 'Active Listings';

//include header template
require('layout/header.php'); 
?>

<table class="table table-bordered table-hover text-center table_active_listings">
	<thead>
		<tr>
			<th>Edit</th>
			<th>Title</th>
			<th>Picture</th>
			<th>Source ID</th>
			<th>Source $</th>
			<th>Sell Price</th>
			<th>Profit $</th>
			<th>Status</th>
		</tr>
	</thead>
<tbody>
	<tr>
<?php
$result = mysqli_query($conn,"SELECT * FROM listings WHERE user = '".$_SESSION['username']."'");

 while($test = mysqli_fetch_array($result)){
 	echo"<td><a href='".$test['sourceID']."'><img src='https://www.shareicon.net/data/32x32/2017/03/02/880214_edit_512x512.png'</a></td>";
 	echo"<td>".$test['title']."</td>";
 	echo"<td><img src='".$test['pic']."' style='height:50px;'</img></td>"; 
 	echo"<td><a href='".$test['sourceID']."'>Link</a></td>";
 	echo"<td>£".$test['sourcePrice']."</td>";
 	echo"<td>£".$test['sellPrice']."</td>";
 	echo"<td>£".$test['profit']."</td>";
 	echo"<td>".$test['status']."</td>";
 	echo "</tr>";
 }
require("layout/footer.php")
?>



