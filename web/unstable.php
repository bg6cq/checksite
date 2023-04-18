<!doctype html>
<html lang="zh">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=1400, initial-scale=1, shrink-to-fit=yes">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">

    <title>网站测试不稳定排行</title>
  </head>
  <body>
    <div class="container">

<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h2 class="display-6">网站测试不稳定排行</h1>
    <p class="lead">7天内网站测试中状态变化的次数，越多说明网站功能越不稳定</p>
    <p class="lead">单击网站内容可以查看测试状态变化历史</p>
  </div>
</div>

<div class="table-responsive">
<table border=1 width=500 cellspacing=0 id="myTable1" class="table text-nowrap">
<thead><th>网站</th><th>状态变动次数</th></tr></thead><tbody>
<?php

include "db.php";

@$g = $_REQUEST["g"];
if ($g != 0) {
    $q = "select status_log.hostname, count(*) c from `group` left join group_site on `group`.id = group_site.groupid left join status_log on group_site.hostname = status_log.hostname where group.id = ? and (status_log.hostname <>\"\" and status_log.tm > date_sub(now(), interval 7 day)) group by status_log.hostname order by c desc limit 50";
    $stmt = $mysqli->prepare($q);
    $stmt->bind_param("i", $g);
} else {
    $q = "select status_log.hostname, count(*) c from status_log where tm>date_sub(now(), interval 7 day) group by hostname order by c desc limit 50";
    $stmt = $mysqli->prepare($q);
}
$stmt->execute();
$stmt->bind_result($hostname, $cnt);
$stmt->store_result();
while ($stmt->fetch()) {
    echo "<tr>";
    echo "<td><a href=log.php?h=$hostname>$hostname</a></td>";
    echo "<td align=center>$cnt</td>";
    echo "</tr>\n";
}
$stmt->close();

?>
</tbody></table>
</div>

</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.6.4.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src='js/jquery.dataTables.min.js'></script>

<script>
$(document).ready(function () {

    var t = $('#myTable1').DataTable({
        paging: false,
	 "order": [[ 1, 'desc' ]]

    });

});</script>

  </body>
</html>


