<?
$student_id = (int) get_param("student_id","post");

$sql="select count(*) as num from student where student_id = '{$student_id}'";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){
	if($row['num'] == 0){
		echo "ok";
	}else{
		echo "false";
	}
}
