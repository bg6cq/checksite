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
        text: '各组高校对比'
    },
    tooltip: {},
    legend: {
    //    data: ['预算分配（Allocated Budget）', '实际开销（Actual Spending）']
       data: [
<?php

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
?>
]
    },
    radar: {
        // shape: 'circle',
        name: {
            textStyle: {
                color: '#fff',
                backgroundColor: '#999',
                borderRadius: 3,
                padding: [3, 5]
           }
        },
        indicator: [
           { name: 'v4 HTTP', max: 40},
           { name: 'V4 HTTPS', max: 10},
           { name: 'V4 HTTP2', max: 10},
           { name: 'V6 AAAA', max: 10},
           { name: 'v6 HTTP', max: 10},
           { name: 'V6 HTTPS', max: 10},
           { name: 'V6 HTTP2', max: 10}
        ]
    },
    series: [{
        name: '预算 vs 开销（Budget vs spending）',
        type: 'radar',
        // areaStyle: {normal: {}},
        data : [
        //    {
        //        value : [4300, 10000, 28000, 35000, 50000, 19000],
        //        name : '预算分配（Allocated Budget）'
        //    },
        //     {
        //        value : [5000, 14000, 28000, 31000, 42000, 21000],
        //        name : '实际开销（Actual Spending）'
        //    }
<?php

$q = "select group.name, avg(status_last.ipv4 * 4) * 10, avg(status_last.httpsv4) * 10, avg(status_last.http2v4) * 10, avg(status_last.aaaa) * 10, avg(status_last.ipv6) * 10,avg(status_last.httpsv6) * 10,avg(status_last.http2v6) * 10 from `group` left join group_site on group.id = group_site.groupid left join status_last on group_site.hostname = status_last.hostname group by group.id";
$stmt = $mysqli->prepare($q);
$stmt->execute();
$stmt->bind_result($name, $ipv4, $httpsv4, $http2v4, $aaaa, $ipv6, $httpsv6, $http2v6);
$stmt->store_result();
$isfirst = 1;
while ($stmt->fetch()) {
    if ($isfirst)
        $isfirst=0;
    else
        echo ",\n";
    echo "{ value: [$ipv4, $httpsv4, $http2v4, $aaaa, $ipv6, $httpsv6, $http2v6 ],";
    echo "name: '$name' }";
}
$stmt->close();
?>
        ]
    }]
};;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
       </script>
   </body>
</html>
