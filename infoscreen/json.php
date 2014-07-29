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
###


if($mode == "drinks"){

	$dtout = array();
	$cats  = array();
	$adds  = array();
	
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
	$query = "SELECT id, name FROM drinks_categories ORDER BY position ASC;";
	$sql   = mysql_query($query);

	//only proceed if there are results
	if(mysql_num_rows($sql)){
		//go through categories
		while($row = mysql_fetch_assoc($sql)){
			$drinks_query = "SELECT id, name, price FROM drinks_drinks WHERE category = '".$row['id']."';";
			$drinks_sql = mysql_query($drinks_query);
			
			//only add this category if it contains any drinks
			if(mysql_num_rows($drinks_sql)){
				$row['drinks'] = array();
				
				//go through drinks
				while($drinks_row = mysql_fetch_assoc($drinks_sql)){
					
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
					
					//write drink to list
					$row['drinks'][] = $drinks_row;
				}
				
				//write category to list
				$cats[] = $row;
			}
		}
		
		//write categories to output array
		$dtout['categories'] = $cats;
			
	}
	
	//write additives to output array
	$dtout['additives'] = array_values($adds);
}


if($mode == "timeline"){

	
	$timelineEntry1  = array("id"=>3,"duration"=>10,"type"=>"drinks", "settings"=>null);
	$timelineEntry2  = array("id"=>2,"duration"=>10,"type"=>"text", "settings"=>array("headline"=>"Da ist was los!", "body"=>"<h1>geiler lorem ipsum text
	blabla...</h1>"));
	$timelineEntry3  = array("id"=>6,"duration"=>10,"type"=>"highlights", "settings"=>array("description"=>"Da ist was los! Und hier soll auch <b>HTML</b> möglich sein...", "url"=>"http://stura.hft-leipzig.de/wp-content/uploads/2014/07/beachparty_plakat_klein.jpg"));
	$dtout = array($timelineEntry1,$timelineEntry2,$timelineEntry3);
	
	
/*
	$dtout = array();
	$cats  = array();
	$adds  = array();
	
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
	$query = "SELECT id, name FROM drinks_categories ORDER BY position ASC;";
	$sql   = mysql_query($query);

	//only proceed if there are results
	if(mysql_num_rows($sql)){
		//go through categories
		while($row = mysql_fetch_assoc($sql)){
			$drinks_query = "SELECT id, name, price FROM drinks_drinks WHERE category = '".$row['id']."';";
			$drinks_sql = mysql_query($drinks_query);
			
			//only add this category if it contains any drinks
			if(mysql_num_rows($drinks_sql)){
				$row['drinks'] = array();
				
				//go through drinks
				while($drinks_row = mysql_fetch_assoc($drinks_sql)){
					
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
					
					//write drink to list
					$row['drinks'][] = $drinks_row;
				}
				
				//write category to list
				$cats[] = $row;
			}
		}
		
		//write categories to output array
		$dtout['categories'] = $cats;
			
	}
	
	//write additives to output array
	$dtout['additives'] = array_values($adds);
	
	*/
	
}




//////////////////////////////

//show output
echo json_encode($dtout);

?>
