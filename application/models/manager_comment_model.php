<?php
/**
Model call function use for manager user
*/
class Manager_comment_model extends CI_Model {
 		
 	function __contruction(){
		parent::__contruction();
   	}
   	
	public function get_list_comment($id_img){

		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*');
		$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.status' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
		$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
		
		$result = $this->db->get();
		return $result;
		
	}
	
	public function get_list_comment_by_level($id_img, $level, $user_id){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
		if($level > 0){
			if($user_id == ""){
				$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.level' => $level, 'luxyart_tb_comment.status' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
			}
			else{
				$this->db->where("`luxyart_tb_product_img`.`id_img` = '".$id_img."' AND `luxyart_tb_comment`.`level` = ".$level." AND (`luxyart_tb_comment`.`status` = 1 OR (`luxyart_tb_comment`.`status` = 0 AND `luxyart_tb_comment`.`user_id` = ".$user_id.")) AND `luxyart_tb_product_img`.`img_check_status` != 4");
			}
		}
		$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
	
		$result = $this->db->get();
		return $result;
	
	}
	
	public function get_list_comment_by_level_paging($id_img, $level, $user_id, $start_limit, $number){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
		if($level > 0){
			if($user_id == ""){
				$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.level' => $level, 'luxyart_tb_comment.status' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
			}
			else{
				$this->db->where("`luxyart_tb_product_img`.`id_img` = '".$id_img."' AND `luxyart_tb_comment`.`level` = ".$level." AND (`luxyart_tb_comment`.`status` = 1 OR (`luxyart_tb_comment`.`status` = 0 AND `luxyart_tb_comment`.`user_id` = ".$user_id.")) AND `luxyart_tb_product_img`.`img_check_status` != 4");
			}
		}
		$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
		
		if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));
		}
	
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		
		return $result;
	
	}
	
	public function get_list_comment_by_level_sub($comment_id){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
		if($comment_id > 0){
			$this->db->where(array('luxyart_tb_comment.comment_id_parent' => $comment_id, 'luxyart_tb_comment.level' => 2, 'luxyart_tb_comment.status' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
		}
		$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
	
		$result = $this->db->get();
		return $result;
	
	}
	
	public function get_list_comment_by_level_sub_paging($comment_id, $user_id, $start_limit, $number){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
		if($comment_id > 0){
			if($user_id == ""){
				$this->db->where(array('luxyart_tb_comment.comment_id_parent' => $comment_id, 'luxyart_tb_comment.level' => 2, 'luxyart_tb_comment.status' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
			}
			else{
				$this->db->where("`luxyart_tb_comment`.`comment_id_parent` = '".$comment_id."' AND `luxyart_tb_comment`.`level` = 2 AND (`luxyart_tb_comment`.`status` = 1 OR (`luxyart_tb_comment`.`status` = 0 AND `luxyart_tb_product_img`.`user_id` = ".$user_id.")) AND `luxyart_tb_product_img`.`img_check_status` != 4");
			}
		}
		$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
	
		if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));
		}
		
		$result = $this->db->get();
		return $result;
	
	}
	
	public function get_detail_comment($comment_id){
	
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*, luxyart_tb_user.*');
		$this->db->where(array('luxyart_tb_comment.comment_id' => $comment_id));
	
		$result = $this->db->get();
	
		return $result;
	
	}
	
	public function get_list_manager_comment($user_id){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_manager_server.server_path_upload');
		$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_comment.status >=' => 0));
		$this->db->order_by('luxyart_tb_comment.id_img', 'desc');
		$this->db->group_by('luxyart_tb_comment.id_img');
	
		$result = $this->db->get();
		return $result;
	
	}
	
	public function get_manager_list_comment_by_level_paging($id_img, $level, $user_id, $start_limit, $number){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
		if($level > 0){
			$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.level' => $level, 'luxyart_tb_product_img.img_check_status != ' => 4));
			//$this->db->or_where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.level' => $level, 'luxyart_tb_comment.status ' => 0, 'luxyart_tb_comment.user_id' => $user_id, 'luxyart_tb_product_img.img_check_status != ' => 4));
		}
		$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
	
		if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));
		}
	
		$result = $this->db->get();
		
		return $result;
	
	}
	
	public function get_manager_comment_by_level_sub_paging($comment_id, $start_limit, $number){
	
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
		$this->db->from('luxyart_tb_comment');
		$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
		if($comment_id > 0){
			$this->db->where(array('luxyart_tb_comment.comment_id_parent' => $comment_id, 'luxyart_tb_comment.level' => 2, 'luxyart_tb_comment.status >= ' => 0, 'luxyart_tb_product_img.img_check_status != ' => 4));
		}
		$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
	
		if($start_limit != "" || $number != ""){
			$this->db->limit(intval($start_limit), intval($number));
		}
	
		$result = $this->db->get();
		return $result;
	
	}
	
	/**
	 * List Search Comment
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2018-05-22
	 */
	function list_search_comment($number,$offset,$data){
	
		$this->db->select('luxyart_tb_comment.*');
		$this->db->order_by('luxyart_tb_comment.comment_id', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchComment']['comment'] != ""){
				$this->db->like('comment', $data['searchComment']['comment']);
			}
		}
	
		$query = $this->db->get('luxyart_tb_comment');
		return $query;
	
	}
	
}