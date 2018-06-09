#!/bin/bash

cat header.html > nindex.html

php gen_addon.php

beginDatetime=`date +"%Y-%m-%d %H:%M:%S"`

id=0

cat data/sites.txt | while read title datafile timeout; do

echo "<div class=\"card\">" >> nindex.html
echo -n "  <h5 class=\"card-header\">${title}对比</h5>" >> nindex.html
echo "  <div class=\"card-body\">" >> nindex.html
echo "    <h5 class=\"card-title\"></h5>" >> nindex.html
echo "    <p class=\"card-text\">" >> nindex.html

let id++
echo -n "<table border=1 cellspacing=0 id=\"myTable$id\" class=\"display\">" >> nindex.html
echo "<thead><th></th><th>高校</th><th>网站</th><th>IPv6解析</th><th>IPv6访问</th><th>v4 HTTPS</th><th>v6 HTTPS</th><th>HTTP/2</th><th>评分</th></tr></thead><tbody>" >> nindex.html

cnt=0
cat data/$datafile | while read univ site; do 
	echo 
	echo $univ $site
	let cnt++
	echo -n "<tr><td align=center>$cnt</td><td><a href=log.php?h=$site>$univ</a></td><td><a href=http://$site target=_blank>$site</a></td>" >> nindex.html
	> tmp.tmp
	bash checksite.sh $site tmp.tmp $timeout
	cat tmp.tmp >> nindex.html
	/bin/rm -f tmp.tmp
	echo  "</tr>" >> nindex.html
done
echo "</tbody></table>" >> nindex.html
echo "    </p>" >> nindex.html
echo "  </div>" >> nindex.html
echo "</div>" >> nindex.html

done

endDatetime=`date +"%H:%M:%S"`

echo "<script>var beginDatetime = \"$beginDatetime\";</script>" >> nindex.html
echo "<script>var endDatetime = \"$endDatetime\";</script>" >> nindex.html

cat footer.html >> nindex.html

/bin/mv -f nindex.html web/index.html
