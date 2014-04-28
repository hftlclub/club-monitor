<?php
require_once("../common/config.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() - 3600));

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

	$dtout = array();

	//check whether service ID exists
	$query = "SELECT id FROM pizza_services WHERE id = '".$service."';";
	$sql = mysql_query($query);
	
	//exit if service ID is not valid
	if(mysql_num_rows($sql) != 1){
		die();
	}
	

	//get all pizzas from specific service
	$query = "SELECT id, number, name, ingredients FROM pizza_pizzas WHERE service = '".$service."' ORDER BY CAST(number as SIGNED INTEGER) ASC;";
	$sql   = mysql_query($query);

	//only proceed if there are results
	if(mysql_num_rows($sql)){
		while($row = mysql_fetch_assoc($sql)){
			//separate ingredients and put them into array
			$ingr = explode("\n", $row['ingredients']);
			
			//trim this array from whitespaces
			$ingr = array_map("trim", $ingr);
			
			//apply array to row (overwrite newline separated list)
			$row['ingredients'] = $ingr;
			
			//push to output
			$dtout[] = $row;
		}	
	}
}




###
### Mode: all_orders
###

if($mode == "all_orders"){
	
	$dtout = array();

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
		ORDER BY pizza_orders.timestamp ASC
	;";
	
	$sql = mysql_query($query);

	//only proceed if there are results
	if(mysql_num_rows($sql)){
		$summary = array();
		
	
		//put all orders to output array $dtout
		$dtout['orders'] = array();
		while($row = mysql_fetch_assoc($sql)){
			
			$dtout['orders'][] = array(
				"id"           => $row['id'],
				"timestamp"    => $row['timestamp'],
				"name"         => $row['name'],
				"pizza_number" => $row['pizza_number'],
				"pizza_name"   => $row['pizza_name'],
				"comment"      => $row['comment'],
				"paid"         => $row['paid']
			);
			
			
			/// COLLECT DATA FOR SUMMARY
			
			//summary only for paid pizza
			if($row['paid'] != 1)
				continue;
			
			//initialize summary array
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

		//Generate summary
		$dtout['summary'] = array();
		
		foreach($summary AS $temp){
			//number of orders for this pizza without comment
			$cntwo = $temp['count'] - count($temp['comments']);
			
			if($cntwo != 0){ //only show if there are some left without comment
				$dtout['summary'][] = array(
					"number"  => $temp['number'],
					"count"   => $cntwo,
					"comment" => ""
				);
			}
			
			//go through commented entries and show them each separately
			if(count($temp['comments'])){
				foreach($temp['comments'] AS $comm){
					$dtout['summary'][] = array(
						"number"  => $temp['number'],
						"count"   => 1,
						"comment" => $comm
					);
				}
			}
		}
	}
}



//////////////////////////////

//show output
echo json_encode($dtout);

?>
