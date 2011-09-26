<?
$err = "";
if(get_param("student_id") && $userdata['data']['student_type'] == "admin"){

	$student_id = get_param("student_id");
	$sql = "DELETE FROM `student` WHERE `student`.`student_id` = {$student_id} LIMIT 1";
	$result = mysql_query($sql,$link);
	if(isset($_SERVER['HTTP_REFERER'])){
		js_redirect($_SERVER['HTTP_REFERER']);
	}else{
		js_redirect("index.php?cmd=user_list");	
	}
}

?>