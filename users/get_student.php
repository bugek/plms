<?php
 	//phpinfo();
 	require("include/php_mysql_class/config.inc.php");

	// pull in the file with the database class
	require("include/php_mysql_class/Database.singleton.php");
	
	
	$db = Database::obtain(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);
	$db->connect();
	
	
	$limit = (isset($_GET['rows']))? @$_GET['rows']:50;
	$page  = (isset($_GET['page']))? @$_GET['page']:1;
	$sidx  = (isset($_GET['sidx']))? @$_GET['sidx']:1;
	$sord  = (isset($_GET['sord']))? @$_GET['sord']:'';
	
	$sql = "SELECT COUNT(*) as total FROM student WHERE student_type='student'";
	$rows = $db->query_first($sql);
	$total_students = $rows['total'];
	
	if( $total_students > 0 && $limit > 0 ){
		$total_pages = ceil($total_students/$limit);
	}else{
		$total_pages = 0;
	}
	
	if( $page > $total_pages) {
		$page = $total_pages;
	}
	
	$start = $limit * $page - $limit;
	
	$sql = "SELECT student_id,prefix_name,student_name,student_lastname,register_date,lastlogin_date FROM student WHERE student_type='student'
			 ORDER BY ".$sidx." ".$sord." limit ".$start.",".$limit ;
	//echo "<br/>".$sql."<br/>";
	$rows = $db->query($sql);
	//$docs = array( 'page' => $page , 'total' => $total_pages , 'record' => $total_students , 'rows' => array());
	$docs = new StdClass();
	
	$docs->page = $page;
	$docs->total = $total_pages;
	$docs->records = $total_students;
	$docs->rows = array();

	$i = 0 ;
	while ($student = $db->fetch($rows)) {
		/*echo '<tr><td width="100" align="center">'.$student['student_id'].'</td>';*/
		/*$docs['rows'][$i]['id'] = $student['student_id'];
		$docs['rows'][$i]['cell'] = $student;*/
       
        /*$docs->rows[$i]['id'] = $student['student_id'];
		$docs->rows[$i]['cell'] = array( $student['student_id'], $student['prefix_name'], $student['student_name'], $student['student_lastname'], 
											 $student['register_date'],$student['lastlogin_date']);*/
		$docs->rows[$i]['id'] = $student['student_id'];
		$docs->rows[$i]['cell'] = array($student['student_id'], $student['prefix_name'], 
										$student['student_name'], $student['student_lastname'], $student['register_date'], $student['lastlogin_date']);
		$i++;
	}
	//var_dump($docs);
	
	$json_docs = json_encode($docs);

	echo $json_docs;
?>