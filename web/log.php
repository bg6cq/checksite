<!doctype html>
<html lang="zh">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=1200, initial-scale=1, shrink-to-fit=yes">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">

    <title>网站测试状态变动历史</title>
  </head>
  <body>
    <div class="container">

<div class="table-responsive">
<table border=1 cellspacing=0 id="myTable1">
<thead><th>时间</th><th>网站</th><th>v4H</th><th>v4S</th><th>v4H2</th><th>v6解析</th><th>v6H</th><th>v6S</th><th>v6H2</th><th>评分</th></tr></thead><tbody>
<?php

include "db.php";

function output_f($v)
{
    if ($v)
        echo "<td align=center><img src=ok.png></td>";
    else echo "<td>&nbsp;</td>";
}

@$g = $_REQUEST["g"];
@$h = $_REQUEST["h"];
if ($g != 0) {
    $q = "select status_log.hostname, tm, ipv4, aaaa, ipv6, httpsv4, httpsv6, http2v4, http2v6 from `group` left join group_site on `group`.id = group_site.groupid left join status_log on group_site.hostname = status_log.hostname where group.id = ? order by tm desc limit 100";
    $stmt = $mysqli->prepare($q);
    $stmt->bind_param("s", $g);
} else if ($h != "") {
    $q = "select hostname, tm, ipv4, aaaa, ipv6, httpsv4, httpsv6, http2v4, http2v6 from status_log where hostname = ? order by tm desc limit 100";
    $stmt = $mysqli->prepare($q);
    $stmt->bind_param("s", $h);
} else {
    $q = "select hostname, tm, ipv4, aaaa, ipv6, httpsv4, httpsv6, http2v4, http2v6 from status_log order by tm desc limit 100";
    $stmt = $mysqli->prepare($q);
}
$stmt->execute();
$stmt->bind_result($hostname, $tm, $ipv4, $aaaa, $ipv6, $httpsv4, $httpsv6, $http2v4, $http2v6);
$stmt->store_result();
while ($stmt->fetch()) {
    echo "<tr><td>$tm</td>";
    echo "<td><a href=log.php?h=$hostname>$hostname</a></td>";
    output_f($ipv4);
    output_f($httpsv4);
    output_f($http2v4);
    output_f($aaaa);
    output_f($ipv6);
    output_f($httpsv6);
    output_f($http2v6);
    echo "<td align=center>";
    echo ($ipv4 * 4 + $aaaa + $ipv6 + $httpsv4 + $httpsv6 + $http2v4 + $http2v6) * 10;
    echo "</td>";
    echo "</tr>\n";
}
$stmt->close();

?>
</tbody></table>
</div>

</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src='js/jquery.dataTables.min.js'></script>

<script>
$(document).ready(function () {

    var t = $('#myTable1').DataTable({
        paging: false,
	 "order": [[ 0, 'desc' ]]

    });

});</script>

  </body>
</html>


