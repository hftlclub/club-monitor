<?php
/*
Fast and dirty script to give imported karaoke song data an ID
Ferdinand Malcher 2014
*/


define("DATABASE_HOST",       "localhost");
define("DATABASE_USER",       "user");
define("DATABASE_PASSWORD",   "pass");
define("DATABASE_NAME",       "database");

$dbc = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD);
mysql_select_db(DATABASE_NAME, $dbc); 



$data = array();

$sql = mysql_query("SELECT * FROM songlist;");
while($row = mysql_fetch_assoc($sql)){
	$newid = substr(md5(uniqid(rand(0,time()))),0,13);
	$data[$newid] = array(
		"id" => $newid,
		"interpret" => mytrim($row['interpret']),
		"title" => mytrim($row['title']),
		"language" => mytrim($row['language'])
	);
}


mysql_query("TRUNCATE songlist;");


foreach($data AS $song){
	$sql = "INSERT INTO songlist (id, interpret, title, language) VALUES('".$song['id']."', '".$song['interpret']."', '".$song['title']."', '".$song['language']."');";
	mysql_query($sql);
}



function mytrim($string){
	return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);
}



?>
