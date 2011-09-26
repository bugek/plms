<?

		$student_id_read_only = 'readonly="readonly"';
		$student_id = $userdata['data']['student_id'];

		$student_name = $userdata['data']['student_name'];
		$student_lastname = $userdata['data']['student_lastname'];
		$prefix_name = $userdata['data']['prefix_name'];
		$msg = get_param("msg");
		$old_pass = "old_pass";	
	
?>
<div class="right_panel">
	<div class="form_register content_inner">
		<div class="inner_form">
		<em class="error"><?=$err?></em>
		<em class="msg"><?=$msg?></em>
		<form action="" method="post">
			<p>
			<ul>
				<li>
					รหัสนักเรียน: <span class="value_preview"><?=$student_id?></span>
				</li>
				<li>
					รหัสผ่าน: <span class="value_preview">xxxx</span>
				</li>
				<li>
					คำนำหน้า: <span class="value_preview"><?=$prefix_name?></span>
				</li>
				<li>
					ชื่อ: <span class="value_preview"><?=$student_name?></span>
				</li>
				<li>
					สกุล: <span class="value_preview"><?=$student_lastname?></span>
				</li>
			</ul>
			<div class="bt_bar">
				<input class="bt_input" type="button" value="แก้ไขข้อมูล" onclick="window.location='index.php?cmd=edit_profile'"/>
			</div>
			</p>
		 </form>
		 </div>
	 </div>
 </div>