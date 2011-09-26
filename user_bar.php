<div class="user_info">
<?
if($head_txt != ""){
?>
<div class="head_text">
	<?=$head_txt;?>
</div>
<?
}
if($userdata && $cd_type != "1"){
?>
<ul class="top_menu">
	<li class="first">
		<a href="index.php?cmd=show_course">สารบัญ</a>
	</li>
	<? 
		if($userdata['data']['student_id'] != 2 && $userdata['data']['student_id'] != 1){
	?>
	<li>
		<a href="index.php?cmd=display_profile">ข้อมูลส่วนตัว</a>
	</li>
	<?
		}
	?>
	<? 
		if($userdata['data']['student_type'] == "admin"){
	?>
	<li>
		<a href="index.php?cmd=report_course_detail">ดูรายงาน</a>
	</li>
	<li>
		<a href="index.php?cmd=user_management">จัดการนักเรียน</a>
	</li>

	<?
		}else{
		?>
	<li>
		<span class="">
		<? if($userdata['data']['student_name']=="guest"){
				echo "ทดลองเรียน";
			}else{
				//$userdata['data']['student_name'];
				echo "<a href='index.php?cmd=report_user'>รายงาน</a>";
			} 
		?>
		</span>
	</li>	
		<?
		}
	?>
	<!--li>
		<span>
			เลือกธีม 
			<select id="select_theme">
				<option <?  if($theme_id == 1) echo "selected='true'";?>value="1">
					สีส้ม
				</option>
				<option <?  if($theme_id == 2) echo "selected='true'";?>value="2">
					สีน้ำเงิน
				</option>
				<option <?  if($theme_id == 3) echo "selected='true'";?>value="3">
					สีน้ำเงินอ่อน
				</option>
			</select>
			
		<script>
				$(document).ready(function(){
					$('#select_theme').change(function() {
						if($(this).val() == 1){
							$("#css_theme").attr("href","theme/theme1/style.css");
							$("#css_ui").attr("href","js/jquery-ui/css/ui-lightness/jquery-ui-1.8.1.custom.css");
							
						}else if($(this).val() == 2){
							$("#css_theme").attr("href","theme/theme2/style.css");
							$("#css_ui").attr("href","users/css/redmond/jquery-ui-1.8.12.custom.css");
							
						}else{
							$("#css_theme").attr("href","theme/theme3/style.css");
							$("#css_ui").attr("href","users/css/cupertino/jquery-ui-1.8.12.custom.css");
							
						}
						data_post = {theme_id: $(this).val()};
						$.post("/sc/index_ajax.php?cmd=save_theme", data_post,
						function(data) {
							//alert(data);
						});
					});
				});
		</script>

		</span>
	</li-->
	<li class="last">
		<a href="index.php?cmd=logout">ออกจากระบบ</a>
	</li>
</ul>
<?
	$student_id = $userdata['data']['student_id'];
	$item_id = (int) get_param("item_id");
	
	if(get_param("cmd") == "play_course" && $student_id != 1 && $student_id != 2){
		$course_id = (int) get_param("course");
		$sql="SELECT * FROM  sco_data
					Where sco_data.student_id = ".$student_id." and sco_data.organizations_id = {$course_id}
				";
		$result = mysql_query($sql);
		$ps = 0;
		while($row = mysql_fetch_array($result)){
			$ps = $row['lesson_graduate_ps'];
			if($row['last_location'] !="" && $item_id == 0){
				echo "<script>window.location='{$row['last_location']}';</script>";
			}
		}
	}
}else if($cd_type != "1"){
?>
<!--ul class="top_menu">
	<li class="first">
		<a href="index.php">หน้าแรก</a>
	</li>
	<li class="last">
		<span>
			เลือกธีม 
			<select id="select_theme">
				<option <?  if($theme_id == 1) echo "selected='true'";?>value="1">
					สีส้ม
				</option>
				<option <?  if($theme_id == 2) echo "selected='true'";?>value="2">
					สีน้ำเงิน
				</option>
				<option <?  if($theme_id == 3) echo "selected='true'";?>value="3">
					สีน้ำเงินอ่อน
				</option>
			</select>
			
		<script>
				$(document).ready(function(){
					$('#select_theme').change(function() {
						if($(this).val() == 1){
							$("#css_theme").attr("href","theme/theme1/style.css");
							$("#css_ui").attr("href","js/jquery-ui/css/ui-lightness/jquery-ui-1.8.1.custom.css");
						}else if($(this).val() == 2){
							$("#css_theme").attr("href","theme/theme2/style.css");
							$("#css_ui").attr("href","js/jquery-ui/css/start/jquery-ui-1.8.12.custom.css");
						}else{
							$("#css_theme").attr("href","theme/theme3/style.css");
							$("#css_ui").attr("href","js/jquery-ui/css/start/jquery-ui-1.8.12.custom.css");
						}
						data_post = {theme_id: $(this).val()};
						$.post("/sc/index_ajax.php?cmd=save_theme", data_post,
						function(data) {
							//alert(data);
						});
					});
				});
		</script>

		</span>
	</li>
</ul-->
<?
}
if($cd_type == 1){
?>
<ul class="top_menu">
	<li class="first">
		<a href="index.php?cmd=show_course">สารบัญ</a>
</ul>
<?
}
?>
</div>