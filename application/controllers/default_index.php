<?php
@session_start();
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Default_index extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
		$this->load->library('session');
		$this->load->library('cart');
		//$this->load->model('main_model');
		$this->load->model('function_model');
		$this->load->model('manager_compe_model');
		$this->load->model('manager_user_model');
		
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
		}
		else if($_SESSION['lang'] == "vi"){
			$this->lang_id = 2;
		}
		else if($_SESSION['lang'] == "en"){
			$this->lang_id = 3;
		}
    }
    public function index() {//used
    	
    	//get logged in
    	$logged_in = $this->function_model->get_logged_in();
    	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->session->set_userdata('actual_link', $actual_link);
		$logged_in = $this->function_model->get_logged_in();
		//lay user_id tu session
		$user_id = $logged_in['user_id'];
		// load list image user bookmark
		if($user_id!="")
		{
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
			$data['is_login_user'] =0;
			$data['user_login_id'] ='';
			$data['id_user_check'] ='';
		}
    	//list product category
    	$whereProductCategory = array('level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1);
    	$orderProductCategory = array();
    	$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
		
		$wherelist_pickup_category = array('lang_id' => $this->lang_id, 'status_cate' => 1,'is_pickup' =>1);
		$orderlist_pickup_category = array();
		$data['list_pickup_category'] = $this->main_model->getAllDataLimit("luxyart_tb_product_category", $wherelist_pickup_category, $orderlist_pickup_category,4,0)->result();
    	//list user follow
    	$data['list_user_follow'] = $this->function_model->getUserFollow()->result();

    	//list competition
    	$logged_in = $this->function_model->get_logged_in();
		$string_loc_by_userlevel = "date_end_com > '".date("Y-m-d")."' and (status_com=1 or status_com=2)";
		/*if($logged_in)
		{
			$user_level = $logged_in['user_level'];
			if($user_level==4 || $user_level == 5)
			{
				$string_loc_by_userlevel .= " ";
				
			}
			else
			{
				$string_loc_by_userlevel .= " and is_private = 0";
			}
			
		}
		else
		{
			$string_loc_by_userlevel .= " and is_private = 0";
		}*/
		//lay user_id tu session
    	$whereCompetition = $string_loc_by_userlevel;
		$orderCompetition = array('luxyart_tb_user_competition.date_end_com' =>'asc');
    	$data['list_competition'] = $this->main_model->getAllDataLimit("luxyart_tb_user_competition", $whereCompetition, $orderCompetition, 3, 0)->result();
		//echo $this->db->last_query();
		//exit();
    	//list category
    	$whereCategory = array('parent_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1);
    	$orderCategory = array();
    	$data['list_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereCategory, $orderCategory)->result();
    	//list color
    	$whereColor = array('id_color > ' => 0,'lang_id' => $this->lang_id);
    	$orderColor = array();
    	$data['list_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereColor, $orderColor)->result();
    	$meta = array();
    	$data['meta_seo'] = $this->function_model->getMetaSeo("default_index/index", $meta, $this->lang_id);
    	//$data['menu_top'] = $this->function_model->getMenuTop()->result();
    	//$data['menu_bottom'] = $this->function_model->getMenuBottom()->result();
    	$this->template->load('default', 'index', $data, __CLASS__);
    }
    
	public function page_category() 
	{   
		// - count all product have on category tra ve ket qua num_row
		$where_count_all_product = array('img_check_status' => 1);
		$order_count_all_product = array();
    	$data['count_all_product'] = $this->function_model->getCount_data("luxyart_tb_product_img",$where_count_all_product,$order_count_all_product);
		// end count
		// - load image moi upload join table server_img with table product_img
		$where_img_moi_nhat = array('luxyart_tb_product_img.img_check_status' =>1, 'luxyart_tb_manager_server.server_status' =>0);
		$order_img_moi_nhat = array('luxyart_tb_product_img.id_img' =>'desc');
		$limit_img_moi_nhat = 8;
		$data['list_product_img_moinhat'] = $this->function_model->get_img_moi_nhat("luxyart_tb_product_img", $where_img_moi_nhat, $order_img_moi_nhat,$limit_img_moi_nhat)->result();
		// end load image moi upload
		// - load image popular join table luxyart_tb_manager_server,luxyart_tb_user_bookmark_image
		$where_img_popular = array('luxyart_tb_product_img.img_check_status' =>1, 'luxyart_tb_manager_server.server_status' =>0);
		$order_img_popular = array();
		$limit_img_popular = 8;
		$data['list_product_img_popular'] = $this->function_model->get_img_popular("luxyart_tb_product_img", $where_img_popular, $order_img_popular,$limit_img_popular)->result();
		// end load image moi upload
		// - Load cate for all
		$whereProductCategory = array('level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1);
    	$orderProductCategory = array();
    	$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
		//End load
    	$meta = array();
    	$data['meta_seo'] = $this->function_model->getMetaSeo("default_index/category", $meta, $this->lang_id);	
    	$this->template->load('default', 'category', $data, __CLASS__);
    }
	public function page_category_theo_id($id_cate)
	{
		$this->load->helper('form');
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		$per_page = $this->input->get('page');
		//exit();
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
		}
		else
		{
			$data['check_bookmark_images']='';
			$data['is_login_user'] =0;
		}
		//get link full
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->session->set_userdata('actual_link', $actual_link);
		// - Load cate for all
		$data['sosanh_cate_id'] = $id_cate;
		$string_search_condition ='';
		$data['search_option1'] = '人気順';
			$data['search_option2'] = '縦横';
			$data['search_color'] = 'none';
			$data['search_ltime'] = 'none';
			$data['search_lnum'] = 'none';
			$data['search_page'] = 50;
			$data['search_key'] = 'none';
			$data['search_cate'] = $id_cate;
			$xcate = $id_cate;
		//echo $data['search_color'];
		$string_search_condition .='luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and ';
		//exit();
		// check show hide cate group siderbar
		$whereProductCategory = array('level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1);
    	$orderProductCategory = array();
    	$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
		//End load
		//list color
    	$whereColor = array('id_color > ' => 0,'lang_id' => $this->lang_id);
    	$orderColor = array();
    	$data['list_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereColor, $orderColor)->result();
		// Load img theo category + condition search
		// + Xu ly 
		//luxyart_tb_img_category root_cate,parent_cate,sub_cate
		$where_search_img = array('luxyart_tb_product_img.img_check_status' =>1, 'luxyart_tb_manager_server.server_status' =>0);
		//$where_category = $string_search_condition.' luxyart_tb_product_img.img_check_status=1';
		$where_category = $string_search_condition.' luxyart_tb_product_img.img_check_status=1 and (luxyart_tb_img_category.root_cate = '.$id_cate.' or luxyart_tb_img_category.parent_cate = '.$id_cate.' or luxyart_tb_img_category.sub_cate = '.$id_cate.')';
		$order_search_img = array();
		//Load phan trang
		$start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$this->load->library('paginationcate');
		//$config['total_rows'] = 10;
		if($this->input->get('page')!="")
		{
			 $config['per_page'] = $this->input->get('page');
			 $data['search_page'] = $this->input->get('numpage');
		}
		else
		{
			$config['per_page'] = 50;
			$data['search_page'] = 50;
		}
		$config['total_rows'] = count($this->function_model->get_img_search_with_condition("luxyart_tb_product_img", $where_search_img, $where_category, $order_search_img,'','')->result());
		//$config['page_query_string'] = TRUE;
		$config['uri_segment'] = 4;	
		$config['next_link'] =  '»';
		$config['prev_link'] =  '«';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/category/'.$id_cate.'/page';
		
		//$config['base_url'] =  base_url().'/search?'.$chuoi_type_image.$chuoi_color.$limittime_link.$limitnum_link.'&numpage='.$config['per_page'];
		$this->paginationcate->initialize($config);
		$data['phantrang'] = $this->paginationcate->create_links();		
		$data['load_product_with_condition_search'] = $this->function_model->get_img_search_with_condition("luxyart_tb_product_img", $where_search_img, $where_category, $order_search_img,$config['per_page'],$start_index)->result();
		$wherelist_pickup_category = array('lang_id' => $this->lang_id, 'status_cate' => 1,'is_pickup' =>1);
		$orderlist_pickup_category = array();
		$data['list_pickup_category'] = $this->main_model->getAllDataLimit("luxyart_tb_product_category", $wherelist_pickup_category, $orderlist_pickup_category,4,0)->result();
		// XU ly cach goi cate
		$query = $this->db->query("Select * FROM luxyart_tb_product_category where cate_id=".$id_cate." and lang_id=".$this->lang_id);
		if($query->num_rows() > 0)
			{
			$row = $query->row();
			if (isset($row))
			{
				$data['cate_path_level']= $row->parent_cate;
				$query2 = $this->db->query("Select * FROM luxyart_tb_product_category where cate_id=".$row->parent_cate." and lang_id=".$this->lang_id);
				$row2 = $query2->row();
				if (isset($row2))
				{
					$data['cate_path_level_name']= $row2->cate_name;
				}
			}
		else
		{
			$data['cate_path_level']=0;
		}
			}else
			{
				$data['cate_path_level']=0;
			}
		// Load SEO
		$meta = array();
    	$data['meta_seo'] = $this->function_model->getMetaSeo_Page_Cate_ID($id_cate, $meta, $this->lang_id);	
    	$this->template->load('default', 'category_theo_id', $data, __CLASS__);
	}
	public function search()
	{
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
		}
		else
		{
			$data['check_bookmark_images']='';
			$data['is_login_user'] =0;
		}
		$this->load->helper('form');
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		// Get cate _ID
		$id_cate = $this->input->get('cate');
		// GET TYPE IMAGE
		$type_image = substr(implode(', ', $this->input->get('typeimage')), 0);
		$chuoi_type_image = '&typeimage%5B%5D=';
		$chuoi_type_image .= substr(implode('&typeimage%5B%5D=', $this->input->get('typeimage')), 0);
		if($chuoi_type_image=="&typeimage%5B%5D=")
		{
			$chuoi_type_image ='';
		}
		// GET COLOR
		$color = substr(implode(', ', $this->input->get('color')), 0);
		$chuoi_color = '&color%5B%5D=';
		$chuoi_color .= substr(implode('&color%5B%5D=', $this->input->get('color')), 0);
		if($chuoi_color=="&color%5B%5D=")
		{
			$chuoi_color ='';
		}
		$limittime = $this->input->get('limittime');
		$limitnum = $this->input->get('limitnum');
		$per_page = $this->input->get('per_page');
		$string_search_condition='';
		if($this->input->get('key_search')!="")
		{
			$key_search = $this->input->get('key_search');
			$string_search_condition .='(`luxyart_tb_product_img`.`img_title` like "%'.$key_search.'%" or `luxyart_tb_product_img`.`img_tags` like "%'.$key_search.'%" or `luxyart_tb_user`.`user_macode` like "%'.$key_search.'%" or `luxyart_tb_user`.`display_name` like "%'.$key_search.'%") and ';
			$data['search_key']=$key_search;
		}
		else
		{
			$key_search ='';
			$data['search_key']='';
		}
		// xu ly type image
		if($this->input->get('typeimage')!="")
		{
			if($type_image!=0)
			{
				 $string_search_condition .='luxyart_tb_product_img.kind_img = '.$type_image.' and ';
				 $data['search_option2']=$type_image;
			}
			else
			{
				$data['search_option2']=0;
			}
		}
		// xuly color
		
		if($this->input->get('color')!=""){
			/*
			$data['search_color'] = (explode(", ",$color));
			$str_search_color = "";
			for($k=0;$k<count($data['search_color']);$k++){
				if($k == 0){
					$str_search_color .= "luxyart_tb_product_img.id_colors in (".$data['search_color'][$k].")";
				}
				else{
					$str_search_color .= " or luxyart_tb_product_img.id_colors in (".$data['search_color'][$k].")";
				}
			}*/
			$str_search_color .= "luxyart_tb_product_img.id_colors in (".$color.")";
			$string_search_condition .= '('.$str_search_color.') and ';
			 
		}
		else{
			$data['search_color'] = '';
		}
		
		// xuly limitlimittime
		if($this->input->get('limittime')!="")
		{
			$string_search_condition .='luxyart_tb_product_img.option_sale = '.$limittime.' and ';
			$limittime_link ='&limittime='.$limittime;
			$data['search_ltime'] = 1;
		}
		else
		{
			$limittime_link ='';
			$data['search_ltime'] = 0;
		}
		if($this->input->get('limitnum')!="")
			 {
			 	$string_search_condition .='luxyart_tb_product_img.is_limit = 1 and ';
				$limitnum_link ='&limitnum='.$this->input->get('limitnum');
				$data['search_lnum'] = 2;
			 }
			 else
			 {
				 $limitnum_link = '';
				 $data['search_lnum'] = 0;
			 }
		$string_search_condition .='luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and ';
		// load show in search page
		if($this->input->get('cate')!="all")
		{
			$data['sosanh_cate_id'] = $this->input->get('cate');
		}
		
		$whereProductCategory = array('level_cate' => 0, 'lang_id' => $this->lang_id, 'status_cate' => 1);
    	$orderProductCategory = array();
    	$data['list_product_category'] = $this->main_model->getAllData("luxyart_tb_product_category", $whereProductCategory, $orderProductCategory)->result();
		//list color
    	$whereColor = array('id_color > ' => 0,'lang_id' => $this->lang_id);
    	$orderColor = array();
    	$data['list_color'] = $this->main_model->getAllData("luxyart_tb_product_color", $whereColor, $orderColor)->result();
		//End load
		// Load img theo category + condition search
		// + Xu ly 
		//luxyart_tb_img_category root_cate,parent_cate,sub_cate
		$where_search_img = array('luxyart_tb_product_img.img_check_status' =>1, 'luxyart_tb_manager_server.server_status' =>0);
		// case search not follow cate
		if($this->input->get('cate')!="all" & $this->input->get('cate') !="")
		{
			$where_category = $string_search_condition.'(luxyart_tb_img_category.root_cate = '.$this->input->get('cate').' or luxyart_tb_img_category.parent_cate = '.$this->input->get('cate').' or luxyart_tb_img_category.sub_cate = '.$this->input->get('cate').')';
		}
		else
		{
			//$string_search_condition .=substr($string_search_condition, 0, -5);
			//$where_category = $string_search_condition.'(luxyart_tb_img_category.root_cate = 1 or luxyart_tb_img_category.parent_cate = 1 or luxyart_tb_img_category.sub_cate = 1)';
			$where_category = substr($string_search_condition, 0, -5);
		}
		
		$order_search_img = array();
		//Load phan trang
		
		$this->load->library('pagination');
		//$config['total_rows'] = 10;
		if($this->input->get('numpage')!="")
		{
			 $config['per_page'] = $this->input->get('numpage');
			 $data['search_page'] = $this->input->get('numpage');
		}
		else
		{
			$config['per_page'] = 50;
			$data['search_page'] = 50;
		}
		
		$config['total_rows'] = count($this->function_model->get_img_search_with_condition("luxyart_tb_product_img", $where_search_img, $where_category, $order_search_img,'','')->result());
		
		$config['uri_segment'] = $per_page;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_link'] =  'Next »';
		$config['prev_link'] =  '« Prev';
		$config['num_tag_open'] =  '';
		$config['num_tag_close'] =  '';
		$config['num_links']	=  5;
		$config['cur_tag_open'] =  '<a class="current">';
		$config['cur_tag_close'] =  '</a>';
		$config['base_url'] =  base_url().'/search?keysearch='.$this->input->get('key_search').'&cate='.$this->input->get('cate').$chuoi_type_image.$chuoi_color.$limittime_link.$limitnum_link.'&numpage='.$config['per_page'];
		
		$this->pagination->initialize($config);
		$data['phantrang'] = $this->pagination->create_links();		

		$data['load_product_with_condition_search'] = $this->function_model->get_img_search_with_condition("luxyart_tb_product_img", $where_search_img, $where_category, $order_search_img,$config['per_page'],$per_page)->result();
		
		$wherelist_pickup_category = array('lang_id' => $this->lang_id, 'status_cate' => 1,'is_pickup' =>1);
		$orderlist_pickup_category = array();
		$data['list_pickup_category'] = $this->main_model->getAllDataLimit("luxyart_tb_product_category", $wherelist_pickup_category, $orderlist_pickup_category,4,0)->result();
		// Load SEO
		if($this->input->get('cate')!="all"  & $this->input->get('cate') !="")
		{
			// XU ly cach goi cate
			$query = $this->db->query("Select * FROM luxyart_tb_product_category where cate_id=".$this->input->get('cate')." and lang_id=".$this->lang_id);
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				if (isset($row))
				{
					$data['cate_path_level']= $row->parent_cate;
					$query2 = $this->db->query("Select * FROM luxyart_tb_product_category where cate_id=".$row->parent_cate." and lang_id=".$this->lang_id);
					$row2 = $query2->row();
					if (isset($row2))
					{
						$data['cate_path_level_name']= $row2->cate_name;
					}
				}
				else
				{
					$data['cate_path_level']=0;
				}
			}
			else
			{
				$data['cate_path_level']=0;
			}
		}
		else
			{
				$data['cate_path_level']=0;
			}
		$meta = array();
    	$data['meta_seo'] = $this->function_model->getMetaSeo_Page_Cate_ID($this->input->get('cate'), $meta, $this->lang_id);	
    	$this->template->load('default', 'search', $data, __CLASS__);
	}
	 
}

