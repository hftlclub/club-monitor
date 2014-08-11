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
### Mode: addShortMessage
### Method: POST
###

if($mode == "addShortMessage") {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("INSERT INTO `infoscreen_ticker` (
		`id` ,
		`author` ,
		`text`
		)
		VALUES (
			'".myuniqid()."',
			'".mysql_real_escape_string($contents->author)."',
			'".mysql_real_escape_string($contents->text)."'
		);
	");
}



//////////////////////////////

//show output
if(!empty($output))
	echo json_encode($output);

?>
