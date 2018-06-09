#!/bin/bash

# 
#./checksite.sh www.site.com outputfile.txt timeout
# 检查 www.site.com 的IPv6解析，IPv6访问，HTTPS，HTTP/2.0 支持，结果写入文件 outputfile.txt
# 结果是<td>OK</td><td>&nbsp</td>等
#

OK="<td align=center><img src=ok.png></td>"
#NA="<td align=center><img src=no.png></td>"
NA="<td align=center>&nbsp;</td>"


#要求3个参数，第一个参数是站点名字，第二个参数是输出文件名，第三个参数是超时时间
if [ ! $# -eq 3 ]; then
	echo I need www.site.com outpufile.txt timeout
	exit
fi

TIMEOUT=$3
IPV4=0
AAAA=0
IPV6=0
HTTPSV4=0
HTTPSV6=0
HTTP2V4=0
HTTP2V6=0

network-probes/200-http-ipv4.sh http://$1 $TIMEOUT && IPV4=1

#检查是否有IPv6解析
#检查http IPv6是否可以访问
network-probes/100-dns-aaaa.sh $1 && AAAA=1 && network-probes/200-http-ipv6.sh http://$1 $TIMEOUT && IPV6=1

#检查httpsv4/v6, http2 v4/v6
https=0
network-probes/200-http-ipv4.sh https://$1 $TIMEOUT && HTTPSV4=1 && network-probes/200-http2-ipv4.sh https://$1 $TIMEOUT && HTTP2V4=1
network-probes/200-http-ipv6.sh https://$1 $TIMEOUT && HTTPSV6=1 && network-probes/200-http2-ipv6.sh https://$1 $TIMEOUT && HTTP2V6=1

score=0
if [ $IPV4 -eq 0 ]; then
	echo -n $NA >> $2
else
	echo -n $OK >> $2
	let score+=40
fi

if [ $AAAA -eq 0 ]; then
	echo -n $NA >> $2
else
	echo -n $OK >> $2
	let score+=10
fi

if [ $IPV6 -eq 0 ]; then
	echo -n $NA >> $2
else
	echo -n $OK >> $2
	let score+=10
fi

if [ $HTTPSV4 -eq 0 ]; then
	echo -n $NA >> $2
else
	echo -n $OK >> $2
	let score+=10
fi

if [ $HTTPSV6 -eq 0 ]; then
	echo -n $NA >> $2
else
	echo -n $OK >> $2
	let score+=10
fi

if [ $HTTP2V4 -eq 0 ]; then
	echo -n $NA >> $2
else
	echo -n $OK >> $2
	let score+=10
fi

if [ $HTTP2V6 -eq 0 ]; then
	echo -n $NA >> $2
else
	echo -n $OK >> $2
	let score+=10
fi


if [ $score -eq 100 ]; then
	if [ -f addon/$1 ]; then
		addon=`cat addon/$1`
		let score=addon+score
	fi
fi

echo -n "<td align=center>$score</td>" >> $2

echo $1 $IPV4 $AAAA $IPV6 $HTTPSV4 $HTTPSV6 $HTTP2V4 $HTTP2V6
php log_status.php $1 $IPV4 $AAAA $IPV6 $HTTPSV4 $HTTPSV6 $HTTP2V4 $HTTP2V6
