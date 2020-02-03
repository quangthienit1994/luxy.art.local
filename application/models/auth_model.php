<?php
class Auth_model extends CI_Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function __contruction() 
	  {
		parent::__contruction();
				
      }//Controller End
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function loginAsAdmin($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('fl_admin.admin_id');
		$result = $this->db->get('fl_admin');
		if($result->num_rows()>0)
			return true;
		else 
			return false;	
	 }//End of loginAsAdmin Function
	 
 	// --------------------------------------------------------------------
 	
	 
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function setAdminSession($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('fl_admin.admin_id,fl_admin.admin_name');
		$result = $this->db->get('fl_admin');
		if($result->num_rows()>0)
		{
			$row = $result->row();
			$values = array ('admin_id'=>$row->admin_id,'logged_in'=>TRUE,'admin_role'=>'admin','admin_status'=>'admin', 'role'=>0); 
			$this->session->set_userdata($values);
		}
		
	 }//End of setAdminSession Function
	 
	 function setAdminUserSession($conditions=array())
	 {
	 	if(count($conditions)>0)
	 		$this->db->where($conditions);
	 
	 	$this->db->select('fl_user_admin.admin_user_id,fl_user_admin.admin_user_name');
	 	$result = $this->db->get('fl_user_admin');
	 	
	 	if($result->num_rows()>0)
	 	{
	 		$row = $result->row();
	 		
	 		$conditionUserAdmins = array('fl_user_admin.admin_user_id'=>$row->admin_user_id, 'fl_user_admin.admin_user_status'=>1);
	 		$data['userAdmins'] = $this->account_model->getUserAdmin($conditionUserAdmins)->row();
	 		$values = array ('admin_id'=>$row->admin_user_id,'logged_in'=>TRUE,'admin_role'=>'admin','admin_status'=>'admin_user', 'role'=>$data['userAdmins']->admin_user_role_id);
	 		$this->session->set_userdata($values);
	 	}
	 
	 }//End of setAdminSession Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 * @author	dohuuthien 2013-09-13
	 */
	 function setUserSession($row=NULL)
	 {
	 	switch($row->swich_status)
		{
			case '1':
			  	$values = array('users_private_id'=>$row->users_private_id,'logged_in'=>TRUE,'swich_status'=>'1');
				$this->session->set_userdata($values);
				break;
				
			case '2':
			
				$values = array('users_private_id'=>$row->users_private_id,'logged_in'=>TRUE,'swich_status'=>'2');
				$this->session->set_userdata($values);
				break;	
		}
	 	
	 }//End of setUserSession Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  * Get Users
	  *
	  * @access	private
	  * @param	array	conditions to fetch data
	  * @return	object	object with result set
	  */
	 function setUserSession_old($row=NULL)
	 {
	 	switch($row->role)
	 	{
	 		case 'buyer':
	 				
	 			$values = array('user_id'=>$row->id,'logged_in'=>TRUE,'role'=>'buyer');
	 			$this->session->set_userdata($values);
	 			break;
	 
	 		case 'programmer':
	 				
	 			$values = array('user_id'=>$row->id,'logged_in'=>TRUE,'role'=>'programmer');
	 			$this->session->set_userdata($values);
	 			break;
	 	}
	 	 
	 }//End of setUserSession Function
	 
	 // --------------------------------------------------------------------
	 
	 // Puhal Changes Start Function added for the Remenber me option  (Sep 17 Issue 3)	
	 
	  function setUserCookie($name='',$value ='',$expire = '',$domain='',$path = '/',$prefix ='')
	 {
	 		 $cookie = array(
                   'name'   =>$name,
                   'value'  => $value,
                   'expire' => $expire,
                   'domain' => $domain,
                   'path'   => $path,
                   'prefix' => $prefix,
               );
			  set_cookie($cookie); 
	 }//End of setUserCookie Function	

		
		
	 function getUserCookie($name='')
	 {
		 $val=get_cookie($name,TRUE); 
		return $val;
	 }//End of getUserCookie Function		
	 
 
	  function clearUserCookie($name=array())
	 {
	 	foreach($name as $val)
		{
			delete_cookie($val);
		}	
	 }//End of clearSession Function*/
	 
// Puhal Changes End Function added for the Remenber me option  (Sep 17 Issue 3)		 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	// --------------------------------------------------------------------
		
	/**
	 * clearSession
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function clearAdminSession(){	
	 	$array_items = array ('admin_id' => '','logged_in_admin'=>'','admin_role'=>'', 'admin_status'=>'', 'role'=>'');
	    $this->session->unset_userdata($array_items);
		
	 }//End of clearSession Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * clearUserSession
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function clearUserSession()
	 {
	 	$array_items = array('user_id' => '','logged_in'=>'','role'=>'');
		$this->session->unset_userdata($array_items);
		$this->session->unset_userdata('users_private_id');
		$config = array(
						'appId'  => '445038932250139',
						'secret' => '820d1e0420382da8aede15c812beaac2',
						'fileUpload' => true, // Indicates if the CURL based @ syntax for file uploads is enabled.
						);
		
		$this->load->library('Facebook', $config);
		$this->facebook->destroySession();
		 $this->session->sess_destroy(); 
		
	 }//End of clearSession Function
	 
}
// End Auth_model Class
   
/* End of file Auth_model.php */ 
/* Location: ./app/models/Auth_model.php */