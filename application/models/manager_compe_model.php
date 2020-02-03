<?php
/**
Model call function use for manager user
*/
class Manager_compe_model extends CI_Model {
 		
 	function __contruction(){
		parent::__contruction();
   	}
   	
	public function get_list_manager_compe($user_id, $number, $offset){
		
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*');
		$this->db->where(array('luxyart_tb_user_competition.user_id' => $user_id, 'luxyart_tb_user_competition.status_com != ' => 4));
		$this->db->order_by('luxyart_tb_user_competition.id_competition', 'desc');
		
		if($number != "" || $offset != ""){
   			$this->db->limit(intval($number), intval($offset));
		}
		
		$result = $this->db->get();
		return $result;
		
	}
	
	public function get_list_manager_compe_join($user_id, $number, $offset){
	
		$this->db->join('luxyart_tb_competition_img_apply', 'luxyart_tb_competition_img_apply.id_competition = luxyart_tb_user_competition.id_competition','left');
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload, luxyart_tb_competition_img_apply.result_img as `result_img_apply`');
		$this->db->where(array('luxyart_tb_user_competition.status_com != ' => 4, 'luxyart_tb_competition_img_apply.user_id' => $user_id));
		//$this->db->group_by('luxyart_tb_user_competition.id_competition');
		$this->db->order_by('luxyart_tb_user_competition.id_competition', 'desc');
	
		if($number != "" || $offset != ""){
			$this->db->limit(intval($number), intval($offset));
		}
	
		$result = $this->db->get();
		return $result;
	
	}
	
