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

		$i = 1;
		//build JSON from datasets
		while($row = mysql_fetch_assoc($sql)){
			$ingr = explode("\n", $row['ingredients']);
						
			$out .= "{";
			$out .= "\"id\" : \"".$row['id']."\", ";
			$out .= "\"number\" : \"".$row['number']."\", ";
			$out .= "\"name\" : \"".$row['name']."\", ";
			$out .= "\"ingredients\" : [";
			
			//go through newline-separated ingredients
			for($j = 0; $j < count($ingr); $j++){
				$out .= "\"".trim($ingr[$j])."\"";
				
				//no comma for last entry
				if($j < (count($ingr) - 1)){
					$out .= ", ";
				}
			}
			
			$out .= "]";
			$out .= "}";
			
			//no comma for last entry
			if($i != $num){
				$out .= ",";
			}
	
			$i++;
		}
		
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
				pizza_pizzas.name AS pizza_name
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

		$i = 1;
		//build JSON from datasets
		while($row = mysql_fetch_assoc($sql)){
						
			$out .= "{";
			$out .= "\"id\" : \"".$row['id']."\", ";
			$out .= "\"name\" : \"".$row['name']."\", ";
			$out .= "\"pizza_number\" : \"".$row['pizza_number']."\", ";
			$out .= "\"pizza_name\" : \"".$row['pizza_name']."\", ";
			$out .= "\"comment\" : \"".$row['comment']."\", ";
			$out .= "\"paid\" : ".$row['paid'];
			$out .= "}";
			
			//no comma for last entry
			if($i != $num){
				$out .= ", ";
			}
	
			$i++;
			
			//set summary
			
			//initialize array
			if(!array_key_exists($row['id'], $summary)){
				$summary[$row['id']] = array(
					"number" => $row['pizza_number'],
					"count" => 0,
					"comments" => array()
				);
			}
			
			//sum up
			$summary[$row['id']]['count']++;
			if($row['comment']){
				$summary[$row['id']]['comments'][] = $row['comment'];
			}
			
			
		}
		
		$out .= "],";
		
		

		
		$out .= "\"summary\" : [";
		
		foreach($summary AS $temp){
			$out .= "{\"number\" : \"".$temp['number']."\", \"count\" : ".($temp['count'] - count($temp['comments'])).", \"comment\" : null}, ";
			
			if(count($temp['comments'])){
				foreach($temp['comments'] AS $comm){
					$out .= "{\"number\" : \"".$temp['number']."\", \"count\" : 1, \"comment\" : \"".$comm."\"}, ";
				}
			}
			

		}
		
		//cut last two chars
		if(substr($out, -2, 2) == ", "){
			$out = substr($out, 0, strlen($out) - 2);
		}
		
		$out .= "]";
		
		
		$out .= "}";
		

	}
}






//show output
echo $out;

?>