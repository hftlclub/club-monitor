<?php
require("../common/config.php");

//This is JSON :)
header("Content-Type: application/json");

//limit from GET value or default
$limit = ($_GET['limit']) ? intval($_GET['limit']) : 1;


$query = "SELECT id, interpret, title FROM songlist ORDER BY RAND() LIMIT ".$limit.";";
$sql = mysql_query($query);
$num = mysql_num_rows($sql);


//build JSON from datasets
$parts = array();
while($row = mysql_fetch_assoc($sql)){
	$parts[] = "{ \"id\" : \"".$row['id']."\", \"value\" : \"".$row['interpret']." - ".$row['title']."\" }";
}

//show results
echo "[";
echo implode(", ", $parts);
echo "]";


?>