<?

$class_id = (int) get_param("class_id","post");
if($class_id <= 9){
$sql="
		SELECT * FROM `organizations_class`,category 
			WHERE organizations_class.class = {$class_id} and organizations_class.cate_id = category.cate_id group by organizations_class.cate_id order by category.cate_id asc 
		";
		$result = mysql_query($sql);
}else{
$sql="
		SELECT * FROM `organizations_class`,category 
			WHERE organizations_class.class > 9 and organizations_class.cate_id = category.cate_id group by organizations_class.cate_id order by category.cate_id asc 
		";
		$result = mysql_query($sql);
}

?>
    <h2>วิชา</h2>
	<ul>
<?
		while($row = mysql_fetch_array($result)){
?>
	<li onclick="get_course_list('<?=$row['class']?>','<?=$row['cate_id']?>',this)"><?=$row['cate_name']?></li>
<?
		}
?>
	</ul>
