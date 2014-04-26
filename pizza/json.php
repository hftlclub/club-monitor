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

		$i = 1;
		//build JSON from datasets
		while($row = mysql_fetch_assoc($sql)){
			$ingr = explode("\n",$row['ingredients']);
						
			$out .= "{\n";
			$out .= "    \"id\" : \"".$row['id']."\",\n";
			$out .= "    \"number\" : \"".$row['number']."\",\n";
			$out .= "    \"name\"   : \"".$row['name']."\",\n";
			$out .= "    \"ingredients\" : [\n";
			
			//go through newline-separated ingredients
			for($j = 0; $j < count($ingr); $j++){
				$out .= "            \"".trim($ingr[$j])."\"";
				
				//no comma for last entry
				if($j < (count($ingr) - 1)){
					$out .= ",";
				}
				
				$out .= "\n";
			}
			
			$out .= "    ]\n";
			$out .= "}";
			
			//no comma for last entry
			if($i != $num){
				$out .= ",";
			}
			$out .= "\n";
	
			$i++;
	
		}
	}
}







//show output
echo "[\n";
echo $out;
echo "]\n";

?>