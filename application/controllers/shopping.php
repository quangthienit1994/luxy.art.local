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

class Shopping extends CI_Controller {

	public function __construct() {
		
		parent::__construct();
		
		@session_start();
		$this->load->library('session');
		$this->load->library('cart');
		
		//$this->load->model('main_model');
        $this->load->model('shopping_model');
        $this->load->model('manager_image_model');
        $this->load->model('function_model');
        
        $dir = getcwd();
        define("DIR", $dir."/publics/");
        define("DIR_TEMP", $dir."");
        define("DIR_DOWNLOAD", $dir."/publics/product_img/download/");
		
		//language message
		$CI =& get_instance();//do this only once in this file
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
	
	function index(){
		
		$this->load->library('pagination');
		$this->db->select('*');
		$this->db->from('cart');
		$this->db->order_by('id desc');
		$offset=$this->uri->segment(2);
		$limit= 6;
		$this->db->limit($limit, $offset);
		$query_poster = $this->db->get();
		
		// pagination
		$config['base_url'] = site_url() . '/shopping/';
		$config['total_rows'] = $this->db->count_all('pagination');
		$config['uri_segment']  = 2;
		$config['per_page'] = $limit;
		$config['prev_link'] 	= '&lt;';
		$config['next_link'] 	= '&gt;';
		$config['last_link'] 	= 'Cuối';
		$config['first_link']	= 'Đầu';
		$this->pagination->initialize($config);
		$paginator=$this->pagination->create_links();
		
		// End pagination
		$ndata = array(
				'paginator'     =>$paginator,
				'post'          =>$query_poster,
				'title'         => "title shopping cart",
				'keywords'      => "keywords shopping cart",
				'description'   => "Description shopping cart"
		);
		
		$this->load->view('tem-shopping',$ndata);
	}
	
	function add(){
		
		@session_start();
		$insert_data = array(
				'id' => $this->input->post('id'),
				'name' => $this->input->post('name'),
				'price' => $this->input->post('price'),
				'qty' => 1
		);
	
		$this->cart->insert($insert_data);
		//redirect('shopping.html');
		redirect('danh-muc-san-pham/ao-1');
	}
	
	function add_cart(){
		
		$id_img_size = $_POST['id_img_size'];
		
		if($id_img_size > 0){
		
			$data['add_cart'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
			
			$cart_check = $this->cart->contents();
			if(!empty($cart_check)) {
				
				foreach($cart_check as $_cart_check){
				
					$data_add_cart = $this->shopping_model->get_data_add_cart($_cart_check['id'])->row();
				
					if($data_add_cart->id_img == $data['add_cart']->id_img){
							
						echo 0;
						exit;
							
					}
				
				}
				
			}
			
			$insert_data = array(
				'id' 			=> $data['add_cart']->id_img_size,
				'name' 			=> $data['add_cart']->type_size_description,
				'price' 		=> $data['add_cart']->price_size_point,
				'qty' 			=> 1
			);
			
			$this->cart->insert($insert_data);
			
			echo 1;
			exit;
		
		}
		else{

			echo 0;
			exit;
			
		}
		
	}
	
	function add_cart_all(){
		
		//check permission
		$is_permission = $this->function_model->check_permission(2);
		if(!$is_permission){
			echo 0;
			exit;
		}
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$id_bookmark = $_POST['id_bookmark'];
		$id_bookmarks = explode(",",$id_bookmark);
		$id_imgs = array();
		
		$this->db->from('luxyart_tb_user_bookmark_image');
		$this->db->select('id_img');
		$this->db->where(array('user_id' => $user_id));
		$this->db->where_in('id_bookmark', $id_bookmarks, FALSE);
		$rs = $this->db->get();
		$result = $rs->result();
		
		foreach($result as $row){
			
			$id_img = $row->id_img;
			$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
			$time_remaining = $this->manager_image_model->get_time_remaining($id_img);
			
			if($detail_product->img_is_join_compe == 0 && $detail_product->img_is_sold == 0 && $time_remaining != -1){				
				array_push($id_imgs, $id_img);
			}
			
		}
		
		$i = 0;
		
		if(!empty($id_imgs)){
			
			$cart_check = $this->cart->contents();
			$arr_add_cart = array();
			
			foreach($id_imgs as $id_img){
				
				$id_img_size = $this->shopping_model->get_max_id_img_size($id_img);
				$add_cart = $this->shopping_model->get_data_add_cart($id_img_size)->row();
				
				if(!empty($cart_check)){
					
					foreach($cart_check as $_cart_check){
					
						$data_add_cart = $this->shopping_model->get_data_add_cart($_cart_check['id'])->row();
						array_push($arr_add_cart, $data_add_cart->id_img);
						$arr_add_cart = array_unique($arr_add_cart);
					
						if(!in_array($id_img, $arr_add_cart)){
					
							$insert_data = array(
								'id' 			=> $add_cart->id_img_size,
								'name' 			=> $add_cart->type_size_description,
								'price' 		=> $add_cart->price_size_point,
								'qty' 			=> 1
							);
							$this->cart->insert($insert_data);
							$i++;
					
						}
					
					}
					
				}
				else{
					
					$insert_data = array(
						'id' 			=> $add_cart->id_img_size,
						'name' 			=> $add_cart->type_size_description,
						'price' 		=> $add_cart->price_size_point,
						'qty' 			=> 1
					);
					$this->cart->insert($insert_data);
					$i++;
					
				}
				
			}
			
			echo $i;
			exit;
			
		}
		else{
			echo 0;
			exit;
		}
	
	}
	
	function remove($rowid){
		
		if ($rowid==="all"){
			$this->cart->destroy();
		}
		else{
			$data = array(
					'id' => $rowid,
					'qty' => 0
			);
			$this->cart->update($data);
		}
		redirect('shopping.html');
	}
	
	function remove_cart(){
		
		$rowid = $_POST['rowid'];
		
		if($rowid != ""){
			
			$data = array(
				'id' => $rowid,
				'qty' => 0
			);
			
			$this->cart->update($data);
			
			echo 1;
			exit;
			
		}
		else{
			
			echo 0;
			exit;
			
		}
		
	}
	
	function update(){
		
		$cart_info = $_POST['cart'];
		foreach( $cart_info as $id => $cart){
			$rowid = $cart['rowid'];
			$price = $cart['price'];
			$amount = $price * $cart['qty'];
			$qty = $cart['qty'];
			$data = array(
					'rowid' => $rowid,
					'price' => $price,
					'amount' => $amount,
					'qty' => $qty
			);
			$this->cart->update($data);
		}
		redirect('shopping.html');
	}
	
	function update_cart(){
	
		$rowid = $_POST['rowid'];
		$id_img_size = $_POST['id_img_size'];
		
		if($rowid != ""){
			
			$data = array(
				'id' => $rowid,
				'qty' => 0
			);
			$this->cart->update($data);
			
			$data['add_cart'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
			
			$insert_data = array(
				'id' 			=> $data['add_cart']->id_img_size,
				'name' 			=> $data['add_cart']->type_size_description,
				'price' 		=> $data['add_cart']->price_size_point,
				'qty' 			=> 1
			);
				
			$this->cart->insert($insert_data);
			
			echo 1;
			exit;
			
		}
		else{
		
			echo 0;
			exit;
				
		}
		
	}
	
	function billing_view(){
		
		$data = array(
			'title'          => "Title thanh toán",
			'keywords'       => "Keywords thanh toán",
			'description'    => "Description thanh toán"
		);
		 
		//get group
		$whereGroup = array('group.group_status' => 1);
		$orderGroup = array('group.group_name' => 'asc');
		$data['group'] = $this->main_model->getAllData("group", $whereGroup, $orderGroup)->result();
	
		$this->template->load('default', 'tem-thanhtoan', $data, __CLASS__);
		 
		//$this->load->view('tem-thanhtoan',$ndata);
	}
	
	function save_order(){
		
		$customer = array(
				'name'          => $this->input->post('name'),
				'email'         => $this->input->post('email'),
				'phone'         => $this->input->post('phone'),
				'address'       => $this->input->post('address'),
				'date'          => date('Y-m-d')
		);
		$cust_id = $this->shopping_model->insert_thanhtoan($customer);
	
		$order = array(
				'date' => date('Y-m-d'),
				'customerid' => $cust_id
		);
	
		$ord_id = $this->shopping_model->insert_order($order);
	
		if ($cart = $this->cart->contents()):
		foreach ($cart as $item):
		$order_detail = array(
				'orderid' => $ord_id,
				'productid' => $item['id'],
				'quantity' => $item['qty'],
				'price' => $item['price']
		);
		$cust_id = $this->shopping_model->insert_order_detail($order_detail);
		endforeach;
		endif;
		$this->load->view('tem-success');
	}
	
	function save_to_cart(){
		
		$this->load->library('sftp');
		
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
		
		$data['detail_user'] = $this->function_model->getUser($user_id);
		
		$setting = $this->function_model->get_setting();
		$value_end_download = "+".$setting['value_end_download']." month";
		$admin_get_point = $setting['admin_get_point'];
		$user_get_point = $setting['user_get_point'];
		$investor_get_point = $setting['investor_get_point'];
		$invite_get_point = $setting['invite_get_point'];
		$seller_get_point = $setting['seller_get_point'];

		$id_img_size = $_POST['id_img_size'];
		$rowid = $_POST['rowid'];
		
		if($id_img_size > 0 && $rowid != ""){
			
			$data['add_cart'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
			
			$is_check_image_bought = $this->shopping_model->check_image_bought($data['add_cart']->id_img,$id_img_size,$user_id);
			if($is_check_image_bought == 1){
				$bought_buy_again = $this->lang->line('you_bought_the_image_size_then_buy_again');
				$bought_buy_again = str_replace("{type_size_description}", $data['add_cart']->type_size_description, $bought_buy_again);
				echo "0{luxyart}".$bought_buy_again;
				exit;
			}
			
			$price_size_point = $data['add_cart']->price_size_point;
			$size_stock = $data['add_cart']->size_stock;
			$is_limit = $data['add_cart']->is_limit;
			
			$date_end_download = date('Y-m-d H:i:s', strtotime($value_end_download, time()));
			
			if($data['add_cart']->user_id == $user_id){
				echo "0{luxyart}".$this->lang->line('you_can_not_purchase_your_photo');
				exit;
			}
			
			if($size_stock == 0 && $is_limit == 1){
				echo "0{luxyart}".$this->lang->line('this_image_is_out_of_stock');
				exit;
			}
			
			$user_paymoney_getpoint = $data['detail_user']->user_paymoney_getpoint;
			$user_point = $data['detail_user']->user_point;
			$total_point = $user_paymoney_getpoint + $user_point;
			
			if($total_point < $price_size_point){
				echo "0{luxyart}".$this->lang->line('you_do_not_have_enough_points_to_buy_this_photo');
				exit;
			}
			
			//add order
			$dataAddProductImgOrder = array(
				'user_buy_img' 				=> $user_id,
				'total_point_order' 		=> $price_size_point,
				'date_order_img' 			=> date('Y-m-d H:i:s'),
				'status_order'				=> 2,
				'date_end_download'			=> $date_end_download
			);
			
			$this->db->insert('luxyart_tb_product_img_order', $dataAddProductImgOrder);
			$id_order_detail = $this->db->insert_id();
			
			$point_user_owner_get = $user_get_point*$price_size_point/100;
			$investor_get_point = $investor_get_point*$price_size_point/100;
			
			$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart");
			
			//invite
			$user_invite_sale = 0;
			$point_user_invite_sale_get = 0;
			$invite_sale = $this->session->userdata['invite_sale'];
			if($invite_sale){
				$invite_user_invite_sale = $invite_sale['user_invite_sale'];
				$invite_id_img = $invite_sale['id_img'];
				if($invite_id_img == $data['add_cart']->id_img){
					$user_invite_sale = $invite_user_invite_sale;
					$point_user_invite_sale_get = $invite_get_point*$price_size_point/100;
				}
			}
			
			//get img
			$user_get_img = 0;
			$point_user_get_img = 0;
			$get_img = $this->session->userdata['get_img'];
			if($get_img){
				$get_user_id = $get_img['user_id_get_img'];
				$get_id_img = $get_img['id_img'];
				if($get_id_img == $data['add_cart']->id_img){
					$user_get_img = $get_user_id;
					$point_user_get_img = $seller_get_point*$price_size_point/100;
				}
			}	
			
			$admin_get_point = $price_size_point - $point_user_owner_get - $investor_get_point - $point_user_invite_sale_get - $point_user_get_img;
		
			//add order detail
			$dataAddProductImgOrder = array(
				'id_order_img' 					=> $id_order_detail,
				'id_img' 						=> $data['add_cart']->id_img,
				'id_img_size' 					=> $data['add_cart']->id_img_size,
				'user_owner' 					=> $data['add_cart']->user_id,
				'point_user_owner_get'			=> $point_user_owner_get,
				'user_invite_sale' 				=> $user_invite_sale,
				'point_user_invite_sale_get' 	=> $point_user_invite_sale_get,
				'user_keep_store' 				=> $user_get_img,
				'point_user_keep_store_get'		=> $point_user_get_img,
				'admin_get_point'				=> $admin_get_point,
				'investor_get_point'			=> $investor_get_point
			);
			$this->db->insert('luxyart_tb_product_img_order_detail', $dataAddProductImgOrder);
			
			//user buy: 1.minus point 2.notice
			if($user_paymoney_getpoint - $price_size_point >= 0){
			
				$this->db->where('user_id', $user_id);
				$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint-'.$price_size_point,FALSE);
				$this->db->update('luxyart_tb_user');
				
				$this->function_model->set_use_point_package(round($price_size_point,2), $user_id);
			
			}
			else{
			
				$this->db->where('user_id', $user_id);
				$this->db->set('user_paymoney_getpoint',0);
				$this->db->update('luxyart_tb_user');
			
				$this->db->where('user_id', $user_id);
				$this->db->set('user_point','user_point-'.$price_size_point."+".$user_paymoney_getpoint,FALSE);
				$this->db->update('luxyart_tb_user');
			
			}
				
			$notice_point_user_buy = $this->lang->line('management_point_shopping_cart_user_buy');
			$notice_point_user_buy = str_replace("{point}", $price_size_point, $notice_point_user_buy);
			$notice_point_user_buy = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_buy);
			$notice_point_user_buy = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_buy);
				
			$dataAddPointUser = array(
					'user_id' 						=> $user_id,
					'point' 						=> $price_size_point,
					'status_change_point' 			=> 2,
					'inbox_id' 						=> $inbox_id,
					'content' 						=> $notice_point_user_buy,
					'date_add' 						=> date('Y-m-d H:i:s')
			);
			
			$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
			
			$addNotice = array();
			$addNotice['type'] 						= "shopping_cart_user_buy";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user_id;
			$addNotice['user_send_id'] 				= -1;
			$addNotice['point'] 					= $price_size_point;
			$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
			$addNotice['img_title'] 				= $data['add_cart']->img_title;
			//$addNotice['is_debug'] 				= 1;
			
			$this->function_model->addNotice($addNotice);
			
			//user sale: 1:add point 2:notice
			$this->db->where('user_id', $data['add_cart']->user_id);
			$this->db->set('user_point','user_point+'.$point_user_owner_get,FALSE);
			$this->db->update('luxyart_tb_user');
				
			$user_fullname = $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
				
			$notice_point_user_get_point = $this->lang->line('management_point_shopping_cart_user_get_point');
			$notice_point_user_get_point = str_replace("{point}", $point_user_owner_get, $notice_point_user_get_point);
			$notice_point_user_get_point = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_point);
			$notice_point_user_get_point = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_point);
			$notice_point_user_get_point = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_point);
				
			$dataAddPointUser = array(
					'user_id' 						=> $data['add_cart']->user_id,
					'point' 						=> $point_user_owner_get,
					'status_change_point' 			=> 1,
					'inbox_id' 						=> $inbox_id,
					'content' 						=> $notice_point_user_get_point,
					'date_add' 						=> date('Y-m-d H:i:s')
			);
			
			$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
			
			$addNotice = array();
			$addNotice['type'] 						= "shopping_cart_user_sale_image";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $data['add_cart']->user_id;
			$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
			$addNotice['point'] 					= $point_user_owner_get;
			$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
			$addNotice['img_title'] 				= $data['add_cart']->img_title;
			//$addNotice['is_debug'] 				= 1;
			
			$this->function_model->addNotice($addNotice);
			
			//user invite
			if($point_user_invite_sale_get > 0){
				
				$data['detail_user_invite_sale'] = $this->function_model->getUser($user_invite_sale);
				
				$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_invite_image");
				
				$this->db->where('user_id', $user_invite_sale);
				$this->db->set('user_point','user_point+'.$point_user_invite_sale_get,FALSE);
				$this->db->update('luxyart_tb_user');
				
				$user_fullname = $this->function_model->get_fullname($data['detail_user_invite_sale']->user_id, $this->lang_id);
				
				$notice_point_user_invite = $this->lang->line('management_point_shopping_cart_user_invite');
				$notice_point_user_invite = str_replace("{point}", $point_user_invite_sale_get, $notice_point_user_invite);
				$notice_point_user_invite = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_invite);
				$notice_point_user_invite = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_invite);
				$notice_point_user_invite = str_replace("{user_fullname}", $user_fullname, $notice_point_user_invite);
				
