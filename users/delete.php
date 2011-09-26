<?php
require("include/user.class.php");
require("user.config.php");

if(isset($_POST['oper'])){
	if(@$_POST['oper'] == "del"){

		
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
		$destroy_id = @$_POST['id'];
		
		   		if($user->destroy($destroy_id)){
		   			echo '0';
		   		}else {
		   			echo '1';
		   		}
			
	}	
}

?>