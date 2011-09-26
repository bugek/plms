<?
include 'function.php';
$userdata = get_userdata();
$head_txt = "";
$left_menu = array();
$class_page = get_param("cmd","get","all");

 if($cd_type != 1){
	if(isset($userdata['data']['student_id'])){
		if($userdata['data']['student_id'] != 2 && $userdata['data']['student_id'] != 1 && $class_page != "login_admin"){
			$cd_type = 3;
		}
	}else if(is_student()){
		$cd_type = 3;
	}else{
		$cd_type = 2;
	} 
 }

$theme_id = 3;

if(isset($_COOKIE["theme_id"])){
	$theme_id = (int) $_COOKIE["theme_id"];
}


switch($cd_type){
	case 1: $theme_id = 3; break;
	case 2: $theme_id = 2; break;
	case 3: $theme_id = 1; break;
}

if($theme_id){
	switch(@$theme_id){
		case 1: $theme = 'ui-lightness'; break;
		case 2: $theme = 'redmond'; break;
		case 3: $theme = 'cupertino'; break;
	}
}else{
	$theme = 'ui-lightness';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" dir="ltr"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>
<?
if($cd_type == 1){
echo "Smart L.O. Standalone";
}else if($cd_type == 2){
echo "Smart L.O. Teacher";
}else if($cd_type == 3){
echo "Smart L.O. Lite";
}
?>
</title> 
  <!--[if IE]><script language="javascript" type="text/javascript" src="js/excanvas.js"></script><![endif]-->  
  <link rel="stylesheet" type="text/css" href="css/jquery.jqplot.css" />
  <link rel="stylesheet" href="css/jquery.treeview.css" />
  
    <!-- BEGIN: load css for user management  
	;require  jquery-ui theme css 
    ;require  jqGrid css
	;require  custom css
  -->
 	<link rel="stylesheet" href="users/css/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<!-- <link rel="stylesheet" href="users/css/site.css" type="text/css" media="screen" title="no title" charset="utf-8"/>-->
	<link id="user-management-theme" rel="stylesheet" href="users/css/<?php echo $theme; ?>/jquery-ui-1.8.12.custom.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	<link rel="stylesheet" href="users/css/ui.jqgrid.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
 
  <!-- BEGIN: load jquery -->

  <script language="javascript" type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script src="js/jquery.cookie.js" type="text/javascript"></script>
	<script src="js/jquery.treeview.js" type="text/javascript"></script>
  <!-- END: load jquery -->
 
  <script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.8.1.custom.min.js"></script>
	<script type="text/javascript" src="js/highcharts.js"></script>
	<script type="text/javascript" src="js/modules/exporting.js"></script>
	<script type="text/javascript" src="js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script type="text/javascript" src="js/tablesorter.pager.js"></script>
  
  
  <!-- BEGIN: load jqplot -->
	<script language="javascript" type="text/javascript" src="js/jquery.jqplot.js"></script>
	<script language="javascript" type="text/javascript" src="js/plugins/jqplot.pieRenderer.js"></script>
	<script type="text/javascript" src="js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
	<script type="text/javascript" src="js/plugins/jqplot.barRenderer.min.js"></script>
  <!-- END: load jqplot -->
  <!-- BEGIN: load js file for user management -->
	
	<script src="users/js/i18n/grid.locale-en.js" type="text/javascript" charset="utf-8"></script>
	<script src="users/js/jquery.jqGrid.min.js" type="text/javascript" charset="utf-8"></script>
  <!-- BEGIN: load js file for user management -->
 <style type="text/css" media="all">
  @import url("css/site.css");
 </style>
 <link rel="stylesheet" href="css/table/blue/style.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
 <?
 if($theme_id == 1){
 ?>
 <link id="css_theme" href="theme/theme1/style.css" type="text/css" rel="stylesheet">
 <link id="css_ui" type="text/css" href="js/jquery-ui/css/ui-lightness/jquery-ui-1.8.1.custom.css" rel="Stylesheet" />	
 <?
 }else if($theme_id == 2){
 ?>
 <link id="css_theme" href="theme/theme2/style.css" type="text/css" rel="stylesheet">
 <link id="css_ui" type="text/css" href="js/jquery-ui/css/start/jquery-ui-1.8.12.custom.css" rel="Stylesheet" />	
 <?
  }else{
 ?>
 <link id="css_theme" href="theme/theme3/style.css" type="text/css" rel="stylesheet">
 <link id="css_ui" type="text/css" href="js/jquery-ui/css/start/jquery-ui-1.8.12.custom.css" rel="Stylesheet" />	
 <?
 }
 ?>

</head> 
 
<body class="<?=$class_page?>_main"> 
<div id="content_main">
	<div id="header">
			<div class="inner">
				<?
					switch (get_param("cmd","get","all")) {
					case "show_course":
						$head_txt = "สารบัญ";
						break;
					case "play_course":
						$head_txt = "เนื้อหา";
						break;			
					case "all":
						$head_txt = "Welcome";
						break;
					case "report_user":
					case "report_course_detail":
						$head_txt = "รายงาน";
						break;
						case "user_management":
						$head_txt = "จัดการนักเรียน";
						break;
					case "login_admin":
					case "login":
						$head_txt = "Welcome";
						break;
					case "edit_profile":
					case "display_profile":
						$head_txt = "ข้อมูลส่วนตัว";
						break;
					}
					include 'user_bar.php';
				?>
			</div>
	</div>
	<div id="content" class="<?=$class_page?>">

		<?

			switch (get_param("cmd")) {
				case "register":
					include 'register.php';
					break;
				case "edit_profile":
					include 'register.php';
					break;
				case "login":
					include 'home.php';
					break;						
				case "login_admin":
					include 'login_admin.php';
					break;				
				case "login_guest":
					include 'login_guest.php';
					break;
				case "logout":
					include 'logout.php';
					break;
				case "home":
					include 'home.php';
					break;
				case "show_class":
					include 'show_class.php';
					break;
				case "del_profile":
					include 'del_profile.php';
					break;									
				case "show_course":
					include 'show_course.php';
					break;
				case "play_course":
					include 'play_course.php';
					break;
				case "display_profile":
					include 'display_profile.php';
					break;						
				case "user_list":
					include 'user_list.php';
					//include 'users/index.php';
			        $menu_path = array(
			 			array("class"=>"first","title"=>"จัดการนักเรียน","link"=>"index.php?cmd=user_list")
					);
					break;
				case "user_management":
					include 'users/index.php';
					/*$menu_path = array(
			 			array("class"=>"first","title"=>"จัดการนักเรียน","link"=>"index.php?cmd=user_management")
					);*/
				//default:
					break;
				case "report_class":
					include 'report_class.php';
					break;
				case "report_course":
					include 'report_course.php';
					break;
				case "report_course_detail":
					include 'report_course_detail.php';
					$menu_path = array(
						array("class"=>"first","title"=>"ดูรายงาน","link"=>"index.php?cmd=report_course_detail")
					);
					break;
				case "report_user":
					include 'report_user.php';
					break;
				default:
					if($cd_type == 1){
						include 'login_guest.php';
					}else{
						include 'home.php';
					}
			}
		?>
	<div class="clear"></div>
	</div>
	<div id="footer">
		<img src="images/playable_logo_small.png" /> ลิขสิทธิ์โดย บริษัท เพลย์เอเบิล จำกัด
<?
	if($cd_type == 2){
		$local_ip = get_local_ip();
		if(count($local_ip)>0){
?>
<strong>
Server IP:
</strong>
<span class="local_ip">
<?
	echo implode(",",$local_ip);
?>
</span>
<div id="server-ip" title="Server IP" align="center">
<br />
<strong>
<?
	echo implode(",",$local_ip);
?>
</strong>
</div>
<?
		}
	}
		
?>
	</div>
	<div class="footer_line">
	&nbsp;
	</div>
<?
	if(isset($menu_path)){
?>	
	<!--div class="over_lay">
		<?
			include 'menu_path.php';
		?>
	</div-->
<?
	}
?>
</div>

</body>
<script>
	function get_subject_list(class_id,obj){
		if(obj !=""){
			$(".menu_path a").removeClass("obj_selected");
			$(obj).addClass("obj_selected");
		}
		$.post("index_ajax.php?cmd=show_subject", { class_id: class_id},
		function(data) {
			$(".subject_list").html(data);
		});
		$(".course_list").html("<h2>หน่วยการเรียนรู้</h2><ul class='ul_list'><li>กรุณาเลือกรายวิชา</li></ul>");
		
	}
	function get_course_list(class_id,cate_id,obj){
		$(".subject_list li").removeClass("obj_selected");
		$(obj).addClass("obj_selected");
		$.post("index_ajax.php?cmd=show_course", { class_id: class_id,cate_id: cate_id},
		function(data) {
			$(".course_list").html(data);
		});
	}
<?
	if($cd_type == 2){
		$local_ip = get_local_ip();
		if(count($local_ip)>0){
?>
	function server_ip(){
		$( "#server-ip" ).dialog( "open" );
	}
	$( "#server-ip" ).dialog({
		modal: true,
		autoOpen: false,
		width: 600,
		buttons: {
			"ปิด": function() {
				$(this).dialog( "close" );
			}
		}
	});
<?
		}
	}
		
?>
</script>
</html> 