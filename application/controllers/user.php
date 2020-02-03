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
class User extends CI_Controller {
	public function __construct() {
		parent::__construct();
		@session_start();
		$this->load->library('session');
		$this->load->library('cart');
		//$this->load->model('main_model');
		$this->load->model('function_model');
		$this->load->model('manager_user_model');
		
		$this->load->helper('cookie');
		
		//language message
		$CI =& get_instance();    //do this only once in this file
		$CI->config->load();
		if(!isset($_SESSION['lang'])){
			$_SESSION['lang'] = "ja";
			
		}
		if($_SESSION['lang']=='english' || $_SESSION['lang']=='japan')
		{
			$_SESSION['lang']='ja';
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
	}
	public function check_email_register()
	{
		$email=$_POST['email'];
		//$query = $this->db->query('SELECT user_id,user_status FROM luxyart_tb_user WHERE user_email="'.$email.'"');
		//$rs = $this->db->get();
		$this->db->from('luxyart_tb_user');
		$this->db->select('user_id,user_status');
		$this->db->where(array('user_email' => $email));
		$rs = $this->db->get();
		$row = $rs->row();
		if($row->user_status==2)
		{
			echo $row->user_status.$email."{return_checkemail}2";exit;
		}
		else if($row->user_status==3)
		{
			echo $row->user_status.$email."{return_checkemail}3";exit;
		}
		else if($rs->num_rows() > 0)
		{
			
				echo $email."{return_checkemail}1";exit;
			
		}
		
		else
		{
			echo $email."{return_checkemail}0";exit;
		}
	}
	public function do_ajax_register_user()
	{
		session_start();
		// Create codeid invite user
		$bangkytuset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$code_repeat_1 = 1;
		$code_repeat_2 = 10;
		$dodaichuoicode = 15;
		// End Create codeid invite user
		$email=$_POST['email'];
		$user_name = $_POST['username'];
		$passord=$_POST['passord'];
		$has_notice=$_POST['has_notice'];
		$member_id=$_POST['member_id'];
		$email_invite=$_POST['email_invite'];
		$invite_member=$_POST['invite_member'];
		
		$date_register = date("Y-m-d H:i:s");
		$user_id_code_invite = substr(str_shuffle(str_repeat($bangkytuset, mt_rand($code_repeat_1,$code_repeat_2))),1,$dodaichuoicode);
		/// Tao ma cho user id
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
		
		$data = array('user_email'=>$email,'user_macode'=>$user_id_gancode,'display_name'=>$user_name,'user_pass'=>md5($passord),'country_id'=>$this->lang_id,'state_id'=>$state_id,'notice_luxyart'=>$has_notice,'user_level'=>1,'user_status'=>0,'user_dateregister'=>$date_register,'user_id_code_invite'=>$user_id_code_invite,'os_register'=>$os_register);
		$this->db->insert('luxyart_tb_user',$data);
		
		$user_id = $this->db->insert_id();
		
    	if($this->db->affected_rows() != 1)
		{
			echo $email."{return_do_register_user}1";
			exit;	
		}
		else
		{
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "register";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $email;
			$dataSendEmail["name"] 					= $user_name;
			$dataSendEmail["link"] 					= base_url()."active-email/".$user_id_code_invite;
			$dataSendEmail["username"] 				= $user_name;
			$dataSendEmail["email"] 				= $email;
			$dataSendEmail["password"] 				= $passord;
			$dataSendEmail["is_send_admin"] 		= 1;
			$this->function_model->sendMailHTML($dataSendEmail);
			
			if($invite_member > 0){
			
				$this->db->from('luxyart_tb_user');
				$this->db->select('*');
				$this->db->where(array('user_id'=>$invite_member));
					
				$rsUserMemberId = $this->db->get();
				$resultUserMemberId = $rsUserMemberId->row();
					
				$this->db->from('luxyart_tb_user');
				$this->db->select('*');
				$this->db->where(array('user_id'=>$user_id));
					
				$rsUserId = $this->db->get();
				$resultUserId = $rsUserId->row();
					
				$email_invite = $resultUserId->user_email;
				$user_macode = $resultUserMemberId->user_macode;
					
				$dataInsertMember = array(
						'user_id' 					=> $invite_member,
						'user_id_invited' 			=> $user_id,
						'email_invite' 				=> $email_invite,
						'user_code_invite_person' 	=> $user_macode,
						'count' 					=> 1,
						'date_create'		 		=> date('Y-m-d H:i:s'),
						'date_invite' 				=> date('Y-m-d H:i:s'),
						'status' 					=> 2
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
				
				$_SESSION['invite_member'] = "";
				$_SESSION['member_id'] = "";
				$_SESSION['email_invite'] = "";
				$_SESSION['return_key'] = 'user/accept_member_success';
				
				echo $email."{return_do_register_user}2";
			
			
			}
			elseif($member_id > 0){
				
				$this->db->from('luxyart_tb_member');
				$this->db->select('*');
				$this->db->where(array('member_id'=>$member_id));
			
				$rsMember = $this->db->get();
				$resultMember = $rsMember->row();
				$email_invite = $resultMember->email_invite;
					
				$this->db->where('member_id', $member_id);
				$this->db->set('user_id_invited',$user_id);
				$this->db->set('date_invite',date("Y-m-d H:i:s"));
				$this->db->set('status',2);
				$this->db->update('luxyart_tb_member');
			
				$this->db->from('luxyart_tb_user');
				$this->db->select('*');
				$this->db->where(array('user_id'=>$resultMember->user_id));
			
				$rsUserSend = $this->db->get();
				$resultUserSend = $rsUserSend->row();
			
				//send email
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "accept_member";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $resultUserSend->user_email;
				$dataSendEmail["name"] 					= ucwords($resultUserSend->display_name);
				$dataSendEmail["email_invite"] 			= $resultMember->email_invite;
				$dataSendEmail["link_invite"] 			= base_url()."mypage/listmember";
				//$dataSendEmail["is_debug"]			= 1;
			
				$this->function_model->sendMailHTML($dataSendEmail);
				
				$_SESSION['invite_member'] = "";
				$_SESSION['member_id'] = "";
				$_SESSION['email_invite'] = "";
				$_SESSION['return_key'] = 'user/accept_member_success';
				
				echo $email."{return_do_register_user}2";
					
			}
			else{
				
				echo $email."{return_do_register_user}0";
				
			}
			
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
		/// Tao ma cho user id
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
		$os_register = 3;
		$data = array('user_email'=>$email,'user_macode'=>$user_id_gancode,'display_name'=>$username,'user_pass'=>md5($passord),'country_id'=>$this->lang_id,'state_id'=>$state_id,'notice_luxyart'=>$has_notice,'user_level'=>2,'user_status'=>0,'user_dateregister'=>$date_register,'user_id_code_invite'=>$user_id_code_invite,'os_register'=>$os_register);
		$this->db->insert('luxyart_tb_user',$data);
		
		//add list img sell for img random
		$userid = $this->db->insert_id();
		$str_list_images = "";
		$list_images = $this->function_model->randomImage();
		
		foreach($list_images as $detail_images){
				
			$detail_image = explode("-",$detail_images);
				
			$id_img = $detail_image[0];
			$user_owner = $detail_image[1];
				
			$dataUserGetImgSell = array(
					'user_id' 		=> $userid,
					'id_img' 		=> $id_img,
					'user_owner' 	=> $user_owner,
					'date_get_img'	=> date('Y-m-d H:i:s')
			);
			$this->db->insert('luxyart_tb_user_get_img_sell', $dataUserGetImgSell);
				
			if($str_list_images == ""){
				$str_list_images .= $id_img;
			}
			else{
				$str_list_images .= ",".$id_img;
			}
				
		}
		
		if($str_list_images != ""){
			$dataUserFilterImg = array(
					'user_id' 		=> $userid,
					'string_filter' => $str_list_images
			);
			$this->db->insert('luxyart_tb_user_filter_img', $dataUserFilterImg);
		}
		//end list img sell for img random
		
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
			$dataSendEmail["is_send_admin"] 		= 1;
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
			$this->db->where(array('user_id_code_invite' => $this->uri->segment('2'),'user_status <='=>1));
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
					$dataSendEmail["name"] 					= $row->display_name;
					//$dataSendEmail["username"] 				= $user_name[0];
					$dataSendEmail["email"] 				= $email;
					$this->function_model->sendMailHTML($dataSendEmail);
					$sess_data = array(
					'user_id' 			=> $row->user_id,
					'user_email' 		=> $row->user_email,
					'user_pass' 		=> md5($row->user_pass),
					'user_firstname' 	=> $row->user_firstname,
					'user_display_name' 	=> $row->display_name,
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
					'user_display_name' 	=> $row->display_name,
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
		$data['noindex'] = '1';
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
			$this->db->where(array('user_id_code_invite' => $this->uri->segment('2'),'user_status <='=>1));
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
					$dataSendEmail["name"] 					= $row->display_name;
					$dataSendEmail["username"] 				= $row->display_name;
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
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/active_email", $meta, $this->lang_id);
		$this->template->load('default', 'active_email', $data, __CLASS__);
	}
	public function subtext($text,$num=80) {
        if (strlen($text) <= $num) {
            return $text;
        }
        $text= substr($text, 0, $num);
        if ($text[$num-1] == ' ') {
            return trim($text)."...";
        }
        $x  = explode(" ", $text);
        $sz = sizeof($x);
        if ($sz <= 1)   {
            return $text."...";}
        $x[$sz-1] = '';
        return trim(implode(" ", $x))."...";
	}
	function xuly_chuoi_tra_ve($chuoi_bo_vao)
	{
		$text_bien  = $chuoi_bo_vao;
		$chuoilay = array(", , ", ",,");
		$choidoi   = array(',',',');
		$out_text = str_replace($chuoilay, $choidoi, $text_bien);
		return $out_text;
	}
	public function user_timeline()
	{
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$data['linkshare'] = $actual_link;
		$this->session->set_userdata('actual_link', $actual_link);
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		// load list image user bookmark
		if($user_id!="")
		{
			
			$this->db->from('luxyart_tb_user_bookmark_image');
			$this->db->select('id_img');
			$this->db->where(array('user_id' => $user_id));
			$rs = $this->db->get();
			$array_list_bookmark_user = array();
			foreach ($rs->result_array() as $row)
			{
					array_push($array_list_bookmark_user, $row['id_img']);
			}
			$data['check_bookmark_images'] = $array_list_bookmark_user;
			//print_r($data['check_bookmark_images']);
			$data['is_login_user'] =1;
			// check user folow
			$this->db->from('luxyart_tb_user_follow');
			$this->db->select('user_id');
			$this->db->where('follow_user_id', $user_id);
			$rs = $this->db->get();
			$array_list_follow_user = array();
			foreach ($rs->result_array() as $row)
			{
					array_push($array_list_follow_user, $row['user_id']);
			}
			$data['id_user_check'] = $array_list_follow_user;
			$data['user_login_id'] =$user_id;
		}
		else
		{
			$data['check_bookmark_images']='';
			$data['is_login_user'] =0;
			$data['user_login_id'] ='';
			$data['id_user_check'] ='';
		}
		$this->db->from('luxyart_tb_user_adwords');
		$this->db->select('content_adwords');
		$this->db->where('user_id', $this->uri->segment('2'));
		$this->db->where('type_adwords', 1);
		$rs = $this->db->get();
		$result = $rs->row();
		if ($this->db->affected_rows() > 0)
		{
			$data['banner_load'] = $result->content_adwords;
		}
		else
		{
			$data['banner_load'] = '';
		}
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
			$name_call = $result->display_name;
			
			
			if($this->lang_id==1)
			{
				$data['meta_seo'] = $this->function_model->getMetaSeo("user/timeline", $meta, $this->lang_id);
				$data['meta_seo']['title_page'] =str_replace("{name_user}",$name_call,$data['meta_seo']['title_page']);
				$data['meta_seo']['description_page'] =str_replace("{name_user}",$name_call,$data['meta_seo']['description_page']);
				$data['meta_seo']['keywords'] =str_replace("{name_user}",$name_call,$data['meta_seo']['keywords']);
				$data['meta_seo']['page_h1'] = str_replace("{name_user}",$name_call,$data['meta_seo']['page_h1']);
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
		/// get image from another user
			$query = $this->db->query("Select id_img from luxyart_tb_product_img where user_id=".$this->uri->segment('2')." and img_check_status=1");
			$bien_chay = 1;
			$chuoi_xuly ='';
			$string_order = '';
			foreach ($query->result() as $row)
			{
				
				if($bien_chay==$query->num_rows())
				{
			   		$chuoi_xuly .= $row->id_img;
					$string_order .= $row->id_img;
				}
				else
				{
					$chuoi_xuly .= $row->id_img.',';
					$string_order .= $row->id_img.',';
				}
				$bien_chay++;
			}		
		$this->db->from('luxyart_tb_user_filter_img');
		$this->db->select('string_filter');
		$this->db->where('user_id', $this->uri->segment('2'));
		$rs_array = $this->db->get();
		$result_array = $rs_array->row();
		if ($this->db->affected_rows() > 0)
		{
			if($chuoi_xuly=="")
			{
				$chuoi_xuly = $result_array->string_filter;
			}
			else
			{
				$chuoi_xuly .= ','.$result_array->string_filter;
			}
			if($result_array->string_filter!="")
			{
				if($string_order!="")
				{
					$string_order = $result_array->string_filter.','.$string_order;
				}
				else
				{
					$string_order = $result_array->string_filter;
				}
			}
			else
			{
				$string_order = $string_order;
			}

		}
		$query2 = $this->db->query("Select id_img from luxyart_tb_user_get_img_sell where user_id=".$this->uri->segment('2'));
		$chuoi_xuly = $this->xuly_chuoi_tra_ve($chuoi_xuly);
			foreach ($query2->result() as $row)
			{	
				if($chuoi_xuly!="")
				{
					$chuoi_xuly .= ','.$row->id_img;
				}
				else
				{
					$chuoi_xuly .= $row->id_img;
				}
				//$string_order .= ','.$row->id_img;
			}
		
		$chuoi_xuly = $this->xuly_chuoi_tra_ve($chuoi_xuly);
		$string_order = $this->xuly_chuoi_tra_ve($string_order);
		$string_image_sapxep = explode(",", $chuoi_xuly);
		$this->load->library('pagination');
		$config['per_page'] = 20;
		$config['total_rows'] = count($this->manager_user_model->get_list_user_timeline_image($result->user_id,'','',$string_image_sapxep,$string_order)->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/timeline/'.$result->user_id;
		$data['list_images'] = $this->manager_user_model->get_list_user_timeline_image($result->user_id,$config['per_page'],$this->uri->segment(3),$string_image_sapxep,$string_order)->result();
		
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
		
		$this->template->load('default', 'user_timeline', $data, __CLASS__);
		}
		else
		{
			header("Location: ".base_url()."404_override.html");
		}
		
	}
	public function do_notice_admin_transfer_money()
	{
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$user_email = $logged_in['user_email'];
		$query = $this->db->query('SELECT luxyart_tb_list_credit.name_credit,luxyart_tb_list_credit.point_credit,luxyart_tb_list_credit.money_pay_credit FROM luxyart_tb_list_credit LEFT JOIN luxyart_tb_user_order_credit ON luxyart_tb_user_order_credit.id_list = luxyart_tb_list_credit.id_list WHERE luxyart_tb_user_order_credit.id_ordercredit="'.$_POST['id_ordercredit'].'" and luxyart_tb_user_order_credit.user_id='.$user_id);
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$name_credit = $row->name_credit;
			$point_credit = $row->point_credit;
			$money_pay_credit = $row->money_pay_credit;
		}
		$this->db->where('id_ordercredit', $_POST['id_ordercredit']);
		if ($this->db->update('luxyart_tb_user_order_credit',array('notice_status' => 1)) === TRUE){
    		$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "notice_admin_user_click_button_transfer_money_for_buy_point";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $user_email;
			$dataSendEmail["username"] 				= $this->function_model->get_fullname($user_id, $this->lang_id);
			$dataSendEmail["key_1"] 				= $name_credit;
			$dataSendEmail["key_2"] 				= $point_credit;
			$dataSendEmail["key_3"] 				= $money_pay_credit;
			$dataSendEmail["is_send_admin"] 		= 2;
			$this->function_model->sendMailHTML($dataSendEmail);
			echo "update{return_do_notice_admin_transfer_money}1";
			exit;
		}
		else
		{
			echo "update{return_do_notice_admin_transfer_money}0";
			exit;
		}
	}
	
	
	public function do_ajax_reset_password()
	{
		// tao chuoi password
		$bangkytuset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
			$dataSendEmail["name"] 					= $this->get_name_user_by_email($_POST['email']);
			$dataSendEmail["username"] 				= $_POST['email'];
			$dataSendEmail["email"] 				= $_POST['email'];
			$dataSendEmail["password"] 				= $password_new;
			$dataSendEmail["is_send_admin"] 		= 1;
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
	
	public function get_email_activation(){
		
		$email = $_POST['email'];
		
		$this->db->from('luxyart_tb_user');
		$this->db->select('user_id,is_active,display_name,user_id_code_invite');
		$this->db->where(array('user_email' => $email));
		$rsUser = $this->db->get();
		
		$resultUser = $rsUser->row();
		$is_active = $resultUser->is_active;
		$display_name = $resultUser->display_name;
		$user_id_code_invite = $resultUser->user_id_code_invite;
		
		if($is_active == 0){
			
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "get_email_activation";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $email;
			$dataSendEmail["name"] 					= $display_name;
			$dataSendEmail["link"] 					= base_url()."active-email/".$user_id_code_invite;
			$dataSendEmail["username"] 				= $display_name;
			$dataSendEmail["email"] 				= $email;
			//$dataSendEmail["is_debug"] 			= 1;
			
			$this->function_model->sendMailHTML($dataSendEmail);
			
			echo 1;
			
		}
		else{
			
			echo "0{luxyart}".$this->lang->line('account_already_activated');
			
		}
			
		
		exit;
		
		// tao chuoi password
		$bangkytuset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
			$dataSendEmail["name"] 					= $this->get_name_user_by_email($_POST['email']);
			$dataSendEmail["username"] 				= $_POST['email'];
			$dataSendEmail["email"] 				= $_POST['email'];
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
	
		//echo phpinfo();
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
			$result = $rs->row();
			if(!empty($result)){
				
				$sftp_config['hostname'] = $result->server_name;
				$sftp_config['username'] = $result->server_ftp_account;
				$sftp_config['password'] = $result->server_ftp_pass;
				$sftp_config['port'] = $result->server_port;
				
				//$sftp_config['hostname'] = '133.130.91.14';
				//$sftp_config['username'] = 'root';
				//$sftp_config['password'] = 'ReWqASDF!0';
				//$sftp_config['port'] = '22';
				//$sftp_config['debug'] = TRUE;
				$this->sftp->connect($sftp_config);
				//$abc = $this->sftp->move('/etc/nginx/html/abc.html','/etc/nginx/html/aa/testabc.html');
				//$abc = $this->sftp->upload('/var/www/html/application/controllers/user.php','/etc/nginx/html/user.php', 'ascii', 0775);
				//$abc = $this->sftp->check_file('/etc/nginx/html','test.html');
				//print_r($abc);
				//exit();
				//$success = $this->sftp->download('/etc/nginx/html/abc.html', "/var/www/html/publics/do_download/abc.html");
				//print_r($success);
				//exit();
				$get_image = $this->sftp->check_file($result->file_name_original_img,$result->server_path_upload_img);
				$path_local = $_SERVER['DOCUMENT_ROOT'].'/publics/do_download/'.$result->file_name_original_img;
				$name_new = explode('.', $result->file_name_original_img);
				$path_local_remove_type = $_SERVER['DOCUMENT_ROOT'].'/publics/do_download/'.$name_new['0'];
				$size_img = explode('px X ',$result->type_size_description);
				if (strlen($get_image))
				{
					$success = $this->sftp->download("/".$result->server_path_upload_img."/".$result->file_name_original_img, "/var/www/html/publics/do_download/".$result->file_name_original_img);
					$result = $_SERVER['DOCUMENT_ROOT'].'/publics/do_download/'.$result->file_name_original_img;
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
				  echo $this->lang->line('error_cannot_find_image_for_download');
				}
			}
			else{
				echo $this->lang->line('error_cannot_find_image_for_download');
			}
		}
		else
		{
			echo $this->lang->line('error_code_download_empty');
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
	public function do_bookmark_follow_user()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->db->from('luxyart_tb_user_follow');
		$this->db->select('*');
		$this->db->where(array('follow_user_id' => $user_id, 'user_id' => $_POST['user_id_bi_click']));
		$rs = $this->db->get();
		$row = $rs->row();
		if(empty($row)){
			echo "ketqua{return_do_bookmark_follow_user}0";
			$insert_data_bookmark = array(
				'user_id' 						=> $_POST['user_id_bi_click'],
				'follow_user_id' 				=> $user_id,
				'date_follow' 				=> date('Y-m-d H:i:s'),
			);
			$this->db->insert('luxyart_tb_user_follow', $insert_data_bookmark);
			//send notice
			$addNotice = array();
			$addNotice['type'] 						= "follow_user";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $_POST['user_id_bi_click'];
			$addNotice['user_send_id'] 				= -1;
			$addNotice['email'] 					= $this->get_name_user($user_id);
				
			$this->function_model->addNotice($addNotice);
			exit;
		}
		else
		{
			echo "ketqua{return_do_bookmark_follow_user}1";
			exit;
		}
		
	}
	public function do_remove_bookmark_follow_user()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->db->from('luxyart_tb_user_follow');
		$this->db->select('*');
		$this->db->where(array('follow_user_id' => $user_id, 'user_id' => $_POST['user_id_bi_click']));
		$rs = $this->db->get();
		$row = $rs->row();
		if(!empty($row))
		{
			echo "ketqua{return_do_remove_bookmark_follow_user}0";
			$this->db->where('follow_user_id', $user_id);
			$this->db->where_in('user_id',$_POST['user_id_bi_click']);
			$this->db->delete('luxyart_tb_user_follow');
			exit;
		}
		else
		{
			echo "ketqua{return_do_remove_bookmark_follow_user}1";
			exit;	
		}
	}
	public function do_bookmark_image()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->db->from('luxyart_tb_user_bookmark_image');
		$this->db->select('*');
		$this->db->where(array('user_id' => $user_id, 'id_img' => $_POST['id_image']));
		$rs = $this->db->get();
		$row = $rs->row();
		if(!empty($row)){
			echo "ketqua{return_do_bookmark_image}1";
			$this->db->where('user_id', $user_id);
			$this->db->where_in('id_img',$_POST['id_image']);
			$this->db->delete('luxyart_tb_user_bookmark_image');
			exit;	
		}
		else
		{
			echo "ketqua{return_do_bookmark_image}0";
			$insert_data_bookmark = array(
				'user_id' 						=> $user_id,
				'id_img' 					=> $_POST['id_image'],
				'date_bookmark' 				=> date('Y-m-d H:i:s'),
			);
			$this->db->insert('luxyart_tb_user_bookmark_image', $insert_data_bookmark);
			$addNotice = array();
			$addNotice['type'] 						= "bookmark_image";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $this->get_user_id_tu_id_img($_POST['id_image']);
			$addNotice['user_send_id'] 				= -1;
			$addNotice['email'] 					= $this->get_name_user($user_id);
			$addNotice['img_title'] 				= $this->get_image_name_title($_POST['id_image']);
			$addNotice['id_img'] 					= $_POST['id_image'];
			$this->function_model->addNotice($addNotice);
			exit();
		}
		
	}
	public function get_name_user($user_id)
	{
		$this->db->from('luxyart_tb_user');
		$this->db->select('display_name,user_email');
		$this->db->where(array('user_id' => $user_id));
		$rs = $this->db->get();
		$row = $rs->row();
		$call_name ='';
		if($row->display_name!="")
		{
			$call_name = $row->display_name;
		}
		else
		{
			$get_name_email = explode('@',$row->user_email);
			$call_name = $get_name_email[0];
		}
		return $call_name;
	}
	public function get_name_user_by_email($email)
	{
		$this->db->from('luxyart_tb_user');
		$this->db->select('display_name,user_email');
		$this->db->where(array('user_email' => $email));
		$rs = $this->db->get();
		$row = $rs->row();
		$call_name ='';
		if($row->display_name!="")
		{
			$call_name = $row->display_name;
		}
		else
		{
			$get_name_email = explode('@',$row->user_email);
			$call_name = $get_name_email[0];
		}
		return $call_name;
	}
	public function get_image_name_title($id_image)
	{
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('img_title');
		$this->db->where(array('id_img' => $id_image));
		$rs = $this->db->get();
		$row = $rs->row();
		return $row->img_title;
	}
	public function get_user_id_tu_id_img($id_image)
	{
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('user_id');
		$this->db->where(array('id_img' => $id_image));
		$rs = $this->db->get();
		$row = $rs->row();
		return $row->user_id;
	}
	
	public function login(){
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if($is_permission){
			header("Location: ".base_url()."mypage.html");
		}
		$is_popup = @$this->session->userdata['is_popup'];
		if($is_popup){
			$data["message_login"] = $this->lang->line('p_login_text_login_fail');
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
	  	
	  	delete_cookie("remember_me");
	  	delete_cookie("user_id");
	  	delete_cookie("user_email");
	  	delete_cookie("user_pass");
	  	delete_cookie("user_display_name");
	  	delete_cookie("user_firstname");
	  	delete_cookie("user_lastname");
	  	delete_cookie("user_level");
	  	
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
						'user_pass' 		=> md5($user_pass),
						'user_display_name' => $row->display_name,
						'user_firstname' 	=> $row->user_firstname,
						'user_lastname' 	=> $row->user_lastname,
						'user_level' 		=> $row->user_level
				);
				$this->session->set_userdata('logged_in', $sess_data);
				
				if($remember_me){
					
					/*$cookie = array(
							'name'   => 'logged_in',
							'value'  => array(
											"remember_me"		=>1,
											'user_id' 			=> $row->user_id,
											'user_email' 		=> $user_email,
											'user_pass' 		=> md5($user_pass),
											'user_display_name' => $row->display_name,
											'user_firstname' 	=> $row->user_firstname,
											'user_lastname' 	=> $row->user_lastname,
											'user_level' 		=> $row->user_level
										),
							'expire' => strtotime("+1 month"),
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);*/
					
					$expire = 30*24*60*60;
					
					$cookie = array(
							'name'   => 'remember_me',
							'value'  => 1,
							'expire' => $expire,
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
					
					$cookie = array(
							'name'   => 'user_id',
							'value'  => $row->user_id,
							'expire' => $expire,
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
					
					$cookie = array(
							'name'   => 'user_email',
							'value'  => $row->user_email,
							'expire' => $expire,
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
					
					$cookie = array(
							'name'   => 'user_pass',
							'value'  => md5($user_pass),
							'expire' => $expire,
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
					
					$cookie = array(
							'name'   => 'user_display_name',
							'value'  => $row->display_name,
							'expire' => $expire,
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
					
					$cookie = array(
							'name'   => 'user_firstname',
							'value'  => $row->user_firstname,
							'expire' => $expire,
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
					
					$cookie = array(
							'name'   => 'user_lastname',
							'value'  => $row->user_lastname,
							'expire' => $expire,
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
					
					$cookie = array(
							'name'   => 'user_level',
							'value'  => $row->user_level,
							'expire' => $expire,
							'path'   => '/',
							'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
					
				}
				
				$member_id = $_SESSION["member_id"];
				$md5 = $_SESSION["md5"];
				
				if($member_id > 0 && $md5 != ""){
					//echo "1{luxyart}".base_url()."/accept_member/".$member_id."-".$md5.".html";
					echo 1;
				}
				else{
					echo 1;
				}
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
		
		session_start();
		
		$data['member_id'] = $_SESSION['member_id'];
		$data['email_invite'] = $_SESSION['email_invite'];
		$data['invite_member'] = $_SESSION['invite_member'];
		
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/dangky", $meta, $this->lang_id);
		$this->template->load('default', 'dangky', $data, __CLASS__);
		
	}
	
	public function dangkytest(){
		
		session_start();
		$data['member_id'] = $_SESSION['member_id'];
		$data['email_invite'] = $_SESSION['email_invite'];
		
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/dangky", $meta, $this->lang_id);
		$this->template->load('default', 'dangkytest', $data, __CLASS__);
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
	public function activation(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/activation", $meta, $this->lang_id);
		$this->template->load('default', 'activation', $data, __CLASS__);
	}
	public function remind_success(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/remind_success", $meta, $this->lang_id);
		$this->template->load('default', 'remind_success', $data, __CLASS__);
	}
	public function activation_success(){
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/activation_success", $meta, $this->lang_id);
		$this->template->load('default', 'activation_success', $data, __CLASS__);
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
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/error_404", $meta, $this->lang_id);
		$this->template->load('default', 'page_error_404', $data, __CLASS__);
	}
	public function notice_transfer_money()
	{
		$get_id_package = $this->uri->segment(2);
		//check package
		$query = $this->db->query('SELECT * FROM luxyart_tb_list_credit WHERE status_credit = 0 and id_list="'.$get_id_package.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$data['name_credit']= $row->name_credit;
			$data['point_credit']= $row->point_credit;
			$data['noindex'] = '1';
			$meta = array();
			$data['meta_seo'] = $this->function_model->getMetaSeo("user/notice_transfer_money", $meta, $this->lang_id);
			$this->template->load('default', 'notice_transfer_money', $data, __CLASS__);
		}
		else
		{
			$data['noindex'] = '1';
			$meta = array();
			$data['meta_seo'] = $this->function_model->getMetaSeo("user/error_404", $meta, $this->lang_id);
			$this->template->load('default', 'page_error_404', $data, __CLASS__);
		}
		
	}
	public function page_q_a()
	{
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/page_q_a", $meta, $this->lang_id);
		$this->template->load('default', 'page_q_a', $data, __CLASS__);
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
			//$this->load->library('form_validation');
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
						'field'=>'tencontact',
						'label'=>$this->lang->line('contact_input_name'),
						'rules'=>'trim|required'
					),
					array(
						'field'=>'emailcontact',
						'label'=>$this->lang->line('contact_input_email'),
						'rules'=>'trim|required|valid_email|xss_clean'
					),
					array(
							'field'=>'casecontact',
							'label'=>$this->lang->line('contact_input_type'),
							'rules'=>'trim|required|callback_checkvalue_select'
						),
					array(
							'field'=>'noidungcontact',
							'label'=>$this->lang->line('contact_input_content'),
							'rules'=>'trim|required|max_length[200]|min_length[50]'
						)
				);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					$email = $logged_in['user_email'];
					$user_name = $logged_in['user_display_name'];
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
			$data['meta_seo']['code_call'] = $data['code_call'];
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
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		//lay user_id tu session
		
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where(array('user_id' => $user_id));
		$rs = $this->db->get();
		$row = $rs->row();
		if(!empty($row)){
			if($row->user_lastname=="" ||$row->user_firstname=="" || $row->user_zipcode=="" || $row->state_id==0 || $row->cityname=="" || $row->user_address=="")
			{
				$data['show_update_account'] = 1;
			}
			else
			{
				$data['show_update_account'] = 0;
			}
		}
		// get list order
		
		$limit_order_list = 10;
		$data['list_order_mypage'] = $this->manager_user_model->get_list_order($user_id,$limit_order_list)->result();
		$limit_view_img_history = 10;
		$data['list_history_view_mypage'] = $this->manager_user_model->get_list_view_img_history($user_id,$limit_view_img_history)->result();
		$limit_new_img = 10;
		$data['list_new_image_bookmark_user'] = $this->manager_user_model->get_list_new_img_bookmark_user($user_id,$limit_new_img)->result();
		//get meta seo
		$data['active_menu_sidebar'] = '';
		$data['noindex'] = '1';
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
		
		$this->load->model('manager_user_model');
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['active_menu_sidebar'] = 'mypage-user-manager-point';
		
		$setting = $this->function_model->get_setting();
		$data['minium_export_money'] = $setting['minium_export_money'];
		$data['list_history_use_point'] = $this->manager_user_model->get_list_user_use_point($user_id,7)->result();
		$data['list_ask_return_point'] = $this->manager_user_model->get_list_ask_return_point($user_id,7)->result();
		$data['count_record_return_money'] = count($this->manager_user_model->count_record_return_money($user_id,7)->result());
		$data['count_record_history_use_point'] = count($this->manager_user_model->count_record_history_use_point($user_id,7)->result());
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_manager_point", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_manager_point', $data, __CLASS__);
	}
	public function history_user_use_point()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['active_menu_sidebar'] = 'mypage-user-manager-point';
		$this->load->library('pagination');
		$config['per_page'] = 20;
		$config['total_rows'] = count($this->manager_user_model->get_list_user_use_point_limit($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<span class="current">';
		$config['cur_tag_close'] =  '</span>';
		$config['base_url'] =  base_url().'/mypage/history-user-use-point';
		$data['list_history_use_point'] = $this->manager_user_model->get_list_user_use_point_limit($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_history_user_use_point", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_history_user_use_point', $data, __CLASS__);
	}
	public function history_ask_return_money()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['active_menu_sidebar'] = 'mypage-user-manager-point';
		$this->load->library('pagination');
		$config['per_page'] = 20;
		$config['total_rows'] = count($this->manager_user_model->get_list_ask_return_point_limit($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<span class="current">';
		$config['cur_tag_close'] =  '</span>';
		$config['base_url'] =  base_url().'/mypage/history-ask-return-money';
		$data['list_ask_return_point'] = $this->manager_user_model->get_list_ask_return_point_limit($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_history_ask_return_money", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_history_ask_return_money', $data, __CLASS__);
	}
	public function user_manager_adwords()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$this->db->from('luxyart_tb_user_adwords');
		$this->db->select('*');
		$this->db->where('luxyart_tb_user_adwords.user_id',$user_id);
		$re=$this->db->get();
		$row = $re->row();
		if($re->num_rows()>0)
		{
			$data['load_banner_code']['banner_code']=$row->content_adwords;
			$is_update=1;
		}
		else
		{
			$data['load_banner_code']['banner_code']='';
			$is_update=0;
		}
		$data['show_notice']=0;
		if(isset($_REQUEST['submit']))
			{
				$data['load_banner_code']['banner_code']= $_POST['banner_code'];
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
							'field'=>'banner_code',
							'label'=>$this->lang->line('manager_adwords_text_label_input'),
							'rules'=>'trim|required'
						)
				);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					if($is_update==1)
					{
						$update_adwords = array(
							'content_adwords' 					=> $_POST['banner_code']
						);
						$this->db->where('luxyart_tb_user_adwords.user_id', $user_id);
    					$this->db->update('luxyart_tb_user_adwords', $update_adwords);
						$data['show_notice']=1;
				
					}else
					{
						$insert_adwords= array(
							'user_id' 						=> $user_id,
							'type_adwords' 					=> 1,
							'content_adwords' 				=> $_POST['banner_code']
							//'date_add_bank' 				=> date('Y-m-d H:i:s'),
						);
						$this->db->insert('luxyart_tb_user_adwords', $insert_adwords);
						$data['show_notice']=2;
					}
				}
			}
		$data['active_menu_sidebar'] = 'mypage-user-adwords';
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_manager_adwords", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_manager_adwords', $data, __CLASS__);
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
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
						'field'=>'point',
						'label'=> $this->lang->line('text_point_export'),
						'rules'=>'trim|required|numeric|callback_checkvalue_point_input'
					),
					array(
						'field'=>'bank_name',
						'label'=>$this->lang->line('bank_account_input_bank_name'),
						'rules'=>'trim|required'
					),
					array(
							'field'=>'bank_branch_name',
							'label'=>$this->lang->line('bank_account_input_bank_branch_name'),
							'rules'=>'trim|required'
						),
					array(
							'field'=>'bank_account_number',
							'label'=>$this->lang->line('bank_account_input_bank_account_number'),
							'rules'=>'trim|required|numeric|callback_checkvalue_number_input'
						),
					array(
							'field'=>'account_name',
							'label'=>$this->lang->line('bank_account_input_account_name'),
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
					$user_name = $logged_in['user_display_name'];
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "user-ask-export-money";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $email;
					$dataSendEmail["name"] 					= $this->get_name_user($user_id);
					//$dataSendEmail["username"] 				= $user_name[0];
					$dataSendEmail["point"] 				= $_POST['point'];
					$dataSendEmail["money_ask"] 			= number_format($money_get,0,".",",");
					$dataSendEmail["is_send_admin"] 		= 1;
					$this->function_model->sendMailHTML($dataSendEmail);
					header("Location: ".base_url()."mypage/user-manager-point.html");
				}
			}
			$data['noindex'] = '1';
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
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
						'field'=>'bank_name',
						'label'=>$this->lang->line('bank_account_input_bank_name'),
						'rules'=>'trim|required'
					),
					array(
							'field'=>'bank_branch_name',
							'label'=>$this->lang->line('bank_account_input_bank_branch_name'),
							'rules'=>'trim|required'
						),
					array(
							'field'=>'bank_account_number',
							'label'=>$this->lang->line('bank_account_input_bank_account_number'),
							'rules'=>'trim|required|numeric|callback_checkvalue_number_input'
						),
					array(
							'field'=>'account_name',
							'label'=>$this->lang->line('bank_account_input_account_name'),
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
						//$data['notice_update'] ='Your bank account update successful';
						$data['notice_update'] = 'notice_use_update_bank_account_information';
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
						//$data['notice_update'] ='Create bank account successful';
						$data['notice_update'] = 'notice_use_create_bank_account_information';
						$addNotice = array();
						$addNotice['type'] 						= "user-create-bank-account";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_send_id'] 				= -1;
						$addNotice['user_id'] 					= $user_id;
						$this->function_model->addNotice($addNotice);
					}
				}
			}
			$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_change_bank_account", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_change_bank_account', $data, __CLASS__);
	}
	
	public function user_edit_card()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$card_edit_id = $this->uri->segment(3);
		$data['card_id'] = $card_edit_id; 
		$data['active_menu_sidebar'] = 'mypage-user-manager-card';
		$this->db->from('luxyart_user_card');
		$this->db->select('*');
		$this->db->where('luxyart_user_card.user_id',$user_id);
		$this->db->where('luxyart_user_card.card_id',$card_edit_id);
		$re=$this->db->get();
		$row = $re->row();
		if($re->num_rows()>0)
		{
			$data['error_edit'] = 0;
			$data['load_bank_user']['card_number']=$row->account_number;
			$data['load_bank_user']['exp_month']=substr($row->account_exp,0,2);
			$data['load_bank_user']['exp_year']=substr($row->account_exp,2);
			$data['load_bank_user']['card_cvc']=$row->account_ccv_value;
		}
		else
		{
			$data['error_edit'] = 1;
		}
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_manager_card", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_edit_card', $data, __CLASS__);
	}
	public function user_manager_card()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$this->load->model('manager_user_model');
		$data['active_menu_sidebar'] = 'mypage-user-manager-card';
		$this->db->from('luxyart_user_card');
		$this->db->select('*');
		$this->db->where('luxyart_user_card.user_id',$user_id);
		$re=$this->db->get();
		$result_get_card = $re->result();
		$row = $re->row();
		if($re->num_rows()>0)
		{
			$data['list_user_card'] = $result_get_card;	
			$data['list_user_card_count'] =1;
		}
		else
		{
			$data['list_user_card_count'] =0;
		}
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_manager_card", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_manager_card', $data, __CLASS__);
	}
	function checkvalue_select($value)
	{
		if($value==0)
		{
			$this->form_validation->set_message('checkvalue_select', $this->lang->line('error_contact_input_select_type_need_to_select'));
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
			
			$chuoibandau  = $this->lang->line('error_check_point_export_value');
			$chuoilay = array("{minium_export_money}", "{point_user}");
			$choidoi   = array($setting['minium_export_money'], number_format($user->user_point,0,".",","));
			$chuoicuoicung = str_replace($chuoilay, $choidoi, $chuoibandau);
			$this->form_validation->set_message('checkvalue_point_input',$chuoicuoicung);
             return FALSE;
		}
		if (!preg_match('/^[0-9]*$/', $point))
		{
			$this->form_validation->set_message('checkvalue_point_input', $this->lang->line('error_check_point_export_value_input_only_number'));
             return FALSE;
		}
		
	}
	function checkvalue_number_input($number)
	{
		if (!preg_match('/^[0-9]*$/', $number))
		{
			$this->form_validation->set_message('checkvalue_number_input', $this->lang->line('error_check_point_export_value_input_only_number'));
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['active_menu_sidebar'] = 'mypage-history-view-image';
		$this->load->library('pagination');
		$config['per_page'] = 36;
		$config['total_rows'] = count($this->manager_user_model->p_history_view_image($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/history-view-image';
		$data['p_history_view_image'] = $this->manager_user_model->p_history_view_image($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();
		$data['noindex'] = '1';	
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['active_menu_sidebar'] = 'mypage-favorite-image';
		$this->load->library('pagination');
		$config['per_page'] = 20;
		$config['total_rows'] = count($this->manager_user_model->p_favorite_image($user_id,'','')->result());
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/favorite-image';
		$data['p_favorite_image'] = $this->manager_user_model->p_favorite_image($user_id,$config['per_page'],$this->uri->segment(3))->result();
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();	
		$data['noindex'] = '1';
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['active_menu_sidebar'] = 'mypage-favorite-user';
		$this->load->library('pagination');
		$config['per_page'] = 5;
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
		$data['noindex'] = '1';	
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
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
		$data['noindex'] = '1';
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
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			$data['show_update_notice'] = 1;
		}
		else
		{
			$data['show_update_notice'] = 0;
		}
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
		//exit();
		if(isset($_REQUEST['submit']))
			{	
				$data['load_user_config']['user_firstname'] = $_POST['firstname'];
				$data['load_user_config']['notice_luxyart'] = $_POST['check_notice'];
				$data['load_user_config']['user_lastname'] = $_POST['lastname'];
				$data['load_user_config']['user_zipcode'] = $_POST['zipcode'];
				$data['load_user_config']['state_id'] = $_POST['state_select'];
				$data['load_user_config']['display_name'] = $_POST['display_name'];
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
						'label'=>$this->lang->line('mypage_user_config_first_name'),
						'rules'=>'trim|required'
					),
					array(
							'field'=>'lastname',
							'label'=>$this->lang->line('mypage_user_config_last_name'),
							'rules'=>'trim|required'
						),
					array(
							'field'=>'display_name',
							'label'=>'氏名を記入して下さい。',
							'rules'=>'trim|required'
						),
					array(
							'field'=>'zipcode',
							'label'=>$this->lang->line('mypage_user_config_zip_code'),
							'rules'=>'trim|required'
						),
					array(
							'field'=>'photo_file',
							'label'=>$this->lang->line('mypage_user_config_avatar_user'),
							'rules'=>'callback_file_selected_check'
						),
					array(
							'field'=>'banner_file',
							'label'=>$this->lang->line('mypage_user_config_banner_user'),
							'rules'=>'callback_file_selected_check_banner'
						),
						array(
							'field'=>'cityname',
							'label'=>$this->lang->line('mypage_user_config_cityname'),
							'rules'=>'trim|required'
						),
						array(
							'field'=>'address',
							'label'=>$this->lang->line('mypage_user_config_address'),
							'rules'=>'trim|required'
						),
						array(
							'field'=>'mota',
							'label'=>$this->lang->line('mypage_user_desscription'),
							'rules'=>'trim|required'
						),
						
				);
				$this->form_validation->set_rules($rules);	
				if($this->form_validation->run())
				{
					if(!empty($_FILES['photo_file']['name']))
					{
						$config['upload_path'] = $path_upload;
						$config['allowed_types'] = 'jpg|jpeg|png|gif';
						$config['file_name'] = $user_id.date("Ymd").str_replace('-','',$_FILES['photo_file']['name']);
				
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
				
						if ($this->upload->do_upload('photo_file')) 
						{
						  $uploadData = $this->upload->data();
						  $file_new_image = $uploadData['file_name'];
						  $data['load_user_config']['user_avatar'] = $uploadData['file_name'];
						  if($file_new_image!="")
						  {
						  	unlink($path_upload.'/'.$old_img); 
						  }
						}
					}
					else
					{
						$file_new_image =$old_img;
					}					
					if (isset($_FILES['banner_file']['name']) && !empty($_FILES['banner_file']['name'])) 
					{
						$config['upload_path'] = $path_upload_banner;
						$config['allowed_types'] = 'jpg|jpeg|png|gif';
						$config['file_name'] = $user_id.date("Ymd").str_replace('-','',$_FILES['banner_file']['name']);
				
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
				
						if ($this->upload->do_upload('banner_file')) {
						  $uploadData = $this->upload->data();
						  $file_new_image_banner = $uploadData['file_name'];
						  $data['load_user_config']['user_banner'] = $uploadData['file_name'];
						  if($file_new_image_banner!="")
						  {
						  	unlink($path_upload_banner.'/'.$old_img_banner); 
						  }
						} 
						
				  	}
					else
					{
						$file_new_image_banner =$old_img_banner;
					}
					$data_update = array(
					'user_firstname'=>$this->input->post('firstname'),
					'user_lastname'=>$this->input->post('lastname'),
					'user_zipcode'=>$this->input->post('zipcode'),
					'display_name'=>$this->input->post('display_name'),
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
					$data['notice_update']="edit_user_success";
					$addNotice = array();
					$addNotice['type'] 						= "user-update-config";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_send_id'] 				= -1;
					// nguoi nhan msg
					$addNotice['user_id'] 					= $user_id;
					$this->function_model->addNotice($addNotice);
					$logged_in = $this->function_model->get_logged_in();
					$this->session->userdata['logged_in']['user_firstname'] = $this->input->post('firstname');
					$this->session->userdata['logged_in']['user_lastname'] = $this->input->post('lastname');
					$this->session->userdata['logged_in']['user_display_name'] = $this->input->post('display_name');
					$this->session->set_userdata('logged_in', $this->session->userdata['logged_in']);
				}
			}
		$data['noindex'] = '1';
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
		$user_level = $logged_in['user_level'];
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['user_level'] = $user_level;
		$data['active_menu_sidebar'] = 'mypage-user-update-creator';
		$data['noindex'] = '1';
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
		$user_level = $logged_in['user_level'];
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['user_level'] = $user_level;
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
		$data['noindex'] = '1';
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$user_level = $logged_in['user_level'];
		$data['user_level'] = $user_level;
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
		$data['noindex'] = '1';
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$user_level = $logged_in['user_level'];
		$data['user_level'] = $user_level;
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
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_update_enterprise", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_update_enterprise', $data, __CLASS__);
	}
	public function user_update_account()
	{
		$this->load->model('manager_user_model');
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		$data["user_id"] = $user_id;
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		$user_level = $logged_in['user_level'];
		$data['user_level'] = $user_level;
		$setting = $this->function_model->get_setting();
		$data['point_enterprise'] = $setting['point_update_level_user_enterprise'];
		$data['point_gold'] = $setting['point_update_level_user_gold'];
		$data['point_platinum'] = $setting['point_update_level_user_platinum'];
		$data['active_menu_sidebar'] = 'mypage-user-update-account';
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_update_account", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_update_account', $data, __CLASS__);
	}
	function check_level_user($level_account)
	{
		if($level_account==1)
		{
			return $this->lang->line('user_level_1');
					
		}else if($level_account==2){
			return $this->lang->line('user_level_2');
		}
		else if($level_account==3){
			return $this->lang->line('user_level_3');
		}
		else if($level_account==4){
			return $this->lang->line('user_level_4');
		}
		else if($level_account==5){
			return $this->lang->line('user_level_5');
		}
	}
	public function notice_user_update_level()
	{
		$level_account = $this->uri->segment(3);
		if($level_account=='creator')
		{
			$data['level'] = 2;
			$data['error'] = '0';
					
		}else if($level_account=='gold'){
			$data['level'] = 3;
			$data['error'] = '0';	
		}
		else if($level_account=='platinum'){
			$data['level'] = 4;
			$data['error'] = '0';
		}
		else if($level_account=='enterprise'){
			$data['level'] = 5;
			$data['error'] = '0';
		}
		else
		{
			$data['level'] = '';
			$data['error'] = '1';
		}
		$data['noindex'] = '1';
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
				$dataSendEmail["name"] 					= $this->get_name_user($user_id);
				$dataSendEmail["link"] 					= base_url()."mypage.html";
				//$dataSendEmail["username"] 				= $user_name[0];
				$dataSendEmail["is_send_admin"] 		= 1;
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
				$dataSendEmail["name"] 					= $this->get_name_user($user_id);
				$dataSendEmail["link"] 					= base_url()."mypage.html";
				//$dataSendEmail["username"] 				= $user_name[0];
				$dataSendEmail["is_send_admin"] 		= 1;
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
				$dataSendEmail["name"] 					= $this->get_name_user($user_id);
				$dataSendEmail["link"] 					= base_url()."mypage.html";
				$dataSendEmail["is_send_admin"] 		= 1;
				//$dataSendEmail["username"] 				= $user_name[0];
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
				$dataSendEmail["name"] 					= $this->get_name_user($user_id);
				$dataSendEmail["link"] 					= base_url()."mypage.html";
				//$dataSendEmail["username"] 				= $user_name[0];
				$dataSendEmail["is_send_admin"] 		= 1;
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
					$this->form_validation->set_message('file_selected_check', '画像のファイル形式が無効です。jpg,jpeg,png,gifのみアップロードできます。');
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
					$this->form_validation->set_message('file_selected_check_banner', '画像のファイル形式が無効です。jpg,jpeg,png,gifのみアップロードできます。');
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
					$this->form_validation->set_message('email_exists', 'このメールアドレスは既に登録済みです。');
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
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
							'label'=>$this->lang->line('user_page_change_email_input_email_text'),
							'rules'=>'trim|required|valid_email|callback_email_exists'
						),
						array(
							'field'=>'xacnhanemail',
							'label'=>$this->lang->line('user_page_change_email_input_email_text_once_more'),
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
					$dataSendEmail["name"] 					= $this->get_name_user($user_id);
					$dataSendEmail["link"] 					= base_url()."active-email-after-changed/".$user_code_invite;
					//$dataSendEmail["username"] 				= $user_name[0];
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
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_email", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_email', $data, __CLASS__);
	}
	public function user_email_comp()
	{
		$data['active_menu_sidebar'] = 'mypage-user-email';
		$data['noindex'] = '1';
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
					$this->form_validation->set_message('password_exists', 'パスワードが不正です。');
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
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
							'label'=>$this->lang->line('user_page_change_pass_input_oldpass'),
							'rules'=>'trim|required|callback_password_exists'
						),
						array(
							'field'=>'new_pass',
							'label'=>$this->lang->line('user_page_change_pass_input_newpass'),
							'rules'=>'trim|required|min_length[8]|alpha_numeric'
						),
						array(
							'field'=>'xacnhanpass',
							'label'=>$this->lang->line('user_page_change_pass_input_newpass_xacnhan'),
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
					$dataSendEmail["name"] 					= $this->get_name_user($user_id);
					$dataSendEmail["email"] 				= $email;
					$dataSendEmail["password"] 				= $this->input->post('new_pass');
					$this->function_model->sendMailHTML($dataSendEmail);
					redirect(base_url('mypage/user-password-comp.html'), 'location', 301);
				}
			}
		$data['active_menu_sidebar'] = 'mypage-user-pass';
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/mypage_user_pass", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_pass', $data, __CLASS__);
	}
	public function user_pass_comp()
	{
		$data['active_menu_sidebar'] = 'mypage-user-pass';
		$data['noindex'] = '1';
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['active_menu_sidebar'] = 'mypage-user-leave';
		$data['noindex'] = '1';
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
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
			$dataSendEmail["name"] 					= $this->get_name_user($user_id);
			$dataSendEmail["is_send_admin"] 		= 1;
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
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/notice_user_leave", $meta, $this->lang_id);
		$this->template->load('default', 'notice_user_leave', $data, __CLASS__);
	}
	public function notice(){
		
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		
		//get detail user
		$data['detail_user'] = $this->function_model->getUser($user_id);
		
		$data['return_key'] 		= $_SESSION['return_key'];
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo($data['return_key'], $meta, $this->lang_id);
		
		//$this->template->load('default', 'notice', $data, __CLASS__);
		$this->load->view('user/notice', $data, __CLASS__);
		
	}
	public function credit_select()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$session_return_url = $this->session->userdata['return_url_update_level'];
		$this->load->model('manager_user_model');
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$get_id_package = $this->uri->segment(2);
		
		//check package
		$query = $this->db->query('SELECT * FROM luxyart_tb_list_credit WHERE status_credit = 0 and id_list="'.$get_id_package.'"');
		if($query->num_rows() > 0)
		{
			$data['error_xuly']=1;
			$row = $query->row();
			$data['name_credit']= $row->name_credit;
			$data['point_credit']= $row->point_credit;
			$data['money_pay_credit']= $row->money_pay_credit;
			if(isset($_POST['payment']))
			{
				if($_POST['payment']=='new_card')
				{
					require '/var/www/html/application/libraries/stripe/Stripe.php';
					$params = array(
	"testmode"   => "off",
	"private_live_key" => "sk_live_ienB7dSdzBLfpuDa5fIVfma3",
	"public_live_key"  => "pk_live_JhEIbvKZpdsTsAe520dzVTC9",
	"private_test_key" => "sk_test_coDx2kN3APcZL8EfX5AYPdge",
	"public_test_key"  => "pk_test_G51fBiBm5a3S4ERlEruAHB5s"
);

if ($params['testmode'] == "on") {
	Stripe::setApiKey($params['private_test_key']);
	$pubkey = $params['public_test_key'];
} else {
	Stripe::setApiKey($params['private_live_key']);
	$pubkey = $params['public_live_key'];
}
try {
$token_stripe = Stripe_Token::create(array(
  "card" => array(
    "number" => $_POST['cc-number'],
    "exp_month" => $_POST['cc-exp-month'],
    "exp_year" => '20'.$_POST['cc-exp-year'],
    "cvc" => $_POST['cc-cvc']
  )
));
}catch(Stripe_CardError $e) {			

	$error = $e->getMessage();
		$result = $error;
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;

	} catch (Stripe_InvalidRequestError $e) {
		$result = "Error Payment with Stripe";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;		  
	} catch (Stripe_AuthenticationError $e) {
		$result = "Error Stripe Authentication";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Stripe_ApiConnectionError $e) {
		$result = "Error Api Connection";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Stripe_Error $e) {
		$result = "Error Payment with Stripe";
	} catch (Exception $e) {

		if ($e->getMessage() == "zip_check_invalid") {
			$result = "zip check invalid";
		} else if ($e->getMessage() == "address_check_invalid") {
			$result = "address check invalid";
		} else if ($e->getMessage() == "cvc_check_invalid") {
			$result = "cvc check invalid";
		} else {
			$result = "Error Payment with Stripe";
		}
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;				  
	}
if(isset($token_stripe->id))
{	
	$amount_cents = $row->money_pay_credit;  // Chargeble amount
	$invoiceid = $user_id."_".$row->name_credit.'_'.$get_id_package;                      // Invoice ID
	$description = "Order point package  #" . $invoiceid;
	
	try {

		$charge = Stripe_Charge::create(array(		 
			  "amount" => $amount_cents,
			  "currency" => "JPY",
			  "source" => $token_stripe->id,
			  "description" => $description)			  
		);

		if ($charge->card->address_zip_check == "fail") {
			throw new Exception("zip_check_invalid");
		} else if ($charge->card->address_line1_check == "fail") {
			throw new Exception("address_check_invalid");
		} else if ($charge->card->cvc_check == "fail") {
			throw new Exception("cvc_check_invalid");
		}
		// Payment has succeeded, no exceptions were thrown or otherwise caught				

		$result = "success";
		
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
						$dataSendEmail["name"] 					= $this->get_name_user($user_id);
						$dataSendEmail["is_send_admin"] 		= 1;
						$dataSendEmail["key_1"]			= $row->name_credit;
						$dataSendEmail["point_package"]			= $row->point_credit;
						$this->function_model->sendMailHTML($dataSendEmail);
						// add notice thong bao in user page
						$addNotice = array();
						$addNotice['type'] 						= "user-buy-point-package";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_send_id'] 				= -1;
							// nguoi nhan msg
						$addNotice['user_id'] 					= $user_id;
						$addNotice["key_1"]			= $row->name_credit;
						$addNotice['point'] 					= $row->point_credit;
						$this->function_model->addNotice($addNotice);
						if($session_return_url!='')
						{
							header("Location: ".base_url($session_return_url));
							$this->session->unset_userdata('return_url_update_level');
						}
						
						
					

	}catch(Stripe_CardError $e) {			

	$error = $e->getMessage();
		$result = $error;
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;

	} catch (Stripe_InvalidRequestError $e) {
		$result = "Error Payment with Stripe";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;	  
	} catch (Stripe_AuthenticationError $e) {
		$result = "Error Stripe Authentication";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Stripe_ApiConnectionError $e) {
		$result = "Error Api Connection";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Stripe_Error $e) {
		$result = "Error Payment with Stripe";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Exception $e) {

		if ($e->getMessage() == "zip_check_invalid") {
			$result = "zip check invalid";
		} else if ($e->getMessage() == "address_check_invalid") {
			$result = "address check invalid";
		} else if ($e->getMessage() == "cvc_check_invalid") {
			$result = "cvc check invalid";
		} else {
			$result = "Error Payment with Stripe";
		}
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;				  
	}

}
				}
				else if($_POST['payment']=='bit_coin')
				{
					echo 'bit coin under working...';
					exit();
				}
				else if($_POST['payment']=='bank_transfer')
				{
						$data['card_pay'] = 2;
						$insert_data_order_list = array(
							'user_id' 						=> $user_id,
							'id_list' 						=> $get_id_package,
							'payment_menthod' 				=> 1,
							'payment_status' 			=> 0,
							'notice_status' 			=> 0,
							'date_order_credit' 		=> date('Y-m-d H:i:s'),
						);
						$this->db->insert('luxyart_tb_user_order_credit', $insert_data_order_list);
						$email = $logged_in['user_email'];
						$user_name = explode('@',$logged_in['user_email']);
						$dataSendEmail = array();
						$dataSendEmail["name_email_code"] 		= "user-buy-point-package-case-bank-transfer";
						$dataSendEmail["lang_id"] 				= $this->lang_id;
						$dataSendEmail["email_to"] 				= $email;
						$dataSendEmail["name"] 					= $this->get_name_user($user_id);
						$dataSendEmail["key_1"]			= $row->name_credit;
						$dataSendEmail["point_package"]			= $row->point_credit;
						$dataSendEmail["money_ask"]			= $row->money_pay_credit;
						$this->function_model->sendMailHTML($dataSendEmail);
						// add notice thong bao in user page
						$addNotice = array();
						$addNotice['type'] 						= "user-buy-point-package-case-bank-transfer";
						$addNotice["lang_id"] 					= $this->lang_id;
						$addNotice['user_send_id'] 				= -1;
							// nguoi nhan msg
						$addNotice['user_id'] 					= $user_id;
						$addNotice["key_1"]			= $row->name_credit;
						$addNotice['point'] 					= $row->point_credit;
						$addNotice['money_ask'] 			= $row->money_pay_credit;
						$this->function_model->addNotice($addNotice);
						//if($session_return_url!='')
						//{
							header("Location: ".base_url('notice-transfer-money/'.$row->id_list.'.html'));
							$this->session->unset_userdata('return_url_update_level');
						//}
				}
				else
				{
					
					$query = $this->db->query('SELECT * FROM luxyart_user_card WHERE user_id = '.$user_id.' and card_id='.$_POST['payment']);
					$row_card = $query->row();
					if($query->num_rows() > 0)
					{
						require '/var/www/html/application/libraries/stripe/Stripe.php';
					$params = array(
	"testmode"   => "off",
	"private_live_key" => "sk_live_ienB7dSdzBLfpuDa5fIVfma3",
	"public_live_key"  => "pk_live_JhEIbvKZpdsTsAe520dzVTC9",
	"private_test_key" => "sk_test_coDx2kN3APcZL8EfX5AYPdge",
	"public_test_key"  => "pk_test_G51fBiBm5a3S4ERlEruAHB5s"
);

if ($params['testmode'] == "on") {
	Stripe::setApiKey($params['private_test_key']);
	$pubkey = $params['public_test_key'];
} else {
	Stripe::setApiKey($params['private_live_key']);
	$pubkey = $params['public_live_key'];
}
try {
$token_stripe_old_card = Stripe_Token::create(array(
  "card" => array(
    "number" => $row_card->account_number,
    "exp_month" => substr($row_card->account_exp,0,2),
    "exp_year" => substr($row_card->account_exp, 2),
    "cvc" => $row_card->account_ccv_value
  )
));
}catch(Stripe_CardError $e) {			

	$error = $e->getMessage();
		$result = $error;
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;

	} catch (Stripe_InvalidRequestError $e) {
		$result = "Error Payment with Stripe";	
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;	  
	} catch (Stripe_AuthenticationError $e) {
		$result = "Error Stripe Authentication";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Stripe_ApiConnectionError $e) {
		$result = "Error Api Connection";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Stripe_Error $e) {
		$result = "Error Payment with Stripe";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Exception $e) {

		if ($e->getMessage() == "zip_check_invalid") {
			$result = "zip check invalid";
		} else if ($e->getMessage() == "address_check_invalid") {
			$result = "address check invalid";
		} else if ($e->getMessage() == "cvc_check_invalid") {
			$result = "cvc check invalid";
		} else {
			$result = "Error Payment with Stripe";
		}
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
				  
	}
				if(isset($token_stripe_old_card->id))
				{
					$amount_cents = $row->money_pay_credit;  // Chargeble amount
					$invoiceid = $user_id."_".$row->name_credit.'_'.$get_id_package;                      // Invoice ID
					$description = "Order point package  #" . $invoiceid;					
					try {

		$charge = Stripe_Charge::create(array(		 
			  "amount" => $amount_cents,
			  "currency" => "JPY",
			  "source" => $token_stripe_old_card->id,
			  "description" => $description)			  
		);

		/*if ($charge->card->address_zip_check == "fail") {
			echo 'zipcode check invalid';exit();
			throw new Exception("zip_check_invalid");
		} else if ($charge->card->address_line1_check == "fail") {
			echo 'address check invalid';exit();
			throw new Exception("address_check_invalid");
		} else if ($charge->card->cvc_check == "fail") {
			echo 'cvc check invalid';exit();
			throw new Exception("cvc_check_invalid");
		}*/
		// Payment has succeeded, no exceptions were thrown or otherwise caught				

		$result = "success";
		
						
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
											$query = $this->db->query('SELECT card_id FROM luxyart_user_card WHERE account_number="'.$row_card->account_number.'" and user_id="'.$user_id.'"');
											if($query->num_rows() <= 0)
											{
												$insert_card_user = array(
																'user_id' 					=> $user_id,
																'account_number' 			=> $row_card->account_number,
																'account_ccv_value' 		=> $row_card->account_ccv_value,
																'account_exp' 				=> $row_card->account_exp,
																'account_type_card' 		=> $row_card->account_type_card,
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
											$dataSendEmail["name"] 					= $this->get_name_user($user_id);
											$dataSendEmail["is_send_admin"] 		= 1;
											$dataSendEmail["key_1"]			= $row->name_credit;
											$dataSendEmail["point_package"]			= $row->point_credit;
											$this->function_model->sendMailHTML($dataSendEmail);
											// add notice thong bao in user page
											$addNotice = array();
											$addNotice['type'] 						= "user-buy-point-package";
											$addNotice["lang_id"] 					= $this->lang_id;
											$addNotice['user_send_id'] 				= -1;
												// nguoi nhan msg
											$addNotice['user_id'] 					= $user_id;
											$addNotice["key_1"]			= $row->name_credit;
											$addNotice['point'] 					= $row->point_credit;
											$this->function_model->addNotice($addNotice);
											if($session_return_url!='')
											{
												header("Location: ".base_url($session_return_url));
												$this->session->unset_userdata('return_url_update_level');
											}						
						
					

	}catch(Stripe_CardError $e) {			

	$error = $e->getMessage();
		$result = $error;
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;

	} catch (Stripe_InvalidRequestError $e) {
		$result = "Error Payment with Stripe";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;		  
	} catch (Stripe_AuthenticationError $e) {
		$result = "Error Stripe Authentication";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Stripe_ApiConnectionError $e) {
		$result = "Error Api Connection";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Stripe_Error $e) {
		$result = "Error Payment with Stripe";
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
	} catch (Exception $e) {

		if ($e->getMessage() == "zip_check_invalid") {
			$result = "zip check invalid";
		} else if ($e->getMessage() == "address_check_invalid") {
			$result = "address check invalid";
		} else if ($e->getMessage() == "cvc_check_invalid") {
			$result = "cvc check invalid";
		} else {
			$result = "Error Payment with Stripe";
		}
		$data['card_pay_loi'] = 0;
		$data['Errors'] = $result;
				  
	}
	
				}
					
					}
					else
					{
							$data['error_xuly']=3;
					}
				
					
				}
			}
			
		}
		else
		{
			$data['error_xuly']=2;
		}
		$where_user_card = array('user_id' => $user_id);
    	$order_user_card = array();
    	$data['user_card'] = $this->main_model->getAllData("luxyart_user_card", $where_user_card, $order_user_card)->result();
		$data['noindex'] = '1';
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['list_buy_point'] = $this->manager_user_model->get_list_buy_point()->result();
		$query = $this->db->query('SELECT user_point,user_paymoney_getpoint FROM luxyart_tb_user WHERE user_status = 1 and user_id="'.$user_id.'"');
		$row = $query->row();
		if (isset($row))
		{
				$data['user_point'] = number_format($row->user_point + $row->user_paymoney_getpoint);
		}
		$data['noindex'] = '1';
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
		$this->load->model('manager_user_model');
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
		$data['active_menu_sidebar'] = 'mypage-user-message';
		$this->load->library('pagination');
		$config['per_page'] = 13;
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
		$data['noindex'] = '1';
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
	public function do_user_delete_card_bank()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo "0{return_do_user_delete_card_bank}0";exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$cardid = $_POST['cardid'];
		$query = $this->db->query('SELECT * FROM luxyart_user_card WHERE luxyart_user_card.card_id = "'.$cardid.'" and luxyart_user_card.user_id="'.$user_id.'" limit 1');
		$row = $query->row();
		if ($query->num_rows()>0)
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('card_id', $cardid);
			$this->db->delete('luxyart_user_card');
			echo "1{return_do_user_delete_card_bank}";exit;
		}
		else
		{
			echo "0{return_do_user_delete_card_bank}0";exit;
		}
	}
	public function do_user_edit_card_bank()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			echo "0{return_do_user_edit_card_bank}0";exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$cardid = $_POST['card_id'];
		$cardnumber = $_POST['carnumber'];
		$cvc = $_POST['cvc'];
		$exp_month = $_POST['exp_month'];
		$exp_year = $_POST['exp_year'];
		$card_name = $_POST['card_name'];
		$query = $this->db->query('SELECT * FROM luxyart_user_card WHERE luxyart_user_card.card_id = "'.$cardid.'" and luxyart_user_card.user_id="'.$user_id.'" limit 1');
		$row = $query->row();
		if ($query->num_rows()>0)
		{
			$this->db->set('account_number', $cardnumber);
			$this->db->set('account_ccv_value', $cvc);
			$this->db->set('account_exp', $exp_month.$exp_year);
			$this->db->set('account_type_card', $card_name);  
			$this->db->where('user_id', $user_id);
			$this->db->where('card_id', $cardid);
			$this->db->update('luxyart_user_card');
			echo "1{return_do_user_edit_card_bank}";exit;
		}
		else
		{
			echo "0{return_do_user_edit_card_bank}0";exit;
		}
	}
	public function String2Stars($string='',$first=0,$last=0,$rep='*'){
	  $begin  = substr($string,0,$first);
	  $middle = str_repeat($rep,strlen(substr($string,$first,$last)));
	  $end    = substr($string,$last);
	  $stars  = $begin.$middle.$end;
	  return $stars;
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
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
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
		$data['noindex'] = '1';
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/user_purchase_order", $meta, $this->lang_id);
		$this->template->load('default', 'mypage_user_purchase_order', $data, __CLASS__);
	}
	
	public function listmember(){
		
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
		
		$user = $this->function_model->getUser($user_id);
		$data["user_level"] = $user->user_level;
		
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		//get list compe
		$this->load->library('pagination');
		$config['per_page'] = 10;
		
		//member
		$config['total_rows'] = count($this->manager_user_model->get_list_member($user_id,'','')->result());
		
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/listmember';
		
		$data['list_member'] = $this->manager_user_model->get_list_member($user_id,$config['per_page'],$this->uri->segment(3))->result();
		
		$this->pagination->initialize($config);
		$data['phantrang_member'] = $this->pagination->create_links();
		
		//get link invite
		$data['detail_user'] = $this->function_model->getUser($user_id);
		$code = $this->function_model->encrypt_id($user_id);
		$data["link_invite"] = base_url()."invite-member/".$code."-".$data['detail_user']->user_id_code_invite.".html";
		
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/listmember", $meta, $this->lang_id);
		
		$data['active_menu_sidebar'] = "listinvited";
		
		$data['noindex'] = '1';
		
		$this->template->load('default', 'listmember', $data, __CLASS__);
		
	}
	
	public function listinvited(){
	
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
		
		$user = $this->function_model->getUser($user_id);
		$data["user_level"] = $user->user_level;
	
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
	
		//get list compe
		$this->load->library('pagination');
		$config['per_page'] = 10;
	
		//invited
		$config['total_rows'] = count($this->manager_user_model->get_list_invited($user_id,'','')->result());
	
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/listinvited';
	
		$data['list_invited'] = $this->manager_user_model->get_list_invited($user_id,$config['per_page'],$this->uri->segment(3))->result();
	
		$this->pagination->initialize($config);
		$data['phantrang_invited'] = $this->pagination->create_links();
		
		//get link invite
		$data['detail_user'] = $this->function_model->getUser($user_id);
		$code = $this->function_model->encrypt_id($user_id);
		$data["link_invite"] = base_url()."invite-member/".$code."-".$data['detail_user']->user_id_code_invite.".html";
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/listinvited", $meta, $this->lang_id);
	
		$data['active_menu_sidebar'] = "listinvited";
	
		$data['noindex'] = '1';
	
		$this->template->load('default', 'listinvited', $data, __CLASS__);
	
	}
	
	public function listagent(){
	
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
		
		$user = $this->function_model->getUser($user_id);
		$data["user_level"] = $user->user_level;
	
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
		
		if($data["user_level"] != 4){
			header("Location: ".base_url()."mypage/user-update-account.html");
		}
	
		//get list compe
		$this->load->library('pagination');
		$config['per_page'] = 5;
	
		//invited
		$config['total_rows'] = count($this->manager_user_model->get_list_email_agent('','')->result());
	
		$config['uri_segment'] = 3;
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/mypage/listagent';
	
		$data['list_invited'] = $this->manager_user_model->get_list_email_agent($config['per_page'],$this->uri->segment(3))->result();
	
		$this->pagination->initialize($config);
		$data['phantrang_invited'] = $this->pagination->create_links();
	
		//get link invite
		$data['detail_user'] = $this->function_model->getUser($user_id);
		$code = $this->function_model->encrypt_id($user_id);
		$data["link_invite"] = base_url()."invite-member/".$code."-".$data['detail_user']->user_id_code_invite.".html";
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/listagent", $meta, $this->lang_id);
	
		$data['active_menu_sidebar'] = "listagent";
	
		$data['noindex'] = '1';
	
		$this->template->load('default', 'listagent', $data, __CLASS__);
	
	}
	
	function invite_member($str_code){
		
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		
		$code_explode = explode("-",$str_code);
	
		$code = $code_explode[0];
		$user_id_code_invite_member = $code_explode[1];
	
		$user_id = $this->function_model->decrypt_id($code);
		$user_id_2018 = $this->function_model->decrypt_id_2018($code);
		
		if(!is_integer($user_id)){
			$user_id = $user_id_2018;
		}
	
		if(!is_integer($user_id)){
			//notice
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
			exit;
		}
	
		$this->db->from('luxyart_tb_user');
		$this->db->select('user_id');
		$this->db->where('user_id', $user_id);
		$rs = $this->db->get();
		$row = $rs->row();
	
		if(empty($row)){
			//notice
			$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
			header("Location: ".base_url()."notice.html");
			exit;
		}
		
		if($logged_in['user_id'] == ""){
			
			$_SESSION['invite_member'] = $user_id;
			header("Location: ".base_url()."entry.html");
			
		}
		else{
			if($logged_in['user_id'] == $row->user_id){
				header("Location: ".base_url()."mypage.html");
			}
			else{
				header("Location: ".base_url()."accept-invite-member/".$str_code.".html");
			}
		}
	
	}
	
	function accept_invite_member($str_code){
	
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		
		$code_explode = explode("-",$str_code);
		$code = $code_explode[0];
		$user_id_code_invite_member = $code_explode[1];
		$user_id = $this->function_model->decrypt_id($code);
		
		if($logged_in['user_id'] == ""){
			
			$_SESSION['invite_member'] = $user_id;
			header("Location: ".base_url()."entry.html");
			
		}
		else{

			if($logged_in['user_id'] != $user_id){
					
				$data["user_id"] = $logged_in['user_id'];
				$data["member_id"] = $user_id;
					
				//get meta seo
				$meta = array();
				$data['meta_seo'] = $this->function_model->getMetaSeo("user/accept_invite_member", $meta, $this->lang_id);
					
				$data['noindex'] = '1';
				$this->template->load('default', 'accept_invite_member', $data, __CLASS__);
					
			}
			else{
				header("Location: ".base_url()."mypage.html");
			}
			
		}
	
	}
	
	public function addmember(){
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
	
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data['user_id'] = $user_id;
	
		$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}
	
		//get meta seo
		$meta = array();
		$data['meta_seo'] = $this->function_model->getMetaSeo("user/addmember", $meta, $this->lang_id);
	
		$data['active_menu_sidebar'] = "mypage-list-member";
	
		$data['noindex'] = '1';
	
		$this->template->load('default', 'addmember', $data, __CLASS__);
	
	}
	
	function accept_member(){
		
		session_start();
		
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$data["user_id"] = $user_id;
			
		$user = $this->function_model->getUser($user_id);
		
		$str_md5 = $this->uri->segment(2);
		
		$str_md5_explode = explode("-",$str_md5);
		$member_id = $str_md5_explode[0];
		$md5 = $str_md5_explode[1];
		
		$this->db->from('luxyart_tb_member');
		$this->db->select('*');
		$this->db->where(array('member_id'=>$member_id));
		
		$rsMember = $this->db->get();
		$resultMember = $rsMember->row();
		
		if(md5($resultMember->user_id) == $md5){
			
			$user_invited = $this->function_model->getUser($resultMember->user_id);
			if($user_invited->user_status == 2 || $user_invited->user_status == 3){
				$_SESSION['return_key'] = 'this_user_stop_using_this_account';
				header("Location: ".base_url()."notice.html");
			}
			
			if($user_id != ""){
				if(($resultMember->user_id == $user_id) || ($resultMember->email_invite != $user->user_email)){
					$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
					header("Location: ".base_url()."notice.html");
				}
				if(($resultMember->status == 1) || ($resultMember->status == 2)){
					$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
					header("Location: ".base_url()."notice.html");
				}
			}
			else{
				if(($resultMember->status == 1) || ($resultMember->status == 2)){
					$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
					header("Location: ".base_url()."notice.html");
				}
			}
			
			$data["member_id"] = $member_id;
			$data["md5"] = $md5;
			
			$_SESSION["member_id"] = $member_id;
			$_SESSION["md5"] = $md5;
			
			$this->db->from('luxyart_tb_user');
			$this->db->select('*');
			$this->db->where(array('user_email'=>$resultMember->email_invite));
			$rsEmailInvite = $this->db->get();
			$countEmailInvite = $rsEmailInvite->num_rows();
			
			if($countEmailInvite == 0){
				$data['is_exist_user'] = 0;
			}
			else{
				$data['is_exist_user'] = 1;
			}
			
			//get meta seo
			$meta = array();
			$data['meta_seo'] = $this->function_model->getMetaSeo("user/accept_member", $meta, $this->lang_id);
				
			$data['active_menu_sidebar'] = "mypage-list-member";
				
			$data['noindex'] = '1';
				
			$this->template->load('default', 'accept_member', $data, __CLASS__);
			
		}
		else{
			
			header("Location: ".base_url());
			
		}
		
	}
	
	function not_accept_member(){
		
		session_start();
	
		$str_md5 = $this->uri->segment(2);
		
		//get logged in
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		$user = $this->function_model->getUser($user_id);
		
		$str_md5_explode = explode("-",$str_md5);
		$member_id = $str_md5_explode[0];
		$md5 = $str_md5_explode[1];
		
		$this->db->from('luxyart_tb_member');
		$this->db->select('*');
		$this->db->where(array('member_id'=>$member_id));
		
		$rsMember = $this->db->get();
		$resultMember = $rsMember->row();
		
		if(md5($resultMember->user_id) == $md5){
			
			if($user_id != ""){
				if(($resultMember->user_id == $user_id) || ($resultMember->email_invite != $user->user_email)){
					$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
					header("Location: ".base_url()."notice.html");
				}
				if(($resultMember->status == 1) || ($resultMember->status == 2)){
					$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
					header("Location: ".base_url()."notice.html");
				}
			}
			else{
				if(($resultMember->status == 1) || ($resultMember->status == 2)){
					$_SESSION['return_key'] = 'you_do_not_have_permission_to_access_this_page';
					header("Location: ".base_url()."notice.html");
				}
			}
			
			$this->db->from('luxyart_tb_user');
			$this->db->select('*');
			$this->db->where(array('user_id'=>$resultMember->user_id));
			
			$rsUserMember = $this->db->get();
			$resultUserMember = $rsUserMember->row();
			
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "not_accept_member";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $resultUserMember->user_email;
			$dataSendEmail["name"] 					= ucwords($resultUserMember->display_name);
			$dataSendEmail["email_invite"] 			= $resultMember->email_invite;
			$dataSendEmail["link_invite"] 			= base_url()."mypage/listmember";
			//$dataSendEmail["is_debug"]			= 1;
				
			$this->function_model->sendMailHTML($dataSendEmail);
			
			$this->db->set('status', 1);
			if($user_id != ""){
				$this->db->set('user_id_invited', $user_id);
			}
			$this->db->set('date_invite', date('Y-m-d H:i:s'));
			$this->db->where('member_id', $member_id);
			$this->db->update('luxyart_tb_member');
			
			//get meta seo
			$_SESSION['return_key'] = 'user/not_accept_member_success';
			header("Location: ".base_url()."notice.html");
			
			exit;
				
		}
		else{
				
			header("Location: ".base_url());
				
		}
	
	}
	
	public function user_message_detail()
	{
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		/*$check_user_input = $this->manager_user_model->check_user_input_basic($user_id);
		if(!$check_user_input){
			header("Location: ".base_url()."mypage/user-config.html");
		}*/
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
		$data['noindex'] = '1';
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