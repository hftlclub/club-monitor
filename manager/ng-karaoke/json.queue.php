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

	//get queue
	$query = "SELECT queue.singer, queue.timestamp, songlist.interpret, songlist.title FROM queue, songlist WHERE songlist.id = queue.songid AND queue.played = 0 ORDER BY queue.timestamp ASC;";
	$sql = mysql_query($query);

	$out['count'] = mysql_num_rows($sql);

	while($row = mysql_fetch_assoc($sql)){
		$out['queue'][] = $row;
	}
}



//////////////////////////////

//show output
if(!empty($out))
	echo json_encode($out);

?>
