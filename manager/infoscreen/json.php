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
		mysql_query("UPDATE `infoscreen_timeline` SET `order`='".(int)($data->order)."' WHERE `id`='".(int)($data->id)."'");
	}
	mysql_query("COMMIT");
}

if($mode == 'activateItem') {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("UPDATE `infoscreen_timeline` SET `active`='1' WHERE `id`='".(int)($data->id)."'");
	}
	mysql_query("COMMIT");
}

if($mode == 'disableItem') {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("UPDATE `infoscreen_timeline` SET `active`='0' WHERE `id`='".(int)($data->id)."'");
	}
	mysql_query("COMMIT");
}

if($mode == 'deleteItem') {
	$contents = json_decode( file_get_contents('php://input') );
	
	mysql_query("BEGIN");
	foreach($contents as $data)
	{
		mysql_query("DELETE FROM `infoscreen_timeline` WHERE `id`='".(int)($data->id)."'");
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
	$id = myuniqid();
	
	mysql_query("INSERT INTO `infoscreen_timeline` (
		`id` ,
		`duration` ,
		`type` ,
		`moduleid` ,
		`order` ,
		`active` 
		)
		VALUES ( '".$id."', '10', 'barclosing', NULL , '".(int)$order."', '0' );
		");
	
	return $id;
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
		VALUES ( '".$timelineId."', '10', 'drinks', ".$moduleId." , '".(int)($data->order)."', '0' );
		");
	
	return $timelineId;
}

if($mode == 'retrieve') {

	//get additives and write them to array $adds
	$timeline_result = mysql_query("SELECT * FROM infoscreen_timeline ORDER BY `order` ASC;");
	while($row = mysql_fetch_assoc($timeline_result)){
		
		if($row['moduleid']) {
			$module_result = mysql_query("SELECT * FROM module_".$row["type"]." WHERE id='".$row['moduleid']."';");
			$settings = mysql_fetch_assoc($module_result);
		} else {
			$settings = array();
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



//////////////////////////////

//show output
if(!empty($output))
	echo json_encode($output);

?>
