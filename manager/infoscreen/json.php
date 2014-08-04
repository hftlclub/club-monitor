<?php
require_once("../../common/config.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header("Expires: ".gmdate("D, d M Y H:i:s \G\M\T", time() - 3600));

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

if($mode == 'retrieve') {
	$dtout = array();

	//get additives and write them to array $adds
	$timeline_result = mysql_query("SELECT * FROM infoscreen_timeline ORDER BY `order` ASC;");
	while($row = mysql_fetch_assoc($timeline_result)){
		
		if($row['moduleid']) {
			$module_result = mysql_query("SELECT * FROM module_".$row["type"]." WHERE id='".$row['moduleid']."';");
			$settings = mysql_fetch_assoc($module_result);
		} else {
			$settings = array();
		}

		$dtout[] = array(
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
echo json_encode($dtout);

?>
