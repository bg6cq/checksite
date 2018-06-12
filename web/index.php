<!doctype html>
<html lang="zh">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">

<?php
    // for ipv6.ustc.edu.cn
    // if request http://ipv6.ustc.edu.cn, redirect to https://ipv6.ustc.edu.cn
    //
    if ($_SERVER['SERVER_NAME'] == "ipv6.ustc.edu.cn")
        if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'http') {
            echo "<meta http-equiv=\"refresh\" content=\"0; URL=https://ipv6.ustc.edu.cn".$_SERVER['REQUEST_URI']."\">";
            echo "we support https, please vist <a href=https://ipv6.ustc.edu.cn>https://ipv6.ustc.edu.cn</a>";
            exit(0);
        }
?>
    <meta name="viewport" content="width=1024, initial-scale=1, shrink-to-fit=yes">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css">
    <title>高校网站HTTP、HTTPS、HTTP/2支持情况</title>
  </head>
  <body>
    <div class="container">

<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h2 class="display-6">高校网站HTTP、HTTPS、HTTP/2支持情况</h1>
    <p class="lead">[ <a href=about.html target=_blank>相关说明</a> | <a href=log.php target=_blank>测试历史</a> | <a href=radar.php target=_blank>分组对比</a> ]</p>
    <p class="lead">
<?php

include("db.php");

function get_groupavg($id)
{
    global $mysqli;
    if($id == 0) {
        $q = "select avg(status_last.ipv4 * 4 + status_last.httpsv4 + status_last.http2v4 + status_last.aaaa + status_last.ipv6 + status_last.httpsv6 + status_last.http2v6) * 10 from status_last";
        $stmt = $mysqli->prepare($q);
    } else {
        $q = "select avg(status_last.ipv4 * 4 + status_last.httpsv4 + status_last.http2v4 + status_last.aaaa + status_last.ipv6 + status_last.httpsv6 + status_last.http2v6) * 10 from `group` left join group_site on group.id = group_site.groupid left join status_last on group_site.hostname = status_last.hostname where group.id = ?";
        $stmt = $mysqli->prepare($q);
        $stmt->bind_param("i", $id);
    }
    $stmt->execute();
    $stmt->bind_result($avg);
    $stmt->store_result();
    $stmt->fetch();
    $stmt->close();
    return sprintf("%.1f", $avg);
}

@$groupid = $_REQUEST["groupid"];
if ($groupid == "")
    $groupid = 0;
$my_name = "所有高校";

$q = "select id, name from `group` order by id";
$stmt = $mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($id, $name);
$stmt->store_result();
echo "[ <a href=index.php>所有高校(";
echo get_groupavg(0);
echo ")</a> ";
while ($stmt->fetch()) {
    if ($groupid == $id)
        $my_name = $name;
    echo "| ";
    echo "<a href=index.php?groupid=$id>$name(";
    echo get_groupavg($id);
    echo ")</a> ";
}
echo " ]";
$stmt->close();
?>
</p>
  </div>
</div>

<div class="alert alert-info" role="alert">
  测试时间：<span id="endDatetime"></span>
</div>
<div class="card">
  <h5 class="card-header"><?php echo $my_name; echo " <a href=log.php?g=$groupid>测试历史</a>"; echo " <a href=unstable.php?g=$groupid>不稳定排行</a>"; ?></h5>  <div class="card-body">
    <h5 class="card-title"></h5>
    <p class="card-text">
      <table border=1 cellspacing=0 id="myTable" class="display">
<thead><th></th><th>高校</th><th>网站</th><th>v4 HTTP</th><th>v4 HTTPS</th><th>v4 HTTP2</th><th>v6解析</th><th>v6 HTTP</th><th>v6 HTTPS</th><th>v6 HTTP2</th><th>评分</th></tr></thead><tbody>
      </tbody></table>
    </p>
  </div>
</div>

</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src='js/jquery.dataTables.min.js'></script>
    <script src='//cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js'></script>

<script>
$(document).ready(function () {
    $.getJSON("result.json.php<?php echo "?groupid=".$groupid;?>", {random: Math.random()}, function(data) {
        $('#beginDatetime').text(data.beginDatetime);
        $('#endDatetime').text(data.endDatetime);

        var resultData = [];
        $.each(data['myTable'], function(key, val) {
            resultData.push([
                val.cnt,
                "<a href=log.php?h=" + val.hostname + ">" + val.name + "</a>",
                "<a href=http://" + val.hostname + " target=_blank>" + val.hostname + "</a>",
                val.ipv4? "<img src=ok.png>": "",
                val.httpsv4? "<img src=ok.png>": "",
                val.http2v4? "<img src=ok.png>": "",
                val.aaaa? "<img src=ok.png>": "",
                val.ipv6? "<img src=ok.png>": "",
                val.httpsv6? "<img src=ok.png>": "",
                val.http2v6? "<img src=ok.png>": "",
                val.score
            ])
        });

        var t = $('#myTable').DataTable({
            paging: false,
	    fixedHeader: true,
            "order": [[ 10, 'desc' ]],
            data: resultData
        });
    });

});
</script>

  </body>
</html>
