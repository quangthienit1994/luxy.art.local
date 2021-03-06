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

class QA extends CI_Controller {
	public function __construct() {
		parent::__construct();
		@session_start();
		$this->load->library('session');
		$this->load->library('cart');
		//$this->load->model('main_model');
		$this->load->model('function_model');
		
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
			$form_lang = "japan";
		}
		else if($_SESSION['lang'] == "vi"){
			$this->lang_id = 2;
			$form_lang = "vietnam";
		}
		else if($_SESSION['lang'] == "en"){
			$this->lang_id = 3;
			$form_lang = "english";
		}
		if(!isset($_SESSION['num_page'])){
			$_SESSION['num_page'] = "10";
		}

		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);

        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
	}


	public function page_q_a()
	{
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/page_q_a", $meta, $this->lang_id);
		$data['title'] = 'よくある質問';
		$this->template->load('default', 'page_q_a', $data, __CLASS__);
	}

	public function page_q_a_category($id) {
		$meta = array();

		//page
		$limit = 10;
		$page = $_GET['page'];
		if($page == '') $page = 1;
		$total = count($this->db->get_where('luxyart_tb_qa',['lang_id'=>$this->lang_id,'category_id'=>$id,'status'=>1])->result());
		$count_page = ceil($total/$limit);
		$begin =  ($page-1)*$limit;
		$data['count_page'] = $count_page;
		$data['current_page'] = $page;
		// end page

		$data['list'] = $this->db->get_where('luxyart_tb_qa',['lang_id'=>$this->lang_id,'category_id'=>$id,'status'=>1],$limit,$begin)->result();
		$category = $this->db->get_where('luxyart_tb_qa_category',['lang_id'=>$this->lang_id,'group_id'=>$id])->row();
		$data['meta_seo']['title_page'] = $category->category_title_seo.' - QA';
		$data['meta_seo']['description_page'] = $category->category_description_seo.' - QA';
		$data['meta_seo']['keyword_page'] = $category->category_keyword_seo.' - QA';
		$this->template->load('default', 'page_q_a_category', $data, __CLASS__);
	}

	public function page_q_a_detail($id) {
		$meta = array();
		$data['get'] = $this->db->get_where('luxyart_tb_qa',['lang_id'=>$this->lang_id,'group_id'=>$id])->row();
		$data['title'] = $data['get']->title;
		$data['meta_seo']['title_page'] = $data['get']->title_seo.' - QA';
		$data['meta_seo']['description_page'] = $data['get']->description_seo.' - QA';
		$data['meta_seo']['keyword_page'] = $data['get']->keyword_seo.' - QA';
		$this->template->load('default', 'page_q_a_detail', $data, __CLASS__);
	}

	public function page_q_a_search() {
		$keyword = $_GET['keyword'];
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/page_q_a", $meta, $this->lang_id);

		if($keyword!=null){
			$listKey = ['title','content'];
			$like = '(';
	        foreach ($listKey as $key => $value) {
	            if($key>0) {
	                $like .= " OR ";
	            }
	            $like .= " $value LIKE '%$keyword%' ";
	        }
	        $like.=')';
	        $this->db->where($like, null, false);
	    }
	    $this->db->where('lang_id', $this->lang_id);
	    $this->db->where('status', 1);
		$this->db->order_by('qa_id','desc');

		//page
		$limit = 10;
		$page = $_GET['page'];
		if($page == '') $page = 1;
		$total = count($this->db->get('luxyart_tb_qa')->result());
		$count_page = ceil($total/$limit);
		$begin =  ($page-1)*$limit;
		$data['count_page'] = $count_page;
		$data['current_page'] = $page;
		// end page

		$this->db->flush_cache();

		if($keyword!=null){
			$listKey = ['title','content'];
			$like = '(';
	        foreach ($listKey as $key => $value) {
	            if($key>0) {
	                $like .= " OR ";
	            }
	            $like .= " $value LIKE '%$keyword%' ";
	        }
	        $like.=')';
	        $this->db->where($like, null, false);
	    }
	    $this->db->where('lang_id', $this->lang_id);
	    $this->db->where('status', 1);
		$this->db->order_by('qa_id','desc');
		$data['list'] = $this->db->get('luxyart_tb_qa', $limit, $begin)->result();


		$data['title'] = 'Search for keyword: '.$_GET['keyword'];
		$data['meta_seo']['title_page'] = $_GET['keyword'].' - QA';
		$data['meta_seo']['description_page'] = $data['title'].' - QA';
		$data['meta_seo']['keyword_page'] = $data['title'].' - QA';
		$this->template->load('default', 'page_q_a_search', $data, __CLASS__);
	}



















	// ==============================================================
	//							Anh Tuan
	// ==============================================================

	public function check_email_register()
	{
		$email=$_POST['email'];
		$query = $this->db->query('SELECT user_id FROM luxyart_tb_user WHERE user_email="'.$email.'"');
		if($query->num_rows() > 0)
		{
			echo $email."{return_checkemail}1";exit;
		}
		else
		{
			echo $email."{return_checkemail}0";exit;
		}
		$query->free_result();
	}
	public function do_ajax_register_user()
	{
		// Create codeid invite user
		$bangkytuset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$code_repeat_1 = 1;
		$code_repeat_2 = 10;
		$dodaichuoicode = 15;
		// End Create codeid invite user
		$email=$_POST['email'];
		$user_name = explode('@',$_POST['email']);
		$passord=$_POST['passord'];
		$has_notice=$_POST['has_notice'];
		$date_register = date("Y-m-d H:i:s");
		$user_id_code_invite = substr(str_shuffle(str_repeat($bangkytuset, mt_rand($code_repeat_1,$code_repeat_2))),1,$dodaichuoicode);
		$os_register = 3;
		$data = array('user_email'=>$email,'user_pass'=>md5($passord),'country_id'=>$this->lang_id,'notice_luxyart'=>$has_notice,'user_level'=>1,'user_status'=>0,'user_dateregister'=>$date_register,'user_id_code_invite'=>$user_id_code_invite,'os_register'=>$os_register);
		$this->db->insert('luxyart_tb_user',$data);
    	if($this->db->affected_rows() != 1)
		{
			echo $email."{return_do_register_user}1";
			exit;	
		}
		else
		{
			echo $email."{return_do_register_user}0";
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "register";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $email;
			$dataSendEmail["name"] 					= ucwords("New User");
			$dataSendEmail["link"] 					= base_url()."active-email/".$user_id_code_invite;
			$dataSendEmail["username"] 				= $user_name[0];
			$dataSendEmail["email"] 				= $email;
			$dataSendEmail["password"] 				= $passord;
			$this->function_model->sendMailHTML($dataSendEmail);
			exit;	
		}
		
	}
	public function do_ajax_register_user_creator()
	{
		// Create codeid invite user
		$bangkytuset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$code_repeat_1 = 1;
		$code_repeat_2 = 10;
		$dodaichuoicode = 15;
		// End Create codeid invite user
		$email=$_POST['email'];
		$user_name = explode('@',$_POST['email']);
		$passord=$_POST['passord'];
		$has_notice=$_POST['has_notice'];
		$username=$_POST['username'];
		$date_register = date("Y-m-d H:i:s");
		$user_id_code_invite = substr(str_shuffle(str_repeat($bangkytuset, mt_rand($code_repeat_1,$code_repeat_2))),1,$dodaichuoicode);
		$os_register = 3;
		$data = array('user_email'=>$email,'user_firstname'=>$username,'user_pass'=>md5($passord),'country_id'=>$this->lang_id,'notice_luxyart'=>$has_notice,'user_level'=>2,'user_status'=>0,'user_dateregister'=>$date_register,'user_id_code_invite'=>$user_id_code_invite,'os_register'=>$os_register);
		$this->db->insert('luxyart_tb_user',$data);
    	if($this->db->affected_rows() != 1)
		{
			echo $email."{return_do_register_user}1";
			exit;	
		}
		else
		{
			echo $email."{return_do_register_user}0";
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "register";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $email;
			$dataSendEmail["name"] 					= $username;
			$dataSendEmail["link"] 					= base_url()."active-email/".$user_id_code_invite;
			$dataSendEmail["username"] 				= $username;
			$dataSendEmail["email"] 				= $email;
			$dataSendEmail["password"] 				= $passord;
			$this->function_model->sendMailHTML($dataSendEmail);
			exit;	
		}
		
	}
	public function active_email()
	{
		if($this->uri->segment('2')!="")
		{
			
			$this->db->from('luxyart_tb_user');
			$this->db->select('*');
			$this->db->where(array('user_id_code_invite' => $this->uri->segment('2')));
			$rs = $this->db->get();
			$row = $rs->row();
			if($rs->num_rows() > 0)
			{
				if($row->is_active == 0)
				{
					$email= $row->user_email;
					$user_name = explode('@',$row->user_email);
					$this->db->where('user_id_code_invite', $this->uri->segment('2'));
					$this->db->update('luxyart_tb_user',array('user_status' =>1,'is_active' =>1));
					$data['error_active'] = 'code_active_do';
					// gui email thong bao active tai khoan thanh cong
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "active-email";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $email;
					$dataSendEmail["name"] 					= ucwords("New User");
					$dataSendEmail["username"] 				= $user_name[0];
					$dataSendEmail["email"] 				= $email;
					$this->function_model->sendMailHTML($dataSendEmail);
					$sess_data = array(
					'user_id' 			=> $row->user_id,
					'user_email' 		=> $row->user_email,
					'user_pass' 		=> md5($row->user_pass),
					'user_firstname' 	=> $row->user_firstname,
					'user_lastname' 	=> $row->user_lastname,
					'user_level' 		=> $row->user_level
					);
					$this->session->set_userdata('logged_in', $sess_data);
				}
				else
				{
					$data['error_active'] = 'code_active_before';
					$sess_data = array(
					'user_id' 			=> $row->user_id,
					'user_email' 		=> $row->user_email,
					'user_pass' 		=> md5($row->user_pass),
					'user_firstname' 	=> $row->user_firstname,
					'user_lastname' 	=> $row->user_lastname,
					'user_level' 		=> $row->user_level
					);
					$this->session->set_userdata('logged_in', $sess_data);
				}
			}
			else
			{
				$data['error_active'] = 'code_not_true';
			}
		}
		else
		{
			$data['error_active'] = 'code_id_not_exits';
		}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/active_email", $meta, $this->lang_id);
		$this->template->load('default', 'active_email', $data, __CLASS__);
	}
	public function active_email_after_change()
	{
		if($this->uri->segment('2')!="")
		{
			
			$this->db->from('luxyart_tb_user');
			$this->db->select('*');
			$this->db->where(array('user_id_code_invite' => $this->uri->segment('2')));
			$rs = $this->db->get();
			$row = $rs->row();
			if($rs->num_rows() > 0)
			{
				if($row->is_active == 0)
				{
					$email= $row->user_email;
					$user_name = explode('@',$row->user_email);
					$this->db->where('user_id_code_invite', $this->uri->segment('2'));
					$this->db->update('luxyart_tb_user',array('is_active' =>1));
					$data['error_active'] = 'code_active_do';
					// gui email thong bao active tai khoan thanh cong
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "active-email-after-changed";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $email;
					$dataSendEmail["name"] 					= $user_name[0];
					$dataSendEmail["username"] 				= $user_name[0];
					$dataSendEmail["email"] 				= $email;
					$this->function_model->sendMailHTML($dataSendEmail);
				}
				else
				{
					$data['error_active'] = 'code_active_before';
				}
			}
			else
			{
				$data['error_active'] = 'code_not_true';
			}
		}
		else
		{
			$data['error_active'] = 'code_id_not_exits';
		}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/active_email", $meta, $this->lang_id);
		$this->template->load('default', 'active_email', $data, __CLASS__);
	}
	public function user_timeline()
	{
		
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where('user_id', $this->uri->segment('2'));
		$this->db->where('user_status', 1);
		$rs = $this->db->get();
		$result = $rs->row();
		if ($this->db->affected_rows() > 0)
		{
			$data['load_user_config']['user_id'] = $result->user_id;
			$user_firstname = $result->user_firstname;
			$user_lastname = $result->user_lastname;
			$get_name_email = explode('@',$result->user_email);
			if($user_firstname =="" && $user_lastname=="")
			{
				$name_call = $get_name_email[0];
			}
			else
			{
				$name_call = $user_firstname.' '.$user_lastname;
			}
			
			if($this->lang_id==1)
			{
				$data['meta_seo']['title_page'] ='Ja Time line User Page '.$name_call;
				$data['meta_seo']['description_page'] ='Ja Time line description';
				$data['meta_seo']['keywords'] =$result->user_introduction;
				$data['meta_seo']['page_h1'] ='Ja Time line User Page';
			}
			if($this->lang_id==2)
			{
				$data['meta_seo']['title_page'] ='Vi Time line User Page '.$name_call;
				$data['meta_seo']['description_page'] ='Vi Time line description';
				$data['meta_seo']['keywords'] =$result->user_introduction;
				$data['meta_seo']['page_h1'] ='Vi Time line User Page';
			}
			if($this->lang_id==3)
			{
				$data['meta_seo']['title_page'] ='En Time line User Page '.$name_call;
				$data['meta_seo']['description_page'] ='En Time line description';
				$data['meta_seo']['keywords'] =$result->user_introduction;
				$data['meta_seo']['page_h1'] ='En Time line User Page';
			}
			$data['load_user_timeline']['user_id'] = $result->user_id;
			$data['load_user_timeline']['user_introduction'] = $result->user_introduction;
			$data['load_user_timeline']['user_name'] = $name_call;
			$data['load_user_timeline']['user_banner'] = $result->user_banner;
			$data['load_user_timeline']['user_avatar'] = $result->user_avatar;
			
			$this->load->model('main_model');
			$this->load->model('manager_user_model');
			$data['load_user_timeline']['count_product'] = $this->manager_user_model->getCountProduct($result->user_id);
			$wherelist_pickup_category = array('lang_id' => $this->lang_id, 'status_cate' => 1,'is_pickup' =>1);
			$orderlist_pickup_category = array();
			$data['list_pickup_category'] = $this->main_model->getAllDataLimit("luxyart_tb_product_category", $wherelist_pickup_category, 				$orderlist_pickup_category,4,0)->result();
    	//list user follow
    	$data['list_user_follow'] = $this->function_model->getUserFollow()->result();
		//get list images
		$this->load->library('pagination');
		$config['per_page'] = 5;
		
		$config['total_rows'] = count($this->manager_user_model->get_list_user_timeline_image($result->user_id,'','')->result());
		
		$config['uri_segment'] = 3;
		$config['next_link'] =  'Next »';
		$config['prev_link'] =  '« Prev';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/timeline/'.$result->user_id;
		
		$data['list_images'] = $this->manager_user_model->get_list_user_timeline_image($result->user_id,$config['per_page'],$this->uri->segment(3))->result();
		
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
			$this->template->load('default', 'user_timeline', $data, __CLASS__);
		}
		else
		{
			header("Location: ".base_url()."404_override.html");
		}
		
	}
	public function do_ajax_reset_password()
	{
		// tao chuoi password
		$bangkytuset = '!*%$#@~/?{}[]0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$code_repeat_1 = 1;
		$code_repeat_2 = 10;
		$dodaichuoicode = 7;
		$password_new = substr(str_shuffle(str_repeat($bangkytuset, mt_rand($code_repeat_1,$code_repeat_2))),1,$dodaichuoicode);
		$this->db->where('user_email', $_POST['email']);
		if ($this->db->update('luxyart_tb_user',array('user_pass' => md5($password_new))) === TRUE){
			$username = explode('@',$_POST['email']);
    		$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "remind-password";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $_POST['email'];
			$dataSendEmail["name"] 					= ucwords("User");
			$dataSendEmail["username"] 				= $_POST['email'];
			$dataSendEmail["email"] 				= $username[0];
			$dataSendEmail["password"] 				= $password_new;
			$this->function_model->sendMailHTML($dataSendEmail);
			echo "update{return_do_reset_password}0";
			exit;
		}
		else
		{
			echo "update{return_do_reset_password}1";
			exit;
		}
	}
	public function do_ajax_download_img()
	{		
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->library('sftp');
		$this->load->model('manager_user_model');
		if($this->uri->segment('2')!="")
		{
			$code_download = $this->uri->segment('2');
			$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img_size.id_img = luxyart_tb_product_img.id_img','left');
			$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
			$this->db->join('luxyart_tb_product_img_order_detail', 'luxyart_tb_product_img_order_detail.id_img_size = luxyart_tb_product_img_size.id_img_size and luxyart_tb_product_img_order_detail.id_order_img = luxyart_tb_product_img_order.id_order_img','left');
			$this->db->from('luxyart_tb_product_img_size,luxyart_tb_product_img_order');
			$this->db->select('luxyart_tb_product_img_size.type_size_description,luxyart_tb_product_img_order.user_buy_img,luxyart_tb_product_img_order.date_end_download,luxyart_tb_product_img.id_img,luxyart_tb_product_img.file_name_original_img,luxyart_tb_product_img.img_width,luxyart_tb_manager_server.server_name
,luxyart_tb_manager_server.server_path_upload_img,luxyart_tb_manager_server.server_path_upload,luxyart_tb_manager_server.server_ftp_account,luxyart_tb_manager_server.server_ftp_pass,luxyart_tb_manager_server.server_port');
			$this->db->where('luxyart_tb_product_img_size.code_download', $code_download);
			$this->db->where('luxyart_tb_product_img_size.status_img_size', 0);
			$this->db->where('luxyart_tb_product_img_order.user_buy_img', $user_id);
			$this->db->where('luxyart_tb_product_img_order.date_end_download >',date("Y-m-d H:i:s"));
			$this->db->limit(1);
			$rs = $this->db->get();
			//echo $this->db->last_query();
			//exit();
			$result = $rs->row();
			if(!empty($result)){
				$sftp_config['hostname'] = $result->server_name;
				$sftp_config['username'] = $result->server_ftp_account;
				$sftp_config['password'] = $result->server_ftp_pass;
				$sftp_config['port'] = $result->server_port;
				$sftp_config['debug'] = FALSE;
				$this->sftp->connect($sftp_config);
				//$success = $this->sftp->list_files("/usr/local/apache/htdocs/luxyart_demo/publics/js/", TRUE);
				//$success = $this->sftp->list_files("/home/vioi1324/public_html/css_version_2/", TRUE);
				
				//print_r($success);
				//exit();
				$get_image = $this->sftp->check_file($result->file_name_original_img,$result->server_path_upload_img);
				$path_local = $_SERVER['DOCUMENT_ROOT'].'/luxyart_demo/publics/do_download/'.$result->file_name_original_img;
				$name_new = explode('.', $result->file_name_original_img);
				$path_local_remove_type = $_SERVER['DOCUMENT_ROOT'].'/luxyart_demo/publics/do_download/'.$name_new['0'];
				$size_img = explode('px X ',$result->type_size_description);
				if (strlen($get_image))
				{
					$success = $this->sftp->download("/".$result->server_path_upload_img."/".$result->file_name_original_img, "/usr/local/apache/htdocs/luxyart_demo/publics/do_download/".$result->file_name_original_img);
					$result = $_SERVER['DOCUMENT_ROOT'].'/luxyart_demo/publics/do_download/'.$result->file_name_original_img;
					  if (file_exists($result)) {
						$this->manager_user_model->resize($size_img['0'],$path_local_remove_type, $path_local);
						header('Content-Description: File Transfer');
						header('Content-Type: application/force-download');
						header('Content-Disposition: attachment; filename='.basename($result));
						header('Content-Transfer-Encoding: binary');
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Pragma: public');
						header('Content-Length: ' . filesize($result));
						ob_clean();
						flush();
						readfile($result);
						@unlink($result);
						//echo 'download file';
						//exit();
					  }
					
				}
				else
				{
				  echo "Error: Can't find image download";
				}
			}
			else{
				echo "Error: Can't find code download image";
			}
		}
		else
		{
			echo "Error: Code download empty";
		}
	}
	public function do_ajax_delete_bookmark_img()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$id_delete = explode(",", $_POST['id_delete']);
		$this->db->where('user_id', $user_id);
		$this->db->where_in('id_bookmark',$id_delete);
		$this->db->delete('luxyart_tb_user_bookmark_image');
		if ($this->db->affected_rows() > 0)
		{
			echo "ketqua{return_delete_bookmark_img}success";
			exit;	
		}
		else
		{
			echo "ketqua{return_delete_bookmark_img}failed";
			exit();
		}
	}
	public function login(){
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if($is_permission){
			header("Location: ".base_url()."mypage.html");
		}
		$is_popup = @$this->session->userdata['is_popup'];
		if($is_popup){
			$data["message_login"] = "Đăng nhập thất bại. Vui lòng thử lại";
			$this->session->unset_userdata('is_popup');
		}
		else{
			$data["message_login"] = "";
		}
		
		$data['actual_link'] = $this->session->userdata['actual_link'];
		$this->session->unset_userdata('actual_link');
		
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/login", $meta, $this->lang_id);
		$this->template->load('default', 'login', $data, __CLASS__);	
	}
	public function logout(){
		$sessionData = $this->session->all_userdata();
	 	foreach($sessionData as $key =>$val){
		if($key!='admin_id' && $key!='logged_in_admin' && $key!='admin_role' && $key!='admin_status')
			{
			 $this->session->unset_userdata($key);
		 	}
	  	}
		header("Location: ".base_url()."login.html");	
	}
	public function login_ajax(){
		
		$user_email 		= trim($_POST['user_email']);
		$user_pass 			= trim($_POST['user_pass']);
		$remember_me 		= trim($_POST['remember_me']);
		$is_popup 			= $_POST['is_popup'];
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where(array('user_email' => $user_email, 'user_pass' => md5($user_pass)));
		$rs = $this->db->get();
		$row = $rs->row();
		if(!empty($row)){
			if($row->user_status==1 && $row->is_active==1)
			{
				if($remember_me) {
					// Set remember me value in session
					$this->session->set_userdata('remember_me', 1);
				}
				$sess_data = array(
						'user_id' 			=> $row->user_id,
						'user_email' 		=> $user_email,
						'user_pass' 		=> $user_pass,
						'user_firstname' 	=> $row->user_firstname,
						'user_lastname' 	=> $row->user_lastname,
						'user_level' 		=> $row->user_level
				);
				$this->session->set_userdata('logged_in', $sess_data);
				echo 1;
			}
			else if($row->user_status==1 && $row->is_active==0)
			{
				echo 2;
			}
			else if($row->user_status==0 && $row->is_active==0)
			{
				echo 3;
			}
			else if($row->user_status==3)
			{
				echo 4;
			}
			else
			{
				echo 5;
			}
		}
		else{
			if($is_popup == 1){
				$this->session->set_userdata('is_popup', 1);
			}
			else{
				$this->session->unset_userdata('is_popup');
			}	
			echo -1;
		}
	}
	public function dangky(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/dangky", $meta, $this->lang_id);
		$this->template->load('default', 'dangky', $data, __CLASS__);
	}
	public function dangky_creator(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/dangky_creator", $meta, $this->lang_id);
		$this->template->load('default', 'dangky_creator', $data, __CLASS__);
	}
	public function remind(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/remind", $meta, $this->lang_id);
		$this->template->load('default', 'remind', $data, __CLASS__);
	}
	public function remind_success(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/remind_success", $meta, $this->lang_id);
		$this->template->load('default', 'remind_success', $data, __CLASS__);
	}
	public function notice_register_success(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/notice_register_success", $meta, $this->lang_id);
		$this->template->load('default', 'register_success', $data, __CLASS__);
	}
	public function notice_register_creator_success(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/notice_register_creator_success", $meta, $this->lang_id);
		$this->template->load('default', 'register_creator_success', $data, __CLASS__);
	}
	public function change_language(){
		$language_id = $_POST['language_id'];
		if($language_id == 1){
			$_SESSION['lang'] ='ja';
		}
		else if($language_id == 2){
			$_SESSION['lang'] ='vi';
		}
		else if($language_id == 3){
			$_SESSION['lang'] ='en';
		}
	}
	public function error_404()
	{
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/error_404", $meta, $this->lang_id);
		$this->template->load('default', 'page_error_404', $data, __CLASS__);
	}
	
	public function page_contact()
	{
		$logged_in = $this->function_model->get_logged_in();
		if(!empty($logged_in))
		{
			$data['load_post_contact']['tencontact'] = $logged_in['user_firstname'].' '.$logged_in['user_lastname'];
			$data['load_post_contact']['emailcontact'] = $logged_in['user_email'];
		}
		$this->load->model('manager_user_model');
		if(isset($_REQUEST['submit']))
		{
			$data['load_post_contact']['tencontact'] = $_POST['tencontact'];
			$data['load_post_contact']['emailcontact'] = $_POST['emailcontact'];
			$data['load_post_contact']['casecontact'] = $_POST['casecontact'];
			$data['load_post_contact']['noidungcontact'] = $_POST['noidungcontact'];
			$this->load->library('form_validation');
				$rules=array(
					array(
						'field'=>'tencontact',
						'label'=>'お名前',
						'rules'=>'trim|required'
					),
					array(
						'field'=>'emailcontact',
						'label'=>'メールアドレスメールアドレス',
						'rules'=>'trim|required|valid_email|xss_clean'
					),
					array(
							'field'=>'casecontact',
							'label'=>'お問い合わせ項目',
							'rules'=>'trim|required|callback_checkvalue_select'
						),
					array(
							'field'=>'noidungcontact',
							'label'=>'お問い合わせ内容',
							'rules'=>'trim|required|max_length[200]|min_length[50]'
						)
				);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					$email = $logged_in['user_email'];
					$user_name = explode('@',$email);
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "user-contact";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $_POST['emailcontact'];
					$dataSendEmail["email"] 				= $_POST['emailcontact'];
					$dataSendEmail["name"] 					= $_POST['tencontact'];
					$dataSendEmail["content_contact"]		= nl2br($_POST['noidungcontact']);
					if($_POST['casecontact']==1)
					{
						$case_contact = '購入について';
					}
					if($_POST['casecontact']==2)
					{
						$case_contact = '素材の販売について';
					}
					if($_POST['casecontact']==3)
					{
						$case_contact = 'その他';
					}
					
					$dataSendEmail["type_contact"]			= $case_contact;
					$dataSendEmail["is_send_admin"] 		= 2;
					$this->function_model->sendMailHTML($dataSendEmail);
					header("Location: ".base_url()."contact-complete.html");
				}
		}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/page_contact", $meta, $this->lang_id);
		$this->template->load('default', 'page_contact', $data, __CLASS__);
	}
	public function contact_complete()
	{
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/page_contact_complete", $meta, $this->lang_id);
		$this->template->load('default', 'page_contact_complete', $data, __CLASS__);
	}
	public function static_page()
	{
		
		$code_call_page = $this->uri->segment(2);
		$this->db->from('luxyart_static_page');
		$this->db->select('*');
		$this->db->where('lang_id',$this->lang_id);
		$this->db->where('code_call',$code_call_page);
		$re=$this->db->get();
		if($re->num_rows()>0)
		{
			$data=$re->row_array();
			$data['meta_seo']['title_page'] = $data['title'];
			$data['meta_seo']['description_page'] = $data['description_seo'];
			$data['meta_seo']['keywords'] = $data['meta_key_seo'];
			$this->template->load('default', 'static_page_show', $data, __CLASS__);
		}
		else
		{
			$this->error_404();
		}
	}
	public function mypage(){
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where(array('user_id' => $user_id));
		$rs = $this->db->get();
		$row = $rs->row();
		if(!empty($row)){
			if($row->user_lastname=="" ||$row->user_firstname=="" || $row->user_zipcode=="" || $row->state_id==0 || $row->cityname=="" || $row->user_address=="" || $row->user_address_building=="")
			{
				$data['show_update_account'] = 1;
			}
			else
			{
				$data['show_update_account'] = 0;
			}
		}
		// get list order
		$this->load->model('manager_user_model');
		$limit_order_list = 10;
		$data['list_order_mypage'] = $this->manager_user_model->get_list_order($user_id,$limit_order_list)->result();
		$limit_view_img_history = 10;
		$data['list_history_view_mypage'] = $this->manager_user_model->get_list_view_img_history($user_id,$limit_view_img_history)->result();
		$limit_new_img = 10;
		$data['list_new_image_bookmark_user'] = $this->manager_user_model->get_list_new_img_bookmark_user($user_id,$limit_new_img)->result();
		//get meta seo
		$data['active_menu_sidebar'] = '';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage", $meta, $this->lang_id);
		$this->template->load('default', 'mypage', $data, __CLASS__);
	}
	public function user_manager_point()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-user-manager-point';
		
		$setting = $this->function_model->get_setting();
		$data['minium_export_money'] = $setting['minium_export_money'];
		$this->load->model('manager_user_model');
		$data['list_history_use_point'] = $this->manager_user_model->get_list_user_use_point($user_id,7)->result();
		$data['list_ask_return_point'] = $this->manager_user_model->get_list_ask_return_point($user_id,7)->result();
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_manager_point", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_manager_point', $data, __CLASS__);
	}
	public function user_export_money()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		$data['active_menu_sidebar'] = 'mypage-user-manager-point';
		$this->db->from('luxyart_tb_bank_infomation');
		$this->db->select('*');
		$this->db->where('luxyart_tb_bank_infomation.user_id',$user_id);
		$re=$this->db->get();
		$row = $re->row();
		if($re->num_rows()>0)
		{
			$data['load_bank_user']['bank_name']=$row->bank_name;
			$data['load_bank_user']['bank_branch_name']=$row->bank_branch_name;
			$data['load_bank_user']['bank_account_number']=$row->bank_account;
			$data['load_bank_user']['account_name']=$row->bank_account_name;
			$data['load_bank_user']['type_bank']=$row->bank_type_account;
			$is_update=1;
		}
		else
		{
			$data['load_bank_user']['bank_name']='';
			$data['load_bank_user']['bank_branch_name']='';
			$data['load_bank_user']['bank_account_number']='';
			$data['load_bank_user']['account_name']='';
			$data['load_bank_user']['type_bank']='';
			$is_update=0;
		}
		if(isset($_REQUEST['submit']))
			{	
				$data['load_bank_user']['point'] = $_POST['point'];
				$data['load_bank_user']['bank_name']= $_POST['bank_name'];
				$data['load_bank_user']['bank_branch_name']= $_POST['bank_branch_name'];
				$data['load_bank_user']['bank_account_number']= $_POST['bank_account_number'];
				$data['load_bank_user']['account_name']= $_POST['account_name'];
				$data['load_bank_user']['type_bank']= $_POST['type_bank'];
				$this->load->library('form_validation');
				$rules=array(
					array(
						'field'=>'point',
						'label'=>'Point',
						'rules'=>'trim|required|numeric|callback_checkvalue_point_input'
					),
					array(
						'field'=>'bank_name',
						'label'=>'Bank name',
						'rules'=>'trim|required'
					),
					array(
							'field'=>'bank_branch_name',
							'label'=>'Bank branch name',
							'rules'=>'trim|required'
						),
					array(
							'field'=>'bank_account_number',
							'label'=>'Bank account number',
							'rules'=>'trim|required|numeric|callback_checkvalue_number_input'
						),
					array(
							'field'=>'account_name',
							'label'=>'Account name',
							'rules'=>'trim|required'
						)
				);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					if($is_update==1)
					{
						$update_data_bank = array(
							'bank_name' 					=> $_POST['bank_name'],
							'bank_branch_name' 				=> $_POST['bank_branch_name'],
							'bank_account' 					=> $_POST['bank_account_number'],
							'bank_account_name' 			=> $_POST['account_name'],
							'bank_type_account' 			=> $_POST['type_bank']
						);
						$this->db->where('luxyart_tb_bank_infomation.user_id', $user_id);
    					$this->db->update('luxyart_tb_bank_infomation', $update_data_bank);
				
					}else
					{
						$insert_data_bank = array(
							'user_id' 						=> $user_id,
							'bank_name' 					=> $_POST['bank_name'],
							'bank_branch_name' 				=> $_POST['bank_branch_name'],
							'bank_account' 					=> $_POST['bank_account_number'],
							'bank_account_name' 			=> $_POST['account_name'],
							'bank_type_account' 			=> $_POST['type_bank'],
							'date_add_bank' 				=> date('Y-m-d H:i:s'),
						);
						$this->db->insert('luxyart_tb_bank_infomation', $insert_data_bank);
					}
					$setting = $this->function_model->get_setting();
					$insert_data_tranfer_money_user = array(
							'user_id' 						=> $user_id,
							'tranfer_point_require' 		=> $_POST['point'],
							'tranfer_status' 				=> 1,
							'money_user_get' 				=> $_POST['point']*$setting['point_convert_money'],
							'date_add_require' 				=> date('Y-m-d H:i:s'),
					);
					$this->db->insert('luxyart_tb_tranfer_money_user', $insert_data_tranfer_money_user);
					// minus point user
					$this->db->set('user_point', 'user_point - ' .$_POST['point'], FALSE);
    				$this->db->where('user_id', $user_id);
    				$this->db->update('luxyart_tb_user');
					$addNotice = array();
					$addNotice['type'] 						= "user-ask-export-money";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_send_id'] 				= -1;
					$addNotice['user_id'] 					= $user_id;
					$money_get = $_POST['point']*$setting['point_convert_money'];
					// nguoi nhan msg
					$addNotice['point'] 					= $_POST['point'];
					$addNotice['money_ask'] 				= number_format($money_get,0,".",",");
					$this->function_model->addNotice($addNotice);
					$email = $logged_in['user_email'];
					$user_name = explode('@',$email);
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "user-ask-export-money";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $email;
					$dataSendEmail["name"] 					= $user_name[0];
					$dataSendEmail["username"] 				= $user_name[0];
					$dataSendEmail["point"] 				= $_POST['point'];
					$dataSendEmail["money_ask"] 			= number_format($money_get,0,".",",");
					$dataSendEmail["is_send_admin"] 		= 1;
					$this->function_model->sendMailHTML($dataSendEmail);
					header("Location: ".base_url()."mypage/user-manager-point.html");
				}
			}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_export_money", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_export_money', $data, __CLASS__);
	}
	public function user_change_bank_account()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		$data['active_menu_sidebar'] = 'mypage-user-change-bank_account';
		$this->db->from('luxyart_tb_bank_infomation');
		$this->db->select('*');
		$this->db->where('luxyart_tb_bank_infomation.user_id',$user_id);
		$re=$this->db->get();
		$row = $re->row();
		if($re->num_rows()>0)
		{
			$data['load_bank_user']['bank_name']=$row->bank_name;
			$data['load_bank_user']['bank_branch_name']=$row->bank_branch_name;
			$data['load_bank_user']['bank_account_number']=$row->bank_account;
			$data['load_bank_user']['account_name']=$row->bank_account_name;
			$data['load_bank_user']['type_bank']=$row->bank_type_account;
			$is_update=1;
		}
		else
		{
			$data['load_bank_user']['bank_name']='';
			$data['load_bank_user']['bank_branch_name']='';
			$data['load_bank_user']['bank_account_number']='';
			$data['load_bank_user']['account_name']='';
			$data['load_bank_user']['type_bank']='';
			$is_update=0;
		}
		if(isset($_REQUEST['submit']))
			{	
				$data['load_bank_user']['bank_name']= $_POST['bank_name'];
				$data['load_bank_user']['bank_branch_name']= $_POST['bank_branch_name'];
				$data['load_bank_user']['bank_account_number']= $_POST['bank_account_number'];
				$data['load_bank_user']['account_name']= $_POST['account_name'];
				$data['load_bank_user']['type_bank']= $_POST['type_bank'];
				$this->load->library('form_validation');
				$rules=array(
					array(
						'field'=>'bank_name',
						'label'=>'Bank name',
						'rules'=>'trim|required'
					),
					array(
							'field'=>'bank_branch_name',
							'label'=>'Bank branch name',
							'rules'=>'trim|required'
						),
					array(
							'field'=>'bank_account_number',
							'label'=>'Bank account number',
							'rules'=>'trim|required|numeric|callback_checkvalue_number_input'
						),
					array(
							'field'=>'account_name',
							'label'=>'Account name',
							'rules'=>'trim|required'
						)
				);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					if($is_update==1)
					{
						$update_data_bank = array(
							'bank_name' 					=> $_POST['bank_name'],
							'bank_branch_name' 				=> $_POST['bank_branch_name'],
							'bank_account' 					=> $_POST['bank_account_number'],
							'bank_account_name' 			=> $_POST['account_name'],
							'bank_type_account' 			=> $_POST['type_bank']
						);
						$this->db->where('luxyart_tb_bank_infomation.user_id', $user_id);
    					$this->db->update('luxyart_tb_bank_infomation', $update_data_bank);
						$data['notice_update'] ='Your bank account update successful';
						$addNotice = array();
						$addNotice['type'] 						= "user-update-bank-account";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_send_id'] 				= -1;
						$addNotice['user_id'] 					= $user_id;
						$this->function_model->addNotice($addNotice);
				
					}else
					{
						$insert_data_bank = array(
							'user_id' 						=> $user_id,
							'bank_name' 					=> $_POST['bank_name'],
							'bank_branch_name' 				=> $_POST['bank_branch_name'],
							'bank_account' 					=> $_POST['bank_account_number'],
							'bank_account_name' 			=> $_POST['account_name'],
							'bank_type_account' 			=> $_POST['type_bank'],
							'date_add_bank' 				=> date('Y-m-d H:i:s'),
						);
						$this->db->insert('luxyart_tb_bank_infomation', $insert_data_bank);
						$data['notice_update'] ='Create bank account successful';
						$addNotice = array();
						$addNotice['type'] 						= "user-create-bank-account";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_send_id'] 				= -1;
						$addNotice['user_id'] 					= $user_id;
						$this->function_model->addNotice($addNotice);
					}
				}
			}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_change_bank_account", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_change_bank_account', $data, __CLASS__);
	}
	function checkvalue_select($value)
	{
		if($value==0)
		{
			$this->form_validation->set_message('checkvalue_select', 'Please choice type contact');
            return FALSE;
		}
	}
	function checkvalue_point_input($point)
	{
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$setting = $this->function_model->get_setting();
		$this->load->model('manager_user_model');
		$user = $this->manager_user_model->get_user_id($user_id);
		if($point < $setting['minium_export_money'] || $point > $user->user_point)
		{
			$this->form_validation->set_message('checkvalue_point_input', 'Point input at least '.$setting['minium_export_money'].' and not greater than '.number_format($user->user_point,0,".",","));
             return FALSE;
		}
		if (!preg_match('/^[0-9]*$/', $point))
		{
			$this->form_validation->set_message('checkvalue_point_input', 'Please input only number not "+" or "-"');
             return FALSE;
		}
		
	}
	function checkvalue_number_input($number)
	{
		if (!preg_match('/^[0-9]*$/', $number))
		{
			$this->form_validation->set_message('checkvalue_number_input', 'Please input only number not "+" or "-"');
             return FALSE;
		}
		
	}
	public function fn_history_user_view_img()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-history-view-image';
		$this->load->library('pagination');
		$config['per_page'] = 1;
		$config['total_rows'] = count($this->manager_user_model->p_history_view_image($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  'Next »';
		$config['prev_link'] =  '« Prev';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/history-view-image';
		$data['p_history_view_image'] = $this->manager_user_model->p_history_view_image($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();	
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_list_history_user_view_image", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_list_history_user_view_image', $data, __CLASS__);
	}
	public function fn_favorite_image()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-favorite-image';
		$this->load->library('pagination');
		$config['per_page'] = 10;
		$config['total_rows'] = count($this->manager_user_model->p_favorite_image($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  'Next »';
		$config['prev_link'] =  '« Prev';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/favorite-image';
		$data['p_favorite_image'] = $this->manager_user_model->p_favorite_image($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();	
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_favorite_image", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_favorite_image', $data, __CLASS__);
	}
	public function fn_favorite_user()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-favorite-user';
		$this->load->library('pagination');
		$config['per_page'] = 1;
		$config['total_rows'] = count($this->manager_user_model->p_favorite_user($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/favorite-user';
		$data['p_favorite_user'] = $this->manager_user_model->p_favorite_user($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();	
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_favorite_user", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_favorite_user', $data, __CLASS__);
	}
	public function fn_list_order()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-list-order';
		$this->load->library('pagination');
		if($_SESSION['num_page']!="")
		{
			$config['per_page'] = $_SESSION['num_page'];
			$data['num_page'] = $_SESSION['num_page'];
		}
		else
		{
			$config['per_page'] = 10;
			$data['num_page'] = 10;
		}
		$config['total_rows'] = count($this->manager_user_model->p_list_order($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<span class="current">';
		$config['cur_tag_close'] =  '</span>';
		$config['base_url'] =  base_url().'/mypage/list-order';
		$data['p_list_order'] = $this->manager_user_model->p_list_order($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();	
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_list_order", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_list_order', $data, __CLASS__);
	}
	public function user_config()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['load_user_config'] = $this->manager_user_model->p_user_config($user_id);
		$old_img = $data['load_user_config']['user_avatar'];
		$old_img_banner = $data['load_user_config']['user_banner'];
		$get_country_id = $data['load_user_config']['country_id'];
		$data['load_list_state'] = $this->manager_user_model->load_list_state($get_country_id)->result();
		$data['active_menu_sidebar'] = 'mypage-user-config';
		$data['notice_update']="";
		//echo getcwd();
		$path_upload = str_replace('application/controllers','publics/avatar',dirname( __FILE__ ));
		$path_upload_banner = str_replace('application/controllers','publics/banner',dirname( __FILE__ ));
		//echo $path_upload;
		if(isset($_REQUEST['submit']))
			{	
				$data['load_user_config']['user_firstname'] = $_POST['firstname'];
				$data['load_user_config']['notice_luxyart'] = $_POST['check_notice'];
				$data['load_user_config']['user_lastname'] = $_POST['lastname'];
				$data['load_user_config']['user_zipcode'] = $_POST['zipcode'];
				$data['load_user_config']['state_id'] = $_POST['state_select'];
				$data['load_user_config']['cityname'] = $_POST['cityname'];
				$data['load_user_config']['user_address'] = $_POST['address'];
				$data['load_user_config']['user_address_building'] = $_POST['addressbuilding'];
				$data['load_user_config']['user_introduction'] = $_POST['mota'];
				if($this->lang_id == 1){
					$form_lang = "japan";
				}
				else if($this->lang_id == 2){
					$form_lang = "vietnam";
				}
				else if($this->lang_id == 3){
					$form_lang = "english";
				}
				$this->load->library('form_validation',$form_lang);
				$this->config->load('config', TRUE);
				$this->config->set_item('language', $form_lang); 
				$rules=array(
					array(
						'field'=>'firstname',
						'label'=>'First name',
						'rules'=>'trim|required'
					),
					array(
							'field'=>'lastname',
							'label'=>'Last name',
							'rules'=>'trim|required'
						),
					array(
							'field'=>'zipcode',
							'label'=>'Zip code',
							'rules'=>'trim|required'
						),
					array(
							'field'=>'photo_file',
							'label'=>'Avatar',
							'rules'=>'callback_file_selected_check'
						),
					array(
							'field'=>'banner_file',
							'label'=>'User banner',
							'rules'=>'callback_file_selected_check_banner'
						),
						array(
							'field'=>'cityname',
							'label'=>'City',
							'rules'=>'trim|required'
						),
						array(
							'field'=>'address',
							'label'=>'Address',
							'rules'=>'trim|required'
						),
						array(
							'field'=>'mota',
							'label'=>'User introduction',
							'rules'=>'trim|required'
						),
						array(
							'field'=>'addressbuilding',
							'label'=>'Address building',
							'rules'=>'trim|required'
						),
				);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					if (!empty($_FILES['photo_file']['name'])) 
					{
						$config['upload_path'] = $path_upload;
						$config['allowed_types'] = 'jpg|jpeg|png|gif';
						$config['file_name'] = time().str_replace('-','',$_FILES['photo_file']['name']);
				
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
				
						if ($this->upload->do_upload('photo_file')) {
						  $uploadData = $this->upload->data();
						  $file_new_image = $uploadData['file_name'];
						  unlink($path_upload.'/'.$old_img); 
						} 
						else
						{
							$file_new_image ='';
						}
				  	}
					if (!empty($_FILES['banner_file']['name'])) 
					{
						$config['upload_path'] = $path_upload_banner;
						$config['allowed_types'] = 'jpg|jpeg|png|gif';
						$config['file_name'] = time().str_replace('-','',$_FILES['banner_file']['name']);
				
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
				
						if ($this->upload->do_upload('banner_file')) {
						  $uploadData = $this->upload->data();
						  $file_new_image_banner = $uploadData['file_name'];
						  unlink($path_upload_banner.'/'.$old_img_banner); 
						} 
						else
						{
							$file_new_image_banner ='';
						}
				  	}
					$data_update = array(
					'user_firstname'=>$this->input->post('firstname'),
					'user_lastname'=>$this->input->post('lastname'),
					'user_zipcode'=>$this->input->post('zipcode'),
					'state_id'=>$this->input->post('state_select'),
					'cityname'=>$this->input->post('cityname'),
					'user_address'=>$this->input->post('address'),
					'user_avatar'=>$file_new_image,
					'user_banner'=>$file_new_image_banner,
					'user_address_building'=>$this->input->post('addressbuilding'),
					'user_introduction'=>$this->input->post('mota'),
					'notice_luxyart'=>$this->input->post('check_notice')
					);
					$this->manager_user_model->update_user_config($user_id,$data_update);
					$data['notice_update']="Edit User Success";
					$addNotice = array();
					$addNotice['type'] 						= "user-update-config";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_send_id'] 				= -1;
					// nguoi nhan msg
					$addNotice['user_id'] 					= $user_id;
					$this->function_model->addNotice($addNotice);
				}
			}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_config", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_config', $data, __CLASS__);
	}
	public function user_update_creator()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-user-update-creator';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_update_creator", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_update_creator', $data, __CLASS__);
	}
	public function user_update_gold()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$setting = $this->function_model->get_setting();
		$query = $this->db->query("Select user_point,user_paymoney_getpoint FROM luxyart_tb_user where user_id=$user_id");
		$row = $query->row();
		if (isset($row))
		{
			$total_point  = $row->user_point + $row->user_paymoney_getpoint;
			if($total_point >= $setting['point_update_level_user_gold'])
			{
				$data['show_buy_point'] = 0;
				$data['point_user'] = $total_point;
				$data['point_gold'] = $setting['point_update_level_user_gold'];
			}
			else
			{
				$data['show_buy_point'] = 1;
				$data['point_user'] = $total_point;
				$data['point_gold'] = $setting['point_update_level_user_gold'];
			}
		}
		else
		{
			$data['show_buy_point'] = 1;
			$data['point_user'] = 0;
			$data['point_gold'] = $setting['point_update_level_user_gold'];
		}
		$data['active_menu_sidebar'] = 'mypage-user-update-gold';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_update_gold", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_update_gold', $data, __CLASS__);
	}
	public function user_update_platinum()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$setting = $this->function_model->get_setting();
		$query = $this->db->query("Select user_point,user_paymoney_getpoint FROM luxyart_tb_user where user_id=$user_id");
		$row = $query->row();
		if (isset($row))
		{
			$total_point  = $row->user_point + $row->user_paymoney_getpoint;
			if($total_point >= $setting['point_update_level_user_platinum'])
			{
				$data['show_buy_point'] = 0;
				$data['point_user'] = $total_point;
				$data['point_platinum'] = $setting['point_update_level_user_platinum'];
			}
			else
			{
				$data['show_buy_point'] = 1;
				$data['point_user'] = $total_point;
				$data['point_platinum'] = $setting['point_update_level_user_platinum'];
			}
		}
		else
		{
			$data['show_buy_point'] = 1;
			$data['point_user'] = 0;
			$data['point_platinum'] = $setting['point_update_level_user_platinum'];
		}
		$data['active_menu_sidebar'] = 'mypage-user-update-platinum';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_update_platinum", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_update_platinum', $data, __CLASS__);
	}
	public function user_update_enterprise()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$setting = $this->function_model->get_setting();
		$query = $this->db->query("Select user_point,user_paymoney_getpoint FROM luxyart_tb_user where user_id=$user_id");
		$row = $query->row();
		if (isset($row))
		{
			$total_point  = $row->user_point + $row->user_paymoney_getpoint;
			if($total_point >= $setting['point_update_level_user_enterprise'])
			{
				$data['show_buy_point'] = 0;
				$data['point_user'] = $total_point;
				$data['point_enterprise'] = $setting['point_update_level_user_enterprise'];
			}
			else
			{
				$data['show_buy_point'] = 1;
				$data['point_user'] = $total_point;
				$data['point_enterprise'] = $setting['point_update_level_user_enterprise'];
			}
		}
		else
		{
			$data['show_buy_point'] = 1;
			$data['point_user'] = 0;
			$data['point_enterprise'] = $setting['point_update_level_user_enterprise'];
		}
		$data['active_menu_sidebar'] = 'mypage-user-update-enterprise';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_update_enterprise", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_update_enterprise', $data, __CLASS__);
	}
	public function notice_user_update_level()
	{
		$level_account = $this->uri->segment(3);
		if($level_account=='creator')
		{
			$data['level'] = 'Creator';
			$data['error'] = '0';
					
		}else if($level_account=='gold'){
			$data['level'] = 'Gold';
			$data['error'] = '0';	
		}
		else if($level_account=='platinum'){
			$data['level'] = 'Platinum';
			$data['error'] = '0';
		}
		else if($level_account=='enterprise'){
			$data['level'] = 'Enterprise';
			$data['error'] = '0';
		}
		else
		{
			$data['level'] = '';
			$data['error'] = '1';
		}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_update_level_notice", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_update_level_notice', $data, __CLASS__);
		
	}
	public function add_session_return_update_level()
	{
		$level_update = $_POST['level_update'];
		if($level_update==3)
		{
			$this->session->set_userdata('return_url_update_level', 'mypage/user-update-gold.html');
		}
		else if ($level_update==4)
		{
			$this->session->set_userdata('return_url_update_level', 'mypage/user-update-platinum.html');
		}
		else if ($level_update==5)
		{
			$this->session->set_userdata('return_url_update_level', 'mypage/user-update-enterprise.html');
		}
		else
		{
			$this->session->set_userdata('return_url_update_level', '');
		}
		echo "1{tieptuc_do_update}";exit();
	}
	public function do_user_update_account()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$email = $logged_in['user_email'];
		$type_account = $_POST['type_account'];
		if($type_account=='2')
		{
			$level = 'Creator';
			$data_update = array('user_level'=>2);
			$this->manager_user_model->update_user_config($user_id,$data_update);
			if ($this->db->affected_rows() == '1')
			{
				// set new level
				$this->session->userdata['logged_in']['user_level'] = 2;
				$this->session->set_userdata('logged_in', $this->session->userdata['logged_in']);
				// add notice thong bao in user page
				$addNotice = array();
				$addNotice['type'] 						= "user-update-level";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_send_id'] 				= -1;
					// nguoi nhan msg
				$addNotice['user_id'] 					= $user_id;
				$addNotice['account_level'] 			= $level;
				$this->function_model->addNotice($addNotice);
				// send email notice
				$user_name = explode('@',$email);
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "user_update_level_send_email";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $email;
				$dataSendEmail["name"] 					= $user_name[0];
				$dataSendEmail["link"] 					= base_url()."mypage.html";
				$dataSendEmail["username"] 				= $user_name[0];
				$dataSendEmail["account_level"]			= $level;
				$this->function_model->sendMailHTML($dataSendEmail);
    			echo "1{return_do_user_update_account}creator";exit();
			}
			else
			{
				echo "0{return_do_user_update_account}creator";exit();
			}
			
		}
		if($type_account=='3')
		{
			$level = 'Gold';
			$setting = $this->function_model->get_setting();
			$data_update = array('user_level'=>3);
			$this->manager_user_model->update_user_config($user_id,$data_update);
			$query = $this->db->query("Select user_point,user_paymoney_getpoint FROM luxyart_tb_user where user_id=$user_id");
			$row = $query->row();
			$money_buy_point = $row->user_paymoney_getpoint;
			$point_update_record_2 = $setting['point_update_level_user_gold'] - $money_buy_point;
			if ($this->db->affected_rows() == '1')
			{
				if($point_update_record_2 > 0)
				{
					$this->db->set('user_point', 'user_point - ' .$point_update_record_2, FALSE);
					$this->db->set('user_paymoney_getpoint', 0);
				}
				else
				{
					$this->db->set('user_paymoney_getpoint', 'user_paymoney_getpoint - ' .$setting['point_update_level_user_gold'], FALSE);
				}
				$this->db->where('user_id', $user_id);
    			$this->db->update('luxyart_tb_user');
				$insert_data_point = array(
					'user_id' 						=> $user_id,
					'point' 						=> $setting['point_update_level_user_gold'],
					'status_change_point' 			=> 2,
					'content' 						=> "Update level account",
					'inbox_id' 						=> $this->function_model->get_inbox_templates_id("user-update-level"),
					'date_add' 						=> date('Y-m-d H:i:s'),
				);
				$this->db->insert('luxyart_tb_management_point', $insert_data_point);
				// set new level
				$this->session->userdata['logged_in']['user_level'] = 3;
				$this->session->set_userdata('logged_in', $this->session->userdata['logged_in']);
				// add notice thong bao in user page
				$addNotice = array();
				$addNotice['type'] 						= "user-update-level";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_send_id'] 				= -1;
					// nguoi nhan msg
				$addNotice['user_id'] 					= $user_id;
				$addNotice['account_level'] 			= $level;
				$this->function_model->addNotice($addNotice);
				// send email notice
				$user_name = explode('@',$email);
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "user_update_level_send_email";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $email;
				$dataSendEmail["name"] 					= $user_name[0];
				$dataSendEmail["link"] 					= base_url()."mypage.html";
				$dataSendEmail["username"] 				= $user_name[0];
				$dataSendEmail["account_level"]			= $level;
				$this->function_model->sendMailHTML($dataSendEmail);
    			echo "1{return_do_user_update_account}gold";exit();
			}
			else
			{
				echo "0{return_do_user_update_account}gold";exit();
			}
			
		}
		if($type_account=='4')
		{
			$level = 'Platinum';
			$setting = $this->function_model->get_setting();
			$data_update = array('user_level'=>4);
			$this->manager_user_model->update_user_config($user_id,$data_update);
			$query = $this->db->query("Select user_point,user_paymoney_getpoint FROM luxyart_tb_user where user_id=$user_id");
			$row = $query->row();
			$money_buy_point = $row->user_paymoney_getpoint;
			$point_update_record_2 = $setting['point_update_level_user_platinum'] - $money_buy_point;
			if ($this->db->affected_rows() == '1')
			{
				if($point_update_record_2 > 0)
				{
					$this->db->set('user_point', 'user_point - ' .$point_update_record_2, FALSE);
					$this->db->set('user_paymoney_getpoint', 0);
				}
				else
				{
					$this->db->set('user_paymoney_getpoint', 'user_paymoney_getpoint - ' .$setting['point_update_level_user_platinum'], FALSE);
				}
				
    			$this->db->where('user_id', $user_id);
    			$this->db->update('luxyart_tb_user');
				$insert_data_point = array(
					'user_id' 						=> $user_id,
					'point' 						=> $setting['point_update_level_user_platinum'],
					'status_change_point' 			=> 2,
					'content' 						=> "Update level account",
					'inbox_id' 						=> $this->function_model->get_inbox_templates_id("user-update-level"),
					'date_add' 						=> date('Y-m-d H:i:s'),
				);
				$this->db->insert('luxyart_tb_management_point', $insert_data_point);
				// set new level
				$this->session->userdata['logged_in']['user_level'] = 4;
				$this->session->set_userdata('logged_in', $this->session->userdata['logged_in']);
				// add notice thong bao in user page
				$addNotice = array();
				$addNotice['type'] 						= "user-update-level";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_send_id'] 				= -1;
					// nguoi nhan msg
				$addNotice['user_id'] 					= $user_id;
				$addNotice['account_level'] 			= $level;
				$this->function_model->addNotice($addNotice);
				// send email notice
				$user_name = explode('@',$email);
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "user_update_level_send_email";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $email;
				$dataSendEmail["name"] 					= $user_name[0];
				$dataSendEmail["link"] 					= base_url()."mypage.html";
				$dataSendEmail["username"] 				= $user_name[0];
				$dataSendEmail["account_level"]			= $level;
				$this->function_model->sendMailHTML($dataSendEmail);
    			echo "1{return_do_user_update_account}platinum";exit();
			}
			else
			{
				echo "0{return_do_user_update_account}platinum";exit();
			}
			
		}
		if($type_account=='5')
		{
			$level = 'Enterprise';
			$setting = $this->function_model->get_setting();
			$data_update = array('user_level'=>5);
			$this->manager_user_model->update_user_config($user_id,$data_update);
			$query = $this->db->query("Select user_point,user_paymoney_getpoint FROM luxyart_tb_user where user_id=$user_id");
			$row = $query->row();
			$money_buy_point = $row->user_paymoney_getpoint;
			$point_update_record_2 = $setting['point_update_level_user_enterprise'] - $money_buy_point;
			if ($this->db->affected_rows() == '1')
			{
				if($point_update_record_2 > 0)
				{
					$this->db->set('user_point', 'user_point - ' .$point_update_record_2, FALSE);
					$this->db->set('user_paymoney_getpoint', 0);
				}
				else
				{
					$this->db->set('user_paymoney_getpoint', 'user_paymoney_getpoint - ' .$setting['point_update_level_user_enterprise'], FALSE);
				}
    			$this->db->where('user_id', $user_id);
    			$this->db->update('luxyart_tb_user');
				$insert_data_point = array(
					'user_id' 						=> $user_id,
					'point' 						=> $setting['point_update_level_user_enterprise'],
					'status_change_point' 			=> 2,
					'content' 						=> "Update level account",
					'inbox_id' 						=> $this->function_model->get_inbox_templates_id("user-update-level"),
					'date_add' 						=> date('Y-m-d H:i:s'),
				);
				$this->db->insert('luxyart_tb_management_point', $insert_data_point);
				// set new level
				$this->session->userdata['logged_in']['user_level'] = 5;
				$this->session->set_userdata('logged_in', $this->session->userdata['logged_in']);
				// add notice thong bao in user page
				$addNotice = array();
				$addNotice['type'] 						= "user-update-level";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_send_id'] 				= -1;
					// nguoi nhan msg
				$addNotice['user_id'] 					= $user_id;
				$addNotice['account_level'] 			= $level;
				$this->function_model->addNotice($addNotice);
				// send email notice
				$user_name = explode('@',$email);
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "user_update_level_send_email";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $email;
				$dataSendEmail["name"] 					= $user_name[0];
				$dataSendEmail["link"] 					= base_url()."mypage.html";
				$dataSendEmail["username"] 				= $user_name[0];
				$dataSendEmail["account_level"]			= $level;
				$this->function_model->sendMailHTML($dataSendEmail);
    			echo "1{return_do_user_update_account}enterprise";exit();
			}
			else
			{
				echo "0{return_do_user_update_account}enterprise";exit();
			}
			
		}
	}
	function file_selected_check()
	{
		
		if (empty($_FILES['photo_file']['name'])) 
		{
				return true;
		}
		else
		{		
				//jpg|jpeg|png|gif
				if($_FILES['photo_file']['type']=='image/jpg' || $_FILES['photo_file']['type']=='image/jpeg' || $_FILES['photo_file']['type']=='image/png' || $_FILES['photo_file']['type']=='image/gif')
				{
					return true;
				}
				else
				{
					if($this->lang_id == 1){
					$this->form_validation->set_message('file_selected_check', 'Ja Avatar image type must be jpg,jpeg,png,gif');
					}
					else if($this->lang_id == 2){
						$this->form_validation->set_message('file_selected_check', 'Vi Avatar image type must be jpg,jpeg,png,gif');
					}
					else if($this->lang_id == 3){
						$this->form_validation->set_message('file_selected_check', 'Avatar image type must be jpg,jpeg,png,gif');
					}
					return false;
				}
		}
	}
	function file_selected_check_banner()
	{
		
		if (empty($_FILES['banner_file']['name'])) 
		{
				return true;
		}
		else
		{		
				//jpg|jpeg|png|gif
				if($_FILES['banner_file']['type']=='image/jpg' || $_FILES['banner_file']['type']=='image/jpeg' || $_FILES['banner_file']['type']=='image/png' || $_FILES['banner_file']['type']=='image/gif')
				{
					return true;
				}
				else
				{
					if($this->lang_id == 1){
					$this->form_validation->set_message('file_selected_check_banner', 'Ja banner image type must be jpg,jpeg,png,gif');
					}
					else if($this->lang_id == 2){
						$this->form_validation->set_message('file_selected_check_banner', 'Vi banner image type must be jpg,jpeg,png,gif');
					}
					else if($this->lang_id == 3){
						$this->form_validation->set_message('file_selected_check_banner', 'Banner image type must be jpg,jpeg,png,gif');
					}
					return false;
				}
		}
	}
	public function email_exists($email)
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		$return = $this->manager_user_model->check_mail_exists($user_id,$email);
		if ($return == 1){
			if($this->lang_id == 1){
					$this->form_validation->set_message('email_exists', 'Ja Email already exists!. Please try another email');
				}
				else if($this->lang_id == 2){
					$this->form_validation->set_message('email_exists', 'Vi Email already exists!. Please try another email');
				}
				else if($this->lang_id == 3){
					$this->form_validation->set_message('email_exists', 'Email already exists!. Please try another email');
				}
			return false;
		}
	}
	public function user_email()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->helper('url');
		if(isset($_REQUEST['submit']))
			{
				if($this->lang_id == 1){
					$form_lang = "japan";
				}
				else if($this->lang_id == 2){
					$form_lang = "vietnam";
				}
				else if($this->lang_id == 3){
					$form_lang = "english";
				}
				$this->load->library('form_validation',$form_lang);
				$this->config->load('config', TRUE);
				$this->config->set_item('language', $form_lang);
				$rules=array(
						array(
							'field'=>'email',
							'label'=>'Email',
							'rules'=>'trim|required|valid_email|callback_email_exists'
						),
						array(
							'field'=>'xacnhanemail',
							'label'=>'Confirm email',
							'rules'=>'trim|required|valid_email|matches[email]'
						)
					);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					$data_update = array(
					'user_email'=>$this->input->post('email'),
					'is_active'=>0
					);
					$this->manager_user_model->update_user_config($user_id,$data_update);
					$query = $this->db->query("Select user_id_code_invite FROM luxyart_tb_user where user_id=$user_id");
					$row = $query->row();
					if (isset($row))
					{
						$user_code_invite = $row->user_id_code_invite;
					}
					$user_name = explode('@',$this->input->post('email'));
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "user_change_email";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $this->input->post('email');
					$dataSendEmail["name"] 					= $user_name[0];
					$dataSendEmail["link"] 					= base_url()."active-email-after-changed/".$user_code_invite;
					$dataSendEmail["username"] 				= $user_name[0];
					$dataSendEmail["email"] 				= $this->input->post('email');
					$this->function_model->sendMailHTML($dataSendEmail);
					$sess_data = array(
					'user_email' 		=> $this->input->post('email'),					
					);
					$this->session->userdata['logged_in']['user_email'] = $this->input->post('email');
					$this->session->set_userdata('logged_in', $this->session->userdata['logged_in']);
					redirect(base_url('mypage/user-email-comp.html'), 'location', 301);
					
				}
			}
		$data['active_menu_sidebar'] = 'mypage-user-email';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_email", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_email', $data, __CLASS__);
	}
	public function user_email_comp()
	{
		$data['active_menu_sidebar'] = 'mypage-user-email';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_email_comp", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_email_comp', $data, __CLASS__);
	}
	public function password_exists($password)
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		$return = $this->manager_user_model->check_pass_exists($user_id,$password);
		if ($return == 0){
				if($this->lang_id == 1){
					$this->form_validation->set_message('password_exists', 'Ja Current password is invalid!.');
				}
				else if($this->lang_id == 2){
					$this->form_validation->set_message('password_exists', 'Vi Current password is invalid!.');
				}
				else if($this->lang_id == 3){
					$this->form_validation->set_message('password_exists', 'Current password is invalid!.');
				}
			
			return false;
		}
	}
	public function user_pass()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->helper('url');
		if(isset($_REQUEST['submit']))
			{
				if($this->lang_id == 1){
					$form_lang = "japan";
				}
				else if($this->lang_id == 2){
					$form_lang = "vietnam";
				}
				else if($this->lang_id == 3){
					$form_lang = "english";
				}
				$this->load->library('form_validation',$form_lang);
				$this->config->load('config', TRUE);
				$this->config->set_item('language', $form_lang);
				$rules=array(
						array(
							'field'=>'curent_pass',
							'label'=>'Current password',
							'rules'=>'trim|required|callback_password_exists'
						),
						array(
							'field'=>'new_pass',
							'label'=>'New password',
							'rules'=>'trim|required|min_length[8]|alpha_numeric'
						),
						array(
							'field'=>'xacnhanpass',
							'label'=>'Confirm new password',
							'rules'=>'trim|required|matches[new_pass]'
						)
					);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					$data_update = array('user_pass'=>md5($this->input->post('new_pass')));
					$this->manager_user_model->update_user_config($user_id,$data_update);
					$query = $this->db->query("Select user_email FROM luxyart_tb_user where user_id=$user_id");
					$row = $query->row();
					if (isset($row))
					{
						$email = $row->user_email;
					}
					$user_name = explode('@',$email);
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "remind-password";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $email;
					$dataSendEmail["name"] 					= $user_name[0];
					$dataSendEmail["email"] 				= $email;
					$dataSendEmail["password"] 				= $this->input->post('new_pass');
					$this->function_model->sendMailHTML($dataSendEmail);
					redirect(base_url('mypage/user-password-comp.html'), 'location', 301);
				}
			}
		$data['active_menu_sidebar'] = 'mypage-user-pass';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_pass", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_pass', $data, __CLASS__);
	}
	public function user_pass_comp()
	{
		$data['active_menu_sidebar'] = 'mypage-user-pass';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_pass_comp", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_pass_comp', $data, __CLASS__);
	}
	public function user_leave()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-user-leave';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_leave", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_leave', $data, __CLASS__);
	}
	public function check_password_leave()
	{
		
		$password=$_POST['password'];
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo $password."{return_checkpassword}0";exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$query = $this->db->query('SELECT user_id FROM luxyart_tb_user WHERE user_pass="'.md5($password).'" and user_id="'.$user_id.'"');
		if($query->num_rows() > 0)
		{
			echo $password."{return_checkpassword}1";exit;
		}
		else
		{
			echo $password."{return_checkpassword}0";exit;
		}
		$query->free_result();
	}
	public function do_user_leave()
	{
		$password=$_POST['password'];
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo $password."{return_checkpassword}0";exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$email = $logged_in['user_email'];
		$user_name = explode('@',$logged_in['user_email']);
		$query = $this->db->query('SELECT user_id FROM luxyart_tb_user WHERE user_pass="'.md5($password).'" and user_id="'.$user_id.'"');
		if($query->num_rows() > 0)
		{
			$data_update = array('user_status'=>2);
			$this->manager_user_model->update_user_config($user_id,$data_update);
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "user-leave";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $email;
			$dataSendEmail["name"] 					= $user_name[0];
			$this->function_model->sendMailHTML($dataSendEmail);
			echo $password."{return_check_do_user_leave}1";
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
			if($key!='admin_id' && $key!='logged_in_admin' && $key!='admin_role' && $key!='admin_status')
				{
				 $this->session->unset_userdata($key);
				}
			}			
			exit;
		}
		else
		{
			echo $password."{return_check_do_user_leave}0";exit;
		}
		$query->free_result();
	}
	public function user_leave_comp()
	{
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/notice_user_leave", $meta, $this->lang_id);
		$this->template->load('default', 'notice_user_leave', $data, __CLASS__);
	}
	public function notice(){
		
		$data['return_title'] 		= $_SESSION['return_title'];
		$data['return_msg'] 		= $_SESSION['return_msg'];
		$data['return_link'] 		= $_SESSION['return_link'];
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/notice", $meta, $this->lang_id);
		$this->template->load('default', 'notice', $data, __CLASS__);
	}
	public function credit_select()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$session_return_url = $this->session->userdata['return_url_update_level'];
		
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$get_id_package = $this->uri->segment(2);
		$this->load->model('manager_user_model');
		//check package
		$query = $this->db->query('SELECT * FROM luxyart_tb_list_credit WHERE status_credit = 0 and id_list="'.$get_id_package.'"');
		if($query->num_rows() > 0)
		{
			$data['error_xuly']=1;
			$row = $query->row();
			$data['point_credit']= $row->point_credit;
			$data['money_pay_credit']= $row->money_pay_credit;
			if(isset($_POST['payment']))
			{
				if($_POST['payment']=='new_card')
				{
					//get user info
					$query_user_info = $this->db->query('SELECT * FROM luxyart_tb_user,luxyart_tb_country WHERE luxyart_tb_user.user_status = 1 and luxyart_tb_user.country_id = luxyart_tb_country.country_id and luxyart_tb_user.user_id="'.$user_id.'"');
					$row_user = $query_user_info->row();
					// Load helpers
					$this->load->helper('url');
					// Load PayPal library
					$this->config->load('paypal');
					$config = array(
						'Sandbox' => $this->config->item('Sandbox'), 			// Sandbox / testing mode option.
						'APIUsername' => $this->config->item('APIUsername'), 	// PayPal API username of the API caller
						'APIPassword' => $this->config->item('APIPassword'), 	// PayPal API password of the API caller
						'APISignature' => $this->config->item('APISignature'), 	// PayPal API signature of the API caller
						'APISubject' => '', 									// PayPal API subject (email address of 3rd party user that has granted API permission for your app)
						'APIVersion' => $this->config->item('APIVersion')		// API version you'd like to use for your call.  You can set a default version in the class and leave this blank if you want.
					);
					// Show Errors
					if($config['Sandbox'])
					{
						error_reporting(E_ALL);
						ini_set('display_errors', '1');
					}
					$this->load->library('paypal/Paypal_pro', $config);
					$DPFields = array(
										'paymentaction' => 'Sale', 						// How you want to obtain payment.  Authorization indidicates the payment is a basic auth subject to settlement with Auth & Capture.  Sale indicates that this is a final sale for which you are requesting payment.  Default is Sale.
										'ipaddress' => $_SERVER['REMOTE_ADDR'], 							// Required.  IP address of the payer's browser.
										'returnfmfdetails' => '1' 					// Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
									);
									
					$CCDetails = array(
										'creditcardtype' => $_POST['card_type'], 					// Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
										'acct' => $_POST['cc-number'], 								// Required.  Credit card number.  No spaces or punctuation.  
										'expdate' => $_POST['cc-exp-month'].'20'.$_POST['cc-exp-year'], 							// Required.  Credit card expiration date.  Format is MMYYYY
										'cvv2' => $_POST['cc-cvc'], 								// Requirements determined by your PayPal account settings.  Security digits for credit card.
										'startdate' => '', 							// Month and year that Maestro or Solo card was issued.  MMYYYY
										'issuenumber' => ''							// Issue number of Maestro or Solo card.  Two numeric digits max.
									);
									
					$PayerInfo = array(
										'email' => $row_user->user_email, 								// Email address of payer.
										'payerid' => '', 							// Unique PayPal customer ID for payer.
										'payerstatus' => '', 						// Status of payer.  Values are verified or unverified
										'business' => '' 							// Payer's business name.
									);
									
					$PayerName = array(
										'salutation' => '', 						// Payer's salutation.  20 char max.
										'firstname' => $row_user->user_firstname, 							// Payer's first name.  25 char max.
										'middlename' => '', 						// Payer's middle name.  25 char max.
										'lastname' => $row_user->user_lastname, 							// Payer's last name.  25 char max.
										'suffix' => ''								// Payer's suffix.  12 char max.
									);
									
					/*$BillingAddress = array(
											'street' => $row_user->user_address, 						// Required.  First street address.
											'street2' => '', 						// Second street address.
											'city' => $row_user->cityname, 							// Required.  Name of City.
											'state' => $row_user->state_name, 							// Required. Name of State or Province.
											'countrycode' => $row_user->country_code, 					// Required.  Country code.
											'zip' => $row_user->user_zipcode, 							// Required.  Postal code of payer.
											'phonenum' => '' 						// Phone Number of payer.  20 char max.
										);*/
										
					/*$ShippingAddress = array(
											'shiptoname' => $row_user->user_firstname.' '.$row_user->user_lastname, 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
											'shiptostreet' => $row_user->user_address, 					// Required if shipping is included.  First street address.  100 char max.
											'shiptostreet2' => '', 					// Second street address.  100 char max.
											'shiptocity' => $row_user->cityname, 					// Required if shipping is included.  Name of city.  40 char max.
											'shiptostate' => $row_user->state_name, 					// Required if shipping is included.  Name of state or province.  40 char max.
											'shiptozip' => $row_user->user_zipcode, 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
											'shiptocountry' => $row_user->country_code, 					// Required if shipping is included.  Country code of shipping address.  2 char max.
											'shiptophonenum' => ''					// Phone number for shipping address.  20 char max.
											);*/
										
					$PaymentDetails = array(
											'amt' => $row->money_pay_credit.'.00', 							// Required.  Total amount of order, including shipping, handling, and tax.  
											'currencycode' => 'USD', 					// Required.  Three-letter currency code.  Default is USD.
											'itemamt' => $row->money_pay_credit.'.00', 						// Required if you include itemized cart details. (L_AMTn, etc.)  Subtotal of items not including S&H, or tax.
											'shippingamt' => '0.00', 					// Total shipping costs for the order.  If you specify shippingamt, you must also specify itemamt.
											'shipdiscamt' => '', 					// Shipping discount for the order, specified as a negative number.  
											'handlingamt' => '', 					// Total handling costs for the order.  If you specify handlingamt, you must also specify itemamt.
											'taxamt' => '', 						// Required if you specify itemized cart tax details. Sum of tax for all items on the order.  Total sales tax. 
											'desc' => 'Order point package', 							// Description of the order the customer is purchasing.  127 char max.
											'custom' => '', 						// Free-form field for your own use.  256 char max.
											'invnum' => '', 						// Your own invoice or tracking number
											'notifyurl' => ''						// URL for receiving Instant Payment Notifications.  This overrides what your profile is set to use.
										);	
							
					$OrderItems = array();
					$Item	 = array(
										'l_name' => 'Point Package '.$get_id_package, 						// Item Name.  127 char max.
										'l_desc' => 'Order point package '.$get_id_package, 						// Item description.  127 char max.
										'l_amt' => $row->money_pay_credit.'.00', 							// Cost of individual item.
										'l_number' => 'Package'.$row->point_credit, 						// Item Number.  127 char max.
										'l_qty' => '1', 							// Item quantity.  Must be any positive integer.  
										'l_taxamt' => '', 						// Item's sales tax amount.
										'l_ebayitemnumber' => '', 				// eBay auction number of item.
										'l_ebayitemauctiontxnid' => '', 		// eBay transaction ID of purchased item.
										'l_ebayitemorderid' => '' 				// eBay order ID for the item.
								);
					array_push($OrderItems, $Item);
					
					$Secure3D = array(
									  'authstatus3d' => '', 
									  'mpivendor3ds' => '', 
									  'cavv' => '', 
									  'eci3ds' => '', 
									  'xid' => ''
									  );
									  
					$PayPalRequestData = array(
											'DPFields' => $DPFields, 
											'CCDetails' => $CCDetails, 
											'PayerInfo' => $PayerInfo, 
											'PayerName' => $PayerName, 
											//'BillingAddress' => $BillingAddress, 
											//'ShippingAddress' => $ShippingAddress, 
											'PaymentDetails' => $PaymentDetails, 
											'OrderItems' => $OrderItems, 
											'Secure3D' => $Secure3D
										);
										
					$PayPalResult = $this->paypal_pro->DoDirectPayment($PayPalRequestData);
					if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
					{
						$data['card_pay'] = 0;
						$data['Errors'] = $PayPalResult['ERRORS'];
						
					}
					else
					{
						// Successful call.  Load view or whatever you need to do here.
						$data['card_pay'] = 1;
						$this->db->set('user_paymoney_getpoint', 'user_paymoney_getpoint + ' .$row->point_credit, FALSE);
						//$this->db->set('user_point', 'user_point + ' .$row->point_credit, FALSE);
    					$this->db->where('user_id', $user_id);
    					$this->db->update('luxyart_tb_user');
						$insert_data_point = array(
							'user_id' 						=> $user_id,
							'point' 						=> $row->point_credit,
							'status_change_point' 			=> 1,
							'content' 						=> "Buy Package Point",
							'inbox_id' 						=> $this->function_model->get_inbox_templates_id("user-buy-point-package"),
							'date_add' 						=> date('Y-m-d H:i:s'),
						);
						$this->db->insert('luxyart_tb_management_point', $insert_data_point);
						$insert_data_order_list = array(
							'user_id' 						=> $user_id,
							'id_list' 						=> $get_id_package,
							'payment_menthod' 				=> 2,
							'payment_status' 			=> 1,
							'date_order_credit' 		=> date('Y-m-d H:i:s'),
						);
						$this->db->insert('luxyart_tb_user_order_credit', $insert_data_order_list);
						$query = $this->db->query('SELECT card_id FROM luxyart_user_card WHERE account_number="'.$_POST['cc-number'].'" and user_id="'.$user_id.'"');
						if($query->num_rows() <= 0)
						{
							$insert_card_user = array(
											'user_id' 					=> $user_id,
											'account_number' 			=> $_POST['cc-number'],
											'account_ccv_value' 		=> $_POST['cc-cvc'],
											'account_exp' 				=> $_POST['cc-exp-month'].'20'.$_POST['cc-exp-year'],
											'account_type_card' 		=> $_POST['card_type'],
											'date_add_card' 			=> date('Y-m-d H:i:s'),
										);
							$this->db->insert('luxyart_user_card', $insert_card_user);
						}
						
						$email = $logged_in['user_email'];
						$user_name = explode('@',$logged_in['user_email']);
						$dataSendEmail = array();
						$dataSendEmail["name_email_code"] 		= "user-buy-point-package";
						$dataSendEmail["lang_id"] 				= $this->lang_id;
						$dataSendEmail["email_to"] 				= $email;
						$dataSendEmail["name"] 					= $user_name[0];
						$dataSendEmail["point_package"]			= $row->point_credit;
						$this->function_model->sendMailHTML($dataSendEmail);
						if($session_return_url!='')
						{
							header("Location: ".base_url($session_return_url));
							$this->session->unset_userdata('return_url_update_level');
						}
						
						
					}
				}
				else if($_POST['payment']=='bit_coin')
				{
					echo 'bit_coin';
					exit();
				}
				else
				{
					echo $_POST['payment'];
					exit();
				}
			}
		}
		else
		{
			$data['error_xuly']=2;
		}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/credit_select", $meta, $this->lang_id);
		$this->template->load('default', 'user_credit_select', $data, __CLASS__);
	}
	function Do_direct_payment()
	{
		$DPFields = array(
							'paymentaction' => 'Sale', 						// How you want to obtain payment.  Authorization indidicates the payment is a basic auth subject to settlement with Auth & Capture.  Sale indicates that this is a final sale for which you are requesting payment.  Default is Sale.
							'ipaddress' => $_SERVER['REMOTE_ADDR'], 							// Required.  IP address of the payer's browser.
							'returnfmfdetails' => '1' 					// Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
						);
						
		$CCDetails = array(
							'creditcardtype' => 'MasterCard', 					// Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
							'acct' => '5424180818927383', 								// Required.  Credit card number.  No spaces or punctuation.  
							'expdate' => '102017', 							// Required.  Credit card expiration date.  Format is MMYYYY
							'cvv2' => '123', 								// Requirements determined by your PayPal account settings.  Security digits for credit card.
							'startdate' => '', 							// Month and year that Maestro or Solo card was issued.  MMYYYY
							'issuenumber' => ''							// Issue number of Maestro or Solo card.  Two numeric digits max.
						);
						
		$PayerInfo = array(
							'email' => 'test@domain.com', 								// Email address of payer.
							'payerid' => '', 							// Unique PayPal customer ID for payer.
							'payerstatus' => '', 						// Status of payer.  Values are verified or unverified
							'business' => 'Testers, LLC' 							// Payer's business name.
						);
						
		$PayerName = array(
							'salutation' => 'Mr.', 						// Payer's salutation.  20 char max.
							'firstname' => 'Tester', 							// Payer's first name.  25 char max.
							'middlename' => '', 						// Payer's middle name.  25 char max.
							'lastname' => 'Testerson', 							// Payer's last name.  25 char max.
							'suffix' => ''								// Payer's suffix.  12 char max.
						);
						
		$BillingAddress = array(
								'street' => '123 Test Ave.', 						// Required.  First street address.
								'street2' => '', 						// Second street address.
								'city' => 'Kansas City', 							// Required.  Name of City.
								'state' => 'MO', 							// Required. Name of State or Province.
								'countrycode' => 'US', 					// Required.  Country code.
								'zip' => '64111', 							// Required.  Postal code of payer.
								'phonenum' => '555-555-5555' 						// Phone Number of payer.  20 char max.
							);
							
		$ShippingAddress = array(
								'shiptoname' => 'Tester Testerson', 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
								'shiptostreet' => '123 Test Ave.', 					// Required if shipping is included.  First street address.  100 char max.
								'shiptostreet2' => '', 					// Second street address.  100 char max.
								'shiptocity' => 'Kansas City', 					// Required if shipping is included.  Name of city.  40 char max.
								'shiptostate' => 'MO', 					// Required if shipping is included.  Name of state or province.  40 char max.
								'shiptozip' => '64111', 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
								'shiptocountry' => 'US', 					// Required if shipping is included.  Country code of shipping address.  2 char max.
								'shiptophonenum' => '555-555-5555'					// Phone number for shipping address.  20 char max.
								);
							
		$PaymentDetails = array(
								'amt' => '100.00', 							// Required.  Total amount of order, including shipping, handling, and tax.  
								'currencycode' => 'USD', 					// Required.  Three-letter currency code.  Default is USD.
								'itemamt' => '95.00', 						// Required if you include itemized cart details. (L_AMTn, etc.)  Subtotal of items not including S&H, or tax.
								'shippingamt' => '5.00', 					// Total shipping costs for the order.  If you specify shippingamt, you must also specify itemamt.
								'shipdiscamt' => '', 					// Shipping discount for the order, specified as a negative number.  
								'handlingamt' => '', 					// Total handling costs for the order.  If you specify handlingamt, you must also specify itemamt.
								'taxamt' => '', 						// Required if you specify itemized cart tax details. Sum of tax for all items on the order.  Total sales tax. 
								'desc' => 'Web Order', 							// Description of the order the customer is purchasing.  127 char max.
								'custom' => '', 						// Free-form field for your own use.  256 char max.
								'invnum' => '', 						// Your own invoice or tracking number
								'notifyurl' => ''						// URL for receiving Instant Payment Notifications.  This overrides what your profile is set to use.
							);	
				
		$OrderItems = array();
		$Item	 = array(
							'l_name' => 'Test Widget 123', 						// Item Name.  127 char max.
							'l_desc' => 'The best test widget on the planet!', 						// Item description.  127 char max.
							'l_amt' => '95.00', 							// Cost of individual item.
							'l_number' => '123', 						// Item Number.  127 char max.
							'l_qty' => '1', 							// Item quantity.  Must be any positive integer.  
							'l_taxamt' => '', 						// Item's sales tax amount.
							'l_ebayitemnumber' => '', 				// eBay auction number of item.
							'l_ebayitemauctiontxnid' => '', 		// eBay transaction ID of purchased item.
							'l_ebayitemorderid' => '' 				// eBay order ID for the item.
					);
		array_push($OrderItems, $Item);
		
		$Secure3D = array(
						  'authstatus3d' => '', 
						  'mpivendor3ds' => '', 
						  'cavv' => '', 
						  'eci3ds' => '', 
						  'xid' => ''
						  );
						  
		$PayPalRequestData = array(
								'DPFields' => $DPFields, 
								'CCDetails' => $CCDetails, 
								'PayerInfo' => $PayerInfo, 
								'PayerName' => $PayerName, 
								'BillingAddress' => $BillingAddress, 
								'ShippingAddress' => $ShippingAddress, 
								'PaymentDetails' => $PaymentDetails, 
								'OrderItems' => $OrderItems, 
								'Secure3D' => $Secure3D
							);
							
		$PayPalResult = $this->paypal_pro->DoDirectPayment($PayPalRequestData);
		
		if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
		{
			$errors = array('Errors'=>$PayPalResult['ERRORS']);
			$this->load->view('paypal/samples/error',$errors);
		}
		else
		{
			// Successful call.  Load view or whatever you need to do here.
			$data = array('PayPalResult'=>$PayPalResult);
			$this->load->view('paypal/samples/do_direct_payment',$data);
		}
	}
	function Get_balance()
	{		
		$GBFields = array('returnallcurrencies' => '1');
		$PayPalRequestData = array('GBFields'=>$GBFields);
		$PayPalResult = $this->paypal_pro->GetBalance($PayPalRequestData);
		
		if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
		{
			$errors = array('Errors'=>$PayPalResult['ERRORS']);
			$this->load->view('paypal/samples/error',$errors);
		}
		else
		{
			// Successful call.  Load view or whatever you need to do here.
			$data = array('PayPalResult'=>$PayPalResult);
			$this->load->view('paypal/samples/get_balance',$data);
		}
	}
	public function credit()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		$data['list_buy_point'] = $this->manager_user_model->get_list_buy_point()->result();
		$query = $this->db->query('SELECT user_point FROM luxyart_tb_user WHERE user_status = 1 and user_id="'.$user_id.'"');
		$row = $query->row();
		if (isset($row))
		{
				$data['user_point'] = $row->user_point + $row->user_paymoney_getpoint;
		}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/credit", $meta, $this->lang_id);
		$this->template->load('default', 'user_credit', $data, __CLASS__);
	}
	public function user_message()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-user-message';
		$this->load->library('pagination');
		$config['per_page'] = 2;
		$config['total_rows'] = count($this->manager_user_model->list_user_message($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<span class="current">';
		$config['cur_tag_close'] =  '</span>';
		$config['base_url'] =  base_url().'/mypage/user-message';
		$data['list_user_message'] = $this->manager_user_model->list_user_message($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/user_message", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_message', $data, __CLASS__);
	}
	public function do_read_all_message()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo "0{return_do_read_all}0";exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$list_items = explode(",",$_POST['list_items']);
		$this->db->set('ms_is_read', 1);  
		$this->db->where('user_id', $user_id);
		$this->db->where_in('id_message', $list_items);  
		$this->db->update('luxyart_tb_user_message');
		echo "1{return_do_read_all}".count($list_items);exit;
	}
	public function do_delete_all_message()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo "0{return_do_delete_all}0";exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$list_items = explode(",",$_POST['list_items']);
		$this->db->where('user_id', $user_id);
		$this->db->where_in('id_message', $list_items);
		$this->db->delete('luxyart_tb_user_message');
		echo "1{return_do_delete_all}".count($list_items);exit;
	}
	public function mypage_do_delete_follow_user()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo "0{return_do_delete_follow_user}0";exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$id_delete = $_POST['id_follow'];
		$this->db->where('follow_user_id', $user_id);
		$this->db->where('id_follow', $id_delete);
		$this->db->delete('luxyart_tb_user_follow');
		echo "1{return_do_delete_follow_user}";exit;
	}
	public function mypage_do_delete_msg()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo "0{return_do_delete_msg}0";exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$id_message = $_POST['id_msg'];
		$query = $this->db->query('SELECT * FROM luxyart_tb_user_message WHERE luxyart_tb_user_message.id_message = "'.$id_message.'" and luxyart_tb_user_message.user_id="'.$user_id.'" limit 1');
		$row = $query->row();
		if ($query->num_rows()>0)
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('id_message', $id_message);
			$this->db->delete('luxyart_tb_user_message');
			echo "1{return_do_delete_msg}";exit;
		}
		else
		{
			echo "0{return_do_delete_msg}0";exit;
		}
		
	}
	public function user_purchase_order()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data['active_menu_sidebar'] = 'mypage-user-purchase-order';
		$this->load->library('pagination');
		if($_SESSION['p_user_purchase_num_page']!="")
		{
			$config['per_page'] = $_SESSION['p_user_purchase_num_page'];
			$data['num_page'] = $_SESSION['p_user_purchase_num_page'];
		}
		else
		{
			$config['per_page'] = 10;
			$data['num_page'] = 10;
		}
		$config['per_page'] = 1;
		$config['total_rows'] = count($this->manager_user_model->list_purchase_order_point($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<span class="current">';
		$config['cur_tag_close'] =  '</span>';
		$config['base_url'] =  base_url().'/mypage/user-purchase-order';
		$data['list_purchase_order_point'] = $this->manager_user_model->list_purchase_order_point($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/user_purchase_order", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_purchase_order', $data, __CLASS__);
	}
	public function user_message_detail()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$id_message = $this->uri->segment(3);
		$data['active_menu_sidebar'] = 'mypage-user-message';
		$query = $this->db->query('SELECT * FROM luxyart_tb_user_message WHERE luxyart_tb_user_message.id_message = "'.$id_message.'" and luxyart_tb_user_message.user_id="'.$user_id.'" limit 1');
		$row = $query->row();
		if ($query->num_rows()>0)
		{
			$data['error_check'] = 0;
			$data['message_ms_subject'] = $row->ms_subject;
			$data['message_ms_content'] = $row->ms_content;
			$data['message_date_message'] = $row->date_message;
			$data['message_user_send_id'] = $row->user_send_id;
			$data['message_id_msg'] = $row->id_message;
			if($row->ms_is_read ==0)
			{
				$this->db->set('ms_is_read', 1);  
				$this->db->where('user_id', $user_id);
				$this->db->where('id_message', $id_message);  
				$this->db->update('luxyart_tb_user_message');
			}
		}
		else
		{
			$data['error_check'] = 1;
		}
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/user_message", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_message_detail', $data, __CLASS__);
	}
	public function do_ajax_get_list_order()
	{
		$num_page = $_POST['num_page'];
		if($num_page == 20){
			$_SESSION['num_page'] ='20';
		}
		else if($num_page == 10){
			$_SESSION['num_page'] ='10';
		}
		else if($num_page == 50){
			$_SESSION['num_page'] ='50';
		}
		else
		{
			$_SESSION['num_page'] ='10';
		}
		
	}
	public function do_ajax_get_user_purchase()
	{
		$num_page = $_POST['num_page'];
		if($num_page == 20){
			$_SESSION['p_user_purchase_num_page'] ='20';
		}
		else if($num_page == 10){
			$_SESSION['p_user_purchase_num_page'] ='10';
		}
		else if($num_page == 50){
			$_SESSION['p_user_purchase_num_page'] ='50';
		}
		else
		{
			$_SESSION['p_user_purchase_num_page'] ='10';
		}
		
	}
	
}