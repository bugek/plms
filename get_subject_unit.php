<option value="0">ทั้งหมด</option>
<?
$class_id = (int) get_param("subject_class","post");
$cate_id = (int) get_param("cate_id","post");

$sql_where = array();;

if($cate_id != 0){
	$sql_where[] = "organizations_class.cate_id = {$cate_id}";
}
if($class_id != 0){
	$sql_where[] = "organizations_class.class = {$class_id}";
}

if(count($sql_where) > 0){
	$sql_where = " where ".implode(" and ",$sql_where);
}else{
	$sql_where = "";
}
$sql="
		SELECT *
		FROM `organizations_class`
		LEFT JOIN organizations ON organizations_class.organizations_id = organizations.organizations_id
		{$sql_where}
		order by organizations.author asc
		";
		$result = mysql_query($sql);

		while($row = mysql_fetch_array($result)){

?>
		<option value="<?=$row['organizations_id']?>"><?=$row['title_bg']?></option>
<?
		}
?>

