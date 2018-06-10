#!/bin/bash

echo "{" > result.json

php gen_addon.php

beginDatetime=`date +"%Y-%m-%d %H:%M:%S"`

id=0

cat data/sites.txt | while read title datafile timeout; do

let id++

echo "   \"myTable$id\": [" >> result.json

cnt=0
cat data/$datafile | while read univ site; do 
	let cnt++
	echo 
	echo -n $cnt" "
	echo $univ $site

	if [ ! $cnt -eq 1 ]; then
		echo "       ," >> result.json
	fi

	echo "      {" >> result.json
    echo "         \"cnt\": \"$cnt\"," >> result.json
	echo "         \"hostname\": \"<a href=log.php?h=$site>$univ</a>\"," >> result.json
    echo "         \"name\": \"<a href=http://$site target=_blank>$site</a>\"," >> result.json

	/bin/rm -f tmp.tmp
	bash checksite.sh $site tmp.tmp $timeout
	cat tmp.tmp >> result.json
	/bin/rm -f tmp.tmp

    echo "      }	" >> result.json
done

echo "   ]," >> result.json

done

endDatetime=`date +"%H:%M:%S"`

echo "   \"beginDatetime\": \"$beginDatetime\"," >> result.json
echo "   \"endDatetime\": \"$endDatetime\"" >> result.json

echo "}" >> result.json

/bin/mv -f result.json web/result.json
