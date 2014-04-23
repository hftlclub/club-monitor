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
<meta http-equiv="refresh" content="3; URL=<?php echo $_SERVER['REQUEST_URI'] ?>">
</head>
<body>

<h1>Karaoke Manager</h1>

<table cellpadding="5" border="1">
	<tr>
		<th>#</th>
		<th>Interpret</th>
		<th>Titel</th>
		<th>eingetragen von</th>
		<th>&nbsp;</th>
	</tr>

<?php

$sql = mysql_query("SELECT songlist.interpret, songlist.title, queue.singer, queue.id FROM songlist, queue WHERE queue.songid = songlist.id AND queue.played = 0 ORDER BY timestamp ASC;");
$i = 1;
while($row = mysql_fetch_assoc($sql)){
	echo "	<tr>\n";
	echo "		<td>".$i++."</td>\n";
	echo "		<td>".$row['interpret']."</td>\n";
	echo "		<td>".$row['title']."</td>\n";
	echo "		<td>".$row['singer']."</td>\n";
	echo "		<td><a href=\"index.php?mode=unregister&id=".$row['id']."\">Entfernen</a></td>\n";
	echo "	</tr>\n";
}

?>
</table>