<?php
require("config.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() - 3600));

$mode = mysql_escape_string($_GET['mode']);



//error if no mode given
if(!$mode){
	header("HTTP/1.1 400 Bad Request");
	exit();
}



###
### Mode: getToken
### Method: GET
### Params: code (the auth code), redirect_uri
### Description: initial authorization in order to obtain access_token (authorization_code)
###

if($mode == "getToken" AND $_GET['code'] AND $_GET['redirect_uri']) {
	
	$postfields = array(
            "grant_type"   => "authorization_code",
            "code"         => $_GET['code'],
            "redirect_uri" => $_GET['redirect_uri']
	);
    
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_HTTPAUTH => CURLAUTH_ANY,
    	CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => "https://accounts.spotify.com/api/token",
	    CURLOPT_POST => 1,
	    CURLOPT_HTTPHEADER => array(
			"Authorization: Basic ".base64_encode(CLIENT_ID.":".CLIENT_SECRET)
		),
        CURLOPT_POSTFIELDS => http_build_query($postfields)
	));

	$resp = curl_exec($curl);
    curl_close($curl);
    
    echo $resp;
    
}



###
### Mode: refreshToken
### Method: GET
### Params: refresh (the refresh_token)
### Description: get new accessToken based on refreshToken from initial authorization (refresh_token)
###

if($mode == "refreshToken" && $_GET['refresh']) {
	
	$postfields = array(
		"grant_type"    => "refresh_token",
		"refresh_token" => $_GET['refresh'],
    );
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_HTTPAUTH => CURLAUTH_ANY,
    	CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => "https://accounts.spotify.com/api/token",
	    CURLOPT_POST => 1,
	    CURLOPT_HTTPHEADER => array(
			"Authorization: Basic ".base64_encode(CLIENT_ID.":".CLIENT_SECRET)
		),
        CURLOPT_POSTFIELDS => http_build_query($postfields)
	));
	
	$resp = curl_exec($curl);
    curl_close($curl);
    
    echo $resp;
	
}









?>
