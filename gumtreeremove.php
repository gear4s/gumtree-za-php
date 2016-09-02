<?php

include "config.php";
include "gumtreefunc.php";

$getData = array(
	"adId" => $_GET["adId"],
	"guid" => $_get["guid"],
	"delConfirm" => "yes",
	"fromMyAds" => "yes",
	"r" => floor(rand()*100)+1
);

parse_str(parse_url(fetch("http://www.gumtree.co.za/deleteAd.html", array("get" => $getData))["redirect_url"], PHP_URL_QUERY), $strout);

?>