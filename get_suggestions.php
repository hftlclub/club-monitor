<?php
require("mysql_connect.php");

$limit = 5;
$search = $_GET['search'];

if(!$search){
	exit();
}

$sql = mysql_query("SELECT id, interpret, title FROM songlist WHERE interpret LIKE '%".$search."%' OR title LIKE '%".$search."%' LIMIT ".$limit.";");
$num = mysql_num_rows($sql);


if($num < 1){
	die();
}

echo "[\n";

$i = 1;
while($row = mysql_fetch_assoc($sql)){
	echo "{ \"id\" : \"".$row['id']."\", \"value\" : \"".$row['interpret']." - ".$row['title']."\" }";
	if($i != $num){
		echo ",";
	}
	echo "\n";
	
	$i++;
	
}

echo "]\n";


?>