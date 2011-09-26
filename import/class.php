<?php 

require "../config.php";
// connect to the database
global $link;
$link = mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname,$link);

//echo $argv[0];

$class_data = "SUBJECT-10,1";
$class_data = "golf_sample_default_org,3";
$class_data = "golf_sample_minimum_org,3";

$class_arr = split(",",$class_data);

$check_data = false;
$organizations_id = 0;

$sql = "select * from organizations where author  ='{$class_arr[0]}' limit 1";
$result = mysql_query($sql,$link);

while($row = mysql_fetch_array($result)){
	$sql = "select count(*) as num from organizations_class where organizations_id ='{$row['organizations_id']}' limit 1";
	$result_count = mysql_query($sql,$link);
	while($row_count = mysql_fetch_array($result_count)){
		if($row_count['num'] < 1){
			$organizations_id = $row['organizations_id'];
		}
	}
	$check_data = true;
}

if($organizations_id != 0){
	$sql="INSERT INTO `organizations_class` (
		`organizations_id` ,
		`class`
		)
		VALUES (
		'{$organizations_id}', '{$class_arr[1]}'
		)";
	$result = mysql_query($sql,$link);
	echo "Import data";
}else if($check_data){
	echo "organizations_id exists";
}else{
	echo "{$class_arr[0]} not exists";
}


?>