<?php
require("include/user.class.php");
require("user.config.php");

if(isset($_POST['oper'])){
	if(@$_POST['oper'] == "edit"){

		
		$user = new User();
		$user->show_debug(TRUE);
			
		$all_prefix = $user->get_prefix();
		$student = array();
		$i = 0; 
		$is_valid = FALSE;
		$student = array();
		
		if(!isset($_POST['id'])){
			echo '99';
		}
		
		$id_valid = array("presence"=>true,
						  			"length"=>array("min"=> 4, "max"=>12),
									"numericality" => true );
		$old_id = @$_POST['id'];
		$student_id = @$_POST['student_id'];
		$prefix 	= @$_POST['prefix_name'];
		$student_name = @$_POST['student_name'];
		$student_lastname = @$_POST['student_lastname'];
		
		$student['student_id'] = (int)trim($student_id);
		$student['prefix_name'] = trim($prefix);
		$student['student_name'] = trim($student_name);
		$student['student_lastname'] = trim($student_lastname);
		//$student['student_password'] = $default_password;
		
			/*exit;*/
		$is_valid = $user->validates($student,"student_id", $id_valid) && $user->validates($student,"prefix_name", $prefix_name_cond) &&
    	$user->validates($student,"student_name", $student_name_cond) && $user->validates($student,"student_lastname",$student_name_cond) ;
			/* &&
    	$user->validates($student,"student_password",$student_password_cond) ;*/
    	
		
    		if ($is_valid !== false){
    	    
		   		if($user->update($student,$old_id)){
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