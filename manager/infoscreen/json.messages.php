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
	

	//get messages fort this page
	$query = "SELECT * FROM infoscreen_ticker ORDER BY posted DESC LIMIT ".$start.",".$perpage.";";
	$sql = mysql_query($query);
	
	error_log($query);

	while($row = mysql_fetch_assoc($sql)){
		$output['messages'][] = $row;		
	}
	
	
	//count all messages
	$sql = mysql_query("SELECT COUNT(*) FROM infoscreen_ticker");
	$output['msgcount'] = intval(mysql_result($sql, 0));
	
	//pagecount
	$output['pagecount'] = ceil($output['msgcount'] / $perpage);
}






###
### Mode: resetViews
### Method: GET
###

if($mode == "editItem") {
	//$query = "UPDATE ..."
}







//////////////////////////////

//show output
if(!empty($output))
	echo json_encode($output);

?>
