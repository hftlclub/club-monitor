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
define("UPLOADED_RESSOURCES_URLPREFIX", "");
//define("UPLOADED_RESSOURCES_URLPREFIX", "https://screen.hftl.club");

//External Auth Endpoint
define("EXTAUTHURL", "http://localhost:3000/api/login/external/club");


//connect to MySQLServer
$dbc = @mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD);
if(!$dbc) {
  die(json_encode(array("errorType" => "fatal", "errorMessage"=>"MySQL connection error")));
}
mysql_set_charset("utf8", $dbc);
mysql_select_db(DATABASE_NAME, $dbc);
