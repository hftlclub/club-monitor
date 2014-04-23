<?php
require("mysql_connect.php");

$limit = 5;
$search = $_GET['search'];

if(!$search){
	exit();
}

$sql = mysql_query("SELECT id, interpret, title FROM songlist WHERE interpret LIKE '%".$search."%' OR title LIKE '%".$search."%' LIMIT ".$limit.";");

if(mysql_num_rows($sql) < 1){
	die();
}

echo "[\n";

while($row = mysql_fetch_assoc($sql)){
	echo "{ \"id\" : \"".$row['id']."\", \"value\" : \"".$row['interpret']." - ".$row['title']."\" },";
}

echo "]\n";


?>