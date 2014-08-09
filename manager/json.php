<?php
require_once("../common/config.php");

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
	
	$query = mysql_query("SELECT * FROM `options` WHERE `key`='passwords';");
	
	$row = mysql_fetch_assoc ($query);
	$possiblePasswords = json_decode($row['value']);
	
	$authenticated = false;
	
	foreach($possiblePasswords as $pass)
	{	
    if(md5($data->password) == $pass->password)
    {
      $authenticated = true;
      break;
    }
	}
	
	if($authenticated)
	{
    $token = md5(myuniqid()); //TODO: save this token to DB
    
    $output = array(
			"type" => 'success',
			"token" => $token,
		); 
	}
	else
	{
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
