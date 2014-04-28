<?php
require_once("../../common/config.php");


//check for post method
if($_SERVER['REQUEST_METHOD'] != "POST"){
	doerror();
}

$data = json_decode(file_get_contents("php://input"), true);

//error_log(print_r($data, true)); exit();


$data['mode'] = mysql_real_escape_string($data['mode']);
$data['id']   = mysql_real_escape_string($data['id']);

//exit if no mode given
if(!$data['mode']){
	doerror();
}


if(($data['mode'] == "pay" OR $data['mode'] == "unpay") AND $data['id']){
	//check whether order ID exists
	$query = "SELECT id FROM pizza_orders WHERE id = '".$data['id']."';";
	$sql = mysql_query($query);
	
	//error if ID is not valid
	if(mysql_num_rows($sql) != 1){
		doerror();
	}
	
	//pay or unpay?
	$newpaid = ($data['mode'] == "pay") ? 1 : 0;
		
	$query = "UPDATE pizza_orders SET paid = ".$newpaid." WHERE id = '".$data['id']."' LIMIT 1;";
	if(!mysql_query($query)){
		doerror();
	}
}



if($data['mode'] == "delOrder" AND $data['id']){
	//check whether order ID exists
	$query = "SELECT id FROM pizza_orders WHERE id = '".$data['id']."';";
	$sql = mysql_query($query);
	
	//error if ID is not valid
	if(mysql_num_rows($sql) != 1){
		doerror();
	}
	
	
	$query = "DELETE FROM pizza_orders WHERE id = '".$data['id']."' LIMIT 1;";
	if(!mysql_query($query)){
		doerror();
	}
}


//////////////////


function doerror(){
	header("HTTP/1.1 400 Bad Request");
	exit();
}
?>