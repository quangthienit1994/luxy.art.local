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

class Images extends CI_Controller {

	public function __construct() {
		
		parent::__construct();
		
		@session_start();
		$this->load->library('session');
		$this->load->library('cart');
		
		//$this->load->model('main_model');
		$this->load->model('function_model');
		$this->load->model('manager_image_model');
		$this->load->model('manager_compe_model');
		$this->load->model('shopping_model');
		$this->load->model('manager_user_model');
		$this->load->model('manager_comment_model');
		
		$dir = getcwd();
		define("DIR", $dir."/publics/");
		define("DIR_TEMP", $dir."/publics/product_img/temp/");
		
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
	
	public function mypage(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/mypage", $meta, $this->lang_id);
		
		$this->template->load('default', 'mypage', $data, __CLASS__);
		
	}
	
	public function list_images(){
		
		//set config
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		
		//get setting
		$setting = $this->function_model->get_setting();
		//$data['num_paging'] = $setting['num_paging'];
		$data['num_paging'] = $setting['num_paging'];
	
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		$logged_in = $this->function_model->get_logged_in();
		$data['user_id'] = $logged_in['user_id'];
		if($data['user_id'] > 0){
			$data['user'] = $this->function_model->getUser($data['user_id']);
		} 
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($data['user_id']);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/list_images", $meta, $this->lang_id);
		$data['count_image'] = count($this->manager_image_model->get_all_list_manager_image_scroll($data['user_id'],"","")->result());
	
		$data['active_menu_sidebar'] = "mypage-list-images";
		
		//get user filter img
		if($this->input->get("page")>0){
			$start_limit = ceil($this->input->get("page") * $setting['num_paging']);
			$data['list_images'] = $this->manager_image_model->get_all_list_manager_image_scroll($data['user_id'], $setting['num_paging'], $start_limit)->result();
			$result = $this->load->view('images/data_list_images_load', $data);
			
			
			
		}
		else{
			$_SESSION['scroll_list_images'] = 1;
			$data['list_images'] = $this->manager_image_model->get_all_list_manager_image_scroll($data['user_id'], $setting['num_paging'], 0)->result();
			$this->load->view('images/list_images', $data, __CLASS__);
		}
		
		$data['noindex'] = '1';
		
		$data["is_mobile"] = $this->function_model->isMobile();
	
		//$this->template->load('default', 'list_images_test', $data, __CLASS__);
	
	}
	
	public function list_images_test(){
		
		//set config
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		
		//get setting
		$setting = $this->function_model->get_setting();
		$data['num_paging'] = $setting['num_paging'];
	
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		$logged_in = $this->function_model->get_logged_in();
		$data['user_id'] = $logged_in['user_id']; 
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/list_images", $meta, $this->lang_id);
		$data['count_image'] = count($this->manager_image_model->get_all_list_manager_image_scroll($data['user_id'], '', '')->result());
	
		$data['active_menu_sidebar'] = "mypage-list-images";
		
		//get user filter img
		if($this->input->get("page")>0){
			$start_limit = ceil($this->input->get("page") * 10);
			$data['list_images'] = $this->manager_image_model->get_all_list_manager_image_scroll($data['user_id'], $data['num_paging'], $start_limit)->result();
			$result = $this->load->view('images/data_list_images_load', $data);
		}
		else{
			$_SESSION['scroll_list_images'] = 1;
			$data['list_images'] = $this->manager_image_model->get_all_list_manager_image_scroll($data['user_id'], $data['num_paging'], 0)->result();
			$this->load->view('images/list_images_test', $data, __CLASS__);
		}
	
		//$this->template->load('default', 'list_images_test', $data, __CLASS__);
	
	}
	
	public function list_images_choose(){
		
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
		if($data['user_id'] > 0){
			$data['user'] = $this->function_model->getUser($data['user_id']);
		}
		$apply_competition_encode = $this->session->userdata['apply_competition_encode'];
		
		$decode = $this->function_model->decrypt_base64_dot($apply_competition_encode);
		$data['id_competition'] = $decode[0];
		$data['user_id'] = $decode[1];
		if($data['user_id'] != $user_id){
			header("Location: ".base_url()."login.html");
		}
		
		
		
		//set config
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		
		//get setting
		$setting = $this->function_model->get_setting();
		$data['num_paging'] = $setting['num_paging'];
	
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		$logged_in = $this->function_model->get_logged_in();
		$data['user_id'] = $logged_in['user_id'];

		$check_user_input = $this->manager_user_model->check_user_input_basic($data['user_id']);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/list_images", $meta, $this->lang_id);
		$data['count_image'] = count($this->manager_image_model->get_all_list_manager_image_choose_scroll($data['user_id'],"","")->result());
	
		$data['active_menu_sidebar'] = "mypage-list-images";
		
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($data['id_competition'])->row();
		
		//get user filter img
		if($this->input->get("page")>0){
			$start_limit = ceil($this->input->get("page") * $data['num_paging']);
			$data['list_images'] = $this->manager_image_model->get_all_list_manager_image_choose_scroll($data['user_id'], $data['num_paging'], $start_limit)->result();
			$result = $this->load->view('images/data_list_images_choose_load', $data);
		}
		else{
			$_SESSION['scroll_list_images_choose'] = 1;
			$data['list_images'] = $this->manager_image_model->get_all_list_manager_image_choose_scroll($data['user_id'], $data['num_paging'], 0)->result();
			$this->load->view('images/list_images_choose', $data, __CLASS__);
		}
	
		$data['noindex'] = '1';
		
		//$this->template->load('default', 'list_images_test', $data, __CLASS__);
	
	}
	
	public function post_image(){
		
		$this->load->library('sftp');
		
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		$check_is_post_img = $this->function_model->check_is_post_img($user_id);
		$check_is_post_img_explode = explode("{luxyart}",$check_is_post_img);
		$is_post_img = $check_is_post_img_explode[0];
		$user_level = $check_is_post_img_explode[1];
		
		$num_allow_post_image = $this->function_model->num_allow_post_image($user_id);
		$data["num_allow_post_image"] = $num_allow_post_image;
		
		if($is_post_img == 0){
			if($user_level == 1){
				$_SESSION['return_key'] = 'please_upgrade_to_creator_or_enterprise';
				header("Location: ".base_url()."notice.html");
			}
			elseif($user_level == 2){
				$_SESSION['return_key'] = 'please_upgrade_to_enterprise';
				header("Location: ".base_url()."notice.html");
			}
			else{
				$_SESSION['return_key'] = 'number_of_picture_exceeded_the_number_of_allowed';
				header("Location: ".base_url()."notice.html");
			}
		}
		
		$user = $this->function_model->getUser($user_id);
		$user_level = $user->user_level;
		$country_id = $user->country_id;
		$email_to = $user->user_email;
		$name = $this->function_model->get_fullname($user->user_id, $this->lang_id);
		
		$setting = $this->function_model->get_setting();
		
		$data['allowed_image_formats'] = $setting['allowed_image_formats'];
		$data['max_mb'] = $setting['max_mb'];
		$data['min_width_img'] = $setting['min_width_img'];
		$data['min_height_img'] = $setting['min_height_img'];
		$data['min_width_img_thumnail'] = $setting['min_width_img_thumnail'];
		$data['min_height_img_thumnail'] = $setting['min_height_img_thumnail'];
		$data['percent_image_pixel'] = $setting['percent_image_pixel'];
		$data['date_begin_random_image'] = $setting['date_begin_random_image'];
		$data['date_end_random_image'] = $setting['date_end_random_image'];
		
		if(isset($_POST['btnPostImage'])){
			
			$img_title 						= trim($_POST["img_title"]);
			
			$val_category 					= $_POST["val_category"];
			$val_category_sub 				= $_POST["val_category_sub"];
			
			//$file_name_original_img
			$type_export_image 				= $_POST["type_export_image"];
			$img_width 						= $_POST["img_width"];
			$img_height 					= $_POST["img_height"];
			$option_sale 					= $_POST["option_sale"];
			$is_limit 						= isset($_POST["is_limit"]) ? 1 : 0;
			
			$product_tags 					= $_POST["product_tags"];
			
			//type s
			$type_size_s 					= $_POST["type_size_s"];
			$type_size_width_s 				= $_POST["type_size_width_s"];
			$type_size_height_s 			= $_POST["type_size_height_s"];
			$type_size_point_s 				= $_POST["type_size_point_s"];
			$type_size_stock_s 				= isset($_POST["type_size_stock_s"]) ? $_POST["type_size_stock_s"] : 0;
			
			$type_size_width_s 				= str_replace(",","",$type_size_width_s);
			$type_size_height_s 			= str_replace(",","",$type_size_height_s);
			$type_size_point_s 				= str_replace(",","",$type_size_point_s);
			$type_size_stock_s 				= str_replace(",","",$type_size_stock_s);
			
			//type m
			$type_size_m 					= $_POST["type_size_m"];
			$type_size_width_m 				= $_POST["type_size_width_m"];
			$type_size_height_m 			= $_POST["type_size_height_m"];
			$type_size_point_m 				= $_POST["type_size_point_m"];
			$type_size_stock_m 				= isset($_POST["type_size_stock_m"]) ? $_POST["type_size_stock_m"] : 0;
			
			$type_size_width_m 				= str_replace(",","",$type_size_width_m);
			$type_size_height_m 			= str_replace(",","",$type_size_height_m);
			$type_size_point_m 				= str_replace(",","",$type_size_point_m);
			$type_size_stock_m 				= str_replace(",","",$type_size_stock_m);
			
			//type l
			$type_size_l 					= $_POST["type_size_l"];
			$type_size_width_l 				= $_POST["type_size_width_l"];
			$type_size_height_l 			= $_POST["type_size_height_l"];
			$type_size_point_l 				= $_POST["type_size_point_l"];
			$type_size_stock_l 				= isset($_POST["type_size_stock_l"]) ? $_POST["type_size_stock_l"] : 0;
			
			$type_size_width_l 				= str_replace(",","",$type_size_width_l);
			$type_size_height_l 			= str_replace(",","",$type_size_height_l);
			$type_size_point_l 				= str_replace(",","",$type_size_point_l);
			$type_size_stock_l 				= str_replace(",","",$type_size_stock_l);
			
			$img_code						= $this->function_model->get_code_image();
			
			$val_product_tags = implode(",",$product_tags);
			
			//upload image
			$file_name_original_img 		= $_FILES["file_name_original_img"];
			
			//choose server upload image
			$manager_server_upload = $this->function_model->getManagerServerUpload($this->lang_id);
			
			$file_name_original_type = $file_name_original_img['type'];
			$file_name_original_type = trim(str_replace("image/", "", $file_name_original_type));
			if($file_name_original_type == "jpeg"){
				$file_name_original_type = "jpg";
			}
			
			$time = time();
			$image_name = $this->function_model->get_image_name().'.'.$file_name_original_type;
			
			$rand = rand(100000,999999);
			
			$image_watermark_no_extension = $time.$rand.'-watermark';
			$image_watermark = $time.$rand.'-watermark.jpg';
				
			$image_blur_no_extension = $time.$rand.'-blur';
			$image_blur = $time.$rand.'-blur.jpg';
			
			$image_thumnail_no_extension = $time.$rand.'-thumnail';
			$image_thumnail = $time.$rand.'-thumnail.jpg';
			
			if($_FILES['file_name_original_img']['tmp_name'] != ""){
				
				move_uploaded_file($_FILES['file_name_original_img']['tmp_name'], DIR_TEMP.$image_name);
			
			}
			
			$md5_file = md5_file(DIR_TEMP.$image_name);
			
			if($this->function_model->check_md5_file($md5_file) == 0){
				
				$bytes = filesize(DIR_TEMP.$image_name);
				$mb = $this->function_model->byte_convert_to_mb($bytes);
				$resolution = $this->function_model->get_dpi(DIR_TEMP.$image_name);
				
				if($resolution[0] == $resolution[1] && $dpi > 36){
					$dpi = $resolution[0];
				}
				else{
					$dpi = 0;
				}
				
				list($width, $height, $type, $attr) = getimagesize(DIR_TEMP.$image_name);
				
				if($width >= $height){
					$kind_img = 1;
				}
				else{
					$kind_img = 2;
				}
				
				if($type_export_image == 1){
					
					$dir = DIR_TEMP.$image_name;
					$this->function_model->fix_rotate_image($dir,$dir);
					
					$dir_end = DIR_TEMP.$image_blur;
					$ext = strtolower(pathinfo($dir, PATHINFO_EXTENSION));
						
					switch($ext) {
						case 'gif':
							$img = imagecreatefromgif($dir);
							break;
						case 'jpg':
							$img = imagecreatefromjpeg($dir);
							break;
						case 'png':
							$img = imagecreatefrompng($dir);
							break;
					}
					imagejpeg($img, $dir_end, 100);
					
					$this->function_model->convert_to_pixel(DIR_TEMP."/".$image_blur, DIR_TEMP."/".$image_blur, $data['percent_image_pixel']);
					
					if($kind_img == 1 && $width > $data['min_width_img']){
						$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_blur_no_extension, DIR_TEMP.$image_blur);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img']){
						$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_blur_no_extension, DIR_TEMP.$image_blur);
					}
					
					if($kind_img == 1 && $width > $data['min_width_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_blur);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_blur);
					}
					
					$image_new = $image_blur;
					
				}
				else if($type_export_image == 2){
									
					$dir = DIR_TEMP.$image_name;
					$this->function_model->fix_rotate_image($dir,$dir);
					
					$dir_end = DIR_TEMP.$image_watermark;
					$ext = strtolower(pathinfo($dir, PATHINFO_EXTENSION));
					
					switch($ext) {
						case 'gif':
							$img = imagecreatefromgif($dir);
							break;
						case 'jpg':
							$img = imagecreatefromjpeg($dir);
							break;
						case 'png':
							$img = imagecreatefrompng($dir);
							break;
					}
					imagejpeg($img, $dir_end, 100);
					
					if($kind_img == 1 && $width > $data['min_width_img']){
						$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_watermark_no_extension, DIR_TEMP.$image_watermark);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img']){
						$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_watermark_no_extension, DIR_TEMP.$image_watermark);
					}
						
					$this->function_model->watermark_image(DIR_TEMP.$image_watermark_no_extension, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 0);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 0);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 1);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 2);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 3);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 4);
						
					if($kind_img == 1 && $width > $data['min_width_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_watermark);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_watermark);
					}
					
					$image_new = $image_watermark;
						
				}
				
				//connect server upload
				$sftp_config['hostname'] 	= $manager_server_upload->server_name;
				$sftp_config['username'] 	= $manager_server_upload->server_ftp_account;
				$sftp_config['password'] 	= $manager_server_upload->server_ftp_pass;
				$sftp_config['port'] 		= $manager_server_upload->server_port;
				$sftp_config['debug'] 		= TRUE;
				$this->sftp->connect($sftp_config);
				
				//upload file new
				$this->sftp->upload(DIR_TEMP.$image_name, $manager_server_upload->server_path_upload_img."/".$image_name);
				$this->sftp->upload(DIR_TEMP.$image_new, $manager_server_upload->server_path_upload_img."/".$image_new);
				$this->sftp->upload(DIR_TEMP.$image_thumnail, $manager_server_upload->server_path_upload_img."/".$image_thumnail);
				
				//get main color
				$main_color = $this->function_model->main_color_image($manager_server_upload->server_path_upload."/".$image_name);
				$id_color = $this->function_model->get_compare_color("#".$main_color);
				$id_colors = $this->function_model->compareColor($manager_server_upload->server_path_upload."/".$image_name);
				
				//delete file temp
				unlink(DIR_TEMP.$image_name);
				unlink(DIR_TEMP.$image_new);
				unlink(DIR_TEMP.$image_thumnail);
				
				//upload luxyart_tb_manager_server + 2
				$this->db->where('id_server', $manager_server_upload->id_server);
				$this->db->set('img_count','img_count+2',FALSE);
				$this->db->update('luxyart_tb_manager_server');
				
				$date_add_img = date('Y-m-d H:i:s');
				
				//add product img
				$dataAddProductImg = array(
					'user_id' 					=> $user_id,
					'img_title' 				=> $img_title,
					'file_name_original_img' 	=> $image_name,
					'file_name_watermark_img' 	=> $image_new,
					'file_name_watermark_img_1' => $image_thumnail,
					'img_file_md5' 				=> $md5_file,
					'img_width' 				=> $width."px",
					'img_height' 				=> $height."px",
					'img_dpi' 					=> $dpi."dpi",
					'img_type'					=> $file_name_original_type,
					'kind_img'					=> $kind_img,
					'id_color'					=> $id_color,
					'id_colors'					=> $id_colors,
					'img_tags' 					=> $val_product_tags,
					'id_server'					=> $manager_server_upload->id_server,
					'option_img_watermark'		=> $type_export_image,
					'option_sale' 				=> $option_sale,
					'is_limit' 					=> $is_limit,
					'date_add_img'				=> $date_add_img,
					'date_active'				=> $date_add_img,	
					'img_check_status'			=> 1,
					'os_register'				=> 3,
					'img_code'					=> $img_code
				);
				$this->db->insert('luxyart_tb_product_img', $dataAddProductImg);
				$id_img = $this->db->insert_id();
				
				$this->function_model->setFilterImg($id_img,$user_id);
				
				//update img link
				$this->db->where('id_img', $id_img);
				$this->db->update('luxyart_tb_product_img', array('img_link' => base_url()."detail/".$id_img.".html"));
				
				//delete img category
				$this->db->where('id_img', $id_img);
				$this->db->delete('luxyart_tb_img_category');
				
				//insert img category
				$category_sub = explode(",", $val_category_sub);
				for($i=0; $i<count($category_sub); $i++){
						
					$parent_cate = $category_sub[$i];
						
					$this->db->from('luxyart_tb_product_category');
					$this->db->select('*');
					$this->db->where(array('product_category_id' => $parent_cate, 'status_cate' => 1, 'lang_id' => $this->lang_id));
					$rsProductCategory = $this->db->get();
				
					if(!empty($rsProductCategory)){
						 
						$resultProductCategory = $rsProductCategory->row();
						$root_cate = $resultProductCategory->root_cate;
						 
						$dataInsertProductCategory = array(
								'id_img' 		=> $id_img,
								'root_cate' 	=> $root_cate,
								'parent_cate' 	=> $parent_cate,
								'sub_cate' 		=> 0
						);
						$this->db->insert('luxyart_tb_img_category', $dataInsertProductCategory);
						 
					}
				
				}
				
				//delete product img size
				$this->db->where('id_img', $id_img);
				$this->db->delete('luxyart_tb_product_img_size');
				
				//insert product img size type s
				$dataInsertProductCategory = array(
   					'id_img' 					=> $id_img,
   					'type_size' 				=> $type_size_s,
   					'type_size_description' 	=> $type_size_width_s."px X ".$type_size_height_s."px",
   					'price_size_point' 			=> $type_size_point_s,
					'size_stock' 				=> $type_size_stock_s,
					'code_download' 			=> $this->function_model->get_code_download($id_img."1"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
	   			);
				if($type_size_s != 0){
			   		$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
				}
			   	
				//insert product img size type m
				$dataInsertProductCategory = array(
					'id_img' 					=> $id_img,
					'type_size' 				=> $type_size_m,
					'type_size_description' 	=> $type_size_width_m."px X ".$type_size_height_m."px",
					'price_size_point' 			=> $type_size_point_m,
					'size_stock' 				=> $type_size_stock_m,
					'code_download' 			=> $this->function_model->get_code_download($id_img."2"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
				);
				if($type_size_m != 0){
					$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
				}
				
				//insert product img size type l
				$dataInsertProductCategory = array(
					'id_img' 					=> $id_img,
					'type_size' 				=> $type_size_l,
					'type_size_description' 	=> $type_size_width_l."px X ".$type_size_height_l."px",
					'price_size_point' 			=> $type_size_point_l,
					'size_stock' 				=> $type_size_stock_l,
					'code_download' 			=> $this->function_model->get_code_download($id_img."2"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
				);
				if($type_size_l != 0){
					$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
				}
				
				$this->db->from('luxyart_tb_product_img');
				$this->db->select('*');
				$this->db->where(array('user_id' => $user_id));
				$result = $this->db->get();
				$count_all_img = $result->num_rows();
				
				$this->db->where('user_id', $user_id);
				//$this->db->set('count_post_img','count_post_img+1',FALSE);
				$this->db->set('count_post_img',$count_all_img);
				$this->db->update('luxyart_tb_user');
				
				//info send email
				if($option_sale == 1){
					$str_option_sale			= "24 ".$this->lang->line('hour');
				}
				elseif($option_sale == 2){
					$str_option_sale			= "1 ".$this->lang->line('week');
				}
				elseif($option_sale == 3){
					$str_option_sale			= "1 ".$this->lang->line('month');
				}
				else{
					$str_option_sale			= $this->lang->line('limited_of_time_expired');
				}
				
				if($type_export_image == 1){
					$str_type_export_image		= $this->lang->line('blur');
				}
				elseif($type_export_image == 2){
					$str_type_export_image		= $this->lang->line('watermark');
				}
			   	
				//send email
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "post_image";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $email_to;
				$dataSendEmail["name"] 					= ucwords($name);
				$dataSendEmail["img_title"]				= $img_title;
				$dataSendEmail["img_width"]				= $img_width;
				$dataSendEmail["img_height"]			= $img_height;
				$dataSendEmail["option_sale"]			= $str_option_sale;
				$dataSendEmail["type_export_image"]		= $str_type_export_image;
				$dataSendEmail["link"]					= base_url()."detail/".$img_code.".html";
				$dataSendEmail["is_send_admin"]			= 1;

				if($id_img > 0){
					if($email_to != "fermatandrew@gmail.com"){
						$this->function_model->sendMailHTML($dataSendEmail);
					}
				}
				
				//send notice
				$addNotice = array();
				$addNotice['type'] 						= "post_image";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_id;
				$addNotice['img_title'] 				= $img_title;
				$addNotice['link'] 						= base_url()."detail/".$img_code.".html";
				$addNotice['option_id'] 				= $id_img;

				if($id_img > 0){
					$this->function_model->addNotice($addNotice);
				}
				
				//get meta seo
				$_SESSION['return_key'] = 'images/post_image_success';
				header("Location: ".base_url()."notice.html");
				
			}
			else{
				
				$_SESSION['return_key'] = 'images/post_image_success';
				header("Location: ".base_url()."notice.html");
				
			}
			
			exit;
			
		}
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/post_image", $meta, $this->lang_id);
		
		//get product tags
		$whereProductTags = array('status' => 1, 'lang_id' => $this->lang_id);
    	$orderProductTags = array();
    	$data['list_product_tags'] = $this->main_model->getAllData("luxyart_tb_product_tags", $whereProductTags, $orderProductTags)->result();
    	
    	//get product color
    	$whereProductColor = array('status' => 1, 'lang_id' => $this->lang_id);
    	$orderProductColor = array();
    	$data['list_product_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->result();
    	
    	//get product category
    	$whereProductCategory = array('level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1);
    	$orderProductCategory = array();
    	$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
    	
    	$data['active_menu_sidebar'] = "mypage-post-image";
    	
    	$data['noindex'] = '1';
		
    	$this->template->load('default', 'post_image', $data, __CLASS__);

	}
	
	public function post_image_test(){
		
		$this->load->library('sftp');
		
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		$check_is_post_img = $this->function_model->check_is_post_img($user_id);
		$check_is_post_img_explode = explode("{luxyart}",$check_is_post_img);
		$is_post_img = $check_is_post_img_explode[0];
		$user_level = $check_is_post_img_explode[1];
		
		$num_allow_post_image = $this->function_model->num_allow_post_image($user_id);
		$data["num_allow_post_image"] = $num_allow_post_image;
		
		if($is_post_img == 0){
			if($user_level == 1){
				$_SESSION['return_key'] = 'please_upgrade_to_creator_or_enterprise';
				header("Location: ".base_url()."notice.html");
			}
			elseif($user_level == 2){
				$_SESSION['return_key'] = 'please_upgrade_to_enterprise';
				header("Location: ".base_url()."notice.html");
			}
			else{
				$_SESSION['return_key'] = 'number_of_picture_exceeded_the_number_of_allowed';
				header("Location: ".base_url()."notice.html");
			}
		}
		
		$user = $this->function_model->getUser($user_id);
		$user_level = $user->user_level;
		$country_id = $user->country_id;
		$email_to = $user->user_email;
		$name = $this->function_model->get_fullname($user->user_id, $this->lang_id);
		
		$setting = $this->function_model->get_setting();
		
		$data['allowed_image_formats'] = $setting['allowed_image_formats'];
		$data['max_mb'] = $setting['max_mb'];
		$data['min_width_img'] = $setting['min_width_img'];
		$data['min_height_img'] = $setting['min_height_img'];
		$data['min_width_img_thumnail'] = $setting['min_width_img_thumnail'];
		$data['min_height_img_thumnail'] = $setting['min_height_img_thumnail'];
		$data['percent_image_pixel'] = $setting['percent_image_pixel'];
		$data['date_begin_random_image'] = $setting['date_begin_random_image'];
		$data['date_end_random_image'] = $setting['date_end_random_image'];
		
		if(isset($_POST['btnPostImage'])){
			
			$img_title 						= trim($_POST["img_title"]);
			
			$val_category 					= $_POST["val_category"];
			$val_category_sub 				= $_POST["val_category_sub"];
			
			//$file_name_original_img
			$type_export_image 				= $_POST["type_export_image"];
			$img_width 						= $_POST["img_width"];
			$img_height 					= $_POST["img_height"];
			$option_sale 					= $_POST["option_sale"];
			$is_limit 						= isset($_POST["is_limit"]) ? 1 : 0;
			
			$product_tags 					= $_POST["product_tags"];
			
			//type s
			$type_size_s 					= $_POST["type_size_s"];
			$type_size_width_s 				= $_POST["type_size_width_s"];
			$type_size_height_s 			= $_POST["type_size_height_s"];
			$type_size_point_s 				= $_POST["type_size_point_s"];
			$type_size_stock_s 				= isset($_POST["type_size_stock_s"]) ? $_POST["type_size_stock_s"] : 0;
			
			$type_size_width_s 				= str_replace(",","",$type_size_width_s);
			$type_size_height_s 			= str_replace(",","",$type_size_height_s);
			$type_size_point_s 				= str_replace(",","",$type_size_point_s);
			$type_size_stock_s 				= str_replace(",","",$type_size_stock_s);
			
			//type m
			$type_size_m 					= $_POST["type_size_m"];
			$type_size_width_m 				= $_POST["type_size_width_m"];
			$type_size_height_m 			= $_POST["type_size_height_m"];
			$type_size_point_m 				= $_POST["type_size_point_m"];
			$type_size_stock_m 				= isset($_POST["type_size_stock_m"]) ? $_POST["type_size_stock_m"] : 0;
			
			$type_size_width_m 				= str_replace(",","",$type_size_width_m);
			$type_size_height_m 			= str_replace(",","",$type_size_height_m);
			$type_size_point_m 				= str_replace(",","",$type_size_point_m);
			$type_size_stock_m 				= str_replace(",","",$type_size_stock_m);
			
			//type l
			$type_size_l 					= $_POST["type_size_l"];
			$type_size_width_l 				= $_POST["type_size_width_l"];
			$type_size_height_l 			= $_POST["type_size_height_l"];
			$type_size_point_l 				= $_POST["type_size_point_l"];
			$type_size_stock_l 				= isset($_POST["type_size_stock_l"]) ? $_POST["type_size_stock_l"] : 0;
			
			$type_size_width_l 				= str_replace(",","",$type_size_width_l);
			$type_size_height_l 			= str_replace(",","",$type_size_height_l);
			$type_size_point_l 				= str_replace(",","",$type_size_point_l);
			$type_size_stock_l 				= str_replace(",","",$type_size_stock_l);
			
			$img_code						= $this->function_model->get_code_image();
			
			$val_product_tags = implode(",",$product_tags);
			
			//upload image
			$file_name_original_img 		= $_FILES["file_name_original_img"];
			
			//choose server upload image
			$manager_server_upload = $this->function_model->getManagerServerUpload($this->lang_id);
			
			$file_name_original_type = $file_name_original_img['type'];
			$file_name_original_type = trim(str_replace("image/", "", $file_name_original_type));
			if($file_name_original_type == "jpeg"){
				$file_name_original_type = "jpg";
			}
			
			$time = time();
			$image_name = $this->function_model->get_image_name().'.'.$file_name_original_type;
			
			$rand = rand(100000,999999);
			
			$image_watermark_no_extension = $time.$rand.'-watermark';
			$image_watermark = $time.$rand.'-watermark.jpg';
				
			$image_blur_no_extension = $time.$rand.'-blur';
			$image_blur = $time.$rand.'-blur.jpg';
			
			$image_thumnail_no_extension = $time.$rand.'-thumnail';
			$image_thumnail = $time.$rand.'-thumnail.jpg';
			
			if($_FILES['file_name_original_img']['tmp_name'] != ""){
				
				move_uploaded_file($_FILES['file_name_original_img']['tmp_name'], DIR_TEMP.$image_name);
			
			}
			
			$md5_file = md5_file(DIR_TEMP.$image_name);
			
			if($this->function_model->check_md5_file($md5_file) == 0){
				
				$bytes = filesize(DIR_TEMP.$image_name);
				$mb = $this->function_model->byte_convert_to_mb($bytes);
				$resolution = $this->function_model->get_dpi(DIR_TEMP.$image_name);
				
				if($resolution[0] == $resolution[1] && $dpi > 36){
					$dpi = $resolution[0];
				}
				else{
					$dpi = 0;
				}
				
				list($width, $height, $type, $attr) = getimagesize(DIR_TEMP.$image_name);
				
				if($width >= $height){
					$kind_img = 1;
				}
				else{
					$kind_img = 2;
				}
				
				if($type_export_image == 1){
					
					$dir = DIR_TEMP.$image_name;
					$this->function_model->fix_rotate_image($dir,$dir);
					
					$dir_end = DIR_TEMP.$image_blur;
					$ext = strtolower(pathinfo($dir, PATHINFO_EXTENSION));
						
					switch($ext) {
						case 'gif':
							$img = imagecreatefromgif($dir);
							break;
						case 'jpg':
							$img = imagecreatefromjpeg($dir);
							break;
						case 'png':
							$img = imagecreatefrompng($dir);
							break;
					}
					imagejpeg($img, $dir_end, 100);
					
					$this->function_model->convert_to_pixel(DIR_TEMP."/".$image_blur, DIR_TEMP."/".$image_blur, $data['percent_image_pixel']);
					
					if($kind_img == 1 && $width > $data['min_width_img']){
						$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_blur_no_extension, DIR_TEMP.$image_blur);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img']){
						$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_blur_no_extension, DIR_TEMP.$image_blur);
					}
					
					if($kind_img == 1 && $width > $data['min_width_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_blur);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_blur);
					}
					
					$image_new = $image_blur;
					
				}
				else if($type_export_image == 2){
									
					$dir = DIR_TEMP.$image_name;
					$this->function_model->fix_rotate_image($dir,$dir);
					
					$dir_end = DIR_TEMP.$image_watermark;
					$ext = strtolower(pathinfo($dir, PATHINFO_EXTENSION));
					
					switch($ext) {
						case 'gif':
							$img = imagecreatefromgif($dir);
							break;
						case 'jpg':
							$img = imagecreatefromjpeg($dir);
							break;
						case 'png':
							$img = imagecreatefrompng($dir);
							break;
					}
					imagejpeg($img, $dir_end, 100);
					
					if($kind_img == 1 && $width > $data['min_width_img']){
						$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_watermark_no_extension, DIR_TEMP.$image_watermark);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img']){
						$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_watermark_no_extension, DIR_TEMP.$image_watermark);
					}
						
					$this->function_model->watermark_image(DIR_TEMP.$image_watermark_no_extension, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 0);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 0);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 1);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 2);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 3);
					$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 4);
						
					if($kind_img == 1 && $width > $data['min_width_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_watermark);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_watermark);
					}
					
					$image_new = $image_watermark;
						
				}
				
				//connect server upload
				$sftp_config['hostname'] 	= $manager_server_upload->server_name;
				$sftp_config['username'] 	= $manager_server_upload->server_ftp_account;
				$sftp_config['password'] 	= $manager_server_upload->server_ftp_pass;
				$sftp_config['port'] 		= $manager_server_upload->server_port;
				$sftp_config['debug'] 		= TRUE;
				
				//upload file new
				$this->sftp->upload(DIR_TEMP.$image_name, $manager_server_upload->server_path_upload_img."/".$image_name);
				$this->sftp->upload(DIR_TEMP.$image_new, $manager_server_upload->server_path_upload_img."/".$image_new);
				$this->sftp->upload(DIR_TEMP.$image_thumnail, $manager_server_upload->server_path_upload_img."/".$image_thumnail);
				
				//get main color
				$main_color = $this->function_model->main_color_image($manager_server_upload->server_path_upload."/".$image_name);
				$id_color = $this->function_model->get_compare_color("#".$main_color);
				$id_colors = $this->function_model->compareColor($manager_server_upload->server_path_upload."/".$image_name);
				
				//delete file temp
				unlink(DIR_TEMP.$image_name);
				unlink(DIR_TEMP.$image_new);
				unlink(DIR_TEMP.$image_thumnail);
				
				//upload luxyart_tb_manager_server + 2
				$this->db->where('id_server', $manager_server_upload->id_server);
				$this->db->set('img_count','img_count+2',FALSE);
				$this->db->update('luxyart_tb_manager_server');
				
				$date_add_img = date('Y-m-d H:i:s');
				
				//add product img
				$dataAddProductImg = array(
					'user_id' 					=> $user_id,
					'img_title' 				=> $img_title,
					'file_name_original_img' 	=> $image_name,
					'file_name_watermark_img' 	=> $image_new,
					'file_name_watermark_img_1' => $image_thumnail,
					'img_file_md5' 				=> $md5_file,
					'img_width' 				=> $width."px",
					'img_height' 				=> $height."px",
					'img_dpi' 					=> $dpi."dpi",
					'img_type'					=> $file_name_original_type,
					'kind_img'					=> $kind_img,
					'id_color'					=> $id_color,
					'id_colors'					=> $id_colors,
					'img_tags' 					=> $val_product_tags,
					'id_server'					=> $manager_server_upload->id_server,
					'option_img_watermark'		=> $type_export_image,
					'option_sale' 				=> $option_sale,
					'is_limit' 					=> $is_limit,
					'date_add_img'				=> $date_add_img,
					'date_active'				=> $date_add_img,	
					'img_check_status'			=> 1,
					'os_register'				=> 3,
					'img_code'					=> $img_code
				);
				$this->db->insert('luxyart_tb_product_img', $dataAddProductImg);
				$id_img = $this->db->insert_id();
				
				$this->function_model->setFilterImg($id_img,$user_id);
				
				//update img link
				$this->db->where('id_img', $id_img);
				$this->db->update('luxyart_tb_product_img', array('img_link' => base_url()."detail/".$id_img.".html"));
				
				//delete img category
				$this->db->where('id_img', $id_img);
				$this->db->delete('luxyart_tb_img_category');
				
				//insert img category
				$category_sub = explode(",", $val_category_sub);
				for($i=0; $i<count($category_sub); $i++){
						
					$parent_cate = $category_sub[$i];
						
					$this->db->from('luxyart_tb_product_category');
					$this->db->select('*');
					$this->db->where(array('product_category_id' => $parent_cate, 'status_cate' => 1, 'lang_id' => $this->lang_id));
					$rsProductCategory = $this->db->get();
				
					if(!empty($rsProductCategory)){
						 
						$resultProductCategory = $rsProductCategory->row();
						$root_cate = $resultProductCategory->root_cate;
						 
						$dataInsertProductCategory = array(
								'id_img' 		=> $id_img,
								'root_cate' 	=> $root_cate,
								'parent_cate' 	=> $parent_cate,
								'sub_cate' 		=> 0
						);
						$this->db->insert('luxyart_tb_img_category', $dataInsertProductCategory);
						 
					}
				
				}
				
				//delete product img size
				$this->db->where('id_img', $id_img);
				$this->db->delete('luxyart_tb_product_img_size');
				
				//insert product img size type s
				$dataInsertProductCategory = array(
   					'id_img' 					=> $id_img,
   					'type_size' 				=> $type_size_s,
   					'type_size_description' 	=> $type_size_width_s."px X ".$type_size_height_s."px",
   					'price_size_point' 			=> $type_size_point_s,
					'size_stock' 				=> $type_size_stock_s,
					'code_download' 			=> $this->function_model->get_code_download($id_img."1"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
	   			);
				if($type_size_s != 0){
			   		$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
				}
			   	
				//insert product img size type m
				$dataInsertProductCategory = array(
					'id_img' 					=> $id_img,
					'type_size' 				=> $type_size_m,
					'type_size_description' 	=> $type_size_width_m."px X ".$type_size_height_m."px",
					'price_size_point' 			=> $type_size_point_m,
					'size_stock' 				=> $type_size_stock_m,
					'code_download' 			=> $this->function_model->get_code_download($id_img."2"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
				);
				if($type_size_m != 0){
					$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
				}
				
				//insert product img size type l
				$dataInsertProductCategory = array(
					'id_img' 					=> $id_img,
					'type_size' 				=> $type_size_l,
					'type_size_description' 	=> $type_size_width_l."px X ".$type_size_height_l."px",
					'price_size_point' 			=> $type_size_point_l,
					'size_stock' 				=> $type_size_stock_l,
					'code_download' 			=> $this->function_model->get_code_download($id_img."2"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
				);
				if($type_size_l != 0){
					$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
				}
				
				$this->db->from('luxyart_tb_product_img');
				$this->db->select('*');
				$this->db->where(array('user_id' => $user_id));
				$result = $this->db->get();
				$count_all_img = $result->num_rows();
				
				$this->db->where('user_id', $user_id);
				//$this->db->set('count_post_img','count_post_img+1',FALSE);
				$this->db->set('count_post_img',$count_all_img);
				$this->db->update('luxyart_tb_user');
				
				//info send email
				if($option_sale == 1){
					$str_option_sale			= "24 ".$this->lang->line('hour');
				}
				elseif($option_sale == 2){
					$str_option_sale			= "1 ".$this->lang->line('week');
				}
				elseif($option_sale == 3){
					$str_option_sale			= "1 ".$this->lang->line('month');
				}
				else{
					$str_option_sale			= $this->lang->line('limited_of_time_expired');
				}
				
				if($type_export_image == 1){
					$str_type_export_image		= $this->lang->line('blur');
				}
				elseif($type_export_image == 2){
					$str_type_export_image		= $this->lang->line('watermark');
				}
			   	
				//send email
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "post_image";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $email_to;
				$dataSendEmail["name"] 					= ucwords($name);
				$dataSendEmail["img_title"]				= $img_title;
				$dataSendEmail["img_width"]				= $img_width;
				$dataSendEmail["img_height"]			= $img_height;
				$dataSendEmail["option_sale"]			= $str_option_sale;
				$dataSendEmail["type_export_image"]		= $str_type_export_image;
				$dataSendEmail["link"]					= base_url()."detail/".$img_code.".html";
				$dataSendEmail["is_send_admin"]			= 1;

				if($id_img > 0){
					if($email_to != "fermatandrew@gmail.com"){
						$this->function_model->sendMailHTML($dataSendEmail);
					}
				}
				
				//send notice
				$addNotice = array();
				$addNotice['type'] 						= "post_image";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_id;
				$addNotice['img_title'] 				= $img_title;
				$addNotice['link'] 						= base_url()."detail/".$img_code.".html";
				$addNotice['option_id'] 				= $id_img;

				if($id_img > 0){
					$this->function_model->addNotice($addNotice);
				}
				
				//get meta seo
				$_SESSION['return_key'] = 'images/post_image_success';
				header("Location: ".base_url()."notice.html");
				
			}
			else{
				
				$_SESSION['return_key'] = 'images/post_image_success';
				header("Location: ".base_url()."notice.html");
				
			}
			
			exit;
			
		}
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/post_image", $meta, $this->lang_id);
		
		//get product tags
		$whereProductTags = array('status' => 1, 'lang_id' => $this->lang_id);
    	$orderProductTags = array();
    	$data['list_product_tags'] = $this->main_model->getAllData("luxyart_tb_product_tags", $whereProductTags, $orderProductTags)->result();
    	
    	//get product color
    	$whereProductColor = array('status' => 1, 'lang_id' => $this->lang_id);
    	$orderProductColor = array();
    	$data['list_product_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->result();
    	
    	//get product category
    	$whereProductCategory = array('level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1);
    	$orderProductCategory = array();
    	$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
    	
    	$data['active_menu_sidebar'] = "mypage-post-image";
    	
    	$data['noindex'] = '1';
		
    	$this->template->load('default', 'post_image', $data, __CLASS__);

	}
	
	public function edit_image($id_img){

		$this->load->library('sftp');
	
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
	
		$user = $this->function_model->getUser($user_id);
		$user_level = $user->user_level;
		$country_id = $user->country_id;
		$email_to = $user->user_email;
		$name = $this->function_model->get_fullname($user->user_id, $this->lang_id);
		$setting = $this->function_model->get_setting();
		
		$data['allowed_image_formats'] = $setting['allowed_image_formats'];
		$data['max_mb'] = $setting['max_mb'];
		$data['min_width_img'] = $setting['min_width_img'];
		$data['min_height_img'] = $setting['min_height_img'];
		$data['min_width_img_thumnail'] = $setting['min_width_img_thumnail'];
		$data['min_height_img_thumnail'] = $setting['min_height_img_thumnail'];
		$data['percent_image_pixel'] = $setting['percent_image_pixel'];
		
		//get product img
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		
		if($user_id != $data['detail_product']->user_id){
			
			//notice
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
			exit;
		}
		
		if($data['detail_product']->img_is_join_compe == 1){
			
			//notice
			$_SESSION['return_key'] = 'image_is_join_competition_you_can_not_edit_this_image';
			header("Location: ".base_url()."notice.html");
			exit;
		}
		
		if(isset($_POST['btnEditImage'])){
			
			$img_title 						= trim($_POST["img_title"]);
				
			$val_category 					= $_POST["val_category"];
			$val_category_sub 				= $_POST["val_category_sub"];
				
			//$file_name_original_img
			$type_export_image 				= $_POST["type_export_image"];
			$type_export_image_old 			= $_POST["type_export_image_old"];
			$img_width 						= $_POST["img_width"];
			$img_height 					= $_POST["img_height"];
			$option_sale 					= $_POST["option_sale"];
			$is_limit 						= isset($_POST["is_limit"]) ? 1 : 0;
				
			$product_tags 					= $_POST["product_tags"];
				
			//type s
			$type_size_s 					= isset($_POST["type_size_s"]) ? $_POST["type_size_s"] : "";
			$type_size_width_s 				= $_POST["type_size_width_s"];
			$type_size_height_s 			= $_POST["type_size_height_s"];
			$type_size_point_s 				= $_POST["type_size_point_s"];
			$type_size_stock_s 				= isset($_POST["type_size_stock_s"]) ? $_POST["type_size_stock_s"] : 0;
			
			$type_size_width_s 				= str_replace(",","",$type_size_width_s);
			$type_size_height_s 			= str_replace(",","",$type_size_height_s);
			$type_size_point_s 				= str_replace(",","",$type_size_point_s);
			$type_size_stock_s 				= str_replace(",","",$type_size_stock_s);
				
			//type m
			$type_size_m 					= isset($_POST["type_size_m"]) ? $_POST["type_size_m"] : "";
			$type_size_width_m 				= $_POST["type_size_width_m"];
			$type_size_height_m 			= $_POST["type_size_height_m"];
			$type_size_point_m 				= $_POST["type_size_point_m"];
			$type_size_stock_m 				= isset($_POST["type_size_stock_m"]) ? $_POST["type_size_stock_m"] : 0;
			
			$type_size_width_m 				= str_replace(",","",$type_size_width_m);
			$type_size_height_m 			= str_replace(",","",$type_size_height_m);
			$type_size_point_m 				= str_replace(",","",$type_size_point_m);
			$type_size_stock_m 				= str_replace(",","",$type_size_stock_m);
				
			//type l
			$type_size_l 					= isset($_POST["type_size_l"]) ? $_POST["type_size_l"] : "";
			$type_size_width_l 				= $_POST["type_size_width_l"];
			$type_size_height_l 			= $_POST["type_size_height_l"];
			$type_size_point_l 				= $_POST["type_size_point_l"];
			$type_size_stock_l 				= isset($_POST["type_size_stock_l"]) ? $_POST["type_size_stock_l"] : 0;
			
			$type_size_width_l 				= str_replace(",","",$type_size_width_l);
			$type_size_height_l 			= str_replace(",","",$type_size_height_l);
			$type_size_point_l 				= str_replace(",","",$type_size_point_l);
			$type_size_stock_l 				= str_replace(",","",$type_size_stock_l);
				
			$val_product_tags = implode(",",$product_tags);
				
			//upload image
			$file_name_original_img 		= $_FILES["file_name_original_img"];
			
			//choose server upload image
			$manager_server_upload = $this->function_model->getManagerServerUpload($this->lang_id);
			
			$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
			
			if($file_name_original_img['name'] != "" || $type_export_image_old != $type_export_image){
				
				if($type_export_image_old != $type_export_image && $file_name_original_img['name'] == ""){

					//get product img
					$file_name_original_type = $data['detail_product']->img_type;
					
				}
				else{
					
					$file_name_original_type = $file_name_original_img['type'];
					$file_name_original_type = trim(str_replace("image/", "", $file_name_original_type));
					if($file_name_original_type == "jpeg"){
						$file_name_original_type = "jpg";
					}
					
				}
				
				$time = time();
				
				if($type_export_image_old != $type_export_image && $file_name_original_img['name'] == ""){
					$image_name = $data['detail_product']->file_name_original_img;
				}
				else{
					$image_name = $this->function_model->get_image_name().'.'.$file_name_original_type;
				}
				
				$rand = rand(100000,999999);
				
				$image_watermark_no_extension = $time.$rand.'-watermark';
				$image_watermark = $time.$rand.'-watermark.jpg';
				
				$image_blur_no_extension = $time.$rand.'-blur';
				$image_blur = $time.$rand.'-blur.jpg';
				
				$image_thumnail_no_extension = $time.$rand.'-thumnail';
				$image_thumnail = $time.$rand.'-thumnail.jpg';
				
				if($_FILES['file_name_original_img']['tmp_name'] != ""){
					move_uploaded_file($_FILES['file_name_original_img']['tmp_name'], DIR_TEMP.$image_name);
				}
				else{
					
					//copy(DIR."/product_img/".$image_name, DIR_TEMP.$image_name);
					$manager_server_old = $this->function_model->getManagerServer($data['detail_product']->id_server);
					
					//connect server old
					$sftp_config['hostname'] 	= $manager_server_old->server_name;
					$sftp_config['username'] 	= $manager_server_old->server_ftp_account;
					$sftp_config['password'] 	= $manager_server_old->server_ftp_pass;
					$sftp_config['port'] 		= $manager_server_old->server_port;
					$sftp_config['debug'] 		= TRUE;
					$this->sftp->connect($sftp_config);
					
					$this->sftp->download($manager_server_old->server_path_upload_img."/".$image_name, DIR_TEMP.$image_name);
					
				}
				
				$md5_file = md5_file(DIR_TEMP.$image_name);
				
				if($this->function_model->check_md5_file_edit($md5_file, $id_img) == 0){
					
					$bytes = filesize(DIR_TEMP.$image_name);
					$mb = $this->function_model->byte_convert_to_mb($bytes);
					$resolution = $this->function_model->get_dpi(DIR_TEMP.$image_name);
					
					if($resolution[0] == $resolution[1] && $dpi > 36){
						$dpi = $resolution[0];
					}
					else{
						$dpi = 0;
					}
					
					list($width, $height, $type, $attr) = getimagesize(DIR_TEMP.$image_name);
					
					if($width >= $height){
						$kind_img = 1;
					}
					else{
						$kind_img = 2;
					}
					
					//$this->function_model->repair_fix_orientation(DIR_TEMP.$image_name, "abc.png");
					//echo 1111111111111;
					//exit;
					
					if($type_export_image == 1){
						
						$dir = DIR_TEMP.$image_name;
						$this->function_model->fix_rotate_image($dir,$dir);
						
						$dir_end = DIR_TEMP.$image_blur;
						$ext = strtolower(pathinfo($dir, PATHINFO_EXTENSION));
							
						switch($ext) {
							case 'gif':
								$img = imagecreatefromgif($dir);
								break;
							case 'jpg':
								$img = imagecreatefromjpeg($dir);
								break;
							case 'png':
								$img = imagecreatefrompng($dir);
								break;
						}
							
						imagejpeg($img, $dir_end, 100);
						
						$this->function_model->convert_to_pixel(DIR_TEMP."/".$image_blur, DIR_TEMP."/".$image_blur, $data['percent_image_pixel']);
							
						if($kind_img == 1 && $width > $data['min_width_img']){
							$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_blur_no_extension, DIR_TEMP.$image_blur);
						}
						elseif($kind_img == 2 && $height > $data['min_height_img']){
							$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_blur_no_extension, DIR_TEMP.$image_blur);
						}
							
						if($kind_img == 1 && $width > $data['min_width_img_thumnail']){
							$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_blur);
						}
						elseif($kind_img == 2 && $height > $data['min_height_img_thumnail']){
							$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_blur);
						}
							
						$image_new = $image_blur;
							
					}
					else if($type_export_image == 2){
							
						$dir = DIR_TEMP.$image_name;
						$this->function_model->fix_rotate_image($dir,$dir);
						
						$dir_end = DIR_TEMP.$image_watermark;
						$ext = strtolower(pathinfo($dir, PATHINFO_EXTENSION));
					
						switch($ext) {
							case 'gif':
								$img = imagecreatefromgif($dir);
								break;
							case 'jpg':
								$img = imagecreatefromjpeg($dir);
								break;
							case 'png':
								$img = imagecreatefrompng($dir);
								break;
						}
					
						imagejpeg($img, $dir_end, 100);
						if($kind_img == 1 && $width > $data['min_width_img']){
							$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_watermark_no_extension, DIR_TEMP.$image_watermark);
						}
						elseif($kind_img == 2 && $height > $data['min_height_img']){
							$this->function_model->resize_width($data['min_width_img'], DIR_TEMP.$image_watermark_no_extension, DIR_TEMP.$image_watermark);
						}
					
						$this->function_model->watermark_image(DIR_TEMP.$image_watermark_no_extension, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 0);
						$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 0);
						$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 1);
						$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 2);
						$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 3);
						$this->function_model->watermark_image(DIR_TEMP."/".$image_watermark, DIR.'img/watermark.png', DIR_TEMP."/".$image_watermark, 4);
						$image_new = $image_watermark;
					
						if($kind_img == 1 && $width > $data['min_width_img_thumnail']){
							$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_watermark);
						}
						elseif($kind_img == 2 && $height > $data['min_height_img_thumnail']){
							$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_watermark);
						}
					
					}
					
				}
				$manager_server_old = $this->function_model->getManagerServer($data['detail_product']->id_server);
				
				if($manager_server_old->server_name == $manager_server_upload->server_name){
					
					//connect server upload
					$sftp_config['hostname'] 	= $manager_server_upload->server_name;
					$sftp_config['username'] 	= $manager_server_upload->server_ftp_account;
					$sftp_config['password'] 	= $manager_server_upload->server_ftp_pass;
					$sftp_config['port'] 		= $manager_server_upload->server_port;
					$sftp_config['debug'] 		= TRUE;
					$this->sftp->connect($sftp_config);
					
					//delete file old
					if($this->sftp->check_file($data['detail_product']->file_name_original_img, $manager_server_upload->server_path_upload_img) != ""){
						$this->sftp->delete_file($manager_server_upload->server_path_upload_img."/".$data['detail_product']->file_name_original_img);
					}
					
					if($this->sftp->check_file($data['detail_product']->file_name_watermark_img, $manager_server_upload->server_path_upload_img) != ""){
						$this->sftp->delete_file($manager_server_upload->server_path_upload_img."/".$data['detail_product']->file_name_watermark_img);
					}
					
					if($data['detail_product']->file_name_watermark_img_1 != ""){
						if($this->sftp->check_file($data['detail_product']->file_name_watermark_img_1, $manager_server_upload->server_path_upload_img) != ""){
							$this->sftp->delete_file($manager_server_upload->server_path_upload_img."/".$data['detail_product']->file_name_watermark_img_1);
						}
					}
					
					//upload file new
					$this->sftp->upload(DIR_TEMP.$image_name, $manager_server_upload->server_path_upload_img."/".$image_name);
					$this->sftp->upload(DIR_TEMP.$image_new, $manager_server_upload->server_path_upload_img."/".$image_new);
					$this->sftp->upload(DIR_TEMP.$image_thumnail, $manager_server_upload->server_path_upload_img."/".$image_thumnail);
					
				}
				else{
					
					//connect server old
					$sftp_config['hostname'] 	= $manager_server_old->server_name;
					$sftp_config['username'] 	= $manager_server_old->server_ftp_account;
					$sftp_config['password'] 	= $manager_server_old->server_ftp_pass;
					$sftp_config['port'] 		= $manager_server_old->server_port;
					$sftp_config['debug'] 		= TRUE;
					$this->sftp->connect($sftp_config);
					
					//delete file old
					if($this->sftp->check_file($data['detail_product']->file_name_original_img, $manager_server_old->server_path_upload_img) != ""){
						$this->sftp->delete_file($manager_server_old->server_path_upload_img."/".$data['detail_product']->file_name_original_img);
					}
						
					if($this->sftp->check_file($data['detail_product']->file_name_watermark_img, $manager_server_old->server_path_upload_img) != ""){
						$this->sftp->delete_file($manager_server_old->server_path_upload_img."/".$data['detail_product']->file_name_watermark_img);
					}
					
					if($data['detail_product']->file_name_watermark_img_1 != ""){
						if($this->sftp->check_file($data['detail_product']->file_name_watermark_img_1, $manager_server_old->server_path_upload_img) != ""){
							$this->sftp->delete_file($manager_server_old->server_path_upload_img."/".$data['detail_product']->file_name_watermark_img_1);
						}
					}
					
					//connect server new
					$sftp_config['hostname'] 	= $manager_server_upload->server_name;
					$sftp_config['username'] 	= $manager_server_upload->server_ftp_account;
					$sftp_config['password'] 	= $manager_server_upload->server_ftp_pass;
					$sftp_config['port'] 		= $manager_server_upload->server_port;
					$sftp_config['debug'] 		= TRUE;
					$this->sftp->connect($sftp_config);
					
					//upload file new
					$this->sftp->upload(DIR_TEMP.$image_name, $manager_server_upload->server_path_upload_img."/".$image_name);
					$this->sftp->upload(DIR_TEMP.$image_new, $manager_server_upload->server_path_upload_img."/".$image_new);
					$this->sftp->upload(DIR_TEMP.$image_thumnail, $manager_server_upload->server_path_upload_img."/".$image_thumnail);
					
				}
				
				//get main color
				$main_color = $this->function_model->main_color_image($manager_server_upload->server_path_upload."/".$image_name);
				$id_color = $this->function_model->get_compare_color("#".$main_color);
				$id_colors = $this->function_model->compareColor($manager_server_upload->server_path_upload."/".$image_name);
				
				//delete file temp
				unlink(DIR_TEMP.$image_name);
				unlink(DIR_TEMP.$image_new);
				unlink(DIR_TEMP.$image_thumnail);
				
			}
			
			//update data
			$date_update_img = date('Y-m-d H:i:s');
				
			if($type_export_image_old != $type_export_image || $file_name_original_img['name'] != ""){
				
				$dataEditProductImg = array(
						'img_title' 				=> $img_title,
						'file_name_original_img' 	=> $image_name,
						'file_name_watermark_img' 	=> $image_new,
						'file_name_watermark_img_1' => $image_thumnail,
						'img_file_md5' 				=> $md5_file,
						'img_width' 				=> $width."px",
						'img_height' 				=> $height."px",
						'img_dpi' 					=> $dpi."dpi",
						'img_type'					=> $file_name_original_type,
						'kind_img'					=> $kind_img,
						'id_color'					=> $id_color,
						'id_colors'					=> $id_colors,
						'img_tags' 					=> $val_product_tags,
						'id_server'					=> $manager_server_upload->id_server,
						'option_img_watermark'		=> $type_export_image,
						'option_sale' 				=> $option_sale,
						'is_limit' 					=> $is_limit,
						'date_update_img'			=> $date_update_img
				);
				
				$this->db->where('id_img', $id_img);
				$this->db->update('luxyart_tb_product_img', $dataEditProductImg);
				
			}
			else{
				
				$dataEditProductImg = array(
						'img_title' 				=> $img_title,
						'id_color'					=> $id_color,
						'img_tags' 					=> $val_product_tags,
						'option_img_watermark'		=> $type_export_image,
						'option_sale' 				=> $option_sale,
						'is_limit' 					=> $is_limit,
						'date_update_img'			=> $date_update_img
				);
				
				$this->db->where('id_img', $id_img);
				$this->db->update('luxyart_tb_product_img', $dataEditProductImg);
				
			}
			
			//set img is sold
			if($is_limit == 0){
				$this->db->where('id_img', $id_img);
				$this->db->set('img_is_sold',0);
				$this->db->update('luxyart_tb_product_img');
			}
			else{
				if($type_size_stock_s + $type_size_stock_m + $type_size_stock_l > 0){
					$this->db->where('id_img', $id_img);
					$this->db->set('img_is_sold',0);
					$this->db->update('luxyart_tb_product_img');
				}
			}
			
			//delete img category
			$this->db->where('id_img', $id_img);
			$this->db->delete('luxyart_tb_img_category');
			
			//insert img category
			$category_sub = explode(",", $val_category_sub);
			
			for($i=0; $i<count($category_sub); $i++){
			
				$parent_cate = $category_sub[$i];
			
				$this->db->from('luxyart_tb_product_category');
				$this->db->select('*');
				$this->db->where(array('product_category_id' => $parent_cate, 'status_cate' => 1, 'lang_id' => $this->lang_id));
				$rsProductCategory = $this->db->get();
			
				if(!empty($rsProductCategory)){
						
					$resultProductCategory = $rsProductCategory->row();
					$root_cate = $resultProductCategory->root_cate;
						
					$dataInsertProductCategory = array(
							'id_img' 		=> $id_img,
							'root_cate' 	=> $root_cate,
							'parent_cate' 	=> $parent_cate,
							'sub_cate' 		=> 0
					);
					$this->db->insert('luxyart_tb_img_category', $dataInsertProductCategory);
						
				}
			
			}
			
			//update product img size
			$dataEditProductImgSize = array(
					'status_img_size' 			=> 1
			);
			
			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_product_img_size', $dataEditProductImgSize);
			
			//insert product img size type s
			$dataInsertProductCategory = array(
					'id_img' 					=> $id_img,
					'type_size' 				=> $type_size_s,
					'type_size_description' 	=> $type_size_width_s."px X ".$type_size_height_s."px",
					'price_size_point' 			=> $type_size_point_s,
					'size_stock' 				=> $type_size_stock_s,
					'code_download' 			=> $this->function_model->get_code_download($id_img."1"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
			);
			if($type_size_s != 0){
				$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
			}
				
			//insert product img size type m
			$dataInsertProductCategory = array(
					'id_img' 					=> $id_img,
					'type_size' 				=> $type_size_m,
					'type_size_description' 	=> $type_size_width_m."px X ".$type_size_height_m."px",
					'price_size_point' 			=> $type_size_point_m,
					'size_stock' 				=> $type_size_stock_m,
					'code_download' 			=> $this->function_model->get_code_download($id_img."2"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
			);
			if($type_size_m != 0){
				$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
			}
			
			//insert product img size type l
			$dataInsertProductCategory = array(
					'id_img' 					=> $id_img,
					'type_size' 				=> $type_size_l,
					'type_size_description' 	=> $type_size_width_l."px X ".$type_size_height_l."px",
					'price_size_point' 			=> $type_size_point_l,
					'size_stock' 				=> $type_size_stock_l,
					'code_download' 			=> $this->function_model->get_code_download($id_img."3"),
					'date_add_img_size' 		=> date('Y-m-d H:i:s')
			);
			if($type_size_l != 0){
				$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
			}
			
			//info send email
			//info send email
			if($option_sale == 1){
				$str_option_sale			= "24 ".$this->lang->line('hour');
			}
			elseif($option_sale == 2){
				$str_option_sale			= "1 ".$this->lang->line('week');
			}
			elseif($option_sale == 3){
				$str_option_sale			= "1 ".$this->lang->line('month');
			}
			else{
				$str_option_sale			= $this->lang->line('limited_of_time_expired');
			}
			
			if($type_export_image == 1){
				$str_type_export_image		= $this->lang->line('blur');
			}
			elseif($type_export_image == 2){
				$str_type_export_image		= $this->lang->line('watermark');
			}
				
			//send email
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "edit_image";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $email_to;
			$dataSendEmail["name"] 					= ucwords($name);
			$dataSendEmail["img_title"]				= $img_title;
			$dataSendEmail["img_width"]				= $img_width;
			$dataSendEmail["img_height"]			= $img_height;
			$dataSendEmail["option_sale"]			= $str_option_sale;
			$dataSendEmail["type_export_image"]		= $str_type_export_image;
			$dataSendEmail["link"]					= base_url()."detail/".$data['detail_product']->img_code.".html";
			$dataSendEmail["is_send_admin"]			= 1;
			//$dataSendEmail["is_debug"]			= 1;
			
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "edit_image";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user_id;
			$addNotice['img_title'] 				= $img_title;
			$addNotice['link'] 						= base_url()."detail/".$data['detail_product']->img_code.".html";
			$addNotice['option_id'] 				= $id_img;
				
			$this->function_model->addNotice($addNotice);
			
			//get meta seo
			$_SESSION['return_key'] = 'images/edit_image_success';
			header("Location: ".base_url()."notice.html");
				
			exit;
				
		}
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/edit_image", $meta, $this->lang_id);
	
		//get product tags
		$whereProductTags = array('status' => 1, 'lang_id' => $this->lang_id);
		$orderProductTags = array();
		$data['list_product_tags'] = $this->main_model->getAllData("luxyart_tb_product_tags", $whereProductTags, $orderProductTags)->result();
		 
		//get product color
		$whereProductColor = array('status' => 1, 'lang_id' => $this->lang_id);
		$orderProductColor = array();
		$data['list_product_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->result();
		 
		//get product category
		$whereProductCategory = array('level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1);
		$orderProductCategory = array();
		$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
		
		//get product tags sub
		$data['list_product_tags_sub'] = $this->manager_image_model->get_product_tags_not_contain($id_img, $this->lang_id);
		
		//get product img size s
		$whereProductImgSize = array('id_img' => $id_img, 'type_size' => 1, 'status_img_size' => 0);
		$orderProductImgSize = array();
		$data['list_product_img_size_s'] = $this->main_model->getAllData("luxyart_tb_product_img_size", $whereProductImgSize, $orderProductImgSize)->row();
		
		$type_size_description_s = $data['list_product_img_size_s']->type_size_description;
		$type_size_description_s_explode = explode("X", $type_size_description_s);
		$type_size_width_s = trim(str_replace("px", "", $type_size_description_s_explode[0]));
		$type_size_height_s = trim(str_replace("px", "", $type_size_description_s_explode[1]));
		$data['list_product_img_size_s']->type_size_width_s = $type_size_width_s;
		$data['list_product_img_size_s']->type_size_height_s = $type_size_height_s;
		
		//get product img size m
		$whereProductImgSize = array('id_img' => $id_img, 'type_size' => 2, 'status_img_size' => 0);
		$orderProductImgSize = array();
		$data['list_product_img_size_m'] = $this->main_model->getAllData("luxyart_tb_product_img_size", $whereProductImgSize, $orderProductImgSize)->row();
		
		$type_size_description_m = $data['list_product_img_size_m']->type_size_description;
		$type_size_description_m_explode = explode("X", $type_size_description_m);
		$type_size_width_m = trim(str_replace("px", "", $type_size_description_m_explode[0]));
		$type_size_height_m = trim(str_replace("px", "", $type_size_description_m_explode[1]));
		$data['list_product_img_size_m']->type_size_width_m = $type_size_width_m;
		$data['list_product_img_size_m']->type_size_height_m = $type_size_height_m;
		
		//get product img size l
		$whereProductImgSize = array('id_img' => $id_img, 'type_size' => 3, 'status_img_size' => 0);
		$orderProductImgSize = array();
		$data['list_product_img_size_l'] = $this->main_model->getAllData("luxyart_tb_product_img_size", $whereProductImgSize, $orderProductImgSize)->row();
		
		$type_size_description_l = $data['list_product_img_size_l']->type_size_description;
		$type_size_description_l_explode = explode("X", $type_size_description_l);
		$type_size_width_l = trim(str_replace("px", "", $type_size_description_l_explode[0]));
		$type_size_height_l = trim(str_replace("px", "", $type_size_description_l_explode[1]));
		$data['list_product_img_size_l']->type_size_width_l = $type_size_width_l;
		$data['list_product_img_size_l']->type_size_height_l = $type_size_height_l;
		
		//get img category
		$whereImgCategory = array('id_img' => $id_img);
		$orderImgCategory = array();
		$data['list_img_category'] = $this->main_model->getAllData("luxyart_tb_img_category", $whereImgCategory, $orderImgCategory)->result();
		
		$arr_root_cate = array();
		$arr_parent_cate = array();
		$arr_sub_cate = array();
		
		foreach($data['list_img_category'] as $detail_img_category){
			array_push($arr_root_cate, $detail_img_category->root_cate);
			array_push($arr_parent_cate, $detail_img_category->parent_cate);
			array_push($arr_sub_cate, $detail_img_category->sub_cate);
		}
		
		$data['root_cate'] 			= $arr_root_cate;
		$data['parent_cate'] 		= $arr_parent_cate;
		$data['sub_cate'] 			= $arr_sub_cate;
		
		$data['active_menu_sidebar'] = "mypage-list-images";
		
		$data['noindex'] = '1';
	
		$this->template->load('default', 'edit_image', $data, __CLASS__);
	
	}
	
	public function detail_image($str_id_img){
		
		//set config
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		
		$data = array();
		
		//get login
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
		
		//get detail user
		$data['detail_user'] = $this->function_model->getUser($user_id);
		
		if(is_numeric($str_id_img)){
			//get detail product
			$data['detail_product'] = $this->manager_image_model->get_detail_image(-1)->row();
		}
		else{
			//get detail product
			$data['detail_product'] = $this->manager_image_model->get_detail_image_by_img_code($str_id_img)->row();
		}
		
		$id_img = $data['detail_product']->id_img;
		$data['id_img_code'] = $this->function_model->encrypt_base64_dot($id_img);
		
		if(empty($data['detail_product'])){
			
			//notice
			$_SESSION['return_key'] = 'page_do_not_exist';
			header("Location: ".base_url()."notice.html");
			
		}
		else{
			if(($data['user_id'] != $data['detail_product']->user_id) && $data['detail_product']->img_check_status != 1){
				
				//notice
				$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
				header("Location: ".base_url()."notice.html");
				
			}
		}
		
		//get img category
		$whereImgCategory = array('id_img' => $id_img);
		$orderImgCategory = array();
		$data['list_img_category'] = $this->main_model->getAllData("luxyart_tb_img_category", $whereImgCategory, $orderImgCategory)->result();
		
		$data['img_category'] = array();
		foreach($data['list_img_category'] as $detail_img_category){
			
			$data['img_category'][$detail_img_category->root_cate][0][] = $detail_img_category->parent_cate;
			$data['img_category'][$detail_img_category->root_cate][1][] = $detail_img_category->sub_cate;
			
		}
		
		if(count($data['img_category']) == 0){
				
			$this->db->from('luxyart_tb_product_category');
			$this->db->select('*');
			$this->db->where(array('level_cate'=>1,'status_cate'=>1,'lang_id'=>1));
			$this->db->order_by('cate_id', 'desc');
			$this->db->limit(1);
				
			$rsProductCategoryMain = $this->db->get();
			$resultProductCategoryMain = $rsProductCategoryMain->row();
				
			//add user view product img
			$dataImgCategory = array(
					'id_img' 		=> $id_img,
					'root_cate' 	=> $resultProductCategoryMain->root_cate,
					'parent_cate'	=> $resultProductCategoryMain->product_category_id
			);
			$this->db->insert('luxyart_tb_img_category', $dataImgCategory);
		
			//get img category
			$whereImgCategory = array('id_img' => $id_img);
			$orderImgCategory = array();
			$data['list_img_category'] = $this->main_model->getAllData("luxyart_tb_img_category", $whereImgCategory, $orderImgCategory)->result();
			
			$data['img_category'] = array();
			foreach($data['list_img_category'] as $detail_img_category){
					
				$data['img_category'][$detail_img_category->root_cate][0][] = $detail_img_category->parent_cate;
				$data['img_category'][$detail_img_category->root_cate][1][] = $detail_img_category->sub_cate;
					
			}
				
		}
		
		//get img size
		$whereImgSize = array('id_img' => $id_img, 'size_stock > ' => 0, 'status_img_size' => 0);
		$orderImgSize = array();
		$data['list_img_size'] = $this->main_model->getAllData("luxyart_tb_product_img_size", $whereImgSize, $orderImgSize)->result();
		
		//get img size new
		$data['list_img_size'] = $this->manager_image_model->get_list_img_size($id_img)->result();
		
		//count size stock
		$data['size_stock'] = 0;
		foreach($data['list_img_size'] as $detail_img_size){
			$data['size_stock'] += $detail_img_size->size_stock;
		}
		
		//get list image other
		$data['list_image_other'] = $this->manager_image_model->get_list_image_other($data['detail_product']->user_id, $id_img, 6)->result();
		
		//get list image relative
		$data['list_image_relative'] = $this->manager_image_model->get_list_image_relative($data['detail_product']->user_id, $id_img, 6)->result();
		
		//get time remaining
		$data['time_remaining'] = $this->manager_image_model->get_time_remaining($id_img);
		$data['time_limit_sale'] = $this->manager_image_model->get_time_limit_sale($id_img);
		
		//get link full
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->session->set_userdata('actual_link', $actual_link);
		
		//get max id image size
		$id_img_size = $this->shopping_model->get_max_id_img_size($id_img);
		$data['data_max_id_img_size'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
		
		//get user bookmark image
		$whereUserBookmarkImage = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserBookmarkImage = array();
		$data['detail_user_bookmark_image'] = $this->main_model->getAllData("luxyart_tb_user_bookmark_image", $whereUserBookmarkImage, $orderUserBookmarkImage)->row();
		
		if(count($data['detail_user_bookmark_image']) == 1){
			$data['is_bookmark_image'] = 1;
		}
		else{
			$data['is_bookmark_image'] = 0;
		}
		
		//get user follow
		$whereUserFollow = array('user_id' => $data['detail_product']->user_id, 'follow_user_id' => $user_id);
		$orderUserFollow = array();
		$data['detail_user_follow'] = $this->main_model->getAllData("luxyart_tb_user_follow", $whereUserFollow, $orderUserFollow)->row();
		
		if(count($data['detail_user_follow']) == 1){
			$data['is_follow'] = 1;
		}
		else{
			$data['is_follow'] = 0;
		}
		
		//get user view product img
		if($user_id != ""){
			
			$whereUserViewProductImg = array('user_id' => $user_id, 'id_img' => $id_img);
			$orderUserViewProductImg = array();
			$data['detail_user_view_product_img'] = $this->main_model->getAllData("luxyart_tb_user_view_product_img", $whereUserViewProductImg, $orderUserViewProductImg)->row();
			
			if(count($data['detail_user_view_product_img']) == 0){
				
				//add user view product img
				$dataUserViewProductImg = array(
					'user_id' 		=> $user_id,
					'id_img' 		=> $id_img,
					'date_view'		=> date('Y-m-d H:i:s')
				);
				$this->db->insert('luxyart_tb_user_view_product_img', $dataUserViewProductImg);
				
			}
			
		}
		
		//get is get img sell
		$whereUserGetImgSell = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserGetImgSell = array();
		$data['user_get_img_sell'] = $this->main_model->getAllData("luxyart_tb_user_get_img_sell", $whereUserGetImgSell, $orderUserGetImgSell)->row();
		
		$data["is_get_img_sell"] = 0;
		if(count($data['user_get_img_sell']) > 0){
			$data["is_get_img_sell"] = 1;
		}
		
		//get is get img sell
		$whereUserGetImgSell = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserGetImgSell = array();
		$data['user_get_img_sell'] = $this->main_model->getAllData("luxyart_tb_user_get_img_sell", $whereUserGetImgSell, $orderUserGetImgSell)->row();
		
		$data["is_get_img_sell"] = 0;
		if(count($data['user_get_img_sell']) > 0){
			$data["is_get_img_sell"] = 1;
		}
		
		$code = $this->function_model->encrypt_id($id_img);
		
		//get link invite
		$data["link_invite"] = base_url()."invite/".$code."-".$data['detail_user']->user_id_code_invite.".html";
		
		$whereNGWordList = array('status' => 1);
		$orderNGWordList = array();
		$data['ngWordList'] = $this->main_model->getAllData("luxyart_tb_ngwordlist", $whereNGWordList, $orderNGWordList)->result();
		
		$i = 0;
		$strWordList = "";
		if(count($data['ngWordList'])>0){
			foreach($data['ngWordList'] as $detailNGWordList){
				if($i==0){
					$strWordList = $detailNGWordList->word;
				}
				else{
					$strWordList .= ",".$detailNGWordList->word;
				}
				$i++;
			}
			$data['strWordList']	= $strWordList;
		}
		
		//get meta seo
		$meta = array();
		$meta['img_title'] = $data['detail_product']->img_title;
		$meta['cate_name'] = $this->function_model->getRootCate($data['list_img_category'][0]->root_cate, $this->lang_id)->cate_name;
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/detail_image", $meta, $this->lang_id);
		
		//$this->template->load('default', 'detail_image', $data, __CLASS__);
		
		// use user_id for compe is private
		$data['count_all_comment'] = $this->manager_comment_model->get_list_comment_by_level($id_img,1,$data['user_id'])->num_rows();
		$data['count_comment'] = $this->manager_comment_model->get_list_comment_by_level_paging($id_img,1,$data['user_id'])->num_rows();
		if($this->input->get("page")>0){
			$start_limit = ceil($this->input->get("page") * 3);
			$data['list_comment'] = $this->manager_comment_model->get_list_comment_by_level_paging($id_img,1,$data['user_id'],3,$start_limit)->result();
			$result = $this->load->view('images/data_comment_load', $data);
			//echo json_encode($result);
		}
		else{
			$_SESSION['load_page'] = 1;
			$data['list_comment'] = $this->manager_comment_model->get_list_comment_by_level_paging($id_img,1,$data['user_id'],3,0)->result();
			$this->load->view('images/detail_image', $data, __CLASS__);
		}
		
	}
	
	public function data_comment_sub_load(){
		
		//set config
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
	
		//get login
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
	
		$comment_id = $this->input->get("comment_id");
		
		$data['list_comment_sub'] = $this->manager_comment_model->get_list_comment_by_level_sub_paging($comment_id,$user_id)->result();
		
		array_splice($data['list_comment_sub'], 0, 1);
		array_splice($data['list_comment_sub'], 0, 1);
		$this->load->view('images/data_comment_sub_load', $data, __CLASS__);
	
	}
	
	public function manager_comment_sub_load(){
		
		//set config
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
	
		//get login
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
	
		$comment_id = $this->input->get("comment_id");
		$data['list_comment_sub'] = $this->manager_comment_model->get_list_comment_by_level_sub_paging($comment_id,$user_id)->result();
		array_splice($data['list_comment_sub'], 0, 1);
		array_splice($data['list_comment_sub'], 0, 1);
		$this->load->view('images/manager_comment_sub_load', $data, __CLASS__);
	
	}
	
	function list_comment(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data["user_id"] = $user_id;
	
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
	
		//get list compe
		$this->load->library('pagination');
		$config['per_page'] = 10;
	
		$config['total_rows'] = count($this->manager_comment_model->get_list_manager_comment($user_id,'','')->result());
	
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/list-comment';
	
		$data['list_comment'] = $this->manager_comment_model->get_list_manager_comment($user_id,$config['per_page'],$this->uri->segment(3))->result();
	
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/mypage_list_comment", $meta, $this->lang_id);
	
		$data['active_menu_sidebar'] = "mypage-list-comment";
	
		$data['noindex'] = '1';
	
		$this->template->load('default', 'mypage_list_comment', $data, __CLASS__);
	
	}
	
	function detail_comment($id_img){
	
		$data["id_img"] = $id_img;
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data["user_id"] = $user_id;
	
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
	
		//get list compe
		$this->load->library('pagination');
		$config['per_page'] = 5;
	
		$config['total_rows'] = count($this->manager_comment_model->get_manager_list_comment_by_level_paging($id_img,1,$user_id,'','')->result());
	
		$config['uri_segment'] = 4;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/detail-comment/'.$id_img;
	
		$data['list_comment'] = $this->manager_comment_model->get_manager_list_comment_by_level_paging($id_img,1,$user_id,$config['per_page'],$this->uri->segment(4))->result();
	
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
		
		//get detail product
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
	
		//get meta seo
		$meta = array();
		$meta['img_title'] = $data['detail_product']->img_title;
		//$meta['cate_name'] = $this->function_model->getRootCate($data['list_img_category'][0]->root_cate, $this->lang_id)->cate_name;
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/mypage_detail_comment", $meta, $this->lang_id);
	
		$data['active_menu_sidebar'] = "mypage-detail-comment";
	
		$data['noindex'] = '1';
	
		$this->template->load('default', 'mypage_detail_comment', $data, __CLASS__);
	
	}
	
	public function detail_image_test($str_id_img){
	
		//set config
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
	
		$data = array();
	
		//get login
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
	
		//get detail user
		$data['detail_user'] = $this->function_model->getUser($user_id);
	
		if(is_numeric($str_id_img)){
			//get detail product
			$data['detail_product'] = $this->manager_image_model->get_detail_image(-1)->row();
		}
		else{
			//get detail product
			$data['detail_product'] = $this->manager_image_model->get_detail_image_by_img_code($str_id_img)->row();
		}
	
		$id_img = $data['detail_product']->id_img;
		$data['id_img_code'] = $this->function_model->encrypt_base64_dot($id_img);
	
		if(empty($data['detail_product'])){
				
			//notice
			$_SESSION['return_key'] = 'page_do_not_exist';
			header("Location: ".base_url()."notice.html");
				
		}
		else{
			if(($data['user_id'] != $data['detail_product']->user_id) && $data['detail_product']->img_check_status != 1){
	
				//notice
				$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
				header("Location: ".base_url()."notice.html");
	
			}
		}
	
		//get img category
		$whereImgCategory = array('id_img' => $id_img);
		$orderImgCategory = array();
		$data['list_img_category'] = $this->main_model->getAllData("luxyart_tb_img_category", $whereImgCategory, $orderImgCategory)->result();
	
		$data['img_category'] = array();
		foreach($data['list_img_category'] as $detail_img_category){
				
			$data['img_category'][$detail_img_category->root_cate][0][] = $detail_img_category->parent_cate;
			$data['img_category'][$detail_img_category->root_cate][1][] = $detail_img_category->sub_cate;
				
		}
	
		//get img size
		$whereImgSize = array('id_img' => $id_img, 'size_stock > ' => 0, 'status_img_size' => 0);
		$orderImgSize = array();
		$data['list_img_size'] = $this->main_model->getAllData("luxyart_tb_product_img_size", $whereImgSize, $orderImgSize)->result();
	
		//get img size new
		$data['list_img_size'] = $this->manager_image_model->get_list_img_size($id_img)->result();
	
		//count size stock
		$data['size_stock'] = 0;
		foreach($data['list_img_size'] as $detail_img_size){
			$data['size_stock'] += $detail_img_size->size_stock;
		}
	
		//get list image other
		$data['list_image_other'] = $this->manager_image_model->get_list_image_other($data['detail_product']->user_id, $id_img, 6)->result();
	
		//get list image relative
		$data['list_image_relative'] = $this->manager_image_model->get_list_image_relative($data['detail_product']->user_id, $id_img, 6)->result();
	
		//get time remaining
		$data['time_remaining'] = $this->manager_image_model->get_time_remaining($id_img);
		$data['time_limit_sale'] = $this->manager_image_model->get_time_limit_sale($id_img);
	
		//get link full
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->session->set_userdata('actual_link', $actual_link);
	
		//get max id image size
		$id_img_size = $this->shopping_model->get_max_id_img_size($id_img);
		$data['data_max_id_img_size'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
	
		//get user bookmark image
		$whereUserBookmarkImage = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserBookmarkImage = array();
		$data['detail_user_bookmark_image'] = $this->main_model->getAllData("luxyart_tb_user_bookmark_image", $whereUserBookmarkImage, $orderUserBookmarkImage)->row();
	
		if(count($data['detail_user_bookmark_image']) == 1){
			$data['is_bookmark_image'] = 1;
		}
		else{
			$data['is_bookmark_image'] = 0;
		}
	
		//get user follow
		$whereUserFollow = array('user_id' => $data['detail_product']->user_id, 'follow_user_id' => $user_id);
		$orderUserFollow = array();
		$data['detail_user_follow'] = $this->main_model->getAllData("luxyart_tb_user_follow", $whereUserFollow, $orderUserFollow)->row();
	
		if(count($data['detail_user_follow']) == 1){
			$data['is_follow'] = 1;
		}
		else{
			$data['is_follow'] = 0;
		}
	
		//get user view product img
		if($user_id != ""){
				
			$whereUserViewProductImg = array('user_id' => $user_id, 'id_img' => $id_img);
			$orderUserViewProductImg = array();
			$data['detail_user_view_product_img'] = $this->main_model->getAllData("luxyart_tb_user_view_product_img", $whereUserViewProductImg, $orderUserViewProductImg)->row();
				
			if(count($data['detail_user_view_product_img']) == 0){
	
				//add user view product img
				$dataUserViewProductImg = array(
						'user_id' 		=> $user_id,
						'id_img' 		=> $id_img,
						'date_view'		=> date('Y-m-d H:i:s')
				);
				$this->db->insert('luxyart_tb_user_view_product_img', $dataUserViewProductImg);
	
			}
				
		}
	
		//get is get img sell
		$whereUserGetImgSell = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserGetImgSell = array();
		$data['user_get_img_sell'] = $this->main_model->getAllData("luxyart_tb_user_get_img_sell", $whereUserGetImgSell, $orderUserGetImgSell)->row();
	
		$data["is_get_img_sell"] = 0;
		if(count($data['user_get_img_sell']) > 0){
			$data["is_get_img_sell"] = 1;
		}
	
		//get is get img sell
		$whereUserGetImgSell = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserGetImgSell = array();
		$data['user_get_img_sell'] = $this->main_model->getAllData("luxyart_tb_user_get_img_sell", $whereUserGetImgSell, $orderUserGetImgSell)->row();
	
		$data["is_get_img_sell"] = 0;
		if(count($data['user_get_img_sell']) > 0){
			$data["is_get_img_sell"] = 1;
		}
	
		$code = $this->function_model->encrypt_id($id_img);
	
		//get link invite
		$data["link_invite"] = base_url()."invite/".$code."-".$data['detail_user']->user_id_code_invite.".html";
		
		$whereNGWordList = array('status' => 1);
		$orderNGWordList = array();
		$data['ngWordList'] = $this->main_model->getAllData("luxyart_tb_ngwordlist", $whereNGWordList, $orderNGWordList)->result();
		
		$i = 0;
		$strWordList = "";
		if(count($data['ngWordList'])>0){
			foreach($data['ngWordList'] as $detailNGWordList){
				if($i==0){
					$strWordList = $detailNGWordList->word;
				}
				else{
					$strWordList .= ",".$detailNGWordList->word;
				}
				$i++;
			}
			$data['strWordList']	= $strWordList;
		}
	
		//get meta seo
		$meta = array();
		$meta['img_title'] = $data['detail_product']->img_title;
		$meta['cate_name'] = $this->function_model->getRootCate($data['list_img_category'][0]->root_cate, $this->lang_id)->cate_name;
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/detail_image", $meta, $this->lang_id);
	
		//$this->template->load('default', 'detail_image', $data, __CLASS__);
	
		// use user_id for compe is private
		$data['count_all_comment'] = $this->manager_comment_model->get_list_comment_by_level($id_img,1,$data['user_id'])->num_rows();
		$data['count_comment'] = $this->manager_comment_model->get_list_comment_by_level_paging($id_img,1,$data['user_id'])->num_rows();
		if($this->input->get("page")>0){
			$start_limit = ceil($this->input->get("page") * 3);
			$data['list_comment'] = $this->manager_comment_model->get_list_comment_by_level_paging($id_img,1,$data['user_id'],3,$start_limit)->result();
			$result = $this->load->view('images/data_comment_load', $data);
			//echo json_encode($result);
		}
		else{
			$_SESSION['load_page'] = 1;
			$data['list_comment'] = $this->manager_comment_model->get_list_comment_by_level_paging($id_img,1,$data['user_id'],3,0)->result();
			$this->load->view('images/detail_image_test', $data, __CLASS__);
		}
	
	}
	
	public function cart(){
		
		$data = array();
		
		//get login
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
		
		//get detail user
		$data['detail_user'] = $this->function_model->getUser($user_id);
		
		//get list cart
		$data['cart_check'] = $this->cart->contents();
		/*if($user_id == 68){
			
			echo "<pre>";
			print_r($this->cart->contents());
			
			echo "count = ".count($this->cart->contents())."<br>";
			exit;
			
		}*/
		$data['cart_check'] = $this->function_model->subval_sort($data['cart_check'],'id');
		
		$data['subtotal'] = $this->function_model->array_sum_values($data['cart_check'], 'subtotal');
		
		//get link full
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->session->set_userdata('actual_link', $actual_link);
		
		//get is save to cart
		$data["is_save_to_cart"] = $this->session->userdata('is_save_to_cart');
		$this->session->unset_userdata('is_save_to_cart');
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/cart", $meta, $this->lang_id);
		
		$this->template->load('default', 'cart', $data, __CLASS__);
		
	}
	
	public function cart_test(){
		
		session_start();
		
		$data = array();
		
		//get login
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
		
		//get detail user
		$data['detail_user'] = $this->function_model->getUser($user_id);
		
		//get list cart
		$data['cart_check'] = $this->cart->contents();
		/*if($user_id == 68){
			
			echo "<pre>";
			print_r($this->cart->contents());
			
			echo "count = ".count($this->cart->contents())."<br>";
			exit;
			
		}*/
		$data['cart_check'] = $this->function_model->subval_sort($data['cart_check'],'id');
		
		$data['subtotal'] = $this->function_model->array_sum_values($data['cart_check'], 'subtotal');
		
		//get link full
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->session->set_userdata('actual_link', $actual_link);
		
		//get is save to cart
		$data["is_save_to_cart"] = $this->session->userdata('is_save_to_cart');
		$this->session->unset_userdata('is_save_to_cart');
		
		if(count($_SESSION["save_email"]) > 0){
			$data["is_save_email"] = 1;
		}
		else{
			$data["is_save_email"] = 0;
		}
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/cart", $meta, $this->lang_id);
		
		$this->template->load('default', 'carttest', $data, __CLASS__);
		
	}
	
	public function cart_test_test(){
	
		session_start();
	
		$data = array();
	
		//get login
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
	
		//get detail user
		$data['detail_user'] = $this->function_model->getUser($user_id);
	
		//get list cart
		$data['cart_check'] = $this->cart->contents();
		/*if($user_id == 68){
		 	
		echo "<pre>";
		print_r($this->cart->contents());
			
		echo "count = ".count($this->cart->contents())."<br>";
		exit;
			
		}*/
		$data['cart_check'] = $this->function_model->subval_sort($data['cart_check'],'id');
	
		$data['subtotal'] = $this->function_model->array_sum_values($data['cart_check'], 'subtotal');
	
		//get link full
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->session->set_userdata('actual_link', $actual_link);
	
		//get is save to cart
		$data["is_save_to_cart"] = $this->session->userdata('is_save_to_cart');
		$this->session->unset_userdata('is_save_to_cart');
	
		if(count($_SESSION["save_email"]) > 0){
			$data["is_save_email"] = 1;
		}
		else{
			$data["is_save_email"] = 0;
		}
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("images/cart", $meta, $this->lang_id);
	
		$this->template->load('default', 'carttesttest', $data, __CLASS__);
	
	}
	
	public function send_email(){
		
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "register";
		$dataSendEmail["lang_id"] 				= 1;
		$dataSendEmail["email_to"] 				= "fermatandrew@gmail.com";
		$dataSendEmail["name"] 					= ucwords("Đỗ Hữu Thiện");
		$dataSendEmail["link"] 					= "http://153.121.55.90/luxyart_demo/active_email/".md5(12345);
		$dataSendEmail["username"] 				= "dhthien";
		$dataSendEmail["email"] 				= "fermatandrew@gmail.com";
		$dataSendEmail["password"] 				= "123456";
		
		$this->function_model->sendMailHTML($dataSendEmail);

	}
	
	public function send_email_attachment(){
	
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "register";
		$dataSendEmail["lang_id"] 				= 1;
		$dataSendEmail["email_to"] 				= "fermatandrew@gmail.com";
		$dataSendEmail["name"] 					= ucwords("Đỗ Hữu Thiện");
		$dataSendEmail["link"] 					= "http://153.121.55.90/luxyart_demo/active_email/".md5(12345);
		$dataSendEmail["username"] 				= "dhthien";
		$dataSendEmail["email"] 				= "fermatandrew@gmail.com";
		$dataSendEmail["password"] 				= "123456";
		$dataSendEmail["file_attachment"] 		= "/usr/local/apache/htdocs/luxyart_demo/publics/product_img/1506658573-watermark.jpg";
	
		$this->function_model->sendMailHTML($dataSendEmail);
		
		echo 6666666666666;
	
	}
	
	public function compare_color(){

		echo $this->function_model->get_compare_color("#e2dbc3");
	
	}
	
	public function zip_muti_file(){

		/*echo 222222222;
		
		$files = array('/usr/local/apache/htdocs/luxyart_demo/publics/competition_img/1507779658.jpg', '/usr/local/apache/htdocs/luxyart_demo/publics/competition_img/1507779384.jpg', '/usr/local/apache/htdocs/luxyart_demo/publics/competition_img/1507609013.jpg');
		$zipname = 'file.zip';
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);
		foreach ($files as $file) {
			$zip->addFile($file);
		}
		$zip->close();
		
		echo 555555555;
		
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($zipname));
		readfile($zipname);*/
		
		/*$files = array('1507779658.jpg', '1507779384.jpg', '1507609013.jpg');
		
		$zip = new ZipArchive();
		$zip_name = time().".zip"; // Zip name
		$zip->open($zip_name,  ZipArchive::CREATE);
		foreach ($files as $file) {
			echo $path = "/usr/local/apache/htdocs/luxyart_demo/publics/competition_img/".$file;
			if(file_exists($path)){
				$zip->addFromString(basename($path),  file_get_contents($path));
			}
			else{
				echo"file does not exist";
			}
		}
		$zip->close();
		
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($zipname));
		readfile($zipname);*/
		
		$file_names = array('1507779658.jpg', '1507779384.jpg', '1507609013.jpg');
		$archive_file_name='thien.zip';
		$file_path=dirname(__FILE__).'/';
		$this->zipFilesAndDownload($file_names,$archive_file_name,"/usr/local/apache/htdocs/luxyart_demo/publics/competition_img/");

	}
	
	//function to zip and force download the files using PHP
	function zipFilesAndDownload($file_names,$archive_file_name,$file_path)
	{
		//create the object
		$zip = new ZipArchive();
		//create the file and throw the error if unsuccessful
		if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
			exit("cannot open <$archive_file_name>\n");
		}
	
		//add each files of $file_name array to archive
		foreach($file_names as $files)
		{
			$zip->addFile($file_path.$files,$files);
		}
		$zip->close();
	
		//then send the headers to foce download the zip file
		header("Content-type: application/zip");
		header("Content-Disposition: attachment; filename=$archive_file_name");
		header("Pragma: no-cache");
		header("Expires: 0");
		readfile("$archive_file_name");
		exit;
	}
	
	function invite($str_code){

		$code_explode = explode("-",$str_code);
		
		$code = $code_explode[0];
		$user_id_code_invite = $code_explode[1];
		
		$id_img = $this->function_model->decrypt_id($code);
		
		if(!is_integer($id_img)){
			
			//notice
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
			exit;
			
		}
		
		$this->db->from('luxyart_tb_user');
		$this->db->select('user_id');
		$this->db->where('user_id_code_invite', $user_id_code_invite);
		$rs = $this->db->get();
		$row = $rs->row();
		
		if(empty($row)){
			//notice
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
			exit;
		}
		
		$sess_data = array(
			'user_invite_sale' 	=> $row->user_id,
			'id_img' 			=> $id_img,
		);
		$this->session->set_userdata('invite_sale', $sess_data);
		
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		
		header("Location: ".base_url()."detail/".$data['detail_product']->img_code.".html");

	}
	 
}