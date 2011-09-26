<?php
require("include/user.class.php");
require("user.config.php");

if(isset($_POST['oper'])){
	if(@$_POST['oper'] == "reset"){

		
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

		$student_id = @$_POST['id'];
		
		   		if($user->reset_password($student_id,$default_password)){
		   			echo '0';
		   		}else {
		   			echo '1';
		   		}
			
	}	
}

?>