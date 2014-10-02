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
### Mode: drinks
### Method: GET
###


if($mode == "drinks"){

	//init
	$output = array();
	$cats   = array();
	$adds   = array();


	//get additives and write them to array $adds
	$query = "SELECT * FROM drinks_additives";
	$sql = mysql_query($query);
	$i = 1;
	while($row = mysql_fetch_assoc($sql)){
		//assign index number dynamically
		$row['index'] = $i++;
		
		//add additive to array $adds
		$adds[$row['id']] = $row;
	}
	

	//get categories
	$cat_sql = mysql_query("SELECT id, name FROM drinks_categories;");

	if(mysql_num_rows($cat_sql)){ //only proceed if there are results
		//go through categories
		while($cat_row = mysql_fetch_assoc($cat_sql)){
			$drinks_sql = mysql_query("SELECT id, name, price, size, `group` FROM drinks_drinks WHERE category = '".$cat_row['id']."';");
			
			//only add this category if it contains any drinks
			if(mysql_num_rows($drinks_sql)){
				$cat_row['drinks'] = array();
				
				//go through drinks
				while($drinks_row = mysql_fetch_assoc($drinks_sql)){
					
					if($drinks_row["group"] == null)
						$drinks_row["group"] = "";
					
					//get additives for this drink
					$add_query = "SELECT additive FROM drinks_addassign WHERE drink = '".$drinks_row['id']."';";
					$add_sql = mysql_query($add_query);

					//if there are additives.....
					if(mysql_num_rows($add_sql)){
						$drinks_row['additives'] = array();
						
						//add their indices to the drink; get index from pre-fetched data
						while($add_row = mysql_fetch_assoc($add_sql)){
							$drinks_row['additives'][] = $adds[$add_row['additive']]['index'];
						}
						
						//sort the index list
						sort($drinks_row['additives']);
					}
					
					//write drink to category
					$cat_row['drinks'][] = $drinks_row;
				}
				
				//write category to list
				$cats[] = $cat_row;
			}
		}
		
		//write categories to output array
		$output['categories'] = $cats;
			
	}
	
	//write additives to output array
	$output['additives'] = array_values($adds);
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
