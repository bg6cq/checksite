<!doctype html>
<html lang="zh">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=1400, initial-scale=1, shrink-to-fit=yes">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">

    <title>网站在线测试次数排行</title>
  </head>
  <body>
    <div class="container">

<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h2 class="display-6">网站在线测试次数排行</h1>
    <p class="lead">7天内网站在线测试次数</p>
    <p class="lead">单击网站可以查看测试状态变化历史</p>
  </div>
</div>

<div class="table-responsive">
<table border=1 width=500 cellspacing=0 id="myTable1" class="table text-nowrap">
<thead><th>网站</th><th>测试次数</th></tr></thead><tbody>
<?php

include "db.php";

$q = "select hostname, count(*) c from onlinecheck_log where tm > date_sub(now(), interval 7 day) group by hostname order by c desc limit 50";
$stmt = $mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($hostname, $cnt);
$stmt->store_result();
while ($stmt->fetch()) {
    echo "<tr>";
    echo "<td><a href=onlinechecklog.php?h=$hostname>$hostname</a></td>";
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


