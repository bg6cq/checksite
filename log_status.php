<?php

$db_host = "localhost";
$db_user = "root";
$db_passwd = "";
$db_dbname = "checksite";

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
	echo mysqli_connect_error();
}

if ($_SERVER['argc']!=7)  {
	echo  "log_status.php hostname aaaa ipv6 httpsv4 httpsv6 http2\n";
	exit(0);
}

function update_last($hostname,$aaaa,$ipv6,$httpsv4,$httpsv6,$http2)
{
	global $mysqli;
	echo "update_last\n";
	$q="replace into status_last values(?, now(), ?,?,?,?,?)";
	$stmt=$mysqli->prepare($q);
	$stmt->bind_param("siiiii",$hostname,$aaaa,$ipv6,$httpsv4,$httpsv6,$http2);
	$stmt->execute();
	$stmt->close();
}

function insert_log($hostname,$aaaa,$ipv6,$httpsv4,$httpsv6,$http2)
{
	global $mysqli;
	echo "insert_log\n";
	$q="insert into status_log values(?, now(), ?,?,?,?,?)";
	$stmt=$mysqli->prepare($q);
	$stmt->bind_param("siiiii",$hostname,$aaaa,$ipv6,$httpsv4,$httpsv6,$http2);
	$stmt->execute();
	$stmt->close();
}

function update_allok($hostname)
{
	global $mysqli;
	echo "update_allok\n";
	$q="select count(*) from allok_first where hostname=?";
	$stmt=$mysqli->prepare($q);
	$stmt->bind_param("s",$hostname);
	$stmt->execute();
	$stmt->bind_result($cnt);
	$stmt->store_result();
	$stmt->fetch();
	$stmt->close();
	if($cnt==1) 
		return;

	$q="insert into allok_first values(?, now())";
	$stmt=$mysqli->prepare($q);
	$stmt->bind_param("s",$hostname);
	$stmt->execute();
	$stmt->close();
}
	
$hostname=$_SERVER['argv'][1];
$aaaa=$_SERVER['argv'][2];
$ipv6=$_SERVER['argv'][3];
$httpsv4=$_SERVER['argv'][4];
$httpsv6=$_SERVER['argv'][5];
$http2=$_SERVER['argv'][6];

if($aaaa+$ipv6+$httpsv4+$httpsv6+$http2==5)
	update_allok($hostname);

// 检查status_last 是否有记录
$q="select aaaa, ipv6, httpsv4, httpsv6, http2 from status_last where hostname=?";
$stmt=$mysqli->prepare($q);
$stmt->bind_param("s",$hostname);
$stmt->execute();
$stmt->bind_result($oldaaaa, $oldipv6, $oldhttpsv4, $oldhttpsv6, $oldhttp2);
$stmt->store_result();
if(!$stmt->fetch()) {	// 第一次记录
	$stmt->close();

	update_last($hostname,$aaaa,$ipv6,$httpsv4,$httpsv6,$http2);

	insert_log($hostname,$aaaa,$ipv6,$httpsv4,$httpsv6,$http2);

	exit(0);
}
$stmt->close();

// 之前有过记录
update_last($hostname,$aaaa,$ipv6,$httpsv4,$httpsv6,$http2);

if( ($aaaa!=$oldaaaa) || ($ipv6!=$oldipv6) || ($httpsv4!=$oldhttpsv4) || ($httpsv6!=$oldhttpsv6) || ($http2!=$oldhttp2) ) 
	insert_log($hostname,$aaaa,$ipv6,$httpsv4,$httpsv6,$http2);

?>
