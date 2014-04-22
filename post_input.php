<?php
if($_SERVER['REQUEST_METHOD'] != "POST"){
	die("Please request with POST method.");
}

//get post data
$songid = mysql_real_escape_string($_POST['songid']);
$singer = mysql_real_escape_string($_POST['singer']);

$error = 0;

//check whether id exists
$sql = mysql_query("SELECT * FROM songlist WHERE id = '".$songid."';");
if(mysql_num_rows($sql) != 1){
	$error++;
}

//check if singer given
if(empty($singer)){
	$error++;
}


if($error){
	//DO ERROR STUFF
	echo "Please give valid information."
	
	exit();
}

////////////////////////////


$sql = "INSERT INTO queue (id, songid, singer, timestamp) VALUES ('".uniqid("")."', '".$songid."', '".$singer."', UNIX_TIMESTAMP());";


//forward to overview page
//header("Location: blabla.php");



?>