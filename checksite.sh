#!/bin/bash

# 
#./checksite www.site.com outputfile.txt
# 检查 www.site.com 的IPv6解析，IPv6访问，HTTPS，HTTP/2.0 支持，结果写入文件 outputfile.txt
# 结果是<td>OK</td><td>&nbsp</td>等
#

OK="<td align=center><img src=ok.png></td>"
#NA="<td align=center><img src=no.png></td>"
NA="<td align=center>&nbsp;</td>"
TIMEOUT=4

score=0

#要求3个参数，第一个参数是站点名字，第二个参数是输出文件名，第三个参数是超时时间
if [ ! $# -eq 3 ]; then
	/bin/echo I need www.site.com outpufile.txt timeout
	exit
fi

TIMEOUT=$3

AAAA=0
IPV6=0
HTTPSV4=0
HTTPSV6=0
HTTP2=0

#检查是否有IPv6解析
/bin/echo -n check ipv6" "
host -t aaaa $1 | grep "has IPv6 add" | cut -f5 -d' ' | head -1 > tmp.tmp.1
ipv6=`cat tmp.tmp.1`
rm -f tmp.tmp.1
if [ -z $ipv6 ]; then
	/bin/echo no ipv6 support
	/bin/echo -n $NA >> $2
	/bin/echo -n $NA >> $2
else
#检查IPv6是否可以访问
	/bin/echo -n $1 IPv6 address is: $ipv6" "
	AAAA=1
	/bin/echo -n $OK >> $2
	score=`expr $score + 20`
	/bin/echo -n check ipv6 http access " "
	curl -m $TIMEOUT -6 -i http://$1  2>/dev/null | head -1 | grep HTTP > /dev/null
	retcode=$?
	if [ $retcode -eq 0 ]; then
		/bin/echo IPv6 http OK
		IPV6=1
		/bin/echo -n $OK >> $2
		score=`expr $score + 20`
	else
		/bin/echo IPv6 http N/A
		/bin/echo -n $NA >> $2
	fi

fi

#检查https是否可以访问
/bin/echo -n check httpsv4" "
https=0

curl -m $TIMEOUT -i -4 https://$1  2>/dev/null | head -1 | grep HTTP > /dev/null
retcode=$?
if [ $retcode -eq 0 ]; then
	/bin/echo httpsv4 OK
	HTTPSV4=1
	/bin/echo -n $OK >> $2
	https=1
	score=`expr $score + 20`
else
	echo N/A
	/bin/echo $NA >> $2
fi

/bin/echo -n check httpsv6" "

curl -m $TIMEOUT -i -6 https://$1  2>/dev/null | head -1 | grep HTTP > /dev/null
retcode=$?
if [ $retcode -eq 0 ]; then
	/bin/echo httpsv6 OK
	HTTPSV6=1
	/bin/echo -n $OK >> $2
	https=1
	score=`expr $score + 20`
else
	echo N/A
	/bin/echo $NA >> $2
fi


if [ $https -eq 1 ]; then

	#访问 http2.pro 检查是否支持http/2

	/bin/echo -n check http/2" "
	#curl -m $TIMEOUT --http2 -i https://$1 2> /dev/null | head -1 | grep HTTP/2 > /dev/mull
	curl https://http2.pro/check?url=https%3A//$1/ 2>/dev/null | grep page_title  | grep Supported > /dev/null
	retcode=$?
	if [ $retcode -eq 0 ]; then
		/bin/echo OK
		HTTP2=1
		/bin/echo -n $OK >> $2
		score=`expr $score + 20`
	else
		/bin/echo N/A 
		/bin/echo $NA >> $2
	fi
else
# https 无法访问就不再检查http/2，对国外站点可能会有偶尔错误
	/bin/echo i will not check http/2
	/bin/echo -n $NA >> $2
fi

if [ $score -eq 100 ]; then
	if [ -f addon/$1 ]; then
		addon=`cat addon/$1`
		score=`expr  $addon + $score`
	fi
fi

/bin/echo -n "<td align=center>$score</td>" >> $2

php log_status.php $1 $AAAA $IPV6 $HTTPSV4 $HTTPSV6 $HTTP2
