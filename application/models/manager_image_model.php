<?php
/**
Model call function use for manager user
*/
class Manager_image_model extends CI_Model {
 		
 	function __contruction(){
		parent::__contruction();
   	}
   	
	public function get_list_manager_image($user_id, $number, $offset){
		
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
		
		if($number != "" || $offset != ""){
   			$this->db->limit(intval($number), intval($offset));
		}
		
		$result = $this->db->get();
		return $result;
		
	}
	
	public function get_all_list_manager_image($user_id){
		
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$result = $this->db->get();
		$join1 = $this->db->last_query();
		
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_user_get_img_sell.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_user_get_img_sell');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$result = $this->db->get();
		$join2 = $this->db->last_query();
		
		$union_query = $this->db->query($join1.' UNION '.$join2.' ORDER BY id_img');
		//$union_query = $this->db->query($join1.' ORDER BY id_img');
		//$union_query = $this->db->query($join2.' ORDER BY id_img');
		
		return $union_query;
		
	}
	
	public function count_list_manager_image_scroll($user_id){
		
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$result = $this->db->get();
		$join1 = $this->db->last_query();
		
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_user_get_img_sell.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_user_get_img_sell');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$result = $this->db->get();
		$join2 = $this->db->last_query();
		
		$union_query = $join1.' UNION '.$join2;
		
		$join3 = $this->db->query($union_query);
		
		return count($join3->result());
		
	}
	
