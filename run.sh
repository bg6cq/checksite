#!/bin/bash

echo "国际一流高校对比<p>" > index.html
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
