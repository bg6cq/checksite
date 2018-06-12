<?php

$db_host = "localhost";
$db_user = "root";
$db_passwd = "";
$db_dbname = "checksite";

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if (mysqli_connect_error()) {
	echo mysqli_connect_error();
}

?>
