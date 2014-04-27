<?php
require_once("../common/config.php");


//check for post method
if($_SERVER['REQUEST_METHOD'] != "POST"){
	doerror();
}

//read from input stream
$data = json_decode(file_get_contents("php://input"), true);

//DEBUG
//error_log(print_r($data, true));


//check if name given
if(!$data['name']){
	//error_log("name invalid: ".$data['name']);
	doerror();
}



$data['name'] = mysql_real_escape_string($data['name']);
$insertsql = array();


//go through ordered pizzas
foreach($data['pizzas'] AS $pizza){
	
	//MySQL escapes
	$pizza['id']      = mysql_real_escape_string($pizza['id']);
	$pizza['comment'] = mysql_real_escape_string($pizza['comment']);
	
	
	//check if pizza ID is valid
	$sql = mysql_query("SELECT id FROM pizza_pizzas WHERE id = '".$pizza['id']."';");
	if(mysql_num_rows($sql) != 1){
		//error_log("ID ".$pizza['id']." invalid");
		doerror();
	}
	
	//if valid, generate SQL command for insert - and stack it for later execution
	$insertsql[] = "INSERT INTO pizza_orders	(id, timestamp, name, pizza, comment)
		VALUES(
			'".myuniqid()."',
			UNIX_TIMESTAMP(),
			'".$data['name']."',
			'".$pizza['id']."',
			'".$pizza['comment']."'
		);";
}


//execute all the insert queries
foreach($insertsql AS $query){
	if(!mysql_query($query)){
		doerror();
	}	
}



//SUCCESS!
header("HTTP/1.1 200 OK");
echo "{ \"message\" : \"Vielen Dank fŸr Deine Bestellung, ".$data['name']."!\n Bitte bezahle ".count($pizzas) * PIZZA_PRICE." EUR an der Bar.\" }";
exit();


//////////////////


function doerror(){
	header("HTTP/1.1 400 Bad Request");
	exit();
}



?>