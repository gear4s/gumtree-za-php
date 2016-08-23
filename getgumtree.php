<?php

include "gumtreefunc.php";

//username and password of account
$username = "user";
$password = "pass";

// #####################
// no need to edit below
// #####################

// login if not logged in yet and retrieve the webpage
fetch("https://www.gumtree.co.za/login",  array('post' => array('redirect' => 'https://gumtree.co.za/', 'email' => $username, 'password' => $password)), true);
$html = str_get_html(fetch("https://www.gumtree.co.za/my/ads.html")["content"]); // login to gumtree

$eList = array();
foreach($html->find('div.commercial') as $element) {
	$eList[] = $element->children(1);
}

uasort($eList, function($a, $b) {   
	$aC = (int)preg_replace("/[^0-9]/","",$a->children(1)->children(1)->plaintext);
	$bC = (int)preg_replace("/[^0-9]/","",$b->children(1)->children(1)->plaintext);

        $aT = strtolower($a->children(0)->children(0)->children(0)->plaintext);
        $bT = strtolower($b->children(0)->children(0)->children(0)->plaintext);
	if($aC < $bC) return -1;
	elseif($aC > $bC) return 1;
	else return strcmp($aT, $bT);
});

$eListRet = array();
foreach($eList as $element) {
	$ad = $element->children(0)->children(0)->children(0);
	$edit = $element->children(3)->children(0)->children(0);
	$delete = $element->children(3)->children(0)->children(1);
	$eListRet[] = array(
		'title' => $ad->plaintext,
		'page' => $element->children(1)->children(1)->plaintext,
		'hrefargs' => parse_url($edit->href, PHP_URL_QUERY),
		'edithref' => $edit->href,
		'adhref' => $ad->href,
		'delhref' => $delete->href
	);
}
header('Content-type: application/json');
print(json_encode($eListRet));
?>