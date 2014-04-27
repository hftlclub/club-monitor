<?php
require("../common/config.php");

//max number of entries
$limit = 1;

header("Content-Type: application/json");

$getsearch = mysql_real_escape_string($_GET['search']);

if($getsearch){
	$search = explode(" ", $getsearch);

	
	$sql = "SELECT id, interpret, title FROM songlist ";
	
	//get A random entry
	$sql .= " ORDER BY RAND() LIMIT ".$limit.";";
	
	//die($sql);
	
	$query = mysql_query($sql);
	$num = mysql_num_rows($query);

	//only proceed if there are results
	if($num > 0){

		$i = 1;
		//build JSON from datasets
		while($row = mysql_fetch_assoc($query)){
			$out .= "{ \"id\" : \"".$row['id']."\", \"value\" : \"".$row['interpret']." - ".$row['title']."\" }";
			
			//only show comma if this is not the last entry
			if($i != $num){
				$out .= ",";
			}
			$out .= "\n";
	
			$i++;
	
		}
	}
}

//show results
echo "[\n";
echo $out;
echo "]\n";


?>