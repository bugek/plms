<?php // every page needs to start with these basic things

// I'm using a separate config file. so pull in those config values
require("php_mysql_class/config.inc.php");

// pull in the file with the database class
require("php_mysql_class/Database.singleton.php");

// create the $db singleton object

class User
{	
 	const PREFIX_BOY = "ด.ช.";
	const PREFIX_GIRL = "ด.ญ.";
	const PREFIX_MEN = "นาย";
	const PREFIX_WOMEN = "นางสาว";

	var $debug = false;
	var $debug_trace = "<br/>===========================================================================
						:debug v";
	var $db;
    var $tbl_name = "student";
	var $error = "";

	public function __construct()
	{
		//nothing
		
		$this->db = Database::obtain(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);
		$this->db->connect();

	}

	public static function get_prefix(){
		return array(self::PREFIX_BOY, self::PREFIX_GIRL, self::PREFIX_MEN ,self::PREFIX_WOMEN);
	}

	public function validates($var = array(),$field,$param = array()) 	
	{
		
		/********************************************************************
		*
		* $data = $student , $fieldname , array( "presence" => true,
		*								"format" => "pattern", 
		*								"length" => 100, 
		*								"uniqueness" => true,
		*								"inclusion" => [1 , 2, 3 , 4],
		*								"exclusion" => [1 , 2, 3 , 4],
		*								"numericality" => true );
		*
		********************************************************************/
		
		$ret = false; 
		$count = 1 ;

		/*if($this->debug){
			$this->debug_trace .=  "start $var = ".$var."<br/>";
			$this->debug_trace .=  "     $field= ".$field."<br/>";
			$this->debug_trace .=  "    $param = ".var_dump($param)."<br/>";
			$this->debug_trace .=  "<hr/>";
		}*/
	
		foreach($param as $key => $value){
		
			if($this->debug) 
			{
				$this->debug_trace .=  "<br/>count = ".$count++." and key = ".$key."  ".$field;
			}
			switch($key){
				case "numericality":
					if ($param["numericality"] == true )
				   		$ret = $this->numericality($var[$field]);
						break;
				case "presence" : 
					$ret =  $this->presence($var[$field]);
					break;
				case "format" : 
					$ret = $this->format($var[$field],$value);
					break;
				case "length" : 
					$ret = $this->length($var[$field],$value);
					break;
				case "uniqueness" : 
					if ($param["uniqueness"] == true ) {
				   		$ret = $this->uniqueness($field , $var[$field]);
					}
						break;
				case "inclusion" : 
					$ret = $this->inclusion($var[$field],$value);
			    	break;
				case "exclusion" :
					$ret = $this->exclusion($var[$field],$value);
					break;
				default :
					$this->debug_trace .= "<br/>error in method of validation";
					return false;
					break;
				}
		
			if ($ret===false) {
				if($this->debug)
					$this->debug_trace .=  "<br/>** ".$field ." fail at " .$key."<br/>";
				
				$this->error = "ข้อมูลผิดพลาด ".$field." - " . $var[$field]."  - ".$key ;
				return false;
			}
		
		}
	
		return $ret;
   	}
	
	private function numericality($value) 
	{
	
		if ($this->debug) {
			$this->debug_trace .=  "<br/>in numericality function with parameter " .$value ; 
		}
	    
		if (is_numeric($value) || is_int($value))
			return true;
		else
			return false;
		}

	private function length($data,$value = array())
	{
		if ($this->debug) {
		 	$this->debug_trace .=  "<br/>in length function with parameter " . $data ." and length = ". strlen($data); 
		}
	
 	
		@$minimum = $value['min'];
		@$maximum = $value['max'];
		$length = strlen($data);
	
		if(isset($minimum) && isset($maximum)){
			if (($length <= $maximum) && ($length >= $minimum)){
				return true;
			}
			else
				return false;
			
		}else if (isset($minimum) && !isset($maximum)){
			if ($length < $minimum)
				return false;
			else 
				return true;
		}else if (!isset($minimum) && isset($maximum)){
			if ($length > $maximum)
				return false;
			else
				return true;
		}else {
			return false;
		}
	}

	private function uniqueness($field,$value)
	{
		if ($this->debug) {
			$this->debug_trace .=  "<br/>in uniqueness function with parameter " . $field. "-".$value ; 
		}
	
		if ($this->is_exists($field,$value,$this->db))
			return false;
		else 
			return true;
	
	}
	
