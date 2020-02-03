<?php
class Shopping_model extends CI_Model {

    function __construct()
        {
            parent::__construct();
        }     
        
    function insert_thanhtoan($data)
        {
            $this->db->insert('khachhang', $data);
            $id = $this->db->insert_id();
            return (isset($id)) ? $id : FALSE;
        }
    
    function insert_order($data)
        {
            $this->db->insert('orders', $data);
            $id = $this->db->insert_id();
            return (isset($id)) ? $id : FALSE;
        }
    
    function insert_order_detail($data)
        {
            $this->db->insert('order_detail', $data);
        }

	function get_data_add_cart($id_img_size){
		
		$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img_size.id_img = luxyart_tb_product_img.id_img','left');
		$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
		$this->db->join('luxyart_tb_user', 'luxyart_tb_product_img.user_id = luxyart_tb_user.user_id','left');
		$this->db->from('luxyart_tb_product_img_size');
		$this->db->select('luxyart_tb_product_img_size.*, luxyart_tb_product_img.*, luxyart_tb_manager_server.server_path_upload, luxyart_tb_user.*');
		$this->db->where(array('luxyart_tb_product_img_size.id_img_size' => $id_img_size));
		$result = $this->db->get();
		return $result;
		
	}
	
	function get_max_id_img_size($id_img){
		
		$this->db->from('luxyart_tb_product_img_size');
		$this->db->select('id_img_size');
		$this->db->where(array('id_img' => $id_img, 'status_img_size' => 0));
		$this->db->order_by("type_size desc");
		$this->db->limit(1);
		$rs = $this->db->get();
		
		if(!empty($rs)){
			$row = $rs->row();
			return $row->id_img_size;
		}
		else{
			return 0;
		}
		
	}
	
	function check_image_bought($id_img,$id_img_size,$user_id){
	
		$this->db->from('luxyart_tb_product_img_order_detail');
		$this->db->join('luxyart_tb_product_img_order', 'luxyart_tb_product_img_order.id_order_img = luxyart_tb_product_img_order_detail.id_order_img','left');
		$this->db->select('luxyart_tb_product_img_order_detail.id_or_detail');
		$this->db->where(array('luxyart_tb_product_img_order_detail.id_img' => $id_img, 'luxyart_tb_product_img_order_detail.id_img_size' => $id_img_size, 'luxyart_tb_product_img_order.user_buy_img' => $user_id));
		$rs = $this->db->get();
	
		$row = $rs->row();
		return count($row);
	
	}
        
} 
?>