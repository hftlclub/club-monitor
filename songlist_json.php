<?php
require("mysql_connect.php");
header("Content-type: application/json");
?>
[
<?php
$sql = mysql_query("SELECT id, interpret, title FROM songlist ORDER BY interpret, title ASC;");
while($row = mysql_fetch_assoc($sql)){
	echo "{ \"id\" : \"".$row['id']."\", \"value\" : \"".$row['interpret']." - ".$row['title']."\", \"tokens\" : [";
	$tokens = array_merge(explode(" ", $row['interpret']), explode(" ", $row['title']));
	for($i = 0; $i < count($tokens); $i++){
		$token = $tokens[$i];
		echo "	\"".$token."\"";
		
		//don't show comma after last entry
		if($i < (count($tokens) - 1)){
			echo ",";
		}
		echo "\n";
	}
	echo "] },\n";	
}
?>
{ "id": "" , "value" : "" }
]