<?php
require_once("../../common/config.php");

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

if($mode == 'reorderTimeline') {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("UPDATE `infoscreen_timeline` SET `order`='".(int)($data->order)."' WHERE `id`='".$data->id."'");
	}
	mysql_query("COMMIT");
}

if($mode == 'activateItem') {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("UPDATE `infoscreen_timeline` SET `active`='1' WHERE `id`='".$data->id."'");
	}
	mysql_query("COMMIT");
}

if($mode == 'disableItem') {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("UPDATE `infoscreen_timeline` SET `active`='0' WHERE `id`='".$data->id."'");
	}
	mysql_query("COMMIT");
}

if($mode == 'deleteItem') {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("DELETE FROM `infoscreen_timeline` WHERE `id`='".$data->id."'");
	}
	mysql_query("COMMIT");
}

if($mode == 'addItem') {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	
	$returnId = null;
	
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
	
	$output[] = array(
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
		VALUES ( '".$timelineId."', '10', 'drinks', '".$moduleId."' , '".(int)($data->order)."', '0' );
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

if($mode == 'retrieve') {

	//get additives and write them to array $adds
	$timeline_result = mysql_query("SELECT * FROM infoscreen_timeline ORDER BY `order` ASC;");
	while($row = mysql_fetch_assoc($timeline_result)){
		
		if($row['moduleid']) {
			$module_result = mysql_query("SELECT * FROM module_".$row["type"]." WHERE id='".$row['moduleid']."';");
			$settings = mysql_fetch_object($module_result);
		} else {
			$settings = NULL;
		}

		$output[] = array(
			"id" => $row["id"],
			"type" => $row["type"],
			"duration" => $row["duration"],
			"active" => ($row["active"]==1),
			"settings" => $settings,
		);
	}
}






if($mode == 'editItem') {
	$data = json_decode( file_get_contents('php://input') );
	
	//print_r($contents); exit;
	
	
	mysql_query("BEGIN");
	
	//update duration and active state
	$act = ($data->active) ? 1 : 0;
	mysql_query("UPDATE infoscreen_timeline SET duration = ".(int)($data->duration).", active = ".$act." WHERE id = '".$data->id."';");


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
	$query = "UPDATE module_barclosing SET time = '".$data->settings->time."' WHERE id = '".getModuleIdFromTimeline($data->id)."';";
	
	mysql_query($query);
}

function editItemText($data) {
	$query = "UPDATE module_text SET
				headline = '".$data->settings->headline."',
				body     = '".$data->settings->body."'
			WHERE id = '".getModuleIdFromTimeline($data->id)."';";
	
	mysql_query($query);
}

function editItemHighlights($data) {
	$query = "UPDATE module_highlights SET
				description = '".$data->settings->description."',
				url         = '".$data->settings->url."'
			WHERE id = '".getModuleIdFromTimeline($data->id)."';";
	
	mysql_query($query);
}


function getModuleIdFromTimeline($tid){
	$sql = mysql_query("SELECT moduleid FROM infoscreen_timeline WHERE id = '".$tid."';");
	
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






if($mode == 'fileUpload') {
	$file     = $_FILES['file'];
	$pathinfo = pathinfo($file['name']);
	
	$mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file['tmp_name']);

	if(preg_match("/image/", $mime)){
		$webpath = "/common/uploads/";
		$relpath = getcwd()."/../../".$webpath;
 
		$filename  = myuniqid()."_".mysql_real_escape_string($file['name']);
       	if(move_uploaded_file($file['tmp_name'], $relpath.$filename)){
	    	error_log($webpath.$filename); //debug
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
