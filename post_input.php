<?php

require("mysql_connect.php");

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
	header("Location: index.php");
	//echo "Please give valid information.";
	
	exit();
}

////////////////////////////


$sql = "INSERT INTO queue (id, songid, singer, timestamp, played) VALUES ('".uniqid("")."', '".$songid."', '".$singer."', UNIX_TIMESTAMP(), 0);";
mysql_query($sql);

//forward to overview page
header("Location: index.php");



?>