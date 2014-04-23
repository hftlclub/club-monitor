<?php
require("mysql_connect.php");

//max number of entries
$limit = 7;

header("Content-Type: application/json");



if($search){
	$search = explode(" ", mysql_real_escape_string($_GET['search']));

	
	$sql = "SELECT id, interpret, title FROM songlist WHERE ";
	
	//go through search words and generate WHERE clauses
	for($i = 0; $i < count($search); $i++){
		$sql .= "interpret LIKE '%".$search[$i]."%' OR title LIKE '%".$search[$i]."%'";
		
		//append "OR" if not the last entry
		if($i < (count($search) - 1)){
			$sql .= " OR ";
		}
	}
	
	$sql .= " ORDER BY interpret, title ASC LIMIT ".$limit.";";
	
	
	
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