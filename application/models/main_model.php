<?php
/**
 * Reverse bidding system Account_model Class
 *
 * Handles Account information in database.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Settings 
 * @author		Flps Dev Team
 * @version		Version 1.0
 * @link		http://www.freelancephpscript.com
 
  <Reverse bidding system> 
    Copyright (C) <2009>  <Freelance PHP Script>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
    If you want more information, please email me at sabrina@freelancephpscript.com or 
    contact us from http://www.freelancephpscript.com/contact 

 
 */
 	class Main_model extends CI_Model {
 		
 	/**
 	 * Constructor
 	 * 
 	 */

 	function __contruction(){
		parent::__contruction();
   	}//Controller End

   	/**
   	 * Get All Data
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2014-10-02
   	 */
   	function getAllData($table, $where=array(), $order=array()){
   	
   		$data = array();
   	
   		if(count($where)>0)
   			$this->db->where($where);
   		 
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   		 
   		$re = $this->db->get($table);
   		 
   		//echo $this->db->last_query();
   		 
   		return $re;
   	
   	}
   	
   	/**
   	 * Get All Data
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2014-10-02
   	 */
   	function getAllDataLimit($table, $where=array(), $order=array(), $number, $offset){
   	
   		$data = array();
   	
   		if(count($where)>0)
   			$this->db->where($where);
   	
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   		 
   		if($number != "" || $offset != "")
   			$this->db->limit(intval($number), intval($offset));
   	
   		$re = $this->db->get($table);
   	
   		//echo $this->db->last_query();
   	
   		return $re;
   	
   	}
   	
   	/**
   	 * Get All Data Like
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2015-07-23
   	 */
   	function getAllDataLike($table, $where = array(), $order=array(), $like=array(), $number, $offset){
   			
   		if(count($where)>0)
   			$this->db->where($where);
   			
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   			
   		if($like){
   			$this->db->like($like);
   		}
   			
   		if($number != "" || $offset != "")
   			$this->db->limit(intval($number), intval($offset));
   			
   		$this->db->from($table);
   		$this->db->select('*');
   		$result = $this->db->get();
   			
   		//echo $this->db->last_query();
   			
   		return $result;
   		 
   	}
   	
   	/**
   	 * Get All Data Like And Or Like
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2015-11-18
   	 */
   	function getAllDataLikeOrLike($table, $where = array(), $order=array(), $like=array(), $or_like=array(), $number, $offset){
   	
   		if(count($where)>0)
   			$this->db->where($where);
   	
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   	
   		if($like){
   			$this->db->like($like);
   		}
   		
   		if($or_like){
   			$this->db->or_like($or_like);
   		}
   	
   		if($number != "" || $offset != "")
   			$this->db->limit(intval($number), intval($offset));
   	
   		$this->db->from($table);
   		$this->db->select('*');
   		$result = $this->db->get();
   	
   		//echo $this->db->last_query();
   	
   		return $result;
   	
   	}
   	
   	/**
   	 * Get All Data Div Page
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2014-10-02
   	 */
   	function getAllDataDivPage($table, $where=array(), $order=array(), $number, $offset){
   	
   		$data = array();
   	
   		if(count($where)>0)
   			$this->db->where($where);
   	
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   		 
   		if($number != "" || $offset != "")
   			$this->db->limit(intval($number), intval($offset));
   	
   		$re = $this->db->get($table);
   		 
   		//echo $this->db->last_query();
   	
   		return $re;
   	
   	}
   	
   	/**
   	 * Get All Data Extends
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2014-10-02
   	 */
   	function getAllDataExtends($table, $where=array(), $order=array(), $join=array(), $col_where_in, $where_in=array(), $having){
   	
   		$data = array();
   	
   		if(count($join)>0){
   			foreach ($join as $key_join => $value_join){
   				$this->db->join($key_join, $value_join);
   			}
   		}
   		 
   		if(count($where)>0)
   			$this->db->where($where);
   	
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   		 
   		if(count($where_in)>0)
   			$this->db->where_in($col_where_in, $where_in);
   	
   		if(count($having)>0)
   			$this->db->having($having);
   	
   		$re = $this->db->get($table);
   		 
   		//echo $this->db->last_query();
   	
   		return $re;
   	
   	}
   	
   	/**
   	 * Get All Data Extends
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2014-10-02
   	 */
   	function getAllDataDivPageExtends($table, $where=array(), $order=array(), $join=array(), $col_where_in, $where_in=array(), $having, $number, $offset){
   		 
   		$data = array();
   	
   		if(count($join)>0){
   			foreach ($join as $key_join => $value_join){
   				$this->db->join($key_join, $value_join);
   			}
   		}
   		 
   		if(count($where)>0)
   			$this->db->where($where);
   	
   		if(count($order)>0){
   			foreach ($order as $key_order => $value_order){
   				$this->db->order_by($key_order, $value_order);
   			}
   		}
   		 
   		if(count($where_in)>0)
   			$this->db->where_in($col_where_in, $where_in);
   	
   		if(count($having)>0)
   			$this->db->having($having);
   	
   		if($number != "" || $offset != "")
   			$this->db->limit(intval($number), intval($offset));
   	
   		$re = $this->db->get($table);
   		 
   		//echo $this->db->last_query();
   	
   		return $re;}
   	
   		/**
   		 * Get All Data Div Page Muti Extends
   		 *
   		 * @access	public
   		 * @param	nil
   		 * @return	void
   		 * @author	dohuuthien 2014-10-02
   		 */
   		function getAllDataMutiExtends($table, $where=array(), $order=array(), $join=array(), $col_where_in, $where_in=array(), $having){
   	
   			//write code
   	
   		}
   	
   		/**
   		 * Get All Data Muti Extends
   		 *
   		 * @access	public
   		 * @param	nil
   		 * @return	void
   		 * @author	dohuuthien 2014-10-02
   		 */
   		function getAllDataDivPageMutiExtends($table, $where=array(), $order=array(), $join=array(), $col_where_in, $where_in=array(), $having, $number, $offset){
   	
   			//write code
   	
   		}
   	
   		/**
   		 * Insert Data
   		 *
   		 * @access	public
   		 * @param	nil
   		 * @return	void
   		 * @author	dohuuthien 2014-10-02
   		 */
   		function insertData($table, $insertData=array()){
   	
   			$this->db->insert($table, $insertData);
   			//echo $this->db->last_query();
   			return $this->db->insert_id();
   	
   		}
   	
   		/**
   		 * Update Data
   		 *
   		 * @access	public
   		 * @param	nil
   		 * @return	void
   		 * @author	dohuuthien 2014-10-02
   		 */
   		function updateData($table, $updateData=array(), $updateKey=array()){
   	
   			$this->db->update($table, $updateData, $updateKey);
   			return $this->db->affected_rows();
   			 
   		}
   	
   		/**
   		 * Delete Data
   		 *
   		 * @access	public
   		 * @param	nil
   		 * @return	void
   		 * @author	dohuuthien 2014-10-02
   		 */
   		function deleteData($table, $cond){
   	
   			$this->db->delete($table, $cond);
   			return $this->db->affected_rows();
   	
   		}
		
   		/**
   		 * Set Admin Session
   		 *
   		 * @access	public
   		 * @param	nil
   		 * @return	void
   		 * @author	?
   		 */
   		function setAdminSession($conditions=array()){
   			
   			if(count($conditions)>0)
   				$this->db->where($conditions);
   		
   			$this->db->select('luxyart_tb_admin.admin_id,luxyart_tb_admin.admin_name');
   			$result = $this->db->get('luxyart_tb_admin');
   			
   			if($result->num_rows()>0){
   				
   				$row = $result->row();
   				$values = array ('admin_id'=>$row->admin_id,'logged_in_admin'=>TRUE,'admin_role'=>'admin','admin_status'=>1);
   				$this->session->set_userdata($values);
   				
   			}	
   		}
   		
   		/**
   		* Set Style for the flash messages in admin section
   		*
   		* @access	public
   		* @param	string	the type of the flash message
   		* @param	string  flash message
   		* @return	string	flash message with proper style
   		*/
   		
   		function admin_flash_message($type,$message){
   		
   			switch($type){
   		
   				case 'success':
   					$data = '<div class="message"><div class="success">'.$message.'</div></div>';
   					break;
   		
   				case 'error':
   					$data = '<div class="message"><div class="error">'.$message.'</div></div>';
   					break;
   		
   			}
   		
   			return $data;
   		
   		}
	 
}
// End Main_model Class

/* End of file Main.php */
/* Location: ./app/models/Main.php */