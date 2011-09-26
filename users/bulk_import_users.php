<?php  
	$theme_name = "ui-lightness";
	//$theme_name = "redmon";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html ; charset=utf8"/>
		<title>ระบบจัดการนักเรียน</title>
		<link rel="stylesheet" href="css/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/site.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="css/<?php echo $theme_name?>/jquery-ui-1.8.12.custom.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<script src="js/jquery-ui-1.8.12.custom.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery-1.5.2.min.js" type="text/javascript" charset="utf-8"></script>
	</head>
<body>	
<?php
require("include/user.class.php");
require("user.config.php");


function fopen_utf8($file_name){
	$encoding = '';
	$handle = fopen($file_name, 'r');
	$bom = fread($handle, 2);
	
	rewind($handle);
	
	if ($bom === chr(0xff).chr(0xfe) || $bom === chr(0xfe).chr(0xff)){
		$encoding = 'UTF-16';
	}else {
		$file_sample = fread($handle, 100) ; // read first 1000 byte , + e is a workaround fo mb_string bug
		
		rewind($handle);
		
		$encoding = mb_detect_encoding($file_sample , 'UTF-8',true);
		//$encoding = iconv_get_encoding($file_sample);
	}

	//exit;
	if (!$encoding){
		
		stream_filter_append($handle, 'convert.iconv.TIS-620/UTF-8');
	}
	
	return($handle);
}




if(!isset($_FILES['csv_file'])){
	//show from
?>
	<script type="text/javascript" charset="utf-8">
		$(function() {
						$("button, input, a ", ".formupload").button();
						$("input").button();
	</script>
	<div class="formupload">
	<form action="http://<? echo $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" name="form">
	  <input name="csv_file" type="file" id="csv_file">
	  <input name="submit" type="submit" id="submit" value="Submit">
	</form>
	</div>
	<?php
}else{
	
	copy($_FILES["csv_file"]["tmp_name"],$_FILES["csv_file"]["name"]); // Copy/Upload CSV
 
		/*$objConnect = mysql_connect("localhost","root","root") or die("Error Connect to Database"); // Conect to MySQL
		$objDB = mysql_select_db("mydatabase");*/

		//$FILE_CSV = fopen($_FILES["csv_file"]["name"], "r");
		$FILE_CSV = fopen_utf8($_FILES["csv_file"]["name"],"r");
		$user = new User();
		if (@$_GET['debug'] == 1)
			$user->show_debug(FALSE);
			
		$all_prefix = $user->get_prefix();
		$student = array();
		$i = 0; 
		$is_valid = FALSE;
		
		echo '<div class="line-record" padding="5"><table width=350>';
		echo '<tr>';
		echo '<th width=280 class="ui-state-default"> ข้อมูลนักเรียน </th><th class="ui-state-default">สถานะ</th></tr>';
		
		
		while (($objArr = fgetcsv($FILE_CSV, 1000, ",")) !== FALSE) {
   	 		//loop insert db
   	 		/*$j = 0;
   	 		foreach($objArr as $v){
   	 			$romeo = mb_detect_encoding($v,"UTF-8");
				echo $romeo;
   	 			If(!preg_match("/UTF-8/",$romeo)){
   	 				echo "convert" ;
				    $objArr[$j] = iconv("TIS-620","utf-8",$v);	
				    echo " to ".$objArr[$j];
				}
				$j++;
				
			} */
			/*var_dump($objArr);*/
			
   	 		list( $student_id, $prefix_name, $student_name, $student_lastname) = $objArr;

   	 		$student['student_id'] = (int)trim($student_id);
			$student['prefix_name'] = trim($prefix_name);
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
    	    
				echo '<td align="left"> ' .$student['student_id'].' '.$student['prefix_name'].' ';
				echo $student['student_name']." ".$student['student_lastname']."</td>";

		   		if($user->save($student)){
		   			echo '<td align="center"><span class="ui-icon ui-state-default ui-icon-check"></span>';
					echo '</td>';
		   		}else {
		   			echo '<td align="center"><span class="ui-icon ui-state-error ui-icon-cancel"></span>';
					echo '</td>';
		   		}

			}else {
				echo '<td align="left"> ' .$student['student_id'].' '.$student['prefix_name'].' ';
				echo $student['student_name']." ".$student['student_lastname']."</td>";
				echo '<td align="center">';
				echo '<a href="#" alt="ผิดพลาด" title="'.$user->error.'"><span class="ui-icon ui-state-error ui-icon-close"></span></a>';
				echo '</td>';
			}		
				echo '</tr>';
				usleep(500 * $i );
				$i++;
	}
	echo '</table></div>';
	
	fclose($FILE_CSV);
	echo " <br/> ...ดำเนินการเสร็จสิ้น.";
	


	if ($user->debug) {
		echo '<div id="debug_php" class="debug_php">';
		$user->debug_flush(); 
		echo '</div>';
	}

  }//end else
?>
	


</body>
</html>