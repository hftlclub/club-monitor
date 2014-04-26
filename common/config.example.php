<?php
### RENAME THIS FILE TO config.php ###


//MySQL settings
define("DATABASE_HOST",       "srv2.malcher-server.de");
define("DATABASE_USER",       "club");
define("DATABASE_PASSWORD",   "");
define("DATABASE_NAME",       "club");






//connect to MySQLServer
$dbc = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD);
mysql_select_db(DATABASE_NAME, $dbc);

?>

