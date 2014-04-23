<?php
require("../mysql_connect.php");

$mode = $_GET['mode'];
$id   = $_GET['id'];


//GET mode unregister
if($mode == "unregister" && !empty($id)){
	$sql = "UPDATE queue SET played = 1 WHERE id = '".$id."';";
	mysql_query($sql);
	
	header("Location: index.php");
	exit();
}


//GET without parameters
?>
<html>
<head>
<title>Karaoke Manager</title>
</head>
<body>

<table>
	<tr>
		<th>Interpret</th>
		<th>Titel</th>
		<th>eingetragen von</th>
		<th>&nbsp;</th>
	</tr>

<?php

$sql = mysql_query("SELECT songlist.interpret, songlist.title, queue.singer, queue.id FROM songlist, queue WHERE queue.songid = songlist.id AND queue.played = 0;");
while($row = mysql_fetch_assoc($sql)){
	echo "	<tr>\n";
	echo "		<td>".$row['interpret']."</td>\n";
	echo "		<td>".$row['title']."</td>\n";
	echo "		<td>".$row['singer']."</td>\n";
	echo "		<td><a href=\"index.php?mode=unregister&id=".$row['id']."\"</td>\n";
	echo "	</tr>\n";
}

?>