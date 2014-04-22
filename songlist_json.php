<?php
require("mysql_connect.php");
header("Content-type: application/json");
?>
{
"songlist" : [
<?php
$sql = mysql_query("SELECT id, interpret, title FROM songlist ORDER BY interpret, title ASC;");
while($row = mysql_fetch_assoc($sql)){
	echo "{ \"id\" : \"".$row['id']."\", \"song\" : \"".$row['interpret']." - ".$row['title']."\" }\n";	
}
?>

]
}