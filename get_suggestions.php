<?php
require("mysql_connect.php");

$limit = 5;
$search = $_GET['search'];



if($search){
	$sql = mysql_query("SELECT id, interpret, title FROM songlist WHERE interpret LIKE '%".$search."%' OR title LIKE '%".$search."%' ORDER BY interpret, title ASC LIMIT ".$limit.";");
	$num = mysql_num_rows($sql);


	if($num < 1){

		$i = 1;
		while($row = mysql_fetch_assoc($sql)){
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