<?php 
require('includes/config.php');
require('includes/connector.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

//define page title
$title = 'Uploader';

//include header template
require('layout/header.php'); 

// Import JSON source file and decode
// $json = file_get_contents('uploader/data.json');
// $json_data = json_decode($json, JSON_PRETTY_PRINT);

// Print data from JSON source file
// print_r($json_data); 

// extract needed info from JSON array and asign to variable
// $url = $json_data[0]['URL'];
// $name = $json_data[0]['NAME'];
// $description = $json_data[0]['DESCRIPTION'];
// $sale_price = $json_data[0]['SALE_PRICE']; //sale price on source
// $sale_price_converted = preg_replace('/[\$,£,]/', '', $sale_price); //remove currency symbol from sale price
// $availability = $json_data[0]['AVAILABILITY'];
// if ($availability === NULL){
//     $avail = yes;
// }else{
//     $avail = $availability;
// };
// JSON end

// get from DB
$sql = "SELECT memberID, username, destSite, breakeEvent, email, prdDesc, prdPrice, prdAvail, prdTitle, prdPic, prdUrl FROM members WHERE username = '" . $_SESSION['username'] . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $destination_site_value = $row["destSite"];
    	$brevent = $row["breakeEvent"];
        $prdDescription = $row["prdDesc"];
        $prdPrice = $row["prdPrice"];
        $prdAvail = $row["prdAvail"];
        $prdTitle = $row["prdTitle"];
        $prdPic = $row["prdPic"];
        $prdUrl = $row["prdUrl"];
    }
} else {
    echo "Error..Something went wrong";
}
// Conversion to use values from DB
$sale_price_converted = preg_replace('/[\$,£,]/', '', $prdPrice);
if ($destination_site_value = "ebay_com"){
        $destination_site2 = "eBay.com";
    }elseif($destination_site_value = "ebay_uk"){
        $destination_site2 = "eBay.co.uk";
    }else{
        $destination_site2 = "eBay.de";
    }
// get from DB END
?>
<div onmousemove="calculateForm();">
<form onmousemove="calculateForm();" action="upload-submit.php" id="upload-process-form" method="post">
<!-- Title of the product -->
<div class="row">
<div class="col-xs-12">
	Title<input onmousemove="calculateForm();" class="form-control" type="text" name="title" value="<?echo $prdTitle?>">
</div>
<!-- AVAILABILITY -->
<div class="col-xs-2">
    Available<input class="form-control" type="text" name="availability" id="availability" value="<? echo $prdAvail?>" readonly >
</div>
<!-- Source price of the product -->
<div class="col-xs-2">
	Source Price<input onmousemove="calculateForm();" class="form-control" type="number" name="source_price" id="sale_price_converted" value="<? echo $sale_price_converted?>" readonly >
</div>
<!-- Our sell price -->
<div class="col-xs-2">
	Sell Price<input onmousemove="calculateForm();" class="form-control" type="number" name="sell_price" id="sell_price" readonly>
</div>
<!-- Our profit -->
<div class="col-xs-2">
	Est. Profit<input onmousemove="calculateForm();" class="form-control" type="number" id="estimated_profit" name="estimated_profit">
</div>
<!-- Profit in % -->
<div class="col-xs-2">
	% Profit<input onmousemove="calculateForm();" class="form-control" type="number" name="percentage_profit" id="percentage_profit">
</div>
<!-- Break Even - its percentage wich needs to be added to sell price to cover ebay and paypal fees -->
<div class="col-xs-2">
	Break Even<input onmousemove="calculateForm();" class="form-control" type="number" name="breakeEvent" id="breakeEvent" value="<? echo $brevent?>">
</div>
</div>
<!-- link to product -->
<input type="hidden" name="sourceID" value="<?echo $prdUrl?>">
<a href="<?echo $prdUrl?>" class="pull-right">Link to product</a></br>
<hr>
Atributes
<hr>
Pictures</br>
<input type="hidden" name="pic" value="<?echo $prdPic?>">
<img style="width: 100px" src="<?echo $prdPic?>">
<hr>
<!-- Editor -->
<textarea class="ckeditor" id="editor" name="editor">
    <img style="width: 100px" src="<?echo $prdPic?>"></br>
	URL: <?echo $url?></br>
	NAME: <?echo $prdTitle?></br>
	PICTURE: </br>
	DESCRIPTIOM: <? echo $prdDescription?></br>
</textarea>
<hr>
<input type="text" class="form-control" name="destination_site" id="destination_site" value="<?echo $destination_site2?>" readonly></br>
<input type="submit" value="Upoload">
</form>
</div>

<!-- Price calculator -->
<script type="text/javascript">
var calculateForm = function () {
  var selling = document.getElementById("sell_price").value =
    (
        Number(document.getElementById("sale_price_converted").value) / 100  *
        Number(document.getElementById("breakeEvent").value) + 
        Number(document.getElementById("sale_price_converted").value) + 
        Number(document.getElementById("estimated_profit").value)
    );
};
</script>
<?
//include header template
require('layout/footer.php'); 
?>