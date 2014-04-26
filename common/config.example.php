<?php
### COPY THIS FILE TO config.php ###


//MySQL settings
define("DATABASE_HOST",       "srv2.malcher-server.de");
define("DATABASE_USER",       "club");
define("DATABASE_PASSWORD",   "");
define("DATABASE_NAME",       "club");






//connect to MySQLServer
$dbc = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD);
mysql_set_charset("utf8", $dbc);
mysql_select_db(DATABASE_NAME, $dbc);




//functions - should be moved to separate file
function myuniqid(){
	return substr(md5(uniqid(rand(0,time()),1)),0,13);
}


?>