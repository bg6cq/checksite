<?php

include ("db.php");

function update_log($hostname, $dnssec, $ipv4, $aaaa, $ipv6, $httpsv4, $httpsv6, $http2v4, $http2v6)
{
    global $mysqli;
    $q = "replace into onlinecheck_log values(?, now(), ?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($q);
    $stmt->bind_param("siiiiiiii", $hostname, $dnssec, $ipv4, $aaaa, $ipv6, $httpsv4, $httpsv6, $http2v4, $http2v6);
    $stmt->execute();
    $stmt->close();
}

function filter_hostname($name, $domain_only=false) {
    // entire hostname has a maximum of 253 ASCII characters
    if (!($len = strlen($name)) || $len > 253
    // .example.org and localhost- are not allowed
    || $name[0] == '.' || $name[0] == '-' || $name[ $len - 1 ] == '.' || $name[ $len - 1 ] == '-'
    // a.de is the shortest possible domain name and needs one dot
    || ($domain_only && ($len < 4 || strpos($name, '.') === false))
    // several combinations are not allowed
    || strpos($name, '..') !== false
    || strpos($name, '.-') !== false
    || strpos($name, '-.') !== false
    // only letters, numbers, dot and hypen are allowed
/*
    // a little bit slower
    || !ctype_alnum(str_replace(array('-', '.'), '', $name))
*/
    || preg_match('/[^a-z\d.-]/i', $name)
    ) {
        return false;
    }
    // each label may contain up to 63 characters
    $offset = 0;
    while (($pos = strpos($name, '.', $offset)) !== false) {
        if ($pos - $offset > 63) {
            return false;
        }
        $offset = $pos + 1;
    }
    return $name;
}

header( 'Content-type: text/html; charset=utf-8' );

$hostname = $_REQUEST["hostname"];
if ($hostname != filter_hostname($hostname)) {
    echo "输入的主机名 $hostname 格式不正确<p>";
    exit(0);
}

if ($hostname == "" ) {
    echo "<form method=get action=onlinecheck.php>";
    echo "主机名：<input name=hostname value=\"$hostname\"><p>";
    echo "<input type=submit name=cmd value=\"测试\"></form>";
    exit(0);
}
$timeout = 2;

$fp = fopen("/tmp/lock.txt", "w+");
$count = 0;
$timeout_secs = 10; //number of seconds of timeout
$got_lock = true;
while (!flock($fp, LOCK_EX | LOCK_NB, $wouldblock)) {
    if ($wouldblock && $count++ < $timeout_secs) {
	echo $count;
	ob_flush();
	flush();
        sleep(1);
    } else {
        $got_lock = false;
        break;
    }
}
if (!$got_lock) {
    echo "系统忙，请刷新重试\n";
    exit(0);
}

echo "正在测试 $hostname"."，请等待测试完成<p>\n";
ob_flush();
flush();

$dnssec = 0;
$ipv4 = 0;
$aaaa = 0;
$ipv6 = 0;
$httpsv4 = 0;
$httpsv6 = 0;
$http2v4 = 0;
$http2v6 = 0;

echo "<table width=200 border=1 cellspacing=0><th width=130>测试项目</th><th width=70>结果</th></tr>\n";
echo "<tr><td>DNSSEC</td><td align=center>";
//检查DNSSEC
$retval = 1;
$msg = system("bash /usr/src/checksite/network-probes/100-dns-dnssec.sh $hostname >/dev/null 2>/dev/null", $retval);
if ($retval == 0) 
    $dnssec = 1;

if ($dnssec == 1) 
    echo "<img src=ok.png>";
else 
    echo "<font color=red>Failed!</font>";
echo "</td></tr>\n";
ob_flush();
flush();

echo "<tr><td>IPv4 HTTP</td><td align=center>";
//检查httpv4
$retval = 1;
$msg = system("bash /usr/src/checksite/network-probes/200-http-ipv4.sh http://$hostname $timeout >/dev/null 2>/dev/null", $retval);
if ($retval == 0) 
    $ipv4 = 1;

if ($ipv4 == 1) 
    echo "<img src=ok.png>";
