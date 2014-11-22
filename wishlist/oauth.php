<?php
require("config.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() - 3600));

$mode = mysql_escape_string($_GET['mode']);




//exit if no mode given
if(!$mode){
	die();
}


$out = array();


###
### Mode: getToken
### Method: GET
### Description: return all active queue elements (active = not played yet)
###

if($mode == "getToken") {
	
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
		CURLOPT_POSTFIELDS => "grant_type=client_credentials"
	));
	
	$resp = curl_exec($curl);
	curl_close($curl);
	
	echo $resp;
	
}


//////////////////////////////

//show output
if(!empty($out))
	echo json_encode($out);


?>