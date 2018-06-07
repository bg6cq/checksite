#!/bin/bash

cat header.html > nindex.html

beginDatetime=`date`

id=1

cat data/sites.txt | while read title datafile timeout; do

/bin/echo '<div class="card">' >> nindex.html
/bin/echo -n '  <h5 class="card-header">' >> nindex.html
/bin/echo -n $title >> nindex.html
/bin/echo '对比</h5>' >> nindex.html
/bin/echo '  <div class="card-body">' >> nindex.html
/bin/echo '    <h5 class="card-title"></h5>' >> nindex.html
/bin/echo '    <p class="card-text">' >> nindex.html

/bin/echo -n '<table border=1 cellspacing=0 id="myTable' >> nindex.html
/bin/echo -n $id >> nindex.html
/bin/echo '" class="display">' >> nindex.html
id=`expr $id + 1`
/bin/echo "<thead><th></th><th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>HTTPS</th><th>HTTP/2</th><th>评分</th></tr></thead><tbody>" >> nindex.html

cat data/$datafile | while read univ site; do 
	/bin/echo 
	/bin/echo $univ $site
	/bin/echo "<tr><td align=center></td><td>$univ</td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	sh checksite.sh $site tmp.tmp $timeout
	cat tmp.tmp >> nindex.html
	/bin/echo "</tr>" >> nindex.html
	/bin/echo "" >> nindex.html
done
/bin/echo "</tbody></table>" >> nindex.html

/bin/echo '    </p>' >> nindex.html
/bin/echo '  </div>' >> nindex.html
/bin/echo '</div>' >> nindex.html

done

endDatetime=`date`

/bin/echo '<script>var beginDatetime = "'$beginDatetime'";</script>' >> nindex.html
/bin/echo '<script>var endDatetime = "'$endDatetime'";</script>' >> nindex.html

cat footer.html >> nindex.html

mv -f nindex.html index.html
