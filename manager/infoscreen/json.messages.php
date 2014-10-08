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
### Mode: retrieve
### Method: GET
### Parameter: page (page number)
###

if($mode == "retrieve") {
	$output['messages'] = array();
	//entries per page
	$perpage = 100;
	
	//
	if(!isset($_GET['page'])){
		$page = 1;
	}else{
		$page = intval($_GET['page']);
	}

	$page--; //first page is 1, but we need 0 for first page
	
	if($page > 0){
		$start = $page * $perpage;
	}else{
		$start = 0;
	}
	

	//get messages for this page
	$query = "SELECT * FROM infoscreen_ticker ORDER BY posted DESC LIMIT ".$start.",".$perpage.";";
	$sql = mysql_query($query);

	while($row = mysql_fetch_assoc($sql)){
		//decide whether entry is active or not, depending on number of views
		if($row['views'] < TICKER_MAXVIEWS){
			$row['active'] = TRUE;
		}else{
			$row['active'] = FALSE;
		}
		
		//convert time to UNIX timestamp
		$row['posted'] = strtotime($row['posted']);
		
		//output row
		$output['messages'][] = $row;		
	}
	
	
	//msgcount
	$sql = mysql_query("SELECT COUNT(*) FROM infoscreen_ticker");
	$output['msgcount'] = intval(mysql_result($sql, 0));
	
	//pagecount
	$output['pagecount'] = ceil($output['msgcount'] / $perpage);
}






###
### Mode: resetViews
### Method: POST
### Parameter: msgid (ID of message)
###

if($mode == "resetViews") {
	$contents = json_decode(file_get_contents("php://input"));
	
	if(!$contents->id){
		header("HTTP/1.1 400 Bad Request");
		exit();
	}
	
	$query = "UPDATE infoscreen_ticker SET views = 0 WHERE id = '".mysql_real_escape_string($contents->id)."';";
	mysql_query($query);
}



###
### Mode: deleteMessage
### Method: POST
###

if($mode == "deleteMessage") {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("DELETE FROM `infoscreen_ticker` WHERE `id`='".mysql_real_escape_string($data->id)."';");
	}
	mysql_query("COMMIT");
}



//////////////////////////////

//show output
if(!empty($output))
	echo json_encode($output);

?>
