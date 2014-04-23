<?php
require("mysql_connect.php");

$limit = 5;
$search = explode(" ", $_GET['search']);





if($search){
	$sql = "SELECT id, interpret, title FROM songlist WHERE ";
	for($i = 0; $i < count($search); $i++){
		$sql .= "interpret LIKE '%".$search[$i]."%' OR title LIKE '%".$search[$i]."%'";
		
		if($i < (count($search) - 1)){
			$sql .= " OR ";
		}
	}
	
	
	$sql .= " ORDER BY interpret, title ASC LIMIT ".$limit.";";
	
	die($sql);
	
	$query = mysql_query($sql);
	$num = mysql_num_rows($query);


	if($num > 0){

		$i = 1;
		while($row = mysql_fetch_assoc($query)){
			$out .= "{ \"id\" : \"".$row['id']."\", \"value\" : \"".$row['interpret']." - ".$row['title']."\" }";
			if($i != $num){
				$out .= ",";
			}
			$out .= "\n";
	
			$i++;
	
		}
	}
}
echo "[\n";
echo $out;
echo "]\n";


?>