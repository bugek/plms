<?
$course_id = (int) get_param("course");
$item_id = get_param("item_id") * 1;

$first_link = "";

$sql="SELECT items. * , link_bg,manifests.author as dir
FROM items
LEFT JOIN items_resources ON ( items.item_id = items_resources.item_id )
LEFT JOIN resources ON ( items_resources.resource_id = resources.resource_id )
LEFT JOIN organizations ON ( items.organizations_id = organizations.organizations_id )
LEFT JOIN manifests ON ( organizations.manifests_id  = manifests.manifests_id )
WHERE items.organizations_id ={$course_id}";

$result = mysql_query($sql);
$arr_sub_course = array();
$parent_id = 0;
while($row = mysql_fetch_array($result)){
	if($row['parent_id'] == 0){
		$arr_sub_course[$row['item_id']]['title'] = $row['title_uk'];
		$arr_sub_course[$row['item_id']]['dir'] = $row['dir'];
		$parent_id = $row['item_id'];
	}else{
		$arr_sub_course[$parent_id]['data'][$row['item_id']]['title'] = $row['title_uk'];
		$arr_sub_course[$parent_id]['data'][$row['item_id']]['link_bg'] = $row['link_bg'];
		$arr_sub_course[$parent_id]['data'][$row['item_id']]['dir'] = $row['dir'];
	}
	
	if(($first_link == "" && $row['link_bg'] != "") || $item_id == $row['item_id']){
		$first_link = $row['dir']."/".$row['link_bg'];
	}
	
}
		
?>
<script>

var curent_course = <?=$course_id;?>;
var current_item_id = <?=$item_id;?>;
var iframe_class_id = 1;

function open_course(playable,src,course,item_id){
	$("#frame_course #course").replaceWith('<h1 id="course_loading">Loading . . .  </h1>');
	setTimeout ( "load_course('"+src+"','"+course+"','"+item_id+"')", 3000 );
}

$(function() {
	$("#tree").treeview({
		collapsed: true,
		animated: "fast",
		control:"#sidetreecontrol",
		prerendered: true,
		persist: "location"
	});
})
	
</script>
<div class="content_left left">
	<div class="menu_path">
		<ul class="treeview" id="tree">
	<?
			$tree_count = count($arr_sub_course);
			$tree_number = 0;
			foreach($arr_sub_course as $k=>$value){
			$tree_number++;
	?>
			<li class="<?  if($tree_number==$tree_count && isset($value['data'])) 
									echo "lastCollapsable"; 
								elseif(isset($value['data']) && $tree_number != $tree_count) 
									echo "collapsable"; 
								elseif($tree_number==$tree_count && !isset($value['data'])) 
									echo "last"; 
							?>">
							<? 
							
								if(isset($value['data'])){ 
							
							?><div class="hitarea collapsable-hitarea <?  if($tree_number==$tree_count && isset($value['data'])) 
																echo "lastCollapsable-hitarea"; 
							?>"></div>
							<?
							}
							?>
							<span><?=$value['title']?></span>
	<?		
			if(isset($value['data'])){
				$sub_count = count($value['data']);
				$sub_number = 0;
?>
<ul>
<?
				foreach($value['data'] as $k_id=>$value_title){
					$sub_number++;
					$playable = "false";
					$pos = strpos("-".$value_title['dir'], "PLAYABLE");
					if($pos != FALSE){
						$playable = "true";
					}
					if($item_id == $k_id){
						$select_item = "obj_selected";
					}else{
						$select_item = "";
					}
					
	?>
				<li class="list_menu_link <? if($sub_number==$sub_count) echo"last"; echo $select_item; ?>"><a href="javascript:open_course(<?=$playable?>,'/sc/src/<?=$value_title['dir']?>/<?=$value_title['link_bg']?>',<?=$course_id?>,<?=$k_id?>);"><?=$value_title['title']?></a></li>
	<?
				}
?>
</ul>
<?
			}
	?>
			</li>
	<?
			}
	?>
		</ul>
	</div>
	<div class="menu_path_footer">
	&nbsp;
	</div>
</div>
<div class="bt_hidden" title="ปิดเมนู">
	ซ่อน