else 
    echo "<font color=red>Failed!</font>";
echo "</td></tr>\n";
ob_flush();
flush();

echo "<tr><td>IPv4 HTTPS</td><td align=center>";
ob_flush();
flush();
#检查httpsv4/v6, http2 v4/v6
$msg = system("bash /usr/src/checksite/network-probes/200-http-ipv4.sh https://$hostname $timeout >/dev/null 2>/dev/null", $retval);
if ($retval == 0) 
    $httpsv4 = 1;
if ($httpsv4 == 1) 
    echo "<img src=ok.png>";
else 
    echo "<font color=red>Failed!</font>";
echo "</td></tr>\n";
ob_flush();
flush();

echo "<tr><td>IPv4 HTTP/2</td><td align=center>";
ob_flush();
flush();
if ($httpsv4 == 1) {
    $msg = system("bash /usr/src/checksite/network-probes/200-http2-ipv4.sh https://$hostname $timeout >/dev/null", $retval);
    if ($retval == 0)
        $http2v4 = 1;
}
if ($http2v4 == 1) 
    echo "<img src=ok.png>";
else 
    echo "<font color=red>Failed!</font>";
echo "</td></tr>\n";
ob_flush();
flush();

#检查是否有IPv6解析
#检查http IPv6是否可以访问
echo "<tr><td>IPv6 AAAA</td><td align=center>";
ob_flush();
flush();
$msg = system("bash /usr/src/checksite/network-probes/100-dns-aaaa.sh $hostname >/dev/null", $retval);
if ($retval == 0) 
    $aaaa = 1;
if ($aaaa == 1) 
    echo "<img src=ok.png>";
else 
    echo "<font color=red>Failed!</font>";
echo "</td></tr>\n";
ob_flush();
flush();

echo "<tr><td>IPv6 HTTP</td><td align=center>";
ob_flush();
flush();
if ($aaaa == 1) {
    $msg = system("bash /usr/src/checksite/network-probes/200-http-ipv6.sh http://$hostname $timeout >/dev/null", $retval);
    if ($retval == 0)
        $ipv6 = 1;
}

if ($ipv6== 1) 
    echo "<img src=ok.png>";
else 
    echo "<font color=red>Failed!</font>";
echo "</td></tr>\n";
ob_flush();
flush();

echo "<tr><td>IPv6 HTTPS</td><td align=center>";
ob_flush();
flush();
if ($aaaa == 1) {
    $msg = system("bash /usr/src/checksite/network-probes/200-http-ipv6.sh https://$hostname $timeout >/dev/null", $retval);
    if ($retval == 0) 
        $httpsv6 = 1;
}
if ($httpsv6 == 1) 
    echo "<img src=ok.png>";
else 
    echo "<font color=red>Failed!</font>";
echo "</td></tr>\n";
ob_flush();
flush();
 
echo "<tr><td>IPv6 HTTP/2</td><td align=center>";
ob_flush();
flush();
if ($httpsv6 == 1) {
    $msg = system("bash /usr/src/checksite/network-probes/200-http2-ipv6.sh https://$hostname $timeout >/dev/null", $retval);
    if ($retval==0)
        $http2v6=1;
}
if ($http2v6 == 1) 
    echo "<img src=ok.png>";
else 
    echo "<font color=red>Failed!</font>";
echo "</td></tr>\n";

echo "</table>";
echo "<p>";
echo "测试完成";

echo "<form method=get action=onlinecheck.php>";
echo "主机名：<input name=hostname value=\"$hostname\"><p>";
echo "<input type=submit name=cmd value=\"重新测试\"></form>";
echo "<p><a href=onlinechecklog.php>最后100条在线测试结果</a></p>";
echo "<p><a href=index.php>返回https://ipv6.ustc.edu.cn/</a></p>";

if ($ipv4 + $aaaa + $ipv6 + $httpsv4 + $httsv6 + $http2v4 + $http2v6 != 0)
    update_log($hostname, $dnssec, $ipv4, $aaaa, $ipv6, $httpsv4, $httpsv6, $http2v4, $http2v6);

?>
