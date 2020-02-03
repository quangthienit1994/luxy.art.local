<?php
if(!isset($_SESSION)){
	session_start();
}
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
ini_set('memory_limit', '-1');
?>

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Compe extends CI_Controller {

	public function __construct() {
		
		parent::__construct();
		
		@session_start();
		$this->load->library('session');
		$this->load->library('cart');
		
		$this->load->model('main_model');
		$this->load->model('function_model');
		$this->load->model('manager_compe_model');
		$this->load->model('manager_user_model');
		
		$dir = getcwd();
		define("DIR", $dir."/publics/");
		define("DIR_COMP_IMG", $dir."/publics/competition_img/");
		define("DIR_COMP_ICON", $dir."/publics/competition_icon/");
		
		//language message
		$CI =& get_instance();    //do this only once in this file
		$CI->config->load();
		
		if(!isset($_SESSION['lang'])){
			$_SESSION['lang'] = "ja";
		}
		$config['language'] = $_SESSION['lang'];
		$ngonngu = $config['language'];
		$this->lang->load('dich', $ngonngu);
		
		if($_SESSION['lang'] == "ja"){
			$this->lang_id = 1;
		}
		else if($_SESSION['lang'] == "vi"){
			$this->lang_id = 2;
		}
		else if($_SESSION['lang'] == "en"){
			$this->lang_id = 3;
		}
		
	}
	
	public function post_compe(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		$setting = $this->function_model->get_setting();
		$data['point_convert_money'] = $setting['point_convert_money'];
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		$user = $this->function_model->getUser($user_id);
		$data['user_level'] = $user->user_level;
		$data['user_point'] = $user->user_point;
		$data['user_paymoney_getpoint'] = $user->user_paymoney_getpoint;
		$data['user_email'] = $user->user_email;
		$data['user_fullname'] = $this->function_model->get_fullname($user->user_id, $this->lang_id);
		
		if(isset($_POST['btnPostCompe'])){
			
			//get data
			$title_com						= trim($_POST["title_com"]);
			//photo_des_com
			$photo_des_com 					= $_FILES['photo_des_com'];
			//icon_com
			$icon_com 						= $_FILES['icon_com'];
			$date_start_com					= $_POST["date_start_com"];
			$date_end_com					= $_POST["date_end_com"];
			$is_private						= isset($_POST["is_private"]) ? 1 : 0;
			$is_private_img_apply			= isset($_POST["is_private_img_apply"]) ? 1 : 0;
			$competition_time_agree			= $_POST["competition_time_agree"];
			$note_require_com				= trim($_POST["note_require_com"]);
			$note_description_com			= trim($_POST["note_description_com"]);
			$img_type_com					= $_POST["img_type_com"];
			$img_color_id					= trim($_POST["id_color"]);
			$note_img_purpose_com			= trim($_POST["note_img_purpose_com"]);
			$point_img_com					= $_POST["point_img_com"];
			$img_quantity_com				= $_POST["img_quantity_com"];
			$link_url_com					= trim($_POST["link_url_com"]);
			$note_another_des_com			= trim($_POST["note_another_des_com"]);
			
			$date_start_com = $this->function_model->convert_date("dd/mm/yyyy", "yyyy-mm-dd", $date_start_com);
			$date_end_com = $this->function_model->convert_date("dd/mm/yyyy", "yyyy-mm-dd", $date_end_com);
			
			$time = time();
			
			$photo_des_com_type = end(explode(".", $photo_des_com['name']));
			$icon_com_type = end(explode(".", $icon_com['name']));
			
			$photo_des_com_name = $time.".".$photo_des_com_type;
			$icon_com_name = $time.".".$icon_com_type;
			
			//upload photo_des_com
			if($photo_des_com['tmp_name'] != ""){
				move_uploaded_file($photo_des_com['tmp_name'], DIR_COMP_IMG.$photo_des_com_name);
			}
			
			//upload icon_com
			if($icon_com['tmp_name'] != ""){
				move_uploaded_file($icon_com['tmp_name'], DIR_COMP_ICON.$icon_com_name);
			}
			
			$point_img_com = str_replace(",", "", $point_img_com);
			$img_quantity_com = str_replace(",", "", $img_quantity_com);
			
			$dataInsertUserCompetition = array(
				'user_id' 						=> $user_id,
				'title_com' 					=> $title_com,
				'photo_des_com' 				=> $photo_des_com_name,
				'date_start_com' 				=> $date_start_com,
				'date_end_com' 					=> $date_end_com,
				'icon_com' 						=> $icon_com_name,
				'note_description_com' 			=> $note_description_com,
				'note_require_com' 				=> $note_require_com,
				'img_type_com' 					=> $img_type_com,
				'img_color_id' 					=> $img_color_id,
				'is_private' 					=> $is_private,
				'is_private_img_apply' 			=> $is_private_img_apply,
				'note_img_purpose_com' 			=> $note_img_purpose_com,
				'point_img_com' 				=> $point_img_com,
				'img_quantity_com' 				=> $img_quantity_com,
				'link_url_com' 					=> $link_url_com,
				'note_another_des_com' 			=> $note_another_des_com,
				'competition_time_agree_id' 	=> $competition_time_agree,
				'date_add_com' 					=> date('Y-m-d H:i:s'),
				'status_com' 					=> 0,
				'os_register' 					=> 3
			);
			
			$this->db->insert('luxyart_tb_user_competition', $dataInsertUserCompetition);
			$id_competition = $this->db->insert_id();
			
			//send email
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "post_competition";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['user_email'];
			$dataSendEmail["name"] 					= ucwords($data['user_fullname']);
			$dataSendEmail["title_com"]				= $title_com;
			$dataSendEmail["date_start_com"]		= $date_start_com;
			$dataSendEmail["date_end_com"]			= $date_end_com;
			$dataSendEmail["point_img_com"]			= $point_img_com;
			$dataSendEmail["img_quantity_com"]		= $img_quantity_com;
			$dataSendEmail["link_url_com"]			= $link_url_com;
			$dataSendEmail["link"]					= base_url()."mypage/list-compe.html";
			$dataSendEmail["is_send_admin"]			= 1;
			//$dataSendEmail["is_debug"]			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "post_competition";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user_id;
			$addNotice['title_com'] 				= $title_com;
			$addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
			$addNotice['option_id'] 				= $id_competition;
			//$addNotice['is_debug'] 				= 1;
			
			$this->function_model->addNotice($addNotice);
			
			//caculator point
			if(($data['user_paymoney_getpoint']-$point_img_com*$img_quantity_com) >= 0){
			
				//update point investor
				$this->db->where('user_id', $user_id);
				$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint-'.($point_img_com*$img_quantity_com),FALSE);
				$this->db->update('luxyart_tb_user');
			
			}
			else{
			
				//update point investor
				$this->db->where('user_id', $user_id);
				$this->db->set('user_paymoney_getpoint',0);
				$this->db->update('luxyart_tb_user');
			
				//update point investor
				$this->db->where('user_id', $user_id);
				$this->db->set('user_point','user_point-'.($point_img_com*$img_quantity_com)."+".$data['user_paymoney_getpoint'],FALSE);
				$this->db->update('luxyart_tb_user');
			
			}
			
			$notice_post_competition = $this->lang->line('management_point_post_competition');
			$notice_post_competition = str_replace("{point}", ($point_img_com*$img_quantity_com), $notice_post_competition);
			$notice_post_competition = str_replace("{title_com}", $title_com, $notice_post_competition);
			$notice_post_competition = str_replace("{link}", base_url()."compe-detail/".$id_competition.".html", $notice_post_competition);
			
			//add point user
			$dataAddPointUser = array(
				'user_id' 						=> $user_id,
				'point' 						=> $point_img_com*$img_quantity_com,
				'status_change_point' 			=> 2,
				'inbox_id' 						=> $this->function_model->get_inbox_templates_id("post_competition"),
				'content' 						=> $notice_post_competition,
				'date_add' 						=> date('Y-m-d H:i:s')
			);
			
			$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
			
			$this->function_model->set_use_point_package(round($point_img_com*$img_quantity_com,2), $user_id);
				
			//notice success
			$_SESSION['return_key'] = 'compe/post_compe_success';
			header("Location: ".base_url()."notice.html");
			
			exit;
			
		}
		
		//get setting
		$setting = $this->function_model->get_setting();
		$data['min_width_img_competition'] = $setting['min_width_img_competition'];
		$data['min_height_img_competition'] = $setting['min_height_img_competition'];
		$data['minimun_lux_post_competition'] = $setting['minimun_lux_post_competition'];
		
		//get product color
    	$whereProductColor = array('lang_id' => $this->lang_id, 'status' => 1);
    	$orderProductColor = array();
    	$data['list_product_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->result();
    	
    	//get competition time agree
    	$whereCompetitionTimeAgree = array('status' => 1);
    	$orderCompetitionTimeAgree = array();
    	$data['competition_time_agree'] = $this->main_model->getAllData("luxyart_tb_competition_time_agree", $whereCompetitionTimeAgree, $orderCompetitionTimeAgree)->result();
    	
    	//get meta seo
    	$meta = array();
    	$data['meta_seo'] = $this->function_model->getMetaSeo("compe/post_compe", $meta, $this->lang_id);
    	
    	$data['noindex'] = '1';
		
		$this->template->load('default', 'post_compe', $data, __CLASS__);
		
	}

	public function post_compe_test(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		$setting = $this->function_model->get_setting();
		$data['point_convert_money'] = $setting['point_convert_money'];
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		$user = $this->function_model->getUser($user_id);
		$data['user_level'] = $user->user_level;
		$data['user_point'] = $user->user_point;
		$data['user_paymoney_getpoint'] = $user->user_paymoney_getpoint;
		$data['user_email'] = $user->user_email;
		$data['user_fullname'] = $this->function_model->get_fullname($user->user_id, $this->lang_id);
		
		if(isset($_POST['btnPostCompe'])){
			
			//get data
			$title_com						= trim($_POST["title_com"]);
			//photo_des_com
			$photo_des_com 					= $_FILES['photo_des_com'];
			//icon_com
			$icon_com 						= $_FILES['icon_com'];
			$date_start_com					= $_POST["date_start_com"];
			$date_end_com					= $_POST["date_end_com"];
			$is_private						= isset($_POST["is_private"]) ? 1 : 0;	
			$competition_time_agree			= $_POST["competition_time_agree"];
			$note_require_com				= trim($_POST["note_require_com"]);
			$note_description_com			= trim($_POST["note_description_com"]);
			$img_type_com					= $_POST["img_type_com"];
			$img_color_id					= trim($_POST["id_color"]);
			$note_img_purpose_com			= trim($_POST["note_img_purpose_com"]);
			$point_img_com					= $_POST["point_img_com"];
			$img_quantity_com				= $_POST["img_quantity_com"];
			$link_url_com					= trim($_POST["link_url_com"]);
			$note_another_des_com			= trim($_POST["note_another_des_com"]);
			
			$date_start_com = $this->function_model->convert_date("dd/mm/yyyy", "yyyy-mm-dd", $date_start_com);
			$date_end_com = $this->function_model->convert_date("dd/mm/yyyy", "yyyy-mm-dd", $date_end_com);
			
			$time = time();
			
			$photo_des_com_type = end(explode(".", $photo_des_com['name']));
			$icon_com_type = end(explode(".", $icon_com['name']));
			
			$photo_des_com_name = $time.".".$photo_des_com_type;
			$icon_com_name = $time.".".$icon_com_type;
			
			//upload photo_des_com
			if($photo_des_com['tmp_name'] != ""){
				move_uploaded_file($photo_des_com['tmp_name'], DIR_COMP_IMG.$photo_des_com_name);
			}
			
			//upload icon_com
			if($icon_com['tmp_name'] != ""){
				move_uploaded_file($icon_com['tmp_name'], DIR_COMP_ICON.$icon_com_name);
			}
			
			$dataInsertUserCompetition = array(
				'user_id' 						=> $user_id,
				'title_com' 					=> $title_com,
				'photo_des_com' 				=> $photo_des_com_name,
				'date_start_com' 				=> $date_start_com,
				'date_end_com' 					=> $date_end_com,
				'icon_com' 						=> $icon_com_name,
				'note_description_com' 			=> $note_description_com,
				'note_require_com' 				=> $note_require_com,
				'img_type_com' 					=> $img_type_com,
				'img_color_id' 					=> $img_color_id,
				'is_private' 					=> $is_private,
				'note_img_purpose_com' 			=> $note_img_purpose_com,
				'point_img_com' 				=> $point_img_com,
				'img_quantity_com' 				=> $img_quantity_com,
				'link_url_com' 					=> $link_url_com,
				'note_another_des_com' 			=> $note_another_des_com,
				'competition_time_agree_id' 	=> $competition_time_agree,
				'date_add_com' 					=> date('Y-m-d H:i:s'),
				'status_com' 					=> 0,
				'os_register' 					=> 3
			);
			
			$this->db->insert('luxyart_tb_user_competition', $dataInsertUserCompetition);
			$id_competition = $this->db->insert_id();
			
			//send email
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "post_competition";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['user_email'];
			$dataSendEmail["name"] 					= ucwords($data['user_fullname']);
			$dataSendEmail["title_com"]				= $title_com;
			$dataSendEmail["date_start_com"]		= $date_start_com;
			$dataSendEmail["date_end_com"]			= $date_end_com;
			$dataSendEmail["point_img_com"]			= $point_img_com;
			$dataSendEmail["img_quantity_com"]		= $img_quantity_com;
			$dataSendEmail["link_url_com"]			= $link_url_com;
			$dataSendEmail["link"]					= base_url()."mypage/list-compe.html";
			$dataSendEmail["is_send_admin"]			= 1;
			//$dataSendEmail["is_debug"]			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "post_competition";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user_id;
			$addNotice['title_com'] 				= $title_com;
			$addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
			$addNotice['option_id'] 				= $id_competition;
			//$addNotice['is_debug'] 				= 1;
			
			$this->function_model->addNotice($addNotice);
			
			//caculator point
			if(($data['user_paymoney_getpoint']-$point_img_com*$img_quantity_com) >= 0){
			
				//update point investor
				$this->db->where('user_id', $user_id);
				$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint-'.($point_img_com*$img_quantity_com),FALSE);
				$this->db->update('luxyart_tb_user');
			
			}
			else{
			
				//update point investor
				$this->db->where('user_id', $user_id);
				$this->db->set('user_paymoney_getpoint',0);
				$this->db->update('luxyart_tb_user');
			
				//update point investor
				$this->db->where('user_id', $user_id);
				$this->db->set('user_point','user_point-'.($point_img_com*$img_quantity_com)."+".$data['user_paymoney_getpoint'],FALSE);
				$this->db->update('luxyart_tb_user');
			
			}
			
			$notice_post_competition = $this->lang->line('management_point_post_competition');
			$notice_post_competition = str_replace("{point}", ($point_img_com*$img_quantity_com), $notice_post_competition);
			$notice_post_competition = str_replace("{title_com}", $title_com, $notice_post_competition);
			$notice_post_competition = str_replace("{link}", base_url()."compe-detail/".$id_competition.".html", $notice_post_competition);
			
			//add point user
			$dataAddPointUser = array(
				'user_id' 						=> $user_id,
				'point' 						=> $point_img_com*$img_quantity_com,
				'status_change_point' 			=> 2,
				'inbox_id' 						=> $this->function_model->get_inbox_templates_id("post_competition"),
				'content' 						=> $notice_post_competition,
				'date_add' 						=> date('Y-m-d H:i:s')
			);
			
			$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
				
			//notice success
			$_SESSION['return_key'] = 'compe/post_compe_success';
			header("Location: ".base_url()."notice.html");
			
			exit;
			
		}
		
		//get setting
		$setting = $this->function_model->get_setting();
		$data['min_width_img_competition'] = $setting['min_width_img_competition'];
		$data['min_height_img_competition'] = $setting['min_height_img_competition'];
		
		//get product color
    	$whereProductColor = array('lang_id' => $this->lang_id, 'status' => 1);
    	$orderProductColor = array();
    	$data['list_product_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->result();
    	
    	//get competition time agree
    	$whereCompetitionTimeAgree = array('status' => 1);
    	$orderCompetitionTimeAgree = array();
    	$data['competition_time_agree'] = $this->main_model->getAllData("luxyart_tb_competition_time_agree", $whereCompetitionTimeAgree, $orderCompetitionTimeAgree)->result();
    	
    	//get meta seo
    	$meta = array();
    	$data['meta_seo'] = $this->function_model->getMetaSeo("compe/post_compe", $meta, $this->lang_id);
    	
    	$data['noindex'] = '1';
		
		$this->template->load('default', 'post_compe_test', $data, __CLASS__);
		
	}
	
	public function edit_compe($id_competition){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		$setting = $this->function_model->get_setting();
		$data['point_convert_money'] = $setting['point_convert_money'];
	
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data["user_id"] = $user_id;
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		$user = $this->function_model->getUser($user_id);
		$data['user_level'] = $user->user_level;
		$data['user_point'] = $user->user_point;
		$data['user_email'] = $user->user_email;
		$data['user_fullname'] = $this->function_model->get_fullname($user->user_id, $this->lang_id);
		
		//get detail competition
		$whereDetailCompetition = array('id_competition' => $id_competition);
		$orderDetailCompetition = array();
		$data['detail_competition'] = $this->main_model->getAllData("luxyart_tb_user_competition", $whereDetailCompetition, $orderDetailCompetition)->row();
		
		$status_com = $data['detail_competition']->status_com;
		
		if($user_id != $data['detail_competition']->user_id){
			//notice
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
			exit;
		}
		
		if($status_com > 1){
			$_SESSION['return_title'] = $this->lang->line('notice');
			if($status_com == 2){
				$_SESSION['return_key'] = 'competition_applied_you_can_not_edit_information';
			}
			else if($status_com == 3){
				$_SESSION['return_key'] = 'competition_finished_you_can_not_edit_information';
			}
			else if($status_com == 4){
				$_SESSION['return_key'] = 'competition_deleted_you_can_not_edit_information';
			}
			else if($status_com == 5){
				$_SESSION['return_key'] = 'competition_blocked_you_can_not_edit_information';
			}
			
			//notice
			header("Location: ".base_url()."notice.html");
			
		}
	
		if(isset($_POST['btnEditCompe'])){
				
			//get data
			//$title_com					= trim($_POST["title_com"]);
			//photo_des_com
			$photo_des_com 					= $_FILES['photo_des_com'];
			$photo_des_com_old 				= $_POST['photo_des_com_old'];
			//icon_com
			$icon_com 						= $_FILES['icon_com'];
			$icon_com_old 					= $_POST['icon_com_old'];
			//$date_start_com				= $_POST["date_start_com"];
			//$date_end_com					= $_POST["date_end_com"];
			//$is_private					= $_POST["is_private"];
			$is_private_img_apply 			= $_POST["is_private_img_apply"];
			$competition_time_agree			= $_POST["competition_time_agree"];
			$note_require_com				= trim($_POST["note_require_com"]);
			$note_description_com			= trim($_POST["note_description_com"]);
			$img_type_com					= $_POST["img_type_com"];
			$img_color_id					= trim($_POST["id_color"]);
			$note_img_purpose_com			= trim($_POST["note_img_purpose_com"]);
			//$point_img_com				= $_POST["point_img_com"];
			//$img_quantity_com				= $_POST["img_quantity_com"];
			$link_url_com					= trim($_POST["link_url_com"]);
			$note_another_des_com			= trim($_POST["note_another_des_com"]);
				
			//$date_start_com = $this->function_model->convert_date("dd/mm/yyyy", "yyyy-mm-dd", $date_start_com);
			//$date_end_com = $this->function_model->convert_date("dd/mm/yyyy", "yyyy-mm-dd", $date_end_com);
				
			$time = time();
				
			$photo_des_com_type = end(explode(".", $photo_des_com['name']));
			$icon_com_type = end(explode(".", $icon_com['name']));

			if($photo_des_com['tmp_name'] != ""){
				$photo_des_com_name = $time.".".$photo_des_com_type;
			}
			else{
				$photo_des_com_name = "";
			}
			
			if($icon_com['tmp_name'] != ""){
				$icon_com_name = $time.".".$icon_com_type;
			}
			else{
				$icon_com_name = "";
			}
			
			//upload photo_des_com
			if($photo_des_com['tmp_name'] != ""){
				
				if(file_exists(DIR_COMP_IMG.$photo_des_com_old)){
					unlink(DIR_COMP_IMG.$photo_des_com_old);
				}
				move_uploaded_file($photo_des_com['tmp_name'], DIR_COMP_IMG.$photo_des_com_name);
				
			}
			
			//upload icon_com
			if($icon_com['tmp_name'] != ""){
				
				if(file_exists(DIR_COMP_ICON.$icon_com_old)){
					unlink(DIR_COMP_ICON.$icon_com_old);
				}
				move_uploaded_file($icon_com['tmp_name'], DIR_COMP_ICON.$icon_com_name);
				
			}
			
			$dataEditUserCompetition = array(
				//'user_id' 					=> $user_id,
				//'title_com' 					=> $title_com,
				'photo_des_com' 				=> $photo_des_com_name,
				//'date_start_com' 				=> $date_start_com,
				//'date_end_com' 				=> $date_end_com,
				'icon_com' 						=> $icon_com_name,
				'note_description_com' 			=> $note_description_com,
				'note_require_com' 				=> $note_require_com,
				'img_type_com' 					=> $img_type_com,
				'img_color_id' 					=> $img_color_id,
				'note_img_purpose_com' 			=> $note_img_purpose_com,
				//'point_img_com' 				=> $point_img_com,
				//'img_quantity_com' 			=> $img_quantity_com,
				'is_private_img_apply' 			=> $is_private_img_apply,
				'link_url_com' 					=> $link_url_com,
				'note_another_des_com' 			=> $note_another_des_com,
				'competition_time_agree_id' 	=> $competition_time_agree,
				'date_update_com' 				=> date('Y-m-d H:i:s'),
				'status_com' 					=> 0,
				//'os_register' 				=> 3
			);
			
			if($photo_des_com_name == ""){
				unset($dataEditUserCompetition['photo_des_com']);
			}
			
			if($icon_com_name == ""){
				unset($dataEditUserCompetition['icon_com']);
			}
				
			$this->db->where('id_competition', $id_competition);
			$this->db->update('luxyart_tb_user_competition', $dataEditUserCompetition);
				
			//send email
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "edit_competition";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['user_email'];
			$dataSendEmail["name"] 					= ucwords($data['user_fullname']);
			$dataSendEmail["title_com"]				= $data['detail_competition']->title_com;
			$dataSendEmail["date_start_com"]		= $data['detail_competition']->date_start_com;
			$dataSendEmail["date_end_com"]			= $data['detail_competition']->date_end_com;
			$dataSendEmail["point_img_com"]			= $data['detail_competition']->point_img_com;
			$dataSendEmail["img_quantity_com"]		= $data['detail_competition']->img_quantity_com;
			$dataSendEmail["link_url_com"]			= $link_url_com;
			$dataSendEmail["link"]					= base_url()."mypage/list-compe.html";
			$dataSendEmail["is_send_admin"]			= 1;
			//$dataSendEmail["is_debug"]			= 1;
	
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "edit_competition";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user_id;
			$addNotice['title_com'] 				= $title_com;
			$addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
			$addNotice['option_id'] 				= $id_competition;
			//$addNotice['is_debug'] 				= 1;
				
			$this->function_model->addNotice($addNotice);
	
			//notice success
			$_SESSION['return_key'] = 'compe/edit_compe_success';
			header("Location: ".base_url()."notice.html");
				
			exit;
				
		}
	
		//get setting
		$setting = $this->function_model->get_setting();
		$data['min_width_img_competition'] = $setting['min_width_img_competition'];
		$data['min_height_img_competition'] = $setting['min_height_img_competition'];
	
		//get product color
		$whereProductColor = array('lang_id' => $this->lang_id, 'status' => 1);
		$orderProductColor = array();
		$data['list_product_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->result();
		
		//get product color choose
		$data['product_color'] = array();
		$img_color_id = explode(",", $data['detail_competition']->img_color_id);
		foreach($img_color_id as $_img_color_id){
			array_push($data['product_color'], $_img_color_id);
		}
		
		//get competition time agree
		$whereCompetitionTimeAgree = array('status' => 1);
		$orderCompetitionTimeAgree = array();
		$data['competition_time_agree'] = $this->main_model->getAllData("luxyart_tb_competition_time_agree", $whereCompetitionTimeAgree, $orderCompetitionTimeAgree)->result();
		 
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/edit_compe", $meta, $this->lang_id);
		
		$data['noindex'] = '1';
	
		$this->template->load('default', 'edit_compe', $data, __CLASS__);
	
	}
	
	public function mypage_compe(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/mypage_compe", $meta, $this->lang_id);
	
		$this->template->load('default', 'mypage_compe', $data, __CLASS__);
	
	}
	
	public function mypage_list_compe(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		//get list compe
		$this->load->library('pagination');
		$config['per_page'] = 10;
		
		$config['total_rows'] = count($this->manager_compe_model->get_list_manager_compe($user_id,'','')->result());
		
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/list-compe';
		
		$data['list_compe'] = $this->manager_compe_model->get_list_manager_compe($user_id,$config['per_page'],$this->uri->segment(3))->result();
		
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/mypage_list_compe", $meta, $this->lang_id);
		
		$data['active_menu_sidebar'] = "mypage-list-compe";
		
		$data['noindex'] = '1';
	
		$this->template->load('default', 'mypage_list_compe', $data, __CLASS__);
	
	}
	
	public function mypage_list_compe_join(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
	
		//get list compe
		$this->load->library('pagination');
		$config['per_page'] = 10;
	
		$config['total_rows'] = count($this->manager_compe_model->get_list_manager_compe_join($user_id,'','')->result());
	
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/list-compe-join';
	
		$data['list_compe_join'] = $this->manager_compe_model->get_list_manager_compe_join($user_id,$config['per_page'],$this->uri->segment(3))->result();
	
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/mypage_list_compe_join", $meta, $this->lang_id);
	
		$data['active_menu_sidebar'] = "mypage-list-compe-join";
		
		$data['noindex'] = '1';
	
		$this->template->load('default', 'mypage_list_compe_join', $data, __CLASS__);
	
	}
	
	public function compe_detail($id_competition){
		
		//check permission
		/*$is_permission = $this->function_model->check_permission(1);
		 if(!$is_permission){
		header("Location: ".base_url()."login.html");
		}*/
		
		//set config
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
		
		$user = $this->function_model->getUser($user_id);
		$data['detail_user'] = $user;
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		$date_now = date('Y-m-d');
		
		$data['is_apply_competition'] = 1;
		if($data['detail_compe']->date_start_com > $date_now || $date_now > $data['detail_compe']->date_end_com){
			$data['is_apply_competition'] = 0;
		}
		
		if($data['user_id'] != $data['detail_compe']->user_id){
		
			if($user->user_level < 2 && $data['detail_compe']->is_private == 1){
				$_SESSION['return_key'] = 'please_update_account_creator_for_view_detail_competition';
				$_SESSION['return_link'] = base_url()."mypage/user-update-account.html";
				header("Location: ".base_url()."notice.html");
			}
		
			if($data['detail_compe']->status_com == 0 || $data['detail_compe']->status_com > 3){
				$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
				header("Location: ".base_url()."notice.html");
			}
		
		}
		
		//get hour minute
		//date_add_img + option_sale
		$date_active = $data['detail_compe']->date_active;
		$date_end_com = $data['detail_compe']->date_end_com;
		
		$value_date_active = date_create($date_active);
		$value_end_com = date_create($date_end_com);
		
		$diff = date_diff($value_date_active, $value_end_com);
		
		$data['hour']	 							= $diff->format("%R%a");
		$data['minute']	 							= $diff->format("%h");
		
		//get link full
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->session->set_userdata('actual_link', $actual_link);
		
		//get list compe other
		$data['list_compe_other'] = $this->manager_compe_model->get_list_compe_other($id_competition, $user_id, 3)->result();
		
		//get list compe apply
		$data['list_compe_apply'] = $this->manager_compe_model->get_list_compe_apply($id_competition, 15)->result();
		
		//get list image apply
		$data['count_compe'] = $this->manager_compe_model->get_list_image_apply_scroll($id_competition)->num_rows();
		
		//get meta seo
		$meta = array();
		$meta['title_com'] = $data['detail_compe']->title_com;
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/compe_detail", $meta, $this->lang_id);
		
		if($this->input->get("page")>0){
			$start_limit = ceil($this->input->get("page") * 4);
			$data['list_image_apply'] = $this->manager_compe_model->get_list_image_apply_scroll($id_competition, 4, $start_limit)->result();
			$result = $this->load->view('compe/data_detail_compe_load', $data);
		}
		else{
			$_SESSION['scroll_detail_compe'] = 1;
			$data['list_image_apply'] = $this->manager_compe_model->get_list_image_apply_scroll($id_competition ,4, 0)->result();
			$this->load->view('compe/compe_detail', $data, __CLASS__);
		}
		
		//$this->template->load('default', 'compe_detail', $data, __CLASS__);
		
	}
	
	public function list_compe(){
		
		//check permission
		/*$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}*/
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		if($user_id > 0){
			$data['user'] = $this->function_model->getUser($user_id);
		}
		
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		//get list image apply
		$data['list_image_apply'] = $this->manager_compe_model->get_list_image_apply(0, 15)->result();
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/list_compe", $meta, $this->lang_id);
		//get list compe other
		
		// use user_id for compe is private
		$data['count_compe'] = $this->manager_compe_model->get_list_compe_other_list_compe($user_id)->num_rows();
		 if($this->input->get("page")>0){
			 $start_limit = ceil($this->input->get("page") * 3);
			 $data['list_compe_other'] = $this->manager_compe_model->get_list_compe_other_list_compe($user_id,3, $start_limit)->result();
			 $result = $this->load->view('compe/data_compe_load', $data);
			 echo json_encode($result);
		 }
		 else
		 {
			 $_SESSION['load_page'] = 1;
			 $data['list_compe_other'] = $this->manager_compe_model->get_list_compe_other_list_compe($user_id ,3, 0)->result();
			 $this->load->view('compe/list_compe', $data, __CLASS__);
		 }
		
	}
	
	public function list_compe_test(){
	
		//check permission
		/*$is_permission = $this->function_model->check_permission(1);
			if(!$is_permission){
		header("Location: ".base_url()."login.html");
		}*/
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		//get list image apply
		$data['list_image_apply'] = $this->manager_compe_model->get_list_image_apply(0, 15)->result();
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/list_compe", $meta, $this->lang_id);
		//get list compe other
		// use user_id for compe is private
		$data['count_compe'] = $this->manager_compe_model->get_list_compe_other_list_compe_count_test($user_id)->num_rows();
		if($this->input->get("page")>0){
			$start_limit = ceil($this->input->get("page")*3);
			$data['list_compe_other'] = $this->manager_compe_model->get_list_compe_other_list_compe_test_page($user_id,3, $start_limit)->result();
			$result = $this->load->view('compe/data_compe_load', $data);
			//echo json_encode($result);
		}
		else
		{
			$_SESSION['load_page'] = 1;
			$data['list_compe_other'] = $this->manager_compe_model->get_list_compe_other_list_compe_test($user_id ,3, 0)->result();
			$this->load->view('compe/list_compe_test', $data, __CLASS__);
		}
	
	}
	public function compe_check_comp(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/compe_check_comp", $meta, $this->lang_id);
	
		$this->template->load('default', 'compe_check_comp', $data, __CLASS__);
	
	}
	
	public function compe_post_list(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/compe_post_list", $meta, $this->lang_id);
	
		$this->template->load('default', 'compe_post_list', $data, __CLASS__);
	
	}
	
	public function compe_check(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
		
		$id_competition = $this->uri->segment(2);
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		$status_com = $data['detail_compe']->status_com;
		if($status_com != 2){
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
		}
		
		if($user_id != $data['detail_compe']->user_id){
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
		}
		
		//get list color
		$whereColor = array('id_color > ' => 0, 'lang_id' => $this->lang_id);
		$orderColor = array();
		$data['list_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereColor, $orderColor)->result();
		
		$data['search_compe_check'] = $this->session->userdata["search_compe_check"];
		$data['search_compe_check']['id_color'] = implode(",", $data['search_compe_check']['id_color']);
		
		//get list image apply paging
		$this->load->library('pagination');
		
		if($data['search_compe_check']['num_page'] != ""){
			
			$config['total_rows'] = count($this->manager_compe_model->get_search_image_apply_paging($id_competition,'','',$data['search_compe_check'])->result());
			$data['count_compe_check'] = $config['total_rows'];
			$config['per_page'] = $data['search_compe_check']['num_page'];
		}
		else{
			$config['total_rows'] = count($this->manager_compe_model->get_list_image_apply_paging($id_competition,'','')->result());
			$data['count_compe_check'] = $config['total_rows'];
			$config['per_page'] = 50;
		}
		
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/compe-check/'.$id_competition;
		
		if($data['search_compe_check']['num_page'] != ""){
			
			$data['list_image_apply'] = $this->manager_compe_model->get_search_image_apply_paging($id_competition,$config['per_page'],$this->uri->segment(3),$data['search_compe_check'])->result();
		}
		else{
			
			$data['list_image_apply'] = $this->manager_compe_model->get_list_image_apply_paging($id_competition,$config['per_page'],$this->uri->segment(3))->result();
		}
		
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
		
		/*$data['choose_img'] = $this->session->userdata["choose_img"];
		if($data['choose_img']){
				
			$data['id_img_choose'] = $data['choose_img'][$id_competition];
			$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_choose($id_competition, $data['id_img_choose'])->result();
				
		}*/
		
		$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_selected($id_competition)->result();
		$arr_image_apply_choose = array();
		$data['id_img_choose'] = array();
		foreach($data['list_image_apply_choose'] as $detail_image_apply_choose){
			array_push($arr_image_apply_choose, $detail_image_apply_choose->id_img);
		}
		if(!empty($arr_image_apply_choose)){
			$data['id_img_choose'] = implode(",", $arr_image_apply_choose);
		}
		else{
			$data['id_img_choose'] = "";
		}
		
		$data['noindex'] = '1';
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/compe_check", $meta, $this->lang_id);
	
		$this->template->load('default', 'compe_check', $data, __CLASS__);
	
	}
	
	public function compe_check_test(){
	
		//check permission
		/*$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);*/
		
		$user_id = '2103';
	
		$id_competition = $this->uri->segment(2);
		$id_competition = 7;
	
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		$status_com = $data['detail_compe']->status_com;
		
		if($status_com != 2){
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
		}
	
		if($user_id != $data['detail_compe']->user_id){
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
		}
	
		//get list color
		$whereColor = array('id_color > ' => 0, 'lang_id' => $this->lang_id);
		$orderColor = array();
		$data['list_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereColor, $orderColor)->result();
	
		$data['search_compe_check'] = $this->session->userdata["search_compe_check"];
		$data['search_compe_check']['id_color'] = implode(",", $data['search_compe_check']['id_color']);
	
		//get list image apply paging
		$this->load->library('pagination');
	
		if($data['search_compe_check']['num_page'] != ""){
				
			$config['total_rows'] = count($this->manager_compe_model->get_search_image_apply_paging($id_competition,'','',$data['search_compe_check'])->result());
			$data['count_compe_check'] = $config['total_rows'];
			$config['per_page'] = $data['search_compe_check']['num_page'];
		}
		else{
			$config['total_rows'] = count($this->manager_compe_model->get_list_image_apply_paging($id_competition,'','')->result());
			$data['count_compe_check'] = $config['total_rows'];
			$config['per_page'] = 50;
		}
		
	
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/compe-check/'.$id_competition;
	
		if($data['search_compe_check']){
			$data['list_image_apply'] = $this->manager_compe_model->get_search_image_apply_paging($id_competition,$config['per_page'],$this->uri->segment(3),$data['search_compe_check'])->result();
		}
		else{
			$data['list_image_apply'] = $this->manager_compe_model->get_list_image_apply_paging($id_competition,$config['per_page'],$this->uri->segment(3))->result();
		}
	
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
	
		/*$data['choose_img'] = $this->session->userdata["choose_img"];
			if($data['choose_img']){
	
		$data['id_img_choose'] = $data['choose_img'][$id_competition];
		$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_choose($id_competition, $data['id_img_choose'])->result();
	
		}*/
	
		$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_selected($id_competition)->result();
		$arr_image_apply_choose = array();
		$data['id_img_choose'] = array();
		foreach($data['list_image_apply_choose'] as $detail_image_apply_choose){
			array_push($arr_image_apply_choose, $detail_image_apply_choose->id_img);
		}
		if(!empty($arr_image_apply_choose)){
			$data['id_img_choose'] = implode(",", $arr_image_apply_choose);
		}
		else{
			$data['id_img_choose'] = "";
		}
	
		$data['noindex'] = '1';
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/compe_check", $meta, $this->lang_id);
	
		$this->template->load('default', 'compe_check', $data, __CLASS__);
	
	}
	
	public function compe_check_conf(){
		
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$id_competition = $this->uri->segment(2);
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		$status_com = $data['detail_compe']->status_com;
		if($status_com != 2){
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
		}
		
		if($user_id != $data['detail_compe']->user_id){
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
		}
		
		//get list choose image
		/*$data['choose_img'] = $this->session->userdata["choose_img"];
		if($data['choose_img']){
		
			$data['id_img_choose'] = $data['choose_img'][$id_competition];
			$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_choose($id_competition, $data['id_img_choose'])->result();
		
		}*/
		
		$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_selected($id_competition)->result();
		$arr_image_apply_choose = array();
		$data['id_img_choose'] = array();
		foreach($data['list_image_apply_choose'] as $detail_image_apply_choose){
			array_push($arr_image_apply_choose, $detail_image_apply_choose->id_img);
		}
		if(!empty($arr_image_apply_choose)){
			$data['id_img_choose'] = implode(",", $arr_image_apply_choose);
		}
		
		$data['noindex'] = '1';
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("compe/compe_check_conf", $meta, $this->lang_id);
		
		$this->template->load('default', 'compe_check_conf', $data, __CLASS__);
		
	}
	 
}