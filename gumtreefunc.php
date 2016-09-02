<?php

include "simpledom/simple_html_dom.php";
function fetch($url, $args = null, $bypass = false) {
	if(isset($args["get"])) {
		$url .= "?";
		foreach($args["get"] as $aK => $aV) {
			$url .= $aK."=".$aV."&";
		}
	}

	$cookie_file_path 	= dirname(__FILE__)."/cookie.txt"; // php cookie jar
	$options		= array( // cURL options
		CURLOPT_HEADER 			=> false,
		CURLOPT_NOBODY			=> false,
		CURLOPT_URL 			=> $url,
		CURLOPT_SSL_VERIFYHOST 	=> 0,
		CURLOPT_HTTPHEADER		=> array("Expect:  "),
		
		CURLOPT_USERAGENT 		=> "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7",
		CURLOPT_RETURNTRANSFER 	=> 1,
		CURLOPT_REFERER			=> $_SERVER['REQUEST_URI'],
		CURLOPT_SSL_VERIFYPEER 	=> 0,
		CURLOPT_FOLLOWLOCATION 	=> 0,
		
		// set options for cookie jar
		CURLOPT_COOKIEJAR		=> $cookie_file_path,
		CURLOPT_COOKIEFILE		=> $cookie_file_path,
		CURLOPT_COOKIE			=> "cookiename=gumtree"
	);

	$ch = curl_init();
	curl_setopt_array($ch, $options);
	
	// set post options
	if(isset($args["post"])) {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args["post"]));
	}

	$roughresult		= curl_exec($ch);
	$err     		= curl_errno( $ch );
	$errmsg  		= curl_error( $ch );
	$header  		= curl_getinfo( $ch );
	curl_close($ch);

	$header_content = substr($roughresult, 0, $header['header_size']);
	$body_content = trim(str_replace($header_content, '', $roughresult));
	$pattern = "#Set-Cookie:\\s+(?<cookie>[^=]+=[^;]+)#m"; 
	preg_match_all($pattern, $header_content, $matches); 
	$cookiesOut = implode("; ", $matches['cookie']);
	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['headers']  = $header_content;
	$header['content'] = $body_content;
	$header['cookies'] = $cookiesOut;
	
	return $header;
}