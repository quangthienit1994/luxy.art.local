<?php
class Admin_model extends CI_Model {
	 
   /**
	* Constructor 
	*
	*/

	function __construct(){
		
	  	parent::__construct();
		
  	}//Controller End
      
 	/**
     * Get Admins
     *
     * @access	private
     * @param	array	conditions to fetch data
     * @return	object	object with result set
     */
  	function getAdmins($conditions=array()){
  		
      	$default = $this->load->database('default', TRUE);
      	if(count($conditions)>0)
      		$default->where($conditions);
      	$default->from('fl_admin');
      	$default->select('*');
      	$result = $default->get();
      	return $result;
  	}//End Of Get Admins Function
      
	function count_programmers(){
		$default = $this->load->database('default',TRUE);
		$default->select('count(*) as totals');
		$default->from('fl_users_private');
		$default->where('swich_status',2);	   
		$query = $default->get();
		return $query->row()->totals;
	}
	
	function count_buyer(){
		
		$default = $this->load->database('default',TRUE);
		$default->select('count(*) as totals');
		$default->from('fl_users_private');
		$default->where('swich_status',1);	   
		$query = $default->get();
		return $query->row()->totals;
	}
	
	function count_project_today_post(){
		
		$day = date("Y-m-d");
		$default = $this->load->database('default',TRUE);
		$default->select('count(*) as totals');
		$default->from('fl_project');
		$default->where("DATE_FORMAT(`project_date_create` , '%Y-%m-%d' ) =  '".$day."'");	
		$query = $default->get();
		return $query->row()->totals;
	}
	
	function count_project_week_post(){
		
		$day = date("Y-m-d");
		$week = date("Y-m-d",strtotime("-1 week"));
		$default = $this->load->database('default',TRUE);
		$default->select('count(*) as totals');
		$default->from('fl_project');
		$default->where("DATE_FORMAT(`project_date_create` , '%Y-%m-%d' ) between  '".$week."' and '".$day."'");	
		$query = $default->get();
		return $query->row()->totals;
	}
	
	function count_project_month_post(){
		
		$day = date("Y-m-d");
		$month = date("Y-m-d",strtotime("-1 month"));
		$default = $this->load->database('default',TRUE);
		$default->select('count(*) as totals');
		$default->from('fl_project');
		$default->where("DATE_FORMAT(`project_date_create` , '%Y-%m-%d' ) between  '".$month."' and '".$day."'");	
		$query = $default->get();
		return $query->row()->totals;
	}
	
	function count_project_year_post(){
		
		$day = date("Y-m-d");
		$year = date("Y-m-d",strtotime("-1 year"));
		$default = $this->load->database('default',TRUE);
		$default->select('count(*) as totals');
		$default->from('fl_project');
		$default->where("DATE_FORMAT(`project_date_create` , '%Y-%m-%d' ) between  '".$year."' and '".$day."'");	
		$query = $default->get();
		return $query->row()->totals;
	}
	
	function count_open_projects(){
		
		$default = $this->load->database('default',TRUE);
		$default->select('count(*) as totals');
		$default->from('fl_project');
		$default->where('project_status >=',2);	
		$query = $default->get();
		return $query->row()->totals;
	}
	
	function count_closed_projects(){
		
		$default = $this->load->database('default',TRUE);
		$default->select('count(*) as totals');
		$default->from('fl_project');
		$default->where("project_status",1);	
		$query = $default->get();
		return $query->row()->totals;
	}
	
	/**
	 * clearSession
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-24
	 */
	function clearAdminSession(){
		
		$array_items = array ('admin_id' => '','logged_in_admin'=>'','admin_role'=>'', 'admin_status'=>'', 'role'=>'');
		$this->session->unset_userdata($array_items);
	
	}
	
	/**
	 * Admin Flash Message
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-24
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
	
	/**
	 * List Product Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2017-04-01
	 */
	function list_product_search($number , $offset, $data){
	
		$this->db->select('product.*');
	
		$this->db->order_by('product.product_id', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchProduct']['product_status'] != -1){
				$this->db->where('product.product_status', $data['searchProduct']['product_status']);
			}
		}
	
		if(!empty($data)){
			if($data['searchProduct']['product_name'] != ""){
				$this->db->like('product_name', $data['searchProduct']['product_name']);
			}
		}
	
		$query = $this->db->get('product');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List School Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-12
	 */
	function list_school_search($number , $offset, $data){
		
		$this->db->select('school.*');
		
		$this->db->order_by('school.school_id', 'DESC');
		
		if($number != ""){
			$this->db->limit($number,$offset);
		}
		
		if(!empty($data)){
			if($data['searchSchool']['school_status'] != -1){
				$this->db->where('school.school_status', $data['searchSchool']['school_status']);
			}
		}
		
		if(!empty($data)){
			if($data['searchSchool']['school_name'] != ""){
				$this->db->like('school_name', $data['searchSchool']['school_name']);
			}
		}
		
		$query = $this->db->get('school');
		
		//echo $this->db->last_query();
		
		return $query;
		
	}
	
	/**
	 * List Class Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-12
	 */
	function list_class_search($number , $offset, $data){
	
		$this->db->select('class.*');
	
		$this->db->order_by('class.class_id', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchClass']['class_status'] != -1){
				$this->db->where('class.class_status', $data['searchClass']['class_status']);
			}
		}
	
