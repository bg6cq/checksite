<?php

$db_host = "localhost";
$db_user = "root";
$db_passwd = "";
$db_dbname = "checksite";

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
	echo mysqli_connect_error();
}

function output_f($v)
{
	if($v) 
		echo "<td align=center><img src=ok.png></td>";
	else echo "<td>&nbsp;</td>";
}
$q="select hostname from allok_first order by tm desc";
$stmt=$mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($hostname);
$stmt->store_result();
$cnt=0;
while($stmt->fetch()) {	
	$cnt++;
	echo $hostname;
	echo " ".$cnt."\n";
	file_put_contents ("addon/".$hostname,$cnt);
}
$stmt->close();

?>
