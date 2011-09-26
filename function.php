<?
session_start();
require "config.php";
// connect to the database
global $link;
$link = mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname,$link);
mysql_set_charset('utf8',$link); 
$param = array();
$count_param = 0;

function get_param($param_name,$type="get",$value = false){
	if($count_param == 0){
	
		foreach($_GET as $key=>$val){
			$param['get'][$key] = $val;
		}
		
		foreach($_POST as $key=>$val){
			$param['post'][$key] = $val;
		}
		
		$count_param = count($param);
		
	}
	
	if(isset($param[$type][$param_name])){
		return $param[$type][$param_name];
	}else{
		return $value;
	}
	
}

function login($user,$pass){
	global $link,$keypass;
	mysql_query("update student set lastlogin_date = NOW() where student_id='{$user}'",$link);
	$sql = "select * from student where student_id='{$user}' and student_password='".md5($pass.$keypass)."' limit 1";
	$result = mysql_query($sql,$link);
	while($row = mysql_fetch_array($result)){
		$_SESSION["user_name"] = $row['student_name'];
		$_SESSION["user_data"] = $row;
		return true;
	}
	return false;
}

function get_userdata(){
	if(isset($_SESSION["user_name"])){
		$array_user = array();
		$array_user['user_name'] = $_SESSION["user_name"];
		$array_user['data'] = $_SESSION["user_data"];
		return $array_user;
	}else{
		return false;
	}
}

function js_redirect($url){
	echo "<script langquage='javascript'>
window.location='{$url}';
</script>";
}

function cur_page_url() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }

 return $pageURL;
}
function is_private_ip($str_ip){
	$arr_ip = split("\.",$str_ip);
	if(($arr_ip[0] == 10 && $arr_ip[1]  >= 0 && $arr_ip[1]  <= 255 && $arr_ip[2] >= 0 && $arr_ip[2] <= 255 && $arr_ip[3] >= 0 && $arr_ip[3] <= 255) 
	|| ($arr_ip[0] == 172 && $arr_ip[1]  >= 16 && $arr_ip[1]  <= 31 && $arr_ip[2] >= 0 && $arr_ip[2] <= 255 && $arr_ip[3] >= 0 && $arr_ip[3] <= 255) 
	|| ($arr_ip[0] == 192 && $arr_ip[1]  == 168 && $arr_ip[2]  >= 0 && $arr_ip[2] <= 255 && $arr_ip[3] >= 0 && $arr_ip[3] <= 255) 
	|| ($arr_ip[0] == 169 && $arr_ip[1]  == 254 && $arr_ip[2] >= 0 && $arr_ip[2] <= 255 && $arr_ip[3] >= 0 && $arr_ip[3] <= 255)){
		return true;
	}
	return false;
}

function get_local_ip(){
	$output = shell_exec('ipconfig | findstr /r "IP.*Address."');
	preg_match_all('/IP.*Address.*:.(([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3}))/i', $output, $ips);
	$local_ip = array();
	foreach($ips[1] as $ip){
		if(is_private_ip($ip)){
			$local_ip[] = $ip;
			
		}
	}
	
	return $local_ip;
}

function is_student(){
	if(isset($_SERVER['REMOTE_ADDR'])){
	//	if($_SERVER['REMOTE_ADDR'] == "::1" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"  ){
		if($_SERVER['SERVER_NAME'] == "localhost"){
			return false;
		} else  { 
			return true;
		}
		$server_ip = get_local_ip();
			foreach($server_ip as $ip){
				if($ip == $_SERVER['REMOTE_ADDR']){
				return false;
			}
		}
	}
return true;
}


/*function is_student(){
	if(isset($_SERVER['REMOTE_ADDR'])){
		echo " REMOTE ADDRESS".$_SERVER['REMOTE_ADDR'];
		if($_SERVER['REMOTE_ADDR'] == "::1"){
			return false;
		}
		$server_ip = get_local_ip();
		print_r($server_ip);
		foreach($server_ip as $ip){
			//if($ip == $_SERVER['REMOTE_ADDR']){
			echo $_SERVER["SERVER_NAME"];
			if( $_SERVER["SERVER_NAME"] == "localhost" ){
				return false;
			}
		}
	}
	return true;
}*/
