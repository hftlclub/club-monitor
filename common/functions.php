<?php
//////////////////////////////////////////////////
// functions.php
//
// common functions for the club monitor
//////////////////////////////////////////////////


function myuniqid(){
	return substr(md5(uniqid(rand(0,time()),1)),0,13);
}

function checkAuthenticationToken($token)
{
	$query = mysql_query("SELECT `id`, `token`,UNIX_TIMESTAMP(`lastused`) as lastused FROM `authentication_tokens` WHERE `token`='".$token."' AND DATE_SUB(CURDATE(),INTERVAL 1 DAY) <= `lastused`;");
	$rows = mysql_num_rows($query);
	
	if($rows == 1)
	{
    //authenticated, yeah!
    $row = mysql_fetch_assoc ($query);
    
    mysql_query("UPDATE `authentication_tokens` SET `lastused` = CURRENT_TIMESTAMP() WHERE `id`='".$row['id']."';");
    
    return true;
  }
  else
  {
    // authentication failed!
    return false;
  }
}

?>