<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Images extends CI_Controller {

	public function __construct() {
		
        parent::__construct();
        
		if(!isAdmin()){
			redirect(base_url().'admin/login');
        }
        
        @session_start();
        
        //$this->load->model('main_model');
        $this->load->model('function_model');
        $this->load->model('manager_image_model');
        
        $dir = getcwd();
        define("DIR", $dir."/publics/");
        define("DIR_TEMP", $dir."/publics/product_img/temp/");
        
        $this->config->load('config', TRUE);
        $this->config->set_item('uri_protocol', 'REQUEST_URI');
        $this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
        $this->config->set_item('enable_query_strings', TRUE);
        
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        
        $CI =& get_instance();
        $CI->config->load();
        $this->lang->load('dich', 'ja');
        
    }

    public function post_image(){
		
		$this->load->library('sftp');
		
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
			
			$user_id 						= $_POST["user_id"];
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
			
			$img_check_status 				= isset($_POST["img_check_status"]) ? $_POST["img_check_status"] : 0;
			
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
			$manager_server_upload = $this->function_model->getManagerServerUpload(1);
			
			$file_name_original_type = $file_name_original_img['type'];
			$file_name_original_type = trim(str_replace("image/", "", $file_name_original_type));
			if($file_name_original_type == "jpeg"){
				$file_name_original_type = "jpg";
			}
			
			$time = time();
			$image_name = $this->function_model->get_image_name().'.'.$file_name_original_type;
			
			$image_watermark_no_extension = $time.'-watermark';
			$image_watermark = $time.'-watermark.jpg';
				
			$image_blur_no_extension = $time.'-blur';
			$image_blur = $time.'-blur.jpg';
			
			$image_thumnail_no_extension = $time.'-thumnail';
			$image_thumnail = $time.'-thumnail.jpg';
			
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
					$image_new = $image_watermark;
				
					if($kind_img == 1 && $width > $data['min_width_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_watermark);
					}
					elseif($kind_img == 2 && $height > $data['min_height_img_thumnail']){
						$this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP.$image_thumnail_no_extension, DIR_TEMP.$image_watermark);
					}
				
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
				$main_color = $this->function_model->main_color_image($manager_server_upload->server_path_upload_img."/".$image_name);
				$id_color = $this->function_model->get_compare_color("#".$main_color);
				
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
					'img_tags' 					=> $val_product_tags,
					'id_server'					=> $manager_server_upload->id_server,
					'option_img_watermark'		=> $type_export_image,
					'option_sale' 				=> $option_sale,
					'is_limit' 					=> $is_limit,
					'img_check_status' 			=> $img_check_status,
					'date_add_img'				=> $date_add_img,
					'img_link'					=> base_url()."detail/".$id_img.".html",
					'os_register'				=> 3,
					'img_code'					=> $img_code
				);
				$this->db->insert('luxyart_tb_product_img', $dataAddProductImg);
				$id_img = $this->db->insert_id();
				
				$this->function_model->setFilterImg($id_img,$user_id);
				
				//delete img category
				$this->db->where('id_img', $id_img);
				$this->db->delete('luxyart_tb_img_category');
				
				//insert img category
				$category_sub = explode(",", $val_category_sub);
				for($i=0; $i<count($category_sub); $i++){
				
					$parent_cate = $category_sub[$i];
				
					$this->db->from('luxyart_tb_product_category');
					$this->db->select('*');
					$this->db->where(array('product_category_id' => $parent_cate, 'status_cate' => 1, 'lang_id' => 1));
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
				if($type_size_l != l){
					$this->db->insert('luxyart_tb_product_img_size', $dataInsertProductCategory);
				}
				
				//info send email
				if($option_sale == 1){
					$str_option_sale			= "24 hour";
				}
				elseif($option_sale == 1){
					$str_option_sale			= "1 week";
				}
				elseif($option_sale == 1){
					$str_option_sale			= "1 month";
				}
				else{
					$str_option_sale			= "1 month";
				}
				
				if($is_export_image == 1){
					$str_type_export_image		= "blur";
				}
				elseif($is_export_image == 2){
					$str_type_export_image		= "watermark";
				}
				
				//set count post img
				$this->db->from('luxyart_tb_product_img');
				$this->db->select('*');
				$this->db->where(array('user_id' => $user_id));
				$result = $this->db->get();
				$count_all_img = $result->num_rows();
				
				$this->db->where('user_id', $user_id);
				//$this->db->set('count_post_img','count_post_img+1',FALSE);
				$this->db->set('count_post_img',$count_all_img);
				$this->db->update('luxyart_tb_user');
				
				$_SESSION['return_msg'] = "画像を正常に投稿";
				$_SESSION['return_link'] = base_url()."admin/page_list_image";
				header("Location: ".base_url()."admin/images/post_image");
				
			}
			
			exit;
			
		}
		
		//get product tags
		$whereProductTags = array('status' => 1, 'lang_id' => 1);
    	$orderProductTags = array();
    	$data['list_product_tags'] = $this->main_model->getAllData("luxyart_tb_product_tags", $whereProductTags, $orderProductTags)->result();
    	
    	//get product color
    	$whereProductColor = array('status' => 1, 'lang_id' => 1);
    	$orderProductColor = array();
    	$data['list_product_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->result();
    	
    	//get product category
    	$whereProductCategory = array('level_cate' => 0, 'lang_id' => 1, 'status_cate' => 1);
    	$orderProductCategory = array();
    	$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
    	
    	//get list user
    	$whereUser = array('user_status' => 1);
    	$orderUser = array('user_email' => 'asc');
    	$data['list_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->result();
    	
    	$data['title'] = $this->lang->line('title_page_post_image');
    	
    	$this->template->load('admin', 'post_image', $data, __CLASS__);

	}

    public function edit_image($id_img){

		$this->load->library('sftp');
		
		$setting = $this->function_model->get_setting();
		
		$data['allowed_image_formats'] = $setting['allowed_image_formats'];
		$data['max_mb'] = $setting['max_mb'];
		$data['min_width_img'] = $setting['min_width_img'];
		$data['min_height_img'] = $setting['min_height_img'];
		$data['min_width_img_thumnail'] = $setting['min_width_img_thumnail'];
		$data['min_height_img_thumnail'] = $setting['min_height_img_thumnail'];
		$data['percent_image_pixel'] = $setting['percent_image_pixel'];
	
		if(isset($_POST['btnEditImage'])){
			
			$user_id 						= $_POST["id"];
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
			
			$img_check_status 				= isset($_POST["img_check_status"]) ? $_POST["img_check_status"] : 0;
				
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
			$manager_server_upload = $this->function_model->getManagerServerUpload(1);
			
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
				
				$image_watermark_no_extension = $time.'-watermark';
				$image_watermark = $time.'-watermark.jpg';
				
				$image_blur_no_extension = $time.'-blur';
				$image_blur = $time.'-blur.jpg';
				
				$image_thumnail_no_extension = $time.'-thumnail';
				$image_thumnail = $time.'-thumnail.jpg';
				
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
							
						$this->function_model->convert_to_pixel(DIR_TEMP.$image_name, DIR_TEMP."/".$image_blur, $data['percent_image_pixel']);
							
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
				$main_color = $this->function_model->main_color_image($manager_server_upload->server_path_upload_img."/".$image_name);
				$id_color = $this->function_model->get_compare_color("#".$main_color);
				
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
						'img_tags' 					=> $val_product_tags,
						'id_server'					=> $manager_server_upload->id_server,
						'option_img_watermark'		=> $type_export_image,
						'option_sale' 				=> $option_sale,
						'is_limit' 					=> $is_limit,
						'img_check_status' 			=> $img_check_status,
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
						'img_check_status' 			=> $img_check_status,
						'date_update_img'			=> $date_update_img
				);
				
				$this->db->where('id_img', $id_img);
				$this->db->update('luxyart_tb_product_img', $dataEditProductImg);
				
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
				$this->db->where(array('product_category_id' => $parent_cate, 'status_cate' => 1, 'lang_id' => 1));
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
			
			$_SESSION['return_msg'] = "画像を正常に投稿";
			$_SESSION['return_link'] = base_url()."admin/page_list_image";
			header("Location: ".base_url()."admin/images/edit_image");
				
			exit;
				
		}
	
		//get product tags
		$whereProductTags = array('status' => 1, 'lang_id' => 1);
		$orderProductTags = array();
		$data['list_product_tags'] = $this->main_model->getAllData("luxyart_tb_product_tags", $whereProductTags, $orderProductTags)->result();
		 
		//get product color
		$whereProductColor = array('status' => 1, 'lang_id' => 1);
		$orderProductColor = array();
		$data['list_product_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->result();
		 
		//get product category
		$whereProductCategory = array('level_cate' => 0, 'lang_id' => 1, 'status_cate' => 1);
		$orderProductCategory = array();
		$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
		
		//get product img
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		
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
		
		//get list user
		$whereUser = array('user_status' => 1);
		$orderUser = array('user_email' => 'asc');
		$data['list_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->result();
		
		//$data['active_menu_sidebar'] = "mypage-list-images";
		
		$data['title'] = $this->lang->line('title_page_edit_image');
	
		$this->template->load('admin', 'edit_image', $data, __CLASS__);
	
	}
	
	public function page_add_edit_lang($id) {
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		
		$this->checkLoginAdmin();
	
		$data['path'] = $this->input->get('path');
		$nameLang = ['ja'=>'Japan','vi'=>'Vietnam','en'=>'English'];
		$data['title'] = "Update language: ".$nameLang[str_replace('application/language/', '', $data['path'])];
		$data['code'] = @file_get_contents(__DIR__."/../../../".$data['path']."/dich_lang.php");
		
		$arr_code = explode(';', nl2br($data['code']));
		
		for($i=0; $i<count($arr_code); $i++){
			
			$arr_row = explode('=', $arr_code[$i]);
			echo "xxx = ".$arr_row[0]."<br>";
			//echo "yyy = ".$arr_row[1]."<br>";
			
			$arr_row_child = explode("\$lang['", $arr_row[0]);
			
			$key = str_replace("']", "", $arr_row_child[1]);
			if($key != ""){
				//echo "xxx = ".$key."<br>";
			}
			
			
		
		}
		
		exit;
		
		$this->template->load('admin', 'page_add_edit_lang', $data, __CLASS__);
	}
	
	public function checkLoginAdmin(){
		if(!isAdmin()) redirect(base_url().'admin/login');
	}
   	
}