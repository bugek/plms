<div class="right_panel">
		<div class="content_inner">	
		<?
		if($userdata){
			js_redirect("index.php?cmd=show_course");
		}else{
			include 'login.php';
		?>
		<!--a class="guest_login" href="index.php?cmd=login_guest">Guest</a-->
		<!--a class="guest_register" href="?cmd=register">ลงทะเบียนเรียน</a-->
		<?
		}
		?>

		</div>
</div>
<div class="clear"></div>