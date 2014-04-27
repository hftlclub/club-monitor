<?php
require_once("../common/config.php");

//this is JSON!
header("Content-Type: application/json");

$mode = mysql_real_escape_string($_GET['mode']);


//exit if no mode given
if(!$mode){
	die();
}

###
### Mode: all_pizzas
###

$service = mysql_real_escape_string($_GET['service']);

//SIMPLIFICATION, delete later!
$service = "535c198491490"; //Bittus pizza


if($mode == "all_pizzas" && $service){
	unset($out);
	
	//check whether service ID exists
	$query = "SELECT id FROM pizza_services WHERE id = '".$service."';";
	$sql = mysql_query($query);
	
	//exit if service ID is not valid
	if(mysql_num_rows($sql) != 1){
		die();
	}

	//get all pizzas from specific service
	$query = "SELECT * FROM pizza_pizzas WHERE service = '".$service."' ORDER BY CAST(number as SIGNED INTEGER) ASC;";
	$sql   = mysql_query($query);
	
	$num = mysql_num_rows($sql);

	//only proceed if there are results
	if($num > 0){

		$out .= "[";

		//build JSON from datasets
		$parts = array();
		while($row = mysql_fetch_assoc($sql)){
			$ingr = explode("\n", $row['ingredients']);
						
			$new = "{";
			$new .= "\"id\" : \"".$row['id']."\", ";
			$new .= "\"number\" : \"".$row['number']."\", ";
			$new .= "\"name\" : \"".$row['name']."\", ";
			$new .= "\"ingredients\" : [";
			
			//go through newline-separated ingredients
			$partsing = array();
			for($j = 0; $j < count($ingr); $j++){
				$partsing[] = "\"".trim($ingr[$j])."\"";
			}
			
			$new .= implode(", ", $partsing);
			
			$new .= "]";
			$new .= "}";
			
			$parts[] = $new;
		}
		
		//join JSON parts
		$out .= implode(", ", $parts);
		
		$out .= "]";
	}
}




###
### Mode: all_orders
###

if($mode == "all_orders"){
	unset($out);

	//get all orders from DB
	$query = "SELECT
				pizza_orders.*,
				pizza_pizzas.number AS pizza_number,
				pizza_pizzas.name AS pizza_name,
				pizza_pizzas.id AS pizza_id
		FROM pizza_orders, pizza_pizzas
		WHERE
			pizza_pizzas.id = pizza_orders.pizza AND
			pizza_orders.ordered = 0
		ORDER BY timestamp ASC
	;";
	
	$sql = mysql_query($query);
	$num = mysql_num_rows($sql);

	//only proceed if there are results
	if($num > 0){
		$summary = array();
	
		$out .= "{";
		$out .= "\"orders\" : [";

		$parts = array();
		//build JSON from datasets
		while($row = mysql_fetch_assoc($sql)){
			
			//convert newlines to \n
			$row['comment'] = str_replace("\n","\\n", $row['comment']);
			
			$new = "{";
			$new .= "\"id\" : \"".$row['id']."\", ";
			$new .= "\"name\" : \"".$row['name']."\", ";
			$new .= "\"pizza_number\" : \"".$row['pizza_number']."\", ";
			$new .= "\"pizza_name\" : \"".$row['pizza_name']."\", ";
			$new .= "\"comment\" : \"".$row['comment']."\", ";
			$new .= "\"paid\" : ".$row['paid'];
			$new .= "}";
			
			$parts[] = $new;
			
			
			/// DATA FOR SUMMARY
			
			//initialize array
			if(!array_key_exists($row['pizza_id'], $summary)){
				$summary[$row['pizza_id']] = array(
					"number" => $row['pizza_number'],
					"count" => 0,
					"comments" => array()
				);
			}
			
			//sum up
			$summary[$row['pizza_id']]['count']++;
			if($row['comment']){
				$summary[$row['pizza_id']]['comments'][] = $row['comment'];
			}
			
			
		}
		
		//join JSON parts
		$out .= implode(", ", $parts);
		
		$out .= "],";
		
		

		//Generate summary
		$out .= "\"summary\" : [";
		
		$parts = array();
		
		foreach($summary AS $temp){
			if(($temp['count'] - count($temp['comments'])) != 0){ //only show if there are some left without comment
				$parts[] = "{\"number\" : \"".$temp['number']."\", \"count\" : ".($temp['count'] - count($temp['comments'])).", \"comment\" : \"\"}";
			}
			
			//go through commented entries and show them each separately
			if(count($temp['comments'])){
				foreach($temp['comments'] AS $comm){
					$parts[] = "{\"number\" : \"".$temp['number']."\", \"count\" : 1, \"comment\" : \"".$comm."\"}";
				}
			}
		}
		
		//join JSON parts
		$out .= implode(", ", $parts);
		
		
		$out .= "]";
		
		
		$out .= "}";
		

	}
}






//show output
echo $out;

?>