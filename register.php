<?

$submit_label = "ลงทะเบียนเรียน";
$submit_class = "register";
$err = "";
$msg = "";

$student_id_read_only = '';
$student_id = "";
$student_pass = "";
$prefix_name = "";
$student_name = "";
$student_lastname = "";
$old_pass = "";

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
				$_SESSION["user_data"] = $row;
			}
			$msg = "บันทึกข้อมูลเรียบร้อย";
			$submit_label = "แก้ไข";
			$submit_class = "edit";
			js_redirect("index.php?cmd=display_profile&msg=บันทึกข้อมูลเรียบร้อย");
		}else if($userdata && get_param("student_id") && $userdata['data']['student_type'] == "admin"){
		
			if($student_pass == "old_pass"){
				$sql = "UPDATE `student` SET `prefix_name` = '{$prefix_name}', `student_name` = '{$student_name}', `student_lastname` = '{$student_lastname}' WHERE `student`.`student_id` = {$student_id} LIMIT 1";
			}else{
				$sql = "UPDATE `student` SET `prefix_name` = '{$prefix_name}', `student_name` = '{$student_name}', `student_lastname` = '{$student_lastname}', `student_password` = '".md5($student_pass.$keypass)."' WHERE `student`.`student_id` = {$student_id} LIMIT 1";
			}
			$result = mysql_query($sql,$link);
			js_redirect("index.php?cmd=user_list");
			
		}else{
			$sql = "select count(*) as num from student where student_id='{$student_id}' limit 1";
			$result = mysql_query($sql,$link);
			while($row = mysql_fetch_array($result)){
				if($row['num'] == 0){

					$sql = "INSERT INTO student 
					(`student_id`, `prefix_name`, `student_name`, `student_lastname`, `student_password`, `student_type`, `register_date`, `lastlogin_date`) 
					VALUES 
					('{$student_id}', '{$prefix_name}', '{$student_name}', '{$student_lastname}', '".md5($student_pass.$keypass)."', 'student', NOW(), NOW())";
					$result = mysql_query($sql,$link);
					if(isset($userdata['data']['student_type']) && $userdata['data']['student_type'] == "admin"){
						js_redirect("index.php?cmd=user_list");
					}else{
						js_redirect("index.php");
					}
					die("");

				}else{
					$err = "คุณเคยลงทะเบียนแล้ว";
				}
			}
		}

	}else{
		$err = "คุณกรอกข้อมูลไม่ถูกต้อง";
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

	$submit_label = "ยืนยัน";
	$submit_class = "edit";
}

$student_id_register = $student_id;
?>
<div class="right_panel">
	<div class="form_register content_inner">
		<?
			if($submit_label == "ลงทะเบียนเรียน"){
		?>
			<h1 class="big_bg_title">
			ลงทะเบียนเรียน
			</h1>
		<?
			}
		?>
		<div class="inner_form">
		<em class="error"><?=$err?></em>
		<em class="msg"><?=$msg?></em>
		<form action="" method="post">
			<p>
			<label for="student_id">รหัสนักเรียน: </label>
			<input type="text" class="input_txt" id="student_id" name="student_id" <?=$student_id_read_only?> value="<?=$student_id_register?>" /><br />
			
			<label for="student_pass">รหัสผ่าน: </label>
			<input type="password" class="input_txt" id="student_pass" name="student_pass" value="<?=$old_pass?>" /><br />	
			
			<label for="prefix_name">คำนำหน้า: </label>
			<select id="prefix_name" class="input_txt" name="prefix_name">
				<option <? if($prefix_name =="ด.ญ.") echo "selected"; ?> value="ด.ญ.">ด.ญ.</option>
				<option <? if($prefix_name =="ด.ช.") echo "selected"; ?> value="ด.ช.">ด.ช.</option>
				<option <? if($prefix_name =="นางสาว") echo "selected"; ?> value="นางสาว">นางสาว</option>
				<option <? if($prefix_name =="นาย") echo "selected"; ?> value="นาย">นาย</option>
			</select><br />	
			
			<label for="student_name">ชื่อ: </label>
			<input type="text" class="input_txt" id="student_name" name="student_name" value="<?=$student_name?>" /><br />
			
			<label for="student_lastname">สกุล: </label>
			<input type="text" class="input_txt" id="student_lastname" name="student_lastname" value="<?=$student_lastname?>" /><br />
			<div class="bt_bar <?=$submit_class?>">
				<input class="bt_input" type="button" onclick="window.location='index.php?cmd=display_profile'" value="ยกเลิก" />
				<input class="bt_input" type="submit" value="<?=$submit_label?>" />
			</div>
			</p>
		 </form>
		 </div>
	 </div>
 </div>