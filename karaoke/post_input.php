<?php

require("../common/config.php");

//check for post method
if($_SERVER['REQUEST_METHOD'] != "POST"){
	die("Please request with POST method.");
}

//get post data
$songid = mysql_real_escape_string($_POST['inputSongId']);
$singer = mysql_real_escape_string($_POST['inputSinger']);


$error = 0;

//check whether id exists
$sql = mysql_query("SELECT * FROM songlist WHERE id = '".$songid."';");
if(mysql_num_rows($sql) != 1){
	$error++;
	//echo "ID invalid";
}

//check if singer given
/*if(!$singer){
	$error++;
	//echo "No singer given";
}*/

//do something if error occured
if($error){
	header("Location: /karaoke/");
	//echo "Please give valid information.";
	
	exit();
}

////////////////////////////

//next order number
$order = 0;

$sql = mysql_query("SELECT `order` FROM queue WHERE played = 0 ORDER BY `order` DESC LIMIT 1;");
error_log("lalalala ".mysql_num_rows($sql));
if(mysql_num_rows($sql)){
	$row = mysql_fetch_assoc($sql);
	if(isset($row['order'])) $order = ($row['order'] + 1);
	error_log("dfdsfds ".$row['order']);
}


$query = "INSERT INTO queue (id, songid, singer, timestamp, `order`, played) VALUES ('".uniqid("")."', '".$songid."', '".$singer."', UNIX_TIMESTAMP(), ".$order.", 0);";

error_log($query);

mysql_query($query);

//forward to overview page
header("Location: /karaoke/");



?>
