<?php
require_once("../../common/config.php");
require_once("../../common/functions.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header("Expires: ".gmdate("D, d M Y H:i:s \G\M\T", time() - 3600));

$mode = mysql_real_escape_string($_GET['mode']);
$token = mysql_real_escape_string($_GET['token']);



//exit if no mode given
if(!$mode){
	die();
}


//deny access if no token given
if(!$token || !checkAuthenticationToken($token)){
	header('HTTP/1.1 401 Unauthorized');
	die();
}

$output = array();


###
### Mode: getActive
### Method: GET
### Description: return all active queue elements (active = not played yet)
###

if($mode == "getActive") {
	$out['queue'] = array();

	$query = "SELECT queue.id, queue.singer, queue.timestamp, songlist.interpret, songlist.title FROM queue, songlist WHERE songlist.id = queue.songid AND queue.played = 0 ORDER BY queue.timestamp ASC;";
	$sql = mysql_query($query);

	$out['count'] = mysql_num_rows($sql);

	while($row = mysql_fetch_assoc($sql)){
		$out['queue'][] = $row;
	}
}



###
### Mode: setPlayed
### Method: POST
### Description: set "played" state to 1 for a specific 
###

if($mode == "setPlayed") {
	$contents = json_decode(file_get_contents("php://input"));
	
	mysql_query("BEGIN");
	foreach($contents as $data){
		mysql_query("UPDATE queue SET played = 1 WHERE id = '".mysql_real_escape_string($data->id)."';");
	}
	mysql_query("COMMIT");
}



//////////////////////////////

//show output
if(!empty($out))
	echo json_encode($out);

?>
