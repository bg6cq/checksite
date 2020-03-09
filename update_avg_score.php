<?php

include ("db.php");

$q = "insert into group_avg_score select 0, now(), avg(status_last.dnssec + status_last.ipv4 * 3 + status_last.httpsv4 + status_last.http2v4 + status_last.aaaa + status_last.ipv6 + status_last.httpsv6 + status_last.http2v6) * 10 from `group` left join group_site on group.id = group_site.groupid left join status_last on group_site.hostname = status_last.hostname";
$stmt = $mysqli->prepare($q);
$stmt->execute();
$stmt->close();

$q = "insert into group_avg_score select group.id, now(), avg(status_last.dnssec + status_last.ipv4 * 3 + status_last.httpsv4 + status_last.http2v4 + status_last.aaaa + status_last.ipv6 + status_last.httpsv6 + status_last.http2v6) * 10 from `group` left join group_site on group.id = group_site.groupid left join status_last on group_site.hostname = status_last.hostname group by group.id";
$stmt = $mysqli->prepare($q);
$stmt->execute();
$stmt->close();

?>
