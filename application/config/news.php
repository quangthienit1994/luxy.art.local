<?php
 if(!isset($_SESSION))
{
	session_start();
}
if(!isset($_SESSION['lang']))
{
	$_SESSION['lang'] = 'english';
}
?>

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class News extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('main_model');
		
		$login_id = $this->session->userdata('users_private_id');
		$swich_status = $this->session->userdata('swich_status');
		
		$this->load->helper('file');
		
		//language message
		$CI =& get_instance();    //do this only once in this file
		$CI->config->load();
		if(!isset($_SESSION['lang'])){
			$_SESSION['lang'] = 'english';
		}
		$config['language'] = $_SESSION['lang'];
		$ngonngu = $config['language'];
		$this->lang->load('dich', $ngonngu);
		
		//language send mail
		if($_SESSION['lang'] == "english"){
			$this->lang_mail = "_en";
		}
		elseif($_SESSION['lang'] == "janpan"){
			$this->lang_mail = "";
		}
		else{
			$this->lang_mail = "_en";
		}

	}
	
	/**
	 * List News Function
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-07-17
	 */
	public function list_news() {
		
		$this->load->library('pagination');
		
		$whereNews = array('news.news_status' => 1);
		$orderNews = array('news.news_name' => 'asc');
		
		$config['total_rows'] = count($this->main_model->getAllDataDivPage("news", $whereNews, $orderNews, '', '')->result());
		
		$config['per_page'] = 1;
		$config['uri_segment'] = 2;
		$config['base_url'] = base_url('list_news');
		$this->pagination->initialize($config);
			
		$data['news'] = $this->main_model->getAllDataDivPage("news", $whereNews, $orderNews, $config['per_page'], $this->uri->segment(2))->result(); 
		
		$data['phantrang'] = $this->pagination->create_links();
		
		$data['title_page'] = $this->lang->line('title_list_news');
		$data['description_page'] = $this->lang->line('description_list_news');
		$data['keywords'] = $this->lang->line('keyword_list_news');
		
		$data['menu'] = 5;
		
		$this->template->load('default', 'list_news', $data, __CLASS__);

	}
	
	/**
	 * Detail News Function
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 * @author 	dohuuthien 2015-07-17
	 */
	public function detail_news($news_id){

		$whereNews = array('news.news_id' => $news_id, 'news.news_status' => 1);
		$orderNews = array();
		
		$data['detail_news'] = $this->main_model->getAllData("news", $whereNews, $orderNews)->row();
		
		//get 5 news related
		$whereNewsRelated = array('news.news_status ' => 1, 'news.news_id != ' => $news_id);
		$orderNewsRelated = array('news.news_id' => 'desc');
		$data['news_related'] = $this->main_model->getAllDataDivPage("news", $whereNewsRelated, $orderNewsRelated, 5, 0)->result();
		
		$data['title_page'] = $this->lang->line('title_detail_news');
		$data['description_page'] = $this->lang->line('description_detail_news');
		$data['keywords'] = $this->lang->line('keyword_detail_news');
		
		$data['menu'] = 5;
		
		$this->template->load('default', 'detail_news', $data, __CLASS__);	
	
	}
	 
}