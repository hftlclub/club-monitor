<?php
require_once("../common/config.php");
require_once("../common/functions.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header("Expires: ".gmdate("D, d M Y H:i:s \G\M\T", time() - 3600));

$mode = mysql_real_escape_string($_GET['mode']);



//exit if no mode given
if(!$mode){
	die();
}

$out = array();


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
### Mode: randomSong
### Method: GET
### Params: limit (optional) (number of songs to return)
### Description: returns 1 or <count> random songs
###

if($mode == "randomSong") {

	//limit from GET value or default
	$limit = ($_GET['limit']) ? intval($_GET['limit']) : 1;


	$query = "SELECT id, interpret, title FROM songlist ORDER BY RAND() LIMIT ".$limit.";";
	$sql = mysql_query($query);

	while($row = mysql_fetch_assoc($sql)){
		$out[] = array(
			"id"    => $row['id'],
			"value" => $row['interpret']." - ".$row['title']
		);
	}
}



###
### Mode: getSuggestions
### Method: GET
### Params: search (search string)
### Description: return songs containing all words from the search string
###

if($mode == "getSuggestions" AND $_GET['search']) {

	//max number of entries
	$limit = 100;

	$getsearch = mysql_real_escape_string($_GET['search']);
	$search = explode(" ", $getsearch);
	
	$query = "SELECT id, interpret, title FROM songlist WHERE ";
	
	//go through search words and generate WHERE clauses
	for($i = 0; $i < count($search); $i++){
		
		//skip this step if search word is a dash
		if($search[$i] == "-"){
			continue;
		}
		
		$query .= "(interpret LIKE '%".$search[$i]."%' OR title LIKE '%".$search[$i]."%')";
		
		//append "OR" if not the last entry
		if($i < (count($search) - 1)){
			$query .= " AND ";
		}
	}
	
	$query .= " ORDER BY interpret, title ASC LIMIT ".$limit.";";
	
	$sql = mysql_query($query);
	$num = mysql_num_rows($sql);

	//only proceed if there are results
	if($num > 0){

		//build array from datasets
		while($row = mysql_fetch_assoc($sql)){
			
			$out[] = array(
				"id"    => $row['id'],
				"value" => $row['interpret']." - ".$row['title']
			);
	
		}
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



###
### Mode: truncateQueue
### Method: POST
### Description: truncate the whole queue 
###

if($mode == "truncateQueue") {

	mysql_query("DELETE FROM queue;");

}



//////////////////////////////

//show output
if(!empty($out))
	echo json_encode($out);

?>
