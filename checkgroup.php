<?php

// php checkgroup.php groupid

include ("db.php");

$groupid = 0;
if ($_SERVER['argc'] > 1)
	$groupid = intval($_SERVER['argv'][1]);

echo "groupid $groupid\n";

if ($groupid == 0) {
    $q = "select group_site.hostname, `group`.timeout from `group` left join group_site on group.id = group_site.groupid order by group_site.groupid, group_site.cnt";
    $stmt = $mysqli->prepare($q);
} else {
   $q = "select group_site.hostname, `group`.timeout from `group` left join group_site on group.id = group_site.groupid where group.id = ? order by group_site.groupid, group_site.cnt";
   $stmt = $mysqli->prepare($q);
   $stmt->bind_param("i",$groupid);
}

$stmt->execute();
$stmt->bind_result($hostname, $timeout);
$stmt->store_result();
while ($stmt->fetch()) {
    system("php checksite.php $hostname $timeout");
}
$stmt->close();

?>