</div>
<div class="frame_play" id="frame_course">
<!--frameset id="frame_course" frameborder="0" framespacing="0" border="0" rows="50,*" cols="*" onbeforeunload="API.LMSFinish('');" onunload="API.LMSFinish('');">
    <!--iframe class="iframe_api" border="0" src="api.php?SCOInstanceID=<?=$userdata['data']['student_id']; ?>&course_id=<?=$course_id; ?>&item_id=<?=$item_id; ?>" name="API" noresize width="0" height="0"></iframe>
	<!--iframe border="0" src="/sc/src/<?=$first_link; ?>" name="course" id="course" width="804" height="604"></iframe-->
	<!--object id="course" type="text/html" data="http://localhost:8888/sc/src/<?=$first_link; ?>" style="width: 804px; height: 604px;"></object>
</frameset-->
</div>
<?
if($userdata && $cd_type != "1" && $cd_type != "2"){
?>

<div class="progress_bar">
	<ul>
		<li>
			แถบความก้าวหน้า
		</li>
		<li>
			<div class="bg_ps">
				<div class="curent_ps" style="width: <? echo ($ps/100)*166; ?>px;">&nbsp;</div>
			</div>
		</li>
		<li>
			<?=$ps?> %
		</li>
	</ul>
</div>
<?
}
?>
<div class="bt_close" alt="ออกจากบทเรียน" title="ออกจากบทเรียน">
	ออกจากบทเรียน
</div>
	
<script>
function init_course(){
	$("#frame_course #course_loading").replaceWith('<iframe border="0" src="src/<?=$first_link; ?>" name="course" id="course" width="804" height="604"></iframe>');
	$.post("index_ajax.php?cmd=update_lesson_graduate", {SCOInstanceID:<?=$userdata['data']['student_id']; ?>,course_id:<?=$course_id;?>,item_id:<?=$item_id;?>},
	function(data) {
		//window.onbeforeunload = finish_data;
		$(".progress_bar").html(data);
	});
}

function load_course(src,course,item_id){
	commit_data();
	$("#frame_course #course_loading").replaceWith('<iframe border="0" src="'+src+'" name="course" id="course" width="804" height="604"></iframe>');
	$.post("index_ajax.php?cmd=update_lesson_graduate", {SCOInstanceID:<?=$userdata['data']['student_id']; ?>,course_id:course,item_id:item_id},
	function(data) {
		curent_course = course;
		current_item_id = item_id;
		API.current_item_id = item_id;
		$(".progress_bar").html(data);
	});

}

function commit_data(){
	API.LMSCommit('');
}

function finish_data(){
	if(API.flagInitialized == true){
		API.LMSFinish('');
	}
}

jQuery(document).ready(function($) {

	$('<iframe class="iframe_api" border="0" src="api.php?SCOInstanceID=<?=$userdata['data']['student_id']; ?>&course_id=<?=$course_id; ?>&item_id=<?=$item_id; ?>" name="API" noresize width="0" height="0"></iframe>').appendTo(".menu_path_footer");
	$('<h1 id="course_loading">Loading . . .</h1>').appendTo("#frame_course");
	setTimeout ( "init_course()", 3000 );
	
	$('.bt_hidden').click(function() {
		API.LMSCommit('');
		if($(this).html() == "แสดง"){
			$(this).html("ซ่อน");
			$(this).attr("title","ปิดเมนู");
			$(this).removeClass("bt_show");
			$(".content_left").css({"display":"block"});
		}else{
			$(this).html("แสดง");
			$(this).attr("title","เปิดเมนู");
			$(this).addClass("bt_show");
			$(".content_left").css({"display":"none"});
		}
		
	});
	$('.list_menu_link').click(function() {
		$(".list_menu_link").removeClass("obj_selected");
		$(this).addClass("obj_selected");
	});
	$('.bt_close').click(function() {
		API.LMSCommit('');
		if(current_item_id != 0){
			$.post("index_ajax.php?cmd=save_location", {url: "/sc/index.php?cmd=play_course&course="+curent_course+"&item_id="+current_item_id,course:<?=(int) get_param("course");?>},
			function(data) {
				window.location='index.php';
			});
		}else{
			$.post("index_ajax.php?cmd=save_location", {url: "",course:<?=(int) get_param("course");?>},
			function(data) {
				window.location='index.php';
			});
		}
	});
});
</script>