<?php
require_once("../common/config.php");


//check for post method
if($_SERVER['REQUEST_METHOD'] != "POST"){
	doerror();
}

$data = json_decode(file_get_contents("php://input"), true);

//DEBUG
print_r($data); exit;


//check if name given
if(!$data['name']){
	doerror();
}

//go through ordered pizzas
foreach($data['pizzas'] AS $pizza){
	//MySQL escapes
	$pizza['id']      = mysql_real_escape_string($pizza['id']);
	$pizza['comment'] = mysql_real_escape_string($pizza['comment']);
	$data['name']     = mysql_real_escape_string($data['name']);
	
	//check if pizza ID is valid
	$sql = mysql_query("SELECT id FROM pizza_pizzas WHERE id = '".$pizza['id']."';");
	if(mysql_num_rows($sql) != 1){
		doerror();
	}
	
	//if valid, generate SQL command for insert - and stack it for later execution
	$insertsql .= "INSERT INTO pizza_orders	(id, timestamp, name, pizza, comment)
		VALUES(
			'".myuniqid()."',
			UNIX_TIMESTAMP(),
			'".$data['name']."',
			'".$pizza['id']."',
			'".$pizza['comment']."'
		);\n";
	
}


//execute all the insert queries
if(!mysql_query($insertsql)){
	doerror();
}


//SUCCESS!
header("HTTP/1.1 200 OK");
exit();


//////////////////


function doerror(){
	header("HTTP/1.1 400 Bad Request");
	exit();
}



?>