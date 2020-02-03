<?php
/**
Model call function use for manager user
 */
 	class Manager_user_model extends CI_Model {
 	function __contruction(){
		parent::__contruction();
   	}
	public function get_user_id($user_id){

   		$this->db->from('luxyart_tb_user');
   		$this->db->select('*');
   		$this->db->where('user_id', $user_id);
   		$rs = $this->db->get();
   		$result = $rs->row();
   		if(!empty($result)){
   			return $result;
   		}
   		else{
   			return 0;
   		}
   	}
	function check_user_input_basic($user_id)
	{
   		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where(array('user_id' => $user_id));
		$rs = $this->db->get();
		$row = $rs->row();
		if(!empty($row)){
			if($row->user_lastname=="" ||$row->user_firstname=="" || $row->user_zipcode=="" || $row->state_id==0 || $row->cityname=="" || $row->user_address=="")
			{
				return 0;
			}
			else
			{
				return 1;
			}
		}
   	}
	public function get_list_user_timeline_image_sosanh($user_id, $number, $offset){
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status = ' => 1));
		
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
		
		if($number != "" || $offset != ""){
   			$this->db->limit(intval($number), intval($offset));
		}
		
		$result = $this->db->get();
		return $result;
		
	}
	public function get_list_user_timeline_image($user_id, $number, $offset, $string_image_sapxep,$string_order){
		
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		$this->db->where('luxyart_tb_product_img.img_check_status = ',1);
		if($string_image_sapxep!="")
		{

			$this->db->where_in('luxyart_tb_product_img.id_img', $string_image_sapxep);
			if($string_order!="")
			{
				//188,187,186
				$this->db->_protect_identifiers = FALSE;
				$this->db->order_by('FIELD ( luxyart_tb_product_img.id_img, '.$string_order.')', '', FALSE);
				$this->db->_protect_identifiers = TRUE;
			}
		}
		else
		{
			$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
		}
		
		if($number != "" || $offset != ""){
   			$this->db->limit(intval($number), intval($offset));
		}
		
		$result = $this->db->get();
		return $result;
		
	}
	function getCountProduct($user_id){
   	
		//$this->db->join('luxyart_tb_user_get_img_sell', 'luxyart_tb_product_img.user_id = luxyart_tb_user_get_img_sell.user_id','left');
   		$this->db->from('luxyart_tb_product_img');
   		$this->db->select('luxyart_tb_product_img.user_id, count(luxyart_tb_product_img.id_img) as count');
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
   		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status' => 1));
		//$this->db->or_where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id));
   		//$this->db->group_by('luxyart_tb_product_img.user_id');
   		$rs = $this->db->get();
   		//echo $this->db->last_query();
		//exit();
   		$result = $rs->row();
   		if(!empty($result)){
   			return $result->count;
   		}
   		else{
   			return 0;
   		}
		
   	
   	}
	function getcountget_sell($user_id){
   	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_user_get_img_sell.id_img','left');
   		$this->db->from('luxyart_tb_user_get_img_sell');
   		$this->db->select('luxyart_tb_user_get_img_sell.id_img');
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
   		$this->db->where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status' => 1));
		//$this->db->or_where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id));
   		$this->db->group_by('luxyart_tb_product_img.id_img');
   		$rs = $this->db->get();
   		//echo $this->db->last_query();
		//exit();
   		$result = $rs->row();
		$count = $this->db->count_all_results();
   		if(!empty($result)){
			//echo count($result);
			return$rs->num_rows();
   			//return count($result);
   		}
   		else{
   			return 0;
   		}
		
   	
   	}
	function get_more_from_image_get_sell($user_id,$limit){
   	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_user_get_img_sell.id_img','left');
   		$this->db->from('luxyart_tb_user_get_img_sell');
   		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->select('*');
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
   		$this->db->where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status' => 1));
		//$this->db->or_where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id));
		$this->db->limit($limit);
   		$this->db->group_by('luxyart_tb_product_img.id_img');
   		$rs = $this->db->get();
   		return $rs;
		
   	
   	}
	
	function get_product_user_top_page($user_id){
   	
		//$this->db->join('luxyart_tb_user_get_img_sell', 'luxyart_tb_product_img.user_id = luxyart_tb_user_get_img_sell.user_id','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->select('*');
   		$this->db->from('luxyart_tb_product_img');
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
   		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status' => 1));
		$this->db->where('luxyart_tb_manager_server.server_status', 0);
		//$this->db->or_where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id));
   		$this->db->group_by('luxyart_tb_product_img.id_img');
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
		$this->db->limit(3);
   		$rs = $this->db->get();
   		//$result = $rs->row();
		//echo $this->db->last_query();
		//exit();
		return $rs;
   	}
	public function get_list_order($user_id,$limit)
	{
		$this->db->join('luxyart_tb_product_img_order_detail', 'luxyart_tb_product_img_order_detail.id_order_img = luxyart_tb_product_img_order.id_order_img','left');
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img_order_detail.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_product_img_order_detail.user_owner','left');
		$this->db->join('luxyart_tb_product_img_size', 'luxyart_tb_product_img_order_detail.id_img_size = luxyart_tb_product_img_size.id_img_size','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');

		$this->db->from('luxyart_tb_product_img_order');
		$this->db->select('luxyart_tb_product_img_order.id_order_img,luxyart_tb_product_img_order.user_buy_img,luxyart_tb_product_img_order.date_order_img,luxyart_tb_product_img_order.date_end_download,luxyart_tb_product_img_size.id_img_size,luxyart_tb_product_img_size.type_size,luxyart_tb_product_img_size.type_size_description,luxyart_tb_product_img.img_title,luxyart_tb_product_img.file_name_original_img,luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_manager_server.server_path_upload,luxyart_tb_user.user_id,luxyart_tb_user.user_firstname,luxyart_tb_user.user_lastname,luxyart_tb_user.display_name,luxyart_tb_user.user_email,luxyart_tb_product_img_size.code_download');
		$this->db->where('luxyart_tb_product_img_order.user_buy_img', $user_id);
		$this->db->where('luxyart_tb_product_img_size.status_img_size', 0);
		$this->db->where('luxyart_tb_manager_server.server_status', 0);
		$this->db->order_by('luxyart_tb_product_img_order_detail.id_or_detail', 'desc');
		$this->db->limit($limit);
		$result = $this->db->get();
		//echo $this->db->last_query();
		//exit();
		return $result;

	}
	
	public function get_list_user_use_point($user_id,$limit)
	{
		$this->db->select('*');
		$this->db->from('luxyart_tb_management_point');
		$this->db->where('luxyart_tb_management_point.user_id', $user_id);
		$this->db->order_by('luxyart_tb_management_point.management_point_id', 'desc');
		$this->db->limit($limit);
		$result = $this->db->get();
		//print_r($result);
		return $result;
	}
	public function count_record_history_use_point($user_id,$limit)
	{
		$this->db->select('*');
		$this->db->from('luxyart_tb_management_point');
		$this->db->where('luxyart_tb_management_point.user_id', $user_id);
		$this->db->order_by('luxyart_tb_management_point.management_point_id', 'desc');
		$this->db->limit($limit,$limit);
		$result = $this->db->get();
		//print_r($result);
		return $result;
	}
	public function get_list_ask_return_point($user_id,$limit)
	{
		$this->db->select('*');
		$this->db->from('luxyart_tb_tranfer_money_user');
		$this->db->where('luxyart_tb_tranfer_money_user.user_id', $user_id);
		$this->db->order_by('luxyart_tb_tranfer_money_user.tranfer_id', 'desc');
		$this->db->limit($limit);
		$result = $this->db->get();
		//print_r($result);
		return $result;
	}
	public function count_record_return_money($user_id,$limit)
	{
		$this->db->select('*');
		$this->db->from('luxyart_tb_tranfer_money_user');
		$this->db->where('luxyart_tb_tranfer_money_user.user_id', $user_id);
		$this->db->order_by('luxyart_tb_tranfer_money_user.tranfer_id', 'desc');
		$this->db->limit($limit,$limit);
		$result = $this->db->get();
		//print_r($result);
		return $result;
	}
	public function get_list_buy_point()
	{
		$this->db->from('luxyart_tb_list_credit');
		$this->db->select('*');
		$this->db->where('status_credit',0);
		$this->db->where('option_pay',1);
		$result = $this->db->get();
		return $result;
	}
	public function list_purchase_order_point($user_id,$number, $offset)
	{
		$this->db->from('luxyart_tb_user_order_credit,luxyart_tb_list_credit');
		$this->db->select('*');
		$this->db->where('user_id',$user_id);
		$this->db->where('luxyart_tb_user_order_credit.id_list = luxyart_tb_list_credit.id_list');
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		$this->db->order_by('luxyart_tb_user_order_credit.id_ordercredit', 'desc');
		$result = $this->db->get();
		return $result;
	}
	public function list_user_message($user_id,$number, $offset)
	{
		$this->db->from('luxyart_tb_user_message');
		$this->db->select('*');
		$this->db->where('user_id',$user_id);
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		$this->db->order_by('luxyart_tb_user_message.id_message', 'desc');
		$result = $this->db->get();
		return $result;
	}
	
	public function get_list_user_use_point_limit($user_id,$number, $offset)
	{
		$this->db->select('*');
		$this->db->from('luxyart_tb_management_point');
		$this->db->where('luxyart_tb_management_point.user_id', $user_id);
		$this->db->order_by('luxyart_tb_management_point.management_point_id', 'desc');
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		$result = $this->db->get();
		//print_r($result);
		return $result;
	}
	public function get_list_ask_return_point_limit($user_id,$number, $offset)
	{
		
		$this->db->select('*');
		$this->db->from('luxyart_tb_tranfer_money_user');
		$this->db->where('luxyart_tb_tranfer_money_user.user_id', $user_id);
		$this->db->order_by('luxyart_tb_tranfer_money_user.tranfer_id', 'desc');
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		$result = $this->db->get();
		//print_r($result);
		return $result;
	}
	
	
	public function get_list_view_img_history($user_id,$limit)
	{

		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_user_view_product_img.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_user_view_product_img');
		$this->db->select('luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_product_img.file_name_watermark_img_1,luxyart_tb_manager_server.server_path_upload');
		$this->db->where('luxyart_tb_user_view_product_img.user_id', $user_id);
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		$this->db->where('luxyart_tb_product_img.img_check_status', 1);
		$this->db->where('luxyart_tb_manager_server.server_status', 0);
		$this->db->order_by('luxyart_tb_user_view_product_img.id_view', 'desc');
		$this->db->limit($limit);
		$result = $this->db->get();
		//echo $this->db->last_query();
		//exit();
		return $result;

	}
	public function get_img_user_follow($user_id)
	{
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_product_img.file_name_watermark_img_1,luxyart_tb_manager_server.server_path_upload');
		$this->db->where('luxyart_tb_product_img.user_id', $user_id);
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		$this->db->where('luxyart_tb_product_img.img_check_status', 1);
		$this->db->where('luxyart_tb_manager_server.server_status', 0);
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
		$this->db->limit(6);
		$result = $this->db->get();
		//echo $this->db->last_query();
		//exit();
		return $result;

	}
	public function get_list_new_img_bookmark_user($user_id,$limit)
	{
		$this->db->join('luxyart_tb_user_follow', 'luxyart_tb_user_follow.user_id = luxyart_tb_product_img.user_id','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_product_img.file_name_watermark_img_1,luxyart_tb_manager_server.server_path_upload');
		$this->db->where('luxyart_tb_user_follow.follow_user_id', $user_id);
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		$this->db->where('luxyart_tb_product_img.img_check_status', 1);
		$this->db->where('luxyart_tb_manager_server.server_status', 0);
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
		$this->db->limit($limit);
		$result = $this->db->get();
		//echo $this->db->last_query();
		//exit();
		return $result;

	}
	public function p_history_view_image($user_id,$number, $offset)
	{
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_user_view_product_img.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_user_view_product_img');
		$this->db->select('luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_product_img.file_name_watermark_img_1,luxyart_tb_manager_server.server_path_upload');
		$this->db->where('luxyart_tb_user_view_product_img.user_id', $user_id);
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		$this->db->where('luxyart_tb_product_img.img_check_status', 1);
		$this->db->where('luxyart_tb_manager_server.server_status', 0);
		$this->db->order_by('luxyart_tb_user_view_product_img.id_view', 'desc');
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		$result = $this->db->get();
		return $result;
	}
	public function p_favorite_image($user_id,$number, $offset)
	{
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_user_bookmark_image.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_user_bookmark_image');
		$this->db->select('luxyart_tb_user_bookmark_image.id_bookmark,luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_product_img.img_title,luxyart_tb_product_img.file_name_watermark_img_1,luxyart_tb_manager_server.server_path_upload');
		$this->db->where('luxyart_tb_user_bookmark_image.user_id', $user_id);
		//$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		$this->db->where('luxyart_tb_product_img.img_check_status', 1);
		$this->db->where('luxyart_tb_manager_server.server_status', 0);
		$this->db->order_by('luxyart_tb_user_bookmark_image.id_bookmark', 'desc');
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		$result = $this->db->get();
		//echo $this->db->last_query();
		//exit();
		return $result;
	}
	public function p_favorite_user($user_id,$number, $offset)
	{
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_follow.user_id','left');
		$this->db->from('luxyart_tb_user_follow');
		$this->db->select('luxyart_tb_user.user_id,luxyart_tb_user.user_firstname,luxyart_tb_user.user_lastname,luxyart_tb_user.display_name,luxyart_tb_user.user_email,luxyart_tb_user.user_avatar,luxyart_tb_user_follow.id_follow');
		$this->db->where('luxyart_tb_user_follow.follow_user_id', $user_id);
		$this->db->where('luxyart_tb_user.user_status', 1);
		$this->db->order_by('luxyart_tb_user_follow.id_follow', 'desc');
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		$result = $this->db->get();
		//echo $this->db->last_query();
		//exit();
		return $result;
	}
	public function p_user_config($user_id)
	{
		$data=array();

		$this->db->join('luxyart_tb_country', 'luxyart_tb_country.country_id = luxyart_tb_user.country_id','left');
		$this->db->from('luxyart_tb_user');
		$this->db->select('*');
		$this->db->where('luxyart_tb_user.user_id',$user_id);
		$re=$this->db->get();
		if($re->num_rows()>0)
		{
			$data=$re->row_array();
		}
		$re->free_result();
		return $data;
	}
	public function p_user_user_export_money($user_id)
	{
		$data=array();
		$this->db->from('luxyart_tb_bank_infomation');
		$this->db->select('*');
		$this->db->where('luxyart_tb_bank_infomation.user_id',$user_id);
		$re=$this->db->get();
		if($re->num_rows()>0)
		{
			$data=$re->row_array();
		}
		$re->free_result();
		return $data;
	}
	function check_mail_exists($user_id,$email)
	{
		$this->db->where_not_in('luxyart_tb_user.user_id', $user_id);
		$this->db->where('luxyart_tb_user.user_email', $email);
		$query = $this->db->get('luxyart_tb_user');
		if ($query->num_rows() > 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	function check_pass_exists($user_id,$pass)
	{
		$this->db->where('luxyart_tb_user.user_id', $user_id);
		$this->db->where('luxyart_tb_user.user_pass', md5($pass));
		$query = $this->db->get('luxyart_tb_user');
		if ($query->num_rows() > 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	function update_user_config($user_id,$data)
	{
		$this->db->where('luxyart_tb_user.user_id', $user_id);
		$this->db->update('luxyart_tb_user', $data);
	}
	public function load_list_state($country_id)
	{
		$this->db->select('*');
		$this->db->from('luxyart_tb_country_state');
		$this->db->where('luxyart_tb_country_state.country_id', $country_id);
		$result = $this->db->get();
		return $result;
	}
	public function p_list_order($user_id,$number, $offset)
	{
		$this->db->join('luxyart_tb_product_img_order_detail', 'luxyart_tb_product_img_order_detail.id_order_img = luxyart_tb_product_img_order.id_order_img','left');
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img_order_detail.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_product_img_order_detail.user_owner','left');
		$this->db->join('luxyart_tb_product_img_size', 'luxyart_tb_product_img_order_detail.id_img_size = luxyart_tb_product_img_size.id_img_size','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');

		$this->db->from('luxyart_tb_product_img_order');
		$this->db->select('luxyart_tb_product_img_order.id_order_img,luxyart_tb_product_img_order.user_buy_img,luxyart_tb_product_img_order.date_order_img,luxyart_tb_product_img_order.date_end_download,luxyart_tb_product_img_size.id_img_size,luxyart_tb_product_img_size.type_size,luxyart_tb_product_img_size.type_size_description,luxyart_tb_product_img.img_title,luxyart_tb_product_img.file_name_original_img,luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_code,luxyart_tb_manager_server.server_path_upload,luxyart_tb_user.user_id,luxyart_tb_user.user_firstname,luxyart_tb_user.user_lastname,luxyart_tb_user.display_name,luxyart_tb_user.user_email,luxyart_tb_product_img_size.code_download');
		$this->db->where('luxyart_tb_product_img_size.status_img_size', 0);
		$this->db->where('luxyart_tb_product_img_order.user_buy_img', $user_id);
		$this->db->where('luxyart_tb_manager_server.server_status', 0);
		$this->db->order_by('luxyart_tb_product_img_order_detail.id_or_detail', 'desc');
		if($number != "" || $offset != "")
		{
   			$this->db->limit(intval($number), intval($offset));
		}
		$result = $this->db->get();
		// echo $this->db->last_query();
		//exit();
    // print_r($result);
		return $result;
	}
	function resize($newWidth,$targetFile,$originalFile) {
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
   	
   	public function get_list_member($user_id, $number, $offset){
   	
   		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_member.user_id_invited','left');
   		$this->db->from('luxyart_tb_member');
   		$this->db->select('luxyart_tb_member.*,luxyart_tb_user.*');
   		$this->db->where(array('luxyart_tb_member.user_id' => $user_id));
   		$this->db->where_in('luxyart_tb_member.status', array(2));
   		$this->db->order_by('luxyart_tb_member.member_id', 'desc');
   	
   		if($number != "" || $offset != ""){
   			$this->db->limit(intval($number), intval($offset));
   		}
   	
   		$result = $this->db->get();
   		return $result;
   	
   	}
   	
   	public function get_list_invited($user_id, $number, $offset){
   	
   		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_member.user_id_invited','left');
   		$this->db->from('luxyart_tb_member');
   		$this->db->select('luxyart_tb_member.*,luxyart_tb_user.*');
   		$this->db->where(array('luxyart_tb_member.user_id' => $user_id));
   		$this->db->where_in('luxyart_tb_member.status', array(0,1,2));
   		$this->db->order_by('luxyart_tb_member.member_id', 'desc');
   	
   		if($number != "" || $offset != ""){
   			$this->db->limit(intval($number), intval($offset));
   		}
   	
   		$result = $this->db->get();
   		return $result;
   	
   	}
   	
   	public function get_list_email_agent($number, $offset){
   	
   		$this->db->from('luxyart_tb_email_agent');
   		$this->db->select('*');
   		$this->db->order_by('rand()');
   		$this->db->limit(5);
   	
   		if($number != "" || $offset != ""){
   			$this->db->limit(intval($number), intval($offset));
   		}
   	
   		$result = $this->db->get();
   		return $result;
   	
   	}

}
