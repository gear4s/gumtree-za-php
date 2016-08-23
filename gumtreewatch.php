<?php

include "gumtreefunc.php";

function get_gumtree_add_info($url) {
	return fetch($url)["content"];
}

$res = fetch("http://www.gumtree.co.za/post.html?adId=".$_GET["adId"]."&guid=".$_GET["guid"]);
$html = str_get_html($res["content"]);

// some default values
// you should capture most of these from inspecting element of the post page
$postData = array(
	"ForSaleBy" => "delr",
	"Phone" => "0212345678",
	"latitude" => "-33.844167",
	"longitude" => "18.698611",

        // these two have to be the same
        "prevMapAddress" => "Shop Wherever, Whenever, Cape Town",
        "Address" => "Shop Wherever, Whenever, Cape Town",

	"currencyValues" => "ZAR",
        "UserName" => "Your Shop",
        "Email" => "your-email",
        "WebSiteUrl" => "your-website.com",,
	"adminAreaName" => "",
	"u" => "",
);
foreach($html->find("form#postAdForm")[0]->children() as $child) {
	if($child->type == "hidden") {
		$postData[$child->name] = $child->value;
	} else if($child->tag == "div" && $child->id == "postForm") {
		// ad title
		$adTitle = $child->children(1)->children(2)->children(0);
		$postData[$adTitle->name] = $adTitle->value;
		
		// ad description
		$adDesc = $child->children(3)->children(3);
		$postData[$adDesc->name] = $adDesc->innertext;
		
		// ad price
		foreach($child->children(7)->children(3)->children(0)->children() as $opt) {
			if($opt->selected == 1) {
				$adPrice = $child->children(7)->children(3)->children(0);
				$postData[$adPrice->name] = $opt->value;
				// check if price is fixed, then add price
				if($opt->value == "FIXED") {
					$postData["Price"] = $child->children(7)->children(4)->children(0)->value;
				}
			}
		}
		
		// ad name
		$adName = $child->children(1)->children(2)->children(0);
		$postData[$adName->name] = $adName->value;
	}
}
unset($postData["adId"]);
$postData["completenessPercentage"] = "85"; // a default value, it resets when page loads
$postData["machineId"] = "56b9da8f-a4d4-41f0-a28d-6a3d1b77c05e-15621000944";
$postData["_mrk_trk"] = "id:038-AZF-323&token:_mch-gumtree.co.za-1468933337232-41877";

parse_str(parse_url(fetch("http://www.gumtree.co.za/post.html", array("post" => $postData))["redirect_url"], PHP_URL_QUERY), $strout);
printf("The ad repost was %ssuccessful", $strout["activateStatus"] == "adActivateSuccess" ? "" : "un");

?>