<?php
require('includes/config.php'); 
require('includes/connector.php');

if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

$asin = $_GET["link"];
$destinationSite = $_GET["destination_site"];
$member_id = $_SESSION['username'];


//Region code and Product ASIN
$response = getAmazonPrice("co.uk", $asin);

function getAmazonPrice($region, $asin) {

    $xml = aws_signed_request($region, array(
        "Operation" => "ItemLookup",
        "ItemId" => $asin,
        "IncludeReviewsSummary" => False,
        "ResponseGroup" => "Large, OfferFull",
    ));

    $item = $xml->Items->Item;
    $title = htmlentities((string) $item->ItemAttributes->Title);
    $url = htmlentities((string) $item->DetailPageURL);
    $image = htmlentities((string) $item->MediumImage->URL);
    $price = htmlentities((string) $item->OfferSummary->LowestNewPrice->Amount);
    $code = htmlentities((string) $item->OfferSummary->LowestNewPrice->CurrencyCode);
    $qty = htmlentities((string) $item->OfferSummary->TotalNew);

    if ($qty !== "0") {
        $response = array(
            "code" => $code,
            "price" => number_format((float) ($price / 100), 2, '.', ''),
            "image" => $image,
            "url" => $url,
            "title" => $title
        );
    }

    return $response;
}

function getPage($url) {

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($curl);
    curl_close($curl);
    return $html;
}

function aws_signed_request($region, $params) {

    $public_key = "AWS public key";
    $private_key = "AWS private key";

    $method = "GET";
    $host = "ecs.amazonaws." . $region;
    $host = "webservices.amazon." . $region;
    $uri = "/onca/xml";

    $params["Service"] = "AWSECommerceService";
    $params["AssociateTag"] = "ondrejstolar1-21"; 
    $params["AWSAccessKeyId"] = $public_key;
    $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
    $params["Version"] = "2011-08-01";

    ksort($params);

    $canonicalized_query = array();
    foreach ($params as $param => $value) {
        $param = str_replace("%7E", "~", rawurlencode($param));
        $value = str_replace("%7E", "~", rawurlencode($value));
        $canonicalized_query[] = $param . "=" . $value;
    }

    $canonicalized_query = implode("&", $canonicalized_query);

    $string_to_sign = $method . "\n" . $host . "\n" . $uri . "\n" . $canonicalized_query;
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
    $signature = str_replace("%7E", "~", rawurlencode($signature));

    $request = "http://" . $host . $uri . "?" . $canonicalized_query . "&Signature=" . $signature;
    $response = getPage($request);

    echo("<script>console.log('PHP: ".$request."');</script>"); //print request url to console


    $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
    $url = $request;

    $sxml = file_get_contents($url, false, $context);
    $sxml = simplexml_load_string($sxml);
    // print_r($sxml);
    global $description2;
    global $price2;
    global $availability2;
    global $title2;
    global $picture2;
    global $url2;
    $description2 = $sxml->Items->Item->EditorialReviews->EditorialReview->Content;
    $price2 = $sxml->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice;
    $availability2 = $sxml->Items->Item->Offers->Offer->OfferListing->Availability;
    $title2 = $sxml->Items->Item->ItemAttributes->Title;
    $picture2 = $sxml->Items->Item->LargeImage->URL;
    $url2 = $sxml->Items->Item->DetailPageURL;
} 

	if ($stmt = $conn->prepare("UPDATE members SET destSite = ?, prdDesc = ?, prdPrice = ?, prdAvail = ?, prdTitle = ?, prdPic = ?, prdUrl = ? WHERE username = ?")){
	$stmt->bind_param('sssssssi', $destinationSite, $description2, $price2, $availability2, $title2, $picture2, $url2, $member_id);
	$stmt->execute();
	}
header("location: upload-process.php"); 
?>
