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
	foreach($tokens AS $token){
		echo "	\"".$token."\",\n";
	}
	echo "] },\n";	
}
?>
{ "id": "" , "value" : "" }
]