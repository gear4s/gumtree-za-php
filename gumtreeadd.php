<?php

include "config.php";
include "gumtreefunc.php";

$res = fetch("https://www.gumtree.co.za/post.html", array("get" => array("adId" => $_GET["adId"], "guid" => $_GET["guid"])));
$html = str_get_html($res["content"]);
if(!$html) {var_dump($res); return;}

$postData = array(
	"ForSaleBy" => $_config->forSaleBy,
	"Phone" => $_config->phoneNumber,
	"latitude" => "-33.844167",
	"longitude" => "18.698611",
	"prevMapAddress" => $_config->physicalAddress,
	"Address" => $_config->physicalAddress,
	"currencyValues" => "ZAR",
	"UserName" => $_config->shopName,
	"Email" => $_config->shopEmail,
	"WebSiteUrl" => $_config->websiteURL,
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
				// check if price is fixed
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
$postData["completenessPercentage"] = "85";
$postData["machineId"] = $_config->machineId;
$postData["_mrk_trk"] = $_config->_mrk_trk;

parse_str(parse_url(fetch("https://www.gumtree.co.za/post.html", array("post" => $postData))["redirect_url"], PHP_URL_QUERY), $strout);
printf("The ad repost was %ssuccessful", $strout["activateStatus"] == "adActivateSuccess" ? "" : "un");

?>