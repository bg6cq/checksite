#!/bin/bash

echo "<html><head>" > index.html
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />" >> index.html
echo "</head><body>" >> index.html
echo "相关测试代码在<a href=https://github.com/bg6cq/checksite>https://github.com/bg6cq/checksite</a><p>" >> index.html
echo "说明：使用<a href=https://http2.pro/>https://http2.pro</a>测试是否支持HTTP/2.0，结果可能与实际有些不同<p>" >> index.html
echo "测试时间：">>index.html
date >> index.html
echo "<p>" >> index.html

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


echo "<p>国内其他高校对比<p>" >> index.html
echo "如果想出现在这里，欢迎PR<p>" >> index.html
echo "<table border=1 cellspacing=0>" >> index.html
echo "<th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>SSL</th><th>HTTP/2</th></tr>" >> index.html

cat cu.txt | while read univ site; do 
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
