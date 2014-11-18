<?php
/*
Import karaoke songlist from exported txt file
Ferdinand Malcher 2014
*/

require("../../common/config.php");

//truncate database
mysql_query("TRUNCATE songlist;");


//read file to array
$file = file("http://development.ferdinand-malcher.de/songliste.txt");

foreach($file AS $line){
	//make array from line
	$row = explode("|", $line);
	
	//trim and escape all values
	array_walk($row, create_function('&$val', '$val = trim($val, "\x00..\x1F\x80..\xFF");'));
	array_walk($row, create_function('&$val', '$val = mysql_escape_string($val);'));
	$row[0] = intval($row[0]);
	
	//skip if no ID is given
	if(!$row[0]){
		continue;
	}
	
	//insert row into DB
	$query = "INSERT INTO songlist(id, interpret, title, language) VALUES ('".$row[0]."', '".$row[1]."', '".$row[2]."', '".$row[3]."');";
	mysql_query($query);
	
	
}

?>