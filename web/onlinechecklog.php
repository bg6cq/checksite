<!doctype html>
<html lang="zh">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=1200, initial-scale=1, shrink-to-fit=yes">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">

    <title>在线测试结果</title>
  </head>
  <body>
    <div class="container">

   <p class="lead"><form method=get action=onlinecheck.php>请输入主机名：<input name=hostname value="www.ustc.edu.cn"><input type=submit name=cmd value="开始测试" ></form></p>
<div class="table-responsive">
<table border=1 cellspacing=0 id="myTable1">
<thead><th>时间</th><th>网站</th><th>DNSSEC</th><th>v4H</th><th>v4S</th><th>v4H2</th><th>v6解析</th><th>v6H</th><th>v6S</th><th>v6H2</th><th>评分</th></tr></thead><tbody>
<?php

include "db.php";

function output_f($v)
{
    if ($v)
        echo "<td align=center><img src=ok.png></td>";
    else echo "<td>&nbsp;</td>";
}

$hostname=@$_REQUEST["h"];
if($hostname!="") {
	$q = "select hostname, tm, dnssec, ipv4, aaaa, ipv6, httpsv4, httpsv6, http2v4, http2v6 from onlinecheck_log where hostname=? order by tm desc limit 100";
	$stmt = $mysqli->prepare($q);
	$stmt->bind_param("s",$hostname);
} else {
	$q = "select hostname, tm, dnssec, ipv4, aaaa, ipv6, httpsv4, httpsv6, http2v4, http2v6 from onlinecheck_log order by tm desc limit 100";
	$stmt = $mysqli->prepare($q);
}
$stmt->execute();
$stmt->bind_result($hostname, $tm, $dnssec, $ipv4, $aaaa, $ipv6, $httpsv4, $httpsv6, $http2v4, $http2v6);
$stmt->store_result();
while ($stmt->fetch()) {
    echo "<tr><td>$tm</td>";
    echo "<td><a href=onlinechecklog.php?h=$hostname>$hostname</a></td>";
    output_f($dnssec);
    output_f($ipv4);
    output_f($httpsv4);
    output_f($http2v4);
    output_f($aaaa);
    output_f($ipv6);
    output_f($httpsv6);
    output_f($http2v6);
    echo "<td align=center>";
    echo ($dnssec + $ipv4 * 3 + $aaaa + $ipv6 + $httpsv4 + $httpsv6 + $http2v4 + $http2v6) * 10;
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
    <script src="js/jquery-3.7.0.slim.min.js"></script>
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


