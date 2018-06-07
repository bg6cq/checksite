#!/bin/bash

cat header.html > nindex.html

beginDatetime=`date`

echo '<div class="card">' >> nindex.html
echo '  <h5 class="card-header">国际知名高校对比</h5>' >> nindex.html
echo '  <div class="card-body">' >> nindex.html
echo '    <h5 class="card-title"></h5>' >> nindex.html
echo '    <p class="card-text">' >> nindex.html

echo "<table border=1 cellspacing=0 id='myTable1' class='display'>" >> nindex.html
echo "<thead><th></th><th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>HTTPS</th><th>HTTP/2</th></tr></thead><tbody>" >> nindex.html

cat data/topu.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td></td><td>$univ</td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> nindex.html
	echo "</tr>" >> nindex.html
	echo "" >> nindex.html
done
echo "</tbody></table>" >> nindex.html

echo '    </p>' >> nindex.html
echo '  </div>' >> nindex.html
echo '</div>' >> nindex.html


echo '<div class="card">' >> nindex.html
echo '  <h5 class="card-header">国内C9高校对比</h5>' >> nindex.html
echo '  <div class="card-body">' >> nindex.html
echo '    <h5 class="card-title"></h5>' >> nindex.html
echo '    <p class="card-text">' >> nindex.html

echo "<table border=1 cellspacing=0 id='myTable2' class='display'>" >> nindex.html
echo "<thead><th></th><th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>HTTPS</th><th>HTTP/2</th></tr></thead><tbody>" >> nindex.html

cat data/c9.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td></td><td>$univ</td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> nindex.html
	echo "</tr>" >> nindex.html
	echo "" >> nindex.html
done
echo "</tbody></table>" >> nindex.html

echo '    </p>' >> nindex.html
echo '  </div>' >> nindex.html
echo '</div>' >> nindex.html

echo '<div class="card">' >> nindex.html
echo '  <h5 class="card-header">国内其他985-211高校对比</h5>' >> nindex.html
echo '  <div class="card-body">' >> nindex.html
echo '    <h5 class="card-title"></h5>' >> nindex.html
echo '    <p class="card-text">' >> nindex.html

echo "<table border=1 cellspacing=0 id='myTable3' class='display'>" >> nindex.html
echo "<thead><th></th><th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>HTTPS</th><th>HTTP/2</th></tr></thead><tbody>" >> nindex.html

cat data/cu.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td></td><td>$univ</td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> nindex.html
	echo "</tr>" >> nindex.html
	echo "" >> nindex.html
done
echo "</tbody></table>" >> nindex.html

echo '    </p>' >> nindex.html
echo '  </div>' >> nindex.html
echo '</div>' >> nindex.html

echo '<div class="card">' >> nindex.html
echo '  <h5 class="card-header">国内其他高校对比</h5>' >> nindex.html
echo '  <div class="card-body">' >> nindex.html
echo '    <h5 class="card-title">如果想出现在这里，欢迎PR</h5>' >> nindex.html
echo '    <p class="card-text">' >> nindex.html

echo "<table border=1 cellspacing=0 id='myTable4' class='display'>" >> nindex.html
echo "<thead><th></th><th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>HTTPS</th><th>HTTP/2</th></tr></thead><tbody>" >> nindex.html

cat data/cuo.txt | while read univ site; do 
	echo 
	echo $univ $site
	echo "<tr><td></td><td>$univ</td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	sh checksite.sh $site  tmp.tmp
	cat tmp.tmp >> nindex.html
	echo "</tr>" >> nindex.html
	echo "" >> nindex.html
done
echo "</tbody></table>" >> nindex.html

echo '    </p>' >> nindex.html
echo '  </div>' >> nindex.html
echo '</div>' >> nindex.html


endDatetime=`date`

echo '<script>var beginDatetime = "'$beginDatetime'";</script>' >> nindex.html
echo '<script>var endDatetime = "'$endDatetime'";</script>' >> nindex.html

cat footer.html >> nindex.html

mv -f nindex.html index.html
