<?php
header('Content-Type: application/json');

include "db.php";

echo "{";

function get_addon($hostname)
{
	global $mysqli;
	$q="SELECT COUNT(*)+1 as rank FROM (SELECT tm FROM allok_first ORDER BY tm) AS sc WHERE tm > (SELECT tm FROM allok_first WHERE hostname= ? )";
	$stmt=$mysqli->prepare($q);
	$stmt->bind_param("s",$hostname);
	$stmt->execute();
	$stmt->bind_result($addon);
	$stmt->store_result();
	$stmt->fetch();
	$stmt->close();
	return $addon;
}

$q="select tm from status_last order by tm desc limit 1";
$stmt=$mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($lasttm);
$stmt->store_result();
$stmt->fetch();
$stmt->close();

echo "\"endDatetime\": \"".$lasttm."\",\n";

$groupid=$_REQUEST["groupid"];
if($groupid=="")
	$groupid=2;

echo "\"myTable\": [\n";
$q="select group_site.cnt, site.hostname, site.name, status_last.ipv4, status_last.httpsv4, status_last.http2v4, status_last.aaaa, status_last.ipv6, status_last.httpsv6, status_last.http2v6 from `site` left join group_site on group_site.hostname = site.hostname left join status_last on site.hostname=status_last.hostname where group_site.groupid= ? ";
$stmt=$mysqli->prepare($q);
$stmt->bind_param("i",$groupid);
$stmt->execute();
$stmt->bind_result($cnt, $hostname, $name, $ipv4, $httpsv4, $http2v4, $aaaa, $ipv6, $httpsv6, $http2v6);
$stmt->store_result();
$isfirst=1;
while($stmt->fetch()) {
	if($isfirst==1)  {
		$isfirst=0;
	} else
		echo ",\n";
	echo "{ \"cnt\": ".$cnt.", ";
	echo "\"hostname\": \"".$hostname."\", ";
	echo "\"name\": \"".$name."\", ";
	echo "\"ipv4\": "; echo intval($ipv4); echo ",";
	echo "\"httpsv4\": "; echo intval($httpsv4); echo ",";
	echo "\"http2v4\": "; echo intval($http2v4); echo ",";
	echo "\"aaaa\": "; echo intval($aaaa); echo ",";
	echo "\"ipv6\": "; echo intval($ipv6); echo ",";
	echo "\"httpsv6\": "; echo intval($httpsv6); echo ",";
	echo "\"http2v6\": "; echo intval($http2v6); echo ",";
       	echo "\"score\": "; 
	$score = ($ipv4*4+$httpsv4+$http2v4+$aaaa+$ipv6+$httpsv6+$http2v6)*10;
	if($score==100) $score+=get_addon($hostname);
	echo $score;
      	echo "}";
}
echo "\n";
$stmt->close();
echo "]\n";

echo "\n";

echo "}\n";
?>
