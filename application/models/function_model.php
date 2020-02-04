<?php

class Function_model extends CI_Model
	{
 	function __contruction()
	{
		parent::__contruction();
		
		$this->load->model('manager_image_model');
		$this->load->helper('cookie');
   	}
   	/**
   	 * Get User Follow
   	 * @author	dohuuthien 2017-09-12
   	 */
   	function getUserFollow()
	{
   		$this->db->from('luxyart_tb_user_pickup');
   		$this->db->select('user_id');
   		$this->db->order_by('position', 'ASC');
   		$this->db->limit(4);

   		$result = $this->db->get();
   		return $result;

   	}

   	/**
   	 * Get Count Product
   	 * @author	dohuuthien 2017-09-12
   	 */
   	function getCountProduct($user_id){

   		$this->db->from('luxyart_tb_product_img');
   		$this->db->select('user_id, count(id_img) as count');
   		$this->db->where(array('user_id' => $user_id, 'img_check_status' => 1));
   		$this->db->group_by('user_id');
   		$rs = $this->db->get();

   		$result = $rs->row();

   		if(!empty($result)){
   			return $result->count;
   		}
   		else{
   			return 0;
   		}

   	}
	function getCount_data($table, $where=array(), $order=array()){
   		if(count($where)>0)
   			$this->db->where($where);
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   		$re = $this->db->get($table);
   		$result = $re->num_rows();
   		if($result>0){
   			return $result;
   		}
   		else{
   			return 0;
   		}
   	}
	function get_img_moi_nhat($table,$where=array(), $order=array(), $limit)
	{
		//join
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		if(count($where)>0)
		{
   			$this->db->where($where);
		}
   		//$this->db->where('luxyart_tb_product_img.img_check_status', 1);
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   		if($limit > 0)
		{
   			$this->db->limit($limit);
		}
   		$this->db->from($table);
   		$this->db->select('luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_manager_server.server_path_upload,luxyart_tb_product_img.file_name_watermark_img_1');
   		$result = $this->db->get();
   		return $result;
	}
	function get_img_popular($table,$where=array(), $order=array(), $limit)
	{
		//join
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->join('luxyart_tb_user_bookmark_image', 'luxyart_tb_user_bookmark_image.id_img = luxyart_tb_product_img.id_img','left');
		if(count($where)>0)
		{
   			$this->db->where($where);
		}
   		//$this->db->where('luxyart_tb_product_img.img_check_status', 1);
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   		if($limit > 0)
		{
   			$this->db->limit($limit);
		}
   		$this->db->from($table);
   		$this->db->select('luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_manager_server.server_path_upload,luxyart_tb_product_img.file_name_watermark_img_1, COUNT(luxyart_tb_user_bookmark_image.id_img) as states_count');
		$this->db->group_by('luxyart_tb_product_img.id_img');
		$this->db->order_by('states_count', 'desc');
   		$result = $this->db->get();
   		return $result;
	}
	function get_img_search_with_condition($table,$where=array(), $where_category='', $order=array(),$number, $offset)
	{
		$this->db->cache_delete_all();
   		//$this->db->select('luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_product_img.option_sale,luxyart_tb_product_img.date_add_img,luxyart_tb_manager_server.server_path_upload,luxyart_tb_product_img.file_name_watermark_img_1, COUNT(luxyart_tb_user_bookmark_image.id_img) as states_count,luxyart_tb_img_category.root_cate,luxyart_tb_img_category.parent_cate,luxyart_tb_img_category.sub_cate');
		$this->db->select('luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_manager_server.server_path_upload,luxyart_tb_product_img.file_name_watermark_img_1');
		$this->db->from($table);
		$this->db->join('luxyart_tb_img_category', 'luxyart_tb_img_category.id_img = luxyart_tb_product_img.id_img','inner');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','inner');
		//$this->db->join('luxyart_tb_user_bookmark_image', 'luxyart_tb_user_bookmark_image.id_img = luxyart_tb_product_img.id_img','left');
		//$this->db->join('luxyart_tb_product_color', 'luxyart_tb_product_color.id_color = luxyart_tb_product_img.id_color','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_product_img.user_id','inner');
		if(count($where)>0)
		{
   			$this->db->where($where);
		}
		if($where_category!="")
		{
			$this->db->where($where_category);
		}
		$this->db->where('luxyart_tb_user.user_status',1);
		$this->db->where("CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		
   		//$this->db->where('luxyart_tb_product_img.img_check_status', 1);
		//$this->db->where('luxyart_tb_product_img.img_is_sold', 0);
		//$this->db->where('luxyart_tb_product_img.img_is_join_compe', 0);
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		
		$this->db->group_by('luxyart_tb_product_img.id_img');
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
   		$result = $this->db->get();
		
   		return $result;
		$result->free_result();
	}
   	/**
   	 * Get Meta Seo
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-12
   	 */
   	function getMetaSeo($type, $meta, $lang_id){
   		$wherePageSeo = array('name_call_code' => $type, 'lang_id' => $lang_id);
   		$orderPageSeo = array();
   		$data['pageSeo'] = $this->main_model->getAllData("luxyart_tb_page_seo", $wherePageSeo, $orderPageSeo)->row();
   		if(!empty($data['pageSeo'])){
   			$data['title_page'] = $data['pageSeo']->page_title_name;
   			$data['description_page'] = $data['pageSeo']->page_description;
   			$data['keywords'] = $data['pageSeo']->page_meta_key;
			$data['page_h1'] = $data['pageSeo']->page_h1;
			$data['return_title'] = $data['pageSeo']->return_title;
			$data['return_msg'] = $data['pageSeo']->return_msg;
			$data['return_link'] = $data['pageSeo']->return_link;
			
			//detail image
			if($type == "images/detail_image"){
				
				$data['title_page'] = str_replace("{img_title}", $meta['img_title'], $data['title_page']);
				$data['title_page'] = str_replace("{cate_name}", $meta['cate_name'], $data['title_page']);
				
				$data['description_page'] = str_replace("{img_title}", $meta['img_title'], $data['description_page']);
				$data['description_page'] = str_replace("{cate_name}", $meta['cate_name'], $data['description_page']);
				
			}
			else if($type == "compe/compe_detail"){
				
				$data['title_page'] = str_replace("{title_com}", $meta['title_com'], $data['title_page']);
				$data['description_page'] = str_replace("{title_com}", $meta['title_com'], $data['description_page']);
				
			}
			else if($type == "default_index/category"){
			
				//$data['title_page'] = str_replace("{title_com}", $meta['title_com'], $data['title_page']);
				//$data['description_page'] = str_replace("{title_com}", $meta['title_com'], $data['description_page']);
			
			}
			
			
   		}
   		return $data;
   	}
	function getMetaSeo_Page_Cate_ID($id_cate, $meta, $lang_id){
		// Set SEO
		if($id_cate!="all")
		{
   			$this->db->from('luxyart_tb_product_category');
   			$this->db->select('cate_name,note_description,meta_key_seo_cate,img_category,parent_cate');
			$this->db->where(array('product_category_id' => $id_cate, 'lang_id' => $lang_id, 'status_cate' => 1));
   			$result = $this->db->get();
			$data['pageSeo'] = $result->row();
			if(!empty($data['pageSeo'])){
				//$data['title_page'] = $data['pageSeo']->cate_name.'-LuxyArt';
				//$data['description_page'] = $data['pageSeo']->note_description;
				//$data['keywords'] = $data['pageSeo']->meta_key_seo_cate;
				//$data['page_h1'] = $data['pageSeo']->cate_name;
				$data['cate_img_id'] = $data['pageSeo']->img_category;
				$data['load_parent_cate_id'] = $data['pageSeo']->parent_cate;
	
			}
			
			//default_index/category_sub
			$wherePageSeo = array('name_call_code' => 'default_index/category_sub', 'lang_id' => $lang_id);
			$orderPageSeo = array();
			$data['page_seo'] = $this->main_model->getAllData("luxyart_tb_page_seo", $wherePageSeo, $orderPageSeo)->row();
				
			if(!empty($data['page_seo'])){
				$data['title_page'] = $data['page_seo']->page_title_name;
				$data['description_page'] = $data['page_seo']->page_description;
				$data['keywords'] = $data['page_seo']->page_meta_key;
				$data['page_h1'] = $data['page_seo']->page_h1;
				$data['return_title'] = $data['page_seo']->return_title;
				$data['return_msg'] = $data['page_seo']->return_msg;
				$data['return_link'] = $data['pageSeo']->return_link;
				
				//{category name}
				//{str_sub_category_name}
				
				$data['keywords'] = str_replace("{category name}", $data['pageSeo']->cate_name, $data['keywords']);
				//{str_sub_category_name}
				
			}
			
			if($data['load_parent_cate_id'] == 0){
				
				$this->db->from('luxyart_tb_product_category');
				$this->db->select('*');
				$this->db->where(array('level_cate' => 1, 'root_cate' => $id_cate, 'lang_id' => $lang_id, 'status_cate' => 1));
				$this->db->limit(3);
				$rsProductCategory = $this->db->get();
				$resultProductCategory = $rsProductCategory->result();
				
				foreach($resultProductCategory as $_resultProductCategory){
					$str_sub_category_name .= "「".$_resultProductCategory->cate_name."」";
					$i++;
				}
				
				$data['title_page'] = str_replace("{category_name}", $data['pageSeo']->cate_name, $data['title_page']);
				$data['keywords'] = str_replace("{category_name}", $data['pageSeo']->cate_name, $data['keywords']);
				$data['description_page'] = str_replace("{str_sub_category_name}", $str_sub_category_name, $data['description_page']);
				
			}
			else{
				
				$data['title_page'] = str_replace("{category_name}", $data['pageSeo']->cate_name, $data['title_page']);
				$data['keywords'] = str_replace("{category_name}", $data['pageSeo']->cate_name, $data['keywords']);
				$data['description_page'] = str_replace("{str_sub_category_name}", $data['pageSeo']->cate_name, $data['description_page']);
				
			}
			
		}
		else
		{
				$data['title_page'] = '検索結果-写真・画像素材販売のLuxyArt';
				$data['description_page'] = '写真素材・ストックフォト - 画像素材販売のLuxyArtの検索結果ページです。';
				$data['keywords'] = '写真素材,ストックフォト,画像素材販売';
				$data['page_h1'] = '検索結果';
				$data['cate_img_id'] = '';
				$data['load_parent_cate_id'] = 1;
		}
		return $data;
		//echo $this->db->last_query();
		//exit();
   	}
   	/**
   	 * Days Diff
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-12
   	 */
   	function days_diff($d1, $d2) {

   		$date1 = date_create(date('Y-m-d', strtotime($d1)));
		$date2 = date_create(date('Y-m-d', strtotime($d2)));
		$diff = date_diff($date1,$date2);

		return $diff->format('%d');

   	}

   	/**
   	 * Get Menu Top
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-13
   	 */
   	function getMenuTop() {

   		$this->db->from('luxyart_tb_load_menu');
   		$this->db->select('*');
   		$this->db->where('is_header', 1);
   		$this->db->order_by('position_menu', 'ASC');
   		$result = $this->db->get();

   		return $result;

   	}

   	/**
   	 * Get Menu Top
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-13
   	 */
   	function getMenuBottom() {

   		$this->db->from('luxyart_tb_load_menu');
   		$this->db->select('*');
   		$this->db->where('is_footer', 1);
   		$this->db->order_by('position_menu', 'ASC');
   		$result = $this->db->get();

   		return $result;

   	}

   	function addNotice($data){

   		$data["lang_id"] = 1;//delete when finish

   		$this->db->from('luxyart_tb_inbox_templates');
   		$this->db->select('*');
   		$this->db->where(array('name' => $data["type"], 'lang_id' => $data["lang_id"]));
   		$rs = $this->db->get();

   		$row = $rs->row();
   		if(!empty($row)){
   			$inbox_templates_id = $row->inbox_templates_id;
   			$subject = $row->subject;
   			$message = $row->message;
   			$link = $row->link;
   		}
   		else{
   			echo "Please create record notice template...";
   			exit;
   		}

   		$key_message = $this->getInbetweenStrings('{', '}', $message);
   		$key_link = $this->getInbetweenStrings('{', '}', $link);
		$key_subject = $this->getInbetweenStrings('{', '}', $subject);

   		$is_debug 							= isset($data['is_debug']) ? $data['is_debug'] : 0;
   		$option_id 							= isset($data['option_id']) ? $data['option_id'] : 0;

   		$value_email 						= isset($data['email']) ? $data['email'] : "";
   		$value_username 					= isset($data['username']) ? $data['username'] : "";
   		$value_name 						= isset($data['name']) ? $data['name'] : "";

   		$value_user_fullname				= isset($data['user_fullname']) ? $data['user_fullname'] : "";
   		$value_link 						= isset($data['link']) ? $data['link'] : "";
   		$value_id_img						= isset($data['id_img']) ? $data['id_img'] : "";
   		$value_img_title					= isset($data['img_title']) ? $data['img_title'] : "";
   		$value_id_competition				= isset($data['id_competition']) ? $data['id_competition'] : "";
   		$value_title_com					= isset($data['title_com']) ? $data['title_com'] : "";
   		$value_user_id						= isset($data['user_id']) ? $data['user_id'] : "";
		$value_account_level				= isset($data['account_level']) ? $data['account_level'] : "";
		$value_point						= isset($data['point']) ? $data['point'] : "";
		$value_money_ask					= isset($data['money_ask']) ? $data['money_ask'] : "";
		$value_email_apply					= isset($data['email_apply']) ? $data['email_apply'] : "";
		$value_person_apply					= isset($data['person_apply']) ? $data['person_apply'] : "";
		$value_link_compe_check				= isset($data['link_compe_check']) ? $data['link_compe_check'] : "";
		$value_email_post_competition		= isset($data['email_post_competition']) ? $data['email_post_competition'] : "";
		$value_email_get					= isset($data['email_get']) ? $data['email_get'] : "";
		$value_email_report					= isset($data['email_report']) ? $data['email_report'] : "";
		$value_return_point					= isset($data['return_point']) ? $data['return_point'] : "";
		$value_package_name					= isset($data['package_name']) ? $data['package_name'] : "";
		$value_lux							= isset($data['lux']) ? $data['lux'] : "";
		$value_content						= isset($data['content']) ? $data['content'] : "";
		
		$value_key_1						= isset($data['key_1']) ? $data['key_1'] : "";
		$value_key_2						= isset($data['key_2']) ? $data['key_2'] : "";
		$value_key_3						= isset($data['key_3']) ? $data['key_3'] : "";
		$value_key_4						= isset($data['key_4']) ? $data['key_4'] : "";
		$value_key_5						= isset($data['key_5']) ? $data['key_5'] : "";

   		$i = 1;
   		$message_end = $message;

   		foreach($key_message as $detail_key_message){

   			$msg = str_replace('{}', "",$message);

   			if($detail_key_message == "email"){
   				if($i == 1){
   					$message_end = str_replace('{email}',$value_email,$msg);
   				}
   				else{
   					$message_end = str_replace('{email}',$value_email,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "username"){
   				if($i == 1){
   					$message_end = str_replace('{username}',$value_username,$msg);
   				}
   				else{
   					$message_end = str_replace('{username}',$value_username,$message_end);
   				}
   				$i++;
   			}
			if($detail_key_message == "account_level"){
   				if($i == 1){
   					$message_end = str_replace('{account_level}',$value_account_level,$msg);
   				}
   				else{
   					$message_end = str_replace('{account_level}',$value_account_level,$message_end);
   				}
   				$i++;
   			}
			if($detail_key_message == "point"){
   				if($i == 1){
   					$message_end = str_replace('{point}',$value_point,$msg);
   				}
   				else{
   					$message_end = str_replace('{point}',$value_point,$message_end);
   				}
   				$i++;
   			}
			if($detail_key_message == "money_ask"){
   				if($i == 1){
   					$message_end = str_replace('{money_ask}',$value_money_ask,$msg);
   				}
   				else{
   					$message_end = str_replace('{money_ask}',$value_money_ask,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "name"){
   				if($i == 1){
   					$message_end = str_replace('{name}',$value_name,$msg);
   				}
   				else{
   					$message_end = str_replace('{name}',$value_name,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "user_fullname"){
   				if($i == 1){
   					$message_end = str_replace('{user_fullname}',$value_user_fullname,$msg);
   				}
   				else{
   					$message_end = str_replace('{user_fullname}',$value_user_fullname,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "link"){
   				if($i == 1){
   					$message_end = str_replace('{link}',$value_link,$msg);
   				}
   				else{
   					$message_end = str_replace('{link}',$value_link,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "id_img"){
   				if($i == 1){
   					$message_end = str_replace('{id_img}',$value_id_img,$msg);
   				}
   				else{
   					$message_end = str_replace('{id_img}',$value_id_img,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "img_title"){
   				if($i == 1){
   					$message_end = str_replace('{img_title}',$value_img_title,$msg);
   				}
   				else{
   					$message_end = str_replace('{img_title}',$value_img_title,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "id_competition"){
   				if($i == 1){
   					$message_end = str_replace('{id_competition}',$value_id_competition,$msg);
   				}
   				else{
   					$message_end = str_replace('{id_competition}',$value_id_competition,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "title_com"){
   				if($i == 1){
   					$message_end = str_replace('{title_com}',$value_title_com,$msg);
   				}
   				else{
   					$message_end = str_replace('{title_com}',$value_title_com,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "user_id"){
   				if($i == 1){
   					$message_end = str_replace('{user_id}',$value_user_id,$msg);
   				}
   				else{
   					$message_end = str_replace('{user_id}',$value_user_id,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "email_apply"){

   				if($i == 1){

   					$message_end = str_replace('{email_apply}',$value_email_apply,$msg);

   				}

   				else{

   					$message_end = str_replace('{email_apply}',$value_email_apply,$message_end);

   				}

   				$i++;

   			}

   			if($detail_key_message == "person_apply"){

   				if($i == 1){

   					$message_end = str_replace('{person_apply}',$value_person_apply,$msg);

   				}

   				else{

   					$message_end = str_replace('{person_apply}',$value_person_apply,$message_end);

   				}

   				$i++;

   			}

   			if($detail_key_message == "link_compe_check"){
   				if($i == 1){
   					$message_end = str_replace('{link_compe_check}',$value_link_compe_check,$msg);
   				}
   				else{
   					$message_end = str_replace('{link_compe_check}',$value_link_compe_check,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "email_post_competition"){
   				if($i == 1){
   					$message_end = str_replace('{email_post_competition}',$value_email_post_competition,$msg);
   				}
   				else{
   					$message_end = str_replace('{email_post_competition}',$value_email_post_competition,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "email_get"){
   				if($i == 1){
   					$message_end = str_replace('{email_get}',$value_email_get,$msg);
   				}
   				else{
   					$message_end = str_replace('{email_get}',$value_email_get,$message_end);
   				}
   				$i++;
   			}

   			if($detail_key_message == "email_report"){
   				if($i == 1){
   					$message_end = str_replace('{email_report}',$value_email_report,$msg);
   				}
   				else{
   					$message_end = str_replace('{email_report}',$value_email_report,$message_end);
   				}
   				$i++;
   			}
   			
   			if($detail_key_message == "return_point"){
   				if($i == 1){
   					$message_end = str_replace('{return_point}',$value_return_point,$msg);
   				}
   				else{
   					$message_end = str_replace('{return_point}',$value_return_point,$message_end);
   				}
   				$i++;
   			}
   			
   			if($detail_key_message == "package_name"){
   				if($i == 1){
   					$message_end = str_replace('{package_name}',$value_package_name,$msg);
   				}
   				else{
   					$message_end = str_replace('{package_name}',$value_package_name,$message_end);
   				}
   				$i++;
   			}
   			
   			if($detail_key_message == "lux"){
   				if($i == 1){
   					$message_end = str_replace('{lux}',$value_lux,$msg);
   				}
   				else{
   					$message_end = str_replace('{lux}',$value_lux,$message_end);
   				}
   				$i++;
   			}
   			
   			if($detail_key_message == "content"){
   				if($i == 1){
   					$message_end = str_replace('{content}',$value_content,$msg);
   				}
   				else{
   					$message_end = str_replace('{content}',$value_content,$message_end);
   				}
   				$i++;
   			}
   			
			if($detail_key_message == "key_1"){
   				if($i == 1){
   					$message_end = str_replace('{key_1}',$value_key_1,$msg);
   				}
   				else{
   					$message_end = str_replace('{key_1}',$value_key_1,$message_end);
   				}
   				$i++;
   			}
			if($detail_key_message == "key_2"){
   				if($i == 1){
   					$message_end = str_replace('{key_2}',$value_key_2,$msg);
   				}
   				else{
   					$message_end = str_replace('{key_2}',$value_key_2,$message_end);
   				}
   				$i++;
   			}
			if($detail_key_message == "key_3"){
   				if($i == 1){
   					$message_end = str_replace('{key_3}',$value_key_3,$msg);
   				}
   				else{
   					$message_end = str_replace('{key_3}',$value_key_3,$message_end);
   				}
   				$i++;
   			}
			if($detail_key_message == "key_4"){
   				if($i == 1){
   					$message_end = str_replace('{key_4}',$value_key_4,$msg);
   				}
   				else{
   					$message_end = str_replace('{key_4}',$value_key_4,$message_end);
   				}
   				$i++;
   			}
			if($detail_key_message == "key_5"){
   				if($i == 1){
   					$message_end = str_replace('{key_5}',$value_key_5,$msg);
   				}
   				else{
   					$message_end = str_replace('{key_5}',$value_key_5,$message_end);
   				}
   				$i++;
   			}

   		}

   		$k = 1;
   		$subject_end = $subject;

   		foreach($key_subject as $detail_key_subject){

   			$subj = str_replace('{}', "",$subject);

   			if($detail_key_subject == "email"){
   				if($k == 1){
   					$subject_end = str_replace('{email}',$value_email,$subj);
   				}
   				else{
   					$subject_end = str_replace('{email}',$value_email,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "username"){
   				if($k == 1){
   					$subject_end = str_replace('{username}',$value_username,$subj);
   				}
   				else{
   					$subject_end = str_replace('{username}',$value_username,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "name"){
   				if($k == 1){
   					$subject_end = str_replace('{name}',$value_name,$subj);
   				}
   				else{
   					$subject_end = str_replace('{name}',$value_name,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "user_fullname"){
   				if($k == 1){
   					$subject_end = str_replace('{user_fullname}',$value_user_fullname,$subj);
   				}
   				else{
   					$subject_end = str_replace('{user_fullname}',$value_user_fullname,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "link"){
   				if($k == 1){
   					$subject_end = str_replace('{link}',$value_link,$subj);
   				}
   				else{
   					$subject_end = str_replace('{link}',$value_link,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "id_img"){
   				if($k == 1){
   					$subject_end = str_replace('{id_img}',$value_id_img,$subj);
   				}
   				else{
   					$subject_end = str_replace('{id_img}',$value_id_img,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "img_title"){
   				if($k == 1){
   					$subject_end = str_replace('{img_title}',$value_img_title,$subj);
   				}
   				else{
   					$subject_end = str_replace('{img_title}',$value_img_title,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "id_competition"){
   				if($k == 1){
   					$subject_end = str_replace('{id_competition}',$value_id_competition,$subj);
   				}
   				else{
   					$subject_end = str_replace('{id_competition}',$value_id_competition,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "title_com"){
   				if($k == 1){
   					$subject_end = str_replace('{title_com}',$value_title_com,$subj);
   				}
   				else{
   					$subject_end = str_replace('{title_com}',$value_title_com,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "user_id"){
   				if($k == 1){
   					$subject_end = str_replace('{user_id}',$value_user_id,$subj);
   				}
   				else{
   					$subject_end = str_replace('{user_id}',$value_user_id,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "email_apply"){

   				if($k == 1){

   					$subject_end = str_replace('{email_apply}',$value_email_apply,$subj);

   				}

   				else{

   					$subject_end = str_replace('{email_apply}',$value_email_apply,$subject_end);

   				}

   				$k++;

   			}

   			if($detail_key_subject == "person_apply"){

   				if($k == 1){

   					$subject_end = str_replace('{person_apply}',$value_person_apply,$subj);

   				}

   				else{

   					$subject_end = str_replace('{person_apply}',$value_person_apply,$subject_end);

   				}

   				$k++;

   			}

   			if($detail_key_subject == "link_compe_check"){
   				if($k == 1){
   					$subject_end = str_replace('{link_compe_check}',$value_link_compe_check,$subj);
   				}
   				else{
   					$subject_end = str_replace('{link_compe_check}',$value_link_compe_check,$subject_end);
   				}
   				$k++;
   			}

   			if($detail_key_subject == "email_post_competition"){
   				if($k == 1){
   					$subject_end = str_replace('{email_post_competition}',$value_email_post_competition,$subj);
   				}
   				else{
   					$subject_end = str_replace('{email_post_competition}',$value_email_post_competition,$subject_end);
   				}
   				$k++;
   			}
   			
   			if($detail_key_subject == "point"){
   				if($k == 1){
   					$subject_end = str_replace('{point}',$value_point,$subj);
   				}
   				else{
   					$subject_end = str_replace('{point}',$value_point,$subject_end);
   				}
   				$k++;
   			}

   		}

   		$j = 1;
   		$link_end = $link;

   		foreach($key_link as $detail_key_link){

   			$lk = str_replace('{}',"",$link);

   			if($detail_key_link == "email"){
   				if($j == 1){
   					$link_end = str_replace('{email}',$value_email,$lk);
   				}
   				else{
   					$link_end = str_replace('{email}',$value_email,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "username"){
   				if($j == 1){
   					$link_end = str_replace('{username}',$value_username,$lk);
   				}
   				else{
   					$link_end = str_replace('{username}',$value_username,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "name"){
   				if($j == 1){
   					$link_end = str_replace('{name}',$value_username,$lk);
   				}
   				else{
   					$link_end = str_replace('{name}',$value_username,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "user_fullname"){
   				if($j == 1){
   					$link_end = str_replace('{user_fullname}',$value_user_fullname,$lk);
   				}
   				else{
   					$link_end = str_replace('{user_fullname}',$value_user_fullname,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "link"){
   				if($j == 1){
   					$link_end = str_replace('{link}',$value_link,$lk);
   				}
   				else{
   					$link_end = str_replace('{link}',$value_link,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "id_img"){
   				if($j == 1){
   					$link_end = str_replace('{id_img}',$value_id_img,$lk);
   				}
   				else{
   					$link_end = str_replace('{id_img}',$value_id_img,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "img_title"){
   				if($j == 1){
   					$link_end = str_replace('{img_title}',$value_img_title,$lk);
   				}
   				else{
   					$link_end = str_replace('{img_title}',$value_img_title,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "id_competition"){
   				if($j == 1){
   					$link_end = str_replace('{id_competition}',$value_id_competition,$lk);
   				}
   				else{
   					$link_end = str_replace('{id_competition}',$value_id_competition,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "title_com"){
   				if($j == 1){
   					$link_end = str_replace('{title_com}',$value_title_com,$lk);
   				}
   				else{
   					$link_end = str_replace('{title_com}',$value_title_com,$link_end);
   				}
   				$j++;
   			}

   			if($detail_key_link == "user_id"){
   				if($j == 1){
   					$link_end = str_replace('{user_id}',$value_user_id,$lk);
   				}
   				else{
   					$link_end = str_replace('{user_id}',$value_user_id,$link_end);
   				}
   				$j++;
   			}
   			
   			if($detail_key_link == "lux"){
   				if($j == 1){
   					$link_end = str_replace('{lux}',$value_lux,$lk);
   				}
   				else{
   					$link_end = str_replace('{lux}',$value_lux,$link_end);
   				}
   				$j++;
   			}
   			
   			if($detail_key_link == "content"){
   				if($j == 1){
   					$link_end = str_replace('{content}',$value_content,$lk);
   				}
   				else{
   					$link_end = str_replace('{content}',$value_content,$link_end);
   				}
   				$j++;
   			}

   		}

   		if($data['user_send_id'] == ""){
   			$data['user_send_id'] = -1;
   		}

   		if($is_debug == 1){

   			echo "message_end = ".$message_end."<br>";
   			echo "subject_end = ".$subject_end."<br>";
   			echo "link_end = ".$link_end."<br>";

   		}
   		else{

   			$this->db->from('luxyart_tb_user');
   			$this->db->select('notice_luxyart');
   			$this->db->where(array('user_id' => $data['user_id']));
   			$rsUser = $this->db->get();
   			$rowUser = $rsUser->row();
   			if(!empty($rowUser)){
   				$notice_luxyart = $rowUser->notice_luxyart;
   			}

   			if($notice_luxyart == 1 || 1 == 1){

	   			$dataInsertUserMessage = array(

	   					'user_send_id' 			=> $data['user_send_id'],
	   					'user_id' 				=> $data['user_id'],

	   					'type_message' 			=> $inbox_templates_id,
	   					'ms_subject' 			=> $subject_end,

	   					'ms_content' 			=> $message_end,

	   					'ms_link' 				=> $link_end,

	   					'date_message' 			=> date('Y-m-d H:i:s'),

	   					'ms_is_read' 			=> 0,

	   					'status' 				=> 1,
	   					'option_id' 			=> $option_id

	   			);

	   			$this->db->insert('luxyart_tb_user_message', $dataInsertUserMessage);

   			}

   		}

   	}

	function sendMailHTML($data){

		$data["lang_id"] = 1;//delete when finish

		$headers = "Content-Type: text/html; charset=UTF-8\r\n";
		$this->db->from('luxyart_tb_email_send');
   		$this->db->select('*');
   		$this->db->where(array('name_email_code' => $data["name_email_code"], 'lang_id' => $data["lang_id"]));
   		$rs = $this->db->get();

   		$row = $rs->row();
   		if(!empty($row)){
   			//get info send email
   			$email_to = $data["email_to"];
   			$email_subject = $row->subject;
   			$email_body = $row->content_email;
   		}
   		else{
   			echo "Please create record email send...";
   			exit;
   		}
		$key_subject = $this->getInbetweenStrings('{', '}', $email_subject);
		$key_body = $this->getInbetweenStrings('{', '}', $email_body);
		//default
		$default_title_web = "株式会社LUXYART";
		$default_logo_web = 'https://lh3.googleusercontent.com/nXqwErNCwtut3vbrPeM31-RvGPSENQ_XSXXWzRwo6cm_m1vAHBGny9Jz-Tx1Z17Zfh8RoQ54Jkrecu_5PFDWu76k5Kky5WGDl8JlZz4mn9y1WKvG3yPlGRVQsmr70qNQf9tZwC6jUd2B_DzDJvew66eZc9jBJ1fxzxH-HfK4LMtYMcXXWsEQw3BcnKFRvdLsmGc5fs0f1Tpkt9EY8NaP9_jeNkFRRm03E-WX3To2SxEqk1smCXnltp6JQOmCiiaY9qRPOEtGgw0ffZ-t-LTXP-ZaQ4ySxa9VMbJjjsvt087DdxgMkL1Q2Xn5m1PhYunpGQTEnUfoyWdT3iMMroY2DcTn9GqkLCuW9I02HEfwkp85z5SWV9ZyCQY_Q6GbReTRISvUgtZ008u3tK1PYbgNBgndRwoizlhcEkTU-0O4fWuxSnx-FJn97ixqp4PkGVtb_G833QfdRaFcv1WMT5AQqKCpDegWxNsN6WJWKDEVDakSNcqNXhga7PMztOr2G5SyGtlq2J8NDFP7hTfOFNpSbRyv-98t30yBkWt-hT8yVZPbiv_q_arVuOAhbWEMMjoyk0Mr1K2ctwwJ9qNPUUTmCpLjPKio9FKbBm09I8vX7H-4qjdaDnj_Ym7Bp9tSOku50Az-0rf_tgd7Ntgc8O1PHG4BNXPMEdN_=w160-h36-no';
		$default_link_web = base_url();
		$default_bg_img = 'https://lh3.googleusercontent.com/1XaygB1fu0vbJXufHIPesUdQstwurR9T8UehQBVBzwZZPEuv4blTU3L6VJwAC-WVKSAEhw30dRn7u9QkcEt3CRPJnz0_fqnnqfJTyfPKJnv3xa67CFGLzWiv2uKXj1MqaXSJC1U11AfmSkjuewL2vgrsM3HSRnsbuOXyPe35WxF_aQLqA9pWIFyi8GB5KRzCPXnhpx8zVD_qT56_nF4mMtDhHeOD-TvqLflZwfXOzoefU7J2IFXEDCFoTz8L6DyUY13qbFwuZgdbEqm-x1SfsZxn87pDbpx5cc5EnqpL3f40thA_kejwf66JukVlW9l3qZd2vLHHyJxItOGjqyRNn7F0LI_y3nXvwDmnLhjFh9Iv8hbyhD5wzbIwBJCqPgZ5nQmJGc0_m05irJK3aNuaR1UI4Kfet1tyWi6tKDBjwwhd2PJw-wJxvxLhAZshib9RTPobbi31Gty07zTeWAPGzowf2K7RpR0ZDFxSMIx4d6Ua_PwWzm3P4Vg4Ysv-s52lx9qJmZy-StyFysCIMzRDVVl_XIub2V8v8fTRvXdFiD_VDQKU_LXSfGOTAVt5pRJ93Z8didgMfNnXr6KT9febuzujV-4-gaFCGrF3J5IvIj6e-qt7qrafDBLbSjXnPzHMlGvHPwWZ1o9R2jQAE9zXFQx9t2k-uUWw=w600-h204-no';
		$default_icon_email = base_url('publics/img/email.png');
		$default_email_address ='info@luxy.art';
		$default_icon_tel = base_url('publics/img/phone.png');
		$default_tel_number = '03-5537-6672';
		$default_link_app_ios ='';
		$default_img_app_ios = base_url('publics/img/ic-app-ios.png');
		$default_link_app_google ='';
		$default_img_app_google = base_url('publics/img/ic-app-android.png');
		$default_address_company ='〒104-0061　東京都中央区銀座六丁目6番1号 5F';
		$default_tel_company = '03-5537-6672';
		//end default

		$is_send_admin 						= isset($data['is_send_admin']) ? $data['is_send_admin'] : 0;
		$is_debug 							= isset($data['is_debug']) ? $data['is_debug'] : 0;
		$file_attachment 					= isset($data['file_attachment']) ? $data['file_attachment'] : "";

		$value_email 						= isset($data['email']) ? $data['email'] : "";
		$value_username 					= isset($data['username']) ? $data['username'] : "";
		$value_password 					= isset($data['password']) ? $data['password'] : "";
		$value_name 						= isset($data['name']) ? $data['name'] : "";
		$value_link 						= isset($data['link']) ? $data['link'] : "";
		$value_img_title					= isset($data['img_title']) ? $data['img_title'] : "";
		$value_img_width					= isset($data['img_width']) ? $data['img_width'] : "";
		$value_img_height					= isset($data['img_height']) ? $data['img_height'] : "";
		$value_option_sale					= isset($data['option_sale']) ? $data['option_sale'] : "";
		$value_type_export_image			= isset($data['type_export_image']) ? $data['type_export_image'] : "";
		$value_user_fullname				= isset($data['user_fullname']) ? $data['user_fullname'] : "";

		$value_email_buy_image				= isset($data['email_buy_image']) ? $data['email_buy_image'] : "";
		$value_person_buy_image				= isset($data['person_buy_image']) ? $data['person_buy_image'] : "";
		$value_link_order					= isset($data['link_order']) ? $data['link_order'] : "";
		$value_date_end_download			= isset($data['date_end_download']) ? $data['date_end_download'] : "";

		$value_title_com					= isset($data['title_com']) ? $data['title_com'] : "";
		$value_date_start_com				= isset($data['date_start_com']) ? $data['date_start_com'] : "";
		$value_date_end_com					= isset($data['date_end_com']) ? $data['date_end_com'] : "";
		$value_point_img_com				= isset($data['point_img_com']) ? $data['point_img_com'] : "";
		$value_img_quantity_com				= isset($data['img_quantity_com']) ? $data['img_quantity_com'] : "";
		$value_link_url_com					= isset($data['link_url_com']) ? $data['link_url_com'] : "";
		$value_point_package				= isset($data['point_package']) ? $data['point_package'] : "";
		$value_account_level				= isset($data['account_level']) ? $data['account_level'] : "";
		// when user ask export money
		$value_point						= isset($data['point']) ? $data['point'] : "";
		$value_money_ask					= isset($data['money_ask']) ? $data['money_ask'] : "";
		// End ask export money

		$value_email_apply					= isset($data['email_apply']) ? $data['email_apply'] : "";
		$value_person_apply					= isset($data['person_apply']) ? $data['person_apply'] : "";
		$value_link_compe_check				= isset($data['link_compe_check']) ? $data['link_compe_check'] : "";
		$value_content_contact				= isset($data['content_contact']) ? $data['content_contact'] : "";
		$value_type_contact					= isset($data['type_contact']) ? $data['type_contact'] : "";
		$value_link_image_review			= isset($data['link_image_review']) ? $data['link_image_review'] : "";
		$value_email_post_competition		= isset($data['email_post_competition']) ? $data['email_post_competition'] : "";

		$value_num_day_extend				= isset($data['num_day_extend']) ? $data['num_day_extend'] : "";
		$value_link_extend					= isset($data['link_extend']) ? $data['link_extend'] : "";
		$value_date_extend					= isset($data['date_extend']) ? $data['date_extend'] : "";
		$value_list_image_choose			= isset($data['list_image_choose']) ? $data['list_image_choose'] : "";

		$value_email_get					= isset($data['email_get']) ? $data['email_get'] : "";
		$value_name_get						= isset($data['name_get']) ? $data['name_get'] : "";
		$value_email_report					= isset($data['email_report']) ? $data['email_report'] : "";
		$value_name_report					= isset($data['name_report']) ? $data['name_report'] : "";
		$value_content_report				= isset($data['content_report']) ? $data['content_report'] : "";
		$value_return_point					= isset($data['return_point']) ? $data['return_point'] : "";
		$value_link_image_choose			= isset($data['link_image_choose']) ? $data['link_image_choose'] : "";
		
		$value_date_start_choose			= isset($data['date_start_choose']) ? $data['date_start_choose'] : "";
		$value_date_end_choose				= isset($data['date_end_choose']) ? $data['date_end_choose'] : "";
		$value_package_name					= isset($data['package_name']) ? $data['package_name'] : "";
		
		$value_comment						= isset($data['comment']) ? $data['comment'] : "";
		$value_email_post_comment			= isset($data['email_post_comment']) ? $data['email_post_comment'] : "";
		$value_name_post_comment			= isset($data['name_post_comment']) ? $data['name_post_comment'] : "";
		
		$value_email_invite					= isset($data['email_invite']) ? $data['email_invite'] : "";
		$value_name_invite					= isset($data['name_invite']) ? $data['name_invite'] : "";
		$value_link_invite					= isset($data['link_invite']) ? $data['link_invite'] : "";
		$value_link_member					= isset($data['link_member']) ? $data['link_member'] : "";
		$value_link_accept					= isset($data['link_accept']) ? $data['link_accept'] : "";
		$value_link_not_accept				= isset($data['link_not_accept']) ? $data['link_not_accept'] : "";

		$value_key_1						= isset($data['key_1']) ? $data['key_1'] : "";
		$value_key_2						= isset($data['key_2']) ? $data['key_2'] : "";
		$value_key_3						= isset($data['key_3']) ? $data['key_3'] : "";
		$value_key_4						= isset($data['key_4']) ? $data['key_4'] : "";
		$value_key_5						= isset($data['key_5']) ? $data['key_5'] : "";
		$value_key_6						= isset($data['key_6']) ? $data['key_6'] : "";

		$i = 1;
		$email_subject_end = $email_subject;

		foreach($key_subject as $detail_key_subject){

			$subject = str_replace('{}',$engine_name,$email_subject);


			if($detail_key_subject == "email"){
				if($i == 1){
					$email_subject_end = str_replace('{email}',$value_email,$subject);
				}
				else{
					$email_subject_end = str_replace('{email}',$value_email,$email_subject_end);
				}
				$i++;
			}
			if($detail_key_subject == "username"){
				if($i == 1){
					$email_subject_end = str_replace('{username}',$value_username,$subject);
				}
				else{
					$email_subject_end = str_replace('{username}',$value_username,$email_subject_end);
				}
				$i++;
			}
			if($detail_key_subject == "name"){
				if($i == 1){
					$email_subject_end = str_replace('{name}',$value_name,$subject);
				}
				else{
					$email_subject_end = str_replace('{name}',$value_name,$email_subject_end);
				}
				$i++;
			}

			if($detail_key_subject == "content_contact"){
				if($i == 1){
					$email_subject_end = str_replace('{content_contact}',$value_content_contact,$subject);
				}
				else{
					$email_subject_end = str_replace('{content_contact}',$value_content_contact,$email_subject_end);
				}
				$i++;
			}
			
			if($detail_key_subject == "title_com"){
				if($i == 1){
					$email_subject_end = str_replace('{title_com}',$value_title_com,$subject);
				}
				else{
					$email_subject_end = str_replace('{title_com}',$value_title_com,$email_subject_end);
				}
				$i++;
			}
			
			if($detail_key_subject == "name_invite"){
				if($i == 1){
					$email_subject_end = str_replace('{name_invite}',$value_name_invite,$subject);
				}
				else{
					$email_subject_end = str_replace('{name_invite}',$value_name_invite,$email_subject_end);
				}
				$i++;
			}

		}
		$j = 1;

		foreach($key_body as $detail_key_body){

			$mail = str_replace('{}',"",$email_body);
			//case default
			if($detail_key_body == "default_title_web"){
				if($i == 1){
					$mail_end = str_replace('{default_title_web}',$default_title_web,$mail);
				}
				else{
					$mail_end = str_replace('{default_title_web}',$default_title_web,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_logo_web"){
				if($i == 1){
					$mail_end = str_replace('{default_logo_web}',$default_logo_web,$mail);
				}
				else{
					$mail_end = str_replace('{default_logo_web}',$default_logo_web,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_link_web"){
				if($i == 1){
					$mail_end = str_replace('{default_link_web}',$default_link_web,$mail);
				}
				else{
					$mail_end = str_replace('{default_link_web}',$default_link_web,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_bg_img"){
				if($i == 1){
					$mail_end = str_replace('{default_bg_img}',$default_bg_img,$mail);
				}
				else{
					$mail_end = str_replace('{default_bg_img}',$default_bg_img,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_icon_email"){
				if($i == 1){
					$mail_end = str_replace('{default_icon_email}',$default_icon_email,$mail);
				}
				else{
					$mail_end = str_replace('{default_icon_email}',$default_icon_email,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_email_address"){
				if($i == 1){
					$mail_end = str_replace('{default_email_address}',$default_email_address,$mail);
				}
				else{
					$mail_end = str_replace('{default_email_address}',$default_email_address,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_icon_tel"){
				if($i == 1){
					$mail_end = str_replace('{default_icon_tel}',$default_icon_tel,$mail);
				}
				else{
					$mail_end = str_replace('{default_icon_tel}',$default_icon_tel,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_tel_number"){
				if($i == 1){
					$mail_end = str_replace('{default_tel_number}',$default_tel_number,$mail);
				}
				else{
					$mail_end = str_replace('{default_tel_number}',$default_tel_number,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_link_app_ios"){
				if($i == 1){
					$mail_end = str_replace('{default_link_app_ios}',$default_link_app_ios,$mail);
				}
				else{
					$mail_end = str_replace('{default_link_app_ios}',$default_link_app_ios,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_img_app_ios"){
				if($i == 1){
					$mail_end = str_replace('{default_img_app_ios}',$default_img_app_ios,$mail);
				}
				else{
					$mail_end = str_replace('{default_img_app_ios}',$default_img_app_ios,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_link_app_google"){
				if($i == 1){
					$mail_end = str_replace('{default_link_app_google}',$default_link_app_google,$mail);
				}
				else{
					$mail_end = str_replace('{default_link_app_google}',$default_link_app_google,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_img_app_google"){
				if($i == 1){
					$mail_end = str_replace('{default_img_app_google}',$default_img_app_google,$mail);
				}
				else{
					$mail_end = str_replace('{default_img_app_google}',$default_img_app_google,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_address_company"){
				if($i == 1){
					$mail_end = str_replace('{default_address_company}',$default_address_company,$mail);
				}
				else{
					$mail_end = str_replace('{default_address_company}',$default_address_company,$mail_end);
				}
				$i++;
			}
			if($detail_key_body == "default_tel_company"){
				if($i == 1){
					$mail_end = str_replace('{default_tel_company}',$default_tel_company,$mail);
				}
				else{
					$mail_end = str_replace('{default_tel_company}',$default_tel_company,$mail_end);
				}
				$i++;
			}
			//end case default
			if($detail_key_body == "email"){
				if($j == 1){
					$mail_end = str_replace('{email}',$value_email,$mail);
				}
				else{
					$mail_end = str_replace('{email}',$value_email,$mail_end);
				}
				$j++;
			}
			if($detail_key_body == "account_level"){
				if($j == 1){
					$mail_end = str_replace('{account_level}',$value_account_level,$mail);
				}
				else{
					$mail_end = str_replace('{account_level}',$value_account_level,$mail_end);
				}
				$j++;
			}
			// use when user ask export money
			if($detail_key_body == "point"){
				if($j == 1){
					$mail_end = str_replace('{point}',$value_point,$mail);
				}
				else{
					$mail_end = str_replace('{point}',$value_point,$mail_end);
				}
				$j++;
			}
			if($detail_key_body == "money_ask"){
				if($j == 1){
					$mail_end = str_replace('{money_ask}',$value_money_ask,$mail);
				}
				else{
					$mail_end = str_replace('{money_ask}',$value_money_ask,$mail_end);
				}
				$j++;
			}
			// end ask export money
			if($detail_key_body == "username"){
				if($j == 1){
					$mail_end = str_replace('{username}',$value_username,$mail);
				}
				else{
					$mail_end = str_replace('{username}',$value_username,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "password"){
				if($j == 1){
					$mail_end = str_replace('{password}',$value_password,$mail);
				}
				else{
					$mail_end = str_replace('{password}',$value_password,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "name"){
				if($j == 1){
					$mail_end = str_replace('{name}',$value_name,$mail);
				}
				else{
					$mail_end = str_replace('{name}',$value_name,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "link"){
				if($j == 1){
					$mail_end = str_replace('{link}',$value_link,$mail);
				}
				else{
					$mail_end = str_replace('{link}',$value_link,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "img_title"){
				if($j == 1){
					$mail_end = str_replace('{img_title}',$value_img_title,$mail);
				}
				else{
					$mail_end = str_replace('{img_title}',$value_img_title,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "img_width"){
				if($j == 1){
					$mail_end = str_replace('{img_width}',$value_img_width,$mail);
				}
				else{
					$mail_end = str_replace('{img_width}',$value_img_width,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "img_height"){
				if($j == 1){
					$mail_end = str_replace('{img_height}',$value_img_height,$mail);
				}
				else{
					$mail_end = str_replace('{img_height}',$value_img_height,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "option_sale"){
				if($j == 1){
					$mail_end = str_replace('{option_sale}',$value_option_sale,$mail);
				}
				else{
					$mail_end = str_replace('{option_sale}',$value_option_sale,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "type_export_image"){
				if($j == 1){
					$mail_end = str_replace('{type_export_image}',$value_type_export_image,$mail);
				}
				else{
					$mail_end = str_replace('{type_export_image}',$value_type_export_image,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "user_fullname"){
				if($j == 1){
					$mail_end = str_replace('{user_fullname}',$value_user_fullname,$mail);
				}
				else{
					$mail_end = str_replace('{user_fullname}',$value_user_fullname,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "email_buy_image"){
				if($j == 1){
					$mail_end = str_replace('{email_buy_image}',$value_email_buy_image,$mail);
				}
				else{
					$mail_end = str_replace('{email_buy_image}',$value_email_buy_image,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "person_buy_image"){
				if($j == 1){
					$mail_end = str_replace('{person_buy_image}',$value_person_buy_image,$mail);
				}
				else{
					$mail_end = str_replace('{person_buy_image}',$value_person_buy_image,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "link_order"){
				if($j == 1){
					$mail_end = str_replace('{link_order}',$value_link_order,$mail);
				}
				else{
					$mail_end = str_replace('{link_order}',$value_link_order,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "date_end_download"){
				if($j == 1){
					$mail_end = str_replace('{date_end_download}',$value_date_end_download,$mail);
				}
				else{
					$mail_end = str_replace('{date_end_download}',$value_date_end_download,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "title_com"){
				if($j == 1){
					$mail_end = str_replace('{title_com}',$value_title_com,$mail);
				}
				else{
					$mail_end = str_replace('{title_com}',$value_title_com,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "date_start_com"){
				if($j == 1){
					$mail_end = str_replace('{date_start_com}',$value_date_start_com,$mail);
				}
				else{
					$mail_end = str_replace('{date_start_com}',$value_date_start_com,$mail_end);
				}
				$j++;
			}
			if($detail_key_body == "point_package"){
				if($j == 1){
					$mail_end = str_replace('{point_package}',$value_point_package,$mail);
				}
				else{
					$mail_end = str_replace('{point_package}',$value_point_package,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "date_end_com"){
				if($j == 1){
					$mail_end = str_replace('{date_end_com}',$value_date_end_com,$mail);
				}
				else{
					$mail_end = str_replace('{date_end_com}',$value_date_end_com,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "point_img_com"){
				if($j == 1){
					$mail_end = str_replace('{point_img_com}',$value_point_img_com,$mail);
				}
				else{
					$mail_end = str_replace('{point_img_com}',$value_point_img_com,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "img_quantity_com"){
				if($j == 1){
					$mail_end = str_replace('{img_quantity_com}',$value_img_quantity_com,$mail);
				}
				else{
					$mail_end = str_replace('{img_quantity_com}',$value_img_quantity_com,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "link_url_com"){
				if($j == 1){
					$mail_end = str_replace('{link_url_com}',$value_link_url_com,$mail);
				}
				else{
					$mail_end = str_replace('{link_url_com}',$value_link_url_com,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "email_apply"){

				if($j == 1){

					$mail_end = str_replace('{email_apply}',$value_email_apply,$mail);

				}

				else{

					$mail_end = str_replace('{email_apply}',$value_email_apply,$mail_end);

				}

				$j++;

			}

			if($detail_key_body == "person_apply"){

				if($j == 1){

					$mail_end = str_replace('{person_apply}',$value_person_apply,$mail);

				}

				else{

					$mail_end = str_replace('{person_apply}',$value_person_apply,$mail_end);

				}

				$j++;

			}

			if($detail_key_body == "link_compe_check"){

				if($j == 1){

					$mail_end = str_replace('{link_compe_check}',$value_link_compe_check,$mail);

				}

				else{

					$mail_end = str_replace('{link_compe_check}',$value_link_compe_check,$mail_end);

				}

				$j++;

			}

			if($detail_key_body == "content_contact"){

				if($j == 1){

					$mail_end = str_replace('{content_contact}',$value_content_contact,$mail);

				}

				else{

					$mail_end = str_replace('{content_contact}',$value_content_contact,$mail_end);

				}

				$j++;

			}

			if($detail_key_body == "type_contact"){
				if($j == 1){
					$mail_end = str_replace('{type_contact}',$value_type_contact,$mail);
				}
				else{
					$mail_end = str_replace('{type_contact}',$value_type_contact,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "link_image_review"){
				if($j == 1){
					$mail_end = str_replace('{link_image_review}',$value_link_image_review,$mail);
				}
				else{
					$mail_end = str_replace('{link_image_review}',$value_link_image_review,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "email_post_competition"){
				if($j == 1){
					$mail_end = str_replace('{email_post_competition}',$value_email_post_competition,$mail);
				}
				else{
					$mail_end = str_replace('{email_post_competition}',$value_email_post_competition,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "num_day_extend"){
				if($j == 1){
					$mail_end = str_replace('{num_day_extend}',$value_num_day_extend,$mail);
				}
				else{
					$mail_end = str_replace('{num_day_extend}',$value_num_day_extend,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "link_extend"){
				if($j == 1){
					$mail_end = str_replace('{link_extend}',$value_link_extend,$mail);
				}
				else{
					$mail_end = str_replace('{link_extend}',$value_link_extend,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "date_extend"){
				if($j == 1){
					$mail_end = str_replace('{date_extend}',$value_date_extend,$mail);
				}
				else{
					$mail_end = str_replace('{date_extend}',$value_date_extend,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "list_image_choose"){
				if($j == 1){
					$mail_end = str_replace('{list_image_choose}',$value_list_image_choose,$mail);
				}
				else{
					$mail_end = str_replace('{list_image_choose}',$value_list_image_choose,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "email_get"){
				if($j == 1){
					$mail_end = str_replace('{email_get}',$value_email_get,$mail);
				}
				else{
					$mail_end = str_replace('{email_get}',$value_email_get,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "name_get"){
				if($j == 1){
					$mail_end = str_replace('{name_get}',$value_name_get,$mail);
				}
				else{
					$mail_end = str_replace('{name_get}',$value_name_get,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "email_report"){
				if($j == 1){
					$mail_end = str_replace('{email_report}',$value_email_report,$mail);
				}
				else{
					$mail_end = str_replace('{email_report}',$value_email_report,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "name_report"){
				if($j == 1){
					$mail_end = str_replace('{name_report}',$value_name_report,$mail);
				}
				else{
					$mail_end = str_replace('{name_report}',$value_name_report,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "content_report"){
				if($j == 1){
					$mail_end = str_replace('{content_report}',$value_content_report,$mail);
				}
				else{
					$mail_end = str_replace('{content_report}',$value_content_report,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "return_point"){
				if($j == 1){
					$mail_end = str_replace('{return_point}',$value_return_point,$mail);
				}
				else{
					$mail_end = str_replace('{return_point}',$value_return_point,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "link_image_choose"){
				if($j == 1){
					$mail_end = str_replace('{link_image_choose}',$value_link_image_choose,$mail);
				}
				else{
					$mail_end = str_replace('{link_image_choose}',$value_link_image_choose,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "date_start_choose"){
				if($j == 1){
					$mail_end = str_replace('{date_start_choose}',$value_date_start_choose,$mail);
				}
				else{
					$mail_end = str_replace('{date_start_choose}',$value_date_start_choose,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "date_end_choose"){
				if($j == 1){
					$mail_end = str_replace('{date_end_choose}',$value_date_end_choose,$mail);
				}
				else{
					$mail_end = str_replace('{date_end_choose}',$value_date_end_choose,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "package_name"){
				if($j == 1){
					$mail_end = str_replace('{package_name}',$value_package_name,$mail);
				}
				else{
					$mail_end = str_replace('{package_name}',$value_package_name,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "comment"){
				if($j == 1){
					$mail_end = str_replace('{comment}',$value_comment,$mail);
				}
				else{
					$mail_end = str_replace('{comment}',$value_comment,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "email_post_comment"){
				if($j == 1){
					$mail_end = str_replace('{email_post_comment}',$value_email_post_comment,$mail);
				}
				else{
					$mail_end = str_replace('{email_post_comment}',$value_email_post_comment,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "name_post_comment"){
				if($j == 1){
					$mail_end = str_replace('{name_post_comment}',$value_name_post_comment,$mail);
				}
				else{
					$mail_end = str_replace('{name_post_comment}',$value_name_post_comment,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "email_invite"){
				if($j == 1){
					$mail_end = str_replace('{email_invite}',$value_email_invite,$mail);
				}
				else{
					$mail_end = str_replace('{email_invite}',$value_email_invite,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "name_invite"){
				if($j == 1){
					$mail_end = str_replace('{name_invite}',$value_name_invite,$mail);
				}
				else{
					$mail_end = str_replace('{name_invite}',$value_name_invite,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "link_invite"){
				if($j == 1){
					$mail_end = str_replace('{link_invite}',$value_link_invite,$mail);
				}
				else{
					$mail_end = str_replace('{link_invite}',$value_link_invite,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "link_member"){
				if($j == 1){
					$mail_end = str_replace('{link_member}',$value_link_member,$mail);
				}
				else{
					$mail_end = str_replace('{link_member}',$value_link_member,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "link_accept"){
				if($j == 1){
					$mail_end = str_replace('{link_accept}',$value_link_accept,$mail);
				}
				else{
					$mail_end = str_replace('{link_accept}',$value_link_accept,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "link_not_accept"){
				if($j == 1){
					$mail_end = str_replace('{link_not_accept}',$value_link_not_accept,$mail);
				}
				else{
					$mail_end = str_replace('{link_not_accept}',$value_link_not_accept,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "key_1"){
				if($j == 1){
					$mail_end = str_replace('{key_1}',$value_key_1,$mail);
				}
				else{
					$mail_end = str_replace('{key_1}',$value_key_1,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "key_2"){
				if($j == 1){
					$mail_end = str_replace('{key_2}',$value_key_2,$mail);
				}
				else{
					$mail_end = str_replace('{key_2}',$value_key_2,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "key_3"){
				if($j == 1){
					$mail_end = str_replace('{key_3}',$value_key_3,$mail);
				}
				else{
					$mail_end = str_replace('{key_3}',$value_key_3,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "key_4"){
				if($j == 1){
					$mail_end = str_replace('{key_4}',$value_key_4,$mail);
				}
				else{
					$mail_end = str_replace('{key_4}',$value_key_4,$mail_end);
				}
				$j++;
			}

			if($detail_key_body == "key_5"){
				if($j == 1){
					$mail_end = str_replace('{key_5}',$value_key_5,$mail);
				}
				else{
					$mail_end = str_replace('{key_5}',$value_key_5,$mail_end);
				}
				$j++;
			}
			
			if($detail_key_body == "key_6"){
				if($j == 1){
					$mail_end = str_replace('{key_6}',$value_key_6,$mail);
				}
				else{
					$mail_end = str_replace('{key_6}',$value_key_6,$mail_end);
				}
				$j++;
			}

		}

		if($is_debug == 1){
			echo "email_subject_end = ".$email_subject_end."<br>";
			echo "mail_end = ".$mail_end."<br>";
			exit;
		}
		
		$setting = $this->get_setting();
		$admin_email = $setting['admin_email'];
		$list_admin_email = explode(",",$admin_email);

		if($file_attachment != ""){
			if($is_send_admin == 1){
				for($z=0; $z<count($list_admin_email); $z++){
					$this->sendMailHtmlAttachment($list_admin_email[$z],'',$email_subject_end,$mail_end,'',$file_attachment);//send admin
				}
				$this->sendMailHtmlAttachment($email_to,'',$email_subject_end,$mail_end,'',$file_attachment);
			}
			else if($is_send_admin == 2){
				for($z=0; $z<count($list_admin_email); $z++){
					$this->sendMailHtmlAttachment($list_admin_email[$z],'',$email_subject_end,$mail_end,'',$file_attachment);//send admin
				}
			}
			else{
				$this->sendMailHtmlAttachment($email_to,'',$email_subject_end,$mail_end,'',$file_attachment);//send user
			}
		}
		else{
			if($is_send_admin == 1){
				$this->sendHtmlMailSimple($email_to,'',$email_subject_end,$mail_end,'');
				for($z=0; $z<count($list_admin_email); $z++){
					$this->sendHtmlMailSimple($list_admin_email[$z],'',$email_subject_end,$mail_end,'');//send admin
				}
				
			}
			else if($is_send_admin == 2){
				for($z=0; $z<count($list_admin_email); $z++){
					$this->sendHtmlMailSimple($list_admin_email[$z],'',$email_subject_end,$mail_end,'');//send admin
				}
			}
			else{
				$this->sendHtmlMailSimple($email_to,'',$email_subject_end,$mail_end,'');
			}
		}

	}
	
	function sendHtmlMailSimple($to ='',$from ='',$subject='',$message='',$cc=''){
		
		$this->config->load('errors');
		$this->config->load('mail');

		require_once("qdmail.php");
		//include "qdsmtp.php";
		require_once("qdsmtp.php");
		
		$mail = new Qdmail();
		$mail -> smtp(true);
		$param = $this->config->item("mail");
		$mail -> smtpServer($param);
		$mail -> to($to); //宛先
		$mail -> subject($subject); //タイトル
		$mail -> html($message); //メッセージ本文
		//$mail -> attach('./testemail.php'); //添付ファイルつける
		$return_flag = $mail ->send(); //送信
		var_dump($param);
		var_dump($mail->errorStatment());
		echo "status $return_flag -> $to";
		if($return_flag != TRUE){
			$email_to	= $this->config->item("errors")["email"]["to"];
			$subject 	= 'Error email luxy.art: '.$to;
			$message 	= 'Error email luxy.art don\'t send: '.$to;
			$headers 	= 'From: verification@luxy.art' . "\r\n" .
					'Reply-To: verification@luxy.art' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
			
			//mail($email_to, $subject, $message, $headers);
			$mail -> smtpServer($param);
			$mail -> to($email_to); //宛先
			$mail -> subject($subject); //タイトル
			$mail -> html($message); //メッセージ本文
			//$mail -> attach('./testemail.php'); //添付ファイルつける
			$mail ->send(); //送信
			
		}
		exit();
		
		//return $return_flag;
		
	}
	
	function sendHtmlMailSimple_old($to ='',$from ='info@luxy.art',$subject='',$message='',$cc=''){
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$config['wordwrap'] = TRUE;
		$from ='info@luxy.art';
	
		$this->email->clear(TRUE);
		$this->email->initialize($config);
		$this->email->to($to);
		$this->email->from($from);
		$this->email->cc($cc);
		$this->email->subject($subject);
		$this->email->message($message);
		//exit();
		if(!$this->email->send()){
			echo $this->email->print_debugger();
		}
	}
	
	function sendMailHtmlAttachment($to ='',$from ='',$subject='',$message='',$cc='', $attachment=''){
	
		$this->config->load('mail');
		require_once("qdmail.php");
		//include "qdsmtp.php";
		require_once("qdsmtp.php");
		
		$mail = new Qdmail();
		$mail -> smtp(true);
		$param = $this->config->item("mail");
		$mail -> smtpServer($param);
		$mail -> to($to); //宛先
		$mail -> subject($subject); //タイトル
		$mail -> html($message); //メッセージ本文
		$mail -> attach($attachment); //添付ファイルつける
		$return_flag = $mail ->send(); //送信
	
	}

	function sendMailHtmlAttachment_old($to ='',$from ='info@luxy.art',$subject='',$message='',$cc='', $attachment=''){

		$this->load->library('email');
		$config['mailtype'] = 'html';
		$config['wordwrap'] = TRUE;
		$from ='info@luxy.art';

		$this->email->clear(TRUE);
		$this->email->initialize($config);
		$this->email->to($to);
		$this->email->from($from);
		$this->email->cc($cc);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->attach($attachment, 'inline');

		if(!$this->email->send()){
			echo $this->email->print_debugger();
		}

	}

	function sendMailHtmlMutiAttachment($to ='',$from ='info@luxy.art',$subject='',$message='',$cc='', $attachment=array()){

		$this->load->library('email');

		$config['mailtype'] = 'html';
		$config['wordwrap'] = TRUE;
		$from ='info@luxy.art';
		
		$this->email->clear(TRUE);
		$this->email->initialize($config);
		$this->email->to($to);
		$this->email->from($from);
		$this->email->cc($cc);
		$this->email->subject($subject);
		$this->email->message($message);

		foreach($attachment as $at){
			$this->email->attach($at, 'inline');
		}

		if(!$this->email->send()){
			echo $this->email->print_debugger();
		}

	}

	function getInbetweenStrings($start, $end, $str){
		$matches = array();
		$regex = "/$start([a-zA-Z0-9_]*)$end/";
		preg_match_all($regex, $str, $matches);
		return $matches[1];
	}
	function mail($email_to, $email_subject, $mail, $headers){
		$is_send = 1;
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where(array('user_email' => $email_to, 'user_status' => 1));
		$rs = $this->db->get();

		if(!empty($rs)){
			return mail($email_to, $email_subject, $mail, $headers);
		}
		else{
			return -1;
		}

	}
	function check_permission($type){
		
		$remember_me = $this->input->cookie("remember_me");
		
		if($remember_me == 1){
			
			$logged_in_cookie = $this->input->cookie("logged_in");
			$remember_me = $this->input->cookie("remember_me");
			$user_id = $this->input->cookie("user_id");
			$user_email = $this->input->cookie("user_email");
			$user_pass = $this->input->cookie("user_pass");
			$user_display_name = $this->input->cookie("user_display_name");
			$user_firstname = $this->input->cookie("user_firstname");
			$user_lastname = $this->input->cookie("user_lastname");
			$user_level = $this->input->cookie("user_level");
			
			$logged_in = array();
			$logged_in['remember_me'] = $remember_me;
			$logged_in['user_id'] = $user_id;
			$logged_in['user_email'] = $user_email;
			$logged_in['user_pass'] = $user_pass;
			$logged_in['user_display_name'] = $user_display_name;
			$logged_in['user_firstname'] = $user_firstname;
			$logged_in['user_lastname'] = $user_lastname;
			$logged_in['user_level'] = $user_level;
			
		}
		else{
			$logged_in = @$this->session->userdata['logged_in'];
		}
   		
		if(!empty($logged_in))
		{
			if($logged_in['user_level'] >= $type)
			{
   				return 1;
   			}
   			else{
   				return 0;
   			}
   		}
   		else{
   			return 0;
   		}
   	}

   	function get_logged_in(){
   		
   		$remember_me = $this->input->cookie("remember_me");
   		
   		if($remember_me == 1){
   			
   			$logged_in_cookie = $this->input->cookie("logged_in");
   			$remember_me = $this->input->cookie("remember_me");
   			$user_id = $this->input->cookie("user_id");
   			$user_email = $this->input->cookie("user_email");
   			$user_pass = $this->input->cookie("user_pass");
   			$user_display_name = $this->input->cookie("user_display_name");
   			$user_firstname = $this->input->cookie("user_firstname");
   			$user_lastname = $this->input->cookie("user_lastname");
   			$user_level = $this->input->cookie("user_level");
   				
   			$logged_in = array();
   			$logged_in['remember_me'] = $remember_me;
   			$logged_in['user_id'] = $user_id;
   			$logged_in['user_email'] = $user_email;
   			$logged_in['user_pass'] = $user_pass;
   			$logged_in['user_display_name'] = $user_display_name;
   			$logged_in['user_firstname'] = $user_firstname;
   			$logged_in['user_lastname'] = $user_lastname;
   			$logged_in['user_level'] = $user_level;
   			
   			return $logged_in;
   			
   		}
   		else{
   			return $logged_in = @$this->session->userdata['logged_in'];
   		}
   		
   	}
   	
   	function check_is_post_img($user_id){
   		
   		$listCountImg = array(1 => 0, 2 => 12, 3 => 50, 4 => 250, 5 => 25);
   		
   		$this->db->from('luxyart_tb_user');
   		$this->db->select('*');
   		$this->db->where('user_id', $user_id);
   		$rs = $this->db->get();
   		
   		if(!empty($rs)){
   			
   			$row = $rs->row();
   			$user_level = $row->user_level;
   			//$count_post_img = $row->count_post_img;
   			$count_allow_post_img = $listCountImg[$user_level];
   			
   			$this->db->from('luxyart_tb_product_img');
   			$this->db->select('*');
   			$this->db->where(array('user_id' => $user_id, 'img_check_status != ' => 4));
   			$rs = $this->db->get();
   			$count_post_img = $rs->num_rows();
   			
   			if($count_post_img < $count_allow_post_img){
   				return "1{luxyart}".$user_level;//allow
   			}
   			else{
   				return "0{luxyart}".$user_level;//don't allow
   			}
   			
   		}
   		else{
   			return -1;//login fail
   		}
   		
   	}
   	
   	function num_allow_post_image($user_id){
   		
   		$listCountImg = array(1 => 0, 2 => 12, 3 => 50, 4 => 250, 5 => 25);
   		
   		$this->db->from('luxyart_tb_user');
   		$this->db->select('*');
   		$this->db->where('user_id', $user_id);
   		$rs = $this->db->get();
   		 
   		if(!empty($rs)){
   		
   			$row = $rs->row();
   			$user_level = $row->user_level;
   			//$count_post_img = $row->count_post_img;
   			$count_allow_post_img = $listCountImg[$user_level];
   			
   			$this->db->from('luxyart_tb_product_img');
   			$this->db->select('*');
   			$this->db->where(array('user_id' => $user_id, 'img_check_status != ' => 4));
   			$rs = $this->db->get();
   			$count_post_img = $rs->num_rows();
   		
   			return ($count_allow_post_img-$count_post_img)."/".$count_allow_post_img;
   		
   		}
   		else{
   			return -1;//login fail
   		}
   		
   	}

   	/**
   	 * Get Manager Server
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-14
   	 */
   	function getManagerServer($id_server){

   		$this->db->from('luxyart_tb_manager_server');
   		$this->db->select('*');
   		$this->db->where('id_server', $id_server);
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}

   		return $result;

   	}

   	/**
   	 * Get Manager Server
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-28
   	 */
   	function getManagerServerUpload($lang_id){

   		$this->db->from('luxyart_tb_manager_server');
   		$this->db->select('*');
   		$this->db->where('img_count < img_store and lang_id = '.$lang_id);
   		$this->db->order_by('id_server', 'ASC');
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;

   	}

   	function getUser($user_id){

   		$this->db->from('luxyart_tb_user');
   		$this->db->select('*');
   		$this->db->where('user_id', $user_id);
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;

   	}

   	/**
   	 * Get User Level
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-14
   	 */
   	function getUserLevel($user_id){

   		$this->db->from('luxyart_tb_user');
   		$this->db->select('user_level');
   		$this->db->where('user_id', $user_id);
   		$rs = $this->db->get();
   		$result = $rs->row();

   		if(!empty($result)){
   			return $result->user_level;
   		}
   		else{
   			return 0;
   		}

   	}

   	/**
   	 * Get Product Color
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-14
   	 */
   	function getProductColor($id_color){

   		$this->db->from('luxyart_tb_product_color');
   		$this->db->select('*');
   		$this->db->where('id_color', $id_color);
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;

   	}

   	function getProductImgSize($id_img){

   		$this->db->from('luxyart_tb_product_img_size');
   		$this->db->select('*');
   		$this->db->where(array('id_img' => $id_img, 'status_img_size' => 0));
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->result();
   		}
   		return $result;

   	}

   	function getProductImgSizeShoppingCart($id_img){

   		$this->db->from('luxyart_tb_product_img_size as a');
   		$this->db->select("a.*, IF((SELECT COUNT(id_img_size) FROM luxyart_tb_product_img_order_detail WHERE a.id_img_size = id_img_size) > 0, 1, 0 ) as is_buy",FALSE);
   		$this->db->where(array('a.id_img' => $id_img, 'a.status_img_size' => 0));
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->result();
   		}
      // echo $this->db->last_query();
   		return $result;

   	}

   	function check_md5_file($md5_file){

   		$this->db->from('luxyart_tb_product_img');
   		$this->db->select('id_img');
   		$this->db->where(array('img_file_md5' => trim($md5_file)));
   		$rs = $this->db->get();
   		$result = $rs->row();

   		if(!empty($result)){
   			return 1;
   		}
   		else{
   			return 0;
   		}

   	}

   	function check_md5_file_edit($md5_file, $id_img){

   		$this->db->from('luxyart_tb_product_img');
   		$this->db->select('id_img');
   		$this->db->where(array('img_file_md5' => trim($md5_file), 'id_img != ' => $id_img));
   		$rs = $this->db->get();
   		$result = $rs->row();

   		if(!empty($result)){
   			return 1;
   		}
   		else{
   			return 0;
   		}

   	}

   	function byte_convert_to_mb($size) {

   		return $size/1048576;

   	}

   	function base64_to_jpeg( $base64_string, $output_file ) {

   		$ifp = fopen( $output_file, "wb" );
   		fwrite( $ifp, base64_decode( $base64_string) );
   		fclose( $ifp );
   		return( $output_file );

   	}

   	function get_dpi($filename){

   		// open the file and read first 20 bytes.
   		$a = fopen($filename,'r');
   		$string = fread($a,20);
   		fclose($a);

   		// get the value of byte 14th up to 18th
   		$data = bin2hex(substr($string,14,4));
   		$x = substr($data,0,4);
   		$y = substr($data,4,4);
   		return array(hexdec($x),hexdec($y));

   	}

   	function getDPIImageMagick($filename){
   		$cmd = 'identify -quiet -format "%x" '.$filename;
   		@exec(escapeshellcmd($cmd), $data);
   		if($data && is_array($data)){
   			$data = explode(' ', $data[0]);

   			if($data[1] == 'PixelsPerInch'){
   				return $data[0];
   			}elseif($data[1] == 'PixelsPerCentimeter'){
   				$x = ceil($data[0] * 2.54);
   				return $x;
   			}elseif($data[1] == 'Undefined'){
   				return $data[0];
   			}
   		}
   		return 72;
   	}

   	function resize_image($file, $w, $h, $crop=FALSE) {
   		list($width, $height) = getimagesize($file);
   		$r = $width / $height;
   		if ($crop) {
   			if ($width > $height) {
   				$width = ceil($width-($width*abs($r-$w/$h)));
   			} else {
   				$height = ceil($height-($height*abs($r-$w/$h)));
   			}
   			$newwidth = $w;
   			$newheight = $h;
   		} else {
   			if ($w/$h > $r) {
   				$newwidth = $h*$r;
   				$newheight = $h;
   			} else {
   				$newheight = $w/$r;
   				$newwidth = $w;
   			}
   		}
   		$src = imagecreatefromjpeg($file);
   		$dst = imagecreatetruecolor($newwidth, $newheight);
   		imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

   		return $dst;
   	}

   	function resize($newWidth, $targetFile, $originalFile) {

   		$info = getimagesize($originalFile);
   		$mime = $info['mime'];

   		if($mime != ""){

	   		switch ($mime) {
	   			case 'image/jpeg':
	   				$image_create_func = 'imagecreatefromjpeg';
	   				$image_save_func = 'imagejpeg';
	   				$new_image_ext = 'jpg';
	   				break;

	   			case 'image/png':
	   				$image_create_func = 'imagecreatefrompng';
	   				$image_save_func = 'imagepng';
	   				$new_image_ext = 'png';
	   				break;

	   			case 'image/gif':
	   				$image_create_func = 'imagecreatefromgif';
	   				$image_save_func = 'imagegif';
	   				$new_image_ext = 'gif';
	   				break;

	   			default:
	   				throw new Exception('Unknown image type.');
	   		}

	   		$img = $image_create_func($originalFile);
	   		list($width, $height) = getimagesize($originalFile);

	   		$newHeight = ($height / $width) * $newWidth;
	   		$tmp = imagecreatetruecolor($newWidth, $newHeight);
	   		imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

	   		if (file_exists($targetFile)) {
	   			unlink($targetFile);
	   		}
	   		$image_save_func($tmp, "$targetFile.$new_image_ext");

   		}
   	}
   	
   	function resize_width($newWidth, $targetFile, $originalFile) {
   		
		$info = getimagesize($originalFile);
		$mime = $info['mime'];
	
		switch ($mime) {
				case 'image/jpeg':
						$image_create_func = 'imagecreatefromjpeg';
						$image_save_func = 'imagejpeg';
						$new_image_ext = 'jpg';
						break;
	
				case 'image/png':
						$image_create_func = 'imagecreatefrompng';
						$image_save_func = 'imagepng';
						$new_image_ext = 'png';
						break;
	
				case 'image/gif':
						$image_create_func = 'imagecreatefromgif';
						$image_save_func = 'imagegif';
						$new_image_ext = 'gif';
						break;
	
				default: 
						throw new Exception('Unknown image type.');
		}
	
		$img = $image_create_func($originalFile);
		list($width, $height) = getimagesize($originalFile);
	
		$newHeight = ($height / $width) * $newWidth;
		$tmp = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
	
		if (file_exists($targetFile)) {
				unlink($targetFile);
		}
		$image_save_func($tmp, "$targetFile.$new_image_ext");
   		
   	}
   	
   	function resize_height($newHeight, $targetFile, $originalFile) {
   	
   		$info = getimagesize($originalFile);
   	
   		$mime = $info['mime'];
   		
   		if($mime != ""){
   	
	   		switch ($mime) {
	   			case 'image/jpeg':
	   				$image_create_func = 'imagecreatefromjpeg';
	   				$image_save_func = 'imagejpeg';
	   				$new_image_ext = 'jpg';
	   				break;
	   	
	   			case 'image/png':
	   				$image_create_func = 'imagecreatefrompng';
	   				$image_save_func = 'imagepng';
	   				$new_image_ext = 'png';
	   				break;
	   	
	   			case 'image/gif':
	   				$image_create_func = 'imagecreatefromgif';
	   				$image_save_func = 'imagegif';
	   				$new_image_ext = 'gif';
	   				break;
	   	
	   			default:
	   				throw new Exception('Unknown image type.');
	   		}
	   	
	   		$img = $image_create_func($originalFile);
	   		list($width, $height) = getimagesize($originalFile);
	   	
	   		$newWidth = ($width / $height) * $newHeight;
	   		$tmp = imagecreatetruecolor($newWidth, $newHeight);
	   		imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
	   	
	   		if (file_exists($targetFile)) {
	   			unlink($targetFile);
	   		}
	   		$image_save_func($tmp, "$targetFile.$new_image_ext");
	   		
   		}
   		
   	}
   	
   	function fix_rotate_image($target, $newcopy){
   		
   		$exif = read_exif_data($target);
   		$exif_orient = isset($exif['Orientation'])?$exif['Orientation']:0;
   			
   		$rotateImage = 0;
   		//We convert the exif rotation to degrees for further use
   		if (6 == $exif_orient) {
   			$rotateImage = 90;
   			$imageOrientation = 1;
   		} elseif (3 == $exif_orient) {
   			$rotateImage = 180;
   			$imageOrientation = 1;
   		} elseif (8 == $exif_orient) {
   			$rotateImage = 270;
   			$imageOrientation = 1;
   		}
   		//if the image is rotated
   			
   		//if the image is rotated
   		if ($rotateImage) {
   			//WordPress 3.5+ have started using Imagick, if it is available since there is a noticeable difference in quality
   			//Why spoil beautiful images by rotating them with GD, if the user has Imagick
   			if (class_exists('Imagick')) {
   				$imagick = new Imagick();
   				$imagick->readImage($newcopy);
   				$imagick->rotateImage(new ImagickPixel(), $rotateImage);
   				$imagick->setImageOrientation($imageOrientation);
   				$imagick->writeImage($newcopy);
   				$imagick->clear();
   				$imagick->destroy();
   			}
   		}		
   		
   	}

   	function watermark_image($target, $wtrmrk_file, $newcopy, $position) {

   		$watermark = imagecreatefrompng($wtrmrk_file);
   		imagealphablending($watermark, false);
   		imagesavealpha($watermark, true);
   		$img = imagecreatefromjpeg($target);
   		$img_w = imagesx($img);
   		$img_h = imagesy($img);
   		$wtrmrk_w = imagesx($watermark);
   		$wtrmrk_h = imagesy($watermark);

   		if($position == 0){

   			$dst_x = ($img_w / 2) - ($wtrmrk_w / 2); // For centering the watermark on any image
   			$dst_y = ($img_h / 2) - ($wtrmrk_h / 2); // For centering the watermark on any image

   		}
   		else if($position == 1){

   			$dst_x = 0; // For centering the watermark on any image
   			$dst_y = 0; // For centering the watermark on any image

   		}
   		else if($position == 2){

   			$dst_x = ($img_w) - ($wtrmrk_w); // For centering the watermark on any image
   			$dst_y = 0; // For centering the watermark on any image

   		}
   		else if($position == 3){

   			$dst_x = 0; // For centering the watermark on any image
   			$dst_y = ($img_h) - ($wtrmrk_h); // For centering the watermark on any image

   		}
   		else if($position == 4){

   			$dst_x = ($img_w) - ($wtrmrk_w); // For centering the watermark on any image
   			$dst_y = ($img_h) - ($wtrmrk_h); // For centering the watermark on any image

   		}

   		imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
   		imagejpeg($img, $newcopy, 100);
   		imagedestroy($img);
   		imagedestroy($watermark);
   	}
   	
   	function pixelated_image($target, $newcopy) {
   		
   		$img = imagecreatefromjpeg($target);
   		$width = imagesx($img);
   		$height = imagesy($img);
   		
   		# Create 5% version of the original image:
   		$newImg = imagecreatetruecolor($width,$height);
   		imagecopyresized($newImg,$img,0,0,0,0,round($width / 5),round($height / 5),$width,$height);
   		
   		# Create 100% version ... blow it back up to it's initial size:
   		$newImg2 = imagecreatetruecolor($width,$height);
   		imagecopyresized($newImg2,$newImg,0,0,0,0,$width,$height,round($width / 5),round($height / 5));
   		
   		# No need for a jpeg here :-)
   		//imagepng($newImg2,"image/image_pixelated.png");
   		imagejpeg($newImg2, $newcopy, 100);
   		
   		imagedestroy($newImg2);
   		imagedestroy($img);
   	}
   	
   	function convert_to_pixel($image, $newcopy, $size) {
   		
   		$im = imagecreatefromjpeg($image);
   		
   		$size = (int)$size;
   		$sizeX = imagesx($im);
   		$sizeY = imagesy($im);
   		if($sizeX < 3 && $sizeX < 3) { // or you can choose any size you want
   			return;
   		}
   		for($i = 0;$i < $sizeX; $i += $size) {
   			for($j = 0;$j < $sizeY; $j += $size) {
   				$colors = Array('alpha' => 0, 'red' => 0, 'green' => 0, 'blue' => 0, 'total' => 0);
   				for($k = 0; $k < $size; ++$k) {
   					for($l = 0; $l < $size; ++$l) {
   						if($i + $k >= $sizeX || $j + $l >= $sizeY) {
   							continue;
   						}
   						$color = imagecolorat($im, $i + $k, $j + $l);
   						imagecolordeallocate($im, $color);
   						$colors['alpha'] += ($color >> 24) & 0xFF;
   						$colors['red'] += ($color >> 16) & 0xFF;
   						$colors['green'] += ($color >> 8) & 0xFF;
   						$colors['blue'] += $color & 0xFF;
   						++$colors['total'];
   					}
   				}
   				$color = imagecolorallocatealpha($im, $colors['red'] / $colors['total'], $colors['green'] / $colors['total'], $colors['blue'] / $colors['total'], $colors['alpha'] / $colors['total']);
   				imagefilledrectangle($im, $i, $j, ($i + $size - 1), ($j + $size - 1), $color);
   			}
   		}
   		//header('Content-type: image/jpg');
   		//imagejpeg($im, '', 100);
   		imagejpeg($im, $newcopy, 100);
   	}
   	
   	function image_fix_orientation(&$image, $filename) {
   		$exif = exif_read_data($filename);
   	
   		if (!empty($exif['Orientation'])) {
   			switch ($exif['Orientation']) {
   				case 3:
   					$image = imagerotate($image, 180, 0);
   					break;
   	
   				case 6:
   					$image = imagerotate($image, -90, 0);
   					break;
   	
   				case 8:
   					$image = imagerotate($image, 90, 0);
   					break;
   			}
   		}
   	}
   	
   	function repair_fix_orientation(&$image, $filename) {
   		
   		$imagick = new \Imagick();
   		$imagick->readImage('test.jpg');
   		$format = strtolower($imagick->getImageFormat());
   		
   		if ($format === 'jpeg') {
   			$orientation = $imagick->getImageOrientation();
   			$isRotated = false;
   			if ($orientation === \Imagick::ORIENTATION_RIGHTTOP) {
   				$imagick->rotateImage('none', 90);
   				$isRotated = true;
   			} elseif ($orientation === \Imagick::ORIENTATION_BOTTOMRIGHT) {
   				$imagick->rotateImage('none', 180);
   				$isRotated = true;
   			} elseif ($orientation === \Imagick::ORIENTATION_LEFTBOTTOM) {
   				$imagick->rotateImage('none', 270);
   				$isRotated = true;
   			}
   			if ($isRotated) {
   				$imagick->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
   			}
   		}
   		
   	}

   	function main_color_image($file){

   		$image=imagecreatefromjpeg($file);
   		$thumb=imagecreatetruecolor(1,1); imagecopyresampled($thumb,$image,0,0,0,0,1,1,imagesx($image),imagesy($image));
   		$mainColor=strtoupper(dechex(imagecolorat($thumb,0,0)));
   		return $mainColor;

   	}
   	
   	function get_average_colour($filename, $as_hex_string = true) {
   		try {
   			// Read image file with Image Magick
   			$image = new Imagick($filename);
   			// Scale down to 1x1 pixel to make Imagick do the average
   			$image->scaleimage(1, 1);
   			/** @var ImagickPixel $pixel */
   			if(!$pixels = $image->getimagehistogram()) {
   				return null;
   			}
   		} catch(ImagickException $e) {
   			// Image Magick Error!
   			return null;
   		} catch(Exception $e) {
   			// Unknown Error!
   			return null;
   		}
   		$pixel = reset($pixels);
   		$rgb = $pixel->getcolor();
   		if($as_hex_string) {
   			return sprintf('%02X%02X%02X', $rgb['r'], $rgb['g'], $rgb['b']);
   		}
   		return $rgb;
   	}
   	
   	function detectColors($image, $num, $level = 5) {
   		$level = (int)$level;
   		$palette = array();
   		$size = getimagesize($image);
   		if(!$size) {
   			return FALSE;
   		}
   		switch($size['mime']) {
   			case 'image/jpeg':
   				$img = imagecreatefromjpeg($image);
   				break;
   			case 'image/png':
   				$img = imagecreatefrompng($image);
   				break;
   			case 'image/gif':
   				$img = imagecreatefromgif($image);
   				break;
   			default:
   				return FALSE;
   		}
   		if(!$img) {
   			return FALSE;
   		}
   		for($i = 0; $i < $size[0]; $i += $level) {
   			for($j = 0; $j < $size[1]; $j += $level) {
   				$thisColor = imagecolorat($img, $i, $j);
   				$rgb = imagecolorsforindex($img, $thisColor);
   				$color = sprintf('%02X%02X%02X', (round(round(($rgb['red'] / 0x33)) * 0x33)), round(round(($rgb['green'] / 0x33)) * 0x33), round(round(($rgb['blue'] / 0x33)) * 0x33));
   				$palette[$color] = isset($palette[$color]) ? ++$palette[$color] : 1;
   			}
   		}
   		arsort($palette);
   		return array_slice(array_keys($palette), 0, $num);
   	}
   	
   	function getNearestColor($givenColor,
   			$palette = array(
   					'1' 	=> 'ff0000',
   					'2' 	=> 'ff7800',
   					'3' 	=> 'ffda0b',
   					'4' 	=> '05dc05',
   					'5' 	=> '00d7ea',
   					'6' 	=> '2f2ff2',
   					'7' 	=> 'cf1af1',
   					'8' 	=> 'ff72d6',
   					'9' 	=> 'ffffff',
   					'10' 	=> 'e0e0e0',
   					'11' 	=> '000000',
   					'12' 	=> 'b15713',
   			)
   	){
   				if(!function_exists('rgb2lab')){
   					function rgb2lab($rgb) {
   						$eps = 216/24389; $k = 24389/27;
   						// reference white D50
   						$xr = 0.964221; $yr = 1.0; $zr = 0.825211;
   						// reference white D65
   						#$xr = 0.95047; $yr = 1.0; $zr = 1.08883;
   	
   						// RGB to XYZ
   						$rgb[0] = $rgb[0]/255; //R 0..1
   						$rgb[1] = $rgb[1]/255; //G 0..1
   						$rgb[2] = $rgb[2]/255; //B 0..1
   	
   						// assuming sRGB (D65)
   						$rgb[0] = ($rgb[0] <= 0.04045)?($rgb[0]/12.92):pow(($rgb[0]+0.055)/1.055,2.4);
   						$rgb[1] = ($rgb[1] <= 0.04045)?($rgb[1]/12.92):pow(($rgb[1]+0.055)/1.055,2.4);
   						$rgb[2] = ($rgb[2] <= 0.04045)?($rgb[2]/12.92):pow(($rgb[2]+0.055)/1.055,2.4);
   	
   						// sRGB D50
   						$x =  0.4360747*$rgb[0] + 0.3850649*$rgb[1] + 0.1430804*$rgb[2];
   						$y =  0.2225045*$rgb[0] + 0.7168786*$rgb[1] + 0.0606169*$rgb[2];
   						$z =  0.0139322*$rgb[0] + 0.0971045*$rgb[1] + 0.7141733*$rgb[2];
   						// sRGB D65
   						/*$x =  0.412453*$rgb[0] + 0.357580*$rgb[1] + 0.180423*$rgb[2];
   						 $y =  0.212671*$rgb[0] + 0.715160*$rgb[1] + 0.072169*$rgb[2];
   						$z =  0.019334*$rgb[0] + 0.119193*$rgb[1] + 0.950227*$rgb[2];*/
   	
   						// XYZ to Lab
   						$xr = $x/$xr; $yr = $y/$yr; $zr = $z/$zr;
   	
   						$fx = ($xr > $eps)?pow($xr, 1/3):($fx = ($k * $xr + 16) / 116); $fy = ($yr > $eps)?pow($yr, 1/3):($fy = ($k * $yr + 16) / 116); $fz = ($zr > $eps)?pow($zr, 1/3):($fz = ($k * $zr + 16) / 116);
   	
   						$lab = array();
   						$lab[] = round(( 116 * $fy ) - 16); $lab[] = round(500*($fx-$fy)); $lab[] = round(200*($fy-$fz));
   						return $lab;
   					} // function rgb2lab
   				}
   				if(!function_exists('deltaE')){
   					function deltaE($lab1, $lab2){
   						// CMC 1:1
   						$l = 1; $c = 1;
   	
   						$c1 = sqrt($lab1[1]*$lab1[1]+$lab1[2]*$lab1[2]); $c2 = sqrt($lab2[1]*$lab2[1]+$lab2[2]*$lab2[2]);
   	
   						$h1 = (((180000000/M_PI) * atan2($lab1[1],$lab1[2]) + 360000000) % 360000000)/1000000;
   	
   						$t = (164 <= $h1 AND $h1 <= 345)?(0.56 + abs(0.2 * cos($h1+168))):(0.36 + abs(0.4 * cos($h1+35)));
   						$f = sqrt(pow($c1,4)/(pow($c1,4) + 1900));
   	
   						$sl = ($lab1[0] < 16)?(0.511):((0.040975*$lab1[0])/(1 + 0.01765*$lab1[0]));
   						$sc = (0.0638 * $c1)/(1 + 0.0131 * $c1) + 0.638;
   						$sh = $sc * ($f * $t + 1 -$f);
   	
   						return sqrt( pow(($lab1[0]-$lab2[0])/($l * $sl),2) + pow(($c1-$c2)/($c * $sc),2) + pow(sqrt(($lab1[1]-$lab2[1])*($lab1[1]-$lab2[1]) + ($lab1[2]-$lab2[2])*($lab1[2]-$lab2[2]) + ($c1-$c2)*($c1-$c2))/$sh,2) );
   					} // function deltaE
   				}
   				if(!function_exists('colorDistance')){
   					function colorDistance($lab1,$lab2){
   						return sqrt(($lab1[0]-$lab2[0])*($lab1[0]-$lab2[0])+($lab1[1]-$lab2[1])*($lab1[1]-$lab2[1])+($lab1[2]-$lab2[2])*($lab1[2]-$lab2[2]));
   					}
   				}
   				if(!function_exists('str2rgb')){
   					function str2rgb($str){
   						$str = preg_replace('~[^0-9a-f]~','',$str);
   						$rgb = str_split($str,2);
   						for($i=0;$i<3;$i++)
   							$rgb[$i] = intval($rgb[$i],16);
   	
   						return $rgb;
   					} // function str2rgb
   				}
   	
   				// split into RGB, if not already done
   				$givenColorRGB = is_array($givenColor)?$givenColor:str2rgb($givenColor);
   				$min = 0xffff;
   				$return = NULL;
   	
   				foreach($palette as $key => $color){
   					// split into RGB
   					$color = is_array($color)?$color:str2rgb($color);
   					// deltaE
   					#if($min >= ($deltaE = deltaE(rgb2lab($color),rgb2lab($givenColorRGB))))
   					// euclidean distance
   					if($min >= ($deltaE = colorDistance(rgb2lab($color),rgb2lab($givenColorRGB))))
   					{
   						$min = $deltaE;
   						$return = $key;
   					}
   				}
   	
   				return $return;
   			}
   			
   	function compareColor($src){
   			
   		$arrColorId = array();
   			
   		$palette =  $this->detectColors($src, 6, 1);
   			
   		foreach($palette as $main_color) {
   			$id_color = $this->getNearestColor(strtolower($main_color));
   			array_push($arrColorId, $id_color);
   		}
   					
   		$arrColorId = array_unique($arrColorId);
   		return implode(",",$arrColorId);
   			
   	}

   	function getRootCate($id, $lang){

   		$this->db->from('luxyart_tb_product_category');
   		$this->db->select('*');
   		$this->db->where(array('product_category_id' => $id, 'level_cate' => 0, 'lang_id' => $lang));
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   			return $result;
   		}
   		else{
   			return $result;
   		}

   	}

	function getParentCate($id, $lang){

   		$this->db->from('luxyart_tb_product_category');
   		$this->db->select('*');
   		$this->db->where(array('product_category_id' => $id, 'level_cate' => 1, 'lang_id' => $lang));
   		$rs = $this->db->get();
   		$result = $rs->row();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;

   	}

   	function getSubCate($id, $lang){

   		$this->db->from('luxyart_tb_product_category');
   		$this->db->select('*');
   		$this->db->where(array('product_category_id' => $id, 'level_cate' => 2, 'lang_id' => $lang));
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   			return $result;
   		}
   		else{
   			return $result;
   		}

   	}

   	function incrementalHash($size){

   		$seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
   		shuffle($seed);
   		$rand = '';

   		foreach(array_rand($seed, $size) as $k){
   			$rand .= $seed[$k];
   		}

   		return $rand;
   	}
   	
   	function randomString($length = 1){
   		$str = "";
   		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
   		//$characters = array_merge(range('A','Z'));
   		$max = count($characters) - 1;
   		for ($i = 0; $i < $length; $i++)
   		{
   			$rand = mt_rand(0, $max);
   			$str .= $characters[$rand];
   		}
   		return $str;
   	}

   	function strToHex($string){
   		$hex='';
   		for ($i=0; $i < strlen($string); $i++){
   			$hex .= dechex(ord($string[$i]));
   		}
   		return $hex;
   	}


   	function hexToStr($hex){
   		$string='';
   		for ($i=0; $i < strlen($hex)-1; $i+=2){
   			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
   		}
   		return $string;
   	}

   	function get_code_download($id){

   		$hex = strtoupper(dechex($id));
   		$rand = $this->incrementalHash(15-count($hex));
   		return $rand.$hex;

   	}

   	function get_image_name(){

   		return base64_encode(rand(1000, 9999).time().rand(1000, 9999));

   	}

   	function get_user_fullname($lastname, $firstname, $lang_id){

   		return $lastname." ".$firstname;

   	}
   	
   	function get_fullname($user_id, $lang_id){
   		
   		$this->db->from('luxyart_tb_user');
   		$this->db->select('*');
   		$this->db->where('user_id', $user_id);
   		$rs = $this->db->get();
   		
   		if(!empty($rs)){
   			
   			$row = $rs->row();
   			$fullname = $row->display_name;
   			
   		}
   		else{
   			$fullname = "";
   		}
   		
   		return $fullname;
   		
   	}

   	function array_sum_values(array $input, $key) {

   		$sum = 0;
   		array_walk($input, function($item, $index, $params) {
   			if (!empty($item[$params[1]]))
   				$params[0] += $item[$params[1]];
   		}, array(&$sum, $key)
   		);

   		return $sum;
   	}

   	// calculates the luminosity of an given RGB color
   	// the color code must be in the format of RRGGBB
   	// the luminosity equations are from the WCAG 2 requirements
   	// http://www.w3.org/TR/WCAG20/#relativeluminancedef

   	function calculateLuminosity($color) {

   		$r = hexdec(substr($color, 0, 2)) / 255; // red value
   		$g = hexdec(substr($color, 2, 2)) / 255; // green value
   		$b = hexdec(substr($color, 4, 2)) / 255; // blue value
   		if ($r <= 0.03928) {
   			$r = $r / 12.92;
   		} else {
   			$r = pow((($r + 0.055) / 1.055), 2.4);
   		}

   		if ($g <= 0.03928) {
   			$g = $g / 12.92;
   		} else {
   			$g = pow((($g + 0.055) / 1.055), 2.4);
   		}

   		if ($b <= 0.03928) {
   			$b = $b / 12.92;
   		} else {
   			$b = pow((($b + 0.055) / 1.055), 2.4);
   		}

   		$luminosity = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
   		return $luminosity;
   	}

   	// calculates the luminosity ratio of two colors
   	// the luminosity ratio equations are from the WCAG 2 requirements
   	// http://www.w3.org/TR/WCAG20/#contrast-ratiodef

   	function calculateLuminosityRatio($color1, $color2) {
   		$l1 = $this->calculateLuminosity($color1);
   		$l2 = $this->calculateLuminosity($color2);

   		if ($l1 > $l2) {
   			$ratio = (($l1 + 0.05) / ($l2 + 0.05));
   		} else {
   			$ratio = (($l2 + 0.05) / ($l1 + 0.05));
   		}
   		return $ratio;
   	}

   	// returns an array with the results of the color contrast analysis
   	// it returns akey for each level (AA and AAA, both for normal and large or bold text)
   	// it also returns the calculated contrast ratio
   	// the ratio levels are from the WCAG 2 requirements
   	// http://www.w3.org/TR/WCAG20/#visual-audio-contrast (1.4.3)
   	// http://www.w3.org/TR/WCAG20/#larger-scaledef

   	function evaluateColorContrast($color1, $color2) {
   		$ratio = $this->calculateLuminosityRatio($color1, $color2);

   		$colorEvaluation["levelAANormal"] = ($ratio >= 4.5 ? 'pass' : 'fail');
   		$colorEvaluation["levelAALarge"] = ($ratio >= 3 ? 'pass' : 'fail');
   		$colorEvaluation["levelAAMediumBold"] = ($ratio >= 3 ? 'pass' : 'fail');
   		$colorEvaluation["levelAAANormal"] = ($ratio >= 7 ? 'pass' : 'fail');
   		$colorEvaluation["levelAAALarge"] = ($ratio >= 4.5 ? 'pass' : 'fail');
   		$colorEvaluation["levelAAAMediumBold"] = ($ratio >= 4.5 ? 'pass' : 'fail');
   		$colorEvaluation["ratio"] = $ratio;

   		return $colorEvaluation;
   	}

   	function get_setting(){

   		$this->db->from('luxyart_tb_settings');
   		$this->db->select('*');
   		$this->db->where(array('setting_id > ' => 0));
   		$rs = $this->db->get();

   		$result = array();
   		if(!empty($rs)){
   			$resultSetting = $rs->result();
   			foreach($resultSetting as $_resultSetting){
   				$result[$_resultSetting->name] = $_resultSetting->value;
   			}
   		}

   		return $result;

   	}

   	function get_inbox_templates_id($name){

   		$this->db->from('luxyart_tb_inbox_templates');

   		$this->db->select('inbox_templates_id');

   		$this->db->where(array('name' => $name));

   		$rs = $this->db->get();

   		$row = $rs->row();

   		return $row->inbox_templates_id;

   	}

   	/**
   	 * Convert Date //Convert 09/05/2013 => 2013-09-05
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2013-09-24
   	 */

   	function convert_date($format_from, $format_to, $str){

   		switch ($format_from){
   			case "mm/dd/yyyy":
   				$d = substr($str, 3, 2);
   				$m = substr($str, 0, 2);
   				$y = substr($str, 6, 4);
   				break;
   			case "yyyy-mm-dd":
   				$d = substr($str, 8, 2);
   				$m = substr($str, 5, 2);
   				$y = substr($str, 0, 4);
   				break;
   			case "yyyy/mm/dd":
   				$d = substr($str, 8, 2);
   				$m = substr($str, 5, 2);
   				$y = substr($str, 0, 4);
   				break;
   			case "dd/mm/yyyy":
   				$d = substr($str, 0, 2);
   				$m = substr($str, 3, 2);
   				$y = substr($str, 6, 4);
   				break;
   		}
   		switch ($format_to){
   			case "yyyy-mm-dd":
   				$value = $y."-".$m."-".$d;
   				break;
   			case "yyyy/mm/dd":
   				$value = $y."/".$m."/".$d;
   				break;
   			case "mm/dd/yyyy":
   				$value = $m."/".$d."/".$y;
   				break;
   			case "dd/mm/yyyy":
   				$value = $d."/".$m."/".$y;
   				break;
   		}
   		return $value;
   	}

   	function encrypt_base64_dot($id, $user_id){

   		return base64_encode($id.".".$user_id);

   	}

   	function decrypt_base64_dot($code){

   		$decode = base64_decode($code);
   		return explode(".", $decode);

   	}

   	function encrypt_id($id){

   		$code = 2019*$id;
   		$code = dechex($code);
   		return $code;

   	}

   	function decrypt_id($code){

   		$id = hexdec($code);
   		$id = $id/2019;
   		return $id;

   	}
   	
   	function encrypt_id_2018($id){
   	
   		$code = 2018*$id;
   		$code = dechex($code);
   		return $code;
   	
   	}
   	
   	function decrypt_id_2018($code){
   	
   		$id = hexdec($code);
   		$id = $id/2018;
   		return $id;
   	
   	}

   	function remove_array_item($array, $item) {

   		$index = array_search($item, $array);
   		if($index !== false ) {
   			unset( $array[$index] );
   		}
   		return $array;
   	}

   	function get_compare_color($main_color){

   		$this->db->from('luxyart_tb_product_color');
   		$this->db->select('*');
   		$this->db->where(array('lang_id' => 1, 'css_color_style !=' => ""));
   		$rs = $this->db->get();
   		$result = $rs->result();

   		$arr_compare_color = array();
   		$arr_value_color = array();

   		foreach($result as $_result){

   			$arr_value_color[$_result->product_color_id] = $_result->css_color_style;
   			$arr_compare_color[$_result->product_color_id] = $this->calculateLuminosityRatio($_result->css_color_style, $main_color);
        // $arr_compare_color[$_result->product_color_id] = $this->calculateLuminosityRatio($main_color, $_result->css_color_style);
   		 //  echo $this->calculateLuminosityRatio($_result->css_color_style, $main_color)." - ";
   		}

   		$min_compare_color = min($arr_compare_color);
   		$value_color_choose = array_search($min_compare_color,$arr_compare_color);
      	//echo 'CHoose:'.$value_color_choose."<br>";
   		$this->db->from('luxyart_tb_product_color');
   		$this->db->select('*');
   		$this->db->where(array('lang_id' => 1, 'css_color_style' => $arr_value_color[$value_color_choose]));
   		$rs = $this->db->get();
   		$row = $rs->row();

   		return $row->product_color_id;

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
   		//exit;
   	}

	function cvf_convert_object_to_array($data) {

	    if (is_object($data)) {
	        $data = get_object_vars($data);
	    }

	    if (is_array($data)) {
	        return array_map(__FUNCTION__, $data);
	    }
	    else {
	        return $data;
	    }
	}
	
	function array_delete($del_val, $array) {
		if(is_array($del_val)) {
			foreach ($del_val as $del_key => $del_value) {
				foreach ($array as $key => $value){
					if ($value == $del_value) {
						unset($array[$key]);
					}
				}
			}
		} else {
			foreach ($array as $key => $value){
				if ($value == $del_val) {
					unset($array[$key]);
				}
			}
		}
		return array_values($array);
	}
	
	function subval_sort($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[$key] = $a[$key];
		}
		return $c;
	}
	
	function get_code_image(){
		
		$code_image = $this->randomString(10);
		
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('img_code', $code_image);
		$rs = $this->db->get();
		$result = $rs->result();
		$count = count($result);
		
		while ($count == 1){
			
			$code_image = $this->randomString(10);
			
			$this->db->from('luxyart_tb_product_img');
			$this->db->select('*');
			$this->db->where('img_code', $code_image);
			$rs = $this->db->get();
			$result = $rs->result();
			$count = count($result);
		}
		
		return $code_image;
		
	}
	
	function set_use_point_package($point, $user_id){
		
		$this->db->from('luxyart_tb_user_use_point_package');
		$this->db->select('*');
		$this->db->where('value_point_pk != history_use_point and user_id = '.$user_id);
		$rsUserUsePointPackage = $this->db->get();
		$resultUserUsePointPackage = $rsUserUsePointPackage->result();
		
		$total_point_pk = 0;
		$total_history_use_point = 0;
		
		foreach ($resultUserUsePointPackage as $rowUserUsePointPackage){
			
			$total_point_pk += $rowUserUsePointPackage->value_point_pk;
			$total_history_use_point += $rowUserUsePointPackage->history_use_point;
			
		}
		
		$total_remain = $total_point_pk - $total_history_use_point;
		
		if($total_remain >= $point){
			
			$save_point = $point;
			
			while($save_point > 0){
			
				$this->db->from('luxyart_tb_user_use_point_package');
				$this->db->select('*');
				$this->db->where('status = 1 and user_id = '.$user_id);
				$rsUserUsePointPackage = $this->db->get();
				$rowUserUsePointPackage = $rsUserUsePointPackage->row();
			
				$id_use_point_pk = $rowUserUsePointPackage->id_use_point_pk;
				$value_point_pk = $rowUserUsePointPackage->value_point_pk;
				$history_use_point = $rowUserUsePointPackage->history_use_point;
				$point_can_update = $value_point_pk - $history_use_point;
			
				if($point < $point_can_update){
			
					//update 1 lan
					$this->db->where('id_use_point_pk', $id_use_point_pk);
					$this->db->set('history_use_point','history_use_point+'.$save_point,FALSE);
					$this->db->update('luxyart_tb_user_use_point_package');
			
					$save_point = 0;
			
				}
				elseif($point == $point_can_update){
			
					//update 1 lan
					$this->db->where('id_use_point_pk', $id_use_point_pk);
					$this->db->set('history_use_point','history_use_point+'.$save_point,FALSE);
					$this->db->update('luxyart_tb_user_use_point_package');
			
					$save_point = 0;
			
					//update status = 0
					$this->db->where('id_use_point_pk', $id_use_point_pk);
					$this->db->update('luxyart_tb_user_use_point_package', array('status' => 0));
			
					//update status next record
					$this->db->select_min('id_use_point_pk');
					$this->db->where('history_use_point < value_point_pk and user_id = '.$user_id);
					$rsMinIdUsePointPk = $this->db->get('luxyart_tb_user_use_point_package');
					$rowMinIdUsePointPk = $rsMinIdUsePointPk->row();
					$id_use_point_pk_next = $rowMinIdUsePointPk->id_use_point_pk;
			
					$this->db->where('id_use_point_pk', $id_use_point_pk_next);
					$this->db->update('luxyart_tb_user_use_point_package', array('status' => 1));
			
			
				}
				else{//update nhieu lan
					
					$this->db->from('luxyart_tb_user_use_point_package');
					$this->db->select('*');
					$this->db->where('status = 1 and user_id = '.$user_id);
					$rsUserUsePointPackage1 = $this->db->get();
					$rowUserUsePointPackage1 = $rsUserUsePointPackage1->row();
						
					$id_use_point_pk1 = $rowUserUsePointPackage1->id_use_point_pk;
					$value_point_pk1 = $rowUserUsePointPackage1->value_point_pk;
					$history_use_point1 = $rowUserUsePointPackage1->history_use_point;
					$point_can_update1 = $value_point_pk - $history_use_point;
					
					if($save_point < $point_can_update1){
						
						//update 1 lan
						$this->db->where('id_use_point_pk', $id_use_point_pk1);
						$this->db->set('history_use_point','history_use_point+'.$save_point,FALSE);
						$this->db->update('luxyart_tb_user_use_point_package');
				
						$save_point = 0;
						
					}
					else if($save_point == $point_can_update1){
					
						//update 1 lan
						$this->db->where('id_use_point_pk', $id_use_point_pk1);
						$this->db->set('history_use_point','history_use_point+'.$save_point,FALSE);
						$this->db->update('luxyart_tb_user_use_point_package');
				
						$save_point = 0;
				
						//update status = 0
						$this->db->where('id_use_point_pk', $id_use_point_pk1);
						$this->db->update('luxyart_tb_user_use_point_package', array('status' => 0));
				
						//update status next record
						$this->db->select_min('id_use_point_pk');
						$this->db->where('history_use_point < value_point_pk and user_id = '.$user_id);
						$rsMinIdUsePointPk = $this->db->get('luxyart_tb_user_use_point_package');
						$rowMinIdUsePointPk = $rsMinIdUsePointPk->row();
						$id_use_point_pk_next1 = $rowMinIdUsePointPk->id_use_point_pk;
				
						$this->db->where('id_use_point_pk', $id_use_point_pk_next1);
						$this->db->update('luxyart_tb_user_use_point_package', array('status' => 1));
						
					}
					else if($save_point > $point_can_update1){
					
						$this->db->where('id_use_point_pk', $id_use_point_pk1);
						$this->db->set('history_use_point','history_use_point+'.$point_can_update,FALSE);
						$this->db->update('luxyart_tb_user_use_point_package');
						
						$save_point -= $point_can_update;
						
						//update status = 0
						$this->db->where('id_use_point_pk', $id_use_point_pk1);
						$this->db->update('luxyart_tb_user_use_point_package', array('status' => 0));
						
						//update status next record
						$this->db->select_min('id_use_point_pk');
						$this->db->where('history_use_point < value_point_pk and user_id = '.$user_id);
						$rsMinIdUsePointPk = $this->db->get('luxyart_tb_user_use_point_package');
						$rowMinIdUsePointPk = $rsMinIdUsePointPk->row();
						$id_use_point_pk_next1 = $rowMinIdUsePointPk->id_use_point_pk;
						
						$this->db->where('id_use_point_pk', $id_use_point_pk_next1);
						$this->db->update('luxyart_tb_user_use_point_package', array('status' => 1));
						
					}
					
					
				}
			
			}
			
		}
		
		
	}
	
	public function get_detail_credit($id_list){
	
		$this->db->from('luxyart_tb_list_credit');
		$this->db->select('*');
		$this->db->where(array('id_list' => $id_list));
	
		$result = $this->db->get();
		
		return $result;
	
	}
	
	public function setFilterImg($id_img,$user_id){
		
		$whereUserFilterImg = array('user_id' => $user_id);
		$orderUserFilterImg = array();
		$user_filter_img = $this->main_model->getAllData("luxyart_tb_user_filter_img", $whereUserFilterImg, $orderUserFilterImg)->row();
		
		if(count($user_filter_img) == 0){
			$this->db->insert('luxyart_tb_user_filter_img', array('user_id'=>$user_id,'string_filter'=>$id_img));
		}
		else{
			
			$id_filter = $user_filter_img->id_filter;
			$string_filter = $user_filter_img->string_filter;
			
			$arr_user_filter_img = explode(",",$string_filter);
			
			if (!in_array($id_img, $arr_user_filter_img)){
				
				$string_filter = $id_img.",".$string_filter;
				
				$this->db->where('id_filter', $id_filter);
				$this->db->update('luxyart_tb_user_filter_img', array('string_filter' => $string_filter));
				
			}
			
		}
		
	}
	
	function randomDate($start_date, $end_date){
		
		// Convert to timetamps
		$min = strtotime($start_date);
		$max = strtotime($end_date);
	
		// Generate random number using above bounds
		$val = rand($min, $max);
	
		// Convert back to desired date format
		return date('Y-m-d H:i:s', $val);
		
	}
	
	function randomImage(){
		
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where(array('img_check_status'=>1,'img_is_join_compe'=>0,'img_is_sold'=>0));
		$this->db->order_by('rand()');
		$this->db->limit(5);
		$rs = $this->db->get();
		
		$result = $rs->result();
		$list_images = array();
		foreach($result as $result){
			array_push($list_images, $result->id_img."-".$result->user_id);
		}
		
		return $list_images;
		
	}
	
	function isMobile(){
	
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
			return 1;
		}
		else{
			return 0;
		}
	
	}
	
	/**
   	 * Get Pagination Links
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2018-05-21
   	 */
	function get_pagination_links($current_page, $total_pages, $url){
		$links = "";
		if ($total_pages >= 1 && $current_page <= $total_pages) {
			$links .= "<li ".(($current_page==1)?'class="active"':"")."><a href=\"{$url}\">1</a></li>";
			$links = preg_replace('{{page}}', 1, $links);
			if($total_pages > 1){
				$i = max(2, $current_page - 5);
				if ($i > 2)
					$links .= "<li><a>...</a></li>";
				for (; $i < min($current_page + 6, $total_pages); $i++) {
					$links .= "<li ".(($current_page==$i)?'class="active"':"")."><a href=\"{$url}\">{$i}</a></li>";
					$links = preg_replace('{{page}}', $i, $links);
				}
				if ($i != $total_pages)
					$links .= "<li><a>...</a></li>";
				$links .= "<li ".(($current_page==$total_pages)?'class="active"':"")."><a href=\"{$url}\">{$total_pages}</a></li>";
				$links = preg_replace('{{page}}', $total_pages, $links);
			}
		}
		return $links;
	}
	
	function num_format($num,$digit){
		
		$number = round($num,$digit);
		$number_explode = explode(".",$number);
		if($number_explode[1] == ""){
			return number_format($number_explode[0],0,".",",");
		}
		else{
			return number_format($number_explode[0],0,".",",").".".$number_explode[1];
		}
	
	}
	
	public function is_check_email_add_member($email){
		 
		$dt = array();
		 
		$message = "";
		 
		$this->db->from('luxyart_tb_user');
		$this->db->select('user_id');
		$this->db->where(array('user_email'=>$email));
		 
		$rsUser = $this->db->get();
		$countUser = $rsUser->num_rows();
		 
		$this->db->from('luxyart_tb_member');
		$this->db->select('*');
		$this->db->where(array('email_invite'=>$email));
		 
		$rsMember = $this->db->get();
		$countMember = $rsMember->num_rows();
		 
		$isSuccess = 1;
	
		if($countUser == 1){//email ton tai roi khong duoc add
			if($countMember == 1){
				$resultMember = $rsMember->row();
				$status = $resultMember->status;
				if($status == 0 || $status == 1){
					$isSuccess = 1;
				}
				else{
					$isSuccess = 0;
				}
			}
			else{
				if($countMember == 0){
					$isSuccess = 1;
				}
				else{
					$isSuccess = 0;
				}
			}
		}
		else{
			$isSuccess = 1;
		}
		 
		return $isSuccess;
		 
	}
	
	function delete_account($user_id){
	
		$is_delete = 1;
		$sql_delete = array();
	
		//ok
		//- Image detail, image invite: When open have notice -> This image is stop selling (T)
		//=> Check function images/detail_image => shooping don't process
	
		//ok
		//- Post compe (This user post compe) : (T)
		//---- No apply -> can delete compe, can delete this user
		//---- Have apply -> can't delete this user, can't delete compe
		//---- Compe (this user post) finish all -> can delete this user, can delete compe, return image of another user with status [Can apply another compe]
		if($is_delete == 1){
	
			$this->db->join('luxyart_tb_user_competition', 'luxyart_tb_user_competition.id_competition = luxyart_tb_competition_img_apply.id_competition','left');
			$this->db->from('luxyart_tb_competition_img_apply');
			$this->db->select('luxyart_tb_competition_img_apply.*');
			$this->db->where(array('luxyart_tb_user_competition.user_id' => $user_id));
			$rsImgApply = $this->db->get();
			$countImgApply = $rsImgApply->num_rows();
	
			if($countImgApply == 0){//no apply
				//---- No apply -> can delete compe, can delete this user
	
				$this->db->from('luxyart_tb_user_competition');
				$this->db->select('id_competition');
				$this->db->where(array('user_id' => $user_id));
				$rsUserCompetition = $this->db->get();
				$countUserCompetition = $rsUserCompetition->num_rows();
	
				if($countUserCompetition > 0){
					array_push($sql_delete, "DELETE FROM `luxyart_tb_user_competition` where `user_id` = ".$user_id);
				}
	
			}
			else{//have apply
	
				$is_finish_all = 1;
				$resultImgApply = $rsImgApply->result();
				foreach($resultImgApply as $_resultImgApply){
					if($_resultImgApply->result_img != 1){
						$is_finish_all = 0;
					}
				}
	
				if($is_finish_all == 1){///finish all
					//---- Compe (this user post) finish all -> can delete this user, can delete compe, return image of another user with status [Can apply another compe]
					array_push($sql_delete, "DELETE FROM `luxyart_tb_user_competition` where `user_id` = ".$user_id);
					foreach($resultImgApply as $_resultImgApply){
						array_push($sql_delete, "UPDATE `luxyart_tb_product_img` SET `img_is_join_compe` = 0 where `id_img` = ".$_resultImgApply->id_img);
					}
				}
				else{
					//---- Have apply -> can't delete this user, can't delete compe
					$is_delete = 0;
				}
	
			}
	
		}
	
		//ok
		//- Case apply compe (This user join apply compe with image) (T)
		//---- In time apply : can delete this user, can delete image apply compe
		//---- In time choice : can delete this user, can delete image is choisen
		//---- Compe finish : can delete this user, but image join compe will keep
		if($is_delete == 1){
	
			// check compe co apply
			$this->db->from('luxyart_tb_competition_img_apply');
			$this->db->select('*');
			$this->db->where(array('user_id' => $user_id));
			$rsImgApply = $this->db->get();
			$countImgApply = $rsImgApply->num_rows();
	
			if($countImgApply > 0){
	
				$is_finish_all = 1;
				$resultImgApply = $rsImgApply->result();
				foreach($resultImgApply as $_resultImgApply){
					if($_resultImgApply->result_img != 1){
						$is_finish_all = 0;
					}
				}
	
				if($is_finish_all == 0){
					//---- In time apply : can delete this user, can delete image apply compe
					//---- In time choice : can delete this user, can delete image is choisen
					array_push($sql_delete, "DELETE FROM `luxyart_tb_competition_img_apply` where `user_id` = ".$user_id);
					array_push($sql_delete, "DELETE FROM `luxyart_tb_user_competition` where `user_id` = ".$user_id);
					foreach($resultImgApply as $_resultImgApply){
						array_push($sql_delete, "UPDATE `luxyart_tb_product_img` SET `img_is_join_compe` = 0 where `id_img` = ".$_resultImgApply->id_img);
					}
				}
	
			}
	
		}
	
		//- Invite member :(T)
		//---- Case link invite from email : When open link invite member from user (delete) -> Notice this user stop using this account
		//---- Case already become a member : remove all member relationship of this user(delete) with another user
		if($is_delete == 1){
			array_push($sql_delete, "DELETE FROM `luxyart_tb_member` where `user_id` = ".$user_id);
			array_push($sql_delete, "DELETE FROM `luxyart_tb_member` where `user_id_invited` = ".$user_id);
		}
	
		//- Remove get image for sell in allbum -> Image for this user (delete user) will be remove in allbum of another user (User get) (T)
		if($is_delete == 1){
	
			//1.luxyart_tb_user_get_img_sell
			array_push($sql_delete, "DELETE FROM `luxyart_tb_user_get_img_sell` where `user_owner` = ".$user_id);
			//2.luxyart_tb_user_filter_img (no need)
	
		}
	
		if($is_delete == 1){
			return $sql_delete;//được xóa
		}
		else{
			return "no_delete";//không xóa
		}
	
		//- Cart check session (T) => later
		// 		---- In time image of this user already in cart --> when click button buy (one by one or buy all)  -> Need to notice can't buy image have user (delete)
	
		//---- When delete user . another user already bought image of this user keep can download this image ( follow setting set time download image) (H)
		//- Comments : keep history this user post or relay
	
		//- Stop notice, stop send email for this user (L)
		//- Remove pickup this user (case have pickup) (H)
		//- Remove bookmark (This user (bookmark)with another user, another user (bookmark) with this user) (H)
		//- User page (user timeline) of this user when open have notice -> this user is stop selling (H)
		//- Manager point : (H)
		//---- Keep history point get, buy. But after delete this user will not insert any more
		//- Order image (H)
		
	
	}
	
	function set_img_tags_id($id_img){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('id_img',$id_img);
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->row();
	
		$id_img = $resultProductImg->id_img;
		$img_tags = $resultProductImg->img_tags;
	
		$img_tag = explode(",",$img_tags);
	
		$arr_id_product_tag_all = array();
	
		foreach ($img_tag as $tag){
	
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
	
		$this->db->where('id_img', $id_img);
		$this->db->update('luxyart_tb_product_img',array('img_tags_id' => $str_id_product_tag_all));
	
		return $str_id_product_tag_all;
	
	}
	
	function get_img_tags_id($id_img){
	
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('*');
		$this->db->where('id_img',$id_img);
	
		$rsProductImg = $this->db->get();
		$resultProductImg = $rsProductImg->row();
	
		$id_img = $resultProductImg->id_img;
		$img_tags = $resultProductImg->img_tags;
	
		$img_tag = explode(",",$img_tags);
	
		$arr_id_product_tag_all = array();
	
		foreach ($img_tag as $tag){
	
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
	
		return $str_id_product_tag_all;
	
	}

}
// End Main_model Class
/* End of file Main.php */
/* Location: ./app/models/Main.php */
