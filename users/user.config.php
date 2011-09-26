<?php
	$student_id_cond 		= array("presence"=>true,
						  			"uniqueness"=>true,
						  			"length"=>array("min"=> 1, "max"=>12),
									"numericality" => true );
						
	$prefix_name_cond 		= array("presence"=>true,
							  		"inclusion"=>User::get_prefix());
		
	$student_name_cond 		= array("presence"=>true);
	
	$student_password_cond	= array("presence"=>true);
							
	$student_type_cond 	 	= array("inclusion" => array("admin","guest","student"));
	
	$register_date_cond		= array("presence" => true);
	
	$default_student_type = "student";
	$default_password = "12345";
	
?>