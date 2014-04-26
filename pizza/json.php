<?php
require_once("../common/config.php");

//this is JSON!
header("Content-Type: application/json");

$mode = mysql_real_escape_string($_GET['mode']);

//exit if no mode given
if(!$mode){
	die();
}


if($mode == "all_pizzas"){
	$query = "SELECT * FROM pizza_pizzas ORDER BY CAST(number as SIGNED INTEGER) ASC;";
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
			
			for($j = 0; $j < count($ingr); $j++){
				$out .= "            \"".trim($ingr[$j])."\"";
				
				//only show comma if not the last entry
				if($j < (count($ingr) - 1)){
					$out .= ",";
				}
				
				$out .= "\n";
			}
			
			$out .= "    ]\n";
			$out .= "}";
			
			//only show comma if this is not the last entry
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