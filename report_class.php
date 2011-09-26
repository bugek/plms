<?
$sql="
		SELECT 
		organizations_class.class
		,count(class) as num
		FROM `organizations_class`
		LEFT JOIN organizations ON organizations_class.organizations_id = organizations.organizations_id
		group by class
		";
		$result = mysql_query($sql,$link);
?>
<div class="class_list">
	<h1>
	ตารางแสดงจำนวนวิชาในแต่ละชั้นเรียน
	</h1>
	<table width="98%" border="1">
		<tr>
			<th>
				ชั้นเรียน
			</th>
			<th>
				จำนวนวิชา
			</th>
		</tr>


<?
		while($row = mysql_fetch_array($result)){
?>
		<tr>
			<td>
				<a href="?cmd=report_course&class=<?=$row['class']?>">ป. <?=$row['class']?></a>
			</td>
			<td>
				<a href="?cmd=report_course&class=<?=$row['class']?>"><?=$row['num']?></a>
			</td>
		</tr>
<?
		}
?>
	</table>
</div>