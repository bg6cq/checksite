<!DOCTYPE html>
<html style="height: 100%">
   <head>
       <meta charset="utf-8">
   </head>
   <body style="height: 100%; margin: 0">
       <div id="container" style="height: 100%"></div>
       <script type="text/javascript" src="//echartsjs.com/gallery/vendors/echarts/echarts.min.js"></script>
       <script type="text/javascript">
var dom = document.getElementById("container");
var myChart = echarts.init(dom);
var app = {};
option = null;

option = {
    title: {
        text: '各组得分变化情况'
    },
    tooltip: {
        trigger: 'axis'
    },
    legend: {
//     data:['邮件营销','联盟广告','视频广告','直接访问','搜索引擎']
        data:[<?php

include("db.php");

$q = "select name from `group` order by id";
$stmt = $mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($name);
$stmt->store_result();
$isfirst = 1;
while ($stmt->fetch()) {
    if ($isfirst)
        $isfirst = 0;
    else
        echo ",";
    echo "'$name'";
}
$stmt->close();
?>]
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    toolbox: {
        feature: {
            saveAsImage: {}
        }
    },
    xAxis: {
        type: 'category',
        boundaryGap: false,
//        data: ['周一','周二','周三','周四','周五','周六','周日']
          data: [<?php
$isfirst = 1;
for ($d = -30; $d < 1; $d++) {
    if ($isfirst)
        $isfirst = 0;
    else
        echo ",";
    echo date("d",strtotime($d."days"));
}
?>]
    },
    yAxis: {
        type: 'value'
    },
    series: [
//        {
//            name:'邮件营销',
//            type:'line',
//            data:[120, 132, 101, 134, 90, 230, 210]
//        },
<?php

$q = "select group.id, group.name from `group` order by id";
$stmt = $mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($id,$name);
$stmt->store_result();
$isfirst = 1;
while ($stmt->fetch()) {
    if ($isfirst)
        $isfirst = 0;
    else
        echo ",\n";
    echo "        {";
    echo "name:'$name', type:'line', data:[";
   
    $isfirst2 = 1;
    for ($d = -30; $d < 1; $d++) {
        if ($isfirst2)
            $isfirst2 = 0;
        else
            echo ",";
        $dt = date("Y-m-d", strtotime($d."days"));
        $ds = $dt." 00:00:00";
        $de = $dt." 23:59:59";
        $q = "select avg(score) from group_avg_score where groupid = ? and tm >= ? and tm <= ?";
        $stmt2 = $mysqli->prepare($q);
        $stmt2->bind_param("iss", $id, $ds, $de);
        $stmt2->execute();
        $stmt2->bind_result($score);
        $stmt2->store_result();
        $stmt2->fetch();
        if ($score != "")
            echo sprintf("%.1f",$score);
        else echo 0;
        $stmt2->close();
    }
    echo "]}";
}
$stmt->close();
?>

    ]
};
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
       </script>
   </body>
</html>