				$dataAddPointUser = array(
					'user_id' 						=> $user_invite_sale,
					'point' 						=> $point_user_invite_sale_get,
					'status_change_point' 			=> 1,
					'inbox_id' 						=> $inbox_id,
					'content' 						=> $notice_point_user_invite,
					'date_add' 						=> date('Y-m-d H:i:s')
				);
					
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
				
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_invite_image";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_invite_sale;
				$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
				$addNotice['point'] 					= $point_user_invite_sale_get;
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$dataSendEmail["is_debug"] 			= 1;
				
				$this->function_model->addNotice($addNotice);
				
			}
			
			//user get img
			if($point_user_get_img > 0){
			
				$data['detail_user_get_img'] = $this->function_model->getUser($user_get_img);
			
				$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_get_image");
			
				$this->db->where('user_id', $user_get_img);
				$this->db->set('user_point','user_point+'.$point_user_get_img,FALSE);
				$this->db->update('luxyart_tb_user');
			
				$user_fullname = $this->function_model->get_fullname($data['detail_user_get_img']->user_id, $this->lang_id);
			
				$notice_point_user_get_img = $this->lang->line('management_point_shopping_cart_user_get_img');
				$notice_point_user_get_img = str_replace("{point}", $point_user_get_img, $notice_point_user_get_img);
				$notice_point_user_get_img = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_img);
				$notice_point_user_get_img = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_img);
				$notice_point_user_get_img = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_img);
			
				$dataAddPointUser = array(
					'user_id' 						=> $user_get_img,
					'point' 						=> $point_user_get_img,
					'status_change_point' 			=> 1,
					'inbox_id' 						=> $inbox_id,
					'content' 						=> $notice_point_user_get_img,
					'date_add' 						=> date('Y-m-d H:i:s')
				);
					
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
			
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_get_image";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_get_img;
				$addNotice['user_fullname'] 			= $data['detail_user_get_img']->display_name;
				$addNotice['point'] 					= $point_user_get_img;
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$addNotice["is_debug"] 				= 1;
			
				$this->function_model->addNotice($addNotice);
			
			}
			
			//update size stock
			if($is_limit == 1){
				
				$this->db->where('id_img_size', $id_img_size);
				$this->db->set('size_stock','size_stock-1',FALSE);
				$this->db->update('luxyart_tb_product_img_size');
				
				$list_img_size = $this->manager_image_model->get_list_img_size($data['add_cart']->id_img)->result();
				if(count($list_img_size) == 0){
					$this->db->where('id_img', $data['add_cart']->id_img);
					$this->db->set('img_is_sold',1);
					$this->db->update('luxyart_tb_product_img');
				}
				
			}
			
			//download file to folder download
			$manager_server = $this->function_model->getManagerServer($data['add_cart']->id_server);
			
			//connect server old
			$sftp_config['hostname'] 	= $manager_server->server_name;
			$sftp_config['username'] 	= $manager_server->server_ftp_account;
			$sftp_config['password'] 	= $manager_server->server_ftp_pass;
			$sftp_config['port'] 		= $manager_server->server_port;
			$sftp_config['debug'] 		= TRUE;
			$this->sftp->connect($sftp_config);
			
			$file_name_original_img = $data['add_cart']->file_name_original_img;
			$file_name_original_img_name = explode(".",$file_name_original_img)[0];
			
			$type_size_description = $data['add_cart']->type_size_description;
			$type_size_description_width = explode("px",$type_size_description)[0];
			
			$this->sftp->download($manager_server->server_path_upload_img."/".$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img);
			$this->function_model->resize($type_size_description_width, DIR_DOWNLOAD.$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img_name);
			
			//send email buy image
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "shopping_cart_user_buy";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['detail_user']->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
			$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
			$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
			$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
			$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
			$dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
			$dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
			$dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
			$dataSendEmail["date_end_download"] 	= $date_end_download;
			$dataSendEmail["file_attachment"] 		= DIR_DOWNLOAD.$data['add_cart']->file_name_original_img;
			$dataSendEmail["is_send_admin"] 		= 1;
			//$dataSendEmail["is_debug"] 			= 1;
			
			$this->function_model->sendMailHTML($dataSendEmail);
			
			//send email sale image
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "shopping_cart_user_sale";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['add_cart']->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['add_cart']->user_id,$this->lang_id));
			$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
			$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
			$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
			$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
			$dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
			$dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
			$dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
			$dataSendEmail["date_end_download"] 	= $date_end_download;
			//$dataSendEmail["is_debug"] 			= 1;
			
			//send email invite image
			if($point_user_invite_sale_get > 0){
				
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "shopping_cart_user_invite";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_user_invite_sale']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_invite_sale']->user_id,$this->lang_id));
				$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
				$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
				$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
				$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
				$dataSendEmail["email_buy_image"] 		= $data['detail_user_invite_sale']->user_email;
				$dataSendEmail["date_end_download"] 	= $date_end_download;
				//$dataSendEmail["is_debug"] 			= 1;
				
				$this->function_model->sendMailHTML($dataSendEmail);
			
			}
			
			//send email invite image
			if($point_user_get_img > 0){

				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "shopping_cart_user_get_img";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_user_get_img']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_get_img']->user_id,$this->lang_id));
				$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
				$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
				$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
				$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
				$dataSendEmail["email_buy_image"] 		= $data['detail_user_get_img']->user_email;
				$dataSendEmail["date_end_download"] 	= $date_end_download;
				//$dataSendEmail["is_debug"] 			= 1;
					
				$this->function_model->sendMailHTML($dataSendEmail);
					
			}
			
			//delete file temp
			unlink(DIR_DOWNLOAD.$data['add_cart']->file_name_original_img);
			
			//remove
			$data = array(
				'id' => $rowid,
				'qty' => 0
			);
				
			$this->cart->update($data);
			
			$this->session->set_userdata('is_save_to_cart', 1);
			
			echo 1;
			exit;
			
			
		}

	}
	
	function save_to_cart_test(){
		
		session_start();
	
		$this->load->library('sftp');
		
		$save_email = array();
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
	
		$data['detail_user'] = $this->function_model->getUser($user_id);
	
		$setting = $this->function_model->get_setting();
		$value_end_download = "+".$setting['value_end_download']." month";
		$admin_get_point = $setting['admin_get_point'];
		$user_get_point = $setting['user_get_point'];
		$investor_get_point = $setting['investor_get_point'];
		$invite_get_point = $setting['invite_get_point'];
		$seller_get_point = $setting['seller_get_point'];
		$member_get_point = $setting['member_get_point'];
	
		$id_img_size = $_POST['id_img_size'];
		$rowid = $_POST['rowid'];
	
		if($id_img_size > 0 && $rowid != ""){
				
			$data['add_cart'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
				
			$is_check_image_bought = $this->shopping_model->check_image_bought($data['add_cart']->id_img,$id_img_size,$user_id);
			if($is_check_image_bought == 1){
				$bought_buy_again = $this->lang->line('you_bought_the_image_size_then_buy_again');
				$bought_buy_again = str_replace("{type_size_description}", $data['add_cart']->type_size_description, $bought_buy_again);
				echo "0{luxyart}".$bought_buy_again;
				exit;
			}
				
			$price_size_point = $data['add_cart']->price_size_point;
			$size_stock = $data['add_cart']->size_stock;
			$is_limit = $data['add_cart']->is_limit;
				
			$date_end_download = date('Y-m-d H:i:s', strtotime($value_end_download, time()));
				
			if($data['add_cart']->user_id == $user_id){
				echo "0{luxyart}".$this->lang->line('you_can_not_purchase_your_photo');
				exit;
			}
				
			if($size_stock == 0 && $is_limit == 1){
				echo "0{luxyart}".$this->lang->line('this_image_is_out_of_stock');
				exit;
			}
				
			$user_paymoney_getpoint = $data['detail_user']->user_paymoney_getpoint;
			$user_point = $data['detail_user']->user_point;
			$total_point = $user_paymoney_getpoint + $user_point;
				
			if($total_point < $price_size_point){
				echo "0{luxyart}".$this->lang->line('you_do_not_have_enough_points_to_buy_this_photo');
				exit;
			}
				
			//add order
			$dataAddProductImgOrder = array(
				'user_buy_img' 				=> $user_id,
				'total_point_order' 		=> $price_size_point,
				'date_order_img' 			=> date('Y-m-d H:i:s'),
				'status_order'				=> 2,
				'date_end_download'			=> $date_end_download
			);
				
			$this->db->insert('luxyart_tb_product_img_order', $dataAddProductImgOrder);
			$id_order_detail = $this->db->insert_id();
				
			$point_user_owner_get = $user_get_point*$price_size_point/100;
			$investor_get_point = $investor_get_point*$price_size_point/100;
			$member_get_point = $member_get_point*$price_size_point/100;
				
			$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart");

			//member
			$this->db->from('luxyart_tb_member');
			$this->db->select('*');
			$this->db->where(array('user_id_invited' => $data['add_cart']->user_id,'status' => 2));
			$rsMember = $this->db->get();
			$countMember = $rsMember->num_rows();
			
			$user_member_get = 0;
			$point_user_member_get = 0;
			if($countMember == 1){
				$resultMember = $rsMember->row();
				$user_member_get = $resultMember->user_id;
				$point_user_member_get = $member_get_point*$price_size_point/100;
			}
			
			//invite
			$user_invite_sale = 0;
			$point_user_invite_sale_get = 0;
			$invite_sale = $this->session->userdata['invite_sale'];
			if($invite_sale){
				$invite_user_invite_sale = $invite_sale['user_invite_sale'];
				$invite_id_img = $invite_sale['id_img'];
				if($invite_id_img == $data['add_cart']->id_img){
					$user_invite_sale = $invite_user_invite_sale;
					$point_user_invite_sale_get = $invite_get_point*$price_size_point/100;
				}
			}
				
			//get img
			$user_get_img = 0;
			$point_user_get_img = 0;
			$get_img = $this->session->userdata['get_img'];
			if($get_img){
				$get_user_id = $get_img['user_id_get_img'];
				$get_id_img = $get_img['id_img'];
				if($get_id_img == $data['add_cart']->id_img){
					$user_get_img = $get_user_id;
					$point_user_get_img = $seller_get_point*$price_size_point/100;
				}
			}

			//admin
			$admin_get_point = $price_size_point - $point_user_owner_get - $investor_get_point - $point_user_invite_sale_get - $point_user_get_img;
	
			//add order detail
			$dataAddProductImgOrder = array(
					'id_order_img' 					=> $id_order_detail,
					'id_img' 						=> $data['add_cart']->id_img,
					'id_img_size' 					=> $data['add_cart']->id_img_size,
					'user_owner' 					=> $data['add_cart']->user_id,
					'point_user_owner_get'			=> $point_user_owner_get - $point_user_member_get,
					'user_invite_sale' 				=> $user_invite_sale,
					'point_user_invite_sale_get' 	=> $point_user_invite_sale_get,
					'user_keep_store' 				=> $user_get_img,
					'point_user_keep_store_get'		=> $point_user_get_img,
					'admin_get_point'				=> $admin_get_point,
					'investor_get_point'			=> $investor_get_point,
					'user_member_get'				=> $user_member_get,
					'point_user_member_get'			=> $point_user_member_get
			);
			$this->db->insert('luxyart_tb_product_img_order_detail', $dataAddProductImgOrder);
				
			//user buy: 1.minus point 2.notice
			if($user_paymoney_getpoint - $price_size_point >= 0){
					
				$this->db->where('user_id', $user_id);
				$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint-'.$price_size_point,FALSE);
				$this->db->update('luxyart_tb_user');
	
				$this->function_model->set_use_point_package(round($price_size_point,2), $user_id);
					
			}
			else{
					
				$this->db->where('user_id', $user_id);
				$this->db->set('user_paymoney_getpoint',0);
				$this->db->update('luxyart_tb_user');
					
				$this->db->where('user_id', $user_id);
				$this->db->set('user_point','user_point-'.$price_size_point."+".$user_paymoney_getpoint,FALSE);
				$this->db->update('luxyart_tb_user');
					
			}
	
			$notice_point_user_buy = $this->lang->line('management_point_shopping_cart_user_buy');
			$notice_point_user_buy = str_replace("{point}", $price_size_point, $notice_point_user_buy);
			$notice_point_user_buy = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_buy);
			$notice_point_user_buy = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_buy);
	
			$dataAddPointUser = array(
					'user_id' 						=> $user_id,
					'point' 						=> $price_size_point,
					'status_change_point' 			=> 2,
					'inbox_id' 						=> $inbox_id,
					'content' 						=> $notice_point_user_buy,
					'date_add' 						=> date('Y-m-d H:i:s')
			);
				
			$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
				
			$addNotice = array();
			$addNotice['type'] 						= "shopping_cart_user_buy";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $user_id;
			$addNotice['user_send_id'] 				= -1;
			$addNotice['point'] 					= $price_size_point;
			$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
			$addNotice['img_title'] 				= $data['add_cart']->img_title;
			//$addNotice['is_debug'] 				= 1;
				
			$this->function_model->addNotice($addNotice);
				
			//user sale: 1:add point 2:notice
			$this->db->where('user_id', $data['add_cart']->user_id);
			$this->db->set('user_point','user_point+'.($point_user_owner_get-$point_user_member_get),FALSE);
			$this->db->update('luxyart_tb_user');
	
			$user_fullname = $data['detail_user']->display_name;
	
			$notice_point_user_get_point = $this->lang->line('management_point_shopping_cart_user_get_point');
			$notice_point_user_get_point = str_replace("{point}", ($point_user_owner_get-$point_user_member_get), $notice_point_user_get_point);
			$notice_point_user_get_point = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_point);
			$notice_point_user_get_point = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_point);
			$notice_point_user_get_point = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_point);
	
			$dataAddPointUser = array(
					'user_id' 						=> $data['add_cart']->user_id,
					'point' 						=> ($point_user_owner_get-$point_user_member_get),
					'status_change_point' 			=> 1,
					'inbox_id' 						=> $inbox_id,
					'content' 						=> $notice_point_user_get_point,
					'date_add' 						=> date('Y-m-d H:i:s')
			);
			$this->db->insert('luxyart_tb_management_point2', $dataAddPointUser);
				
			$addNotice = array();
			$addNotice['type'] 						= "shopping_cart_user_sale_image";
			$addNotice["lang_id"] 					= $this->lang_id;
			$addNotice['user_id'] 					= $data['add_cart']->user_id;
			$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
			$addNotice['point'] 					= ($point_user_owner_get-$point_user_member_get);
			$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
			$addNotice['img_title'] 				= $data['add_cart']->img_title;
			//$addNotice['is_debug'] 				= 1;
				
			$this->function_model->addNotice($addNotice);
			
			//user member
			if($point_user_member_get > 0){
					
				$data['detail_user_member_get'] = $this->function_model->getUser($user_member_get);
				$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_member_get");
					
				$this->db->where('user_id', $user_member_get);
				$this->db->set('user_point','user_point+'.$point_user_member_get,FALSE);
				$this->db->update('luxyart_tb_user');
					
				$user_fullname = $data['detail_user_member_get']->display_name;
					
				$notice_point_user_member_get = $this->lang->line('management_point_shopping_cart_user_member_get');
				$notice_point_user_member_get = str_replace("{point}", $point_user_member_get, $notice_point_user_member_get);
				$notice_point_user_member_get = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_member_get);
				$notice_point_user_member_get = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_member_get);
				$notice_point_user_member_get = str_replace("{user_fullname}", $user_fullname, $notice_point_user_member_get);
					
				$dataAddPointUser = array(
						'user_id' 						=> $user_member_get,
						'point' 						=> $point_user_member_get,
						'status_change_point' 			=> 1,
						'inbox_id' 						=> $inbox_id,
						'content' 						=> $notice_point_user_member_get,
						'date_add' 						=> date('Y-m-d H:i:s')
				);
					
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
					
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_member_get";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_member_get;
				$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
				$addNotice['point'] 					= $point_user_member_get;
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$addNotice["is_debug"] 				= 1;
					
				$this->function_model->addNotice($addNotice);
					
			}
				
			//user invite
			if($point_user_invite_sale_get > 0){
	
				$data['detail_user_invite_sale'] = $this->function_model->getUser($user_invite_sale);
	
				$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_invite_image");
	
				$this->db->where('user_id', $user_invite_sale);
				$this->db->set('user_point','user_point+'.$point_user_invite_sale_get,FALSE);
				$this->db->update('luxyart_tb_user');
	
				$user_fullname = $data['detail_user_invite_sale']->display_name;
	
				$notice_point_user_invite = $this->lang->line('management_point_shopping_cart_user_invite');
				$notice_point_user_invite = str_replace("{point}", $point_user_invite_sale_get, $notice_point_user_invite);
				$notice_point_user_invite = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_invite);
				$notice_point_user_invite = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_invite);
				$notice_point_user_invite = str_replace("{user_fullname}", $user_fullname, $notice_point_user_invite);
	
				$dataAddPointUser = array(
						'user_id' 						=> $user_invite_sale,
						'point' 						=> $point_user_invite_sale_get,
						'status_change_point' 			=> 1,
						'inbox_id' 						=> $inbox_id,
						'content' 						=> $notice_point_user_invite,
						'date_add' 						=> date('Y-m-d H:i:s')
				);
					
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
	
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_invite_image";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_invite_sale;
				$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
				$addNotice['point'] 					= $point_user_invite_sale_get;
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$dataSendEmail["is_debug"] 			= 1;
	
				$this->function_model->addNotice($addNotice);
	
			}
				
			//user get img
			if($point_user_get_img > 0){
					
				$data['detail_user_get_img'] = $this->function_model->getUser($user_get_img);
					
				$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_get_image");
					
				$this->db->where('user_id', $user_get_img);
				$this->db->set('user_point','user_point+'.$point_user_get_img,FALSE);
				$this->db->update('luxyart_tb_user');
					
				$user_fullname = $data['detail_user_get_img']->display_name;
					
				$notice_point_user_get_img = $this->lang->line('management_point_shopping_cart_user_get_img');
				$notice_point_user_get_img = str_replace("{point}", $point_user_get_img, $notice_point_user_get_img);
				$notice_point_user_get_img = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_img);
				$notice_point_user_get_img = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_img);
				$notice_point_user_get_img = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_img);
					
				$dataAddPointUser = array(
						'user_id' 						=> $user_get_img,
						'point' 						=> $point_user_get_img,
						'status_change_point' 			=> 1,
						'inbox_id' 						=> $inbox_id,
						'content' 						=> $notice_point_user_get_img,
						'date_add' 						=> date('Y-m-d H:i:s')
				);
					
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
					
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_get_image";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_get_img;
				$addNotice['user_fullname'] 			= $data['detail_user_get_img']->display_name;
				$addNotice['point'] 					= $point_user_get_img;
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$addNotice["is_debug"] 				= 1;
					
				$this->function_model->addNotice($addNotice);
					
			}
				
			//update size stock
			if($is_limit == 1){
	
				$this->db->where('id_img_size', $id_img_size);
				$this->db->set('size_stock','size_stock-1',FALSE);
				$this->db->update('luxyart_tb_product_img_size');
	
				$list_img_size = $this->manager_image_model->get_list_img_size($data['add_cart']->id_img)->result();
				if(count($list_img_size) == 0){
					$this->db->where('id_img', $data['add_cart']->id_img);
					$this->db->set('img_is_sold',1);
					$this->db->update('luxyart_tb_product_img');
				}
	
			}
				
			//download file to folder download
			$manager_server = $this->function_model->getManagerServer($data['add_cart']->id_server);
				
			//connect server old
			$sftp_config['hostname'] 	= $manager_server->server_name;
			$sftp_config['username'] 	= $manager_server->server_ftp_account;
			$sftp_config['password'] 	= $manager_server->server_ftp_pass;
			$sftp_config['port'] 		= $manager_server->server_port;
			$sftp_config['debug'] 		= TRUE;
			$this->sftp->connect($sftp_config);
				
			$file_name_original_img = $data['add_cart']->file_name_original_img;
			$file_name_original_img_name = explode(".",$file_name_original_img)[0];
				
			$type_size_description = $data['add_cart']->type_size_description;
			$type_size_description_width = explode("px",$type_size_description)[0];
				
			$this->sftp->download($manager_server->server_path_upload_img."/".$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img);
			$this->function_model->resize($type_size_description_width, DIR_DOWNLOAD.$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img_name);
				
			//send email buy image
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "shopping_cart_user_buy";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['detail_user']->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
			$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
			$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
			$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
			$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
			$dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
			$dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
			$dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
			$dataSendEmail["date_end_download"] 	= $date_end_download;
			$dataSendEmail["file_attachment"] 		= DIR_DOWNLOAD.$data['add_cart']->file_name_original_img;
			$dataSendEmail["is_send_admin"] 		= 1;
			//$dataSendEmail["is_debug"] 			= 1;

			$save_email["shopping_cart_user_buy"][0] = $dataSendEmail;
			//send_after***$this->function_model->sendMailHTML($dataSendEmail);
				
			//send email sale image
			$dataSendEmail = array();
			$dataSendEmail["name_email_code"] 		= "shopping_cart_user_sale";
			$dataSendEmail["lang_id"] 				= $this->lang_id;
			$dataSendEmail["email_to"] 				= $data['add_cart']->user_email;
			$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['add_cart']->user_id,$this->lang_id));
			$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
			$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
			$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
			$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
			$dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
			$dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
			$dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
			$dataSendEmail["date_end_download"] 	= $date_end_download;
			//$dataSendEmail["is_debug"] 			= 1;
			
			//send_after***$this->function_model->sendMailHTML($dataSendEmail);
			$save_email["shopping_cart_user_sale"][0] = $dataSendEmail;
				
			//send email invite image
			if($point_user_invite_sale_get > 0){
	
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "shopping_cart_user_invite";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_user_invite_sale']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_invite_sale']->user_id,$this->lang_id));
				$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
				$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
				$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
				$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
				$dataSendEmail["email_buy_image"] 		= $data['detail_user_invite_sale']->user_email;
				$dataSendEmail["date_end_download"] 	= $date_end_download;
				//$dataSendEmail["is_debug"] 			= 1;
				
				//send_after***$this->function_model->sendMailHTML($dataSendEmail);
				$save_email["shopping_cart_user_invite"][0] = $dataSendEmail;
					
			}
				
			//send email invite image
			if($point_user_get_img > 0){
	
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "shopping_cart_user_get_img";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_user_get_img']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_get_img']->user_id,$this->lang_id));
				$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
				$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
				$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
				$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
				$dataSendEmail["email_buy_image"] 		= $data['detail_user_get_img']->user_email;
				$dataSendEmail["date_end_download"] 	= $date_end_download;
				//$dataSendEmail["is_debug"] 			= 1;
					
				//send_after***$this->function_model->sendMailHTML($dataSendEmail);
				$save_email["shopping_cart_user_get_img"][0] = $dataSendEmail;
					
			}
				
			//delete file temp
			unlink(DIR_DOWNLOAD.$data['add_cart']->file_name_original_img);
				
			//remove
			$data = array(
					'id' => $rowid,
					'qty' => 0
			);
	
			$this->cart->update($data);
				
			$this->session->set_userdata('is_save_to_cart', 1);
			
			$_SESSION["save_email"] = $save_email;
				
			echo 1;
			exit;
				
				
		}
	
	}
	
	function save_to_cart_all(){
	
		session_start(); 
		
		$this->load->library('sftp');
		
		$save_email = array();
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
	
		$data['detail_user'] = $this->function_model->getUser($user_id);
	
		$setting = $this->function_model->get_setting();
		$value_end_download = "+".$setting['value_end_download']." month";
		$admin_get_point = $setting['admin_get_point'];
		$user_get_point = $setting['user_get_point'];
		$investor_get_point = $setting['investor_get_point'];
		$invite_get_point = $setting['invite_get_point'];
		$seller_get_point = $setting['seller_get_point'];
		
		$id_img_sizes = $_POST['id_img_sizes'];
		$id_img_sizes_explode = explode(",",$id_img_sizes);
		
		if(count($id_img_sizes_explode) == 0){
			echo 0;
			exit;
		}
		
		for($k=0; $k<count($id_img_sizes_explode); $k++){
			
			$data['detail_user'] = $this->function_model->getUser($user_id);
			
			$_id_img_sizes = $id_img_sizes_explode[$k];
			$_id_img_sizes_explode = explode("-",$_id_img_sizes);
			
			$id_img_size = $_id_img_sizes_explode[0];
			$rowid = $_id_img_sizes_explode[1];
			
			if($id_img_size > 0 && $rowid != ""){
			
				$data['add_cart'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
			
				/*$is_check_image_bought = $this->shopping_model->check_image_bought($data['add_cart']->id_img,$id_img_size,$user_id);
				if($is_check_image_bought == 1){
					$bought_buy_again = $this->lang->line('you_bought_the_image_size_then_buy_again');
					$bought_buy_again = str_replace("{type_size_description}", $data['add_cart']->type_size_description, $bought_buy_again);
					echo "0{luxyart}".$bought_buy_again;
					exit;
				}*/
			
				$price_size_point = $data['add_cart']->price_size_point;
				$size_stock = $data['add_cart']->size_stock;
				$is_limit = $data['add_cart']->is_limit;
			
				$date_end_download = date('Y-m-d H:i:s', strtotime($value_end_download, time()));
			
				if($data['add_cart']->user_id == $user_id){
					echo "0{luxyart}".$this->lang->line('you_can_not_purchase_your_photo');
					exit;
				}
			
				if($size_stock == 0 && $is_limit == 1){
					echo "0{luxyart}".$this->lang->line('this_image_is_out_of_stock');
					exit;
				}
			
				$user_paymoney_getpoint = $data['detail_user']->user_paymoney_getpoint;
				$user_point = $data['detail_user']->user_point;
				$total_point = $user_paymoney_getpoint + $user_point;
			
				if($total_point < $price_size_point){
					echo "0{luxyart}".$this->lang->line('you_do_not_have_enough_points_to_buy_this_photo');
					exit;
				}
			
				//add order
				$dataAddProductImgOrder = array(
						'user_buy_img' 				=> $user_id,
						'total_point_order' 		=> $price_size_point,
						'date_order_img' 			=> date('Y-m-d H:i:s'),
						'status_order'				=> 2,
						'date_end_download'			=> $date_end_download
				);
			
				$this->db->insert('luxyart_tb_product_img_order', $dataAddProductImgOrder);
				$id_order_detail = $this->db->insert_id();
			
				$point_user_owner_get = $user_get_point*$price_size_point/100;
				$investor_get_point = $investor_get_point*$price_size_point/100;
			
				$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart");
			
				//invite
				$user_invite_sale = 0;
				$point_user_invite_sale_get = 0;
				$invite_sale = $this->session->userdata['invite_sale'];
				if($invite_sale){
					$invite_user_invite_sale = $invite_sale['user_invite_sale'];
					$invite_id_img = $invite_sale['id_img'];
					if($invite_id_img == $data['add_cart']->id_img){
						$user_invite_sale = $invite_user_invite_sale;
						$point_user_invite_sale_get = $invite_get_point*$price_size_point/100;
					}
				}
			
				//get img
				$user_get_img = 0;
				$point_user_get_img = 0;
				$get_img = $this->session->userdata['get_img'];
				if($get_img){
					$get_user_id = $get_img['user_id_get_img'];
					$get_id_img = $get_img['id_img'];
					if($get_id_img == $data['add_cart']->id_img){
						$user_get_img = $get_user_id;
						$point_user_get_img = $seller_get_point*$price_size_point/100;
					}
				}
			
				$admin_get_point = $price_size_point - $point_user_owner_get - $investor_get_point - $point_user_invite_sale_get - $point_user_get_img;
			
				//add order detail
				$dataAddProductImgOrder = array(
						'id_order_img' 					=> $id_order_detail,
						'id_img' 						=> $data['add_cart']->id_img,
						'id_img_size' 					=> $data['add_cart']->id_img_size,
						'user_owner' 					=> $data['add_cart']->user_id,
						'point_user_owner_get'			=> $point_user_owner_get,
						'user_invite_sale' 				=> $user_invite_sale,
						'point_user_invite_sale_get' 	=> $point_user_invite_sale_get,
						'user_keep_store' 				=> $user_get_img,
						'point_user_keep_store_get'		=> $point_user_get_img,
						'admin_get_point'				=> $admin_get_point,
						'investor_get_point'			=> $investor_get_point
				);
				$this->db->insert('luxyart_tb_product_img_order_detail', $dataAddProductImgOrder);
			
				//user buy: 1.minus point 2.notice
				if($user_paymoney_getpoint - $price_size_point >= 0){
						
					$this->db->where('user_id', $user_id);
					$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint-'.$price_size_point,FALSE);
					$this->db->update('luxyart_tb_user');
			
					$this->function_model->set_use_point_package(round($price_size_point,2), $user_id);
						
				}
				else{
						
					$this->db->where('user_id', $user_id);
					$this->db->set('user_paymoney_getpoint',0);
					$this->db->update('luxyart_tb_user');
						
					$this->db->where('user_id', $user_id);
					$this->db->set('user_point','user_point-'.$price_size_point."+".$user_paymoney_getpoint,FALSE);
					$this->db->update('luxyart_tb_user');
						
				}
			
				$notice_point_user_buy = $this->lang->line('management_point_shopping_cart_user_buy');
				$notice_point_user_buy = str_replace("{point}", $price_size_point, $notice_point_user_buy);
				$notice_point_user_buy = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_buy);
				$notice_point_user_buy = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_buy);
			
				$dataAddPointUser = array(
						'user_id' 						=> $user_id,
						'point' 						=> $price_size_point,
						'status_change_point' 			=> 2,
						'inbox_id' 						=> $inbox_id,
						'content' 						=> $notice_point_user_buy,
						'date_add' 						=> date('Y-m-d H:i:s')
				);
			
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
			
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_buy";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_id;
				$addNotice['user_send_id'] 				= -1;
				$addNotice['point'] 					= $price_size_point;
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$addNotice['is_debug'] 				= 1;
			
				$this->function_model->addNotice($addNotice);
			
				//user sale: 1:add point 2:notice
				$this->db->where('user_id', $data['add_cart']->user_id);
				$this->db->set('user_point','user_point+'.$point_user_owner_get,FALSE);
				$this->db->update('luxyart_tb_user');
			
				$user_fullname = $this->function_model->get_fullname($data['detail_user']->user_id, $this->lang_id);
			
				$notice_point_user_get_point = $this->lang->line('management_point_shopping_cart_user_get_point');
				$notice_point_user_get_point = str_replace("{point}", $point_user_owner_get, $notice_point_user_get_point);
				$notice_point_user_get_point = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_point);
				$notice_point_user_get_point = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_point);
				$notice_point_user_get_point = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_point);
			
				$dataAddPointUser = array(
						'user_id' 						=> $data['add_cart']->user_id,
						'point' 						=> $point_user_owner_get,
						'status_change_point' 			=> 1,
						'inbox_id' 						=> $inbox_id,
						'content' 						=> $notice_point_user_get_point,
						'date_add' 						=> date('Y-m-d H:i:s')
				);
			
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
			
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_sale_image";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $data['add_cart']->user_id;
				$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
				$addNotice['point'] 					= $point_user_owner_get;
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$addNotice['is_debug'] 				= 1;
			
				$this->function_model->addNotice($addNotice);
			
				//user invite
				if($point_user_invite_sale_get > 0){
			
					$data['detail_user_invite_sale'] = $this->function_model->getUser($user_invite_sale);
			
					$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_invite_image");
			
					$this->db->where('user_id', $user_invite_sale);
					$this->db->set('user_point','user_point+'.$point_user_invite_sale_get,FALSE);
					$this->db->update('luxyart_tb_user');
			
					$user_fullname = $this->function_model->get_fullname($data['detail_user_invite_sale']->user_id, $this->lang_id);
			
					$notice_point_user_invite = $this->lang->line('management_point_shopping_cart_user_invite');
					$notice_point_user_invite = str_replace("{point}", $point_user_invite_sale_get, $notice_point_user_invite);
					$notice_point_user_invite = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_invite);
					$notice_point_user_invite = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_invite);
					$notice_point_user_invite = str_replace("{user_fullname}", $user_fullname, $notice_point_user_invite);
			
					$dataAddPointUser = array(
							'user_id' 						=> $user_invite_sale,
							'point' 						=> $point_user_invite_sale_get,
							'status_change_point' 			=> 1,
							'inbox_id' 						=> $inbox_id,
							'content' 						=> $notice_point_user_invite,
							'date_add' 						=> date('Y-m-d H:i:s')
					);
						
					$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
			
					$addNotice = array();
					$addNotice['type'] 						= "shopping_cart_user_invite_image";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_id'] 					= $user_invite_sale;
					$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
					$addNotice['point'] 					= $point_user_invite_sale_get;
					$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
					$addNotice['img_title'] 				= $data['add_cart']->img_title;
					//$dataSendEmail["is_debug"] 			= 1;
			
					$this->function_model->addNotice($addNotice);
			
				}
			
				//user get img
				if($point_user_get_img > 0){
						
					$data['detail_user_get_img'] = $this->function_model->getUser($user_get_img);
						
					$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_get_image");
						
					$this->db->where('user_id', $user_get_img);
					$this->db->set('user_point','user_point+'.$point_user_get_img,FALSE);
					$this->db->update('luxyart_tb_user');
						
					$user_fullname = $this->function_model->get_fullname($data['detail_user_get_img']->user_id, $this->lang_id);
						
					$notice_point_user_get_img = $this->lang->line('management_point_shopping_cart_user_get_img');
					$notice_point_user_get_img = str_replace("{point}", $point_user_get_img, $notice_point_user_get_img);
					$notice_point_user_get_img = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_img);
					$notice_point_user_get_img = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_img);
					$notice_point_user_get_img = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_img);
						
					$dataAddPointUser = array(
							'user_id' 						=> $user_get_img,
							'point' 						=> $point_user_get_img,
							'status_change_point' 			=> 1,
							'inbox_id' 						=> $inbox_id,
							'content' 						=> $notice_point_user_get_img,
							'date_add' 						=> date('Y-m-d H:i:s')
					);
						
					$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
						
					$addNotice = array();
					$addNotice['type'] 						= "shopping_cart_user_get_image";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_id'] 					= $user_get_img;
					$addNotice['user_fullname'] 			= $data['detail_user_get_img']->display_name;
					$addNotice['point'] 					= $point_user_get_img;
					$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
					$addNotice['img_title'] 				= $data['add_cart']->img_title;
					//$addNotice["is_debug"] 				= 1;
						
					$this->function_model->addNotice($addNotice);
						
				}
			
				//update size stock
				if($is_limit == 1){
			
					$this->db->where('id_img_size', $id_img_size);
					$this->db->set('size_stock','size_stock-1',FALSE);
					$this->db->update('luxyart_tb_product_img_size');
			
					$list_img_size = $this->manager_image_model->get_list_img_size($data['add_cart']->id_img)->result();
					if(count($list_img_size) == 0){
						$this->db->where('id_img', $data['add_cart']->id_img);
						$this->db->set('img_is_sold',1);
						$this->db->update('luxyart_tb_product_img');
					}
			
				}
			
				//download file to folder download
				$manager_server = $this->function_model->getManagerServer($data['add_cart']->id_server);
			
				//connect server old
				$sftp_config['hostname'] 	= $manager_server->server_name;
				$sftp_config['username'] 	= $manager_server->server_ftp_account;
				$sftp_config['password'] 	= $manager_server->server_ftp_pass;
				$sftp_config['port'] 		= $manager_server->server_port;
				$sftp_config['debug'] 		= TRUE;
				$this->sftp->connect($sftp_config);
			
				$file_name_original_img = $data['add_cart']->file_name_original_img;
				$file_name_original_img_name = explode(".",$file_name_original_img)[0];
			
				$type_size_description = $data['add_cart']->type_size_description;
				$type_size_description_width = explode("px",$type_size_description)[0];
			
				$this->sftp->download($manager_server->server_path_upload_img."/".$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img);
				$this->function_model->resize($type_size_description_width, DIR_DOWNLOAD.$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img_name);
			
				//send email buy image
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "shopping_cart_user_buy";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_user']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
				$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
				$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
				$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
				$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
				$dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
				$dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
				$dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
				$dataSendEmail["date_end_download"] 	= $date_end_download;
				$dataSendEmail["file_attachment"] 		= DIR_DOWNLOAD.$data['add_cart']->file_name_original_img;
				$dataSendEmail["is_send_admin"] 		= 1;
				//$dataSendEmail["is_debug"] 			= 1;
				
				$save_email["shopping_cart_user_buy"][$k] = $dataSendEmail;
				//send_after***$this->function_model->sendMailHTML($dataSendEmail);
			
				//send email sale image
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "shopping_cart_user_sale";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['add_cart']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['add_cart']->user_id,$this->lang_id));
				$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
				$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
				$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
				$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
				$dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
				$dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
				$dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
				$dataSendEmail["date_end_download"] 	= $date_end_download;
				//$dataSendEmail["is_debug"] 			= 1;
				
				//send_after***$this->function_model->sendMailHTML($dataSendEmail);
				$save_email["shopping_cart_user_sale"][$k] = $dataSendEmail;
			
				//send email invite image
				if($point_user_invite_sale_get > 0){
			
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "shopping_cart_user_invite";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $data['detail_user_invite_sale']->user_email;
					$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_invite_sale']->user_id,$this->lang_id));
					$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
					$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
					$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
					$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
					$dataSendEmail["email_buy_image"] 		= $data['detail_user_invite_sale']->user_email;
					$dataSendEmail["date_end_download"] 	= $date_end_download;
					//$dataSendEmail["is_debug"] 			= 1;
			
					//send_after***$this->function_model->sendMailHTML($dataSendEmail);
					$save_email["shopping_cart_user_invite"][$k] = $dataSendEmail;
						
				}
			
				//send email invite image
				if($point_user_get_img > 0){
			
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "shopping_cart_user_get_img";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $data['detail_user_get_img']->user_email;
					$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_get_img']->user_id,$this->lang_id));
					$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
					$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
					$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
					$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
					$dataSendEmail["email_buy_image"] 		= $data['detail_user_get_img']->user_email;
					$dataSendEmail["date_end_download"] 	= $date_end_download;
					//$dataSendEmail["is_debug"] 			= 1;
						
					//send_after***$this->function_model->sendMailHTML($dataSendEmail);
					$save_email["shopping_cart_user_get_img"][$k] = $dataSendEmail;
						
				}
			
				//delete file temp
				unlink(DIR_DOWNLOAD.$data['add_cart']->file_name_original_img);
			
				//remove
				$data = array(
						'id' => $rowid,
						'qty' => 0
				);
			
				$this->cart->update($data);
			
				$this->session->set_userdata('is_save_to_cart', 1);
				
				$_SESSION["save_email"] = $save_email;
			
			}
			
		}

		echo 1;
		exit;
	
	}
	
	function save_to_cart_all_test(){
	
		session_start();
	
		$this->load->library('sftp');
	
		$save_email = array();
	
		//check permission
		$is_permission = $this->function_model->check_permission(1);
		if(!$is_permission){
			header("Location: ".base_url()."login.html");
			exit;
		}
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
	
		$data['detail_user'] = $this->function_model->getUser($user_id);
	
		$setting = $this->function_model->get_setting();
		$value_end_download = "+".$setting['value_end_download']." month";
		$admin_get_point = $setting['admin_get_point'];
		$user_get_point = $setting['user_get_point'];
		$investor_get_point = $setting['investor_get_point'];
		$invite_get_point = $setting['invite_get_point'];
		$seller_get_point = $setting['seller_get_point'];
		$member_get_point = $setting['member_get_point'];
	
		$id_img_sizes = $_POST['id_img_sizes'];
		$id_img_sizes_explode = explode(",",$id_img_sizes);
	
		if(count($id_img_sizes_explode) == 0){
			echo 0;
			exit;
		}
	
		for($k=0; $k<count($id_img_sizes_explode); $k++){
				
			$data['detail_user'] = $this->function_model->getUser($user_id);
				
			$_id_img_sizes = $id_img_sizes_explode[$k];
			$_id_img_sizes_explode = explode("-",$_id_img_sizes);
				
			$id_img_size = $_id_img_sizes_explode[0];
			$rowid = $_id_img_sizes_explode[1];
				
			if($id_img_size > 0 && $rowid != ""){
					
				$data['add_cart'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
					
				/*$is_check_image_bought = $this->shopping_model->check_image_bought($data['add_cart']->id_img,$id_img_size,$user_id);
					if($is_check_image_bought == 1){
				$bought_buy_again = $this->lang->line('you_bought_the_image_size_then_buy_again');
				$bought_buy_again = str_replace("{type_size_description}", $data['add_cart']->type_size_description, $bought_buy_again);
				echo "0{luxyart}".$bought_buy_again;
				exit;
				}*/
					
				$price_size_point = $data['add_cart']->price_size_point;
				$size_stock = $data['add_cart']->size_stock;
				$is_limit = $data['add_cart']->is_limit;
					
				$date_end_download = date('Y-m-d H:i:s', strtotime($value_end_download, time()));
					
				if($data['add_cart']->user_id == $user_id){
					echo "0{luxyart}".$this->lang->line('you_can_not_purchase_your_photo');
					exit;
				}
					
				if($size_stock == 0 && $is_limit == 1){
					echo "0{luxyart}".$this->lang->line('this_image_is_out_of_stock');
					exit;
				}
					
				$user_paymoney_getpoint = $data['detail_user']->user_paymoney_getpoint;
				$user_point = $data['detail_user']->user_point;
				$total_point = $user_paymoney_getpoint + $user_point;
					
				if($total_point < $price_size_point){
					echo "0{luxyart}".$this->lang->line('you_do_not_have_enough_points_to_buy_this_photo');
					exit;
				}
					
				//add order
				$dataAddProductImgOrder = array(
						'user_buy_img' 				=> $user_id,
						'total_point_order' 		=> $price_size_point,
						'date_order_img' 			=> date('Y-m-d H:i:s'),
						'status_order'				=> 2,
						'date_end_download'			=> $date_end_download
				);
					
				$this->db->insert('luxyart_tb_product_img_order', $dataAddProductImgOrder);
				$id_order_detail = $this->db->insert_id();
					
				$point_user_owner_get = $user_get_point*$price_size_point/100;
				$investor_get_point = $investor_get_point*$price_size_point/100;
				$member_get_point = $member_get_point*$price_size_point/100;
					
				$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart");
				
				//member
				$this->db->from('luxyart_tb_member');
				$this->db->select('*');
				$this->db->where(array('user_id_invited' => $data['add_cart']->user_id,'status' => 2));
				$rsMember = $this->db->get();
				$countMember = $rsMember->num_rows();
					
				$user_member_get = 0;
				$point_user_member_get = 0;
				if($countMember == 1){
					$resultMember = $rsMember->row();
					$user_member_get = $resultMember->user_id;
					$point_user_member_get = $member_get_point*$price_size_point/100;
				}
					
				//invite
				$user_invite_sale = 0;
				$point_user_invite_sale_get = 0;
				$invite_sale = $this->session->userdata['invite_sale'];
				if($invite_sale){
					$invite_user_invite_sale = $invite_sale['user_invite_sale'];
					$invite_id_img = $invite_sale['id_img'];
					if($invite_id_img == $data['add_cart']->id_img){
						$user_invite_sale = $invite_user_invite_sale;
						$point_user_invite_sale_get = $invite_get_point*$price_size_point/100;
					}
				}
					
				//get img
				$user_get_img = 0;
				$point_user_get_img = 0;
				$get_img = $this->session->userdata['get_img'];
				if($get_img){
					$get_user_id = $get_img['user_id_get_img'];
					$get_id_img = $get_img['id_img'];
					if($get_id_img == $data['add_cart']->id_img){
						$user_get_img = $get_user_id;
						$point_user_get_img = $seller_get_point*$price_size_point/100;
					}
				}
				
				//admin
				$admin_get_point = $price_size_point - $point_user_owner_get - $investor_get_point - $point_user_invite_sale_get - $point_user_get_img;
					
				//add order detail
				$dataAddProductImgOrder = array(
						'id_order_img' 					=> $id_order_detail,
						'id_img' 						=> $data['add_cart']->id_img,
						'id_img_size' 					=> $data['add_cart']->id_img_size,
						'user_owner' 					=> $data['add_cart']->user_id,
						'point_user_owner_get'			=> $point_user_owner_get - $point_user_member_get,
						'user_invite_sale' 				=> $user_invite_sale,
						'point_user_invite_sale_get' 	=> $point_user_invite_sale_get,
						'user_keep_store' 				=> $user_get_img,
						'point_user_keep_store_get'		=> $point_user_get_img,
						'admin_get_point'				=> $admin_get_point,
						'investor_get_point'			=> $investor_get_point,
						'user_member_get'				=> $user_member_get,
						'point_user_member_get'			=> $point_user_member_get
				);
				$this->db->insert('luxyart_tb_product_img_order_detail', $dataAddProductImgOrder);
					
				//user buy: 1.minus point 2.notice
				if($user_paymoney_getpoint - $price_size_point >= 0){
	
					$this->db->where('user_id', $user_id);
					$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint-'.$price_size_point,FALSE);
					$this->db->update('luxyart_tb_user');
						
					$this->function_model->set_use_point_package(round($price_size_point,2), $user_id);
	
				}
				else{
	
					$this->db->where('user_id', $user_id);
					$this->db->set('user_paymoney_getpoint',0);
					$this->db->update('luxyart_tb_user');
	
					$this->db->where('user_id', $user_id);
					$this->db->set('user_point','user_point-'.$price_size_point."+".$user_paymoney_getpoint,FALSE);
					$this->db->update('luxyart_tb_user');
	
				}
					
				$notice_point_user_buy = $this->lang->line('management_point_shopping_cart_user_buy');
				$notice_point_user_buy = str_replace("{point}", $price_size_point, $notice_point_user_buy);
				$notice_point_user_buy = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_buy);
				$notice_point_user_buy = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_buy);
					
				$dataAddPointUser = array(
						'user_id' 						=> $user_id,
						'point' 						=> $price_size_point,
						'status_change_point' 			=> 2,
						'inbox_id' 						=> $inbox_id,
						'content' 						=> $notice_point_user_buy,
						'date_add' 						=> date('Y-m-d H:i:s')
				);
					
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
					
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_buy";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $user_id;
				$addNotice['user_send_id'] 				= -1;
				$addNotice['point'] 					= $price_size_point;
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$addNotice['is_debug'] 				= 1;
					
				$this->function_model->addNotice($addNotice);
					
				//user sale: 1:add point 2:notice
				$this->db->where('user_id', $data['add_cart']->user_id);
				$this->db->set('user_point','user_point+'.($point_user_owner_get-$point_user_member_get),FALSE);
				$this->db->update('luxyart_tb_user');
					
				$user_fullname = $data['detail_user']->display_name;
					
				$notice_point_user_get_point = $this->lang->line('management_point_shopping_cart_user_get_point');
				$notice_point_user_get_point = str_replace("{point}", ($point_user_owner_get-$point_user_member_get), $notice_point_user_get_point);
				$notice_point_user_get_point = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_point);
				$notice_point_user_get_point = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_point);
				$notice_point_user_get_point = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_point);
					
				$dataAddPointUser = array(
						'user_id' 						=> $data['add_cart']->user_id,
						'point' 						=> ($point_user_owner_get-$point_user_member_get),
						'status_change_point' 			=> 1,
						'inbox_id' 						=> $inbox_id,
						'content' 						=> $notice_point_user_get_point,
						'date_add' 						=> date('Y-m-d H:i:s')
				);
					
				$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
					
				$addNotice = array();
				$addNotice['type'] 						= "shopping_cart_user_sale_image";
				$addNotice["lang_id"] 					= $this->lang_id;
				$addNotice['user_id'] 					= $data['add_cart']->user_id;
				$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
				$addNotice['point'] 					= ($point_user_owner_get-$point_user_member_get);
				$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
				$addNotice['img_title'] 				= $data['add_cart']->img_title;
				//$addNotice['is_debug'] 				= 1;
					
				$this->function_model->addNotice($addNotice);
				
				//user member
				if($point_user_member_get > 0){
						
					$data['detail_user_member_get'] = $this->function_model->getUser($user_member_get);
					$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_member_get");
						
					$this->db->where('user_id', $user_member_get);
					$this->db->set('user_point','user_point+'.$point_user_member_get,FALSE);
					$this->db->update('luxyart_tb_user');
						
					$user_fullname = $data['detail_user_member_get']->display_name;
						
					$notice_point_user_member_get = $this->lang->line('management_point_shopping_cart_user_member_get');
					$notice_point_user_member_get = str_replace("{point}", $point_user_member_get, $notice_point_user_member_get);
					$notice_point_user_member_get = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_member_get);
					$notice_point_user_member_get = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_member_get);
					$notice_point_user_member_get = str_replace("{user_fullname}", $user_fullname, $notice_point_user_member_get);
						
					$dataAddPointUser = array(
							'user_id' 						=> $user_member_get,
							'point' 						=> $point_user_member_get,
							'status_change_point' 			=> 1,
							'inbox_id' 						=> $inbox_id,
							'content' 						=> $notice_point_user_member_get,
							'date_add' 						=> date('Y-m-d H:i:s')
					);
						
					$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
						
					$addNotice = array();
					$addNotice['type'] 						= "shopping_cart_user_member_get";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_id'] 					= $user_member_get;
					$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
					$addNotice['point'] 					= $point_user_member_get;
					$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
					$addNotice['img_title'] 				= $data['add_cart']->img_title;
					//$addNotice["is_debug"] 				= 1;
						
					$this->function_model->addNotice($addNotice);
						
				}
					
				//user invite
				if($point_user_invite_sale_get > 0){
						
					$data['detail_user_invite_sale'] = $this->function_model->getUser($user_invite_sale);
						
					$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_invite_image");
						
					$this->db->where('user_id', $user_invite_sale);
					$this->db->set('user_point','user_point+'.$point_user_invite_sale_get,FALSE);
					$this->db->update('luxyart_tb_user');
						
					$user_fullname = $data['detail_user_invite_sale']->display_name;
						
					$notice_point_user_invite = $this->lang->line('management_point_shopping_cart_user_invite');
					$notice_point_user_invite = str_replace("{point}", $point_user_invite_sale_get, $notice_point_user_invite);
					$notice_point_user_invite = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_invite);
					$notice_point_user_invite = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_invite);
					$notice_point_user_invite = str_replace("{user_fullname}", $user_fullname, $notice_point_user_invite);
						
					$dataAddPointUser = array(
							'user_id' 						=> $user_invite_sale,
							'point' 						=> $point_user_invite_sale_get,
							'status_change_point' 			=> 1,
							'inbox_id' 						=> $inbox_id,
							'content' 						=> $notice_point_user_invite,
							'date_add' 						=> date('Y-m-d H:i:s')
					);
	
					$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
						
					$addNotice = array();
					$addNotice['type'] 						= "shopping_cart_user_invite_image";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_id'] 					= $user_invite_sale;
					$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
					$addNotice['point'] 					= $point_user_invite_sale_get;
					$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
					$addNotice['img_title'] 				= $data['add_cart']->img_title;
					//$dataSendEmail["is_debug"] 			= 1;
						
					$this->function_model->addNotice($addNotice);
						
				}
					
				//user get img
				if($point_user_get_img > 0){
	
					$data['detail_user_get_img'] = $this->function_model->getUser($user_get_img);
	
					$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_get_image");
	
					$this->db->where('user_id', $user_get_img);
					$this->db->set('user_point','user_point+'.$point_user_get_img,FALSE);
					$this->db->update('luxyart_tb_user');
	
					$user_fullname = $data['detail_user_get_img']->display_name;
	
					$notice_point_user_get_img = $this->lang->line('management_point_shopping_cart_user_get_img');
					$notice_point_user_get_img = str_replace("{point}", $point_user_get_img, $notice_point_user_get_img);
					$notice_point_user_get_img = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_img);
					$notice_point_user_get_img = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_img);
					$notice_point_user_get_img = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_img);
	
					$dataAddPointUser = array(
							'user_id' 						=> $user_get_img,
							'point' 						=> $point_user_get_img,
							'status_change_point' 			=> 1,
							'inbox_id' 						=> $inbox_id,
							'content' 						=> $notice_point_user_get_img,
							'date_add' 						=> date('Y-m-d H:i:s')
					);
	
					$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
	
					$addNotice = array();
					$addNotice['type'] 						= "shopping_cart_user_get_image";
					$addNotice["lang_id"] 					= $this->lang_id;
					$addNotice['user_id'] 					= $user_get_img;
					$addNotice['user_fullname'] 			= $data['detail_user_get_img']->display_name;
					$addNotice['point'] 					= $point_user_get_img;
					$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
					$addNotice['img_title'] 				= $data['add_cart']->img_title;
					//$addNotice["is_debug"] 				= 1;
	
					$this->function_model->addNotice($addNotice);
	
				}
					
				//update size stock
				if($is_limit == 1){
						
					$this->db->where('id_img_size', $id_img_size);
					$this->db->set('size_stock','size_stock-1',FALSE);
					$this->db->update('luxyart_tb_product_img_size');
						
					$list_img_size = $this->manager_image_model->get_list_img_size($data['add_cart']->id_img)->result();
					if(count($list_img_size) == 0){
						$this->db->where('id_img', $data['add_cart']->id_img);
						$this->db->set('img_is_sold',1);
						$this->db->update('luxyart_tb_product_img');
					}
						
				}
					
				//download file to folder download
				$manager_server = $this->function_model->getManagerServer($data['add_cart']->id_server);
					
				//connect server old
				$sftp_config['hostname'] 	= $manager_server->server_name;
				$sftp_config['username'] 	= $manager_server->server_ftp_account;
				$sftp_config['password'] 	= $manager_server->server_ftp_pass;
				$sftp_config['port'] 		= $manager_server->server_port;
				$sftp_config['debug'] 		= TRUE;
				$this->sftp->connect($sftp_config);
					
				$file_name_original_img = $data['add_cart']->file_name_original_img;
				$file_name_original_img_name = explode(".",$file_name_original_img)[0];
					
				$type_size_description = $data['add_cart']->type_size_description;
				$type_size_description_width = explode("px",$type_size_description)[0];
					
				$this->sftp->download($manager_server->server_path_upload_img."/".$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img);
				$this->function_model->resize($type_size_description_width, DIR_DOWNLOAD.$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img_name);
					
				//send email buy image
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "shopping_cart_user_buy";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['detail_user']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
				$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
				$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
				$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
				$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
				$dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
				$dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
				$dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
				$dataSendEmail["date_end_download"] 	= $date_end_download;
				$dataSendEmail["file_attachment"] 		= DIR_DOWNLOAD.$data['add_cart']->file_name_original_img;
				$dataSendEmail["is_send_admin"] 		= 1;
				//$dataSendEmail["is_debug"] 			= 1;
	
				$save_email["shopping_cart_user_buy"][$k] = $dataSendEmail;
				//send_after***$this->function_model->sendMailHTML($dataSendEmail);
					
				//send email sale image
				$dataSendEmail = array();
				$dataSendEmail["name_email_code"] 		= "shopping_cart_user_sale";
				$dataSendEmail["lang_id"] 				= $this->lang_id;
				$dataSendEmail["email_to"] 				= $data['add_cart']->user_email;
				$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['add_cart']->user_id,$this->lang_id));
				$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
				$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
				$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
				$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
				$dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
				$dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
				$dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
				$dataSendEmail["date_end_download"] 	= $date_end_download;
				//$dataSendEmail["is_debug"] 			= 1;
	
				//send_after***$this->function_model->sendMailHTML($dataSendEmail);
				$save_email["shopping_cart_user_sale"][$k] = $dataSendEmail;
					
				//send email invite image
				if($point_user_invite_sale_get > 0){
						
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "shopping_cart_user_invite";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $data['detail_user_invite_sale']->user_email;
					$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_invite_sale']->user_id,$this->lang_id));
					$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
					$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
					$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
					$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
					$dataSendEmail["email_buy_image"] 		= $data['detail_user_invite_sale']->user_email;
					$dataSendEmail["date_end_download"] 	= $date_end_download;
					//$dataSendEmail["is_debug"] 			= 1;
						
					//send_after***$this->function_model->sendMailHTML($dataSendEmail);
					$save_email["shopping_cart_user_invite"][$k] = $dataSendEmail;
	
				}
					
				//send email invite image
				if($point_user_get_img > 0){
						
					$dataSendEmail = array();
					$dataSendEmail["name_email_code"] 		= "shopping_cart_user_get_img";
					$dataSendEmail["lang_id"] 				= $this->lang_id;
					$dataSendEmail["email_to"] 				= $data['detail_user_get_img']->user_email;
					$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_get_img']->user_id,$this->lang_id));
					$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
					$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
					$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
					$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
					$dataSendEmail["email_buy_image"] 		= $data['detail_user_get_img']->user_email;
					$dataSendEmail["date_end_download"] 	= $date_end_download;
					//$dataSendEmail["is_debug"] 			= 1;
	
					//send_after***$this->function_model->sendMailHTML($dataSendEmail);
					$save_email["shopping_cart_user_get_img"][$k] = $dataSendEmail;
	
				}
					
				//delete file temp
				unlink(DIR_DOWNLOAD.$data['add_cart']->file_name_original_img);
					
				//remove
				$data = array(
						'id' => $rowid,
						'qty' => 0
				);
					
				$this->cart->update($data);
					
				$this->session->set_userdata('is_save_to_cart', 1);
	
				$_SESSION["save_email"] = $save_email;
					
			}
				
		}
	
		echo 1;
		exit;
	
	}
	
	function send_save_email(){
		
		session_start();
		$save_email = $_SESSION["save_email"];
		
		foreach($save_email as $detail_save_email){
			for($i=0; $i<count($detail_save_email); $i++){
				$dataSendEmail = array();
				foreach ($detail_save_email[$i] as $key_save_email => $value_save_email){
					$dataSendEmail[$key_save_email] = $value_save_email;
					//$dataSendEmail['is_send_admin'] = 1;
					//$dataSendEmail['is_debug'] = 1;
				}
				$this->function_model->sendMailHTML($dataSendEmail);
			}
		}
		
		unset($_SESSION['save_email']);
		
	}
	
	function check_image_bought_ajax(){
	
		$id_img_size = $_POST['id_img_size'];
		$rowid = $_POST['rowid'];
		
		$logged_in = $this->function_model->get_logged_in();
		$user_id = $logged_in['user_id'];
	
		if($id_img_size > 0 && $rowid != ""){
				
			$data['add_cart'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();
				
			$is_check_image_bought = $this->shopping_model->check_image_bought($data['add_cart']->id_img,$id_img_size,$user_id);
			if($is_check_image_bought == 1){
				$bought_buy_again = $this->lang->line('you_bought_the_image_size_then_buy_again');
				$bought_buy_again = str_replace("{type_size_description}", $data['add_cart']->type_size_description, $bought_buy_again);
				echo "0{luxyart}".$bought_buy_again;
				exit;
			}
			else{
				echo 1;
				exit;
			}	
				
		}
		else{
			echo 0;
			exit;
		}
	
	}
	 
}