	public function get_all_list_manager_image_scroll($user_id, $start_limit, $number){
		
		//echo '<br/>Start'.$start_limit;
		//echo '<br/>number '.$number;
		$arr_id_img = array();
		$order_filter_id_user = '';
		$order_filter_id_user1 = '';
		//$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.id_img');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$result1 = $this->db->get();
		$rs1 = $result1->result();
		$bien_chay = 1;
		foreach ($rs1 as $_rs1){
			
				if($bien_chay==$result1->num_rows())
				{
					$order_filter_id_user .= $_rs1->id_img;
				}
				else
				{
					$order_filter_id_user .= $_rs1->id_img.', ';
				}
				$bien_chay++;
			array_push($arr_id_img, $_rs1->id_img);
		}
		//print_r($arr_id_img);
		//echo $order_filter_id_user;
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_user_get_img_sell.id_img','left');
		//$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_user_get_img_sell');
		$this->db->select('luxyart_tb_product_img.id_img');
		$this->db->where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$result2 = $this->db->get();
		$rs2 = $result2->result();
		$bien_chay2 = 1;
		
		if($result2->num_rows() > 0){
			$order_filter_id_user .=', ';
		}
		
		foreach ($rs2 as $_rs2){
			if($bien_chay2==$result2->num_rows())
				{
					$order_filter_id_user .= $_rs2->id_img;
				}
				else
				{
					$order_filter_id_user .= $_rs2->id_img.', ';
				}
				$bien_chay2++;
			array_push($arr_id_img, $_rs2->id_img);
		}
		//echo $order_filter_id_user.'<br/>';
		//exit();
		//echo '<br/>2<br/>';
		//print_r($arr_id_img);
		//exit();
		$arr_id_img = array_unique($arr_id_img);
		$this->db->from('luxyart_tb_user_filter_img');
		$this->db->select('string_filter');
		$this->db->where('user_id', $user_id);
		$rs_array = $this->db->get();
		$result_array = $rs_array->row();
		if ($this->db->affected_rows() > 0)
		{
			
			$string_filter .= ', '.$result_array->string_filter;
			if($result_array->string_filter!="")
			{
				$order_filter_id_user = $result_array->string_filter.', '.$order_filter_id_user;
			}

		}
		//if($start_limit != "" || $number != ""){
			
			//get user filter img
			$whereUserFilterImg = array('user_id' => $user_id);
			$orderUserFilterImg = array();
			$data['user_filter_img'] = $this->main_model->getAllData("luxyart_tb_user_filter_img", $whereUserFilterImg, $orderUserFilterImg)->row();
			
			$string_filter = $data['user_filter_img']->string_filter;
			if($string_filter!=""){
				$filter = explode(", ", $string_filter);
				$filter = array_reverse($filter);
				$order_filter = implode(", ", $filter);
			}
			
		//}
		
		$order_filter_id_user_explode = explode(",",$order_filter_id_user);
		
		if($order_filter_id_user_explode[0] == ""){
			$order_filter_id_user = ltrim($order_filter_id_user, ",");
		}
		
		if(trim($order_filter_id_user_explode[count($order_filter_id_user_explode)-1]) == ""){
			//$order_filter_id_user = rtrim($order_filter_id_user, ",");
			$order_filter_id_user = substr($order_filter_id_user, 0, -2);;
		}
			
		$order_filter = $order_filter_id_user;
		$order_filter = $this->xuly_chuoi_tra_ve($order_filter);
		
			$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
			$this->db->from('luxyart_tb_product_img');
			$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
			
			if($result2->num_rows() > 0){
				$this->db->where(array('luxyart_tb_product_img.img_check_status != ' => 4));
			}
			else{
				$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
			}
			
			$this->db->group_by('luxyart_tb_product_img.id_img');
			
			if(!empty($arr_id_img)){
				$this->db->where_in("luxyart_tb_product_img.id_img", $arr_id_img);
			}
			
			
			
			if($string_filter == ""){
				
				$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
				
			}
			else{
				if(!empty($order_filter)){
					$this->db->_protect_identifiers = FALSE;
					$this->db->order_by('FIELD ( luxyart_tb_product_img.id_img, '.$order_filter.')', '', FALSE);
					$this->db->_protect_identifiers = TRUE;
				}
			}
			
			if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));}
			
		
			$result = $this->db->get();
	
		
		return $result;
		
	}
	
	public function get_all_list_manager_image_choose_scroll($user_id, $start_limit, $number){
		
		//echo '<br/>Start'.$start_limit;
		//echo '<br/>number '.$number;
		$arr_id_img = array();
		$order_filter_id_user = '';
		//$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.id_img');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status' => 1));
		$result1 = $this->db->get();
		$rs1 = $result1->result();
		$bien_chay = 1;
		foreach ($rs1 as $_rs1){
			
				if($bien_chay==$result1->num_rows())
				{
					$order_filter_id_user .= $_rs1->id_img;
				}
				else
				{
					$order_filter_id_user .= $_rs1->id_img.', ';
				}
				$bien_chay++;
			array_push($arr_id_img, $_rs1->id_img);
		}
		//print_r($arr_id_img);
		//echo $order_filter_id_user;
		
		//echo $order_filter_id_user.'<br/>';
		//exit();
		//echo '<br/>2<br/>';
		//print_r($arr_id_img);
		//exit();
		$arr_id_img = array_unique($arr_id_img);
		$this->db->from('luxyart_tb_user_filter_img');
		$this->db->select('string_filter');
		$this->db->where('user_id', $user_id);
		$rs_array = $this->db->get();
		$result_array = $rs_array->row();
		if ($this->db->affected_rows() > 0)
		{
			
			$string_filter .= ', '.$result_array->string_filter;
			if($result_array->string_filter!="")
			{
				$order_filter_id_user = $result_array->string_filter.', '.$order_filter_id_user;
			}

		}
		//if($start_limit != "" || $number != ""){
			
			//get user filter img
			$whereUserFilterImg = array('user_id' => $user_id);
			$orderUserFilterImg = array();
			$data['user_filter_img'] = $this->main_model->getAllData("luxyart_tb_user_filter_img", $whereUserFilterImg, $orderUserFilterImg)->row();
			
			$string_filter = $data['user_filter_img']->string_filter;
			if($string_filter!=""){
				$filter = explode(", ", $string_filter);
				$filter = array_reverse($filter);
				$order_filter = implode(", ", $filter);
			}
			
		//}
		
			$order_filter_id_user_explode = explode(",",$order_filter_id_user);
			
			if($order_filter_id_user_explode[0] == ""){
				$order_filter_id_user = ltrim($order_filter_id_user, ",");
			}
			
			if(trim($order_filter_id_user_explode[count($order_filter_id_user_explode)-1]) == ""){
				//$order_filter_id_user = rtrim($order_filter_id_user, ",");
				$order_filter_id_user = substr($order_filter_id_user, 0, -2);;
			}
			
		$order_filter = $order_filter_id_user;
		
		$order_filter = $this->xuly_chuoi_tra_ve($order_filter);
		
		//echo '=====<br/>'.$order_filter.'<br/>';
			$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
			$this->db->from('luxyart_tb_product_img');
			$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
			$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4, 'luxyart_tb_product_img.img_is_join_compe' => 0));
			$this->db->group_by('luxyart_tb_product_img.id_img');
			
			if(!empty($arr_id_img)){
				$this->db->where_in("luxyart_tb_product_img.id_img", $arr_id_img);
			}
			
			if(!empty($order_filter)){
				$this->db->_protect_identifiers = FALSE;
				$this->db->order_by('FIELD ( luxyart_tb_product_img.id_img, '.$order_filter.')', '', FALSE);
				$this->db->_protect_identifiers = TRUE;
			}
			
			if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));}
			
		
			$result = $this->db->get();
		
		return $result;
		
	}
	
	public function get_all_list_manager_image_scroll_old($user_id, $start_limit, $number){
	
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$result = $this->db->get();
		$join1 = $this->db->last_query();
		
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_user_get_img_sell.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_user_get_img_sell');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_user_get_img_sell.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$result = $this->db->get();
		$join2 = $this->db->last_query();
		
		/*if($start_limit != "" || $number != ""){
				
			//get user filter img
			$whereUserFilterImg = array('user_id' => $user_id);
			$orderUserFilterImg = array();
			$data['user_filter_img'] = $this->main_model->getAllData("luxyart_tb_user_filter_img", $whereUserFilterImg, $orderUserFilterImg)->row();
				
			$string_filter = $data['user_filter_img']->string_filter;
			$filter = explode(",", $string_filter);
			$filter = array_reverse($filter);
			$order_filter = implode(",", $filter);
			
			if(!empty($order_filter)){
				$order_by = "ORDER BY FIELD(`id_img`,".$order_filter.") DESC";
			}
			else{
				$order_by = "ORDER BY `id_img` DESC";
			}
			//$union_query = $this->db->query($join1.' UNION '.$join2.' '.$order_by.' LIMIT '.$number.", ".$start_limit);
			$union_query = $join1.' UNION '.$join2.' '.$order_by.' LIMIT '.$number.", ".$start_limit;
				
		}
		else{
			
			$order_by = "ORDER BY `id_img` DESC";
			//$union_query = $this->db->query($join1.' UNION '.$join2.' '.$order_by);
			$union_query = $join1.' UNION '.$join2.' '.$order_by;
			
		}*/
		
		if($start_limit != "" || $number != ""){
			
			//get user filter img
			$whereUserFilterImg = array('user_id' => $user_id);
			$orderUserFilterImg = array();
			$data['user_filter_img'] = $this->main_model->getAllData("luxyart_tb_user_filter_img", $whereUserFilterImg, $orderUserFilterImg)->row();
			
			$string_filter = $data['user_filter_img']->string_filter;
			$filter = explode(",", $string_filter);
			$filter = array_reverse($filter);
			$order_filter = implode(",", $filter);
			
			if(!empty($order_filter)){
				$order_by = "GROUP BY id_img ORDER BY FIELD(`id_img`,".$order_filter.") DESC";
			}
			else{
				$order_by = "GROUP BY id_img ORDER BY `id_img` DESC";
			}
			
			
			$union_query = $join1.' UNION '.$join2.' '.$order_by.' LIMIT '.$number.", ".$start_limit;;
			
		}
		else{
			
			
			
			$order_by = "ORDER BY `id_img` DESC";
			$union_query = $join1.' UNION '.$join2.' '.$order_by;
			
		}
		
		
		
		
		//echo "union_query = ".$union_query."<br>";
		
		
		
		$join3 = $this->db->query($union_query);
		
		//echo "<pre>";
		//print_r($join3->result());
		
		
		//echo "union_query = ".$this->db->last_query()."<br>";
		//$result = $this->db->get();
		
		return $join3;
	
	}
	
	public function get_list_manager_image_choose($user_id, $number, $offset){
	
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4, 'luxyart_tb_product_img.img_is_join_compe' => 0));
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
	
		if($number != "" || $offset != ""){
			$this->db->limit(intval($number), intval($offset));
		}
	
		$result = $this->db->get();
		return $result;
	
	}
	
	public function get_list_image_other($user_id, $id_img, $number){
	
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_product_img.id_img != ' => $id_img, 'luxyart_tb_product_img.img_check_status ' => 1));
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
		if($number != ""){
			$this->db->limit(intval($number));
		}
		
		$result = $this->db->get();
		
		return $result;
	
	}
	
	public function get_list_image_relative($user_id, $id_img, $number){
		
		$idImgs = array();
		
		$this->db->from('luxyart_tb_img_category');
		$this->db->select('root_cate');
		$this->db->where(array('id_img' => $id_img));
		$this->db->group_by("root_cate");
		$rsImgCategory = $this->db->get();
		
		$resultImgCategory = $rsImgCategory->result();
		
		foreach($resultImgCategory as $detailImgCategory){
			
			array_push($idImgs, $detailImgCategory->root_cate);
		}
		
		$idImgs = array_unique($idImgs);
	
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->join('luxyart_tb_img_category', 'luxyart_tb_img_category.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id != ' => $user_id, 'luxyart_tb_product_img.id_img != ' => $id_img, 'luxyart_tb_product_img.img_check_status ' => 1));
		$this->db->where("luxyart_tb_product_img.img_is_join_compe = 0 and luxyart_tb_product_img.img_is_sold = 0 and CASE WHEN luxyart_tb_product_img.option_sale = 1 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +1 DAY) >= now() WHEN luxyart_tb_product_img.option_sale = 2 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +7 DAY) >= now()  WHEN luxyart_tb_product_img.option_sale = 3 THEN  DATE_ADD(luxyart_tb_product_img.date_add_img, INTERVAL +30 DAY) >= now() ELSE luxyart_tb_product_img.option_sale = 4 END", NULL, FALSE);
		if(!empty($idImgs)){
			$this->db->where_in("luxyart_tb_img_category.root_cate", $idImgs);
		}
		$this->db->group_by("luxyart_tb_product_img.id_img");
		$this->db->order_by('luxyart_tb_product_img.id_img', 'desc');
	
		if($number != ""){
			$this->db->limit(intval($number));
		}
	
		$result = $this->db->get();
		//echo $this->db->last_query();
	
		return $result;
	
	}
	
	public function get_detail_image($id_img){
	
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_product_img.user_id','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.*, luxyart_tb_user.*');
		$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img));
	
		$result = $this->db->get();
		
		return $result;
	
	}
	
	public function get_detail_image_by_img_code($img_code){
	
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_product_img.user_id','left');
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*, luxyart_tb_manager_server.*, luxyart_tb_user.*');
		$this->db->where(array('luxyart_tb_product_img.img_code' => $img_code));
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_product_tags_not_contain($id_img, $lang_id){
		
		$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
		
		$img_root = array();
		$img_parent = array();
		
		$img_tags = $detail_product->img_tags;
		$img_tags_explode = explode(",", $img_tags);
		
		$this->db->from('luxyart_tb_img_category');
		$this->db->select('root_cate,parent_cate');
		$this->db->where(array('id_img' => $id_img));
		$this->db->group_by("root_cate,parent_cate");
		$rsImgCategory = $this->db->get();
		$resultImgCategory = $rsImgCategory->result();
		
		foreach($resultImgCategory as $detailImgCategory){			
			array_push($img_root, $detailImgCategory->root_cate);
			array_push($img_parent, $detailImgCategory->parent_cate);
		}
		
		$img_root = array_unique($img_root);
		$img_parent = array_unique($img_parent);
		
		$count_img_root = count($img_root);
		$count_img_parent = count($img_parent);
		
		if($count_img_root == 0 && $count_img_parent == 0){
			$this->db->from('luxyart_tb_product_tags');
			$this->db->select('luxyart_tb_product_tags.*');
			$this->db->where(array('status' => 1));
			$this->db->where_not_in('tag_name', $img_tags_explode);
			$this->db->order_by('luxyart_tb_product_tags.tag_name', 'asc');
			$rsProductTags = $this->db->get();
			$resultProductTags = $rsProductTags->result();
		}
		else{
			if($count_img_parent == 0){
				$this->db->from('luxyart_tb_product_tags');
				$this->db->select('luxyart_tb_product_tags.*');
				$this->db->where(array('status' => 1));
				$this->db->where_in('root_cate',$img_root);
				$this->db->where_not_in('tag_name', $img_tags_explode);
				$this->db->order_by('luxyart_tb_product_tags.tag_name', 'asc');
				$rsProductTags = $this->db->get();
				$resultProductTags = $rsProductTags->result();
			}
			else{
				$this->db->from('luxyart_tb_product_tags');
				$this->db->select('luxyart_tb_product_tags.*');
				$this->db->where(array('status' => 1));
				$this->db->where_in('parent_cate',$img_parent);
				$this->db->where_not_in('tag_name', $img_tags_explode);
				$this->db->order_by('luxyart_tb_product_tags.tag_name', 'asc');
				$rsProductTags = $this->db->get();
				$resultProductTags = $rsProductTags->result();
			}
		}
		
		return $resultProductTags;
		
	}
	
	function get_time_limit_sale($id_img){
		
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*');
		$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img));
		$rsProductImg = $this->db->get();
		
		if(!empty($rsProductImg)){
			
   			$resultProductImg = $rsProductImg->row();
   			
   			$date_active = $resultProductImg->date_active;
   			$option_sale = $resultProductImg->option_sale;
   			
   			//date_add_img + option_sale
   			if($option_sale == 1){//1d
   				$date_limit = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date_active)));
   			}
   			else if($option_sale == 2){//1w
   			
   				$date_limit = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($date_active)));
   			
   			}
   			else if($option_sale == 3){//1m
   			
   				$date_limit = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($date_active)));
   			
   			}
   			else if($option_sale == 4){
   				$date_limit = date('Y-m-d H:i:s', strtotime('+1 day', time()));
   			}
   			
   			return $date_limit;
   			
   		}
   		else{
   			return "";
   		}
		
	}
	
	function get_time_remaining($id_img){
		
		$this->db->from('luxyart_tb_product_img');
		$this->db->select('luxyart_tb_product_img.*');
		$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img));
		$rsProductImg = $this->db->get();
		
		if(!empty($rsProductImg)){
			
   			$resultProductImg = $rsProductImg->row();
   			
   			$date_active = $resultProductImg->date_active;
   			$option_sale = $resultProductImg->option_sale;
   			
   			//date_add_img + option_sale
   			if($option_sale == 1){//1d
   				$date_limit = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date_active)));
   			}
   			else if($option_sale == 2){//1w
   			
   				$date_limit = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($date_active)));
   			
   			}
   			else if($option_sale == 3){//1m
   			
   				$date_limit = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($date_active)));
   			
   			}
   			else if($option_sale == 4){
   				$date_limit = date('Y-m-d H:i:s', strtotime('+1 day', time()));
   			}
   			
   			$value_date_limit = date_create($date_limit);
   			
   			$now = date_create(date('Y-m-d H:i:s'));
   			
   			$diff = date_diff($value_date_limit,$now);
   			
   			$r	 							= $diff->format("%R");
   			$d	 							= $diff->format("%d");
   			
   			if($r == "-"){
   				if($option_sale == 4){
   					return 1;
   				}
   				else{
   					if($d == 0){
   						return $diff->format("%H:%I:%S");
   					}
   					else{
   						return $diff->format("%d ".$this->lang->line('day')." %H:%I:%S");
   					}
   				}
   			}
   			else{
   				return -1;
   			}
   			
   			return -1;
   			
   		}
		
	}
	
	public function get_list_img_size($id_img){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_product_img_size.id_img','left');
		$this->db->from('luxyart_tb_product_img_size');
		$this->db->select('luxyart_tb_product_img_size.*');
		$this->db->where(array('luxyart_tb_product_img_size.id_img' => $id_img,'luxyart_tb_product_img_size.status_img_size' => 0,'luxyart_tb_product_img.img_check_status' => 1));
		$this->db->where('((luxyart_tb_product_img.is_limit = 0) OR (luxyart_tb_product_img.is_limit = 1 and luxyart_tb_product_img_size.size_stock > 0))');
		$result = $this->db->get();
	
		return $result;
	
	}
	
	function main_color_image($file){
	
		$image=imagecreatefromjpeg($file);
		$thumb=imagecreatetruecolor(1,1); imagecopyresampled($thumb,$image,0,0,0,0,1,1,imagesx($image),imagesy($image));
		$mainColor=strtoupper(dechex(imagecolorat($thumb,0,0)));
		return $mainColor;
	
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
			$arr_compare_color[$_result->product_color_id] = $this->calculateLuminosityRatio($_result->css_color_style, "#".$main_color);
			// $arr_compare_color[$_result->product_color_id] = $this->calculateLuminosityRatio($main_color, $_result->css_color_style);
			//  echo $this->calculateLuminosityRatio($_result->css_color_style, $main_color)." - ";
		}
	
		$min_compare_color = min($arr_compare_color);
		$value_color_choose = array_search($min_compare_color,$arr_compare_color);
		// echo 'CHoose:'.$value_color_choose;
		$this->db->from('luxyart_tb_product_color');
		$this->db->select('*');
		$this->db->where(array('lang_id' => 1, 'css_color_style' => $arr_value_color[$value_color_choose]));
		$rs = $this->db->get();
		$row = $rs->row();
	
		return $row->product_color_id;
	
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
	
	function xuly_chuoi_tra_ve($chuoi_bo_vao)
	{
		$text_bien  = $chuoi_bo_vao;
		$chuoilay = array(", , ", ",,");
		$choidoi   = array(',',',');
		$out_text = str_replace($chuoilay, $choidoi, $text_bien);
		return $out_text;
	}
	
}