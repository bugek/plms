<?
include 'function.php';
$userdata = get_userdata();
$class_page = get_param("cmd","get","all");
switch (get_param("cmd")) {
	case "report_course_detail_ajax":
		include 'report_course_detail_ajax.php';
		break;
	case "update_lesson_graduate":
		include 'update_lesson_graduate_ajax.php';
		break;
	case "show_subject":
		include 'show_subject_ajax.php';
		break;
	case "show_course":
		include 'show_course_ajax.php';
		break;
	case "get_subject_unit":
		include 'get_subject_unit.php';
		break;
	case "sub_score":
		include 'sub_score_ajax.php';
		break;
	case "save_location":
		include 'save_location_ajax.php';
		break;
	case "report_user":
		include 'report_user_ajax.php';
		break;
	case "register":
		include 'register_ajax.php';
		break;
	case "save_theme":
		include 'save_theme_ajax.php';
		break;
	case "check_student_id":
		include 'check_student_id_ajax.php';
		break;
	default:
		include 'home.php';
}

?>