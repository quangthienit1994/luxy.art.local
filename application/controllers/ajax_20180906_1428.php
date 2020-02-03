<?php
if(!isset($_SESSION)){
	session_start();
}
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
?>

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function __construct() {
		
		parent::__construct();
		
		@session_start();
		$this->load->library('session');
		
		$this->load->model('main_model');
		$this->load->model('function_model');
		$this->load->model('manager_image_model');
		$this->load->model('manager_compe_model');
		$this->load->model('manager_comment_model');
		$this->load->model('shopping_model');
		
		$dir = getcwd();
		define("DIR", $dir."/publics/");	
		define("DIR_DOWNLOAD", $dir."/publics/product_img/download/");
		define("DIR_ZIP", $dir."/publics/product_img/zip/");
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
	
	public function get_muti_sub_cate(){
	
		$val_category = isset($_POST['val_category']) ? $_POST['val_category'] : "";
		$val_parent_cate = isset($_POST['val_parent_cate']) ? $_POST['val_parent_cate'] : "";
	
		$category = array();
		$category = explode(",", $val_category);
	
		$sub_category = array();
		if($val_parent_cate != ""){
			$sub_category = explode(",", $val_parent_cate);
		}
	
		$category = array_unique($category);
	
		$str = '<select id="category_sub" name="category_sub" multiple="multiple" style="display: none;">';
	
		foreach($category as $_category){
				
			$this->db->from('luxyart_tb_product_category');
			$this->db->select('*');
			$this->db->where(array('product_category_id' => $_category, 'level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1));
			$rsRoot = $this->db->get();
			$resultRoot = $rsRoot->row();
				
			$this->db->from('luxyart_tb_product_category');
			$this->db->select('*');
			$this->db->where(array('root_cate' => $_category, 'level_cate' => 1, 'lang_id' => $this->lang_id, 'status_cate' => 1));
			$rsCategory = $this->db->get();
	
			if(!empty($rsCategory)){
				
				$resultCategory = $rsCategory->result();
		   
				foreach($resultCategory as $_resultCategory){
					
					if (in_array($_resultCategory->product_category_id, $sub_category)){
						$str .= '<option value="'.$_resultCategory->product_category_id.'" selected="selected">'.$resultRoot->cate_name."&nbsp;>&nbsp;".$_resultCategory->cate_name.'</option>';
					
					}
					else{
						$str .= '<option value="'.$_resultCategory->product_category_id.'">'.$resultRoot->cate_name."&nbsp;>&nbsp;".$_resultCategory->cate_name.'</option>';
					}
					$str .= '</optgroup>';
					
				}
			}
	
		}
	
		$str .= '</select>';
	
		echo $str;
	
	}
	
	public function get_muti_sub_category(){
		
		$val_category = isset($_POST['val_category']) ? $_POST['val_category'] : "";
		$val_sub_category = isset($_POST['val_sub_category']) ? $_POST['val_sub_category'] : "";
		
		$category = array();
		$category = explode(",", $val_category);
		
		$sub_category = array();
		if($val_sub_category != ""){
			$sub_category = explode(",", $val_sub_category);
		}
		
		$category = array_unique($category);
		
		$str = '<select id="category_sub" name="category_sub" multiple="multiple" style="display: none;">';
		
		for($i=0;  $i<count($category); $i++){
			
			$this->db->from('luxyart_tb_product_category');
			$this->db->select('*');
			$this->db->where(array('product_category_id' => $category[$i], 'level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1));
			$rsRoot = $this->db->get();
			$resultRoot = $rsRoot->row();
			
			$this->db->from('luxyart_tb_product_category');
	   		$this->db->select('*');
	   		$this->db->where(array('root_cate' => $category[$i], 'level_cate' => 1, 'lang_id' => $this->lang_id, 'status_cate' => 1));
	   		$rsCategory = $this->db->get();
	   		
	   		if(!empty($rsCategory)){
	   			
	   			$resultCategory = $rsCategory->result();
	   			
	   			foreach($resultCategory as $_resultCategory){
	   				
	   				$str .= '<optgroup label="'.$resultRoot->cate_name."&nbsp;>&nbsp;".$_resultCategory->cate_name.'">';
	   				
	   				$this->db->from('luxyart_tb_product_category');
	   				$this->db->select('*');
	   				$this->db->where(array('parent_cate' => $_resultCategory->product_category_id, 'level_cate' => 2, 'lang_id' => $this->lang_id, 'status_cate' => 1));
	   				$rsCategorySub = $this->db->get();
	   				
	   				if(!empty($rsCategorySub)){
	   					
	   					$resultCategorySub = $rsCategorySub->result();
	   					
	   					foreach($resultCategorySub as $_resultCategorySub){
	   						
	   						if (in_array($_resultCategorySub->product_category_id, $sub_category)){
	   						
		   						$str .= '<option value="'.$_resultCategorySub->product_category_id.'" selected="selected">'.$_resultCategorySub->cate_name.'</option>';
		   					
	   						}
	   						
	   						else{
	   							
	   							$str .= '<option value="'.$_resultCategorySub->product_category_id.'">'.$_resultCategorySub->cate_name.'</option>';
	   							
	   						}
		   					
	   					}
	   				
	   				}
	   				
	   				$str .= '</optgroup>';
	   				
	   			}
	   		}

		}
		
		$str .= '</select>';
		
		echo $str;

	}
	
	function load_product_tags(){
		
		$val_category = ($_POST["val_category"] != "null") ? $_POST["val_category"] : "";
		$val_category_sub = ($_POST["val_category_sub"] != "null") ? $_POST["val_category_sub"] : "";
		
		$val_category = str_replace("multiselect-all,", "", $val_category);
		$val_category_sub = str_replace("multiselect-all,", "", $val_category_sub);
		
		if($val_category_sub == ""){
			
			$arr_val_category = explode(",",$val_category);
			
			$this->db->from('luxyart_tb_product_tags');
	   		$this->db->select('id_product_tag,tag_name');
	   		$this->db->where(array('status' => 1));
	   		$this->db->where_in('root_cate',$arr_val_category);
	   		$rsProductTags = $this->db->get();
			$resultProductTags = $rsProductTags->result();
			
			$str = '<select id="product_tags" name="product_tags[]" data-placeholder="'.$this->lang->line('please_choose_tags_image').'" multiple class="chosen-select-product_tags" tabindex="8">';
			foreach($resultProductTags as $rowProductTags){
				$str .= '<option>'.$rowProductTags->tag_name.'</option>';
			}
			$str .= "</select>";
			
		}
		else{
			
			$arr_val_category_sub = explode(",",$val_category_sub);
				
			$this->db->from('luxyart_tb_product_tags');
			$this->db->select('id_product_tag,tag_name');
			$this->db->where(array('status' => 1));
			$this->db->where_in('parent_cate',$arr_val_category_sub);
			$rsProductTags = $this->db->get();
			$resultProductTags = $rsProductTags->result();
				
			$str = '<select id="product_tags" name="product_tags[]" data-placeholder="'.$this->lang->line('please_choose_tags_image').'" multiple class="chosen-select-product_tags" tabindex="8">';
			foreach($resultProductTags as $rowProductTags){
				$str .= '<option>'.$rowProductTags->tag_name.'</option>';
			}
			$str .= "</select>";
			
		}
		
		echo $str;
		exit;
		
	}
	
	public function check_file_md5_exits(){
		
		$file_md5 = isset($_POST['file_md5']) ? $_POST['file_md5'] : "";
		$id_img = isset($_POST['id_img']) ? $_POST['id_img'] : "";
		
		$this->db->from('luxyart_tb_product_img');
   		$this->db->select('id_img');
   		if($id_img != ""){
   			$this->db->where(array('img_file_md5' => $file_md5));
   		}
   		else{
   			$this->db->where(array('img_file_md5' => $file_md5, 'id_img != ' => $id_img));
   		}
   		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->row();
   		
   		if(!empty($resultProductImg)){
   			echo 1;
   		}
   		else{
   			echo 0;
   		}
		
	}
	
	public function check_private_post_competition(){
		
		$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : "";
		$user = $this->function_model->getUser($user_id);
		
		if($user->user_level == 5){
			echo 1;
		}
		else{
			echo 0;
		}
		
		exit;
		
	}
	
	public function delete_all_image(){
		
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		$id_imgs = $_POST['id_imgs'];
		$id_img_explode = explode(",", $id_imgs);
		
		for($i=0; $i<count($id_img_explode); $i++){

			$id_img = $id_img_explode[$i];
			
			$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
			$str_img_file_md5 = $detail_product->img_file_md5.".".md5($id_img);
			
			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_product_img',array('img_check_status' => 4,'img_file_md5' => $str_img_file_md5));

		}
		
		echo 1;
		exit;

	}
	
	public function add_to_favorite(){

		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		$id_img = $_POST['id_img'];
		$user_id = $_POST['user_id'];
		
		//get detail user
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
		
		//get detail image
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		
		//get user bookmark image
		$whereUserBookmarkImage = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserBookmarkImage = array();
		$data['detail_user_bookmark_image'] = $this->main_model->getAllData("luxyart_tb_user_bookmark_image", $whereUserBookmarkImage, $orderUserBookmarkImage)->row();
		
		if(count($data['detail_user_bookmark_image']) == 0){
			
			$dataInsertUserBookmarkImage = array(
					'user_id' 		=> $user_id,
					'id_img' 		=> $id_img,
					'date_bookmark' => date('Y-m-d H:i:s')
			);
			$this->db->insert('luxyart_tb_user_bookmark_image', $dataInsertUserBookmarkImage);
			
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "bookmark_image";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['detail_product']->user_email;
			$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_product']->user_id, $this->lang_id);
			$dataSendEmail["img_title"]				= $data['detail_product']->img_title;
			$dataSendEmail["img_width"]				= $data['detail_product']->img_width;
			$dataSendEmail["img_height"]			= $data['detail_product']->img_height;
			$dataSendEmail["link"] 					= base_url()."detail/".$data['detail_product']->img_code.".html";
			$dataSendEmail["user_fullname"] 		= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
			$dataSendEmail["email"] 				= $data['detail_user']->user_email;
			//$dataSendEmail["is_debug"] 			= 1;
					
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "bookmark_image";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $data['detail_product']->user_id;
			$addNotice['user_send_id'] 				= $user_id;
			$addNotice['email'] 					= $data['detail_user']->display_name;
			$addNotice['img_title'] 				= $data['detail_product']->img_title;
			$addNotice["link"] 						= base_url()."detail/".$data['detail_product']->img_code.".html";
			$addNotice['id_img'] 					= $id_img;
				
			$this->function_model->addNotice($addNotice);
			
			echo 1;
			
		}
		else{
			
			echo 0;
			
		}
		
		exit;

	}
	
	public function remove_to_favorite(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo 0;
			exit;
		}
	
		$id_img = $_POST['id_img'];
		$user_id = $_POST['user_id'];
	
		//get detail user
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
	
		//get detail image
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
	
		//get user bookmark image
		$whereUserBookmarkImage = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserBookmarkImage = array();
		$data['detail_user_bookmark_image'] = $this->main_model->getAllData("luxyart_tb_user_bookmark_image", $whereUserBookmarkImage, $orderUserBookmarkImage)->row();
		
		if(count($data['detail_user_bookmark_image']) == 1){
			
			$this->db->where(array('user_id' => $user_id, 'id_img' => $id_img));
			$this->db->delete('luxyart_tb_user_bookmark_image');
				
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "remove_bookmark_image";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['detail_product']->user_email;
			$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_product']->user_id, $this->lang_id);
			$dataSendEmail["img_title"]				= $data['detail_product']->img_title;
			$dataSendEmail["img_width"]				= $data['detail_product']->img_width;
			$dataSendEmail["img_height"]			= $data['detail_product']->img_height;
			$dataSendEmail["link"] 					= base_url()."detail/".$data['detail_product']->img_code.".html";
			$dataSendEmail["user_fullname"] 		= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
			$dataSendEmail["email"] 				= $data['detail_user']->user_email;
			//$dataSendEmail["is_debug"] 			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "remove_bookmark_image";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $data['detail_product']->user_id;
			$addNotice['user_send_id'] 				= $user_id;
			$addNotice['email'] 					= $data['detail_user']->display_name;
			$addNotice['img_title'] 				= $data['detail_product']->img_title;
			$addNotice['id_img'] 					= $id_img;
			$addNotice["link"] 						= base_url()."detail/".$data['detail_product']->img_code.".html";
			
			$this->function_model->addNotice($addNotice);
				
			echo 1;
				
		}
		else{
				
			echo 0;
				
		}
	
		exit;
	
	}
	
	public function add_to_follow(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo 0;
			exit;
		}
	
		$id_img = $_POST['id_img'];
		$user_id = $_POST['user_id'];
	
		//get detail user
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
		
		//get detail image
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		
		//get user follow
		$whereUserFollow = array('user_id' => $data['detail_product']->user_id, 'follow_user_id' => $user_id);
		$orderUserFollow = array();
		$data['detail_user_follow'] = $this->main_model->getAllData("luxyart_tb_user_follow", $whereUserFollow, $orderUserFollow)->row();
		
		if(count($data['detail_user_follow']) == 0){
				
			$dataInsertUserFollow = array(
					'user_id' 			=> $data['detail_product']->user_id,
					'follow_user_id' 	=> $user_id,
					'date_follow' 		=> date('Y-m-d H:i:s')
			);
			$this->db->insert('luxyart_tb_user_follow', $dataInsertUserFollow);
				
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "follow_user";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['detail_product']->user_email;
			$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_product']->user_id, $this->lang_id);
			$dataSendEmail["user_fullname"] 		= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
			$dataSendEmail["email"] 				= $data['detail_user']->user_email;
			//$dataSendEmail["is_debug"] 			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "follow_user";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $data['detail_product']->user_id;
			$addNotice['user_send_id'] 				= $user_id;
			$addNotice['name'] 						= $data['detail_user']->display_name;
			//$addNotice['is_debug'] 				= 1;
				
			$this->function_model->addNotice($addNotice);
				
			echo 1;
				
		}
		else{
				
			echo 0;
				
		}
		
		exit;
	
	}
	public function do_follow_user_timeline()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id_follow = $logged_in['user_id'];
		$user_id = $_POST['user_id'];
		//get detail user
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
		//get user follow
		$whereUserFollow = array('user_id' => $user_id, 'follow_user_id' => $user_id_follow);
		$orderUserFollow = array();
		$data['detail_user_follow'] = $this->main_model->getAllData("luxyart_tb_user_follow", $whereUserFollow, $orderUserFollow)->row();
		
		if(count($data['detail_user_follow']) == 0){
				
			$dataInsertUserFollow = array(
					'user_id' 			=> $user_id,
					'follow_user_id' 	=> $user_id_follow,
					'date_follow' 		=> date('Y-m-d H:i:s')
			);
			$this->db->insert('luxyart_tb_user_follow', $dataInsertUserFollow);
				
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "follow_user";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['detail_user_follow']->user_email;
			$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_user_follow']->user_id, $this->lang_id);
			$dataSendEmail["user_fullname"] 		= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
			$dataSendEmail["email"] 				= $data['detail_user']->user_email;
			//$dataSendEmail["is_debug"] 			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "follow_user";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $data['detail_user_follow']->user_id;
			$addNotice['user_send_id'] 				= $user_id;
			$addNotice['email'] 					= $data['detail_user']->display_name;
			//$addNotice['is_debug'] 				= 1;
				
			$this->function_model->addNotice($addNotice);
				
			echo 1;
				
		}
		else{
				
			echo 0;
				
		}
		exit;
		
	}
	
	public function remove_to_follow(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo 0;
			exit;
		}
	
		$id_img = $_POST['id_img'];
		$user_id = $_POST['user_id'];
	
		//get detail user
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
	
		//get detail image
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
	
		//get user follow
		$whereUserFollow = array('user_id' => $data['detail_product']->user_id, 'follow_user_id' => $user_id);
		$orderUserFollow = array();
		$data['detail_user_follow'] = $this->main_model->getAllData("luxyart_tb_user_follow", $whereUserFollow, $orderUserFollow)->row();
	
		if(count($data['detail_user_follow']) == 1){
			
			$this->db->where(array('user_id' => $data['detail_product']->user_id, 'follow_user_id' => $user_id));
			$this->db->delete('luxyart_tb_user_follow');
	
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "remove_follow_user";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['detail_product']->user_email;
			$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_product']->user_id, $this->lang_id);
			$dataSendEmail["user_fullname"] 		= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
			$dataSendEmail["email"] 				= $data['detail_user']->user_email;
			//$dataSendEmail["is_debug"] 			= 1;
	
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "remove_follow_user";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $data['detail_product']->user_id;
			$addNotice['user_send_id'] 				= $user_id;
			$addNotice['name'] 						= $data['detail_user']->display_name;
			
			$this->function_model->addNotice($addNotice);
	
			echo 1;
	
		}
		else{
	
			echo 0;
	
		}
	
		exit;
	
	}
	
	function add_code_apply_competition(){
		
		//get data
		$id_competition = $_POST['id_competition'];
		$user_id = $_POST['user_id'];

		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo 0;//login
			exit;
		}
		
		//get logged in 
		$logged_in = $this->function_model->get_logged_in();
		if($logged_in['user_id'] != $user_id){
			echo 0;//login
			exit;
		}
		
		//get detail user
		$detail_user = $this->function_model->getUser($user_id);
		
		if($detail_user->user_level < 2){
			echo 2;//upgrade
			exit;
		}
		
		//set data
		$encode = $this->function_model->encrypt_base64_dot($id_competition, $user_id);
		$this->session->set_userdata('apply_competition_encode', $encode);
		
		//return data
		echo 1;//apply competition
		exit;

	}
	
	function apply_competition(){
		
		$id_competition = $_POST['id_competition'];
		$id_imgs = $_POST['id_imgs'];
		$user_id = $_POST['user_id'];
		
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		if($logged_in['user_id'] != $user_id){
			echo 0;
			exit;
		}
		
		//get detail user
		$detail_user = $this->function_model->getUser($user_id);
		
		//get detail compe
		$detail_compe = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		$detail_user_compe = $this->function_model->getUser($detail_compe->user_id);
		
		//get detail user compe
		$id_img_explode = explode(",", $id_imgs);
		
		if(count($id_img_explode) == 0){
			echo 0;
			exit;
		}
		else{
			
			for($i=0; $i<count($id_img_explode); $i++){

				$id_img = $id_img_explode[$i];
					
				//add competition img apply
				$dataCompetitionImgApply= array(
						'id_img' 					=> $id_img,
						'user_id' 					=> $user_id,
						'id_competition' 			=> $id_competition,
						'date_join' 				=> date('Y-m-d H:i:s'),
						'result_img' 				=> 0
				);
				$this->db->insert('luxyart_tb_competition_img_apply', $dataCompetitionImgApply);
				
				$this->db->where('id_img', $id_img);
				$this->db->update('luxyart_tb_product_img',array('img_is_join_compe' => 1));
			
			}
			
			if($detail_compe->status_com == 1){
				$this->db->where('id_competition', $id_competition);
				$this->db->update('luxyart_tb_user_competition',array('status_com' => 2));
			}
			
			//send email user post compe
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "apply_competition";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $detail_user_compe->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($detail_user_compe->user_id,$this->lang_id));
			$dataSendEmail["title_com"]				= $detail_compe->title_com;
			$dataSendEmail["link"]					= base_url()."compe-detail/".$id_competition;
			$dataSendEmail["date_start_com"]		= $detail_compe->date_start_com;
			$dataSendEmail["date_end_com"]			= $detail_compe->date_end_com;
			$dataSendEmail["point_img_com"]			= $detail_compe->point_img_com;
			$dataSendEmail["img_quantity_com"]		= $detail_compe->img_quantity_com;
			$dataSendEmail["link_url_com"]			= $detail_compe->link_url_com;
			$dataSendEmail["email_apply"]			= $detail_user->user_email;
			$dataSendEmail["person_apply"]			= ucwords($this->function_model->get_fullname($detail_user->user_id,$this->lang_id));
			$dataSendEmail["link_compe_check"]		= base_url()."compe-check/".$id_competition.".html";
			$dataSendEmail["is_send_admin"]			= 1;
			//$dataSendEmail["is_debug"]			= 1;
					
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send email user apply compe
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "apply_competition_for_user_apply_compe";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $detail_user->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($detail_user->user_id,$this->lang_id));
			$dataSendEmail["title_com"]				= $detail_compe->title_com;
			$dataSendEmail["link"]					= base_url()."compe-detail/".$id_competition;
			$dataSendEmail["date_start_com"]		= $detail_compe->date_start_com;
			$dataSendEmail["date_end_com"]			= $detail_compe->date_end_com;
			$dataSendEmail["point_img_com"]			= $detail_compe->point_img_com;
			$dataSendEmail["img_quantity_com"]		= $detail_compe->img_quantity_com;
			$dataSendEmail["link_url_com"]			= $detail_compe->link_url_com;
			//$dataSendEmail["is_debug"]			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice user post compe
			$addNotice = array();
			$addNotice['type'] 						= "apply_competition";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $detail_user_compe->user_id;
			$addNotice['email_apply'] 				= $detail_user->display_name;
			$addNotice['id_competition'] 			= $detail_compe->id_competition;
			//$addNotice['is_debug'] 				= 1;
				
			$this->function_model->addNotice($addNotice);
			
			//send notice user post compe
			$addNotice = array();
			$addNotice['type'] 						= "apply_competition_for_user_apply_compe";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $detail_user->user_id;
			$addNotice['user_send_id'] 				= $user_id;
			$addNotice['email_apply'] 				= $detail_user->display_name;
			$addNotice['id_competition'] 			= $detail_compe->id_competition;
			//$addNotice['is_debug'] 				= 1;
			
			$this->function_model->addNotice($addNotice);
			
			//notice
			$_SESSION['return_key'] = 'notice_apply_competition_success';
			
			echo 1;
			exit;

		}

	}
	
	function set_value_search_compe_check(){

		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		//get value search
		$by_popularity = $_POST['by_popularity'];
		$horizontal_vertical = $_POST['horizontal_vertical'];
		$id_colors = $_POST['id_colors'];
		$num_page = $_POST['num_page'];
		$id_competition = $_POST['id_competition'];
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		if($data['detail_compe']->user_id != $user_id){
			echo 0;
			exit;
		}
		
		$id_color = explode(",", $id_colors);
		
		$sess_data = array(
			'by_popularity' 		=> $by_popularity,
			'horizontal_vertical' 	=> $horizontal_vertical,
			'num_page' 				=> $num_page,
			'id_color' 				=> $id_color
		);
		
		$this->session->set_userdata('search_compe_check', $sess_data);
		
		echo 1;
		exit;

	}
	
	function set_value_choose_image_compe(){
		
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		//get value search
		$id_img_chooses = $_POST['id_img_chooses'];
		$id_competition = $_POST['id_competition'];
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		if($data['detail_compe']->user_id != $user_id){
			echo 0;
			exit;
		}
		
		/*$sess_data = array(
		 $id_competition => $id_img_chooses,
		);
		$this->session->set_userdata('choose_img', $sess_data);*/
		
		$this->db->where('id_competition', $id_competition);
		$this->db->update('luxyart_tb_competition_img_apply', array('is_selected' => 0));
		
		$id_img_choose = explode(",",$id_img_chooses);
		for($i=0; $i<count($id_img_choose); $i++){

			$this->db->where(array('id_competition'=>$id_competition,'id_img'=>$id_img_choose[$i]));
			$this->db->update('luxyart_tb_competition_img_apply', array('is_selected' => 1));
			
		}
		
		echo 1;
		exit;

	}
	
	function delete_image_choose(){

		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		//get value search
		$id_img_choose = $_POST['id_img_choose'];
		$id_competition = $_POST['id_competition'];
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		if($data['detail_compe']->user_id != $user_id){
			echo 0;
			exit;
		}
		
		/*$data['choose_img'] = $this->session->userdata["choose_img"];
		if($data['choose_img']){
			
			$choose_img = $data['choose_img'][$id_competition];
			$choose_img_explode = explode(",",$choose_img);
			
			$choose_img_explode = $this->function_model->remove_array_item($choose_img_explode, $id_img_choose);
			$choose_img_new = implode(",", $choose_img_explode);
			
			$sess_data = array(
				$id_competition => $choose_img_new,
			);
			$this->session->set_userdata('choose_img', $sess_data);
		}*/
		
		$this->db->where(array('id_competition'=>$id_competition,'id_img'=>$id_img_choose));
		$this->db->update('luxyart_tb_competition_img_apply', array('is_selected' => 0));
		
		echo 1;
		exit;
		
	}
	
	function reset_choose_image(){

		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		//get value search
		$id_competition = $_POST['id_competition'];
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		if($data['detail_compe']->user_id != $user_id){
			echo 0;
			exit;
		}
		
		//$this->session->unset_userdata('choose_img');
		
		$this->db->where('id_competition', $id_competition);
		$this->db->update('luxyart_tb_competition_img_apply', array('is_selected' => 0));
		
		echo 1;
		exit;

	}
	
	function review_competition(){

		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		//get value search
		$id_competition = $_POST['id_competition'];
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		$img_quantity_com = $data['detail_compe']->img_quantity_com;
		
		if($data['detail_compe']->user_id != $user_id){
			echo 0;
			exit;
		}
		
		$user_ids = array();
		$data['imgs'] = array();
		
		$arr_image_apply_choose = array();
		$data['id_img_choose'] = array();
		$list_img_all = array();
		
		//$data['choose_img'] = $this->session->userdata["choose_img"];
		$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_selected($id_competition)->result();
		$count_img_apply_choose = count($data['list_image_apply_choose']);
		
		$data['list_image_apply_normal'] = $this->manager_compe_model->get_list_image_apply_normal($id_competition)->result();
		$count_img_apply_normal = count($data['list_image_apply_normal']);
		foreach($data['list_image_apply_normal'] as $detail_image_apply_normal){
			array_push($list_img_all, $detail_image_apply_normal->id_img);
		}
		
		$list_img_choose = array();
		foreach($data['list_image_apply_choose'] as $detail_image_apply_choose){
			array_push($list_img_choose, $detail_image_apply_choose->id_img);
		}
		
		if($count_img_apply_normal > $img_quantity_com){
			//echo "So luong chon anh > so luong can<br>";
			$num_get = $img_quantity_com - $count_img_apply_choose;
			
			$data['list_image_apply_add_more'] = $this->manager_compe_model->get_list_image_apply_add_more($id_competition, $list_img_choose, $num_get)->result();
			
			foreach($data['list_image_apply_add_more'] as $list_image_apply_add_more){
				array_push($list_img_choose, $list_image_apply_add_more->id_img);
			}
			
		}
		elseif($count_img_apply_normal < $img_quantity_com){
			//echo "So luong chon anh < so luong can<br>";
			
			if($count_img_apply_choose == $count_img_apply_normal){
				//don't proccessing
			}
			elseif($count_img_apply_choose < $count_img_apply_normal){
				
				$num_get = $img_quantity_com - $count_img_apply_choose;
				
				$data['list_image_apply_add_more'] = $this->manager_compe_model->get_list_image_apply_add_more($id_competition, $list_img_choose, $num_get)->result();
					
				foreach($data['list_image_apply_add_more'] as $list_image_apply_add_more){
					array_push($list_img_choose, $list_image_apply_add_more->id_img);
				}
				
			}
		}
		
		//update img_is_join_compe
		$array_diff = array_diff($list_img_all,$list_img_choose);
		foreach($array_diff as $value_diff){
			
			$this->db->where('id_img', $value_diff);
			$this->db->update('luxyart_tb_product_img', array('img_is_join_compe' => 0));
			
		}
		
		$this->db->where('id_competition', $id_competition);
		$this->db->update('luxyart_tb_competition_img_apply', array('result_img' => 2));
		
		foreach($list_img_choose as $detail_img_choose){
			
			$id_img = $detail_img_choose;
			
			//get product img
			$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
			
			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_competition_img_apply', array('result_img' => 1));
			
			array_push($user_ids, $data['detail_product']->user_id);
			$data['imgs'][$data['detail_product']->user_id][] = $data['detail_product']->server_path_upload."/".$data['detail_product']->file_name_original_img;
			
			//update point user
			$this->db->where('user_id', $data['detail_product']->user_id);
			$this->db->set('user_point','user_point+'.$data['detail_compe']->point_img_com,FALSE);
			$this->db->update('luxyart_tb_user');
			
			$notice_apply_competition_for_person_choose = $this->lang->line('management_point_apply_competition_for_person_choose');
			$notice_apply_competition_for_person_choose = str_replace("{point}", ($data['detail_compe']->point_img_com), $notice_apply_competition_for_person_choose);
			$notice_apply_competition_for_person_choose = str_replace("{title_com}", $data['detail_compe']->title_com, $notice_apply_competition_for_person_choose);
			$notice_apply_competition_for_person_choose = str_replace("{link}", base_url()."compe-detail/".$data['detail_compe']->id_competition.".html", $notice_apply_competition_for_person_choose);
			
			
			//add point user
			$dataAddPointUser = array(
				'user_id' 						=> $data['detail_product']->user_id,
				'point' 						=> $data['detail_compe']->point_img_com,
				'status_change_point' 			=> 1,
				'inbox_id' 						=> $this->function_model->get_inbox_templates_id("review_competition_for_person_post"),
				'content' 						=> $notice_apply_competition_for_person_choose,
				'date_add' 						=> date('Y-m-d H:i:s')
			);
			
			$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
			
		}
		
		//luxyart_tb_user_competition
		//set status_com = 3
		$this->db->where('id_competition', $id_competition);
		$this->db->update('luxyart_tb_user_competition', array('status_com' => 3));
		
		//send email
		$user_ids = array_unique($user_ids);
		$user_ids_all = $this->manager_compe_model->get_all_user_id_apply_competition($id_competition);
		$user_ids_not = array();
		
		foreach($user_ids_all as $_user_ids_all){
			if(!in_array($_user_ids_all->user_id, $user_ids)){
				array_push($user_ids_not, $_user_ids_all->user_id);
			}
		}
		
		//send email notice person success
		foreach($user_ids as $user_id){
			
			$user = $this->function_model->getUser($user_id);
			
			$list_image_choose = "";
			$m = 1;
			foreach($data['imgs'][$user_id] as $detail_img){
				$name_img = end(explode("/", $detail_img));
				if($m == 1){
					//$list_image_choose .= $m."."."<a href='".$detail_img."'>".$name_img."</a>";
					$list_image_choose .= $m.".".$name_img;
				}
				else{
					//$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".<a href='".$detail_img."'>".$name_img."</a>";
					$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".".$name_img;
				}
				$m++;
			}
			
			//send email
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "review_competition_for_person_success";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $user->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
			$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
			$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
			$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
			$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
			$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
			$dataSendEmail["point"]					= count($data['imgs'][$user_id])."x".$data['detail_compe']->point_img_com;
			$dataSendEmail["list_image_choose"]		= $list_image_choose;
			//$dataSendEmail["is_debug"]			= 1;
			
			$this->function_model->sendMailHTML($dataSendEmail);

			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "review_competition_for_person_success";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user->user_id;
			$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
			$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
			$addNotice['point'] 					= count($data['imgs'][$user_id])."x".$data['detail_compe']->point_img_com;
			$addNotice['title_com'] 				= $data['detail_compe']->title_com;
			$addNotice['link'] 						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
			$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
			//$addNotice['is_debug'] 				= 1;
				
			$this->function_model->addNotice($addNotice);

		}
		
		//send email notice person fail
		foreach($user_ids_not as $user_id){
				
			$user = $this->function_model->getUser($user_id);
				
			$list_image_choose = "";
			$m = 1;
			foreach($data['imgs'][$user_id] as $detail_img){
				$name_img = end(explode("/", $detail_img));
				if($m == 1){
					//$list_image_choose .= $m."."."<a href='".$detail_img."'>".$name_img."</a>";
					$list_image_choose .= $m.".".$name_img;
				}
				else{
					//$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".<a href='".$detail_img."'>".$name_img."</a>";
					$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".".$name_img;
				}
				$m++;
			}
				
			//send email
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "review_competition_for_person_fail";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $user->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
			$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
			$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
			$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
			$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
			$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
			$dataSendEmail["list_image_choose"]		= $list_image_choose;
			//$dataSendEmail["is_debug"]			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
		
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "review_competition_for_person_fail";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user->user_id;
			$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
			$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
			$addNotice["title_com"]					= $data['detail_compe']->title_com;
			$addNotice["link"]						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
			$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
			//$addNotice['is_debug'] 				= 1;
		
			$this->function_model->addNotice($addNotice);
		
		}
		
		//send email notice person post
		$user = $this->function_model->getUser($data['detail_compe']->user_id);
		
		//send email
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "review_competition_for_person_post";
		$dataSendEmail["lang_id"] 				= $this->lang_id;
		$dataSendEmail["email_to"] 				= $user->user_email;
		$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
		$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
		$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
		$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
		$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
		$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
		$dataSendEmail["link_image_review"]		= base_url()."mypage/list-compe.html";
		$dataSendEmail["is_send_admin"]			= 1;
		//$dataSendEmail["is_debug"]			= 1;
		
		$this->function_model->sendMailHTML($dataSendEmail);
		
		//send notice
		$addNotice = array();
		$addNotice['type'] 						= "review_competition_for_person_post";
		$addNotice["lang_id"] 					= $this->lang_id;
		$addNotice['user_id'] 					= $user->user_id;
		$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
		$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
		$addNotice["title_com"]					= $data['detail_compe']->title_com;
		$addNotice["link"]						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
		$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
		//$addNotice['is_debug'] 				= 1;
		
		$this->function_model->addNotice($addNotice);
		
		//$this->session->unset_userdata('choose_img');
		
		$this->db->where('id_competition', $id_competition);
		$this->db->update('luxyart_tb_competition_img_apply', array('is_selected' => 0));
		
		echo 1;
		exit;

	}
	
	function rotate(){
		
		$this->load->library('sftp');
		
		$id_img = $_POST["id_img"];
		$degree = $_POST["degree"];
		
		echo "id_img = ".$id_img."<br>";
		echo "degree = ".$degree."<br>";
		
		$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
		
		if(!empty($detail_product)){
			
			$file_name_original_img = $detail_product->file_name_original_img;
			$file_name_watermark_img = $detail_product->file_name_watermark_img;
			$file_name_watermark_img_1 = $detail_product->file_name_watermark_img_1;
			
			echo "file_name_original_img = ".$file_name_original_img."<br>";
			echo "file_name_watermark_img = ".$file_name_watermark_img."<br>";
			echo "file_name_watermark_img_1 = ".$file_name_watermark_img_1."<br>";
			
			$manager_server_old = $this->function_model->getManagerServer($detail_product->id_server);
				
			//connect server old
			$sftp_config['hostname'] 	= $manager_server_old->server_name;
			$sftp_config['username'] 	= $manager_server_old->server_ftp_account;
			$sftp_config['password'] 	= $manager_server_old->server_ftp_pass;
			$sftp_config['port'] 		= $manager_server_old->server_port;
			$sftp_config['debug'] 		= TRUE;
			
			$this->sftp->connect($sftp_config);
			
			echo 1111111111111;
				
			$this->sftp->download($manager_server_old->server_path_upload_img."/".$file_name_original_img, DIR_TEMP.$file_name_original_img);
			$this->sftp->download($manager_server_old->server_path_upload_img."/".$file_name_watermark_img, DIR_TEMP.$file_name_watermark_img);
			$this->sftp->download($manager_server_old->server_path_upload_img."/".$file_name_watermark_img_1, DIR_TEMP.$file_name_watermark_img_1);
			
		}
		else{
			echo 0;
		}
		
		exit;
		
	}
	
	/**
	 * Autorun compe apply expired
	 */
	
	function fix_for_autorun_compe_apply_expired_3_days_ago($id_competition){
		
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		if($data['detail_compe']->status_com >= 1 || $data['detail_compe']->status_com <=3){
			if($data['detail_compe']->is_auto_run_apply_expired == 0){
				
				$date_end_com_update = date('Y-m-d', strtotime('+3 day', strtotime(date('Y-m-d'))));
				
				$this->db->where('id_competition', $id_competition);
				$this->db->update('luxyart_tb_user_competition', array('date_end_com' 	=> $date_end_com_update));
				
				echo "fix for autorun compe apply expired 3 days ago: ".$id_competition;
				
			}
		}
		
	}
	
	function fix_for_autorun_compe_apply_expired($id_competition){
	
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
	
		if($data['detail_compe']->status_com >= 1 || $data['detail_compe']->status_com <=3){
	
			$date_end_com_update = date('Y-m-d');

			$this->db->where('id_competition', $id_competition);
			$this->db->update('luxyart_tb_user_competition', array('date_end_com' 	=> $date_end_com_update));

			echo "fix for autorun compe apply expired: ".$id_competition;
			
		}
	
	}
	
	function fix_for_autorun_compe_choose_image_3_days_ago($id_competition){
		
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		if($data['detail_compe']->status_com >= 1 || $data['detail_compe']->status_com <=3){
			if($data['detail_compe']->is_auto_run_choice_expired == 0){
				
				$date_time_agree_update = date('Y-m-d', strtotime('+3 day', strtotime(date('Y-m-d'))));
				
				$this->db->where('id_competition', $id_competition);
				$this->db->update('luxyart_tb_user_competition', array('date_time_agree' 	=> $date_time_agree_update));
				
				echo "fix for autorun compe choose image 3 days ago: ".$id_competition;
				
			}
		}
		
	}
	
	function fix_for_autorun_compe_choose_image($id_competition){
	
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
	
		if($data['detail_compe']->status_com >= 1 || $data['detail_compe']->status_com <=3){
	
			$date_time_agree_update = date('Y-m-d');

			$this->db->where('id_competition', $id_competition);
			$this->db->update('luxyart_tb_user_competition', array('date_time_agree' 	=> $date_time_agree_update));

			echo "fix for autorun compe choose image: ".$id_competition;
			
		}
	
	}
	
	function autorun_compe_apply_expired(){
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		
		//mail("hotrodangky@viecoi.vn","autorun_compe_apply_expired","123<br>123<br>123");
		
		$setting = $this->function_model->get_setting();
		
		$num_day_extend = $setting['num_day_extend'];
		
		//autorun compe apply expired
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('*');
		$this->db->where(array('is_auto_run_apply_expired' => 0));
		$this->db->where_in('status_com', array(1,2,3));
		$rs = $this->db->get();
		
		$result = $rs->result();
		
		$date_now = date('Y-m-d');
		
		echo "date_now = ".$date_now."<br>";
		
		foreach($result as $_result){

			$id_competition		= $_result->id_competition;
			$date_end_com 		= $_result->date_end_com;
			
			$date_end_com_minus_three_days = date('Y-m-d', strtotime('-3 day', strtotime($date_end_com)));
			
			if($date_end_com_minus_three_days == $date_now){//autorun compe expired
				
				$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
				$code = $this->function_model->encrypt_base64_dot($id_competition, $data['detail_compe']->user_id);
				
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "autorun_compe_apply_expired";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_compe']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_compe']->user_id,$this->lang_id));
				$dataSendEmail["num_day_extend"]		= $num_day_extend;
				$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
				$dataSendEmail["link"]					= base_url()."compe-detail/".$id_competition;
				$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
				$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
				$dataSendEmail["point_img_com"]			= $data['detail_compe']->point_img_com;
				$dataSendEmail["img_quantity_com"]		= $data['detail_compe']->img_quantity_com;
				$dataSendEmail["link_extend"]			= base_url()."extend-compe/".$code;
				//$dataSendEmail["is_debug"]			= 1;
					
				$this->function_model->sendMailHTML($dataSendEmail);
				
				//send notice
				$addNotice = array();
				$addNotice['type'] 						= "autorun_compe_apply_expired";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $data['detail_compe']->user_id;
				$addNotice['title_com'] 				= $data['detail_compe']->title_com;
				$addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
				//$addNotice['is_debug'] 				= 1;
				
				$this->function_model->addNotice($addNotice);
				
				//update is_auto_run_apply_expired = 1
				$this->db->where('id_competition', $id_competition);
				$this->db->update('luxyart_tb_user_competition', array('is_auto_run_apply_expired' => 1));
				
				echo "auto run compe expired 3 days ago: ".$id_competition."<br>";
			
				echo "-----------------------------<br>";
				
			}

		}
		
		//autorun compe apply
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('*');
		$this->db->where_in('status_com', array(1,2,3));
		$rs = $this->db->get();
		
		$result = $rs->result();
		
		foreach($result as $_result){
		
			$id_competition		= $_result->id_competition;
			$date_end_com 		= $_result->date_end_com;
				
			if($date_end_com == $date_now){//autorun compe apply
				
				$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
				
				$count_image_apply = $this->manager_compe_model->get_list_image_apply_autorun($id_competition, "")->num_rows();
				$img_quantity_com = $data['detail_compe']->img_quantity_com;
				$status_com = $data['detail_compe']->status_com;
				
				echo "id_competition = ".$id_competition."<br>";
				echo "count_image_apply = ".$count_image_apply."<br>";
				echo "img_quantity_com = ".$img_quantity_com."<br>";
				echo "status_com = ".$status_com."<br>";
				
				if($status_com == 1 && $count_image_apply == 0){//Khong ai apply => Tra tien lai (129 - email đã có)
					
					$return_point = $data['detail_compe']->point_img_com * $data['detail_compe']->img_quantity_com;
						
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "autorun_compe_finish_no_apply";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $data['detail_compe']->user_email;
					$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_compe']->user_id,$this->lang_id));
					$dataSendEmail["num_day_extend"]		= $num_day_extend;
					$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
					$dataSendEmail["link"]					= base_url()."compe-detail/".$id_competition;
					$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
					$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
					$dataSendEmail["point_img_com"]			= $data['detail_compe']->point_img_com;
					$dataSendEmail["img_quantity_com"]		= $data['detail_compe']->img_quantity_com;
					$dataSendEmail["return_point"]			= $return_point;
					//$dataSendEmail["is_debug"]			= 1;
					
					$this->function_model->sendMailHTML($dataSendEmail);
						
					//send notice
					$addNotice = array();
					$addNotice['type'] 						= "autorun_compe_finish_no_apply";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_id'] 					= $data['detail_compe']->user_id;
					$addNotice['title_com'] 				= $data['detail_compe']->title_com;
					$addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
					$addNotice['id_competition'] 			= $id_competition;
					$addNotice["return_point"]				= $return_point;
					//$addNotice["is_debug"] 				= 1;
						
					$this->function_model->addNotice($addNotice);
						
					$this->db->where('user_id', $data['detail_compe']->user_id);
					$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint+'.($data['detail_compe']->point_img_com * $data['detail_compe']->img_quantity_com),FALSE);
					$this->db->update('luxyart_tb_user');
						
					$notice_point_user_post = $this->lang->line('management_point_compe_finish_no_apply');
					$notice_point_user_post = str_replace("{point}", $return_point, $notice_point_user_post);
					$notice_point_user_post = str_replace("{title_com}", $data['detail_compe']->title_com, $notice_point_user_post);
					$notice_point_user_post = str_replace("{link}", base_url()."compe-detail/".$id_competition, $notice_point_user_post);
						
					$dataAddPointUser = array(
							'user_id' 						=> $data['detail_compe']->user_id,
							'point' 						=> $data['detail_compe']->point_img_com * $data['detail_compe']->img_quantity_com,
							'status_change_point' 			=> 1,
							'inbox_id' 						=> $this->function_model->get_inbox_templates_id("autorun_compe_finish_no_apply"),
							'content' 						=> $notice_point_user_post,
							'date_add' 						=> date('Y-m-d H:i:s')
					);
					$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
						
					//luxyart_tb_user_competition
					//set status_com = 3
					$this->db->where('id_competition', $id_competition);
					$this->db->update('luxyart_tb_user_competition', array('status_com' => 3));
					
					echo "auto run compe finish when don't have no apply: ".$id_competition."<br>";
					
				}
				elseif($status_com == 2 && $count_image_apply > 0){
					
					if($img_quantity_com >= $count_image_apply){//So luong image yeu cau >= so luong apply (nop it hon => chon het)
						
						$user_ids = array();
						
						$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_selected_autorun($id_competition)->result();
						
						//choice all
						foreach($data['list_image_apply_choose'] as $detail_image_apply_choose){
							
							$id_img = $detail_image_apply_choose->id_img;
							
							//get product img
							$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
							
							$this->db->where('id_img', $id_img);
							$this->db->update('luxyart_tb_competition_img_apply', array('result_img' => 1));
							
							array_push($user_ids, $data['detail_product']->user_id);
							$data['imgs'][$data['detail_product']->user_id][] = $data['detail_product']->server_path_upload."/".$data['detail_product']->file_name_original_img;

							//update point user
							$this->db->where('user_id', $data['detail_product']->user_id);
							$this->db->set('user_point','user_point+'.$data['detail_compe']->point_img_com,FALSE);
							$this->db->update('luxyart_tb_user');
							
							$notice_apply_competition_for_person_choose = $this->lang->line('management_point_apply_competition_for_person_choose');
							$notice_apply_competition_for_person_choose = str_replace("{point}", ($data['detail_compe']->point_img_com), $notice_apply_competition_for_person_choose);
							$notice_apply_competition_for_person_choose = str_replace("{title_com}", $data['detail_compe']->title_com, $notice_apply_competition_for_person_choose);
							$notice_apply_competition_for_person_choose = str_replace("{link}", base_url()."compe-detail/".$data['detail_compe']->id_competition.".html", $notice_apply_competition_for_person_choose);
								
							//add point user
							$dataAddPointUser = array(
								'user_id' 						=> $data['detail_product']->user_id,
								'point' 						=> $data['detail_compe']->point_img_com,
								'status_change_point' 			=> 1,
								'inbox_id' 						=> $this->function_model->get_inbox_templates_id("review_competition_for_person_post"),
								'content' 						=> $notice_apply_competition_for_person_choose,
								'date_add' 						=> date('Y-m-d H:i:s')
							);
								
							$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
							
						}
						
						$user_ids = array_unique($user_ids);
						
						//send email notice person success
						foreach($user_ids as $user_id){
								
							$user = $this->function_model->getUser($user_id);
								
							$list_image_choose = "";
							$m = 1;
							foreach($data['imgs'][$user_id] as $detail_img){
								$name_img = end(explode("/", $detail_img));
								if($m == 1){
									//$list_image_choose .= $m."."."<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= $m.".".$name_img;
								}
								else{
									//$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".".$name_img;
								}
								$m++;
							}
								
							//send email
							$dataSendEmail = array();
							$dataSendEmail["name_email_code"] 		= "autorun_review_competition_for_person_success";
							$dataSendEmail["lang_id"] 				= $this->lang_id;
							$dataSendEmail["email_to"] 				= $user->user_email;
							$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
							$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
							$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
							$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
							$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
							$dataSendEmail["point"]					= count($data['imgs'][$user_id])."x".$data['detail_compe']->point_img_com;
							$dataSendEmail["list_image_choose"]		= $list_image_choose;
							//$dataSendEmail["is_debug"]			= 1;
								
							$this->function_model->sendMailHTML($dataSendEmail);
						
							//send notice
							$addNotice = array();
							$addNotice['type'] 						= "autorun_review_competition_for_person_success";
							$addNotice["lang_id"] 					= $this->lang_id;
							$addNotice['user_id'] 					= $user->user_id;
							$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
							$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
							$addNotice['point'] 					= count($data['imgs'][$user_id])."x".$data['detail_compe']->point_img_com;
							$addNotice['title_com'] 				= $data['detail_compe']->title_com;
							$addNotice['link'] 						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
							//$addNotice['is_debug'] 				= 1;
						
							$this->function_model->addNotice($addNotice);
							
							//luxyart_tb_user_competition
							//set status_com = 3
							$this->db->where('id_competition', $id_competition);
							$this->db->update('luxyart_tb_user_competition', array('status_com' => 3));
						
						}
						
						//send email notice person post
						$user = $this->function_model->getUser($data['detail_compe']->user_id);
						
						//send email
						$dataSendEmail = array();
						$dataSendEmail["name_email_code"] 		= "autorun_review_competition_for_person_post";
						$dataSendEmail["lang_id"] 				= $this->lang_id;
						$dataSendEmail["email_to"] 				= $user->user_email;
						$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
						$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
						$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
						$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
						$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
						$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
						$dataSendEmail["link_image_review"]		= base_url()."mypage/list-compe.html";
						$dataSendEmail["is_send_admin"]			= 1;
						//$dataSendEmail["is_debug"]			= 1;
						
						$this->function_model->sendMailHTML($dataSendEmail);
						
						//send notice
						$addNotice = array();
						$addNotice['type'] 						= "autorun_review_competition_for_person_post";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_id'] 					= $user->user_id;
						$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
						$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
						$addNotice["title_com"]					= $data['detail_compe']->title_com;
						$addNotice["link"]						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
						$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
						//$addNotice['is_debug'] 				= 1;
						
						$this->function_model->addNotice($addNotice);
						
						$this->db->where('id_competition', $id_competition);
						$this->db->update('luxyart_tb_competition_img_apply', array('is_selected' => 0));
						
						echo "auto run compe finish choice all image: ".$id_competition."<br>";
						
					}
					elseif($img_quantity_com < $count_image_apply){//So luong image yeu cau < so luong apply (nop nhieu hon => thong bao chon)
						
						//send email notice person post
						$user = $this->function_model->getUser($data['detail_compe']->user_id);
						
						//send email
						$dataSendEmail = array();
						$dataSendEmail["name_email_code"] 		= "autorun_require_choice_image_for_person_post";
						$dataSendEmail["lang_id"] 				= $this->lang_id;
						$dataSendEmail["email_to"] 				= $user->user_email;
						$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
						$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
						$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
						$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
						$dataSendEmail["date_start_choose"]		= $data['detail_compe']->date_end_com;
						$dataSendEmail["date_end_choose"]		= date('Y-m-d', strtotime('-1 day', strtotime($data['detail_compe']->date_time_agree)))." 23:59";
						$dataSendEmail["link_image_choose"]		= base_url()."compe-check/".$data['detail_compe']->id_competition;
						$dataSendEmail["is_send_admin"]			= 1;
						
						$this->function_model->sendMailHTML($dataSendEmail);
						
						//send notice
						$addNotice = array();
						$addNotice['type'] 						= "autorun_require_choice_image_for_person_post";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_id'] 					= $user->user_id;
						$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
						$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
						$addNotice["title_com"]					= $data['detail_compe']->title_com;
						$addNotice["link"]						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
						$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
						//$addNotice['is_debug'] 				= 1;
						
						$this->function_model->addNotice($addNotice);
						
						echo "auto run send email require choice image for person post: ".$id_competition."<br>";
						
					}
					
				}
					
				echo "-----------------------------<br>";
		
			}
		
		}
		
		exit;

	}
	
	function extend_compe($code){
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		
		$setting = $this->function_model->get_setting();
		
		$num_day_extend = $setting['num_day_extend'];
		
		echo $code."<br>";
		
		$decode = $this->function_model->decrypt_base64_dot($code);
		$id_competition = $decode[0];
		$user_id = $decode[1];
		
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		$status_com = $data['detail_compe']->status_com;
		$is_auto_run_apply_expired = $data['detail_compe']->is_auto_run_apply_expired;
		
		if($is_auto_run_apply_expired == 1 && ($status_com > 0 && $status_com < 4)){
			
			$date_extend = date('Y-m-d', strtotime($num_day_extend, strtotime($data['detail_compe']->date_end_com)));
			$date_time_agree_extend = date('Y-m-d', strtotime($num_day_extend, strtotime($data['detail_compe']->date_time_agree)));
			
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "extend_compe_success";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['detail_compe']->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_compe']->user_id,$this->lang_id));
			$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
			$dataSendEmail["link"]					= base_url()."compe-detail/".$id_competition;
			$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
			$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
			$dataSendEmail["point_img_com"]			= $data['detail_compe']->point_img_com;
			$dataSendEmail["img_quantity_com"]		= $data['detail_compe']->img_quantity_com;
			$dataSendEmail["date_extend"]			= $date_extend;
			//$dataSendEmail["is_debug"]			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "extend_compe_success";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user_id;
			$addNotice['title_com'] 				= $data['detail_compe']->title_com;
			$addNotice["link"]						= base_url()."compe-detail/".$id_competition;
			//$addNotice['is_debug'] 				= 1;
			
			$this->function_model->addNotice($addNotice);
			
			//update is auto run = 2
			$this->db->where('id_competition', $id_competition);
			$this->db->update('luxyart_tb_user_competition', array('date_end_com'=>$date_extend,'date_time_agree'=>$date_time_agree_extend,'is_auto_run_apply_expired'=>2));
			
			//notice
			$_SESSION['return_key'] = 'notice_compe_extend_success';
			header("Location: ".base_url()."notice.html");

		}
		else{

			//notice
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");		

		}

	}
	
	function autorun_compe_choice_image(){
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		
		//mail("hotrodangky@viecoi.vn","autorun_compe_choice_image","123<br>123<br>123");
		
		$setting = $this->function_model->get_setting();
		
		$num_day_extend = $setting['num_day_extend'];
		
		//auto run choice compe 3 days ago
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('*');
		$this->db->where(array('is_auto_run_choice_expired' => 0));
		$this->db->where_in('status_com', array(1,2,3));
		$rs = $this->db->get();
		
		$result = $rs->result();
		
		$date_now = date('Y-m-d');
		
		echo "date_now = ".$date_now."<br>";
		
		foreach($result as $_result){

			$id_competition		= $_result->id_competition;
			$date_time_agree 	= $_result->date_time_agree;
			
			$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
			$img_quantity_com = $data['detail_compe']->img_quantity_com;
			
			$date_time_agree = date('Y-m-d', strtotime($date_time_agree));
			$date_time_agree_minus_three_days = date('Y-m-d', strtotime('-3 day', strtotime($date_time_agree)));
			
			//if($id_competition == 111){
			
			if($date_time_agree_minus_three_days == $date_now){
				
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "autorun_compe_choice_image_before_num_days";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_compe']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_compe']->user_id,$this->lang_id));
				$dataSendEmail["num_day_extend"]		= $num_day_extend;
				$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
				$dataSendEmail["link"]					= base_url()."compe-detail/".$id_competition;
				$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
				$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
				$dataSendEmail["point_img_com"]			= $data['detail_compe']->point_img_com;
				$dataSendEmail["img_quantity_com"]		= $data['detail_compe']->img_quantity_com;
				//$dataSendEmail["is_debug"]			= 1;
					
				$this->function_model->sendMailHTML($dataSendEmail);
				
				//send notice
				$addNotice = array();
				$addNotice['type'] 						= "autorun_compe_choice_image_before_num_days";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $data['detail_compe']->user_id;
				$addNotice['title_com'] 				= $data['detail_compe']->title_com;
				$addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
				$addNotice['id_competition'] 			= $id_competition;
				//$addNotice["is_debug"] 				= 1;
				
				$this->function_model->addNotice($addNotice);
				
				//update is_auto_run_apply_expired = 1
				$this->db->where('id_competition', $id_competition);
				$this->db->update('luxyart_tb_user_competition', array('is_auto_run_choice_expired' => 1));
				
				echo "auto run choice compe 3 days ago: ".$id_competition."<br>";
				
			}
				
			//}

		}
		
		//auto run choice compe
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('*');
		$this->db->where_in('status_com', array(1,2,3));
		$rs = $this->db->get();
		
		$result = $rs->result();
		
		foreach($result as $_result){
			
			$id_competition		= $_result->id_competition;
			$date_time_agree 	= $_result->date_time_agree;
			
			$date_time_agree = date('Y-m-d', strtotime($date_time_agree));
				
			$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
			$img_quantity_com = $data['detail_compe']->img_quantity_com;
				
			$date_time_agree = date('Y-m-d', strtotime($date_time_agree));
				
			//if($id_competition == 114){
				
			if($date_time_agree == $date_now){
				
				$count_image_apply = $this->manager_compe_model->get_list_image_apply_autorun($id_competition, "")->num_rows();
				$status_com = $data['detail_compe']->status_com;
				
				if($status_com == 1 && $count_image_apply == 0){
					
					echo "Don't exist: auto run compe finish when don't have no apply: ".$id_competition."<br>";
					
				}
				elseif($status_com == 2 && $count_image_apply > 0){
					
					if($img_quantity_com >= $count_image_apply){//So luong image yeu cau >= so luong apply (nop it hon => chon het)
						
						echo "Don't exist: auto run compe finish choice all image: ".$id_competition."<br>";
					}
					elseif($img_quantity_com < $count_image_apply){//So luong image yeu cau < so luong apply (nop nhieu hon => thong bao chon)
						
						$user_ids = array();
						$data['imgs'] = array();
							
						$arr_image_apply_choose = array();
						$data['id_img_choose'] = array();
						$list_img_all = array();
						$list_img_choose = array();
						
						echo "So luong can: ".$img_quantity_com."<br>";
						echo "So luong apply: ".$count_image_apply."<br>";
						
						$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_selected($id_competition)->result();
						
						$data['list_image_apply_normal'] = $this->manager_compe_model->get_list_image_apply_normal($id_competition)->result();
						foreach($data['list_image_apply_normal'] as $detail_image_apply_normal){
							array_push($list_img_all, $detail_image_apply_normal->id_img);
						}
						
						$num_get = $img_quantity_com - $count_img_apply_choose;
						
						$data['list_image_apply_add_more'] = $this->manager_compe_model->get_list_image_apply_add_more($id_competition, $list_img_choose, $num_get)->result();
							
						foreach($data['list_image_apply_add_more'] as $list_image_apply_add_more){
							array_push($list_img_choose, $list_image_apply_add_more->id_img);
						}
						
						//update img_is_join_compe
						$array_diff = array_diff($list_img_all,$list_img_choose);
						
						foreach($array_diff as $value_diff){
								
							$this->db->where('id_img', $value_diff);
							$this->db->update('luxyart_tb_product_img', array('img_is_join_compe' => 0));
								
						}
						
						$this->db->where('id_competition', $id_competition);
						$this->db->update('luxyart_tb_competition_img_apply', array('result_img' => 2));
						
						foreach($list_img_choose as $detail_img_choose){
							
							$id_img = $detail_img_choose;
							
							//get product img
							$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
							
							$this->db->where('id_img', $id_img);
							$this->db->update('luxyart_tb_competition_img_apply', array('result_img' => 1));
							
							array_push($user_ids, $data['detail_product']->user_id);
							$data['imgs'][$data['detail_product']->user_id][] = $data['detail_product']->server_path_upload."/".$data['detail_product']->file_name_original_img;
							
							//update point user
							$this->db->where('user_id', $data['detail_product']->user_id);
							$this->db->set('user_point','user_point+'.$data['detail_compe']->point_img_com,FALSE);
							$this->db->update('luxyart_tb_user');
							
							$notice_apply_competition_for_person_choose = $this->lang->line('management_point_apply_competition_for_person_choose');
							$notice_apply_competition_for_person_choose = str_replace("{point}", ($data['detail_compe']->point_img_com), $notice_apply_competition_for_person_choose);
							$notice_apply_competition_for_person_choose = str_replace("{title_com}", $data['detail_compe']->title_com, $notice_apply_competition_for_person_choose);
							$notice_apply_competition_for_person_choose = str_replace("{link}", base_url()."compe-detail/".$data['detail_compe']->id_competition.".html", $notice_apply_competition_for_person_choose);
								
							//add point user
							$dataAddPointUser = array(
								'user_id' 						=> $data['detail_product']->user_id,
								'point' 						=> $data['detail_compe']->point_img_com,
								'status_change_point' 			=> 1,
								'inbox_id' 						=> $this->function_model->get_inbox_templates_id("review_competition_for_person_post"),
								'content' 						=> $notice_apply_competition_for_person_choose,
								'date_add' 						=> date('Y-m-d H:i:s')
							);
							$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
							
						}
						
						//luxyart_tb_user_competition
						//set status_com = 3
						$this->db->where('id_competition', $id_competition);
						$this->db->update('luxyart_tb_user_competition', array('status_com' => 3));
						
						//send email
						$user_ids = array_unique($user_ids);
						$user_ids_all = $this->manager_compe_model->get_all_user_id_apply_competition($id_competition);
						$user_ids_not = array();
						
						foreach($user_ids_all as $_user_ids_all){
							if(!in_array($_user_ids_all->user_id, $user_ids)){
								array_push($user_ids_not, $_user_ids_all->user_id);
							}
						}
						
						//send email notice person success
						foreach($user_ids as $user_id){
								
							$user = $this->function_model->getUser($user_id);
								
							$list_image_choose = "";
							$m = 1;
							foreach($data['imgs'][$user_id] as $detail_img){
								$name_img = end(explode("/", $detail_img));
								if($m == 1){
									//$list_image_choose .= $m."."."<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= $m.".".$name_img;
								}
								else{
									//$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".".$name_img;
								}
								$m++;
							}
								
							//send email
							$dataSendEmail = array();
							$dataSendEmail["name_email_code"] 		= "autorun_review_competition_for_person_success";
							$dataSendEmail["lang_id"] 				= $this->lang_id;
							$dataSendEmail["email_to"] 				= $user->user_email;
							$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
							$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
							$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
							$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
							$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
							$dataSendEmail["point"]					= count($data['imgs'][$user_id])."x".$data['detail_compe']->point_img_com;
							$dataSendEmail["list_image_choose"]		= $list_image_choose;
							//$dataSendEmail["is_debug"]			= 1;
								
							$this->function_model->sendMailHTML($dataSendEmail);
						
							//send notice
							$addNotice = array();
							$addNotice['type'] 						= "autorun_review_competition_for_person_success";
							$addNotice["lang_id"] 					= $this->lang_id;
							$addNotice['user_id'] 					= $user->user_id;
							$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
							$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
							$addNotice['point'] 					= count($data['imgs'][$user_id])."x".$data['detail_compe']->point_img_com;
							$addNotice['title_com'] 				= $data['detail_compe']->title_com;
							$addNotice['link'] 						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
							//$addNotice['is_debug'] 				= 1;
						
							$this->function_model->addNotice($addNotice);
						
						}
						
						//send email notice person fail
						foreach($user_ids_not as $user_id){
						
							$user = $this->function_model->getUser($user_id);
						
							$list_image_choose = "";
							$m = 1;
							foreach($data['imgs'][$user_id] as $detail_img){
								$name_img = end(explode("/", $detail_img));
								if($m == 1){
									//$list_image_choose .= $m."."."<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= $m.".".$name_img;
								}
								else{
									//$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".".$name_img;
								}
								$m++;
							}
						
							//send email
							$dataSendEmail = array();
							$dataSendEmail["name_email_code"] 		= "autorun_review_competition_for_person_fail";
							$dataSendEmail["lang_id"] 				= $this->lang_id;
							$dataSendEmail["email_to"] 				= $user->user_email;
							$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
							$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
							$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
							$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
							$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
							$dataSendEmail["list_image_choose"]		= $list_image_choose;
							//$dataSendEmail["is_debug"]			= 1;
						
							$this->function_model->sendMailHTML($dataSendEmail);
						
							//send notice
							$addNotice = array();
							$addNotice['type'] 						= "autorun_review_competition_for_person_fail";
							$addNotice["lang_id"] 					= $this->lang_id;
							$addNotice['user_id'] 					= $user->user_id;
							$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
							$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
							$addNotice["title_com"]					= $data['detail_compe']->title_com;
							$addNotice["link"]						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
							//$addNotice['is_debug'] 				= 1;
						
							$this->function_model->addNotice($addNotice);
						
						}
						
						//send email notice person post
						$user = $this->function_model->getUser($data['detail_compe']->user_id);
						
						//send email
						$dataSendEmail = array();
						$dataSendEmail["name_email_code"] 		= "autorun_review_competition_time_choice_image_for_person_post";
						$dataSendEmail["lang_id"] 				= $this->lang_id;
						$dataSendEmail["email_to"] 				= $user->user_email;
						$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
						$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
						$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
						$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
						$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
						$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
						$dataSendEmail["link_image_review"]		= base_url()."mypage/list-compe-join.html";
						$dataSendEmail["is_send_admin"]			= 1;
						//$dataSendEmail["is_debug"]			= 1;
						
						$this->function_model->sendMailHTML($dataSendEmail);
						
						//send notice
						$addNotice = array();
						$addNotice['type'] 						= "autorun_review_competition_time_choice_image_for_person_post";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_id'] 					= $user->user_id;
						$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
						$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
						$addNotice["title_com"]					= $data['detail_compe']->title_com;
						$addNotice["link"]						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
						$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
						//$addNotice['is_debug'] 				= 1;
						
						$this->function_model->addNotice($addNotice);
						
						$this->db->where('id_competition', $id_competition);
						$this->db->update('luxyart_tb_competition_img_apply', array('is_selected' => 0));
						
						echo "auto run send email require choice image for person post: ".$id_competition."<br>";
						
					}
					
				}
				
				
			}
			
			//}
			
		}
		
		exit;

	}
	
	function autorun_compe_choice_image_test(){
	
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	
		//mail("hotrodangky@viecoi.vn","autorun_compe_choice_image","123<br>123<br>123");
	
		$setting = $this->function_model->get_setting();
	
		$num_day_extend = $setting['num_day_extend'];
	
		//auto run choice compe 3 days ago
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('*');
		$this->db->where(array('is_auto_run_choice_expired' => 0));
		$this->db->where_in('status_com', array(1,2,3));
		$rs = $this->db->get();
	
		$result = $rs->result();
	
		$date_now = date('Y-m-d');
	
		echo "date_now = ".$date_now."<br>";
	
		foreach($result as $_result){
	
			$id_competition		= $_result->id_competition;
			$date_time_agree 	= $_result->date_time_agree;
				
			$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
			$img_quantity_com = $data['detail_compe']->img_quantity_com;
				
			$date_time_agree = date('Y-m-d', strtotime($date_time_agree));
			$date_time_agree_minus_three_days = date('Y-m-d', strtotime('-3 day', strtotime($date_time_agree)));
				
			if($id_competition == 22){
				
			if($date_time_agree_minus_three_days == $date_now){
	
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "autorun_compe_choice_image_before_num_days";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_compe']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_compe']->user_id,$this->lang_id));
				$dataSendEmail["num_day_extend"]		= $num_day_extend;
				$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
				$dataSendEmail["link"]					= base_url()."compe-detail/".$id_competition;
				$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
				$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
				$dataSendEmail["point_img_com"]			= $data['detail_compe']->point_img_com;
				$dataSendEmail["img_quantity_com"]		= $data['detail_compe']->img_quantity_com;
				//$dataSendEmail["is_debug"]			= 1;
					
				$this->function_model->sendMailHTML($dataSendEmail);
	
				//send notice
				$addNotice = array();
				$addNotice['type'] 						= "autorun_compe_choice_image_before_num_days";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $data['detail_compe']->user_id;
				$addNotice['title_com'] 				= $data['detail_compe']->title_com;
				$addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
				$addNotice['id_competition'] 			= $id_competition;
				//$addNotice["is_debug"] 				= 1;
	
				$this->function_model->addNotice($addNotice);
	
				//update is_auto_run_apply_expired = 1
				$this->db->where('id_competition', $id_competition);
				$this->db->update('luxyart_tb_user_competition', array('is_auto_run_choice_expired' => 1));
	
				echo "auto run choice compe 3 days ago: ".$id_competition."<br>";
	
			}
	
			}
	
		}
	
		//auto run choice compe
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('*');
		$this->db->where_in('status_com', array(1,2,3));
		$rs = $this->db->get();
	
		$result = $rs->result();
	
		foreach($result as $_result){
				
			$id_competition		= $_result->id_competition;
			$date_time_agree 	= $_result->date_time_agree;
				
			$date_time_agree = date('Y-m-d', strtotime($date_time_agree));
	
			$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
			$img_quantity_com = $data['detail_compe']->img_quantity_com;
	
			$date_time_agree = date('Y-m-d', strtotime($date_time_agree));
	
			if($id_competition == 22){
				
				
				echo "date_time_agree = ".$date_time_agree."<br>";
				
				$date_time_agree = "2018-09-04";
				
				echo "date_now = ".$date_now."<br>";
	
			if($date_time_agree == $date_now){
	
				$count_image_apply = $this->manager_compe_model->get_list_image_apply_autorun($id_competition, "")->num_rows();
				$status_com = $data['detail_compe']->status_com;
	
				if($status_com == 1 && $count_image_apply == 0){
						
					echo "Don't exist: auto run compe finish when don't have no apply: ".$id_competition."<br>";
						
				}
				elseif($status_com == 2 && $count_image_apply > 0){
						
					if($img_quantity_com >= $count_image_apply){//So luong image yeu cau >= so luong apply (nop it hon => chon het)
	
						echo "Don't exist: auto run compe finish choice all image: ".$id_competition."<br>";
					}
					elseif($img_quantity_com < $count_image_apply){//So luong image yeu cau < so luong apply (nop nhieu hon => thong bao chon)
	
						$user_ids = array();
						$data['imgs'] = array();
							
						$arr_image_apply_choose = array();
						$data['id_img_choose'] = array();
						$list_img_all = array();
						$list_img_choose = array();
	
						$data['list_image_apply_choose'] = $this->manager_compe_model->get_list_image_apply_selected($id_competition)->result();
	
						$data['list_image_apply_normal'] = $this->manager_compe_model->get_list_image_apply_normal($id_competition)->result();
						foreach($data['list_image_apply_normal'] as $detail_image_apply_normal){
							array_push($list_img_all, $detail_image_apply_normal->id_img);
						}
	
						$num_get = $img_quantity_com - $count_img_apply_choose;
	
						$data['list_image_apply_add_more'] = $this->manager_compe_model->get_list_image_apply_add_more($id_competition, $list_img_choose, $num_get)->result();
							
						foreach($data['list_image_apply_add_more'] as $list_image_apply_add_more){
							array_push($list_img_choose, $list_image_apply_add_more->id_img);
						}
						
						echo "<pre>";
						print_r($list_img_choose);
						exit;
	
						//update img_is_join_compe
						$array_diff = array_diff($list_img_all,$list_img_choose);
	
						foreach($array_diff as $value_diff){
	
							$this->db->where('id_img', $value_diff);
							$this->db->update('luxyart_tb_product_img', array('img_is_join_compe' => 0));
	
						}
	
						$this->db->where('id_competition', $id_competition);
						$this->db->update('luxyart_tb_competition_img_apply', array('result_img' => 2));
	
						foreach($list_img_choose as $detail_img_choose){
								
							$id_img = $detail_img_choose;
								
							//get product img
							$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
								
							$this->db->where('id_img', $id_img);
							$this->db->update('luxyart_tb_competition_img_apply', array('result_img' => 1));
								
							array_push($user_ids, $data['detail_product']->user_id);
							$data['imgs'][$data['detail_product']->user_id][] = $data['detail_product']->server_path_upload."/".$data['detail_product']->file_name_original_img;
								
							//update point user
							$this->db->where('user_id', $data['detail_product']->user_id);
							$this->db->set('user_point','user_point+'.$data['detail_compe']->point_img_com,FALSE);
							$this->db->update('luxyart_tb_user');
								
							$notice_apply_competition_for_person_choose = $this->lang->line('management_point_apply_competition_for_person_choose');
							$notice_apply_competition_for_person_choose = str_replace("{point}", ($data['detail_compe']->point_img_com), $notice_apply_competition_for_person_choose);
							$notice_apply_competition_for_person_choose = str_replace("{title_com}", $data['detail_compe']->title_com, $notice_apply_competition_for_person_choose);
							$notice_apply_competition_for_person_choose = str_replace("{link}", base_url()."compe-detail/".$data['detail_compe']->id_competition.".html", $notice_apply_competition_for_person_choose);
	
							//add point user
							$dataAddPointUser = array(
									'user_id' 						=> $data['detail_product']->user_id,
									'point' 						=> $data['detail_compe']->point_img_com,
									'status_change_point' 			=> 1,
									'inbox_id' 						=> $this->function_model->get_inbox_templates_id("review_competition_for_person_post"),
									'content' 						=> $notice_apply_competition_for_person_choose,
									'date_add' 						=> date('Y-m-d H:i:s')
							);
							$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
								
						}
	
						//luxyart_tb_user_competition
						//set status_com = 3
						$this->db->where('id_competition', $id_competition);
						$this->db->update('luxyart_tb_user_competition', array('status_com' => 3));
	
						//send email
						$user_ids = array_unique($user_ids);
						$user_ids_all = $this->manager_compe_model->get_all_user_id_apply_competition($id_competition);
						$user_ids_not = array();
	
						foreach($user_ids_all as $_user_ids_all){
							if(!in_array($_user_ids_all->user_id, $user_ids)){
								array_push($user_ids_not, $_user_ids_all->user_id);
							}
						}
	
						//send email notice person success
						foreach($user_ids as $user_id){
	
							$user = $this->function_model->getUser($user_id);
	
							$list_image_choose = "";
							$m = 1;
							foreach($data['imgs'][$user_id] as $detail_img){
								$name_img = end(explode("/", $detail_img));
								if($m == 1){
									//$list_image_choose .= $m."."."<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= $m.".".$name_img;
								}
								else{
									//$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".".$name_img;
								}
								$m++;
							}
	
							//send email
							$dataSendEmail = array();
							$dataSendEmail["name_email_code"] 		= "autorun_review_competition_for_person_success";
							$dataSendEmail["lang_id"] 				= $this->lang_id;
							$dataSendEmail["email_to"] 				= $user->user_email;
							$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
							$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
							$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
							$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
							$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
							$dataSendEmail["point"]					= count($data['imgs'][$user_id])."x".$data['detail_compe']->point_img_com;
							$dataSendEmail["list_image_choose"]		= $list_image_choose;
							//$dataSendEmail["is_debug"]			= 1;
	
							$this->function_model->sendMailHTML($dataSendEmail);
	
							//send notice
							$addNotice = array();
							$addNotice['type'] 						= "autorun_review_competition_for_person_success";
							$addNotice["lang_id"] 					= $this->lang_id;
							$addNotice['user_id'] 					= $user->user_id;
							$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
							$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
							$addNotice['point'] 					= count($data['imgs'][$user_id])."x".$data['detail_compe']->point_img_com;
							$addNotice['title_com'] 				= $data['detail_compe']->title_com;
							$addNotice['link'] 						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
							//$addNotice['is_debug'] 				= 1;
	
							$this->function_model->addNotice($addNotice);
	
						}
	
						//send email notice person fail
						foreach($user_ids_not as $user_id){
	
							$user = $this->function_model->getUser($user_id);
	
							$list_image_choose = "";
							$m = 1;
							foreach($data['imgs'][$user_id] as $detail_img){
								$name_img = end(explode("/", $detail_img));
								if($m == 1){
									//$list_image_choose .= $m."."."<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= $m.".".$name_img;
								}
								else{
									//$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".<a href='".$detail_img."'>".$name_img."</a>";
									$list_image_choose .= "<br>&nbsp;&nbsp;".$m.".".$name_img;
								}
								$m++;
							}
	
							//send email
							$dataSendEmail = array();
							$dataSendEmail["name_email_code"] 		= "autorun_review_competition_for_person_fail";
							$dataSendEmail["lang_id"] 				= $this->lang_id;
							$dataSendEmail["email_to"] 				= $user->user_email;
							$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
							$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
							$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
							$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
							$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
							$dataSendEmail["list_image_choose"]		= $list_image_choose;
							//$dataSendEmail["is_debug"]			= 1;
	
							$this->function_model->sendMailHTML($dataSendEmail);
	
							//send notice
							$addNotice = array();
							$addNotice['type'] 						= "autorun_review_competition_for_person_fail";
							$addNotice["lang_id"] 					= $this->lang_id;
							$addNotice['user_id'] 					= $user->user_id;
							$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
							$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
							$addNotice["title_com"]					= $data['detail_compe']->title_com;
							$addNotice["link"]						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
							$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
							//$addNotice['is_debug'] 				= 1;
	
							$this->function_model->addNotice($addNotice);
	
						}
	
						//send email notice person post
						$user = $this->function_model->getUser($data['detail_compe']->user_id);
	
						//send email
						$dataSendEmail = array();
						$dataSendEmail["name_email_code"] 		= "autorun_review_competition_time_choice_image_for_person_post";
						$dataSendEmail["lang_id"] 				= $this->lang_id;
						$dataSendEmail["email_to"] 				= $user->user_email;
						$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($user->user_id, $this->lang_id));
						$dataSendEmail["email_post_competition"]= $data['detail_compe']->user_email;
						$dataSendEmail["title_com"]				= $data['detail_compe']->title_com;
						$dataSendEmail["link"]					= base_url()."compe-detail/".$data['detail_compe']->id_competition;
						$dataSendEmail["date_start_com"]		= $data['detail_compe']->date_start_com;
						$dataSendEmail["date_end_com"]			= $data['detail_compe']->date_end_com;
						$dataSendEmail["link_image_review"]		= base_url()."mypage/list-compe-join.html";
						$dataSendEmail["is_send_admin"]			= 1;
						//$dataSendEmail["is_debug"]			= 1;
	
						$this->function_model->sendMailHTML($dataSendEmail);
	
						//send notice
						$addNotice = array();
						$addNotice['type'] 						= "autorun_review_competition_time_choice_image_for_person_post";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_id'] 					= $user->user_id;
						$addNotice['user_send_id'] 				= $data['detail_compe']->user_id;
						$addNotice['email_post_competition'] 	= $data['detail_compe']->display_name;
						$addNotice["title_com"]					= $data['detail_compe']->title_com;
						$addNotice["link"]						= base_url()."compe-detail/".$data['detail_compe']->id_competition;
						$addNotice['option_id'] 				= $data['detail_compe']->id_competition;
						//$addNotice['is_debug'] 				= 1;
	
						$this->function_model->addNotice($addNotice);
	
						$this->db->where('id_competition', $id_competition);
						$this->db->update('luxyart_tb_competition_img_apply', array('is_selected' => 0));
	
						echo "auto run send email require choice image for person post: ".$id_competition."<br>";
	
					}
						
				}
	
	
			}
				
			}
				
		}
	
		exit;
	
	}
	
	function autorun_reset_point_expired(){
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		
		//mail("hotrodangky@viecoi.vn","autorun_reset_point_expired","123<br>123<br>123");
		
		$setting = $this->function_model->get_setting();
		
		$reset_point_package = $setting['reset_point_package'];
		$reset_point_package = "+ ".$reset_point_package." month";
		
		//auto run choice compe 3 days ago
		$this->db->from('luxyart_tb_user_use_point_package');
		$this->db->select('*');
		$this->db->where(array('is_reset' => 0));
		$rs = $this->db->get();
		
		$result = $rs->result();
		
		$date_now = date('Y-m-d');
		
		echo "date_now = ".$date_now."<br>";
		
		foreach($result as $_result){
			
			$id_use_point_pk = $_result->id_use_point_pk;
			$user_id = $_result->user_id;
			$id_list = $_result->id_list;
			$date_add = $_result->date_add;
			
			$date_reset_point_expired = date('Y-m-d', strtotime($date_add));
			$date_reset_point_expired_end = date('Y-m-d', strtotime($reset_point_package, strtotime($date_reset_point_expired)));
			
			$user = $this->function_model->getUser($user_id);
				
			//if($id_use_point_pk == 4){
				
				if($date_reset_point_expired_end == $date_now){
					
					$detail_credit = $this->function_model->get_detail_credit($id_list)->row();
					
					$point_need_update = $_result->value_point_pk - $_result->history_use_point;
					
					$this->db->where('id_use_point_pk', $id_use_point_pk);
					$this->db->set('history_use_point','history_use_point+'.$point_need_update,FALSE);
					$this->db->update('luxyart_tb_user_use_point_package');
					
					$this->db->where('id_use_point_pk', $id_use_point_pk);
					$this->db->update('luxyart_tb_user_use_point_package', array('status' => 0,'is_reset' => 1));
					
					//update status next record
					$this->db->select_min('id_use_point_pk');
					$this->db->where('history_use_point < value_point_pk and user_id = '.$user_id);
					$rsMinIdUsePointPk = $this->db->get('luxyart_tb_user_use_point_package');
					$rowMinIdUsePointPk = $rsMinIdUsePointPk->row();
					$id_use_point_pk_next = $rowMinIdUsePointPk->id_use_point_pk;
					
					$this->db->where('id_use_point_pk', $id_use_point_pk_next);
					$this->db->update('luxyart_tb_user_use_point_package', array('status' => 1));
					
					//send email
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "autorun_reset_point_expired";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $user->user_email;
					$dataSendEmail["name"] 					= ucwords($user->display_name);
					$dataSendEmail["package_name"]			= $detail_credit->name_credit;
					$dataSendEmail["point"]					= $detail_credit->point_credit;
					$dataSendEmail["link"]					= base_url("mypage/user-purchase-order.html");
					$dataSendEmail["is_send_admin"]			= 1;
					//$dataSendEmail["is_debug"]			= 1;
						
					$this->function_model->sendMailHTML($dataSendEmail);
					
					//send notice
					$addNotice = array();
					$addNotice['type'] 						= "autorun_reset_point_expired";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_id'] 					= $user_id;
					$addNotice["package_name"]				= $detail_credit->name_credit;
					$addNotice["point"]						= $detail_credit->point_credit;
					$addNotice["link"]						= base_url("mypage/user-purchase-order.html");
					//$addNotice['option_id'] 				= $id_competition;
					//$addNotice['is_debug'] 				= 1;
						
					$this->function_model->addNotice($addNotice);
					
					echo "autorun reset point expired: ".$id_use_point_pk."<br>";
					
				}
				
				echo "----------------------------<br>";
			
			//}
			
		}
		
	}
	
	function download_picture(){
		
		$this->load->library('sftp');
		
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		//get value search
		$id_competition = $_POST['id_competition'];
		
		//get detail compe
		$data['detail_compe'] = $this->manager_compe_model->get_detail_compe($id_competition)->row();
		
		if($data['detail_compe']->user_id != $user_id){
			echo 0;
			exit;
		}
		
		if($data['detail_compe']->status_com != 3){
			echo 0;
			exit;
		}
		
		$list_image_applied_finish = $this->manager_compe_model->get_list_image_applied_finish($id_competition)->result();
		
		$arr_server = array();
		$file_names = array();
		
		foreach($list_image_applied_finish as $detail_image_applied_finish){
			$id_server = $detail_image_applied_finish->id_server;
			$arr_server[$id_server][] = $detail_image_applied_finish;
		}
		
		foreach ($arr_server as $key_server => $value_server){
			
			$manager_server = $this->function_model->getManagerServer($key_server);
			
			//connect server old
			$sftp_config['hostname'] 	= $manager_server->server_name;
			$sftp_config['username'] 	= $manager_server->server_ftp_account;
			$sftp_config['password'] 	= $manager_server->server_ftp_pass;
			$sftp_config['port'] 		= $manager_server->server_port;
			$sftp_config['debug'] 		= TRUE;
			$this->sftp->connect($sftp_config);
				
			foreach($value_server as $_value_server){
				
				$file_name_original_img = $_value_server->file_name_original_img;
				$this->sftp->download($manager_server->server_path_upload_img."/".$file_name_original_img, DIR_ZIP.$file_name_original_img);
				array_push($file_names, $file_name_original_img);
				
			}
			
		}
		
		if(!empty($file_names)){
			$archive_file_name='list_images_compe_'.$id_competition.'.zip';
			$file_path=dirname(__FILE__).'/';
			$this->function_model->zipFilesAndDownload($file_names,$archive_file_name,DIR_ZIP);
			unlink($archive_file_name);
		}
		
		if(!empty($file_names)){
			foreach($file_names as $_file_names){
				unlink(DIR_ZIP.$_file_names);
			}
		}
		
		//unlink("/".$archive_file_name);

	}
	
	function download_file($id_img_code){
		
		$this->load->library('sftp');
		
		$decode = $this->function_model->decrypt_base64_dot($id_img_code);
		$id_img = $decode[0];
		
		if(is_numeric($id_img)){
			$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		}
		else{
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
			exit;
		}
		
		//connect server old
		$sftp_config['hostname'] 	= $data['detail_product']->server_name;
		$sftp_config['username'] 	= $data['detail_product']->server_ftp_account;
		$sftp_config['password'] 	= $data['detail_product']->server_ftp_pass;
		$sftp_config['port'] 		= $data['detail_product']->server_port;
		$sftp_config['debug'] 		= TRUE;
		$this->sftp->connect($sftp_config);
		
		$this->sftp->download($data['detail_product']->server_path_upload_img."/".$data['detail_product']->file_name_watermark_img, DIR_DOWNLOAD.$data['detail_product']->file_name_watermark_img);
		
		$dir = DIR_DOWNLOAD.$data['detail_product']->file_name_watermark_img;
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename='.basename($dir));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($dir));
		ob_clean();
		flush();
		readfile($dir);
		
		unlink(DIR_DOWNLOAD.$data['detail_product']->file_name_watermark_img);

	}
	
	function get_image_sell(){

		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
		
		$id_img = $_POST['id_img'];
		$user_id = $_POST['user_id'];
		
		//get detail user
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
		
		//get product img
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		
		if($data['detail_product']->user_id == $logged_in['user_id'] || $data['detail_user']->user_status != 1){
			echo 0;
			exit;
		}
		
		//get user get img sell
		$whereUserGetImgSell = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserGetImgSell = array();
		$data['user_get_img_sell'] = $this->main_model->getAllData("luxyart_tb_user_get_img_sell", $whereUserGetImgSell, $orderUserGetImgSell)->row();
		
		if(count($data['user_get_img_sell']) > 0){
			echo 2;
			exit;
		}
		
		$whereUserFilterImg = array('user_id' => $user_id);
		$orderUserFilterImg = array();
		$data['user_filter_img'] = $this->main_model->getAllData("luxyart_tb_user_filter_img", $whereUserFilterImg, $orderUserFilterImg)->row();
		
		if(count($data['user_filter_img']) > 0){
			
			$string_filter = $data['user_filter_img']->string_filter;
			if($string_filter != ""){
				$string_filter .= ",".$id_img;
			}
			else{
				$string_filter .= $id_img;
			}
			
			$arr_user_filter_img = explode(",",$string_filter);
			$arr_user_filter_img = array_unique($arr_user_filter_img);
			$value_string_filter = implode(",", $arr_user_filter_img);
				
			//update
			$dataUserFilterImg = array(
					'string_filter' 				=> $value_string_filter
			);
			$this->db->where('user_id', $user_id);
			$this->db->update('luxyart_tb_user_filter_img', $dataUserFilterImg);
			
			
		}
		else{
			
			$dataUserFilterImg = array(
				'user_id' 						=> $user_id,
				'string_filter' 				=> $id_img
			);
			$this->db->insert('luxyart_tb_user_filter_img', $dataUserFilterImg);
			
		}
		
		//add data
		$dataUserGetImgSell = array(
			'user_id' 		=> $user_id,
			'id_img' 		=> $id_img,
			'user_owner' 	=> $data['detail_product']->user_id,
			'date_get_img' 	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('luxyart_tb_user_get_img_sell', $dataUserGetImgSell);
		
		//send email person get
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "get_image_sell_for_person_get";
		$dataSendEmail["lang_id"] 				= $this->lang_id;
		$dataSendEmail["email_to"] 				= $data['detail_user']->user_email;
		$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
		$dataSendEmail["img_title"]				= $data['detail_product']->img_title;
		$dataSendEmail["img_width"]				= $data['detail_product']->img_width;
		$dataSendEmail["img_height"]			= $data['detail_product']->img_height;
		$dataSendEmail["link"] 					= base_url()."detail/".$data['detail_product']->img_code.".html";;
		//$dataSendEmail["is_debug"] 			= 1;
			
		$this->function_model->sendMailHTML($dataSendEmail);
		
		//send email person post
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "get_image_sell_for_person_post";
		$dataSendEmail["lang_id"] 				= $this->lang_id;
		$dataSendEmail["email_to"] 				= $data['detail_product']->user_email;
		$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_product']->user_id, $this->lang_id);
		$dataSendEmail["img_title"]				= $data['detail_product']->img_title;
		$dataSendEmail["img_width"]				= $data['detail_product']->img_width;
		$dataSendEmail["img_height"]			= $data['detail_product']->img_height;
		$dataSendEmail["link"] 					= base_url()."detail/".$data['detail_product']->img_code.".html";
		$dataSendEmail["email_get"] 			= $data['detail_user']->user_email;
		$dataSendEmail["name_get"] 				= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
		//$dataSendEmail["is_debug"] 			= 1;
			
		$this->function_model->sendMailHTML($dataSendEmail);
		
		//notice person get
		$addNotice = array();
		$addNotice['type'] 						= "get_image_sell_for_person_get";
		$addNotice["lang_id"] 					= $this->lang_id;
		$addNotice['user_id'] 					= $data['detail_user']->user_id;
		$addNotice['img_title'] 				= $data['detail_product']->img_title;
		$addNotice["link"] 						= base_url()."detail/".$data['detail_product']->img_code.".html";
		//$addNotice['is_debug'] 				= 1;
			
		$this->function_model->addNotice($addNotice);
		
		//notice person post
		$addNotice = array();
		$addNotice['type'] 						= "get_image_sell_for_person_post";
		$addNotice["lang_id"] 					= $this->lang_id;
		$addNotice['user_id'] 					= $data['detail_product']->user_id;
		$addNotice['img_title'] 				= $data['detail_product']->img_title;
		$addNotice["link"] 						= base_url()."detail/".$data['detail_product']->img_code.".html";
		$addNotice["email_get"] 				= $data['detail_user']->display_name;
		//$addNotice['is_debug'] 				= 1;
			
		$this->function_model->addNotice($addNotice);
		
		echo 1;
		exit;
		
	}
	
	function remove_image_sell(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo 0;
			exit;
		}
	
		//get detail user
		$logged_in = $this->function_model->get_logged_in();
	
		$id_img = $_POST['id_img'];
		$user_id = $_POST['user_id'];
	
		//get detail user
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
	
		//get product img
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
	
		if($data['detail_product']->user_id == $logged_in['user_id'] || $data['detail_user']->user_status != 1){
			echo 0;
			exit;
		}
	
		//get user get img sell
		$whereUserGetImgSell = array('id_img' => $id_img, 'user_id' => $user_id);
		$orderUserGetImgSell = array();
		$data['user_get_img_sell'] = $this->main_model->getAllData("luxyart_tb_user_get_img_sell", $whereUserGetImgSell, $orderUserGetImgSell)->row();
	
		if(count($data['user_get_img_sell']) > 1){
			echo 2;
			exit;
		}
		
		$this->db->where(array('id_img' => $id_img, 'user_id' => $user_id));
		$this->db->delete('luxyart_tb_user_get_img_sell');
	
		//send email person get
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "remove_image_sell_for_person_get";
		$dataSendEmail["lang_id"] 				= $this->lang_id;
		$dataSendEmail["email_to"] 				= $data['detail_user']->user_email;
		$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
		$dataSendEmail["img_title"]				= $data['detail_product']->img_title;
		$dataSendEmail["img_width"]				= $data['detail_product']->img_width;
		$dataSendEmail["img_height"]			= $data['detail_product']->img_height;
		$dataSendEmail["link"] 					= base_url()."detail/".$data['detail_product']->img_code.".html";
		//$dataSendEmail["is_debug"] 			= 1;
			
		$this->function_model->sendMailHTML($dataSendEmail);
	
		//send email person post
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "remove_image_sell_for_person_post";
		$dataSendEmail["lang_id"] 				= $this->lang_id;
		$dataSendEmail["email_to"] 				= $data['detail_product']->user_email;
		$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_product']->user_id, $this->lang_id);
		$dataSendEmail["img_title"]				= $data['detail_product']->img_title;
		$dataSendEmail["img_width"]				= $data['detail_product']->img_width;
		$dataSendEmail["img_height"]			= $data['detail_product']->img_height;
		$dataSendEmail["link"] 					= base_url()."detail/".$data['detail_product']->img_code.".html";
		$dataSendEmail["email_get"] 			= $data['detail_user']->user_email;
		$dataSendEmail["name_get"] 				= $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
		//$dataSendEmail["is_debug"] 			= 1;
			
		$this->function_model->sendMailHTML($dataSendEmail);
	
		//notice person get
		$addNotice = array();
		$addNotice['type'] 						= "remove_image_sell_for_person_get";
		$addNotice["lang_id"] 					= $this->lang_id;
		$addNotice['user_id'] 					= $data['detail_user']->user_id;
		$addNotice['img_title'] 				= $data['detail_product']->img_title;
		$addNotice['link'] 						= base_url()."detail/".$data['detail_product']->img_code.".html";
		//$addNotice['is_debug'] 				= 1;
			
		$this->function_model->addNotice($addNotice);
	
		//notice person post
		$addNotice = array();
		$addNotice['type'] 						= "remove_image_sell_for_person_post";
		$addNotice["lang_id"] 					= $this->lang_id;
		$addNotice['user_id'] 					= $data['detail_product']->user_id;
		$addNotice['img_title'] 				= $data['detail_product']->img_title;
		$addNotice['link'] 						= base_url()."detail/".$data['detail_product']->img_code.".html";
		$addNotice["email_get"] 				= $data['detail_user']->display_name;
		//$addNotice['is_debug'] 				= 1;
			
		$this->function_model->addNotice($addNotice);
		
		$whereUserFilterImg = array('user_id' => $user_id);
		$orderUserFilterImg = array();
		$data['user_filter_img'] = $this->main_model->getAllData("luxyart_tb_user_filter_img", $whereUserFilterImg, $orderUserFilterImg)->row();
		
		if(count($data['user_filter_img']) > 0){
			
			$arr_user_filter_img = explode(",",$data['user_filter_img']->string_filter);
			$arr_user_filter_img = $this->function_model->array_delete($id_img, $arr_user_filter_img);
			$value_string_filter = implode(",", $arr_user_filter_img);
			
			//update
			$dataUserFilterImg = array(
				'string_filter' 				=> $value_string_filter
			);
			$this->db->where('user_id', $user_id);
			$this->db->update('luxyart_tb_user_filter_img', $dataUserFilterImg);
			
		}
	
		echo 1;
		exit;
	
	}
	
	function report_image(){

		//check permission
		/*$is_permission = $this->function_model->check_permission(1);
		echo "is_permission = ".$is_permission."<br>";
		if(!$is_permission){
			echo 0;
			exit;
		}*/
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$id_img = $_POST['id_img'];
		$email = trim($_POST['email']);
		$full_name = trim($_POST['full_name']);
		$reason = $_POST['reason'];
		
		if($user_id != ""){
			//get detail user
			$whereUser = array('user_id' => $user_id);
			$orderUser = array();
			$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
			
			//get product img
			$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
			
			if($data['detail_product']->user_id == $user_id || $data['detail_user']->user_status != 1){
				echo 0;
				exit;
			}
		}
		else{
			//get product img
			$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		}
		
		//get report image
		$whereReportImage = array('img_id' => $id_img, 'email' => $email);
		$orderReportImage = array();
		$data['report_image'] = $this->main_model->getAllData("luxyart_tb_report_image", $whereReportImage, $orderReportImage)->result();
		
		if(count($data['report_image']) > 0){
			echo 2;
			exit;
		}
		
		//add data
		$dataReportImage = array(
				'img_id' 		=> $id_img,
				'email' 		=> $email,
				'name' 			=> $full_name,
				'type' 			=> $reason,
				'date_create' 	=> date('Y-m-d H:i:s'),
				'status' 		=> 0
		);
		$this->db->insert('luxyart_tb_report_image', $dataReportImage);
		
		if($reason == 1){
			$content_report = $this->lang->line('possibility_of_violation');
		}
		elseif($reason == 2){
			$content_report = $this->lang->line('this_is_other_ones_image');
		}
		elseif($reason == 3){
			$content_report = $this->lang->line('violent_image');
		}
		elseif($reason == 4){
			$content_report = $this->lang->line('porn_image');
		}
		
		//send email person get
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "report_image";
		$dataSendEmail["lang_id"] 				= $this->lang_id;
		$dataSendEmail["email_to"] 				= $data['detail_product']->user_email;
		$dataSendEmail["name"] 					= $this->function_model->get_fullname($data['detail_product']->user_id, $this->lang_id);
		$dataSendEmail["img_title"]				= $data['detail_product']->img_title;
		$dataSendEmail["img_width"]				= $data['detail_product']->img_width;
		$dataSendEmail["img_height"]			= $data['detail_product']->img_height;
		$dataSendEmail["link"] 					= base_url()."detail/".$data['detail_product']->img_code.".html";
		$dataSendEmail["email_report"] 			= $email;
		$dataSendEmail["name_report"] 			= $full_name;
		$dataSendEmail["content_report"] 		= $content_report;
		if($email != ""){
			$dataSendEmail["is_send_admin"] 	= 1;
		}
		else{
			$dataSendEmail["is_send_admin"] 	= 2;
		}
		//$dataSendEmail["is_debug"] 			= 1;
			
		$this->function_model->sendMailHTML($dataSendEmail);
		
		if($email != ""){
		
			//notice person get
			$addNotice = array();
			$addNotice['type'] 						= "report_image";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $data['detail_user']->user_id;
			$addNotice['img_title'] 				= $data['detail_product']->img_title;
			$addNotice['link'] 						= base_url()."detail/".$data['detail_product']->img_code.".html";;
			$addNotice['email_report'] 				= $email;
			//$addNotice['is_debug'] 				= 1;
				
			$this->function_model->addNotice($addNotice);
		
		}
		
		echo 1;
		exit;
		
	}
	
	function add_user_timeline(){

		$id_img = $_POST['id_img'];
		$user_id_get_img = $_POST['user_id_get_img'];
		
		//get detail user
		$whereUser = array('user_id' => $user_id_get_img);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->result();

		if(count($data['detail_user']) == 1){
			
			$sess_data = array(
				'user_id_get_img' 	=> $user_id_get_img,
				'id_img' 			=> $id_img,
			);
			$this->session->set_userdata('get_img', $sess_data);
			
		}
		
		$data['detail_product'] = $this->manager_image_model->get_detail_image($id_img)->row();
		
		echo $data['detail_product']->img_code;
		exit;

	}
	
	function drag_and_drop(){

		$str_image_list = $_POST['str_image_list'];
		$page_now = $_POST['page_now'];
		$user_id = $_POST['user_id'];
		
		if(empty($str_image_list)){
			echo 0;
			exit;
		}
		
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->result();
		
		if(count($data['detail_user']) == 0){
			echo 0;
			exit;
		}
		
		$whereUserFilterImg = array('user_id' => $user_id);
		$orderUserFilterImg = array();
		$data['user_filter_img'] = $this->main_model->getAllData("luxyart_tb_user_filter_img", $whereUserFilterImg, $orderUserFilterImg)->row();
		
		if(count($data['user_filter_img']) == 0){
			
			$str_image_list = str_replace("li_","",$str_image_list);
			
			$arr_image_list = explode(",", $str_image_list);
			
			$arr_image_list = array_unique($arr_image_list);
			
			$value_string_filter = implode(",", $arr_image_list);
			
			$dataUserFilterImg = array(
				'user_id' 						=> $user_id,
				'string_filter' 				=> $value_string_filter
			);
				
			$this->db->insert('luxyart_tb_user_filter_img', $dataUserFilterImg);

		}
		else{

			$string_filter = $data['user_filter_img']->string_filter;
			$str_image_list = str_replace("li_","",$str_image_list);
			
			$arr_string_filter = explode(",", $string_filter);
			$arr_image_list = explode(",", $str_image_list);
			
			$arr_string_filter = array_unique($arr_string_filter);
			$arr_image_list = array_unique($arr_image_list);
			
			if(count($arr_string_filter) <= count($arr_image_list)){
				
				$value_image_list = implode(",", $arr_image_list);
				
				//update
				$dataUserFilterImg = array(
					'string_filter' 				=> $value_image_list
				);
				$this->db->where('user_id', $user_id);
				$this->db->update('luxyart_tb_user_filter_img', $dataUserFilterImg);
			}
			else{
				//replace to page
				for($i=0; $i<count($arr_image_list); $i++){
					$arr_string_filter[$i] = $arr_image_list[$i];
				}
				$value_string_filter = implode(",", $arr_string_filter);
				//update
				$dataUserFilterImg = array(
					'string_filter' 				=> $value_string_filter
				);
				$this->db->where('user_id', $user_id);
				$this->db->update('luxyart_tb_user_filter_img', $dataUserFilterImg);
				
			}	
			
		}
		
		exit;

	}
	
	function check_point_post_competition(){

		$total_com = $_POST['total_com'];
		$user_id = $_POST['user_id'];
		
		//get detail user
		$whereUser = array('user_id' => $user_id);
		$orderUser = array();
		$data['detail_user'] = $this->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
		
		if(count($data['detail_user']) == 1){
			
			$point = $data['detail_user']->user_point + $data['detail_user']->user_paymoney_getpoint;
			if($total_com > $point){
				$msg = $this->lang->line('please_purchase_credit');
				$msg = str_replace("{user_point}", $point, $msg);
				echo "0{luxyart}".$msg;				
			}
			else{
				echo 1;
			}
		}
		else{
			echo -1;
		}
		
		exit;

	}
	
	function add_comment(){
	
		$arr_users = array();
	
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
	
		$comment = $_POST["comment"] ? $_POST["comment"] : "";
		$id_img = $_POST["id_img"] ? $_POST["id_img"] : "";
		$comment_id = $_POST["comment_id"] ? $_POST["comment_id"] : "";
		$level = $_POST["level"] ? $_POST["level"] : "";
	
		$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
		$arr_users[0]["user_id"] = $detail_product->user_id."$$$".$detail_product->user_email."$$$".$detail_product->display_name;
	
		if($comment_id > 0){
				
			$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
			$arr_users[1]["user_id"] = $detail_comment->user_id."$$$".$detail_comment->user_email."$$$".$detail_comment->display_name;
				
			if($detail_comment->level == 2){
	
				$detail_comment_root = $this->manager_comment_model->get_detail_comment($detail_comment->comment_id_parent)->row();
				$arr_users[2]["user_id"]  = $detail_comment_root->user_id."$$$".$detail_comment_root->user_email."$$$".$detail_comment_root->display_name;
	
			}
	
		}
	
		if($detail_comment->level == 2){
			$dataInsertComment = array(
					'id_img' 			=> $id_img,
					'user_id' 			=> $user_id,
					'level' 			=> $level,
					'comment_id_parent' => $detail_comment_root->comment_id,
					'comment' 			=> $comment,
					'date_create' 		=> date('Y-m-d H:i:s'),
					'status' 			=> 0,
			);
		}
		else{
			$dataInsertComment = array(
					'id_img' 			=> $id_img,
					'user_id' 			=> $user_id,
					'level' 			=> $level,
					'comment_id_parent' => $comment_id,
					'comment' 			=> $comment,
					'date_create' 		=> date('Y-m-d H:i:s'),
					'status' 			=> 0,
			);
		}
		$this->db->insert('luxyart_tb_comment', $dataInsertComment);
	
		$arr_users = array_unique($arr_users, SORT_REGULAR);
	
		//info send email
		if($detail_product->option_sale == 1){
			$str_option_sale			= "24 ".$this->lang->line('hour');
		}
		elseif($detail_product->option_sale == 2){
			$str_option_sale			= "1 ".$this->lang->line('week');
		}
		elseif($detail_product->option_sale == 3){
			$str_option_sale			= "1 ".$this->lang->line('month');
		}
		else{
			$str_option_sale			= $this->lang->line('limited_of_time_expired');
		}
	
		if($detail_product->option_img_watermark == 1){
			$str_type_export_image		= $this->lang->line('blur');
		}
		elseif($detail_product->option_img_watermark == 2){
			$str_type_export_image		= $this->lang->line('watermark');
		}
	
		foreach($arr_users as $_arr_users){
				
			$str_user = $_arr_users["user_id"];
			$str_users = explode("$$$",$str_user);
				
			$user_id = $str_users[0];
			$email_to = $str_users[1];
			$name = $str_users[2];
				
			//send email
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "post_comment";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $email_to;
			$dataSendEmail["name"] 					= ucwords($name);
			$dataSendEmail["img_title"]				= $detail_product->img_title;
			$dataSendEmail["img_width"]				= $detail_product->img_width;
			$dataSendEmail["img_height"]			= $detail_product->img_height;
			$dataSendEmail["option_sale"]			= $str_option_sale;
			$dataSendEmail["type_export_image"]		= $str_type_export_image;
			$dataSendEmail["link"]					= base_url()."detail/".$detail_product->img_code.".html";
			$dataSendEmail["comment"]				= $comment;
			$dataSendEmail["email_post_comment"]	= $user->user_email;
			$dataSendEmail["name_post_comment"]		= $user->display_name;
			//$dataSendEmail["is_send_admin"]		= 1;
			//$dataSendEmail["is_debug"]			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
				
		}
	
		echo 1;
		exit;
	
	}
	
	function set_status_comment(){
	
		$arr_users = array();
	
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
	
		$comment_id = $_POST["comment_id"] ? $_POST["comment_id"] : "";
		$level = $_POST["level"] ? $_POST["level"] : "";
		$status = $_POST["status"] ? $_POST["status"] : "";
		$id_img = $_POST["id_img"] ? $_POST["id_img"] : "";
	
		$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
		$arr_users[0]["user_id"] = $detail_product->user_id."$$$".$detail_product->user_email."$$$".$detail_product->display_name;
	
		if($comment_id > 0){
				
			$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
			$arr_users[1]["user_id"] = $detail_comment->user_id."$$$".$detail_comment->user_email."$$$".$detail_comment->display_name;
				
			$comment = $detail_comment->comment;
				
			if($detail_comment->level == 2){
	
				$detail_comment_root = $this->manager_comment_model->get_detail_comment($detail_comment->comment_id_parent)->row();
				$arr_users[2]["user_id"]  = $detail_comment_root->user_id."$$$".$detail_comment_root->user_email."$$$".$detail_comment_root->display_name;
	
			}
	
		}
	
		$arr_users = array_unique($arr_users, SORT_REGULAR);
	
		//info send email
		if($detail_product->option_sale == 1){
			$str_option_sale			= "24 ".$this->lang->line('hour');
		}
		elseif($detail_product->option_sale == 2){
			$str_option_sale			= "1 ".$this->lang->line('week');
		}
		elseif($detail_product->option_sale == 3){
			$str_option_sale			= "1 ".$this->lang->line('month');
		}
		else{
			$str_option_sale			= $this->lang->line('limited_of_time_expired');
		}
	
		if($detail_product->option_img_watermark == 1){
			$str_type_export_image		= $this->lang->line('blur');
		}
		elseif($detail_product->option_img_watermark == 2){
			$str_type_export_image		= $this->lang->line('watermark');
		}
	
		foreach($arr_users as $_arr_users){
				
			$str_user = $_arr_users["user_id"];
			$str_users = explode("$$$",$str_user);
				
			$user_id = $str_users[0];
			$email_to = $str_users[1];
			$name = $str_users[2];
				
			//send email
			$dataSendEmail = array();
			if($status == 0){
				$dataSendEmail["name_email_code"] 		= "active_comment";
			}
			else{
				$dataSendEmail["name_email_code"] 		= "not_active_comment";
			}
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $email_to;
			$dataSendEmail["name"] 					= ucwords($name);
			$dataSendEmail["img_title"]				= $detail_product->img_title;
			$dataSendEmail["img_width"]				= $detail_product->img_width;
			$dataSendEmail["img_height"]			= $detail_product->img_height;
			$dataSendEmail["option_sale"]			= $str_option_sale;
			$dataSendEmail["type_export_image"]		= $str_type_export_image;
			$dataSendEmail["link"]					= base_url()."detail/".$detail_product->img_code.".html";
			$dataSendEmail["comment"]				= $comment;
			$dataSendEmail["email_post_comment"]	= $user->user_email;
			$dataSendEmail["name_post_comment"]		= $user->display_name;
			//$dataSendEmail["is_send_admin"]		= 1;
			//$dataSendEmail["is_debug"]			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
				
		}
		
		$this->db->where('comment_id', $comment_id);
		if($status == 1){
			$this->db->update('luxyart_tb_comment',array('status' => 0));
		}
		else{
			$this->db->update('luxyart_tb_comment',array('status' => 1));
		}
	
		echo 1;
		exit;
	
	}
	
	function add_member(){
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
		
		$list_email = $_POST["list_email"] ? $_POST["list_email"] : "";
		$userid = $_POST["userid"] ? $_POST["userid"] : "";
		
		$count_invite = 0;
		
		if($user_id == $userid){
			
			if($list_email != ""){
				
				$list_email = explode(",",$list_email);
				for($i=0;$i<count($list_email);$i++){
					
					$email = $list_email[$i];
					$email = str_replace('"', "", $email);
					$email = str_replace('[', "", $email);
					$email = str_replace(']', "", $email);
					$email = trim($email);
					
					$this->db->from('luxyart_tb_member');
					$this->db->select('*');
					$this->db->where(array('user_id'=>$user_id,'email_invite'=>$email));
						
					$rsMember = $this->db->get();
					$countMember = $rsMember->num_rows();
					
					if($countMember == 0){//mời lần đầu tiên
						
						$dataInsertMember = array(
							'user_id' 					=> $user_id,
							'email_invite' 				=> $email,
							'user_code_invite_person'	=> $user->user_macode,
							'date_create' 				=> date('Y-m-d H:i:s'),
							'count' 					=> 1,
							'status'					=> 0
						);
						$this->db->insert('luxyart_tb_member', $dataInsertMember);
						$member_id = $this->db->insert_id();
						
						$dataSendEmail = array();
						$dataSendEmail["name_email_code"] 		= "add_member";
						$dataSendEmail["lang_id"] 				= $this->lang_id;
						$dataSendEmail["email_to"] 				= $email;
						$dataSendEmail["name"] 					= ucwords($email);
						$dataSendEmail["email"] 				= $email;
						$dataSendEmail["email_invite"] 			= $user->user_email;
						$dataSendEmail["name_invite"] 			= $user->display_name;
						$dataSendEmail["link_invite"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
						$dataSendEmail["link_accept"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
						$dataSendEmail["link_not_accept"] 		= base_url()."not_accept_member/".$member_id."-".md5($user_id).".html";
						
						//$dataSendEmail["is_debug"]			= 1;
							
						$this->function_model->sendMailHTML($dataSendEmail);
						
						$count_invite++;
						
					}
					else{
						
						$resultMember = $rsMember->row();
						$member_id = $resultMember->member_id;
						$status = $resultMember->status;
						
						if($status == 0 || $status == 1){//mời + từ chối => mời lại

							$this->db->where(array('user_id'=>$user_id,'email_invite'=>$email));
							$this->db->set('status','0');
							$this->db->set('count','count+1',FALSE);
							$this->db->update('luxyart_tb_member');
							
							$dataSendEmail = array();
							$dataSendEmail["name_email_code"] 		= "add_member";
							$dataSendEmail["lang_id"] 				= $this->lang_id;
							$dataSendEmail["email_to"] 				= $email;
							$dataSendEmail["name"] 					= ucwords($email);
							$dataSendEmail["email"] 				= $email;
							$dataSendEmail["email_invite"] 			= $user->user_email;
							$dataSendEmail["name_invite"] 			= $user->display_name;
							$dataSendEmail["link_invite"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
							$dataSendEmail["link_accept"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
							$dataSendEmail["link_not_accept"] 		= base_url()."not_accept_member/".$member_id."-".md5($user_id).".html";
							//$dataSendEmail["is_debug"]			= 1;
								
							$this->function_model->sendMailHTML($dataSendEmail);
							
							$count_invite++;
							
						}
						else if($status == 2){//đồng ý => không mời
							//khong xu ly
						}
						
					}
					
				}
				
				if($count_invite > 0){
					echo 1;
				}
				else{
					echo 0;
				}
				
			}
			else{
				echo 0;
			}
			
		}
		else{
			
			echo 0;
			
		}
		
		exit;
		
	}
	
	function insert_member(){
	
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
	
		$email_invite = $_POST["email_invite"] ? $_POST["email_invite"] : "";
		$userid = $_POST["userid"] ? $_POST["userid"] : "";
	
		$count_invite = 0;
	
		if($user_id == $userid){
				
			if($email_invite != ""){
				
				$email = trim($email_invite);
				
				$this->db->from('luxyart_tb_member');
				$this->db->select('*');
				$this->db->where(array('user_id'=>$user_id,'email_invite'=>$email));
				
				$rsMember = $this->db->get();
				$countMember = $rsMember->num_rows();
				
				if($countMember == 0){//mời lần đầu tiên
				
					$dataInsertMember = array(
							'user_id' 					=> $user_id,
							'email_invite' 				=> $email,
							'user_code_invite_person'	=> $user->user_macode,
							'date_create' 				=> date('Y-m-d H:i:s'),
							'count' 					=> 1,
							'status'					=> 0
					);
					$this->db->insert('luxyart_tb_member', $dataInsertMember);
					$member_id = $this->db->insert_id();
				
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "add_member";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $email;
					$dataSendEmail["name"] 					= ucwords($email);
					$dataSendEmail["email"] 				= $email;
					$dataSendEmail["email_invite"] 			= $user->user_email;
					$dataSendEmail["name_invite"] 			= $user->display_name;
					$dataSendEmail["link_invite"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
					$dataSendEmail["link_accept"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
					$dataSendEmail["link_not_accept"] 		= base_url()."not_accept_member/".$member_id."-".md5($user_id).".html";
					//$dataSendEmail["is_debug"]			= 1;
						
					$this->function_model->sendMailHTML($dataSendEmail);
				
					echo 1;
					exit;
				
				}
				else{
				
					$resultMember = $rsMember->row();
					$member_id = $resultMember->member_id;
					$status = $resultMember->status;
				
					if($status == 0 || $status == 1){//mời + từ chối => mời lại
				
						$this->db->where(array('user_id'=>$user_id,'email_invite'=>$email));
						$this->db->set('status','0');
						$this->db->set('count','count+1',FALSE);
						$this->db->update('luxyart_tb_member');
				
						$dataSendEmail = array();
						$dataSendEmail["name_email_code"] 		= "add_member";
						$dataSendEmail["lang_id"] 				= $this->lang_id;
						$dataSendEmail["email_to"] 				= $email;
						$dataSendEmail["name"] 					= ucwords($email);
						$dataSendEmail["email"] 				= $email;
						$dataSendEmail["email_invite"] 			= $user->user_email;
						$dataSendEmail["name_invite"] 			= $user->display_name;
						$dataSendEmail["link_invite"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
						$dataSendEmail["link_accept"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
						$dataSendEmail["link_not_accept"] 		= base_url()."not_accept_member/".$member_id."-".md5($user_id).".html";
						//$dataSendEmail["is_debug"]			= 1;
				
						$this->function_model->sendMailHTML($dataSendEmail);
				
						echo 1;
				
					}
					else if($status == 2){//đồng ý => không mời
						//khong xu ly
						echo 0;
					}
				
				}
	
			}
			else{
				echo 0;
			}
				
		}
		else{
				
			echo 0;
				
		}
	
		exit;
	
	}
	
	function accept_member(){
		
		session_start();
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
	
		$member_id = $_POST["member_id"] ? $_POST["member_id"] : "";
		$userid = $_POST["userid"] ? $_POST["userid"] : "";
	
		$count_invite = 0;
	
		if($member_id > 0){
			
			$this->db->from('luxyart_tb_member');
			$this->db->select('*');
			$this->db->where(array('member_id'=>$member_id));
			
			$rsMember = $this->db->get();
			$resultMember = $rsMember->row();
			$email_invite = $resultMember->email_invite;
			
			$this->db->from('luxyart_tb_user');
			$this->db->select('*');
			$this->db->where(array('user_email'=>$email_invite));
				
			$rsUserInvited = $this->db->get();
			$resultUserInvited = $rsUserInvited->row();
			$user_id_invited = $resultUserInvited->user_id;
			
			$this->db->where('member_id', $member_id);
			$this->db->set('user_id_invited',$user_id_invited);
			$this->db->set('date_invite',date("Y-m-d H:i:s"));
			$this->db->set('status',2);
			$this->db->update('luxyart_tb_member');
			
			$this->db->from('luxyart_tb_user');
			$this->db->select('*');
			$this->db->where(array('user_id'=>$resultMember->user_id));
			
			$rsUserSend = $this->db->get();
			$resultUserSend = $rsUserSend->row();
			
			//set login
			$sess_data = array(
				'user_id' 			=> $resultUserInvited->user_id,
				'user_email' 		=> $resultUserInvited->user_email,
				'user_pass' 		=> $resultUserInvited->user_pass,
				'user_display_name' => $resultUserInvited->display_name,
				'user_firstname' 	=> $resultUserInvited->user_firstname,
				'user_lastname' 	=> $resultUserInvited->user_lastname,
				'user_level' 		=> $resultUserInvited->user_level
			);
			$this->session->set_userdata('logged_in', $sess_data);
			
			//send email
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "accept_member";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $resultUserSend->user_email;
			$dataSendEmail["name"] 					= ucwords($resultUserSend->display_name);
			$dataSendEmail["email_invite"] 			= $resultMember->email_invite;
			$dataSendEmail["link_member"] 			= base_url()."mypage/listmember";
			//$dataSendEmail["is_debug"]			= 1;
			
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//get meta seo
			$_SESSION['return_key'] = 'user/accept_member_success';
			
			echo 1;
			exit;
			
		}
		else{
			
			echo 0;
			exit;
			
		}
		
	}
	
	function accept_invite_member(){
	
		session_start();
	
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
	
		$member_id = $_POST["member_id"] ? $_POST["member_id"] : "";
		$userid = $_POST["userid"] ? $_POST["userid"] : "";
	
		$count_invite = 0;
	
		if($member_id > 0){
			
			$this->db->from('luxyart_tb_member');
			$this->db->select('*');
			$this->db->where(array('user_id'=>$userid,'user_id_invited'=>$member_id));
			$rsMember = $this->db->get();
			$countMember = $rsMember->num_rows();
			
			if($countMember > 0){
				echo 4;
				exit;
			}
			
			$this->db->from('luxyart_tb_member');
			$this->db->select('*');
			$this->db->where(array('user_id_invited'=>$userid,'status'=>2));
			$rsMember = $this->db->get();
			$countMember = $rsMember->num_rows();
			
			//user
			if($countMember > 0){
				$resultMember = $rsMember->row();
				
				if($member_id == $resultMember->user_id){
					echo 2;
				}
				else{
					echo 3;
				}
				exit;
			}
			else{
				
				$this->db->from('luxyart_tb_user');
				$this->db->select('*');
				$this->db->where(array('user_id'=>$member_id));
				
				$rsUserMemberId = $this->db->get();
				$resultUserMemberId = $rsUserMemberId->row();
				
				$this->db->from('luxyart_tb_user');
				$this->db->select('*');
				$this->db->where(array('user_id'=>$userid));
				
				$rsUserId = $this->db->get();
				$resultUserId = $rsUserId->row();
				
				$email_invite = $resultUserId->user_email;
				$user_macode = $resultUserMemberId->user_macode;
				
				$dataInsertMember = array(
					'user_id' 					=> $member_id,
					'user_id_invited' 			=> $userid,
					'email_invite' 				=> $email_invite,
					'user_code_invite_person' 	=> $user_macode,
					'count' 					=> 1,
					'date_create'		 		=> date('Y-m-d H:i:s'),
					'date_invite' 				=> date('Y-m-d H:i:s'),
					'status' 					=> 2,
				);
				
				$this->db->insert('luxyart_tb_member', $dataInsertMember);
				
				//send email
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "accept_member";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $resultUserMemberId->user_email;
				$dataSendEmail["name"] 					= ucwords($resultUserMemberId->display_name);
				$dataSendEmail["email_invite"] 			= $resultUserId->user_email;
				$dataSendEmail["link_member"] 			= base_url()."mypage/listmember";
				//$dataSendEmail["is_debug"]			= 1;
				
				$this->function_model->sendMailHTML($dataSendEmail);
				
				//get meta seo
				$_SESSION['return_key'] = 'user/accept_member_success';
				
				echo 1;
				exit;
				
			}
				
		}
		else{
				
			echo 0;
			exit;
				
		}
	
	}
	
	function set_member_id(){
		
		session_start();
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
		
		$member_id = $_POST["member_id"] ? $_POST["member_id"] : "";
		$userid = $_POST["userid"] ? $_POST["userid"] : "";
		
		if($member_id > 0){
			
			$this->db->from('luxyart_tb_member');
			$this->db->select('*');
			$this->db->where(array('member_id'=>$member_id));
			
			$rsMember = $this->db->get();
			$resultMember = $rsMember->row();
			$email_invite = $resultMember->email_invite;
			
			$_SESSION['member_id'] = $member_id;
			$_SESSION['email_invite'] = $email_invite;
			
			echo 1;
			exit;
			
		}
		else{
			
			echo 0;
			exit;
			
		}
		
	}

	function delete_member(){
	
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
	
		$member_id = $_POST["member_id"] ? $_POST["member_id"] : "";
		$userid = $_POST["userid"] ? $_POST["userid"] : "";
	
		if($user_id == $userid){
	
			if($member_id > 0){
				
				$this->db->from('luxyart_tb_member');
				$this->db->select('*');
				$this->db->where(array('member_id'=>$member_id));
					
				$rsMember = $this->db->get();
				$resultMember = $rsMember->row();
				$user_id_invited = $resultMember->user_id_invited;
					
				$this->db->from('luxyart_tb_user');
				$this->db->select('*');
				$this->db->where(array('user_id'=>$user_id_invited));
				
				$rsUserInvited = $this->db->get();
				$resultUserInvited = $rsUserInvited->row();
	
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "delete_member";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $resultUserInvited->user_email;
				$dataSendEmail["name"] 					= ucwords($user->display_name);
				$dataSendEmail["name_invite"] 			= ucwords($resultUserInvited->display_name);
				$dataSendEmail["email"] 				= $resultUserInvited->user_email;
				//$dataSendEmail["is_debug"]			= 1;
				
				$this->function_model->sendMailHTML($dataSendEmail);
				
				$this->db->where(array('member_id'=>$member_id));
				$this->db->delete('luxyart_tb_member');
				
				echo 1;
	
			}
			else{
				echo 0;
			}
	
		}
		else{
	
			echo 0;
	
		}
	
		exit;
	
	}
	
	function check_email_exist(){
		
		$email_check = isset($_POST["email_check"]) ? $_POST["email_check"] : "";
		
		$this->db->from('luxyart_tb_user');
		$this->db->select('user_id');
		$this->db->where(array('user_email'=>$email_check));
		
		$rsUser = $this->db->get();
		$countUser = $rsUser->num_rows();
		
		$this->db->from('luxyart_tb_member');
		$this->db->select('*');
		$this->db->where(array('email_invite'=>$email_check));
		
		$rsMember = $this->db->get();
		$countMember = $rsMember->num_rows();
			
		if($countUser == 1){//email ton tai roi khong duoc add
			if($countMember == 1){
				$resultMember = $rsMember->row();
				
				$status = $resultMember->status;
				
				if($status == 0 || $status == 1){
					echo 0;
					exit;
				}
				else{
					echo 1;
					exit;
				}
			}
			else{
				
				if($countMember == 0){
					echo 0;
				}
				else{
					echo 1;
				}
				
				exit;
			}
		}
		else{//email chua ton tai
			echo 0;
			exit;
		}
		
	}
	
	function test_send_email(){
		
		//send email
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "edit_competition";
		$dataSendEmail["lang_id"] 				= 2;
		$dataSendEmail["email_to"] 				= "fermatandrew111@gmail.com";
		$dataSendEmail["name"] 					= ucwords("Dư Thị Thu Dung");
		$dataSendEmail["title_com"]				= "title_com";
		$dataSendEmail["date_start_com"]		= "2017-11-21";
		$dataSendEmail["date_end_com"]			= "2017-12-12";
		$dataSendEmail["point_img_com"]			= 5;
		$dataSendEmail["img_quantity_com"]		= 2;
		$dataSendEmail["link_url_com"]			= "https://viecoi.vn";
		$dataSendEmail["link"]					= base_url()."mypage/list-compe.html";
		$dataSendEmail["is_admin"]				= 1;
		//$dataSendEmail["is_debug"]			= 1;
		
		$this->function_model->sendMailHTML($dataSendEmail);

	}
	
	function test_get_fullname(){
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo $this->function_model->get_fullname(2, $this->lang_id);
		
	}
	
	function fix_image_link(){

		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('id_img > ', 0);
		$rs = $this->db->get();
		$result = $rs->result();
		
		foreach($result as $_result){
			
			$id_img = $_result->id_img;

			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_product_img',array('img_link' => base_url()."detail/".$id_img.".html"));

		}
		
		echo "fix image link finish";

	}
	
	function fix_image_date(){
		
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
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('id_img < ', 906);
		$rs = $this->db->get();
		$result = $rs->result();
	
		foreach($result as $_result){
				
			$id_img = $_result->id_img;
			
			$date_add_img = $this->function_model->randomDate($data['date_begin_random_image'],$data['date_end_random_image']);
	
			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_product_img',array('date_add_img' => $date_add_img));
			
			echo "fix image date_add_img finish = ".$id_img;
	
		}
	
		echo "fix image link finish";
	
	}
	
	function fix_img_code(){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('id_img > ', 0);
		$rs = $this->db->get();
		$result = $rs->result();
	
		foreach($result as $_result){
				
			$id_img = $_result->id_img;
			
			$img_code						= $this->function_model->get_code_image();
	
			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_product_img',array('img_code' => $img_code));
	
		}
	
		echo "fix image link finish";
	
	}
	
	function get_code_image(){

		echo $code_image = $this->function_model->get_code_image();

	}
	
	function fix_user_macode(){
	
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where('display_name', "");
		$rs = $this->db->get();
		$result = $rs->result();
	
		foreach($result as $_result){
			
			$user_id_gancode = "";
	
			$user_id = $_result->user_id;
	
			$bangkytuset_ID = '1234567890';
			$code_repeat_1_ID = 1;
			$code_repeat_2_ID = 10;
			$dodaichuoicode_ID = 10;
			$user_id_gancode = substr(str_shuffle(str_repeat($bangkytuset_ID, mt_rand($code_repeat_1_ID,$code_repeat_2_ID))),0,$dodaichuoicode_ID);
			
			
			
			$os_register = 3;
			if($this->lang_id==1)
			{
				$state_id = 1;
				$user_id_gancode = 'JA'.$user_id_gancode;
			}
			if($this->lang_id==2)
			{
				$state_id = 5;
				$user_id_gancode = 'VI'.$user_id_gancode;
			}
			if($this->lang_id==3)
			{
				$state_id = 3;
				$user_id_gancode = 'EN'.$user_id_gancode;
			}
			
			
			
			$this->db->where('user_id', $user_id);
			$this->db->update('luxyart_tb_user',array('user_macode' => $user_id_gancode));
	
		}
	
		echo "fix user macode finish";
	
	}
	
	function fix_display_name(){
	
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where('display_name', "");
		$rs = $this->db->get();
		$result = $rs->result();
	
		foreach($result as $_result){
				
			$display_name = "";
			
			$user_id = $_result->user_id;
			$user_firstname = $_result->user_firstname;
			$user_lastname = $_result->user_lastname;
			$user_email = $_result->user_email;
	
			if($user_firstname != "" && $user_lastname != ""){
				$display_name = $user_firstname." ".$user_lastname;
			} 
			else if($user_firstname != "" && $user_lastname == ""){
				$display_name = $user_firstname;
			}
			else if($user_firstname == "" && $user_lastname != ""){
				$display_name = $user_lastname;
			}
			else if($user_firstname == "" && $user_lastname == ""){
				$user_email_explode = explode("@",$user_email);
				$display_name = $user_email_explode[0];
			}
				
			$this->db->where('user_id', $user_id);
			$this->db->update('luxyart_tb_user',array('display_name' => $display_name));
	
		}
	
		echo "fix user macode finish";
	
	}
	
	function load_type_size(){
	
		$type_size_id = $_POST["type_size_id"];
		
		$data['add_cart'] = $this->shopping_model->get_data_add_cart($type_size_id)->row();
		
		if($data['add_cart']->is_limit == 1){
			$str = "<strong>".$this->lang->line('stock')."</strong>: ".$data['add_cart']->size_stock."<br />";
		}
		else{
			$str = "<strong>".$this->lang->line('stock')."</strong>: ".$this->lang->line('limited_of_out_stock')."<br />";
		}
		
        $str .= "<strong>".$this->lang->line('format')."</strong>: ".$data['add_cart']->img_type."<br />";
        $str .= "<strong>".$this->lang->line('price')."</strong>: ".$data['add_cart']->price_size_point." ".$this->lang->line('point');
        
        echo $str;
	
		exit;
	
	}
	
	function fix_filter_img(){

		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where(array('user_id > ' => 0));

		$rsUser = $this->db->get();
		$resultUser = $rsUser->result();
	
		foreach($resultUser as $_resultUser){

			$user_id = $_resultUser->user_id;
			
			$this->db->from('luxyart_tb_product_img');
			$this->db->select('*');
			$this->db->where(array('user_id' => $user_id));
			$this->db->order_by("id_img","desc");
			
			$rsProductImg = $this->db->get();
			$resultProductImg = $rsProductImg->result();
			
			$arr_id_img = array();
			
			foreach($resultProductImg as $_resultProductImg){
				
				$id_img = $_resultProductImg->id_img;
				array_push($arr_id_img, $id_img);
				
			}
			
			$str_id_img = implode(",", $arr_id_img);
			
			if($str_id_img != ""){
				$this->db->insert('luxyart_tb_user_filter_img',array('user_id'=>$user_id,'string_filter'=>$str_id_img));
			}
			
			echo "fix...".$user_id."<br>";
			echo "------------------------<br>";

		}
		
		echo "finish...";
		
		exit;

	}
	
	public function fix_image_exist(){
		
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where(array('id_img >'=>0));
		$this->db->limit(5);
		
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->result();
		
		foreach($resultProductImg as $_resultProductImg){
		
			$id_img = $_resultProductImg->id_img;
			$id_server = $_resultProductImg->id_server;
			$file_name_original_img = $_resultProductImg->file_name_original_img;
			$file_name_watermark_img = $_resultProductImg->file_name_watermark_img;
			$file_name_watermark_img_1 = $_resultProductImg->file_name_watermark_img_1;
			
			echo "id_img = ".$id_img."<br>";
			
			$this->db->from('luxyart_tb_manager_server');
			$this->db->select('*');
			$this->db->where(array('id_server'=>$id_server));
			
			$rsProductImg = $this->db->get();
			$resultProductImg = $rsProductImg->row();
			
			$server_path_upload = $resultProductImg->server_path_upload;
			
			$dir_file_name_original_img = $server_path_upload."/".$file_name_original_img;
			$dir_file_name_watermark_img = $server_path_upload."/".$file_name_watermark_img;
			$dir_file_name_watermark_img_1 = $server_path_upload."/".$file_name_watermark_img_1;
			
			echo "<a target='_blank' href='".$dir_file_name_original_img."' >".$dir_file_name_original_img."</a><br>";
			
			echo "check file = ".file_exists($dir_file_name_original_img)."<br>";
			
			/*if(!$this->check_img_exists($dir_file_name_original_img) || !$this->check_img_exists($dir_file_name_watermark_img) || !$this->check_img_exists($dir_file_name_watermark_img_1)) {
				
				$this->db->where('id_img', $id_img);
				$this->db->update('luxyart_tb_product_img',array('img_check_status' => 4, 'is_fix' => 1));
				
				echo "fix image exist: ".$id_img."<br>";
				
			}*/
			
			/*if(!$this->check_img_exists($dir_file_name_original_img)) {
			
				//$this->db->where('id_img', $id_img);
				//$this->db->update('luxyart_tb_product_img',array('img_check_status' => 4, 'is_fix' => 1));
			
				echo "fix image exist: ".$id_img."<br>";
			
			}*/

			echo "--------------------------------------<br>";
			
		}
		
		//header("Refresh:0");
		
	}
	
	public function fix_image_rotate(){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where(array('img_check_status != '=>4));
		//$this->db->limit(5);
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->result();
	
		foreach($resultProductImg as $_resultProductImg){
	
			$id_img = $_resultProductImg->id_img;
			$id_server = $_resultProductImg->id_server;
			$file_name_original_img = $_resultProductImg->file_name_original_img;
			$file_name_watermark_img = $_resultProductImg->file_name_watermark_img;
			$file_name_watermark_img_1 = $_resultProductImg->file_name_watermark_img_1;
				
			echo "id_img = ".$id_img."<br>";
				
			$this->db->from('luxyart_tb_manager_server');
			$this->db->select('*');
			$this->db->where(array('id_server'=>$id_server));
				
			$rsProductImg = $this->db->get();
			$resultProductImg = $rsProductImg->row();
				
			$server_path_upload = $resultProductImg->server_path_upload;
				
			$dir_file_name_original_img = $server_path_upload."/".$file_name_original_img;
			$dir_file_name_watermark_img = $server_path_upload."/".$file_name_watermark_img;
			$dir_file_name_watermark_img_1 = $server_path_upload."/".$file_name_watermark_img_1;
				
			echo "<a href='https://luxy.art/admin/images/edit_image/".$id_img."' target='_blank'><img src='".$dir_file_name_original_img."' width='150px' height='150px' /></a>&nbsp;";
			echo "<a href='https://luxy.art/admin/images/edit_image/".$id_img."' target='_blank'><img src='".$dir_file_name_watermark_img."' width='150px' height='150px' /></a>&nbsp;";
			echo "<a href='https://luxy.art/admin/images/edit_image/".$id_img."' target='_blank'><img src='".$dir_file_name_watermark_img_1."' width='150px' height='150px' /></a>";
				
			/*if(!$this->check_img_exists($dir_file_name_original_img) || !$this->check_img_exists($dir_file_name_watermark_img) || !$this->check_img_exists($dir_file_name_watermark_img_1)) {
	
			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_product_img',array('img_check_status' => 4, 'is_fix' => 1));
	
			echo "fix image exist: ".$id_img."<br>";
	
			}*/
				
			/*if(!$this->check_img_exists($dir_file_name_original_img)) {
			 	
			//$this->db->where('id_img', $id_img);
			//$this->db->update('luxyart_tb_product_img',array('img_check_status' => 4, 'is_fix' => 1));
				
			echo "fix image exist: ".$id_img."<br>";
				
			}*/
	
			echo "<br>--------------------------------------<br>";
				
		}
	
		//header("Refresh:0");
	
	}
	
	public function fix_image_rotate_for_id_img($id_img){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where(array('id_img'=>$id_img));
		//$this->db->limit(5);
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->result();
	
		foreach($resultProductImg as $_resultProductImg){
	
			$id_img = $_resultProductImg->id_img;
			$id_server = $_resultProductImg->id_server;
			$file_name_original_img = $_resultProductImg->file_name_original_img;
			$file_name_watermark_img = $_resultProductImg->file_name_watermark_img;
			$file_name_watermark_img_1 = $_resultProductImg->file_name_watermark_img_1;
	
			echo "id_img = ".$id_img."<br>";
	
			$this->db->from('luxyart_tb_manager_server');
			$this->db->select('*');
			$this->db->where(array('id_server'=>$id_server));
	
			$rsProductImg = $this->db->get();
			$resultProductImg = $rsProductImg->row();
	
			$server_path_upload = $resultProductImg->server_path_upload;
	
			$dir_file_name_original_img = $server_path_upload."/".$file_name_original_img;
			$dir_file_name_watermark_img = $server_path_upload."/".$file_name_watermark_img;
			$dir_file_name_watermark_img_1 = $server_path_upload."/".$file_name_watermark_img_1;
	
			echo "<a href='https://luxy.art/admin/images/edit_image/".$id_img."' target='_blank'><img src='".$dir_file_name_original_img."' width='150px' height='150px' /></a>&nbsp;";
			echo "<a href='https://luxy.art/admin/images/edit_image/".$id_img."' target='_blank'><img src='".$dir_file_name_watermark_img."' width='150px' height='150px' /></a>&nbsp;";
			echo "<a href='https://luxy.art/admin/images/edit_image/".$id_img."' target='_blank'><img src='".$dir_file_name_watermark_img_1."' width='150px' height='150px' /></a>";
	
			
	
			echo "<br>--------------------------------------<br>";
	
		}
	
		//header("Refresh:0");
	
	}
	
	public function check_img_exists($dir){
		
		if(@is_array(getimagesize($dir))){
			$image = true;
		} else {
			$image = false;
		}
		return $image;
		
	}
	
	public function send_email(){
	
		$dataSendEmail = array();
		$dataSendEmail["name_email_code"] 		= "register";
		$dataSendEmail["lang_id"] 				= 1;
		$dataSendEmail["email_to"] 				= "fermatandrew111@gmail.com";
		$dataSendEmail["name"] 					= ucwords("Đỗ Hữu Thiện");
		$dataSendEmail["link"] 					= "http://153.121.55.90/luxyart_demo/active_email/".md5(12345);
		$dataSendEmail["username"] 				= "dhthien";
		$dataSendEmail["email"] 				= "fermatandrew111@gmail.com";
		$dataSendEmail["password"] 				= "123456";
	
		$this->function_model->sendMailHTML($dataSendEmail);
	
	}
	
	public function send_email_qd(){
		
		$this->function_model->sendMailHTMLQDMail($dataSendEmail);
	
	}
	
	public function delete_user(){

		$user_id = $_POST["user_id"];
		
		if($user_id > 0 ){
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_product_img');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_competition');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_competition_img_apply');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_management_point');
			
			$this->db->where(array('user_buy_img'=>$user_id));
			$this->db->delete('luxyart_tb_product_img_order');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_bookmark_image');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_filter_img');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_follow');
			
			$this->db->where(array('follow_user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_follow');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_get_img_sell');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_message');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_order_credit');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_pickup');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_use_point_package');
			
			$this->db->where(array('user_id'=>$user_id));
			$this->db->delete('luxyart_tb_user_view_product_img');
			
			echo 1;

		}
		else{
			
			echo 0;
			
		}

	}
	
	function fixCountImg(){

		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where('user_id > 0');
		
		$rsUser = $this->db->get();
		$resultUser = $rsUser->result();
		
		$i=0;
		
		foreach($resultUser as $_resultUser){
			
			$user_id = $_resultUser->user_id;
			$count_post_img = $_resultUser->count_post_img;
			
			echo "user_id = ".$user_id."<br>";
			echo "count_post_img = ".$count_post_img."<br>";
			
			$this->db->from('luxyart_tb_product_img');
			$this->db->select('*');
			$this->db->where(array('user_id' => $user_id));
			$result = $this->db->get();
			$count_all_img = $result->num_rows();
			
			echo "count_all_img = ".$count_all_img."<br>";
			
			if($count_post_img != $count_all_img){
				echo "fail(".$i.")<br>";
				$i++;
			}
			
			echo "-------------------------------<br>";

		}

	}
	
	function fixImgColor(){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('id_img < 3000 and is_fix = 0');
		$this->db->limit(1);
		$this->db->order_by("id_img","desc");
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->result();
	
		$i=0;
	
		foreach($resultProductImg as $_resultProductImg){
				
			$id_img = $_resultProductImg->id_img;
			$file_name_original_img = $_resultProductImg->file_name_original_img;
			$id_server = $_resultProductImg->id_server;
				
			echo "id_img = ".$id_img."<br>";
			
			$this->db->from('luxyart_tb_manager_server');
			$this->db->select('*');
			$this->db->where('id_server > '.$id_server);
			
			$rsManagerServer = $this->db->get();
			$resultManagerServer = $rsManagerServer->row();
			
			$server_path_upload = $resultManagerServer->server_path_upload;
			$server_path_upload_img = $resultManagerServer->server_path_upload_img;
			
			echo "src = ".$server_path_upload."/".$file_name_original_img."<br>";
			
			$id_colors = $this->function_model->compareColor($server_path_upload."/".$file_name_original_img);
			echo "id_colors = ".$id_colors."<br>";
			
			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_product_img',array('id_colors' => $id_colors,'is_fix' => 1));
			echo $this->db->last_query();
				
			echo "-------------------------------<br>";
	
		}
		
		header("Refresh:0");
	
	}
	
	function fixErrorUserMessage(){
	
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where('user_id > 0');
		
		$rsUser = $this->db->get();
		$resultUser = $rsUser->result();
		
		$arrKey = array();
		$arrUsers = array();
		foreach($resultUser as $_resultUser){
			if(trim($_resultUser->display_name) != ""){
				$arrKey[] = $_resultUser->user_email;
				$arrUsers[$_resultUser->user_email] = $_resultUser->display_name;
			}
		}
		
		$this->db->from('luxyart_tb_user_message');
		$this->db->select('*');
		$this->db->where('is_fix = 0');
		$this->db->limit(10);
		$this->db->order_by("id_message","desc");
	
		$rsUserMessage = $this->db->get();
		$resultUserMessage = $rsUserMessage->result();
	
		$i=0;
	
		foreach($resultUserMessage as $_resultUserMessage){
				
			$id_message = $_resultUserMessage->id_message;
			$ms_subject = $_resultUserMessage->ms_subject;
			$ms_content = $_resultUserMessage->ms_content;
				
			echo "id_message = ".$id_message."<br>";
			
			$this->db->where('id_message', $id_message);
			$this->db->update('luxyart_tb_user_message',array('is_fix' => 1));
			
			foreach($arrKey as $_arrKey){
				
				if(strpos($ms_subject,$_arrKey)){
					
					$ms_subject = trim(str_replace($_arrKey, $arrUsers[$_arrKey], $ms_subject));
						
					$this->db->where('id_message', $id_message);
					$this->db->update('luxyart_tb_user_message',array('ms_subject' => $ms_subject,'is_fix' => 2));
					
				}
				
				if(strpos($ms_content,$_arrKey)){
						
					$ms_content = trim(str_replace($_arrKey, $arrUsers[$_arrKey], $ms_content));
					
					$this->db->where('id_message', $id_message);
					$this->db->update('luxyart_tb_user_message',array('ms_content' => $ms_content,'is_fix' => 3));
						
				}
				
			}
				
			echo "-------------------------------<br>";
	
		}
		
		header("Refresh:0");
	
	}
	
	function testCompareColor(){
		
		$src = "http://imj01.luxy.art/1526871938-watermark.jpg";
		echo "xxx = ".$this->function_model->compareColor($src);
		
	}
	
	function test_number_format(){
	
		echo $this->function_model->num_format("2.00",2)."<br>";
		echo $this->function_model->num_format("2.70",2)."<br>";
		echo $this->function_model->num_format("2.22",2)."<br>";
		echo $this->function_model->num_format("52.00",2)."<br>";
		echo $this->function_model->num_format("142.70",2)."<br>";
		echo $this->function_model->num_format("79332.22",2)."<br>";
		echo $this->function_model->num_format("179332.00",2)."<br>";
		echo $this->function_model->num_format("2279332.70",2)."<br>";
	
	}
	
	function fix_double_file_name_original_img(){
		
		$this->db->select('id_img, file_name_watermark_img, count(*) as `num`');
		$this->db->from('luxyart_tb_product_img');
		$this->db->group_by('file_name_watermark_img');
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->result();
		
		foreach($resultProductImg as $_resultProductImg){
			
			if($_resultProductImg->num > 1){
			
				$id_img = $_resultProductImg->id_img;
				$num = $_resultProductImg->num;
				$file_name_watermark_img = $_resultProductImg->file_name_watermark_img;
	
				echo "id_img = ".$id_img."<br>";
				echo "num = ".$num."<br>";
				echo "file_name_watermark_img = ".$file_name_watermark_img."<br>";
				
				echo "-------------------------------<br>";
			
			}
	
		}
	
	}
	
	function fixImgDetail(){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('img_check_status != 4');
		$this->db->limit(100);
		$this->db->order_by("id_img","desc");
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->result();
	
		$i=0;
	
		foreach($resultProductImg as $_resultProductImg){
	
			$id_img = $_resultProductImg->id_img;
			$file_name_original_img = $_resultProductImg->file_name_original_img;
			$id_server = $_resultProductImg->id_server;
	
			echo "id_img = ".$id_img."<br>";
				
			$this->db->from('luxyart_tb_manager_server');
			$this->db->select('*');
			$this->db->where('id_server',$id_server);
				
			$rsManagerServer = $this->db->get();
			$resultManagerServer = $rsManagerServer->row();
				
			$server_path_upload = $resultManagerServer->server_path_upload;
			$server_path_upload_img = $resultManagerServer->server_path_upload_img;
				
			echo "src = "."<a target='_blank' href='https://luxy.art/detail/".$_resultProductImg->img_code.".html'>".$server_path_upload."/".$file_name_original_img."</a><br>";
				
			
	
			echo "-------------------------------<br>";
	
		}
	
		header("Refresh:0");
	
	}
	
	function fixProductImgSize(){
		
		$this->db->from('luxyart_tb_product_img_size');
		$this->db->select('*');
		$this->db->where('type_size_description','px X px');
		$this->db->order_by("id_img_size","desc");
		
		$rsProductImgSize = $this->db->get();
		$resultProductImgSize = $rsProductImgSize->result();
		
		$i=0;
		
		foreach($resultProductImgSize as $_resultProductImgSize){
		
			$id_img_size = $_resultProductImgSize->id_img_size;
			$id_img = $_resultProductImgSize->id_img;
			$type_size = $_resultProductImgSize->type_size;
			
			echo "id_img_size = ".$id_img_size."<br>";
			echo "id_img = ".$id_img."<br>";
			echo "type_size = ".$type_size."<br>";
			
			$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
			
			$file_name_original_img = $detail_product->file_name_original_img;
			$id_server = $detail_product->id_server;
			
			$this->db->from('luxyart_tb_manager_server');
			$this->db->select('*');
			$this->db->where('id_server',$id_server);
			
			$rsManagerServer = $this->db->get();
			$resultManagerServer = $rsManagerServer->row();
			
			$server_path_upload = $resultManagerServer->server_path_upload;
			$server_path_upload_img = $resultManagerServer->server_path_upload_img;
			
			echo "src = "."<a target='_blank' href='http://imj01.luxy.art/".$detail_product->file_name_original_img."'>"."http://imj01.luxy.art/".$detail_product->file_name_original_img."</a><br>";
			
			list($width, $height, $type, $attr) = getimagesize("http://imj01.luxy.art/".$detail_product->file_name_original_img);
			
			echo "width = ".$width."<br>";
			echo "height = ".$height."<br>";
			
			if($type_size == 1){
				$width_new = round($width/3);
				$height_new = round($height/3);
			}
			else if($type_size == 2){
				$width_new = round($width/2);
				$height_new = round($height/2);
			}
			else if($type_size == 3){
				$width_new = $width;
				$height_new = $height;
			}
			
			echo "width_new = ".$width_new."<br>";
			echo "height_new = ".$height_new."<br>";
			
			$type_size_description = $width_new."px X ".$height_new."px";
			
			$this->db->where('id_img_size', $id_img_size);
			$this->db->update('luxyart_tb_product_img_size',array('is_fix' => 1,'type_size_description' => $type_size_description));
			
			echo "--------------------<br>";
			
		}

	}
	
	function distribute_lux_to_investor_user(){

		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where(array('user_id > '=>0,'is_recive_lux'=>0));
		//$this->db->where(array('user_id'=>68,'is_recive_lux'=>0));
		$this->db->where_in('user_level',array(3,4));
		$this->db->order_by("user_id","desc");
		
		$rsUser = $this->db->get();
		$resultUser = $rsUser->result();
		
		foreach($resultUser as $_resultUser){
		
			$user_id = $_resultUser->user_id;
			$user_point = $_resultUser->user_point;
			
			//update point user
			$this->db->where('user_id', $user_id);
			$this->db->set('user_point','user_point+164.7',FALSE);
			$this->db->set('is_recive_lux',1);
			$this->db->update('luxyart_tb_user');
			
			echo "distribute lux to investor user = ".$user_id."<br>";
			
		}

	}
	
	function distribute_lux_to_investor_user_for_email(){
	
		$list_email = array(
				'mkt_nshdtykt111521@yahoo.co.jp',
				'kaorichan.1024.y@icloud.com',
				'o9o59354823@docomo.ne.jp',
				'eikou_r3@yahoo.co.jp',
				'naoi_0811@icloud.com',
				'aprikot46@gmail.com',
				'kuroreko369419@gmail.com',
				'jgfny588@gmail.com',
				'acm.snt.sor@gmail.com',
				'haku421seika@ymobile.ne.jp',
				'fnsnow01@gmail.com',
				'yako167sato@ezweb.ne.jp',
				'yuya_shirane@hotmail.com',
				'ayuko.komori@gmail.com',
				'usapyon369@gmail.com'
		);
	
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where(array('user_id > '=>0));
		//$this->db->where(array('user_id'=>68,'is_recive_lux'=>0));
		//$this->db->where_in('user_level',array(3,4));
		$this->db->where_in('user_email',$list_email);
		$this->db->order_by("user_id","desc");
	
		$rsUser = $this->db->get();
		$resultUser = $rsUser->result();
	
		foreach($resultUser as $_resultUser){
	
			$user_id = $_resultUser->user_id;
			$user_email = $_resultUser->user_email;
			$user_point = $_resultUser->user_point;
			$user_level = $_resultUser->user_level;
	
			echo "user_id = ".$user_id."<br>";
			echo "user_email = ".$user_email."<br>";
			echo "user_point = ".$user_point."<br>";
			echo "user_level = ".$user_level."<br>";
				
			//update point user
			$this->db->where('user_id', $user_id);
			$this->db->set('user_point','user_point+183',FALSE);
			$this->db->set('is_recive_lux',2);
			$this->db->update('luxyart_tb_user');
				
			echo "distribute lux to investor user = ".$user_id."<br>";
			echo "------------------------------------<br>";
				
		}
	
	}
	
	function category_zero(){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where(array('id_img > '=>0));
		$this->db->order_by('id_img', 'desc');
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->result();
	
		$i = 0;
		foreach($resultProductImg as $_resultProductImg){
	
			$id_img = $_resultProductImg->id_img;
				
			$this->db->from('luxyart_tb_img_category');
			$this->db->select('*');
			$this->db->where(array('id_img'=>$id_img));
			$rsImgCategory = $this->db->get();
			$resultImgCategory = $rsImgCategory->result();
			
			$num_rows = count($resultImgCategory);
			
			if($num_rows == 0){

				$i++;				

			}
				
		}
		
		echo "count = ".$i."<br>";
	
	}
	
	function delete_account(){
		
		$user_id = 514;
		$sql_delete = $this->function_model->delete_account($user_id);
		
		if($sql_delete != "no_delete"){
			for($i=0; $i<count($sql_delete); $i++){
				$this->db->query($sql_delete[$i]);
			}
		}
	
	}
	
	function fix_img_tags_id(){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('is_fix = 0');
		$this->db->limit(10);
		$this->db->order_by("id_img","desc");
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->result();
	
		$i=0;
	
		foreach($resultProductImg as $_resultProductImg){
	
			$id_img = $_resultProductImg->id_img;
			$img_tags = $_resultProductImg->img_tags;
				
			echo "id_img = ".$id_img."<br>";
				
			$img_tag = explode(",",$img_tags);
			
			$arr_id_product_tag_all = array();
				
			foreach ($img_tag as $tag){
	
				echo "tag = ".$tag."<br>";
	
				$this->db->from('luxyart_tb_product_tags');
				$this->db->select('*');
				$this->db->where('tag_name',trim($tag));
	
				$rsProductTags = $this->db->get();
				$resultProductTags = $rsProductTags->result();
	
				$arr_id_product_tag = array();
	
				foreach($resultProductTags as $_resultProductTags){
						
					$id_product_tag = $_resultProductTags->id_product_tag;
					$root_cate = $_resultProductTags->root_cate;
					$parent_cate = $_resultProductTags->parent_cate;
					
					array_push($arr_id_product_tag, $id_product_tag);
					array_push($arr_id_product_tag_all, $id_product_tag);
						
					$this->db->from('luxyart_tb_img_category');
					$this->db->select('*');
					$this->db->where('id_img',$id_img);
					$this->db->where('root_cate',$root_cate);
					$this->db->where('parent_cate',$parent_cate);
						
					$rsImgCategory = $this->db->get();
					$resultImgCategory = $rsImgCategory->result();
						
					foreach($resultImgCategory as $_resultImgCategory){
	
						$root_cate_img = $_resultProductTags->root_cate;
						$parent_cate_img = $_resultProductTags->parent_cate;
	
						if($root_cate_img == $root_cate && $parent_cate_img == $parent_cate){
							array_push($arr_id_product_tag, $id_product_tag);
							array_push($arr_id_product_tag_all, $id_product_tag);
						}
	
					}
						
				}
				
				$arr_id_product_tag = array_unique($arr_id_product_tag);
				$arr_id_product_tag_all = array_unique($arr_id_product_tag_all);
	
			}
			
			$str_id_product_tag_all = implode(",",$arr_id_product_tag_all);
			echo "str_id_product_tag_all = ".$str_id_product_tag_all."<br>";
			
			$this->db->where('id_img', $id_img);
			$this->db->update('luxyart_tb_product_img',array('img_tags_id' => $str_id_product_tag_all,'is_fix' => 1));
			echo $this->db->last_query();
			
			echo "fix_img_tags_id = ".$id_img."<br>";
	
		}
	
		header("Refresh:0");
	
	}
		
}