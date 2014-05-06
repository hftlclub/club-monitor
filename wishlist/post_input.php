<?php

require("../common/config.php");

//check for post method
if($_SERVER['REQUEST_METHOD'] != "POST"){
	die("Please request with POST method.");
}

//get post data
$songname = mysql_real_escape_string($_POST['inputSongname']);
$artist = mysql_real_escape_string($_POST['inputInterpret']);
$album = mysql_real_escape_string($_POST['inputAlbum']);
$href = mysql_real_escape_string($_POST['inputHref']);


$error = 0;



//do something if error occured
if($error){
	header("Location: index.php");
	//echo "Please give valid information.";
	
	exit();
}

////////////////////////////


$sql = "INSERT INTO wishlist (id, song, interpret, album, href) VALUES ('".uniqid("")."', '".$songname."', '".$artist."', '".$album."', '".$href."');";
mysql_query($sql);

//forward to overview page
header("Location: index.php");

?>