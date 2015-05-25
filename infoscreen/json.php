<?php
require_once("../common/config.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header("Expires: ".gmdate("D, d M Y H:i:s \G\M\T", time() - 3600));

$mode = mysql_real_escape_string($_GET['mode']);




//exit if no mode given
if(!$mode){
	die();
}




###
### Mode: timeline
### Method: GET
###

if($mode == "timeline"){
	$output = array();

	
	$tl_sql = mysql_query("SELECT * FROM infoscreen_timeline WHERE active = 1 ORDER BY `order` ASC;");
	while($tl_row = mysql_fetch_assoc($tl_sql)){
		
		if($tl_row['moduleid']) {
			$mod_sql = mysql_query("SELECT * FROM module_".$tl_row['type']." WHERE id = '".$tl_row['moduleid']."' LIMIT 1;");
			$settings = mysql_fetch_assoc($mod_sql);
			if($tl_row['type'] == 'highlights'){
				$settings['url'] = UPLOADED_RESSOURCES_URLPREFIX . $settings['url'];
			}
		} else {
			$settings = array();
		}

		$output[] = array(
			"id"       => $tl_row['id'],
			"type"     => $tl_row['type'],
			"duration" => $tl_row['duration'],
			"settings" => $settings,
		);
	}
}




###
### Mode: ticker
### Method: GET
###

if($mode == "ticker"){
	$output = array();

	$sql = mysql_query("SELECT id, author, text, posted FROM infoscreen_ticker WHERE views < ".TICKER_MAXVIEWS." ORDER BY views ASC, posted ASC LIMIT 1;");
	while($row = mysql_fetch_assoc($sql)){
		$output[] = array(
			"id"     => $row["id"],
			"author" => $row["author"],
			"text"   => $row["text"],
			"posted" => date('H:i', strtotime($row["posted"]))
		);
		mysql_query("UPDATE infoscreen_ticker SET `views` = `views`+1 WHERE `id`='".$row['id']."';");
	}
}

//////////////////////////////

//show output
if(!empty($output))
	echo json_encode($output);

?>
