<?php

// php checkgroup.php groupid

include ("db.php");

$groupid=0;
if($_SERVER['argc']>1)
	$groupid=intval($_SERVER['argv'][1]);

echo "groupid ".$groupid."\n";

if($groupid==0) {
	$q="select group_site.hostname, `group`.timeout from `group` left join group_site on group.id = group_site.groupid";
} else {
	$q="select group_site.hostname, `group`.timeout from `group` left join group_site on group.id = group_site.groupid where group.id=".$groupid;
}
$stmt=$mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($hostname,$timeout);
$stmt->store_result();
while($stmt->fetch()) {
//	echo $hostname." ".$timeout."\n";
	system("php checksite.php ".$hostname." ".$timeout);
}
$stmt->close();
	
?>
