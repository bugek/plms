<?
	if(login(2,"guest")){
		js_redirect("index.php?cmd=show_course");
		die("");
	}else{
		echo "Guest login fail";
	}
?>