	private function presence($value)
	{
		if ($this->debug) {
			$this->debug_trace .=  "<br/>in presence function with parameter " .$value ; 
		}
		if (empty($value))
			return false;
		else
			return true;
	}

	private function format($data,$pattern)
	{
		if ($this->debug) {
			$this->debug_trace .=  "<br/>in format function with parameter " . $data . "-".$pattern ; 
		}
	
		if (preg_match($pattern,$data)) 
			return true;
		else
			return false;
	
	}

	private function inclusion($field,$array = array())
	{
		
		if ($this->debug) {
			$this->debug_trace .=  "<br/>in inclusion function with parameter " . $field ; 
		}
		if (in_array($field,$array)) {
			return true;
		}
		else {
			//echo "<br/>".$field."<br/>hey";
			return false;
				
		}
	}

	private function exclusion($field,$array = array()){
		if ($this->debug) {
			$this->debug_trace .=  "<br/>in exclusion function with parameter " . $field  ; 
		}
		if (in_array($field,$array)) 
			return false;
		else 
			return true;
	}

	private function is_exists($field,$value,$DB)
	{
		if ($this->debug) {
			$this->debug_trace .=  "<br/>in is_exists function with parameter " . $field . "-" .$value ;
		}
		/*if(is_string($value)) 
	   		$v = "'".$this->db->escape("$value")."'";
		else if (is_int($value))
	   		$v = $value;
		else 
	   		return false;
    	*/

		$sql="SELECT `".$field."` FROM `student` WHERE $field=".$value;
    	$row = $this->db->query_first($sql);
        
    	// if user exists
    	if(!empty($row['student_id'])) 
        	return true;
	
    	else 
        	return false;
		
	}
	
	
	public function save($user_data)
	{
	
		$this->debug_trace = "";
		foreach( $user_data as $key => $value){
			if($key == 'student_password')
			 $user_data[$key] = $this->encrypt_password($value,KEYPASS);
		}

		$pri_id = $this->db->insert($this->tbl_name , $user_data);
		//echo $pri_id;
		if(($pri_id !== false) && ($pri_id >= 0 )){
			if($this->debug) 
				$this->debug_trace .= "<br/>record saved.";
			
			return true;
		} else {
				$this->debug_trace .= "<br/>record could not be saved";
			return false;
		}

	}	
	
	public function find(){
		
	}
	
	public function reset_password($student_id,$password){
		if($student_id != 1) {
			$data['student_password'] = $this->encrypt_password($password, KEYPASS);
			$link_id = $this->db->update('student',$data,"student_id = ".(int)$student_id);
			if ($this->db->affected_rows == 1) {
				if($this->debug) 
					$this->debug_trace .= "<br/>complete.";
			
				return true;
			} else {
				$this->debug_trace .= "<br/>fail.";
				return false;
		}
		}
	}
	
	public function destroy($student_id){
		//check is exists
		//check is not admin
		if($student_id != 1) {
			$sql = "DELETE FROM student WHERE student_id=".$student_id;
			$this->db->query($sql);
			if ($this->db->affected_rows == 1) {
				if($this->debug) 
				$this->debug_trace .= "<br/>record deleted.";
			
				return true;
			} else {
				$this->debug_trace .= "<br/>cloud not delete record";
				return false;
			}
		}  
		return false;
	}
	
	public function update($user_data,$old_id){
		$this->debug_trace = "";
		foreach( $user_data as $key => $value){
			if($key == 'student_password')
			 $user_data[$key] = $this->encrypt_password($value,KEYPASS);
		}

		$link_id = $this->db->update($this->tbl_name , $user_data, "student_id=".(int)$old_id);
		//echo $pri_id;
		//echo "affected_rows = ".$this->db->affected_rows;
		if ($this->db->affected_rows == 1) {
			if($this->debug) 
				$this->debug_trace .= "<br/>record saved.";
			
			return true;
		} else {
				$this->debug_trace .= "<br/>record could not be saved";
			return false;
		}
	}
	
	private function encrypt_password($pass,$key){
		return md5($pass.$key);
	}
	
	public function show_debug($bool)
	{
		$this->debug = $bool;
	}

	public function debug_flush(){
		
		if($this->debug){
			$this->debug_trace .= "<br/>============================================================================" ;
			echo $this->debug_trace;
			echo "hello";
		}
	}
}
?>
