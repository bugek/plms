<?php // every page needs to start with these basic things

// I'm using a separate config file. so pull in those config values
require(realpath("./include/php_mysql_class/config.inc.php"));

// pull in the file with the database class
require(realpath("./include/php_mysql_class/Database.singleton.php"));

// create the $db singleton object
DEFINE("PREFIX_BOY","ด.ช.");
DEFINE("PREFIX_GIRL","ด.ญ.");
DEFINE("PREFIX_MEN","นาย");
DEFINE("PREFIX_WOMEN","นางสาว");
$debug = true;

$student['student_id'] = 54001;
$student['prefix_name'] = PREFIX_BOY;
$student['student_name'] = "ป๋อง";
$student['student_lastname'] = "เก่งดี";
$student['student_password'] = "12345";
$student['student_type'] = 'student';
$student['register_date'] = time();
$student['lastlogin_date'] = "";

$db = Database::obtain(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);
$db->connect();

//validate data
if (validates("student_id",array("uniqueness" => true , "value" => $student['student_id'] ))) 
    echo "is exists";

//check user before save

/*$data = $student , $fieldname , array( "presence" => true,
								"format" => "pattern", 
								"length" => 100, 
								"uniqueness" => true,
								"inclusion" => [1 , 2, 3 , 4],
								"exclusion" => [1 , 2, 3 , 4],
								"numericality" => true );
*/

$student['student_id'] = "";
$validate_student_id = validates($student, "student_id", array( "presence" => true ,
																"uniqueness" => true , 
																"numericality" =>true )) ;
echo $validate_student_id;


// if all validate 
// save data

function validates($var,$field,$param){
	foreach($param as $key => $value){
		switch($key){
			case "numericality":
				return $param["numerical"] === true && numericality ;
				break;
			case "presence" : 
				return presence($field);
				break;
			case "format" : 
				return format($field,$value);
				break;
			case "length" : 
				return length($field,$value);
				break;
			case "uniqueness" : 
				return $param["uniqueness"] === true && uniqueness($field , $param['value']);
			case "inclusion" : 
				return inclusion($value);
			    break;
			case "exclusion" :
				return !inclusion($value);
				break;
		}
	}
	
}


function length($data,$value){
	if ($debug) {
		echo "in length function with parameter " . $data . "-".$value ; 
	}
	if ($data <= $value)
		return true;
	else 
		return false;
}

function uniqueness($field,$value){
	if ($debug) {
		echo "in uniquenessfunction with parameter " . $field. "-".$value ; 
	}
	
	if (is_exists($field,$value))
		return false;
	else 
		return true;
	
}
function presence($value){
	//not blank
	if ($debug) {
		echo "in presence function with parameter " .$value ; 
	}
	if (empty($value))
		return false;
	else
		return true;
}

function format($data,$pattern){
	if ($debug) {
		echo "in length function with parameter " . $data . "-".$pattern ; 
	}
	
	if (ereg($pattern,$data)) 
		return true;
	else
		return false;
	
}

function inclusion($field,$array){
	if ($debug) {
		echo "in length function with parameter " . $field . "-" .print_r($array) ; 
	}
	if (array_search($field,$array)) 
		return true;
	else 
		return false;
}

function is_exists($field,$value){
	if ($debug) {
		echo "in is_exists<br/";
		echo $field."<br/>";
		echo $value;
	}
	$db = Database::obtain();
	if(is_string($value)) 
	   $v = "'".$db->escape("$value")."'";
	else if (is_int($value))
	   $v = $value;
	else 
	   return false;
    $sql="SELECT `".$field."` FROM `student` WHERE $field=".$v;
    $row = $db->query_first($sql);
    
    // if user exists
    if(!empty($row['student_id'])) 
        return true;
	
    else 
        return false;
}

// connect to the server

$db->close();
?>