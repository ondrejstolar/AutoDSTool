<?php
require('includes/config.php'); 
require('includes/connector.php');
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

//define page title
$title = 'Uploader';

//include header template
require('layout/header.php');

// Get DATA from DB 
$sql = "SELECT memberID FROM members WHERE username = '" . $_SESSION['username'] . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    	$member_id = $row["memberID"];
    }
} else {
    echo "Error..Something went wrong";
} 
?>
	<form id="upload" action="" method="get">
		Source link(Please provide ASIN something like B071DWMPGP) <input class="form-control" type="text" name="link" required></br>
    <div class="col-md-4">
		Source site<select class="form-control" id="origin"></br>
  						 <option value="com">Amazon COM</option>
 						   <option value="amz_uk">Amazon UK</option>
  						 <option value="de">Amazon DE</option>
		  		     </select>
    </div>
    <div class="col-md-4">
    Select Template<select class="form-control" type="text" name="sale_template">
                   <option value="special_sale_30days">Special Sale 30 days return</option>
                   <option value="special_sale_14days">Special Sale 14 days return</option>
                   <option value="christmas_sale_30days">Christmas Sale 30 days return</option>
                   </select>
    </div>
    <div class="col-md-4">
    Destination site<select class="form-control" type="text" name="destination_site">
                    <option value="ebay_com">eBay COM</option>
                    <option value="ebay_uk">eBay UK</option>
                    <option value="ebay_de">eBay DE</option>
                    </select></br>
    </div>
		<button class="btn-success" action='submit'>Load Product</button>
	</form>
  <div class="uploader_buttons">
    <div class="uploader_editbutton">
      <a href="edittemplates.php"><button class="btn-info pull-right">Edit Templates</button></a>
    </div>
    <a href="addnewtemplate.php"><button class="btn-warning pull-right">Add New Template</button></a>
  </div>
<!-- Submit form based on source site selection -->
<script>
  $(function() {
   $('#upload').submit(function(){
     var orig = $('#origin').val();
     $(this).attr('action', "http://localhost:8888/" + orig + ".php");
   });
  });
</script>

<?php 
//include header template
require('layout/footer.php'); 
?>
