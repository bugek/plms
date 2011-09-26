<table width="100%">
<tr>
	<th width="80%" align="left">
		
	</th>
	<th width="20%" align="left">
		คะแนน
	</th>
</tr>
<?
if(get_param("sco_id","post")){
	$sco_id = get_param("sco_id","post");
	$sql="SELECT * FROM  sco_data Where sco_data.sco_id = ".$sco_id;
	$result = mysql_query($sql,$link);
	$score = "";
	while($row = mysql_fetch_array($result)){
		$arr_item = unserialize($row['score_item']);
		$score = $row['score'];
		$arr_item_id_array = array();
		foreach($arr_item as $item_id=>$score_item){
			$arr_item_id_array[] = "item_id = ".$item_id;
		}
		$sql = "select * from items where ".implode(" or ",$arr_item_id_array)." order by item_id";
		$result_item = mysql_query($sql,$link);
		while($row_item = @mysql_fetch_array($result_item)){
			?>
<tr>
	<td>
		<?=$row_item['title_uk']?>
	</td>
	<td>
		<?=(int) $arr_item[$row_item['item_id']]?>%
	</td>
</tr>
			<?
		}
	}

}else{
 echo "ส่งข้อมูลไม่ถูกต้อง";
}
?>
<tr>
	<td>
		เฉลี่ย
	</td>
	<td>
		<?=(int) $score?>%
	</td>
</tr>
<table>