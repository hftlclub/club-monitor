<?php
require("../common/config.php");

//this is JSON!
header("Content-Type: application/json");

//and it should not be cached by browsers like IE
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() - 3600));


$out = array();


//max number of entries
$limit = 1000;


$getsearch = mysql_real_escape_string($_GET['search']);

if($getsearch){
	$search = explode(" ", $getsearch);

	
	$sql = "SELECT id, interpret, title FROM songlist WHERE ";
	
	//go through search words and generate WHERE clauses
	for($i = 0; $i < count($search); $i++){
		
		//skip this step if search word is a dash
		if($search[$i] == "-"){
			continue;
		}
		
		$sql .= "(interpret LIKE '%".$search[$i]."%' OR title LIKE '%".$search[$i]."%')";
		
		//append "OR" if not the last entry
		if($i < (count($search) - 1)){
			$sql .= " AND ";
		}
	}
	
	$sql .= " ORDER BY interpret, title ASC LIMIT ".$limit.";";
	
	//die($sql);
	
	$query = mysql_query($sql);
	$num = mysql_num_rows($query);

	//only proceed if there are results
	if($num > 0){

		//build array from datasets
		while($row = mysql_fetch_assoc($query)){
			
			$out[] = array(
				"id" => $row['id'],
				"value" => $row['interpret']." - ".$row['title']
			);
	
		}
	}
}


//////////////////////////////
//show output
echo json_encode($out);


?>
