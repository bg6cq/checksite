#!/bin/bash

echo "<html><head>" > nindex.html
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />" >> nindex.html
echo "</head><body>" >> nindex.html
echo "相关测试代码在 <a href=https://github.com/bg6cq/checksite target=_blank>https://github.com/bg6cq/checksite</a><p>" >> nindex.html
echo "说明：使用 <a href=https://http2.pro/ target=_blank>https://http2.pro</a> 测试是否支持HTTP/2.0<p>" >> nindex.html
echo "测试开始时间：">>nindex.html
date >> nindex.html
echo "<p>" >> nindex.html

echo "国际知名高校对比<p>" >> nindex.html
echo "<table border=1 cellspacing=0>" >> nindex.html
echo "<th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>SSL</th><th>HTTP/2</th></tr>" >> nindex.html

cat topu.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td>$univ</td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> nindex.html
	echo "</tr>" >> nindex.html
	echo "" >> nindex.html
done
echo "</table>" >> nindex.html

echo "<p>国内C9高校对比<p>" >> nindex.html
echo "<table border=1 cellspacing=0>" >> nindex.html
echo "<th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>SSL</th><th>HTTP/2</th></tr>" >> nindex.html

cat c9.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td>$univ</td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> nindex.html
	echo "</tr>" >> nindex.html
	echo "" >> nindex.html
done
echo "</table>" >> nindex.html


echo "<p>国内其他高校对比<p>" >> nindex.html
echo "如果想出现在这里，欢迎PR<p>" >> nindex.html
echo "<table border=1 cellspacing=0>" >> nindex.html
echo "<th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>SSL</th><th>HTTP/2</th></tr>" >> nindex.html

cat cu.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td>$univ</td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> nindex.html
	echo "</tr>" >> nindex.html
	echo "" >> nindex.html
done
echo "</table><p>" >> nindex.html

echo "测试结束时间：">>nindex.html
date >> nindex.html
echo "<p>" >> nindex.html

mv -f nindex.html index.html