		if(!empty($data)){
			if($data['searchClass']['class_name'] != ""){
				$this->db->like('class_name', $data['searchClass']['class_name']);
			}
		}
	
		$query = $this->db->get('class');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List Booking Trial Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-12
	 */
	function list_trial_lesson_search($number , $offset, $data){
	
		$this->db->select('trial_lesson.*');
	
		$this->db->order_by('trial_lesson.trial_lesson_id', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchTrialLesson']['trial_lesson_status'] != -1){
				$this->db->where('trial_lesson.trial_lesson_status', $data['searchTrialLesson']['trial_lesson_status']);
			}
		}
	
		if(!empty($data)){
			if($data['searchTrialLesson']['trial_lesson_name'] != ""){
				$this->db->like('trial_lesson_name', $data['searchTrialLesson']['trial_lesson_name']);
			}
		}
	
		$query = $this->db->get('trial_lesson');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List User Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-20
	 */
	function list_user_search($number , $offset, $data){
	
		$this->db->select('user.*');
	
		$this->db->order_by('user.user_id', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchUser']['user_status'] != -1){
				$this->db->where('user.user_status', $data['searchUser']['user_status']);
			}
		}
	
		if(!empty($data)){
			if($data['searchUser']['user_name'] != ""){
				$this->db->like('user_name', $data['searchUser']['user_name']);
			}
		}
	
		$query = $this->db->get('user');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List User Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-20
	 */
	function list_user_extension_search($number , $offset, $data){
	
		$this->db->select('user_extension.*');
	
		$this->db->order_by('user_extension.date_payment', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchUserExtension']['date_payment'] != ""){
				$this->db->where('user_extension.date_payment', $data['searchUserExtension']['date_payment']);
			}
		}
		
		$this->db->where('user_extension.payment_status', 1);
	
		$query = $this->db->get('user_extension');
	
		return $query;
	
	}
	
	/**
	 * List Group Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-21
	 */
	function list_group_search($number , $offset, $data){
	
		$this->db->select('group.*');
	
		$this->db->order_by('group.group_id', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchGroup']['group_status'] != -1){
				$this->db->where('group.group_status', $data['searchGroup']['group_status']);
			}
		}
	
		if(!empty($data)){
			if($data['searchGroup']['group_name'] != ""){
				$this->db->like('group_name', $data['searchGroup']['group_name']);
			}
		}
	
		$query = $this->db->get('group');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List Category Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-21
	 */
	function list_category_search($number , $offset, $data){
	
		$this->db->select('category.*');
	
		$this->db->order_by('category.category_id', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchCategory']['category_status'] != -1){
				$this->db->where('category.category_status', $data['searchCategory']['category_status']);
			}
		}
	
		if(!empty($data)){
			if($data['searchCategory']['category_name'] != ""){
				$this->db->like('category_name', $data['searchCategory']['category_name']);
			}
		}
	
		$query = $this->db->get('category');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List City Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-21
	 */
	function list_city_search($number , $offset, $data){
	
		$this->db->select('city.*');
	
		$this->db->order_by('city.city_name', 'ASC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchCity']['city_name'] != ""){
				$this->db->like('city_name', $data['searchCity']['city_name']);
			}
		}
	
		$query = $this->db->get('city');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List District Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-21
	 */
	function list_district_search($number , $offset, $data){
	
		$this->db->select('district.*');
	
		$this->db->order_by('district.district_name', 'ASC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchDistrict']['city_id'] != -1){
				$this->db->where('district.city_id', $data['searchDistrict']['city_id']);
			}
		}
	
		if(!empty($data)){
			if($data['searchDistrict']['district_name'] != ""){
				$this->db->like('district_name', $data['searchDistrict']['district_name']);
			}
		}
	
		$query = $this->db->get('district');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List Email Templates Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-21
	 */
	function list_email_templates_search($number , $offset, $data){
	
		$this->db->select('email_templates.*');
	
		$this->db->order_by('email_templates.email_templates_title', 'ASC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
	
		if(!empty($data)){
			if($data['searchEmailTemplates']['email_templates_title'] != ""){
				$this->db->like('email_templates_title', $data['searchEmailTemplates']['email_templates_title']);
			}
		}
	
		$query = $this->db->get('email_templates');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	
	/**
	 * List News Search
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-08-24
	 */
	function list_news_search($number , $offset, $data){
	
		$this->db->select('news.*');
	
		$this->db->order_by('news.news_id', 'DESC');
	
		if($number != ""){
			$this->db->limit($number,$offset);
		}
		
		if(!empty($data)){
			if($data['searchNews']['news_status'] != -1){
				$this->db->where('news.news_status', $data['searchNews']['news_status']);
			}
		}
	
		if(!empty($data)){
			if($data['searchNews']['news_type_id'] != -1){
				$this->db->where('news.news_type_id', $data['searchNews']['news_type_id']);
			}
		}
	
		if(!empty($data)){
			if($data['searchNews']['news_name'] != ""){
				$this->db->like('news_name', $data['searchNews']['news_name']);
			}
		}
	
		$query = $this->db->get('news');
	
		//echo $this->db->last_query();
	
		return $query;
	
	}
	 
}
// End Skills_model Class
   
/* End of file Skills_model.php */ 
/* Location: ./app/models/Skills_model.php */
?>