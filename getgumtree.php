<?php

include "config.php";
include "gumtreefunc.php";

fetch("https://www.gumtree.co.za/login",  array('post' => array('redirect' => 'https://gumtree.co.za/', 'email' => $_config->username, 'password' => $_config->password)), true);

$html = str_get_html(fetch("https://www.gumtree.co.za/my/ads.html")["content"]); // login to gumtree

$eList = array();
foreach($html->find('div.commercial') as $element) {
	$eList[] = $element;
}

uasort($eList, function($a, $b) {   
	$aC = (int)preg_replace("/[^0-9]/","",$a->children(1)->children(1)->children(1)->plaintext);
	$bC = (int)preg_replace("/[^0-9]/","",$b->children(1)->children(1)->children(1)->plaintext);

        $aT = strtolower($a->children(1)->children(0)->children(0)->children(0)->plaintext);
        $bT = strtolower($b->children(1)->children(0)->children(0)->children(0)->plaintext);
	if($aC < $bC) return -1;
	elseif($aC > $bC) return 1;
	else return strcmp($aT, $bT);
});

$eListRet = array(
	"messages" => preg_grep("/([0-9])/g",$html->find(".unreadMsg")[0]->plaintext)[0],
	"items" => array()
);
foreach($eList as $element) {
	$ad = $element->children(1)->children(0)->children(0)->children(0);
	$edit = $element->children(1)->children(3)->children(0)->children(0);
	$delete = $element->children(1)->children(3)->children(0)->children(1);
	$img = $element->children(0)->children(0)->children(0)->children(0)->src;
	$eListRet["items"][] = array(
		'title' => $ad->plaintext,
		'page' => $element->children(1)->children(1)->children(1)->plaintext,
		'hrefargs' => parse_url($edit->href, PHP_URL_QUERY),
		'edithref' => $edit->href,
		'adhref' => $ad->href,
		'imgsrc' => $img
	);
}
header('Content-type: application/json');
print(json_encode($eListRet));
?>