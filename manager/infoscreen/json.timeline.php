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
### Mode: reorderTimeline
### Method: POST
###

if($mode == "reorderTimeline") {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("UPDATE `infoscreen_timeline` SET `order`='".(int)($data->order)."' WHERE `id`='".mysql_real_escape_string($data->id)."';");
	}
	mysql_query("COMMIT");
}



###
### Mode: activateItem
### Method: POST
###

if($mode == "activateItem") {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("UPDATE `infoscreen_timeline` SET `active`='1' WHERE `id`='".mysql_real_escape_string($data->id)."';");
	}
	mysql_query("COMMIT");
}


###
### Mode: disableItem
### Method: POST
###

if($mode == "disableItem") {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("UPDATE `infoscreen_timeline` SET `active`='0' WHERE `id`='".mysql_real_escape_string($data->id)."';");
	}
	mysql_query("COMMIT");
}


###
### Mode: deleteItem
### Method: POST
###

if($mode == "deleteItem") {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("DELETE FROM `infoscreen_timeline` WHERE `id`='".mysql_real_escape_string($data->id)."';");
	}
	mysql_query("COMMIT");
}



###
### Mode: retrieve
### Method: GET
###

if($mode == "retrieve") {

	//get timeline elements and output them in their correct order, including module specific settings
	$timeline_sql = mysql_query("SELECT * FROM infoscreen_timeline ORDER BY `order` ASC;");

	//go through timeline elements
	while($row = mysql_fetch_assoc($timeline_sql)){
		
		//does this have module specific settings?
		if($row['moduleid']) {
			$module_sql = mysql_query("SELECT * FROM module_".$row["type"]." WHERE id='".$row['moduleid']."' LIMIT 1;");
			$settings = mysql_fetch_object($module_sql);
		} else {
			$settings = NULL;
		}

		//write to output array
		$output[] = array(
			"id" => $row["id"],
			"type" => $row["type"],
			"duration" => $row["duration"],
			"active" => ($row["active"] == 1),
			"settings" => $settings,
		);
	}
}






###
### Mode: addItem
### Method: POST
###

if($mode == "addItem") {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	
	$returnId = null;
	
	//call module specific edit function
	switch ($contents->type) {
		case 'drinks':
			$returnId = addItemDrinks($contents->order);
			break;
		case 'barclosing':
			$returnId = addItemBarclosing($contents->order);
			break;
		case 'text':
			$returnId = addItemText($contents);
			break;
		case 'highlights':
			$returnId = addItemHighlights($contents);
			break;
	}
	
	mysql_query("COMMIT");
	
	//output the ID of the newly created element
	$output = array(
		"id" => $returnId
	);
}


function addItemDrinks ($order)
{
	$id = myuniqid();
	
	mysql_query("INSERT INTO `infoscreen_timeline` (
		`id` ,
		`duration` ,
		`type` ,
		`moduleid` ,
		`order` ,
		`active` 
		)
		VALUES (
		 '".$id."', '10', 'drinks', NULL , '".(int)$order."', '0'
		);
		");
	
	return $id;
}


function addItemBarclosing ($order)
{
	$moduleId = myuniqid();
	mysql_query("INSERT INTO `module_barclosing` (
		`id`
		)
		VALUES ( '".$moduleId."');
		");
		
	$timelineId = myuniqid();
	mysql_query("INSERT INTO `infoscreen_timeline` (
		`id` ,
		`duration` ,
		`type` ,
		`moduleid` ,
		`order` ,
		`active` 
		)
		VALUES ( '".$timelineId."', '10', 'barclosing', '".$moduleId."' , '".(int)$order."', '0' );
		");
	
	return $timelineId;
}


function addItemText ($data)
{
	$moduleId = myuniqid();
	mysql_query("INSERT INTO `module_text` (
		`id`
		)
		VALUES ( '".$moduleId."');
		");
		
	$timelineId = myuniqid();
	mysql_query("INSERT INTO `infoscreen_timeline` (
		`id` ,
		`duration` ,
		`type` ,
		`moduleid` ,
		`order` ,
		`active` 
		)
		VALUES ( '".$timelineId."', '10', 'text', '".$moduleId."' , '".(int)($data->order)."', '0' );
		");
	
	return $timelineId;
}


function addItemHighlights ($data)
{
	$moduleId = myuniqid();
	mysql_query("INSERT INTO `module_highlights` (
		`id`
		)
		VALUES ( '".$moduleId."');
		");
		
	$timelineId = myuniqid();
	mysql_query("INSERT INTO `infoscreen_timeline` (
		`id` ,
		`duration` ,
		`type` ,
		`moduleid` ,
		`order` ,
		`active` 
		)
		VALUES ( '".$timelineId."', '10', 'highlights', '".$moduleId."' , '".(int)($data->order)."', '0' );
		");
	
	return $timelineId;
}



###
### Mode: editItem
### Method: POST
###

if($mode == "editItem") {
	$data = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	
	//update duration and active state
	$act = ($data->active) ? 1 : 0;
	mysql_query("UPDATE infoscreen_timeline SET duration = ".(int)($data->duration).", active = ".$act." WHERE id = '".mysql_real_escape_string($data->id)."';");


	switch ($data->type) {
		case 'barclosing':
			editItemBarclosing($data);
			break;
		case 'text':
			editItemText($data);
			break;
		case 'highlights':
			editItemHighlights($data);
			break;
	}
	
	mysql_query("COMMIT");
}


function editItemBarclosing($data) {
	$query = "UPDATE module_barclosing SET time = '".mysql_real_escape_string($data->settings->time)."' WHERE id = '".getModuleIdFromTimeline($data->id)."';";
	
	mysql_query($query);
}


function editItemText($data) {
	$query = "UPDATE module_text SET
				headline = '".mysql_real_escape_string($data->settings->headline)."',
				body     = '".mysql_real_escape_string($data->settings->body)."'
			WHERE id = '".getModuleIdFromTimeline($data->id)."';";
	
	mysql_query($query);
}


function editItemHighlights($data) {
	$query = "UPDATE module_highlights SET
				description = '".mysql_real_escape_string($data->settings->description)."',
				url         = '".mysql_real_escape_string($data->settings->url)."',
				headline	= '".mysql_real_escape_string($data->settings->headline)."'
			WHERE id = '".getModuleIdFromTimeline($data->id)."';";
	
	mysql_query($query);
}


function getModuleIdFromTimeline($tid){
	$sql = mysql_query("SELECT moduleid FROM infoscreen_timeline WHERE id = '".mysql_real_escape_string($tid)."';");
	
	if(mysql_num_rows($sql) == 1){
		$row = mysql_fetch_assoc($sql);
		if($row['moduleid']){		
			return $row['moduleid'];
		}else{
			return false;
		}
	}else{
		return false;
	}
}




###
### Mode: fileUpload
### Method: POST
###

if($mode == "fileUpload") {
	$file = $_FILES['file'];

	//check for MIME type (must be "image/*")
	$mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file['tmp_name']);
	if(preg_match("/image/", $mime)){
		$webpath = "/common/uploads/";
		$abspath = getcwd()."/../..".$webpath;
 
		//build filename
		$filename  = myuniqid()."_".$file['name'];
		
		//upload file
       	if(move_uploaded_file($file['tmp_name'], $abspath.$filename)){
	    	//error_log($webpath.$filename); //debug
			
			//output web url of uploaded image
			$output = array(
				'url' => $webpath.$filename,
			);
       	}	
	}
}







//////////////////////////////

//show output
if(!empty($output))
	echo json_encode($output);

?>
