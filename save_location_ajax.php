<?

$url = get_param("url","post");
$course = get_param("course","post");
$student_id = $userdata['data']['student_id'];

$sql="UPDATE  `sco`.`sco_data` SET  `last_location` =  '{$url}' WHERE  `sco_data`.`organizations_id` ={$course} and `sco_data`.`student_id` ={$student_id} LIMIT 1";
$result = mysql_query($sql);
	
echo $sql;
?>
