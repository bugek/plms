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
	<ul>
	<li class="head_level"><img src="images/h-level.gif" /></li>
<?
		while($row = mysql_fetch_array($result)){
?>
		<li class="class_level"><a href="?cmd=show_course&class=<?=$row['class']?>"><span>(<?=$row['num']?>)</span><img src="images/level-<?=$row['class']?>.gif" /></a></li>
<?
		}
?>
	</ul>
	<br clear="all" />
</div>