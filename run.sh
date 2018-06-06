#!/bin/bash

echo "相关测试代码在<a href=https://github.com/bg6cq/checksite>https://github.com/bg6cq/checksite</a><p>" > index.html
echo "说明：测试是否支持HTTP/2.0使用的是<a href=https://http2.pro/>https://http2.pro</a>，结果可能与实际不一致<p>" >> index.html
echo "国际一流高校对比<p>" >> index.html
echo "<table border=1 cellspacing=0>" >> index.html
echo "<th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>SSL</th><th>HTTP/2</th></tr>" >> index.html

cat topu.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td>$univ</td><td>$site</td>" >> index.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> index.html
	echo "</tr>" >> index.html
	echo "" >> index.html
done
echo "</table>" >> index.html

echo "<p>国内C9高校对比<p>" >> index.html
echo "<table border=1 cellspacing=0>" >> index.html
echo "<th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>SSL</th><th>HTTP/2</th></tr>" >> index.html

cat c9.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td>$univ</td><td>$site</td>" >> index.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> index.html
	echo "</tr>" >> index.html
	echo "" >> index.html
done
echo "</table>" >> index.html
