<?php
### COPY THIS FILE TO config.php ###


//MySQL settings
define("DATABASE_HOST",       "srv3.malcher-server.de");
define("DATABASE_USER",       "club");
define("DATABASE_PASSWORD",   "");
define("DATABASE_NAME",       "club");


//Pizza
define("PIZZA_PRICE", 5);

//Infoscreen
define("TICKER_MAXVIEWS", 26);


//connect to MySQLServer
$dbc = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD);
mysql_set_charset("utf8", $dbc);
mysql_select_db(DATABASE_NAME, $dbc);


?>
