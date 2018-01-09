<?php 
require('includes/config.php'); 
require('includes/connector.php'); 
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

//define page title
$title = 'Settings';

//include header template
require('layout/header.php'); 

// Get DATA from DB 
$sql = "SELECT memberID, username, breakeEvent, email FROM members WHERE username = '" . $_SESSION['username'] . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    	$brevent = $row["breakeEvent"];
    }
} else {
    echo "Error..Something went wrong";
}
?>
<form action="save_settings.php" method="get">
Break Event<input class="form-control" type="number" name="breakeEvent" value="<?echo $brevent?>">
</br>
<button class="btn-success" action='submit'>Save</button>
</form>

<?
//include header template
require('layout/footer.php'); 
?>
