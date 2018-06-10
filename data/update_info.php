<?php

$db_host = "localhost";
$db_user = "root";
$db_passwd = "";
$db_dbname = "checksite";

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
	echo mysqli_connect_error();
}


$file_group = fopen("group.txt","r");
if(!$file_group) {
	echo "file group.txt open error\n";
	exit(1);
}

// first delete all
$q="delete from `group`";
$stmt=$mysqli->prepare($q);
$stmt->execute();
$stmt->close();

while (($buffer = fgets($file_group, 4096)) !== false) {
        echo $buffer;
	$g=preg_split("/[\s,]+/",$buffer);
	if($g[0]=="#")
		continue;
	
	// insert into group
	$q="replace into `group` values(?,?,?)";
	$stmt=$mysqli->prepare($q);
	$stmt->bind_param("isi",$g[0],$g[1],$g[3]);
	$stmt->execute();
	$stmt->close();

	$file_site = fopen($g[2],"r");
	while (($buf2 = fgets($file_site, 4096)) !== false) {
        	echo $buf2;
		$s=preg_split("/[\s,]+/",$buf2);
		if($s[0]=="#")
			continue;
	
		// insert into group_site
		$q="replace into group_site values(?,?)";
		$stmt=$mysqli->prepare($q);
		$stmt->bind_param("is",$g[0],$s[1]);
		$stmt->execute();
		$stmt->close();
	
		// insert into site
		$q="replace into site values(?,?)";
		$stmt=$mysqli->prepare($q);
		$stmt->bind_param("ss",$s[1],$s[0]);
		$stmt->execute();
		$stmt->close();
	}
	fclose($file_site);
}
?>
