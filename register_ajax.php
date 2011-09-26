<?
$json_return = array();

if(get_param("student_id","post")){
	//ลงทะเบียน
	$student_id = (int) get_param("student_id","post");
	$student_pass = get_param("student_pass","post");
	$prefix_name = get_param("prefix_name","post");
	$student_name = get_param("student_name","post");
	$student_lastname = get_param("student_lastname","post");
	$student_type = "student";
	
	if($student_id > 0 && $student_pass != "" && $student_name != "" && $student_lastname != ""){
	
		if($userdata && get_param("cmd") != "register" && !get_param("student_id")){
		
			if($student_pass == "old_pass"){
				$sql = "UPDATE `student` SET `prefix_name` = '{$prefix_name}', `student_name` = '{$student_name}', `student_lastname` = '{$student_lastname}' WHERE `student`.`student_id` = ".$userdata['data']['student_id']." LIMIT 1";
			}else{
				$sql = "UPDATE `student` SET `prefix_name` = '{$prefix_name}', `student_name` = '{$student_name}', `student_lastname` = '{$student_lastname}', `student_password` = '".md5($student_pass.$keypass)."' WHERE `student`.`student_id` = ".$userdata['data']['student_id']." LIMIT 1";
			}
			$result = mysql_query($sql,$link);
			
			$sql = "select * from student where student_id='{$student_id}' limit 1";
			$result = mysql_query($sql,$link);
			while($row = mysql_fetch_array($result)){
				$_SESSION["user_name"] = $row['student_name'];
				$_SESSION["data"] = $row;
			}
			$json_return["msg"] = "บันทึกข้อมูลเรียบร้อย";
			$json_return["status"] = "true";
			
		}else if($userdata && get_param("student_id") && $userdata['data']['student_type'] == "admin"){
		
			if($student_pass == "old_pass"){
				$sql = "UPDATE `student` SET `prefix_name` = '{$prefix_name}', `student_name` = '{$student_name}', `student_lastname` = '{$student_lastname}' WHERE `student`.`student_id` = {$student_id} LIMIT 1";
			}else{
				$sql = "UPDATE `student` SET `prefix_name` = '{$prefix_name}', `student_name` = '{$student_name}', `student_lastname` = '{$student_lastname}', `student_password` = '".md5($student_pass.$keypass)."' WHERE `student`.`student_id` = {$student_id} LIMIT 1";
			}
			$result = mysql_query($sql,$link);

			$json_return["msg"] = "index.php?cmd=user_list";
			$json_return["status"] = "redirect";
			
		}else{
			$sql = "select count(*) as num from student where student_id='{$student_id}' limit 1";
			$result = mysql_query($sql,$link);
			while($row = mysql_fetch_array($result)){
				if($row['num'] == 0){

					$sql = "INSERT INTO student 
					(`student_id`, `prefix_name`, `student_name`, `student_lastname`, `student_password`, `student_type`, `register_date`, `lastlogin_date`) 
					VALUES 
					('{$student_id}', '{$prefix_name}', '{$student_name}', '{$student_lastname}', '".md5($student_pass.$keypass)."', 'student', NOW(), NOW())";
					mysql_query($sql,$link);
					if(isset($userdata['data']['student_type']) && $userdata['data']['student_type'] == "admin"){
						$json_return["msg"] = "index.php?cmd=user_list";
						$json_return["status"] = "redirect";
					}else{
						$json_return["msg"] = "ลงทะเบียนเรียนเรียบร้อย";
						$json_return["status"] = "true";
					}

				}else{
					$json_return["msg"] = "คุณเคยลงทะเบียนแล้ว";
					$json_return["status"] = "false";
				}
			}
		}

	}else{
		$json_return["msg"] = "คุณกรอกข้อมูลไม่ถูกต้อง";
		$json_return["status"] = "false";
	}
	
}else if($userdata && get_param("cmd") != "register"){
	if(get_param("student_id") && $userdata['data']['student_type'] == "admin"){
		$student_id = get_param("student_id");
		$sql = "select * from student where student_id='{$student_id}' limit 1";
		$result = mysql_query($sql,$link);
		while($row = mysql_fetch_array($result)){
			$student_name = $row['student_name'];
			$student_lastname = $row['student_lastname'];
			$prefix_name = $row['prefix_name'];
		}
			
		$student_id_read_only = 'readonly="readonly"';
		$old_pass = "old_pass";	
	}else{
		$student_id_read_only = 'readonly="readonly"';
		$student_id = $userdata['data']['student_id'];

		$student_name = $userdata['data']['student_name'];
		$student_lastname = $userdata['data']['student_lastname'];
		$prefix_name = $userdata['data']['prefix_name'];
		$old_pass = "old_pass";	
	}

	$submit_label = "แก้ไข";
	$submit_class = "edit";
}

echo json_encode($json_return);