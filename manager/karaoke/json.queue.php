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
### Mode: getSongInputStatus
### Method: GET
### Description: return true if currently song inputs can be done by the front-end users
###

if($mode == "getSongInputStatus") {
	$out['songInputActive'] = false;

	$query = "SELECT `value` FROM `options` WHERE `key` = 'karaoke_songInputActive';";
	$sql = mysql_query($query);

	$row = mysql_fetch_object($sql);
    $out['songInputActive'] = filter_var($row->value, FILTER_VALIDATE_BOOLEAN);
}

###
### Mode: setSongInputStatus
### Method: POST
### Description: sets if currently song inputs can be done by the front-end users
###

if($mode == "setSongInputStatus") {
    $content = json_decode(file_get_contents("php://input"));
    $query = "UPDATE `options` SET `value` = '".mysql_real_escape_string($content->songInputActive)."' WHERE `options`.`key` = 'karaoke_songInputActive';";
	$sql = mysql_query($query);
}


###
### Mode: getQueue
### Method: GET
### Description: return all active queue elements (active = not played yet)
###

if($mode == "getQueue") {
	$out['queue'] = array();

	$query = "SELECT queue.id, queue.singer, queue.timestamp, songlist.interpret, songlist.title FROM queue, songlist WHERE songlist.id = queue.songid AND queue.played = 0 ORDER BY queue.order ASC;";
	$sql = mysql_query($query);

	$out['count'] = mysql_num_rows($sql);

	while($row = mysql_fetch_assoc($sql)){
		$out['queue'][] = $row;
	}
	
	
	//overall count
	$sql = mysql_query("SELECT COUNT(*) FROM queue;");
	$out['countall'] = intval(mysql_result($sql, 0));
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



###
### Mode: truncateQueue
### Method: POST
### Description: truncate the whole queue 
###

if($mode == "truncateQueue") {

	mysql_query("DELETE FROM queue;");

}


###
### Mode: reorderQueue
### Method: POST
###

if($mode == "reorderQueue") {
	$contents = json_decode(file_get_contents('php://input'));
	
	mysql_query("BEGIN");
	foreach($contents as $data) {
		mysql_query("UPDATE queue SET `order` = ".(int)($data->order)." WHERE id = '".mysql_real_escape_string($data->id)."';");
	}
	
	mysql_query("COMMIT");
}




//////////////////////////////

//show output
if(!empty($out))
	echo json_encode($out);

?>
