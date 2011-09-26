<script>
	function del_profile(student_id){
		var answer = confirm ("คุณต้องการลบข้อมูลนักเรียนหรือไม่ ?");
		if (answer){
			window.location = "index.php?cmd=del_profile&&student_id="+student_id;
		}
	}
</script>

<?php 

if($userdata['data']['student_type'] != "admin"){
	js_redirect("index.php");
}

$page = (int) get_param("page","get",1);
$perpage = 20;
$page = ($page - 1) ;

$limit = "limit {$page},$perpage";

$sql = "select * from student where student_type  ='student' {$limit}";
$result = mysql_query($sql,$link);

?>
<div class="add_student">
<a href="index.php?cmd=register">Add student</a>
</div>
<table>
	<tr>
		<th>รหัสนักเรียน</th>
		<th>ชื่อ</th>
		<th>นามสกุล</th>
		<th>ลงทะเบียนเมื่อ</th>
		<th>แก้ไข</th>
	</tr>
<?
$count_row = 0;
while($row = mysql_fetch_array($result)){
$count_row++;
?>
	<tr class="<? if($count_row%2 == 0) echo "odd"; else echo "add"; ?>">
		<td><?=$row['student_id'];?></td>
		<td><?=$row['student_name'];?></td>
		<td><?=$row['student_lastname'];?></td>
		<td><?=$row['register_date'];?></td>
		<td><a href="index.php?cmd=edit_profile&&student_id=<?=$row['student_id'];?>">edit</a>
		<a href="javascript:del_profile(<?=$row['student_id'];?>);">Del</a></td>
	</tr>
<?
}
?>
</table>
<ul class="list_page">
<li>
หน้า
</li>
<?

$sql = "select count(student_id) as num from student where student_type  ='student'";
$result = mysql_query($sql,$link);
while($row = mysql_fetch_array($result)){
	$num = $row['num'];
	$all_page = ceil($num/$perpage);
	$curent_page = $page + 1;
}

for($i=1;$i<=$all_page;$i++){

	if($i == $curent_page){
		?>
		<li>
		<span>
			<?=$curent_page?>
		</span>
		</li>
		<?
	}else{
		?>
		<li>
		<a href="?cmd=user_list&page=<?=$i?>">
			<?=$i?>
		</a>
		</li>
		<?
	}

}
?>
<li class="page">
จาก <?=$all_page?>
</li>
</ul>



