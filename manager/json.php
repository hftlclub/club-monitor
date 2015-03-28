<?php
require_once("../common/config.php");
require_once("../common/functions.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header("Expires: ".gmdate("D, d M Y H:i:s \G\M\T", time() - 3600));

$output = array();

$mode = mysql_real_escape_string($_GET['mode']);

//exit if no mode given
if(!$mode){
	die();
}


###
### Mode: login
### Method: POST
###

if($mode == "login") {
	$data = json_decode( file_get_contents('php://input') );
	
	$send = json_encode(array(
		"username" => $data->username,
		"password" => $data->password,
	));

	
	$ch = curl_init(EXTAUTHURL);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($send))                                                                       
	);
	
	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	
	$authenticated = false;
	
	if($httpcode == 200){
		$authenticated = true;
	}
	
	////////////////////////
	
	if($authenticated){
    	$token = md5(myuniqid());
    
		mysql_query("INSERT INTO `authentication_tokens` (`id`, `token`, `lastused`) VALUES ('".myuniqid()."', '".$token."', CURRENT_TIMESTAMP());");
    
		$output = array(
			"type" => 'success',
			"token" => $token,
		); 
	}
	else{
    	$output = array(
			"type" => 'failure',
		); 
	}
}


//////////////////////////////

//show output
if(!empty($output))
	echo json_encode($output);

?>
