<?php
require("include/user.class.php");
require("user.config.php");

if(isset($_POST['oper'])){
	if(@$_POST['oper'] == "add"){

		$user = new User();
		if (@$_GET['debug'] == 1)
			$user->show_debug(FALSE);
			
		$all_prefix = $user->get_prefix();
		$student = array();
		$i = 0; 
		$is_valid = FALSE;
		$student = array();
		
		$student_id = @$_POST['student_id'];
		$prefix 	= @$_POST['prefix_name'];
		$student_name = @$_POST['student_name'];
		$student_lastname = @$_POST['student_lastname'];
		
		$student['student_id'] = (int)trim($student_id);
		$student['prefix_name'] = trim($prefix);
		$student['student_name'] = trim($student_name);
		$student['student_lastname'] = trim($student_lastname);
		$student['student_password'] = $default_password;
		$student['student_type'] = $default_student_type;
		$student['register_date'] = 'NOW()';
		$student['lastlogin_date'] = "";
			/*exit;*/
		$is_valid = $user->validates($student,"student_id", $student_id_cond) && $user->validates($student,"prefix_name", $prefix_name_cond) &&
    	$user->validates($student,"student_name", $student_name_cond) && $user->validates($student,"student_lastname",$student_name_cond) &&
    	$user->validates($student,"student_password",$student_password_cond) && $user->validates($student,"student_type",$student_type_cond) &&
    	$user->validates($student,"register_date",$register_date_cond) ;
    	
		
    		if ($is_valid !== false){
    	    
		   		if($user->save($student)){
		   			echo '0';
		   		}else {
		   			echo '1';
		   		}

			}else {
				echo '99';
			}		
	}
}

?>