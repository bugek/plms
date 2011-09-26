<?

$student_id = $userdata['data']['student_id'];

	if($student_id !=2){
		//$left_menu[] = array("link"=>"index.php?cmd=edit_profile","title"=>"ข้อมูลส่วนตัว");
	}


$sql="
		SELECT 
		organizations_class.class
		,count(class) as num
		FROM `organizations_class`
		LEFT JOIN organizations ON organizations_class.organizations_id = organizations.organizations_id
		group by class
		";
		$result_class = mysql_query($sql);
		$class_link = array();
		$class_count = 0;
		$select_class = 0;
		$class_46 = false;
		while($row = mysql_fetch_array($result_class)){
			$class_count++;
			$pre_text_class = "";
			$end_num_class = "";
			if($row['class'] <= 6){
				$pre_text_class = "ประถมศึกษาปีที่";
				$end_num_class = $row['class'];
			}else if($row['class'] >= 7 && $row['class'] <= 9){
				$pre_text_class = "มัธยมศึกษาปีที่";
				$end_num_class = $row['class'] - 6;
			}else if($row['class'] >= 10 && $row['class'] <= 12){
				$pre_text_class = "มัธยมศึกษาปีที่";
				$end_num_class = "4-6";
			}
			if(!$class_46){
				if(get_param("cmd") == "show_course"){
					if(get_param("class") != "" && $row['class'] == get_param("class")){
						$class_link[] = array("class"=>"obj_selected","link"=>"#","title"=>"{$pre_text_class} ".$end_num_class,"onclick"=>"get_subject_list('{$row['class']}',this)");
						$select_class = $row['class'];
					}else{
						if($class_count == 1 && get_param("class") == ""){
							$class_link[] = array("class"=>"obj_selected","link"=>"#","title"=>"{$pre_text_class} ".$end_num_class,"onclick"=>"get_subject_list('{$row['class']}',this)");
							$select_class = $row['class'];
						}else{
							$class_link[] = array("link"=>"#","title"=>"{$pre_text_class} ".$end_num_class,"onclick"=>"get_subject_list('{$row['class']}',this)");
						}
					}
				}else{
					$class_link[] = array("link"=>"index.php?cmd=show_course&class=".$row['class'],"title"=>"{$pre_text_class} ".$end_num_class);
				}			
			}
			if($row['class'] >= 10 && $row['class'] <= 12){
				$class_46 = true;
			}

		}
		
		$left_menu[] = array("class"=>"head_left class_left", "link"=>"?cmd=show_course","title"=>"ชั้นเรียน","list"=>$class_link);
		
		if($student_id !=2){
			//$left_menu[] = array("link"=>"index.php?cmd=report_user","title"=>"รายงาน");
		}
		
		
		
?>

<div class="content_left left">
<?
	if(!$userdata){
?>
	<div class="menu_path">
		<div class="home_ie">
				&nbsp;
		</div>
	</div>
	<div class="menu_path_footer">
	&nbsp;
	</div>
<?
	}else{
		include 'left_menu.php';
	}
?>
</div>