	public function get_list_compe_other($id_competition, $user_id, $number){
		
		$user = $this->function_model->getUser($user_id);
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_competition.user_id','left');
		$this->db->join('luxyart_tb_competition_time_agree', 'luxyart_tb_competition_time_agree.id = luxyart_tb_user_competition.competition_time_agree_id','left');
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*, luxyart_tb_user.*');
		/*if($user->user_level >= 4){
			$this->db->where(array('luxyart_tb_user_competition.id_competition != ' => $id_competition, 'luxyart_tb_user_competition.date_end_com > ' => date('Y-m-d')));
		}
		else{
			$this->db->where(array('luxyart_tb_user_competition.id_competition != ' => $id_competition, 'luxyart_tb_user_competition.date_end_com > ' => date('Y-m-d'), 'luxyart_tb_user_competition.is_private' => 0));
		}*/
		$this->db->where(array('luxyart_tb_user_competition.id_competition != ' => $id_competition, 'luxyart_tb_user_competition.date_end_com >= ' => date('Y-m-d')));
		$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		$this->db->order_by('luxyart_tb_user_competition.date_end_com', 'asc');
		
		if($number != ""){
			$this->db->limit(intval($number));
		}
	
		$result = $this->db->get();
	
		return $result;
	
	}
	public function get_list_compe_other_list_compe($user_id, $start_limit, $number){
		
		$user = $this->function_model->getUser($user_id);
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_competition.user_id','left');
		$this->db->join('luxyart_tb_competition_time_agree', 'luxyart_tb_competition_time_agree.id = luxyart_tb_user_competition.competition_time_agree_id','left');
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*, luxyart_tb_user.*');
		/*if($user->user_level >= 4){
			$this->db->where(array('luxyart_tb_user_competition.date_end_com >= ' => date('Y-m-d')));
		}
		else{
			$this->db->where(array('luxyart_tb_user_competition.date_end_com >= ' => date('Y-m-d'), 'luxyart_tb_user_competition.is_private' => 0));
		}*/
		$this->db->where(array('luxyart_tb_user_competition.date_end_com >= ' => date('Y-m-d')));
		$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		$this->db->order_by('luxyart_tb_user_competition.date_end_com', 'asc');
		if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));
		}
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_compe_other_list_compe_test($user_id, $start_limit, $number){
	
		$user = $this->function_model->getUser($user_id);
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_competition.user_id','left');
		$this->db->join('luxyart_tb_competition_time_agree', 'luxyart_tb_competition_time_agree.id = luxyart_tb_user_competition.competition_time_agree_id','left');
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*, luxyart_tb_user.*');
		$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2,3));
	$this->db->order_by('luxyart_tb_user_competition.date_end_com', 'asc');
		if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));
		}
	
		$result = $this->db->get();
	
		return $result;
	
	}
	public function get_list_compe_other_list_compe_test_page($user_id, $start_limit, $number){
	
		$user = $this->function_model->getUser($user_id);
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_competition.user_id','left');
		$this->db->join('luxyart_tb_competition_time_agree', 'luxyart_tb_competition_time_agree.id = luxyart_tb_user_competition.competition_time_agree_id','left');
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*, luxyart_tb_user.*');
		$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2,3));
	$this->db->order_by('luxyart_tb_user_competition.date_end_com', 'asc');
		if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number+1));
		}
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_compe_other_list_compe_count($user_id){
		
		$user = $this->function_model->getUser($user_id);
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_competition.user_id','left');
		$this->db->join('luxyart_tb_competition_time_agree', 'luxyart_tb_competition_time_agree.id = luxyart_tb_user_competition.competition_time_agree_id','left');
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*, luxyart_tb_user.*');
		if($user->user_level >= 4){
			$this->db->where(array('luxyart_tb_user_competition.date_end_com >= ' => date('Y-m-d')));
		}
		else{
			$this->db->where(array('luxyart_tb_user_competition.date_end_com >= ' => date('Y-m-d'), 'luxyart_tb_user_competition.is_private' => 0));
		}
		$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		$this->db->order_by('luxyart_tb_user_competition.date_end_com', 'asc');	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_compe_other_list_compe_count_test($user_id){
	
		$user = $this->function_model->getUser($user_id);
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_competition.user_id','left');
		$this->db->join('luxyart_tb_competition_time_agree', 'luxyart_tb_competition_time_agree.id = luxyart_tb_user_competition.competition_time_agree_id','left');
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*, luxyart_tb_user.*');
		$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2,3));
		$this->db->order_by('luxyart_tb_user_competition.date_end_com', 'asc');
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_compe_apply($id_competition, $number){
	
		$user = $this->function_model->getUser($user_id);
		
		$this->db->join('luxyart_tb_user_competition', 'luxyart_tb_user_competition.id_competition = luxyart_tb_competition_img_apply.id_competition','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_competition.user_id','left');
		$this->db->join('luxyart_tb_competition_time_agree', 'luxyart_tb_competition_time_agree.id = luxyart_tb_user_competition.competition_time_agree_id','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_user_competition.*, luxyart_tb_user.*');
		if($user->user_level >= 4){
			$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 0));
		}
		else{
			$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 0, 'luxyart_tb_user_competition.is_private' => 0));
		}
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		if($number != ""){
			$this->db->limit(intval($number));
		}
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_image_apply($id_competition, $number){
	
		$this->db->join('luxyart_tb_user_competition', 'luxyart_tb_user_competition.id_competition = luxyart_tb_competition_img_apply.id_competition','left');
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		if($id_competition == 0){//is_private
			$this->db->where(array('luxyart_tb_user_competition.is_private' => 0,'luxyart_tb_competition_img_apply.result_img' => 0,'luxyart_tb_product_img.img_check_status' => 1,'luxyart_tb_user_competition.date_end_com >= ' => date('Y-m-d')));
			$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		}
		else{
			$this->db->where(array('luxyart_tb_user_competition.is_private' => 0,'luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 0,'luxyart_tb_product_img.img_check_status' => 1,'luxyart_tb_user_competition.date_end_com >= ' => date('Y-m-d')));
			$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		}
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		if($number != ""){
			$this->db->limit(intval($number));
		}
	
		$result = $this->db->get();
		//echo $this->db->last_query();
	
		return $result;
	
	}
	
	public function get_list_image_apply_autorun($id_competition, $number){
	
		$this->db->join('luxyart_tb_user_competition', 'luxyart_tb_user_competition.id_competition = luxyart_tb_competition_img_apply.id_competition','left');
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		if($id_competition == 0){
			$this->db->where(array('luxyart_tb_competition_img_apply.result_img' => 0,'luxyart_tb_product_img.img_check_status' => 1));
			$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		}
		else{
			$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 0,'luxyart_tb_product_img.img_check_status' => 1));
			$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		}
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		if($number != ""){
			$this->db->limit(intval($number));
		}
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_image_apply_autorun_finish($id_competition){
	
		$this->db->join('luxyart_tb_user_competition', 'luxyart_tb_user_competition.id_competition = luxyart_tb_competition_img_apply.id_competition','left');
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 1,'luxyart_tb_product_img.img_check_status' => 1));
		$this->db->where_in('luxyart_tb_user_competition.status_com', array(3));
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_image_apply_scroll($id_competition, $start_limit, $number){
	
	
		$this->db->join('luxyart_tb_user_competition', 'luxyart_tb_user_competition.id_competition = luxyart_tb_competition_img_apply.id_competition','left');
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		if($id_competition == 0){
			$this->db->where(array('luxyart_tb_competition_img_apply.result_img' => 0,'luxyart_tb_product_img.img_check_status' => 1));
			$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		}
		else{
			$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 0,'luxyart_tb_product_img.img_check_status' => 1));
			$this->db->where_in('luxyart_tb_user_competition.status_com', array(1,2));
		}
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		/*if($number != ""){
			$this->db->limit(intval($number));
		}*/
		
		if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));
		}
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_image_apply_paging($id_competition, $number, $offset){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 0));
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		if($number != "" || $offset != ""){
			$this->db->limit(intval($number), intval($offset));
		}
	
		$result = $this->db->get();
		return $result;
	
	}
	
	public function get_search_image_apply_paging($id_competition, $number, $offset, $search_compe_check){
		
		$by_popularity = $search_compe_check["by_popularity"];
		$horizontal_vertical = $search_compe_check["horizontal_vertical"];
		$id_color = $search_compe_check["id_color"];
		$num_page = $search_compe_check["num_page"];
	
		$this->db->join('luxyart_tb_user_competition', 'luxyart_tb_user_competition.id_competition = luxyart_tb_competition_img_apply.id_competition','left');
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		//$this->db->join('luxyart_tb_user_bookmark_image', 'luxyart_tb_user_bookmark_image.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->join('luxyart_tb_product_img_order_detail', 'luxyart_tb_product_img_order_detail.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		//$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload, COUNT(luxyart_tb_user_bookmark_image.id_img) as count_bookmark_image, COUNT(luxyart_tb_product_img_order_detail.id_img) as count_order_detail');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 0));
		//$this->db->group_by('luxyart_tb_product_img.id_img');
		
		//by_popularity
		if($by_popularity == 1){
			//$this->db->order_by('count_bookmark_image', 'desc');
		}
		else if($by_popularity == 1){
			//$this->db->order_by('count_order_detail', 'desc');
		}
		
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
		
		//horizontal_vertical => img_type_com
		if($horizontal_vertical == 0){
			$this->db->where('luxyart_tb_user_competition.img_type_com > ', -1);
		}
		else{
			$this->db->where('luxyart_tb_user_competition.img_type_com', $horizontal_vertical);
		}
		
		//id_color
		if($id_color != ""){
			
			$id_color_explode = explode(",", $id_color);
			$this->db->where_in('luxyart_tb_product_img.id_color', $id_color_explode);
			
		}
	
		if($number != "" || $offset != ""){
			$this->db->limit(intval($number), intval($offset));
		}
	
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		
		return $result;
	
	}
	
	public function get_list_image_apply_choose($id_competition, $id_img_choose){
		
		$id_img_chooses = explode(",", $id_img_choose);
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 0));
		$this->db->where_in("luxyart_tb_product_img.id_img",$id_img_chooses);
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		$result = $this->db->get();
		
		return $result;
	
	}
	
	public function get_list_image_apply_selected($id_competition){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition,'luxyart_tb_competition_img_apply.result_img' => 0,'luxyart_tb_competition_img_apply.is_selected' => 1));
		//$this->db->where_in("luxyart_tb_product_img.id_img",$id_img_chooses);
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_image_apply_selected_autorun($id_competition){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition,'luxyart_tb_competition_img_apply.result_img' => 0,'luxyart_tb_competition_img_apply.is_selected' => 0));
		//$this->db->where_in("luxyart_tb_product_img.id_img",$id_img_chooses);
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_image_apply_normal($id_competition){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition,'luxyart_tb_competition_img_apply.result_img' => 0));
		//$this->db->where_in("luxyart_tb_product_img.id_img",$id_img_chooses);
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_image_apply_add_more($id_competition, $list_img_choose, $num_get){
		
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition,'luxyart_tb_competition_img_apply.result_img' => 0));
		if(!empty($list_img_choose)){
			$this->db->where_not_in("luxyart_tb_product_img.id_img",$list_img_choose);
		}
		$this->db->limit($num_get);
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'asc');
		
		$result = $this->db->get();
		
		return $result;
		
	}
	
	public function get_detail_compe($id_competition){
		
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_user_competition.user_id','left');
		$this->db->join('luxyart_tb_competition_time_agree', 'luxyart_tb_competition_time_agree.id = luxyart_tb_user_competition.competition_time_agree_id','left');
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('luxyart_tb_user_competition.*, luxyart_tb_user.*');
		$this->db->where(array('luxyart_tb_user_competition.id_competition' => $id_competition));
		$result = $this->db->get();
		
		return $result;
	
	}
	
	function get_time_remain_competition($id_competition){
		
		$arr_time_remain = array();
		 
		$this->db->from('luxyart_tb_user_competition');
		$this->db->select('*');
		$this->db->where(array('id_competition' => $id_competition));
		$result = $this->db->get();
		
		$row = $result->row();
		
		$date_active = $row->date_end_com;
		$date_end_com = $row->date_time_agree;
		
		$value_date_active = date_create($date_active);
		$value_end_com = date_create($date_end_com);
		
		$diff = date_diff($value_date_active, $value_end_com);
		
		$arr_time_remain['hour']	 							= $diff->format("%R%a");
		$arr_time_remain['minute']	 							= $diff->format("%h");
		
		return $arr_time_remain;
		
	}
	
	function get_diff_date_begin_date_end($date_begin, $date_end){
		
		$time_ago = strtotime($date_begin);
		$cur_time   = strtotime($date_end);
		
		$time_elapsed   = $cur_time - $time_ago;
		
		$seconds    = $time_elapsed ;
		 
		$minutes    = round($time_elapsed / 60 );
		$hours      = round($time_elapsed / 3600);
		$days       = round($time_elapsed / 86400 );
		$weeks      = round($time_elapsed / 604800);
		$months     = round($time_elapsed / 2600640 );
		$years      = round($time_elapsed / 31207680 );
		 
		// Seconds
		if($seconds <= 60){
			return "Hết hạn";
		}
		 
		//Minutes
		else if($minutes <=60){
			if($minutes==1){
				return "1 phút";
			}
			else{
				return "$minutes phút";
			}
		}
			 
		//Hours
		else if($hours <=24){
			if($hours==1){
				return "1 giờ";
			}
			else{
				return "$hours giờ";
			}
		}
		
		//Days
		else{
			if($days==1){
				return "1 ngày";
			}
			else{
				return "$days ngày";
			}
		}
		
	}
	
	function get_time_remain_apply($date_end){
	
		$time_ago = strtotime($date_end." 23:59:59");
		$cur_time   = strtotime(date('Y-m-d H:i:s'));
	
		$time_elapsed   = $time_ago - $cur_time;
		
		$seconds    = $time_elapsed ;
			
		$minutes    = round($time_elapsed / 60 );
		$hours      = round($time_elapsed / 3600);
		$days       = round($time_elapsed / 86400 );
		$weeks      = round($time_elapsed / 604800);
		$months     = round($time_elapsed / 2600640 );
		$years      = round($time_elapsed / 31207680 );
			
		// Seconds
		if($seconds <= 60){
			return $this->lang->line('limited_of_time_expired');
		}
			
		//Minutes
		else if($minutes <=60){
			if($minutes==1){
				return "1 ".$this->lang->line('minute');
			}
			else{
				return "$minutes ".$this->lang->line('minute');
			}
		}
	
		//Hours
		else if($hours <=24){
		if($hours==1){
		return "1 ".$this->lang->line('hour');
		}
			else{
		return "$hours ".$this->lang->line('hour');
		}
		}
	
		//Days
		else{
		if($days==1){
		return "1 ".$this->lang->line('day');
		}
		else{
			return "$days ".$this->lang->line('day');
		}
		}
	
	}
	
	public function get_list_image_applied_finish($id_competition){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server','left');
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('luxyart_tb_competition_img_apply.*,luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_competition_img_apply.id_competition' => $id_competition, 'luxyart_tb_competition_img_apply.result_img' => 1));
		$this->db->order_by('luxyart_tb_competition_img_apply.id_apply', 'desc');
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_all_user_id_apply_competition($id_competition){
		
		$user_ids = array();
		
		$this->db->from('luxyart_tb_competition_img_apply');
		$this->db->select('user_id');
		$this->db->where(array('id_competition' => $id_competition));
		$this->db->group_by('user_id');
		
		$rs = $this->db->get();
		$result = $rs->result();
		
		foreach($result as $_result){
			array_push($user_ids, $_result);
		}
		
		return $user_ids;
		
	}
	
}