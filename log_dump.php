<!doctype html>
<html lang="zh">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">

    <title>各网站变动历史日志</title>
  </head>
  <body>
    <div class="container">


<div class="card">
  <div class="card-body">
    <p class="card-text">
<table border=1 cellspacing=0 id="myTable1" class="display">
<thead><th>时间</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>v4 HTTPS</th><th>v6 HTTPS</th><th>HTTP/2</th></tr></thead><tbody>
<?php

$db_host = "localhost";
$db_user = "root";
$db_passwd = "";
$db_dbname = "checksite";

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
	echo mysqli_connect_error();
}

function output_f($v)
{
	if($v) 
		echo "<td align=center><img src=ok.png></td>";
	else echo "<td>&nbsp;</td>";
}
$q="select hostname, tm, aaaa, ipv6, httpsv4, httpsv6, http2 from status_log order by tm desc limit 100";
$stmt=$mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($hostname, $tm, $aaaa, $ipv6, $httpsv4, $httpsv6, $http2);
$stmt->store_result();
while($stmt->fetch()) {	
	echo "<tr><td>".$tm."</td>";
	echo "<td>".$hostname."</td>";
	output_f($aaaa);
	output_f($ipv6);
	output_f($httpsv4);
	output_f($httpsv6);
	output_f($http2);
	echo "</tr>\n";
}
$stmt->close();

?>
</tbody></table>
    </p>
  </div>
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


