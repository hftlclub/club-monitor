<?php
define("DATABASE_HOST",       "localhost");
define("DATABASE_USER",       "user");
define("DATABASE_PASSWORD",   "pass");
define("DATABASE_NAME",       "db");

$dbc = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD);
mysql_select_db(DATABASE_NAME, $dbc); 

?>
