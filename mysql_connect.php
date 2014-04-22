<?php
define("DATABASE_HOST",       "localhost");
define("DATABASE_USER",       "tillisql1");
define("DATABASE_PASSWORD",   "");
define("DATABASE_NAME",       "tillisql1");

$dbc = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD);
mysql_select_db(DATABASE_NAME, $dbc); 

?>
