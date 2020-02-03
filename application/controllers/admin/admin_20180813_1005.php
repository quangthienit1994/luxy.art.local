<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Admin {

	private $data = [];
    public function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
		$this->load->model('main_model');
		$this->load->model('admin_model');
		$this->load->model('function_model');
		$this->load->model('manager_image_model');
		$this->load->model('manager_comment_model');
		$this->load->model('manager_user_model');
        $this->load->library('session');
    	$this->load->library('form_validation');
    	$this->load->helper('form');
    	$this->load->helper('functions');
		$this->config->load('config', TRUE);
		$this->config->set_item('uri_protocol', 'REQUEST_URI');
		$this->config->set_item('permitted_uri_chars', "a-z 0-9~%.:_\-?");
		$this->config->set_item('enable_query_strings', TRUE);

        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        
        $CI =& get_instance();
        $CI->config->load();
        $this->lang->load('dich', 'ja');http://luxy.art/admin/page_list_image
        
    }

    public function login() {
    	$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

    	if($this->input->post('loginAdmin')){

    		$this->form_validation->set_rules('username','lang:username_validation','required|trim|xss_clean');

    		$this->form_validation->set_rules('pwd','lang:pwd_validation','required|trim|xss_clean');

    		if($this->form_validation->run()){

    			$username = $this->input->post('username');

    			$password = md5($this->input->post('pwd'));

    			$whereAdmin = array('admin_name' => $username,'admin_pass' => $password);

    			$orderAdmin = array();

    			$data['admin'] = $this->main_model->getAllData("luxyart_tb_admin", $whereAdmin, $orderAdmin)->result();
   
    			if(count($data['admin']) == 1){

    				$this->main_model->setAdminSession($conditions);
    				redirect('admin');
    			} else {
    				$data['checkLogin'] = false;
    			}
    		}
    	}

    	$this->load->view('admin/admin/login.php', $data);
    }

   	public function checkLoginAdmin(){
   		if(!isAdmin()) redirect(base_url().'admin/login'); 
   	}

    

    // -------------------- GET DATA -------------------
    public function getData($id, $table, $key_id) {
        $this->db->flush_cache();
        $this->db->where($key_id, $id);
        return $this->db->get($table)->row();
    }

    public function allData($table, $where = []) {
        return $this->db->get_where($table, $where)->result();
    }

    public function deleteData($id, $table, $key_id) {
        $this->db->flush_cache();
        $this->db->where($key_id, $id);
        return $this->db->delete($table);
    }

    public function updateData($id, $table, $key_id, $data) {
        $this->db->flush_cache();
        $this->db->where($key_id, $id);
        return $this->db->update($table, $data);
    }

    public function insertData($table, $data) {
        if($this->db->insert($table, $data)){
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        return false;
    }

    public function searchData($keyword, $table, $listKey = [], $orderByDesc = null) {
        foreach ($listKey as $key => $value) {
            $this->db->or_like($value, $keyword);
        }
        if($orderByDesc != null){
            $this->db->order_by($orderByDesc,'desc');
        }
        return $this->db->get($table)->result();
    }

    public function pagination($data, $page = nulll, $limit = 15) {
        if($page == null) $page = 1;
        $total                  = count($data);
        $numPage                = ceil($total/$limit);
        $data['begin']          = ($page-1)*$limit;
        $data['numPage']        = $numPage;
        $data['currentPage']    = $page;
        $data['limit']          = $limit;
        return $data;
    }

    public function messageUpdate($data) {
        if($data){
            $this->res(true, "更新が完了しました。 !");
        }else{
            $this->res(false, "更新エラー !");
        }
        return $this->res();
    }

    public function messageCreate($data) {
        if($data){
            $this->res(true, "追加が完了しました。 !");
        }else{
            $this->res(false, "追加エラー !");
        }
        return $this->res();
    }

    public function messageDelete($data) {
        if($data){
            $this->res(true, "削除が完了しました。 !");
        }else{
            $this->res(false, "削除エラー !");
        }
        return $this->res();
    }

    public function getRandom($dodaichuoicode = 15){
        // Create codeid invite user
        $bangkytuset            = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code_repeat_1          = 1;
        $code_repeat_2          = 10;
        // $dodaichuoicode         = 15;
        $user_id_code_invite    = substr(str_shuffle(str_repeat($bangkytuset, mt_rand($code_repeat_1,$code_repeat_2))),1,$dodaichuoicode);
        return $user_id_code_invite;
    }

    public function upload($key, $name, $folder, $configCustom = []){
        $config['upload_path']    = $folder;
        $config['allowed_types']  = 'gif|GIF|jpg|JPG|png|PNG|jpeg|JPEG';
        /*dhthien fix. Ku Tuan choi ky khong thong bao*/
        $config['max_size']       = 10*15000;
        $config['max_width']      = 5*2024;
        $config['max_height']     = 5*2024;
        $config['file_name']      = $name;
        array_merge($config, $configCustom);
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if($this->upload->do_upload($key)){
            return $config['file_name'];
        }
        return ['error'=>$this->upload->display_errors()];
    }
    public function deleteFile($path) {
        if(file_exists($path)){
            unlink($path);
        }
    }
    public function dd($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }

    function array_to_csv_download($array, $filename = "export.csv", $delimiter=",") {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w'); 
        // loop over the input array
        foreach ($array as $line) { 
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter); 
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv;charset=UTF-8');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$filename.'";');

        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        fpassthru($f);
    }
    public function array_csv_table($table) {
        $data = [];
        $list_table = $this->db->list_fields($table);
        $list_row = $this->db->get($table)->result_array();
        array_push($data, $list_table);
        foreach ($list_row as $key => $value) {
            array_push($data, $value);
        }
        return $data;
    }
    
    public function index() {
        $this->checkLoginAdmin();
        $data['title'] = 'Dashboard';
        $data['key_page'] = 'page_main';
        $this->db->from('luxyart_tb_product_img as i','LEFT');
        $this->db->join('luxyart_tb_manager_server as s','i.id_server = s.id_server','LEFT');
        $this->db->order_by('i.id_img', 'DESC');
        $this->db->limit(8);
        $data['register_img'] = $this->db->get()->result();
        $this->template->load('admin', 'index', $data, __CLASS__);
    }

    // ------------------- Statistic -----------------------------------
    public function page_menu() {
        $this->checkLoginAdmin();
        $data['title'] = $this->lang->line('title_page_menu');
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'menu', $data, __CLASS__);
    }

    public function page_statistic() {
        $this->checkLoginAdmin();
        $data['title'] = $this->lang->line('title_page_statistic');
        $data['key_page'] = 'page_statistic';
        $this->template->load('admin', 'statistic', $data, __CLASS__);
    }

    public function load_statistic_day($begin, $end) {
        $time_end = time();
        if($end != '') $time_end = strtotime($end);
        $time_begin = strtotime('-10 days');
        if($begin != '') $time_begin = strtotime($begin);

        $use_point      = [];
        $buy_point      = [];
        $list_category  = [];

        $time = $time_begin;
        while($time <= $time_end){
            $date_start = date('Y-m-d 00:00:00', $time);
            $date_end = date('Y-m-d 00:00:00', strtotime('+1 days', $time));

            $this->db->from('luxyart_tb_management_point');
            $this->db->where('status_change_point',2);
            $this->db->where('date_add >=', $date_start);
            $this->db->where('date_add <', $date_end);
            $this->db->select('SUM(point) as total_point');
            $result = $this->db->get()->row();
            array_push($use_point, $result->total_point==''?0:$result->total_point);

            /*$this->db->from('luxyart_tb_management_point');
            $this->db->where('status_change_point',1);
            $this->db->where('date_add >=', $date_start);
            $this->db->where('date_add <', $date_end);
            $this->db->select('SUM(point) as total_point');
            $result = $this->db->get()->row();*/
            
            $this->db->join('luxyart_tb_list_credit', 'luxyart_tb_list_credit.id_list=luxyart_tb_user_order_credit.id_list','LEFT');
            $this->db->where('luxyart_tb_user_order_credit.date_order_credit >=', $date_start);
            $this->db->where('luxyart_tb_user_order_credit.date_order_credit <=', $date_end);
            $this->db->where('luxyart_tb_user_order_credit.payment_status', 1);
            $this->db->select('SUM(luxyart_tb_list_credit.point_credit) as total_point');
            $result = $this->db->get('luxyart_tb_user_order_credit')->row();
            
            array_push($buy_point, $result->total_point==''?0:$result->total_point);
            array_push($list_category, date('d/m', $time));
            $time = strtotime('+1 days', $time);
        }
        $list_category = array_map(function($value){return "\"$value\"";},$list_category);
        $data['use_point']      = "[".implode($use_point, ', ')."]";
        $data['buy_point']      = "[".implode($buy_point, ', ')."]";
        $data['list_category']  = "[".implode($list_category, ', ')."]";
        return $data;
    }

    public function load_statistic_month($begin, $end) {
        $time_end = time();
        if($end != '') $time_end = strtotime($end);
        $time_begin = strtotime('-12 month');
        if($begin != '') $time_begin = strtotime($begin);

        $use_point      = [];
        $buy_point      = [];
        $list_category  = [];

        $time = $time_begin;
        while($time <= $time_end){
            $date_start = date('Y-m-00 00:00:00', $time);
            $date_end = date('Y-m-00 00:00:00', strtotime('+1 month', $time));

            $this->db->from('luxyart_tb_management_point');
            $this->db->where('status_change_point',2);
            $this->db->where('date_add >=', $date_start);
            $this->db->where('date_add <', $date_end);
            $this->db->select('SUM(point) as total_point');
            $result = $this->db->get()->row();
            array_push($use_point, $result->total_point==''?0:$result->total_point);

            /*$this->db->from('luxyart_tb_management_point');
            $this->db->where('status_change_point',1);
            $this->db->where('date_add >=', $date_start);
            $this->db->where('date_add <', $date_end);
            $this->db->select('SUM(point) as total_point');
            $result = $this->db->get()->row();*/
            
            $this->db->join('luxyart_tb_list_credit', 'luxyart_tb_list_credit.id_list=luxyart_tb_user_order_credit.id_list','LEFT');
            $this->db->where('luxyart_tb_user_order_credit.date_order_credit >=', $date_start);
            $this->db->where('luxyart_tb_user_order_credit.date_order_credit <=', $date_end);
            $this->db->where('luxyart_tb_user_order_credit.payment_status', 1);
            $this->db->select('SUM(luxyart_tb_list_credit.point_credit) as total_point');
            $result = $this->db->get('luxyart_tb_user_order_credit')->row();
            
            array_push($buy_point, $result->total_point==''?0:$result->total_point);
            array_push($list_category, date('m/Y', $time));
            $time = strtotime('+1 month', $time);
        }
        $list_category = array_map(function($value){return "\"$value\"";},$list_category);
        $data['use_point']      = "[".implode($use_point, ', ')."]";
        $data['buy_point']      = "[".implode($buy_point, ', ')."]";
        $data['list_category']  = "[".implode($list_category, ', ')."]";
        return $data;
    }

    public function load_statistic_week($begin, $end) {
        $time_end = time();
        if($end != '') $time_end = strtotime($end);
        $time_begin = strtotime('-10 week');
        if($begin != '') $time_begin = strtotime($begin);

        $use_point      = [];
        $buy_point      = [];
        $list_category  = [];

        $time = $time_begin;
        while($time <= $time_end){
            $date_start = date('Y-m-d 00:00:00', $time);
            $date_end = date('Y-m-d 00:00:00', strtotime('+1 week', $time));

            $this->db->from('luxyart_tb_management_point');
            $this->db->where('status_change_point',2);
            $this->db->where('date_add >=', $date_start);
            $this->db->where('date_add <', $date_end);
            $this->db->select('SUM(point) as total_point');
            $result = $this->db->get()->row();
            array_push($use_point, $result->total_point==''?0:$result->total_point);

//             $this->db->from('luxyart_tb_management_point');
//             $this->db->where('status_change_point',1);
//             $this->db->where('date_add >=', $date_start);
//             $this->db->where('date_add <', $date_end);
//             $this->db->select('SUM(point) as total_point');
//             $result = $this->db->get()->row();
            
            $this->db->join('luxyart_tb_list_credit', 'luxyart_tb_list_credit.id_list=luxyart_tb_user_order_credit.id_list','LEFT');
            $this->db->where('luxyart_tb_user_order_credit.date_order_credit >=', $date_start);
            $this->db->where('luxyart_tb_user_order_credit.date_order_credit <=', $date_end);
            $this->db->where('luxyart_tb_user_order_credit.payment_status', 1);
            $this->db->select('SUM(luxyart_tb_list_credit.point_credit) as total_point');
            $result = $this->db->get('luxyart_tb_user_order_credit')->row();
            
            array_push($buy_point, $result->total_point==''?0:$result->total_point);
            array_push($list_category, date('d/m', $time).'-'.date('d/m', strtotime('+1 week', $time)));
            $time = strtotime('+1 week', $time);
        }
        $list_category = array_map(function($value){return "\"$value\"";},$list_category);
        $data['use_point']      = "[".implode($use_point, ', ')."]";
        $data['buy_point']      = "[".implode($buy_point, ', ')."]";
        $data['list_category']  = "[".implode($list_category, ', ')."]";
        return $data;
    }

    public function load_statistic() {
        $data   = [];
        $type   = $this->input->post('type');
        $begin  = str_replace("/", '-', $this->input->post('begin'));
        $end    = str_replace("/", '-', $this->input->post('end'));
      
        if($type == 'week') {
            $item = $this->load_statistic_week($begin, $end);
        }elseif($type == 'month') {
            $item = $this->load_statistic_month($begin, $end);
        }else{
            $item = $this->load_statistic_day($begin, $end);
        }
        $data += $item;
        //print_r($item);
        $this->load->view('admin/admin/load_statistic.php', $data);
    }

    // -------------------- USER ------------------
    public function export($key) {
        if($key=='user'){
            $data = $this->array_csv_table('luxyart_tb_user');
            $this->array_to_csv_download($data,"users_".date('Y_m_d_H_i_s').".csv");
        }else if($key=='user_order_full'||$key == 'user_order_day' || $key == 'user_order_month' || $key == 'user_order_wait'){
            $data = [];
            $filename = 'user_order_full';
            $this->db->from('luxyart_tb_user_order_credit as oc');
            $this->db->join('luxyart_tb_user as u', 'oc.user_id=u.user_id');
            $this->db->join('luxyart_tb_list_credit as l', 'l.id_list=oc.id_list');
            if($key=='user_order_day'){
                $this->db->where('date_order_credit >=', date("Y-m-d")." 00:00:00");
                $this->db->where('date_order_credit <=', date("Y-m-d H:i:s"));
                $filename = 'user_order_day';
            }elseif($key=='user_order_month'){
                $this->db->where('date_order_credit >=', date("Y-m")."-00 00:00:00");
                $this->db->where('date_order_credit <=', date("Y-m-d H:i:s"));
                $filename = 'user_order_month';
            }elseif($key=='user_order_wait'){
                $this->db->where('payment_status', 0);
                $filename = 'user_order_wait';
            }
            $this->db->order_by('id_ordercredit','desc');
            $result_row = $this->db->get()->result();

            array_push($data, ['スクリーンネーム','ログインID','決済ステータス','決済種別','会員種別','保有ポイント','Date']);
            foreach ($result_row as $key => $value) {
                $name = $value->user_firstname==''&& $value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname;
                $email = $value->user_email;
                $status = $value->payment_status==1?'Received':'Wait';
                if($value->payment_menthod==2) $type = "Master Visa Card";
                elseif($value->payment_menthod==3) $type = "Bitcoin";
                else $type = "Transfer money";
                switch ($value->user_level) {
                  case 1:
                    $user_level = "Regular";
                    break;
                  case 2:
                    $user_level = "Creator";
                    break;
                  case 3:
                    $user_level = "Gold";
                    break;
                  case 4:
                    $user_level = "Platinum";
                    break;
                  case 5:
                    $user_level = "Enterprise";
                    break;
                  
                  default:
                    # code...
                    break;
                }
                $point = $value->point_credit;
                $date = $value->date_order_credit;
                $item = [$name, $email, $status, $type, $user_level, $point, $date];
                array_push($data, $item);
            }
            $this->array_to_csv_download($data, $filename."_".date('Y_m_d_H_i_s').".csv");
        }else{
            echo "Export error !";
        } 
    }

    function csvstring_to_array($string, $separatorChar = ',', $enclosureChar = '"', $newlineChar = "\n") {
        // @author: Klemen Nagode
        $array = array();
        $size = strlen($string);
        $columnIndex = 0;
        $rowIndex = 0;
        $fieldValue="";
        $isEnclosured = false;
        for($i=0; $i<$size;$i++) {

            $char = $string{$i};
            $addChar = "";

            if($isEnclosured) {
                if($char==$enclosureChar) {

                    if($i+1<$size && $string{$i+1}==$enclosureChar){
                        // escaped char
                        $addChar=$char;
                        $i++; // dont check next char
                    }else{
                        $isEnclosured = false;
                    }
                }else {
                    $addChar=$char;
                }
            }else {
                if($char==$enclosureChar) {
                    $isEnclosured = true;
                }else {

                    if($char==$separatorChar) {

                        $array[$rowIndex][$columnIndex] = $fieldValue;
                        $fieldValue="";

                        $columnIndex++;
                    }elseif($char==$newlineChar) {
                        echo $char;
                        $array[$rowIndex][$columnIndex] = $fieldValue;
                        $fieldValue="";
                        $columnIndex=0;
                        $rowIndex++;
                    }else {
                        $addChar=$char;
                    }
                }
            }
            if($addChar!=""){
                $fieldValue.=$addChar;

            }
        }

        if($fieldValue) { // save last field
            $array[$rowIndex][$columnIndex] = $fieldValue;
        }
        return $array;
    }

    public function import_user() {
        $filename = __DIR__.'/../../../publics/csv/'.$_FILES['file']['name'];
        $move  = move_uploaded_file($_FILES['file']['tmp_name'], $filename);
        $data = $this->process_import_user($filename);
        // $this->dd($data);
        if(!$data) {
            $this->res(false, "Import error !");
            return $this->res();
        }
        foreach ($data as $key => $value) {
            $this->insertData('luxyart_tb_user', $value);
        }
        
        $this->res(true, "Import success !");
        return $this->res();
    }

    public function process_import_user($filename) {
        $data = [];
        $get = file_get_contents($filename);
        $csv = $this->csvstring_to_array($get);
       // $this->dd($csv);
        foreach ($csv as $key => $value) {
            if($key == 0) continue;
            $item = [];
            foreach ($csv[0] as $key_k => $value_k) {
                $value_k = preg_replace("/[^a-zA-Z0-9_]+/", "", $value_k);
                $item[$value_k] = $value[$key_k];
            }
            $item['user_macode'] = "JA".rand(1000000000,9999999999);
            $item['user_pass']   = md5($item['user_pass']);
            $item['country_id']  = 1;
            $item['user_level']  = 1;
            $item['user_dateregister']  = date('Y-m-d H:i:s');
            $item['user_status']  = 1;
            $item['is_active']  = 1;

            if(isset($item['state_id']) && $item['state_id']!=null && ($state = $this->findStateByName($item['state_id']))){
                $item['state_id']    = $state->state_id;
            }else{
                $item['state_id']    = 1;
            }
            
            array_push($data, $item);
        }
        return $data;
    }

    public function findStateByName($name) {
        $this->db->from('luxyart_tb_country_state');
        $this->db->where('state_name', $name);
        $get = $this->db->get()->row();
        return $get;
    }

    public function send_mail_change_password_admin($id) {
        $this->db->flush_cache();
        $this->db->from('luxyart_tb_admin');
        $this->db->where('admin_id', $id);
        $get = $this->db->get()->row();

        $dataSendEmail["name_email_code"]       = "change_password_admin";
        $dataSendEmail["lang_id"]               = 1;
        $dataSendEmail["email_to"]              = $get->admin_email;
        $dataSendEmail["name"]                  = $get->admin_full_name;
        $dataSendEmail["key_1"]                 = $get->admin_email;

        $this->function_model->sendMailHTML($dataSendEmail);
    }
    public function page_change_password_admin() {
        $data['title'] = $this->lang->line('title_page_change_password_admin');
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'user/change_password', $data, __CLASS__);
    }
    public function change_password_admin() {
        $id = $this->session->all_userdata()['logged_in_admin'];
        if($this->input->post('admin_pass')!=$this->input->post('admin_pass_confirm')) {
            $this->res(false, "Password confirm the same !");
            return $this->res();
        }
        $item ['admin_pass'] = md5($this->input->post('admin_pass'));
        $check = $this->updateData($id, "luxyart_tb_admin",'admin_id', $item);
        if($check) {
            $this->send_mail_change_password_admin($id);
        }
        return $this->messageUpdate($check);
    }
    public function get_list_user_pickup(){
        $aaa = [];
        foreach ($this->allData('luxyart_tb_user_pickup') as $key => $value) {
           array_push($aaa, $value->user_id);
        }
        return $aaa;
    }
    public function get_list_user($search = false, $pagination = false, $limit = null, $begin = null){
        $pickup = $this->get_list_user_pickup();
        $this->db->flush_cache();
        $keyword = trim($_GET['keyword']);
        $user_level = $_GET['user_level'];

        $this->db->from('luxyart_tb_user');
        
        $listKey = ['user_email','user_firstname','user_lastname','display_name'];
        
        if($user_level!='') {
            $this->db->where('user_level', $user_level);
        }



        if($_GET['pickup']==1) {
            $this->db->where_in('user_id', $pickup);
        }
        
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $ex = explode(' ', $keyword);
            $like_user_firstname = '';
            foreach ($ex as $key => $value) {
                if($value==' ') continue;
                if($key>0) {
                    $like_user_firstname .= " OR ";
                }
                $like_user_firstname .= " user_firstname LIKE '%$value%' ";
            }
            $like_last_name = '';
            foreach ($ex as $key => $value) {
                if($value==' ') continue;
                if($key>0) {
                    $like_last_name .= " OR ";
                }
                $like_last_name .= " user_lastname LIKE '%$value%' ";
            }
            $like_display_name = '';
            foreach ($ex as $key => $value) {
            	if($value==' ') continue;
            	if($key>0) {
            		$like_display_name .= " OR ";
            	}
            	$like_display_name .= " display_name LIKE '%$value%' ";
            }

            $like = "(user_email LIKE '%$keyword%' OR ((".$like_user_firstname.") AND (".$like_user_firstname.")) OR user_firstname LIKE '%$keyword%' OR user_lastname LIKE '%$keyword%' OR display_name LIKE '%$keyword%')";
            $this->db->where($like, null, false);
        }

        //$this->db->where('user_status <=', 1);

        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('user_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_user($page) {
    	$this->checkLoginAdmin();
        // $this->dd($this->get_list_user_pickup());
        $data = $this->pagination($this->get_list_user(false,false), $page);
        $data['list'] = $this->get_list_user(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_user(true,false), $page);
            $data['list'] = $this->get_list_user(true,true,$data['limit'],$data['begin']);
        }

    	$data['title'] = $this->lang->line('title_page_list_user');
        $data['keyword'] = $search;
        $data['user_level'] = $_GET['user_level'];
        $data['key_page'] = 'page_list_user';
		$this->template->load('admin', 'user/list', $data, __CLASS__);
    }

    public function page_add_edit_user($id) {
        $this->checkLoginAdmin();
        $data['country']        = $this->allData('luxyart_tb_country');
        $data['country_state']  = $this->allData('luxyart_tb_country_state');

        $this->db->from('luxyart_tb_user as user');
        $this->db->join('luxyart_tb_bank_infomation as bank', 'user.user_id=bank.user_id','LEFT');
        $this->db->where('user.user_id', $id);
        $this->db->select('*,user.user_id as user_id');
        $data['get'] = $this->db->get()->row();
        if(count($data['get'])>0) {
            $name = ($data['get']->user_firstname==''&&$data['get']->user_lastname=='')?$data['get']->user_email:$data['get']->user_firstname.' '.$data['get']->user_lastname;
            $data['title']      = $this->lang->line('title_page_list_user').": ". $name;
            $data['type']       = 'edit';
        }else{
            $data['title']      = $this->lang->line('title_page_list_user');
            $data['type']       = 'add';
        }
        $data['key_page'] = 'page_list_user';
        
        $data['user_id'] = $id;
        $data['list_member'] = $this->manager_user_model->get_list_member($id,'','')->result();
        $data['list_invited'] = $this->manager_user_model->get_list_invited($id,'','')->result();
        
        $this->template->load('admin', 'user/edit', $data, __CLASS__);
    }

    public function add_edit_user() {
    	$this->checkLoginAdmin();

    	$id = $this->input->post('id');

    	$item = [
            'notice_luxyart'        =>   $this->input->post('notice_luxyart'),
			'user_firstname'        =>   $this->input->post('user_firstname'),
			'user_lastname'         =>   $this->input->post('user_lastname'),
			'display_name'         =>   $this->input->post('display_name'),
			'cityname'              =>   $this->input->post('cityname'),
            'country_id'            =>   $this->input->post('country_id'),
            'state_id'              =>   $this->input->post('state_id'),
			'user_address'          =>   $this->input->post('user_address'),
            'user_address_building' =>   $this->input->post('user_address_building'),
            'user_zipcode'          =>   $this->input->post('user_zipcode'),
			'user_introduction'     =>   $this->input->post('user_introduction'),
            'user_email'            =>   trim($this->input->post('user_email')),
            'user_level'            =>   $this->input->post('user_level'),
    	];
    	if($this->input->post('user_pass') != null){
			$item['user_pass'] = md5($this->input->post('user_pass'));
		}
        
        $get_user = $this->getData($id, 'luxyart_tb_user', 'user_id');

        if($_FILES['avatar']['name']!=''){
            $upload = $this->upload('avatar','avatar_'.time().'.png', 'publics/avatar');
            if(is_array($upload)){
                $note['note_avatar'] = 'Avatar upload error ! Image must format: gif|GIF|jpg|JPG|png|PNG|jpeg|JPEG';
            }else{
                $item['user_avatar'] = $upload; 
                if($id != null) 
                    $this->deleteFile('publics/avatar/'.$get_user->user_avatar);
            }
        }

        if($_FILES['banner']['name']!=''){
            $upload = $this->upload('banner','banner_'.time().'.png', 'publics/banner');
            if(is_array($upload)){
                $note['note_banner'] = 'Banner upload error ! Image must format: gif|GIF|jpg|JPG|png|PNG|jpeg|JPEG';
            }else{
                $item['user_banner'] = $upload; 
                if($id != null) 
                    $this->deleteFile('publics/avatar/'.$get_user->user_banner);
            }
        }

        $this->db->where('user_email', $item['user_email']);
        $this->db->where('user_id !=', $id);
        if(count($this->db->get('luxyart_tb_user')->result())>0) {
            $note['note_email'] = "メールアドレスが既に存在しています。他のメールアドレスを記入して下さい。 !";
        }

        if(count($note)>0) {
            $this->res(false, 'いくつかのものをエラー !', $note);
            return $this->res();
        }

        if($this->input->post('type') == 'bank') {
            $get = $this->getData($id, 'luxyart_tb_bank_infomation', 'user_id');

            $bank['bank_name']          = $this->input->post('bank_name');
            $bank['bank_branch_name']   = $this->input->post('bank_branch_name');
            $bank['bank_account']       = $this->input->post('bank_account');
            $bank['bank_account_name']  = $this->input->post('bank_account_name');
            $bank['bank_type_account']  = $this->input->post('bank_type_account');

            if(count($get)>0) {
                return $this->messageUpdate($this->updateData($get->bank_id, "luxyart_tb_bank_infomation",'bank_id', $bank));
            }else{
                $bank['user_id'] = $id;
                $bank['date_add_bank'] = date('Y-m-d H:i:s');
                return $this->messageCreate($this->insertData("luxyart_tb_bank_infomation", $bank));
            }

            
        }

    	if($id != null) {
			return $this->messageUpdate($this->updateData($id, "luxyart_tb_user",'user_id', $item));	
    	}else{  
    		$item['user_dateregister']      = date('Y-m-d h:i:s');
            $item['user_id_code_invite']    = $this->getRandom();
            $user_id = $this->insertData("luxyart_tb_user", $item);
            return $this->messageCreate($user_id);  
    	}
        return $this->res();
    }

    public function status_user($id) {
    	$item = ['user_status'=>$this->input->post('status')];
    	return $this->messageUpdate($this->updateData($id, 'luxyart_tb_user','user_id',$item));
    }

    public function level_user($id) {
        $item = ['user_level'=>$this->input->post('user_level')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_user','user_id',$item));
    }

    public function delete_user($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_user", 'user_id'));
    }

    // ---------------------- User pickup -----------
    public function pickup_user($id) {
        $status = $this->input->post('status');
        if($status==1){
            $count = count($this->db->get_where('luxyart_tb_user_pickup', ['user_id'=>$id])->result());
            if($count>0){
                 return $this->messageUpdate(true); 
            }else{
                $item['user_id']      = $id;
                $item['position']      = 1;
                return $this->messageUpdate($this->insertData("luxyart_tb_user_pickup", $item));  
            }
        }else{
            return $this->messageUpdate($this->deleteData($id, "luxyart_tb_user_pickup", 'user_id')); 
        }
        
    }

    public function page_list_user_pickup() {
        $this->checkLoginAdmin();

        $this->db->from('luxyart_tb_user_pickup as up');
        $this->db->join('luxyart_tb_user as u','u.user_id=up.user_id');
        $this->db->order_by('up.position','ASC');
        $data['list'] = $this->db->get()->result();

        $data['title'] = $this->lang->line('title_list_user_pickup');
        $data['key_page'] = 'page_list_user_pickup';
        $this->template->load('admin', 'user_pickup/list', $data, __CLASS__);
    }
    public function delete_user_pickup($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_user_pickup", 'pickup_id'));
    }
    public function position_user_pickup($id) {
        $item = ['position'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_user_pickup','pickup_id',$item));
    }

    // ------------------- ORDER ----------------------------
    public function page_list_order($page) {
        $this->checkLoginAdmin();

        $data = $this->pagination($this->db->get("luxyart_tb_product_img_order")->result(), $page);

        $this->db->from('luxyart_tb_product_img_order as o');
        $this->db->join('luxyart_tb_user as u','u.user_id=o.user_buy_img');
        $this->db->order_by('id_order_img','desc');
        $this->db->limit($data['limit'], $data['begin']);
        $data['order'] = $this->db->get()->result();

        $data['title'] = $this->lang->line('title_page_list_order');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_list_order';
        $this->template->load('admin', 'order/list', $data, __CLASS__);
    }

    public function status_order($id) {
        $item = ['status_order'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_product_img_order','id_order_img',$item));
    }

    public function delete_order($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_product_img_order", 'id_order_img'));
    }

    public function page_view_order($id) {
        $this->checkLoginAdmin();

        $this->db->where('id_order_img', $id);
        $data['get'] = $this->db->get("luxyart_tb_product_img_order")->row();;
        
        $this->db->from('luxyart_tb_product_img_order_detail as d');
        $this->db->join('luxyart_tb_user as u','u.user_id=d.user_owner');
        $this->db->join('luxyart_tb_product_img as i','i.id_img=d.id_img');
        $this->db->join('luxyart_tb_product_img_size as s','s.id_img_size=d.id_img_size');
        $this->db->where('id_order_img', $data['get']->id_order_img);
        $data['list'] = $this->db->get()->result();

        $data['title'] = $this->lang->line('title_page_view_order'). $data['get']->id_order_img;
        $data['key_page'] = 'page_list_order';
        $this->template->load('admin', 'order/view', $data, __CLASS__);
    }


    // ------------------- NOTICE ----------------------------
    public function get_list_notice($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $listKey = ['ms_subject','ms_content','u.user_firstname','u.user_lastname'];

        $this->db->from('luxyart_tb_user_message as m');
        $this->db->join('luxyart_tb_user as u','u.user_id=m.user_send_id','LEFT');
        $this->db->join('luxyart_tb_user as u2','u2.user_id=m.user_id','LEFT');

        $this->db->select('*,u.user_firstname as user_send_firstname,u.user_lastname as user_send_lastname,u2.user_firstname as user_lastname,u2.user_lastname as user_lastname');
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }
        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('id_message','desc');
        return $this->db->get()->result();
    }
    public function page_list_notice($page) {
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_notice(false,false), $page);
        $data['list'] = $this->get_list_notice(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_notice(true,false), $page);
            $data['list'] = $this->get_list_notice(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = "売れた画像";
        $data['keyword'] = $search;
        $data['key_page'] = 'page_list_notice';
        $this->template->load('admin', 'notice/list', $data, __CLASS__);
    }

    public function add_edit_notice() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id');
        $item = [
            'user_id'       =>  $this->input->post('user_id'),
            'ms_subject'    =>  $this->input->post('ms_subject'),
            'ms_content'    =>  $this->input->post('ms_content'),
        ];

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_user_message",'id_message', $item));
        }else{
            $item['user_send_id'] = 0;
            $item['type_message'] = 1;
            $item['date_message'] = date('Y-m-d h:i:s');
            return $this->messageCreate($this->insertData("luxyart_tb_user_message", $item));  
        }
        return $this->res();
    }

    public function delete_notice($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_user_message", 'id_message'));
    }

    public function page_add_edit_notice($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_user_message','id_message');
        $data['user'] = $this->allData('luxyart_tb_user');

        if(count($data['get'])>0) {
            $data['title']  = "売れた画像: ". $data['get']->ms_subject;
            $data['type']   = 'edit';
        }else{
            $data['title']  = "売れた画像";
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_list_notice';
        $this->template->load('admin', 'notice/edit', $data, __CLASS__);
    }

    // ------------------- META SEO ----------------------------
    public function get_list_meta($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $listKey = ['page_title_name','name_call_code','page_meta_key','page_description','page_h1'];

        $this->db->from('luxyart_tb_page_seo');
        $this->db->where('lang_id', 1);
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }
        $this->db->where('lang_id', 1);
        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->order_by('id_seo','desc');

        return $this->db->get()->result();
    }
    public function page_list_meta($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_meta(false,false), $page);
        $data['list'] = $this->get_list_meta(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_meta(true,false), $page);
            $data['list'] = $this->get_list_meta(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_meta');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'meta/list', $data, __CLASS__);
    }

    public function add_edit_meta() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id_seo');
        $page_seo_id = $this->input->post('page_seo_id');
        foreach ($id as $key => $value) {
            $item = [
                'page_title_name'   =>  $this->input->post('page_title_name')[$key],
                'name_call_code'    =>  $this->input->post('name_call_code')[$key],
                'page_meta_key'     =>  $this->input->post('page_meta_key')[$key],
                'return_title'      =>  $this->input->post('return_title')[$key],
                'return_msg'        =>  $this->input->post('return_msg')[$key],
                'return_link'       =>  $this->input->post('return_link')[$key],
                'page_description'  =>  $this->input->post('page_description')[$key],
                'page_h1'           =>  $this->input->post('page_h1')[$key],
                'lang_id'           =>  $this->input->post('lang_id')[$key],
            ];
            if($value != null) {
                if(!$this->updateData($value, "luxyart_tb_page_seo",'id_seo', $item)){
                    return $this->messageUpdate(false);
                }    
            }else{
                $check = $this->insertData("luxyart_tb_page_seo", $item);
                if($page_seo_id == null) $page_seo_id = $check;
                $item_page_seo = ['page_seo_id'=>$page_seo_id];
                $this->updateData($check, "luxyart_tb_page_seo",'id_seo', $item_page_seo);
               if(!$check){
                    return $this->messageCreate(false);
               }
            }
        }
        return $this->messageUpdate(true);
        return $this->res();
    }

    public function delete_meta($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_page_seo", 'page_seo_id'));
    }

    public function page_add_edit_meta($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_page_seo','id_seo');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_meta').": ". $data['get']->page_title_name;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_meta');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'meta/edit', $data, __CLASS__);
    }

    // ------------------- Email template ----------------------------
    public function get_list_email_tpl($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_email_send');
        $listKey = ['name_email_code','subject','content_email'];

        $this->db->where('lang_id', 1);

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }
        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('id_email','desc');
        return $this->db->get()->result();
    }
    public function page_list_email_tpl($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_email_tpl(false,false), $page);
        $data['list'] = $this->get_list_email_tpl(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_email_tpl(true,false), $page);
            $data['list'] = $this->get_list_email_tpl(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_email_tpl');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'email_tpl/list', $data, __CLASS__);
    }

    public function add_edit_email_tpl() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id_email');
        $group_id = $this->input->post('group_id');
        foreach ($id as $key => $value) {
            $item = [
                'name_email_code'   =>  $this->input->post('name_email_code'),
                'subject'           =>  $this->input->post('subject')[$key],
                'content_email'     =>  $this->input->post('content_email')[$key],
                'lang_id'           =>  $this->input->post('lang_id')[$key],
            ];
            if($value != null) {
                if(!$this->updateData($value, "luxyart_tb_email_send",'id_email', $item)){
                    return $this->messageUpdate(false);
                }    
            }else{
                $check = $this->insertData("luxyart_tb_email_send", $item);
                if($group_id == null) $group_id = $check;
                $item_page_seo = ['group_id'=>$group_id];
                $this->updateData($check, "luxyart_tb_email_send",'id_email', $item_page_seo);
               if(!$check){
                    return $this->messageCreate(false);
               }
            }
        }
        return $this->messageUpdate(true);
        return $this->res();
    }

    public function delete_email_tpl($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_email_send", 'id_email'));
    }

    public function page_add_edit_email_tpl($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_email_send','id_email');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_email_tpl').": ". $data['get']->subject;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_email_tpl');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'email_tpl/edit', $data, __CLASS__);
    }

    // ------------------- Notification ----------------------------
    public function info_check_notification($id) { 
        $data['get'] = $this->db->get_where('luxyart_tb_notification',['note_id'=>$id])->row();
        $this->load->view('admin/admin/notification/info_check.php', $data);
    }
    public function get_list_notification($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_notification');
        $listKey = ['title','content'];

        $this->db->where('lang_id', 1);

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }
        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('note_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_notification($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_notification(false,false), $page);
        $data['list'] = $this->get_list_notification(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_notification(true,false), $page);
            $data['list'] = $this->get_list_notification(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_notification');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'notification/list', $data, __CLASS__);
    }

    public function add_edit_notification() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('note_id');
        $group_id = $this->input->post('group_id');
        foreach ($id as $key => $value) {
            $item = [
                'title'         =>  $this->input->post('title')[$key],
                'content'       =>  $this->input->post('content')[$key],
                'date'          =>  $this->input->post('year').'-'.
                                    $this->input->post('month').'-'.
                                    $this->input->post('day'),
                'lang_id'       =>  $this->input->post('lang_id')[$key],
            ];
            if($value != null) {
                if(!$this->updateData($value, "luxyart_tb_notification",'note_id', $item)){
                    return $this->messageUpdate(false);
                }    
            }else{
                $check = $this->insertData("luxyart_tb_notification", $item);
                if($group_id == null) $group_id = $check;
                $item_page_seo = ['group_id'=>$group_id];
                $this->updateData($check, "luxyart_tb_notification",'note_id', $item_page_seo);
               if(!$check){
                    return $this->messageCreate(false);
               }
            }
        }
        return $this->messageUpdate(true);
        return $this->res();
    }

    public function delete_notification($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_notification", 'group_id'));
    }

    public function page_add_edit_notification($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_notification','note_id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_notification').": ". $data['get']->title;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_notification');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'notification/list', $data, __CLASS__);
    }

    // ------------------- SETTING ----------------------------
    public function get_list_setting($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_settings');
        $listKey = ['name','value'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }
        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->where('status', 1);
        $this->db->order_by('setting_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_setting($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_setting(false,false), $page);
        $data['list'] = $this->get_list_setting(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_setting(true,false), $page);
            $data['list'] = $this->get_list_setting(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_setting');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'setting/list', $data, __CLASS__);
    }

    public function add_edit_setting() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id');
        $item = [
            'value_text'              =>  $this->input->post('value_text'),
            'value'             =>  $this->input->post('value'),
        ];

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_settings",'setting_id', $item));      
        }else{
            return $this->messageCreate($this->insertData("luxyart_tb_settings", $item));  
        }
        return $this->res();
    }

    public function delete_setting($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_settings", 'setting_id'));
    }

    public function page_add_edit_setting($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_settings','setting_id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_setting').": ". $data['get']->name;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_setting');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'setting/edit', $data, __CLASS__);
    }


    // ------------------- IMAGE ----------------------------
    
    public function image_info($id){
        $this->db->flush_cache();
        $this->db->from('luxyart_tb_product_img as img');
        $this->db->where('id_img', $id);
        $this->db->join('luxyart_tb_user as user','user.user_id=img.user_id','LEFT');
        $this->db->join('luxyart_tb_manager_server as server','server.id_server=img.id_server','LEFT');
        $data['get'] = $this->db->get()->row();
        $this->load->view('admin/admin/product_image/image_info.php', $data);
    }
    public function get_list_image($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_product_img as img');
        $this->db->join('luxyart_tb_user as user','user.user_id=img.user_id','left');
        $this->db->join('luxyart_tb_manager_server as server','server.id_server=img.id_server','inner');
        $this->db->join('luxyart_tb_img_category as icc','icc.id_img=img.id_img','inner');
        $this->db->group_by('img.id_img'); 

        $listKey = ['img_title','img_tags'];

        $user_level = $_GET['user_level'];
        $img_check_status = $_GET['img_check_status'];
        $cate_id = $_GET['cate_id'];

        if($user_level != '') {
            $this->db->where('user.user_level', $user_level);
        }

        if($img_check_status != '') {
            $this->db->where('img_check_status', $img_check_status);
        }

        if($cate_id != '') {
            $cate = " (root_cate='$cate_id' OR parent_cate='$cate_id') ";
            $this->db->where($cate, null, false);
        }
        
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }


        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->select('*, img.id_img as id_img_abc');
        $this->db->order_by('img.id_img','desc');
        
        //$this->db->get();
        //echo "xxx = ".$this->db->last_query();
        
        return $this->db->get()->result();
    }
    public function page_list_image($page) {
    	
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_image(false,false), $page, 64);
        $data['list'] = $this->get_list_image(false,true,$data['limit'],$data['begin']);


        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_image(true,false), $page, 64);
            $data['list'] = $this->get_list_image(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_image');
        $data['keyword'] = $search;
        $data['user_level'] = $_GET['user_level'];
        $data['img_check_status'] = $_GET['img_check_status'];
        $data['cate_id'] = $_GET['cate_id'];
        $data['key_page'] = 'page_list_image';
        $this->template->load('admin', 'product_image/list', $data, __CLASS__);
    }

    public function status_image($id) {
        $status = $this->input->post('status');
        $item['img_check_status'] = $status;
        if($status==1){
            $item['date_active'] = date('Y-m-d h:i:s');
        }
        if($status==3) {
            $this->updateData($id, 'luxyart_tb_report_image','img_id',['status'=>1]);
        }
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_product_img','id_img',$item));
    }

    public function delete_image($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_product_img", 'id_img'));
    }

    // ------------------- IMAGE REPORT ----------------------------
    public function report_image_info($id){
        $this->db->flush_cache();
        $this->db->from('luxyart_tb_report_image as ri');
        $this->db->where('report_image_id', $id);
        $this->db->join('luxyart_tb_product_img as img','ri.img_id=img.id_img','LEFT');
        $this->db->join('luxyart_tb_user as user','user.user_id=img.user_id','LEFT');
        $this->db->join('luxyart_tb_manager_server as server','server.id_server=img.id_server','LEFT');
        $this->db->select('*, img.id_img as id_img_abc');
        $data['get'] = $this->db->get()->row();
        $this->load->view('admin/admin/report_image/image_info.php', $data);
    }
    public function get_list_report_image($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_report_image as ri');
        $this->db->join('luxyart_tb_product_img as img','ri.img_id = img.id_img','LEFT');
        $this->db->join('luxyart_tb_user as user','user.user_id=img.user_id','LEFT');
        $this->db->join('luxyart_tb_manager_server as server','server.id_server=img.id_server','LEFT');
        $this->db->where('ri.status',0);
        // $this->db->group_by('ri.img_id'); 

        $listKey = ['img_title','img_tags'];
        
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }


        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->select('*, img.id_img as id_img_abc');
        $this->db->order_by('ri.report_image_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_report_image($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_report_image(false,false), $page, 600);
        $data['list'] = $this->get_list_report_image(false,true,$data['limit'],$data['begin']);


        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_report_image(true,false), $page, 600);
            $data['list'] = $this->get_list_report_image(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_report_image');
        $data['key_page'] = 'page_list_report_image';
        $this->template->load('admin', 'report_image/list', $data, __CLASS__);
    }

    public function status_report_image($id) {
        $status = $this->input->post('status');
        $item['status'] = $status;
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_report_image','img_id',$item));
    }

    public function status_report_image_full() {
        $status = $this->input->post('status');
        $item['status'] = $status;
        $update = $this->db->update('luxyart_tb_report_image', $item);
        return $this->messageUpdate($update);
    }

    public function delete_report_image($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_report_image", 'report_image_id'));
    }

    // ---------------------------Language manager---------------
    public function page_list_lang($page) { 
        $this->checkLoginAdmin();

        $data['list'] = glob('application/language/*');
        $data['title'] = "翻訳管理";
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'lang/list', $data, __CLASS__);
    }

    public function add_edit_lang() {
        $this->checkLoginAdmin();
        
        $path = $this->input->post('path');
        $code = $this->input->post('code');
        $update = @file_put_contents(__DIR__."/../../../".$path."/dich_lang.php", $code);
        return $this->messageUpdate($update);   
    }

    public function page_add_edit_lang($id) {
        $this->checkLoginAdmin();

        $data['path'] = $this->input->get('path');
        $data['code'] = $this->input->get('code');
        
        if($data['code'] == md5('luxyart')){
        
	        $nameLang = ['ja'=>'Japan','vi'=>'Vietnam','en'=>'English'];
	        $data['title'] = $this->lang->line('title_page_add_edit_lang').": ".$nameLang[str_replace('application/language/', '', $data['path'])];
	        $data['code'] = @file_get_contents(__DIR__."/../../../".$data['path']."/dich_lang.php");
	        $data['key_page'] = 'page_list_lang';
	        $this->template->load('admin', 'lang/edit', $data, __CLASS__);
        
        }
        else{
        	echo $this->lang->line('you_do_not_have_permission_to_access_this_page');
        }
        
    }

    // ------------------- COUNTRY ----------------------------
    public function get_list_country($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_country');
        $listKey = ['country_name','country_code','currency_code'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('country_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_country($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_country(false,false), $page);
        $data['list'] = $this->get_list_country(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_country(true,false), $page);
            $data['list'] = $this->get_list_country(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_country');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'country/list', $data, __CLASS__);
    }

    public function add_edit_country() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id');
        $item = [
            'country_name'              =>  $this->input->post('country_name'),
            'country_code'              =>  $this->input->post('country_code'),
            'currency_code'             =>  $this->input->post('currency_code'),
        ];

        if(count($this->getData(trim($item['country_name']),'luxyart_tb_country','country_name'))>0) {
            $this->res(false, "既に存在しています。 !");
            return $this->res();
        }

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_country",'country_id', $item));      
        }else{
            return $this->messageCreate($this->insertData("luxyart_tb_country", $item));  
        }
        return $this->res();
    }

    public function delete_country($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_country", 'country_id'));
    }

    public function page_add_edit_country($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_country','country_id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_country').": ". $data['get']->country_name;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_country');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'country/edit', $data, __CLASS__);
    }

    // ------------------- STATE ----------------------------
    public function load_state($country_id){
        $data['state_id'] = $this->input->get('state_id');
        $data['list'] = $this->db->get_where('luxyart_tb_country_state',['country_id'=>$country_id])->result();
        $this->load->view('admin/admin/state/load.php', $data);
    }
    public function get_list_state($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $country_id = $_GET['country_id'];
        $this->db->from('luxyart_tb_country as c');
        $this->db->join('luxyart_tb_country_state as s', 'c.country_id = s.country_id');
        $listKey = ['state_name'];

        if($country_id != '') {
            $this->db->where('s.country_id', $country_id);
        }

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        

        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('state_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_state($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_state(false,false), $page);
        $data['list'] = $this->get_list_state(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_state(true,false), $page);
            $data['list'] = $this->get_list_state(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_state');
        $data['keyword'] = $search;
        $data['country_id'] = $_GET['country_id'];
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'state/list', $data, __CLASS__);
    }

    public function add_edit_state() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id');
        $item = [
            'state_name'              =>  $this->input->post('state_name'),
            'country_id'              =>  $this->input->post('country_id'),
        ];

        if(count($this->getData(trim($item['state_name']),'luxyart_tb_country_state','state_name'))>0) {
            $this->res(false, "既に存在しています。 !");
            return $this->res();
        }

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_country_state",'state_id', $item));      
        }else{
            return $this->messageCreate($this->insertData("luxyart_tb_country_state", $item));  
        }
        return $this->res();
    }

    public function delete_state($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_country_state", 'state_id'));
    }

    public function page_add_edit_state($id) {
        $this->checkLoginAdmin();

        $data['country']        = $this->allData('luxyart_tb_country');
        $data['get'] = $this->getData($id, 'luxyart_tb_country_state','state_id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_state').": ". $data['get']->state_name;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_state');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'state/edit', $data, __CLASS__);
    }

    
    // ------------------- CATEGORY ----------------------------
    public function load_menu($root_cate){
        $data['parent_cate'] = $_GET['parent_cate'];
        $data['list'] = $this->db->get_where('luxyart_tb_product_category',['root_cate'=>$root_cate,'lang_id'=>1])->result();
        if($root_cate==0) $data['list'] = [];
        $this->load->view('admin/admin/category/load_parent.php', $data);
    }
    public function get_list_category($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $is_pickup = $_GET['is_pickup'];
        $root_cate = $_GET['root_cate'];
        $listKey = ['cate_name','note_description','meta_key_seo_cate'];

        $this->db->from('luxyart_tb_product_category');
        
        if($is_pickup==1){
            $this->db->where('is_pickup', 1);
        }
        if($root_cate!=''){
            $this->db->where('root_cate', $root_cate);
        }
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }else{
            $this->db->where('lang_id', 1);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->order_by('cate_id','desc');

        return $this->db->get()->result();
    }
    public function page_list_category($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_category(false,false), $page);
        $data['list'] = $this->get_list_category(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_category(true,false), $page);
            $data['list'] = $this->get_list_category(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_category');
        $data['keyword'] = $search;
        $data['is_pickup'] = $_GET['is_pickup'];
        $data['root_cate'] = $_GET['root_cate'];
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'category/list', $data, __CLASS__);
    }

    public function add_edit_category() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('cate_id');
        $product_category_id = $this->input->post('product_category_id');
        
        $is_upload = 0;
        if($_FILES['avatar']['name']){
        	$is_upload = 1;
        	$upload = $this->upload('avatar', "category_".time().".png", 'publics/cate_img');
        }

        foreach ($id as $key => $value) {
            $item = [
                'cate_name'             =>  $this->input->post('cate_name')[$key],
                'note_description'      =>  $this->input->post('note_description')[$key],
                'meta_key_seo_cate'     =>  $this->input->post('meta_key_seo_cate')[$key],
                'root_cate'             =>  $this->input->post('root_cate'),
                'parent_cate'           =>  $this->input->post('parent_cate'),
                'lang_id'               =>  $this->input->post('lang_id')[$key],
                'is_pickup'             =>  $this->input->post('is_pickup')==1?1:0,
                'status_cate'           =>  1,
            ];

            if(($this->input->post('parent_cate')==0 || $this->input->post('parent_cate')=='') && $this->input->post('root_cate') !=0){
                $item['parent_cate'] = $this->input->post('root_cate');
            }

            $item['level_cate'] = 0;
            if($this->input->post('root_cate')!=''&&$this->input->post('root_cate')!=0) {
                $item['level_cate']=$item['level_cate']+1;
            }
            if($this->input->post('parent_cate')!=''&&$this->input->post('parent_cate')!=0) {
                $item['level_cate']=$item['level_cate']+1;
            }

            if(/*$upload*/$is_upload == 1) {
                $item['img_category'] = $upload;
            }
            if($value != null) {
                if(!$this->updateData($value, "luxyart_tb_product_category",'cate_id', $item)){
                    return $this->messageUpdate(false);
                }    
            }else{
                $check = $this->insertData("luxyart_tb_product_category", $item);
                if($product_category_id == null) $product_category_id = $check;
                $item_page_seo = ['product_category_id'=>$product_category_id];
                $this->updateData($check, "luxyart_tb_product_category",'cate_id', $item_page_seo);
               if(!$check){
                    return $this->messageCreate(false);
               }
            }
        }
        return $this->messageUpdate(true);
        return $this->res();
    }

    public function delete_category($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_product_category", 'product_category_id'));
    }

    public function pickup_category($id) {
        $item = ['is_pickup'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_product_category','product_category_id',$item));
    }

    public function page_add_edit_category($id) {
        $this->checkLoginAdmin();

        $this->db->where('lang_id',1);
        $data['list_category'] = $this->db->get_where('luxyart_tb_product_category',['lang_id'=>1,'parent_cate'=>'','root_cate'=>''])->result();
        $data['get'] = $this->getData($id, 'luxyart_tb_product_category','cate_id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_category').": ". $data['get']->cate_name;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_category');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'category/edit', $data, __CLASS__);
    }


    // -------------------- Package -------------------
    public function get_list_package($search = false, $pagination = false, $limit = null, $begin = null)
    {
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_list_credit');
        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->where("option_pay", 1);
        $this->db->order_by('id_list','desc');
        return $this->db->get()->result();
    }
    public function page_list_package($page) {
        $this->checkLoginAdmin();
        
        $data = $this->pagination($this->get_list_package(false,false), $page);
        $data['list'] = $this->get_list_package(false,true,$data['limit'],$data['begin']);

        $data['title'] = $this->lang->line('title_page_list_package');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'package/list', $data, __CLASS__);
    }

    public function page_add_edit_package($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_list_credit', 'id_list');
        if(count($data['get'])>0) {
            $data['title']      = $this->lang->line('title_page_add_edit_package').": ". $data['get']->point_credit ;
            $data['type']       = 'edit';
        }else{
            $data['title']      = $this->lang->line('title_page_add_edit_package');
            $data['type']       = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'package/edit', $data, __CLASS__);
    }

    public function add_edit_package() {
        $this->checkLoginAdmin();

        $id = $this->input->post('id');
        $item = [
            'name_credit'               =>   $this->input->post('name_credit'),
            'point_credit'              =>   $this->input->post('point_credit'),
            'money_pay_credit'          =>   $this->input->post('money_pay_credit'),
            'value_point_money'         =>   $this->input->post('value_point_money'),
        ];

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_list_credit",'id_list', $item));    
        }else{
            return $this->messageCreate($this->insertData("luxyart_tb_list_credit", $item));  
        }
        return $this->res();
    }

    public function status_package($id) {
        $item = ['status_credit'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_list_credit','id_list',$item));
    }

    public function delete_package($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_list_credit", 'id_list'));
    }

    // -------------------- Server upload image -------------------
    public function get_list_server($search = false, $pagination = false, $limit = null, $begin = null)
    {
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_manager_server');
        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->order_by('id_server','desc');
        return $this->db->get()->result();
    }
    public function page_list_server($page) {
        $this->checkLoginAdmin();
        
        $data = $this->pagination($this->get_list_server(false,false), $page);
        $data['list'] = $this->get_list_server(false,true,$data['limit'],$data['begin']);

        $data['title'] = "画像の保存サーバー選択";
        $data['keyword'] = $search;
        $data['key_page'] = 'page_list_server';
        $this->template->load('admin', 'server/list', $data, __CLASS__);
    }

    public function page_add_edit_server($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_manager_server', 'id_server');
        if(count($data['get'])>0) {
            $data['title']      = "Edit server: ". $data['get']->server_name ;
            $data['type']       = 'edit';
        }else{
            $data['title']      = "Create server New";
            $data['type']       = 'add';
        }
        $data['key_page'] = 'page_list_server';
        $this->template->load('admin', 'server/edit', $data, __CLASS__);
    }

    public function add_edit_server() {
        $this->checkLoginAdmin();

        $id = $this->input->post('id');
        $item = [
            'server_name'               =>   $this->input->post('server_name'),
            'server_path_upload'        =>   $this->input->post('server_path_upload'),
            'server_path_upload_img'    =>   $this->input->post('server_path_upload_img'),
            'server_ftp_account'        =>   $this->input->post('server_ftp_account'),
            'server_ftp_pass'           =>   $this->input->post('server_ftp_pass'),
            'server_port'               =>   $this->input->post('server_port'),
            'img_store'                 =>   $this->input->post('img_store'),
            'img_count'                 =>   $this->input->post('img_count'),
            'lang_id'                   =>   $this->input->post('lang_id'),
        ];

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_manager_server",'id_server', $item));    
        }else{
            return $this->messageCreate($this->insertData("luxyart_tb_manager_server", $item));  
        }
        return $this->res();
    }

        public function status_server($id) {
            $item = ['server_status'=>$this->input->post('status')];
            return $this->messageUpdate($this->updateData($id, 'luxyart_tb_manager_server','id_server',$item));
        }

    public function delete_server($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_manager_server", 'id_server'));
    }


    // ------------------- Color ----------------------------
    public function get_list_color($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $listKey = ['name','name_color'];

        $this->db->from('luxyart_tb_product_color');
        $this->db->where('lang_id', 1);
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->order_by('id_color','desc');

        return $this->db->get()->result();
    }
    public function page_list_color($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_color(false,false), $page);
        $data['list'] = $this->get_list_color(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_color(true,false), $page);
            $data['list'] = $this->get_list_color(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = "List color";
        $data['keyword'] = $search;
        $data['key_page'] = 'page_list_color';
        $this->template->load('admin', 'color/list', $data, __CLASS__);
    }

    public function add_edit_color() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id_color');
        $group_id = $this->input->post('product_color_id');
        foreach ($id as $key => $value) {
            $item = [
                'name'              =>  $this->input->post('name')[$key],
                'name_color'        =>  $this->input->post('name_color')[$key],
                'css_color_style'   =>  $this->input->post('css_color_style')[$key],
                'lang_id'           =>  $this->input->post('lang_id')[$key],
            ];
            if($value != null) {
                if(!$this->updateData($value, "luxyart_tb_product_color",'id_color', $item)){
                    return $this->messageUpdate(false);
                }    
            }else{
                $check = $this->insertData("luxyart_tb_product_color", $item);
                if($group_id == null) $group_id = $check;
                $item_group = ['product_color_id'=>$group_id,'status'=>1];
                $this->updateData($check, "luxyart_tb_product_color",'id_color', $item_group);
                if(!$check){
                    return $this->messageCreate(false);
                }
            }
        }
        return $this->messageUpdate(true);
        return $this->res();
    }

    public function delete_color($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_product_color", 'product_color_id'));
    }

    public function page_add_edit_color($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_product_color','id_color');

        if(count($data['get'])>0) {
            $data['title']  = "Edit color: ". $data['get']->name_color;
            $data['type']   = 'edit';
        }else{
            $data['title']  = "Create color New";
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_list_color';
        $this->template->load('admin', 'color/edit', $data, __CLASS__);
    }


    // -------------------- Competition -------------------
    public function get_list_competition($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_user_competition as c');
        $this->db->join('luxyart_tb_user as u', 'u.user_id=c.user_id');
        $listKey = ['title_com'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('id_competition','desc');
        return $this->db->get()->result();
    }
    
    // -------------------- Competition -------------------
    public function get_list_comment($search = false, $pagination = false, $limit = null, $begin = null){
    	$this->db->flush_cache();
    	
    	$keyword = $_GET['keyword'];
    	$status = $_GET['status'];
    	
    	$this->db->from('luxyart_tb_comment as a');
    	$this->db->select('*, a.user_id as `a_user_id`');
    	$this->db->join('luxyart_tb_user as b', 'b.user_id=a.user_id');
    	$this->db->join('luxyart_tb_product_img as c', 'c.id_img=a.id_img');
    	$this->db->join('luxyart_tb_manager_server as d', 'd.id_server=c.id_server');
    	//$listKey = ['comment'];
    
    	/*if($search) {
    		$like = '(';
    		foreach ($listKey as $key => $value) {
    			if($key>0) {
    				$like .= " OR ";
    			}
    			$like .= " $value LIKE '%$keyword%' ";
    		}
    		$like.=')';
    		
    		$this->db->where($like, null, false);
    	}*/
    	
    	if($search) {
    	
	    	if($keyword != ""){
	    		$this->db->like('a.comment', $keyword);
	    		$this->db->or_like('b.display_name', $keyword);
	    	}
	    	
	    	if($status != -1){
	    		$this->db->where(array('a.status'=>$status));
	    	}
    	
    	}
    
    	if($pagination) {
    		$this->db->limit($limit, $begin);
    	}
    
    	$this->db->order_by('comment_id','desc');
    	
    	return $this->db->get()->result();
    }
    
    public function get_give_point($search = false, $pagination = false, $limit = null, $begin = null){
    	$this->db->flush_cache();
    	 
    	$keyword = $_GET['keyword'];
    	$user_level = $_GET['user_level'];
    	 
    	$this->db->from('luxyart_tb_user');
    	$this->db->select('*');
    	 
    	if($search) {
    		 
    		if($keyword != ""){
    			$this->db->like('email', $keyword);
    		}
    
    		if($user_level != -1){
    			$this->db->where(array('user_level'=>$user_level));
    		}
    		 
    	}
    
    	if($pagination) {
    		$this->db->limit($limit, $begin);
    	}
    
    	$this->db->order_by('user_id','desc');
    	 
    	return $this->db->get()->result();
    }
    
    public function add_point_user(){
    	
    	session_start();
    	
    	if($_POST){

    		$list_email = $_POST["list_email"];
    		$add_point = $_POST["add_point"];
    		$content = $_POST["content"];
    		$type_point = $_POST["type_point"];
    		
    		$arr_list_email = explode(",",$list_email);
    		
    		$this->db->from('luxyart_tb_user');
    		$this->db->select('*');
    		$this->db->where(array('user_id > '=>0));
    		$this->db->where_in('user_email',$arr_list_email);
    		$this->db->order_by("user_id","desc");
    		
    		$rsUser = $this->db->get();
    		$resultUser = $rsUser->result();
    		
    		$i = 0;
    		
    		foreach($resultUser as $_resultUser){
    		
    			$user_id = $_resultUser->user_id;
    			$user_email = $_resultUser->user_email;
    			$user_point = $_resultUser->user_point;
    			$user_level = $_resultUser->user_level;
    			
    			if(filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
	    		
	    			//update point user
	    			$this->db->where('user_id', $user_id);
	    			if($type_point == 1){//user_point
	    				$this->db->set('user_point','user_point+'.$add_point,FALSE);
	    			}
	    			elseif($type_point == 2){//user_paymoney_getpoint
	    				$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint+'.$add_point,FALSE);
	    			}
	    			$this->db->set('is_recive_lux','is_recive_lux+1',FALSE);
	    			$this->db->update('luxyart_tb_user');
	    			
	    			$inbox_id = $this->function_model->get_inbox_templates_id("content_add_point_to_all_user");
	    			
	    			$notice_point_add_point_to_all_user = $this->lang->line('content_add_point_to_all_user');
	    			$notice_point_add_point_to_all_user = str_replace("{content}", $content, $notice_point_add_point_to_all_user);
	    			$notice_point_add_point_to_all_user = str_replace("{lux}", $add_point, $notice_point_add_point_to_all_user);
	    			
	    			$dataAddPointUser = array(
	    					'user_id' 						=> $user_id,
	    					'point' 						=> $add_point,
	    					'status_change_point' 			=> 1,
	    					'inbox_id' 						=> $inbox_id,
	    					'content' 						=> $notice_point_add_point_to_all_user,
	    					'date_add' 						=> date('Y-m-d H:i:s')
	    			);
	    			$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
	    			
	    			$addNotice = array();
	    			$addNotice['type'] 					= "content_add_point_to_all_user";
	    			$addNotice["lang_id"] 				= $this->lang_id;
	    			$addNotice['user_id'] 				= $user_id;
	    			$addNotice['user_send_id'] 			= -1;
	    			$addNotice['lux'] 					= $add_point;
	    			$addNotice['content'] 				= $content;
	    			//$addNotice['is_debug'] 			= 1;
	    			 
	    			$this->function_model->addNotice($addNotice);
	    			
	    			$i++;
    			
    			}
    		
    		}
    		
    		$upload_num_record_record = $this->lang->line('upload_num_record_record');
    		$upload_num_record_record = str_replace("{num_record}", $i, $upload_num_record_record);
    		
    		$_SESSION['return_msg'] = $upload_num_record_record;
    		$_SESSION['return_link'] = base_url()."admin/give_point";
    		
    		header("Location: ".base_url()."admin/give_point");
    		
    	}
    	
    }
    
    public function add_point_user_type(){
    	 
    	session_start();
    	 
    	if($_POST){
    
    		$user_level = $_POST["user_level"];
    		$add_point = $_POST["add_point"];
    		$content = $_POST["content"];
    		$type_point = $_POST["type_point"];
    
    		$this->db->from('luxyart_tb_user');
    		$this->db->select('*');
    		$this->db->where(array('user_level'=>$user_level));
    		$this->db->order_by("user_id","desc");
    
    		$rsUser = $this->db->get();
    		$resultUser = $rsUser->result();
    
    		$i = 0;
    
    		foreach($resultUser as $_resultUser){
    			
    			$user_id = $_resultUser->user_id;
    
    			//update point user
    			$this->db->where('user_id', $user_id);
    			if($type_point == 1){//user_point
    				$this->db->set('user_point','user_point+'.$add_point,FALSE);
    			}
    			elseif($type_point == 2){//user_paymoney_getpoint
    				$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint+'.$add_point,FALSE);
    			}
    			$this->db->set('is_recive_lux','is_recive_lux+1',FALSE);
    			$this->db->update('luxyart_tb_user');
    			
    			$inbox_id = $this->function_model->get_inbox_templates_id("content_add_point_to_all_user");
    			
    			$notice_point_add_point_to_all_user = $this->lang->line('content_add_point_to_all_user');
    			$notice_point_add_point_to_all_user = str_replace("{content}", $content, $notice_point_add_point_to_all_user);
    			$notice_point_add_point_to_all_user = str_replace("{lux}", $add_point, $notice_point_add_point_to_all_user);
    			
    			$dataAddPointUser = array(
    					'user_id' 						=> $user_id,
    					'point' 						=> $add_point,
    					'status_change_point' 			=> 1,
    					'inbox_id' 						=> $inbox_id,
    					'content' 						=> $notice_point_add_point_to_all_user,
    					'date_add' 						=> date('Y-m-d H:i:s')
    			);
    			$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
    			
    			$addNotice = array();
    			$addNotice['type'] 					= "content_add_point_to_all_user";
    			$addNotice["lang_id"] 				= $this->lang_id;
    			$addNotice['user_id'] 				= $user_id;
    			$addNotice['user_send_id'] 			= -1;
    			$addNotice['lux'] 					= $add_point;
    			$addNotice['content'] 				= $content;
    			//$addNotice['is_debug'] 			= 1;
    			 
    			$this->function_model->addNotice($addNotice);
    			
    			$i++;
    
    		}
    
    		$upload_num_record_record = $this->lang->line('upload_num_record_record');
    		$upload_num_record_record = str_replace("{num_record}", $i, $upload_num_record_record);
    
    		$_SESSION['return_msg'] = $upload_num_record_record;
    		$_SESSION['return_link'] = base_url()."admin/give_point";
    
    		header("Location: ".base_url()."admin/give_point");
    
    	}
    	 
    }
    
    public function page_list_competition($page) {
        $this->checkLoginAdmin();
        
        $data = $this->pagination($this->get_list_competition(false,false), $page);
        $data['list'] = $this->get_list_competition(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_competition(true,false), $page);
            $data['list'] = $this->get_list_competition(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_competition');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_list_competition';
        $this->template->load('admin', 'competition/list', $data, __CLASS__);
    }
    
    public function page_list_comment($page) {
    	$this->checkLoginAdmin();
    
    	$data = $this->pagination($this->get_list_comment(false,false), $page);
    	$data['list'] = $this->get_list_comment(false,true,$data['limit'],$data['begin']);
    
    	$keyword = $_GET['keyword'];
    	$status = $_GET['status'];
    	
    	/*if(status == -1 && $keyword == ""){
    		//page_list_comment?status=-1&keyword=
    	}*/
    	
    	if($keyword != "" || $status != -1) {
    		
    		$data = $this->pagination($this->get_list_comment(true,false), $page);
    		$data['list'] = $this->get_list_comment(true,true,$data['limit'],$data['begin']);
    	}
    	
    	$data['keyword'] = $keyword;
    	$data['status'] = $status;
    
    	$data['title'] = $this->lang->line('title_page_list_comment');
    	$data['key_page'] = 'page_list_comment';
    	$this->template->load('admin', 'comment/list', $data, __CLASS__);
    }

    public function page_add_edit_competition($id) {
        $this->checkLoginAdmin();
        $data['color']        = $this->allData('luxyart_tb_product_color',['lang_id'=>1]);
        $data['user']        = $this->allData('luxyart_tb_user');
        $data['competition_time_agree_id']  = $this->allData('luxyart_tb_competition_time_agree');

        $data['get'] = $this->getData($id, 'luxyart_tb_user_competition', 'id_competition');
        if(count($data['get'])>0) {
            $data['title']      = $this->lang->line('title_page_add_edit_competition').": ". $data['get']->title_com;
            $data['type']       = 'edit';
        }else{
            $data['title']      = $this->lang->line('title_page_add_edit_competition');
            $data['type']       = 'add';
        }
        $data['key_page'] = 'page_list_competition';
        $this->template->load('admin', 'competition/edit', $data, __CLASS__);
    }
    
    public function page_add_edit_comment($id) {
    	$this->checkLoginAdmin();
    	//$data['color']        = $this->allData('luxyart_tb_product_color',['lang_id'=>1]);
    	//$data['user']        = $this->allData('luxyart_tb_user');
    	//$data['competition_time_agree_id']  = $this->allData('luxyart_tb_competition_time_agree');
    
    	$data['get'] = $this->getData($id, 'luxyart_tb_comment', 'comment_id');
    	if(count($data['get'])>0) {
    		$data['title']      = $this->lang->line('title_page_add_edit_comment').": ". $data['get']->comment;
    		$data['type']       = 'edit';
    	}else{
    		$data['title']      = $this->lang->line('title_page_add_edit_comment');
    		$data['type']       = 'add';
    	}
    	$data['key_page'] = 'page_list_comment';
    	$this->template->load('admin', 'comment/edit', $data, __CLASS__);
    }
    
    public function page_add_edit_ngwordlist() {
    	
    	$this->checkLoginAdmin();

    	$whereNGWordList = array('status' => 1);
    	$orderNGWordList = array();
    	$data['ngWordList'] = $this->main_model->getAllData("luxyart_tb_ngwordlist", $whereNGWordList, $orderNGWordList)->result();
    	
    	$i = 0;
    	$strWordList = "";
    	if(count($data['ngWordList'])>0){
    		foreach($data['ngWordList'] as $detailNGWordList){
    			if($i==0){
    				$strWordList = $detailNGWordList->word;
    			}
    			else{
    				$strWordList .= ",".$detailNGWordList->word;
    			}
    			$i++;
    		}
    		$data['strWordList']	= $strWordList;
    		$data['title']      	= $this->lang->line('title_page_add_edit_ngwordlist');
    		$data['type']       	= 'edit';
    	}
    	else{
    		$data['strWordList']	= $strWordList;
    		$data['title']      	= $this->lang->line('title_page_add_edit_ngwordlist');
    		$data['type']       	= 'add';
    	}
    	
    	$data['key_page'] = 'page_list_ngwordlist';
    	$this->template->load('admin', 'ngwordlist/edit', $data, __CLASS__);
    	
    }

    public function add_edit_competition() {
        $this->checkLoginAdmin();

        $user = $this->getData($this->input->post('user_id'), 'luxyart_tb_user','user_id');
        $total = $this->input->post('point_img_com')*$this->input->post('img_quantity_com');
        if($total > ($user->user_paymoney_getpoint+$user->user_point)) {
            $note['note_point'] = 'ユーザーには十分ではない点 !';
        }

        if($this->input->post('img_color_id')==null) {
            $note['note_color'] = '最低1色を選択して下さい。 !';
        }

        

        $id = $this->input->post('id');
        $item = [
            'user_id'                   =>   $this->input->post('user_id'),
            'title_com'                 =>   $this->input->post('title_com'),
            'date_start_com'            =>   date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('date_start_com')))),
            'date_end_com'              =>   date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('date_end_com')))),
            'note_description_com'      =>   $this->input->post('note_description_com'),
            'note_require_com'          =>   $this->input->post('note_require_com'),
            'img_type_com'              =>   $this->input->post('img_type_com'),
            'img_color_id'              =>   implode($this->input->post('img_color_id'), ','),
            'note_img_purpose_com'      =>   $this->input->post('note_img_purpose_com'),
            'point_img_com'             =>   $this->input->post('point_img_com'),
            'img_quantity_com'          =>   $this->input->post('img_quantity_com'),
            'link_url_com'              =>   $this->input->post('link_url_com'),
            'note_another_des_com'      =>   $this->input->post('note_another_des_com'),
            'competition_time_agree_id' =>   $this->input->post('competition_time_agree_id'),
            'is_private'                =>   $this->input->post('is_private'),
            'is_private_img_apply'      =>   $this->input->post('is_private_img_apply'),

        ];

        $get_competition = $this->getData($id, 'luxyart_tb_user_competition', 'id_competition');

        if($_FILES['icon_com']['name']!=''){
            $upload_icon = $this->upload('icon_com','competition_icon_'.time().'.png', 'publics/competition_icon');
            if(is_array($upload_icon)){
                $note['note_icon'] = 'Icon upload error ! Image must format: gif|GIF|jpg|JPG|png|PNG|jpeg|JPEG';
            }else{
                $item['icon_com'] = $upload_icon; 
                if($id != null) 
                    $this->deleteFile('publics/competition_icon/'.$get_competition->icon_com);
            }
        }

        if($_FILES['photo_des_com']['name']!=''){
            $upload_photo = $this->upload('photo_des_com','competition_img_'.time().'.png', 'publics/competition_img');
            if(is_array($upload_photo)){
                $note['note_photo'] = 'Photo upload error ! Image must format: gif|GIF|jpg|JPG|png|PNG|jpeg|JPEG';
            }else{
                $item['photo_des_com'] = $upload_photo; 
                if($id != null) 
                    $this->deleteFile('publics/competition_img/'.$get_competition->photo_des_com);
            }
        }

        if(count($note)>0) {
            $this->res(false, 'いくつかのものをエラー !', $note);
            return $this->res();
        }

        $item['date_update_com']     =  date('Y-m-d H:i:s');
        if($id != null) { 

            $check = $this->updateData($id, "luxyart_tb_user_competition",'id_competition', $item);
            $this->set_time_agree_competition($id);
            return $this->messageUpdate($check);    
        }else{   
            $item['date_add_com']      = date('Y-m-d H:i:s');
            $item['os_register ']       = 3;

            
            $user_paymoney_getpoint = $user->user_paymoney_getpoint;
            $user_point = $user->user_point;
            $remain_user_paymoney_getpoint = $user_paymoney_getpoint - $total;
            if($remain_user_paymoney_getpoint<0){
                $user_paymoney_getpoint = 0;
                $user_point = $user_point + $remain_user_paymoney_getpoint;
            }

            $updateUser = ['user_paymoney_getpoint'=>$user_paymoney_getpoint,'user_point'=>$user_point];
            $this->updateData($user->user_id, 'luxyart_tb_user', 'user_id', $updateUser);
            $check = $this->insertData("luxyart_tb_user_competition", $item);
            $this->set_time_agree_competition($check);
            return $this->messageCreate($check);  
        }
        return $this->res();
    }
    
    public function add_edit_comment() {
    	$this->checkLoginAdmin();
    
    	$id = $this->input->post('id');
    	$comment = $this->input->post('comment');
    	
    	if($id != null) {
    		
    		$this->db->where('comment_id', $id);
    		$this->db->set('comment',$comment);
    		$check = $this->db->update('luxyart_tb_comment');
    		return $this->messageCreate($check);
    		
    	}
    	return $this->res();
    }
    
    public function add_edit_ngwordlist() {
    	$this->checkLoginAdmin();
    
    	$word = $this->input->post('word');
    	 
    	if($word != "") {
    		
    		$this->db->truncate('luxyart_tb_ngwordlist');
    		$word_explode = explode(",",$word);
    		$word_explode = array_unique($word_explode);
    		
    		foreach ($word_explode as $_word_explode){
    			$_word_explode = trim($_word_explode);
    			if($_word_explode != ""){
	    			$item = [
	    				'word'                   =>   $_word_explode,
	    				'status'                 =>   1
	    			];
	    			$check = $this->insertData("luxyart_tb_ngwordlist", $item);
    			}
    		}
    		
    		return $this->messageCreate($check);
    		
    	}
    	return $this->res();
    }
    
    public function give_point($page) {
    	
    	session_start();
    	
    	$this->checkLoginAdmin();
    
    	$data = $this->pagination($this->get_give_point(false,false), $page);
    	$data['list'] = $this->get_give_point(false,true,$data['limit'],$data['begin']);
    
    	$keyword = $_GET['keyword'];
    	$status = $_GET['status'];
    	 
    	/*if(status == -1 && $keyword == ""){
    	 //page_list_comment?status=-1&keyword=
    	}*/
    	 
    	if($keyword != "" || $status != "") {
    
    		$data = $this->pagination($this->get_give_point(true,false), $page);
    		$data['list'] = $this->get_give_point(true,true,$data['limit'],$data['begin']);
    	}
    	 
    	$data['keyword'] = $keyword;
    	$data['status'] = $status;
    	
    	$data['return_msg'] = $_SESSION['return_msg'];
    	$data['return_link'] = $_SESSION['return_link'];
    	
    	$_SESSION['return_msg'] = "";
    	$_SESSION['return_link'] = "";
    
    	$data['title'] = $this->lang->line('title_page_give_point');
    	$data['key_page'] = 'page_give_point';
    	$this->template->load('admin', 'user/give_point', $data, __CLASS__);
    }

    public function set_time_agree_competition($id) {
        $getCompetition = $this->getData($id, 'luxyart_tb_user_competition', 'id_competition');
        $getCompetitionAgree = $this->getData($getCompetition->competition_time_agree_id, 'luxyart_tb_competition_time_agree', 'id');
        if($getCompetitionAgree->type == 2){
            $type = 'week';
        }elseif($getCompetitionAgree->type == 3){
            $type = 'month';
        }else{
            $type = 'days';
        }
        $date_time_agree = date('Y-m-d H:i:s', strtotime('+'.$getCompetitionAgree->name.' '.$type, strtotime($getCompetition->date_end_com)));
        $item['date_time_agree'] = $date_time_agree;
        $item['date_active'] = date('Y-m-d H:i:s');
        return $this->updateData($id, 'luxyart_tb_user_competition','id_competition',$item);
    }

    public function send_mail_active_competition($id) {
        $this->db->flush_cache();
        $this->db->from('luxyart_tb_user_competition as com');
        $this->db->join('luxyart_tb_user as user', 'com.user_id=user.user_id');
        $this->db->where('com.id_competition', $id);
        $this->db->select('*, com.user_id as user_id_abc');
        $get = $this->db->get()->row();

        $dataSendEmail["name_email_code"]       = "active_competition";
        $dataSendEmail["lang_id"]               = 1;
        $dataSendEmail["email_to"]              = $get->user_email;
        $dataSendEmail["name"]                  = $get->user_lastname." ".$get->user_firstname;
        $dataSendEmail["key_1"]                 = $get->title_com;
        $dataSendEmail["key_2"]                 = $get->img_quantity_com;
        $dataSendEmail["key_3"]                 = $get->point_img_com;
        $dataSendEmail["key_4"]                 = $get->date_start_com;
        $dataSendEmail["key_5"]                 = $get->date_end_com;
        $dataSendEmail["key_6"]                 = $get->link_url_com ;

        $this->function_model->sendMailHTML($dataSendEmail);

        $addNotice = array();
		$addNotice['type'] 						= "active_competition";
		$addNotice["lang_id"] 					= 1;
		$addNotice['user_send_id'] 				= -1;
		$addNotice['user_id'] 					= $get->user_id_abc;
		$addNotice['title_com'] 				= $get->title_com;
		$addNotice['link'] 						= base_url("compe-detail/".$get->id_competition.".html");

		$this->function_model->addNotice($addNotice);
    }

    public function status_competition($id) {
        if($this->input->post('status') == 1){
            $this->set_time_agree_competition($id);
            $this->send_mail_active_competition($id);
        }
        $item = ['status_com'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_user_competition','id_competition',$item));
    }
    
    public function status_comment($comment_id) {
    	
    	$status = $this->input->post('status');
    	
    	//get detail comment
    	$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
    	$level = $detail_comment->level;
    	$id_img = $detail_comment->id_img;
    	
    	$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
    	$arr_users[0]["user_id"] = $detail_product->user_id."$$$".$detail_product->user_email."$$$".$detail_product->display_name;
    	
    	if($comment_id > 0){
    	
    		$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
    		$arr_users[1]["user_id"] = $detail_comment->user_id."$$$".$detail_comment->user_email."$$$".$detail_comment->display_name;
    	
    		$comment = $detail_comment->comment;
    	
    		if($detail_comment->level == 2){
    	
    			$detail_comment_root = $this->manager_comment_model->get_detail_comment($detail_comment->comment_id_parent)->row();
    			$arr_users[2]["user_id"]  = $detail_comment_root->user_id."$$$".$detail_comment_root->user_email."$$$".$detail_comment_root->display_name;
    	
    		}
    	
    	}
    	
    	$arr_users = array_unique($arr_users, SORT_REGULAR);
    	
    	//info send email
    	if($detail_product->option_sale == 1){
    		$str_option_sale			= "24 ".$this->lang->line('hour');
    	}
    	elseif($detail_product->option_sale == 2){
    		$str_option_sale			= "1 ".$this->lang->line('week');
    	}
    	elseif($detail_product->option_sale == 3){
    		$str_option_sale			= "1 ".$this->lang->line('month');
    	}
    	else{
    		$str_option_sale			= $this->lang->line('limited_of_time_expired');
    	}
    	
    	if($detail_product->option_img_watermark == 1){
    		$str_type_export_image		= $this->lang->line('blur');
    	}
    	elseif($detail_product->option_img_watermark == 2){
    		$str_type_export_image		= $this->lang->line('watermark');
    	}
    	
    	foreach($arr_users as $_arr_users){
    	
    		$str_user = $_arr_users["user_id"];
    		$str_users = explode("$$$",$str_user);
    	
    		$user_id = $str_users[0];
    		$email_to = $str_users[1];
    		$name = $str_users[2];
    	
    		//send email
    		$dataSendEmail = array();
    		if($status == 0){
    			$dataSendEmail["name_email_code"] 		= "not_active_comment_by_admin";
    		}
    		else{
    			$dataSendEmail["name_email_code"] 		= "active_comment_by_admin";
    		}
    		$dataSendEmail["lang_id"] 				= 1;
    		$dataSendEmail["email_to"] 				= $email_to;
    		$dataSendEmail["name"] 					= ucwords($name);
    		$dataSendEmail["img_title"]				= $detail_product->img_title;
    		$dataSendEmail["img_width"]				= $detail_product->img_width;
    		$dataSendEmail["img_height"]			= $detail_product->img_height;
    		$dataSendEmail["option_sale"]			= $str_option_sale;
    		$dataSendEmail["type_export_image"]		= $str_type_export_image;
    		$dataSendEmail["link"]					= base_url()."detail/".$detail_product->img_code.".html";
    		$dataSendEmail["comment"]				= $comment;
    		$dataSendEmail["email_post_comment"]	= "luxyadvertisement@gmail.com";
    		$dataSendEmail["name_post_comment"]		= "Administrator";
    		//$dataSendEmail["is_debug"]			= 1;
    	
    		$this->function_model->sendMailHTML($dataSendEmail);
    	
    	}
    	
    	if($status == 1){
    		$item = ['status'=>$status];
    	}
    	else{
    		$item = ['status'=>$status];
    	}
    	
    	return $this->messageUpdate($this->updateData($comment_id, 'luxyart_tb_comment','comment_id',$item));
    }
    
    function set_status_comment_by_admin(){
    
    	$arr_users = array();
    
    	$comment_id = $_POST["comment_id"] ? $_POST["comment_id"] : "";
    	$level = $_POST["level"] ? $_POST["level"] : "";
    	$status = $_POST["status"] ? $_POST["status"] : "";
    	$id_img = $_POST["id_img"] ? $_POST["id_img"] : "";
    
    	$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
    	$arr_users[0]["user_id"] = $detail_product->user_id."$$$".$detail_product->user_email."$$$".$detail_product->display_name;
    
    	if($comment_id > 0){
    
    		$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
    		$arr_users[1]["user_id"] = $detail_comment->user_id."$$$".$detail_comment->user_email."$$$".$detail_comment->display_name;
    
    		$comment = $detail_comment->comment;
    
    		if($detail_comment->level == 2){
    
    			$detail_comment_root = $this->manager_comment_model->get_detail_comment($detail_comment->comment_id_parent)->row();
    			$arr_users[2]["user_id"]  = $detail_comment_root->user_id."$$$".$detail_comment_root->user_email."$$$".$detail_comment_root->display_name;
    
    		}
    
    	}
    
    	$arr_users = array_unique($arr_users, SORT_REGULAR);
    
    	//info send email
    	if($detail_product->option_sale == 1){
    		$str_option_sale			= "24 ".$this->lang->line('hour');
    	}
    	elseif($detail_product->option_sale == 2){
    		$str_option_sale			= "1 ".$this->lang->line('week');
    	}
    	elseif($detail_product->option_sale == 3){
    		$str_option_sale			= "1 ".$this->lang->line('month');
    	}
    	else{
    		$str_option_sale			= $this->lang->line('limited_of_time_expired');
    	}
    
    	if($detail_product->option_img_watermark == 1){
    		$str_type_export_image		= $this->lang->line('blur');
    	}
    	elseif($detail_product->option_img_watermark == 2){
    		$str_type_export_image		= $this->lang->line('watermark');
    	}
    
    	foreach($arr_users as $_arr_users){
    
    		$str_user = $_arr_users["user_id"];
    		$str_users = explode("$$$",$str_user);
    
    		$user_id = $str_users[0];
    		$email_to = $str_users[1];
    		$name = $str_users[2];
    
    		//send email
    		$dataSendEmail = array();
    		if($status == 0){
    			$dataSendEmail["name_email_code"] 	= "active_comment_by_admin";
    		}
    		else{
    			$dataSendEmail["name_email_code"] 	= "not_active_comment_by_admin";
    		}
    		$dataSendEmail["lang_id"] 				= $this->lang_id;
    		$dataSendEmail["email_to"] 				= $email_to;
    		$dataSendEmail["name"] 					= ucwords($name);
    		$dataSendEmail["img_title"]				= $detail_product->img_title;
    		$dataSendEmail["img_width"]				= $detail_product->img_width;
    		$dataSendEmail["img_height"]			= $detail_product->img_height;
    		$dataSendEmail["option_sale"]			= $str_option_sale;
    		$dataSendEmail["type_export_image"]		= $str_type_export_image;
    		$dataSendEmail["link"]					= base_url()."detail/".$detail_product->img_code.".html";
    		$dataSendEmail["comment"]				= $comment;
    		$dataSendEmail["email_post_comment"]	= $user->user_email;
    		$dataSendEmail["name_post_comment"]		= $user->display_name;
    		//$dataSendEmail["is_send_admin"]		= 1;
    		//$dataSendEmail["is_debug"]			= 1;
    
    		$this->function_model->sendMailHTML($dataSendEmail);
    
    	}
    
    	$this->db->where('comment_id', $comment_id);
    	if($status == 1){
    		$this->db->update('luxyart_tb_comment',array('status' => 0));
    	}
    	else{
    		$this->db->update('luxyart_tb_comment',array('status' => 1));
    	}
    
    	echo 1;
    	exit;
    
    }

    public function delete_competition($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_user_competition", 'id_competition'));
    }
    
    public function delete_comment($comment_id) {
    	 
    	//get detail comment
    	$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
    	$level = $detail_comment->level;
    	$id_img = $detail_comment->id_img;
    	 
    	$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
    	$arr_users[0]["user_id"] = $detail_product->user_id."$$$".$detail_product->user_email."$$$".$detail_product->display_name;
    	 
    	if($comment_id > 0){
    		 
    		$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
    		$arr_users[1]["user_id"] = $detail_comment->user_id."$$$".$detail_comment->user_email."$$$".$detail_comment->display_name;
    		 
    		$comment = $detail_comment->comment;
    		 
    		if($detail_comment->level == 2){
    			 
    			$detail_comment_root = $this->manager_comment_model->get_detail_comment($detail_comment->comment_id_parent)->row();
    			$arr_users[2]["user_id"]  = $detail_comment_root->user_id."$$$".$detail_comment_root->user_email."$$$".$detail_comment_root->display_name;
    			 
    		}
    		 
    	}
    	 
    	$arr_users = array_unique($arr_users, SORT_REGULAR);
    	 
    	//info send email
    	if($detail_product->option_sale == 1){
    		$str_option_sale			= "24 ".$this->lang->line('hour');
    	}
    	elseif($detail_product->option_sale == 2){
    		$str_option_sale			= "1 ".$this->lang->line('week');
    	}
    	elseif($detail_product->option_sale == 3){
    		$str_option_sale			= "1 ".$this->lang->line('month');
    	}
    	else{
    		$str_option_sale			= $this->lang->line('limited_of_time_expired');
    	}
    	 
    	if($detail_product->option_img_watermark == 1){
    		$str_type_export_image		= $this->lang->line('blur');
    	}
    	elseif($detail_product->option_img_watermark == 2){
    		$str_type_export_image		= $this->lang->line('watermark');
    	}
    	 
    	foreach($arr_users as $_arr_users){
    		 
    		$str_user = $_arr_users["user_id"];
    		$str_users = explode("$$$",$str_user);
    		 
    		$user_id = $str_users[0];
    		$email_to = $str_users[1];
    		$name = $str_users[2];
    		 
    		//send email
    		$dataSendEmail = array();
    		$dataSendEmail["name_email_code"] 		= "delete_comment_by_admin";
    		$dataSendEmail["lang_id"] 				= 1;
    		$dataSendEmail["email_to"] 				= $email_to;
    		$dataSendEmail["name"] 					= ucwords($name);
    		$dataSendEmail["img_title"]				= $detail_product->img_title;
    		$dataSendEmail["img_width"]				= $detail_product->img_width;
    		$dataSendEmail["img_height"]			= $detail_product->img_height;
    		$dataSendEmail["option_sale"]			= $str_option_sale;
    		$dataSendEmail["type_export_image"]		= $str_type_export_image;
    		$dataSendEmail["link"]					= base_url()."detail/".$detail_product->img_code.".html";
    		$dataSendEmail["comment"]				= $comment;
    		$dataSendEmail["email_post_comment"]	= "luxyadvertisement@gmail.com";
    		$dataSendEmail["name_post_comment"]		= "Administrator";
    		//$dataSendEmail["is_debug"]			= 1;
    		 
    		$this->function_model->sendMailHTML($dataSendEmail);
    		 
    	}
    	 
    	return $this->messageDelete($this->deleteData($comment_id, "luxyart_tb_comment", 'comment_id'));
    }

    // ------------------- INBOX ----------------------------
    public function get_list_inbox($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $listKey = ['name','subject','message'];

        $this->db->from('luxyart_tb_inbox_templates');
        $this->db->where('lang_id', 1);
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        $this->db->where('lang_id', 1);
        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->order_by('inbox_templates_id','desc');

        return $this->db->get()->result();
    }
    public function page_list_inbox($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_inbox(false,false), $page);
        $data['list'] = $this->get_list_inbox(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_inbox(true,false), $page);
            $data['list'] = $this->get_list_inbox(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_inbox');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'inbox/list', $data, __CLASS__);
    }

    public function add_edit_inbox() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('inbox_templates_id');
        $group_id = $this->input->post('group_id');
        foreach ($id as $key => $value) {
            $item = [
                'name'          =>  $this->input->post('name'),
                'subject'       =>  $this->input->post('subject')[$key],
                'message'       =>  $this->input->post('message')[$key],
                'link'          =>  $this->input->post('link')[$key],
                'status'        =>  1,
                'lang_id'       =>  $this->input->post('lang_id')[$key],
            ];
            if($value != null) {
                if(!$this->updateData($value, "luxyart_tb_inbox_templates",'inbox_templates_id', $item)){
                    return $this->messageUpdate(false);
                }    
            }else{
                $check = $this->insertData("luxyart_tb_inbox_templates", $item);
                if($group_id == null) $group_id = $check;
                $item_page_seo = ['group_id'=>$group_id];
                $this->updateData($check, "luxyart_tb_inbox_templates",'inbox_templates_id', $item_page_seo);
               if(!$check){
                    return $this->messageCreate(false);
               }
            }
        }
        return $this->messageUpdate(true);
        return $this->res();
    }

    public function status_inbox($id) {
        $item = ['status'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_inbox_templates','inbox_templates_id',$item));
    }

    public function delete_inbox($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_inbox_templates", 'group_id'));
    }

    public function page_add_edit_inbox($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_inbox_templates','inbox_templates_id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_inbox').": ". $data['get']->subject;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_inbox');;
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'inbox/edit', $data, __CLASS__);
    }

    // ------------------- TAGS ----------------------------
    public function get_list_tags($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_product_tags');
        $listKey = ['name','value'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }
        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('id_product_tag','desc');
        return $this->db->get()->result();
    }
    public function page_list_tags($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_tags(false,false), $page);
        $data['list'] = $this->get_list_tags(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_tags(true,false), $page);
            $data['list'] = $this->get_list_tags(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_tags');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'tags/list', $data, __CLASS__);
    }

    public function add_edit_tags() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id');
        $item = [
            'tag_name'              =>  $this->input->post('tag_name'),
            'root_cate'             =>  $this->input->post('root_cate'),
            'parent_cate'             =>  $this->input->post('parent_cate'),
        ];

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_product_tags",'id_product_tag', $item));      
        }else{
            return $this->messageCreate($this->insertData("luxyart_tb_product_tags", $item));  
        }
        return $this->res();
    }

    public function delete_tags($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_product_tags", 'id_product_tag'));
    }

    public function page_add_edit_tags($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_product_tags','id_product_tag');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_tags').": ". $data['get']->name;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_tags');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'tags/edit', $data, __CLASS__);
    }
    
    public function status_tags($id) {
        $item = ['status'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_product_tags','id_product_tag',$item));
    }

    // ------------------- Competition time agree ----------------------------
    public function get_list_c_agree($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_competition_time_agree');
        $listKey = ['name','type'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('id','desc');
        return $this->db->get()->result();
    }
    public function page_list_c_agree($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_c_agree(false,false), $page);
        $data['list'] = $this->get_list_c_agree(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_c_agree(true,false), $page);
            $data['list'] = $this->get_list_c_agree(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_c_agree');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'competition_time_agree/list', $data, __CLASS__);
    }

    public function add_edit_c_agree() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id');
        $item = [
            'name'              =>  $this->input->post('name'),
            'type'              =>  $this->input->post('type'),
        ];

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_competition_time_agree",'id', $item));      
        }else{
            $item['status'] = 1;
            return $this->messageCreate($this->insertData("luxyart_tb_competition_time_agree", $item));  
        }
        return $this->res();
    }

    public function delete_c_agree($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_competition_time_agree", 'id'));
    }

    public function status_c_agree($id) {
        $item = ['status'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_competition_time_agree','id',$item));
    }

    public function page_add_edit_c_agree($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_competition_time_agree','id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_c_agree').": ". $data['get']->name;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_c_agree');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'competition_time_agree/edit', $data, __CLASS__);
    }

    // ------------------- Return tranfer ----------------------------
    public function bank_info($id){
        $this->db->flush_cache();
        $this->db->from('luxyart_tb_user as user');
        $this->db->where('user.user_id', $id);
        $this->db->join('luxyart_tb_bank_infomation as i','i.user_id=user.user_id');
        $data['get'] = $this->db->get()->row();
        $this->load->view('admin/admin/return_money/bank_info.php', $data);
    }
    public function get_list_return_money($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_tranfer_money_user as m');
        $this->db->join('luxyart_tb_user as u','u.user_id=m.user_id','LEFT');
        $listKey = ['user_firstname','user_lastname','user_email'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('tranfer_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_return_money($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_return_money(false,false), $page);
        $data['list'] = $this->get_list_return_money(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_return_money(true,false), $page);
            $data['list'] = $this->get_list_return_money(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_return_money');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_list_return_money';
        $this->template->load('admin', 'return_money/list', $data, __CLASS__);
    }

    public function delete_return_money($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_tranfer_money_user", 'tranfer_id'));
    }

    public function send_mail_return_money($id) {
        $this->db->flush_cache();
        $this->db->from('luxyart_tb_tranfer_money_user as tr');
        $this->db->join('luxyart_tb_user as u','tr.user_id=u.user_id','LEFT');
        $this->db->where('tr.tranfer_id', $id);
        $this->db->select('*,tr.user_id as user_id_abc');
        $get = $this->db->get()->row();

        $dataSendEmail["name_email_code"]       = "export_money";
        $dataSendEmail["lang_id"]               = 1;
        $dataSendEmail["email_to"]              = $get->user_email;
        $dataSendEmail["name"]                  = $get->user_lastname." ".$get->user_firstname;
        $dataSendEmail["key_1"]                 = $get->tranfer_point_require;
        $dataSendEmail["key_2"]                 = number_format($get->money_user_get,0,",",",");
        if($get->tranfer_status==2){
        	$dataSendEmail["key_3"]             = $this->lang->line('transferred');
        }else{
        	$dataSendEmail["key_3"]             = $this->lang->line('requested');
        }
        $this->function_model->sendMailHTML($dataSendEmail);

        $addNotice = array();
        $addNotice['type']                      = "export_money";
        $addNotice["lang_id"]                   = 1;
        $addNotice['user_send_id']              = -1;
        $addNotice['user_id']                   = $get->user_id_abc;
        $addNotice['point']                     = $get->tranfer_point_require;
        $this->function_model->addNotice($addNotice);
    }


    public function status_return_money($id) {
        
        $item = ['tranfer_status'=>$this->input->post('status'),'date_tranfer'=>date("Y-m-d H:i:s")];
        $check = $this->updateData($id, 'luxyart_tb_tranfer_money_user','tranfer_id',$item);
        if($check){
            $this->send_mail_return_money($id);
        }
        return $this->messageUpdate($check);
    }

    // ------------------- MENU ----------------------------
    public function get_list_menu($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_load_menu');
        $listKey = ['menu_ja','menu_vi','menu_en'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('id_menu','desc');
        return $this->db->get()->result();
    }
    public function page_list_menu($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_menu(false,false), $page);
        $data['list'] = $this->get_list_menu(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_menu(true,false), $page);
            $data['list'] = $this->get_list_menu(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_menu');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'menu/list', $data, __CLASS__);
    }

    public function add_edit_menu() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('id');
        $item = [
            'menu_ja'              =>  $this->input->post('menu_ja'),
            'menu_vi'              =>  $this->input->post('menu_vi'),
            'menu_en'              =>  $this->input->post('menu_en'),
            'link_url'             =>  $this->input->post('link_url'),
            'is_header'            =>  $this->input->post('is_header'),
            'is_footer'            =>  $this->input->post('is_footer'),
            'position_menu'        =>  $this->input->post('position_menu'),
        ];

        if($id != null) {
            return $this->messageUpdate($this->updateData($id, "luxyart_tb_load_menu",'id_menu', $item));      
        }else{
            return $this->messageCreate($this->insertData("luxyart_tb_load_menu", $item));  
        }
        return $this->res();
    }

    public function delete_menu($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_load_menu", 'id_menu'));
    }

    // public function status_menu($id) {
    //     $item = ['status'=>$this->input->post('status')];
    //     return $this->messageUpdate($this->updateData($id, 'luxyart_tb_load_menu','id',$item));
    // }

    public function page_add_edit_menu($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_load_menu','id_menu');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_menu').": ". $data['get']->menu_ja;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_menu');
            $data['type']   = 'add';
        }
        $data['key_page'] = 'page_menu';
        $this->template->load('admin', 'menu/edit', $data, __CLASS__);
    }

    // ------------------- User follow ----------------------------
    public function get_list_user_follow($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_user_follow as f');
        $this->db->join('luxyart_tb_user as u', 'u.user_id = f.user_id');
        $this->db->join('luxyart_tb_user as uf','uf.user_id = f.follow_user_id');
        $listKey = ['user_firstname','user_lastname'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->select('*,uf.user_firstname as user_follow_firstname, uf.user_lastname as user_follow_lastname, u.user_firstname as user_firstname, u.user_lastname as user_lastname, uf.user_id as user_follow_id, u.user_id as user_id');
        $this->db->order_by('id_follow','desc');
        return $this->db->get()->result();
    }
    public function page_list_user_follow($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_user_follow(false,false), $page);
        $data['list'] = $this->get_list_user_follow(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_user_follow(true,false), $page);
            $data['list'] = $this->get_list_user_follow(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = "List  user follow";
        $data['keyword'] = $search;
        $this->template->load('admin', 'user_follow/list', $data, __CLASS__);
    }


    public function delete_user_follow($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_user_follow", 'id_follow'));
    }

    // ------------------- Bookmark image ----------------------------
    public function get_list_bookmark_img($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_user_bookmark_image as f');
        $this->db->join('luxyart_tb_user as u', 'u.user_id = f.user_id');
        $this->db->join('luxyart_tb_product_img as uf','uf.id_img = f.id_img');
        $listKey = ['user_firstname','user_lastname', 'img_title'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->select('*,f.user_id as user_id_abc');
        $this->db->order_by('id_bookmark','desc');
        return $this->db->get()->result();
    }
    public function page_list_bookmark_img($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_bookmark_img(false,false), $page);
        $data['list'] = $this->get_list_bookmark_img(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_bookmark_img(true,false), $page);
            $data['list'] = $this->get_list_bookmark_img(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = "List bookmark image";
        $data['keyword'] = $search;
        $this->template->load('admin', 'bookmark_img/list', $data, __CLASS__);
    }


    public function delete_bookmark_img($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_user_bookmark_image", 'id_bookmark'));
    }

    // ------------------- User view image ----------------------------
    public function get_list_user_view_img($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_user_view_product_img as f');
        $this->db->join('luxyart_tb_user as u', 'u.user_id = f.user_id');
        $this->db->join('luxyart_tb_product_img as uf','uf.id_img = f.id_img');
        $listKey = ['user_firstname','user_lastname', 'img_title'];

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->select('*,f.user_id as user_id_abc');
        $this->db->order_by('id_view','desc');
        return $this->db->get()->result();
    }
    public function page_list_user_view_img($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_user_view_img(false,false), $page);
        $data['list'] = $this->get_list_user_view_img(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_user_view_img(true,false), $page);
            $data['list'] = $this->get_list_user_view_img(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = "List user view image";
        $data['keyword'] = $search;
        $this->template->load('admin', 'user_view_img/list', $data, __CLASS__);
    }


    public function delete_user_view_img($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_user_view_product_img", 'id_view'));
    }

    // ------------------- Image apply ----------------------------
    public function get_list_image_apply($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $competition_id = $_GET['competition_id'];
        $this->db->from('luxyart_tb_competition_img_apply as a');
        $this->db->join('luxyart_tb_user as u', 'u.user_id = a.user_id','LEFT');
        $this->db->join('luxyart_tb_product_img as uf','uf.id_img = a.id_img','LEFT');
        $this->db->join('luxyart_tb_manager_server as s', 'uf.id_server = s.id_server','LEFT');
        $this->db->join('luxyart_tb_user_competition as c','c.id_competition = a.id_competition','LEFT');
        $listKey = ['user_firstname','user_lastname', 'img_title','title_com'];

        if($competition_id!=null) {
            $this->db->where('a.id_competition',$competition_id);
        }
        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->select('*,a.user_id as user_id_abc,c.id_competition as id_competition_abc');
        $this->db->order_by('id_apply','desc');
        return $this->db->get()->result();
    }
    public function page_list_image_apply($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_image_apply(false,false), $page);
        $data['list'] = $this->get_list_image_apply(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_image_apply(true,false), $page);
            $data['list'] = $this->get_list_image_apply(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_image_apply');;
        $data['keyword'] = $search;
        $data['competition_id'] = $_GET['competition_id'];
        $this->template->load('admin', 'image_apply/list', $data, __CLASS__);
    }


    public function delete_image_apply($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_competition_img_apply", 'id_apply'));
    }

    // -------------------- User order -------------------
    public function get_list_user_order($search = false, $pagination = false, $limit = null, $begin = null)
    {
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_user_order_credit as oc');
        $this->db->join('luxyart_tb_user as u', 'oc.user_id=u.user_id');
        $this->db->join('luxyart_tb_list_credit as l', 'l.id_list=oc.id_list');
        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->order_by('id_ordercredit','desc');
        return $this->db->get()->result();
    }
    public function page_list_user_order($page) {
        $this->checkLoginAdmin();
        
        $data = $this->pagination($this->get_list_user_order(false,false), $page,20);
        $data['list'] = $this->get_list_user_order(false,true,$data['limit'],$data['begin']);

        $data['title'] = $this->lang->line('title_page_list_user_order');
        $data['keyword'] = $search;
        $data['key_page'] = 'page_list_user_order';
        $this->template->load('admin', 'user_order/list', $data, __CLASS__);
    }
    public function add_point_user_active_user_order($id) {
        $this->db->from('luxyart_tb_user_order_credit as c');
        $this->db->join('luxyart_tb_user as u','u.user_id=c.user_id','LEFT');
        $this->db->join('luxyart_tb_list_credit as l','c.id_list=l.id_list','LEFT');
        $this->db->where('c.id_ordercredit', $id);
        $get = $this->db->get()->row();
        $point_current = $get->user_paymoney_getpoint;
        $point = $point_current + $get->point_credit;
        $item['user_paymoney_getpoint'] = $point;
        $this->updateData($get->user_id, 'luxyart_tb_user','user_id',$item);
    }
    public function send_mail_user_active_user_order($id) {
        $this->db->from('luxyart_tb_user_order_credit as c');
        $this->db->join('luxyart_tb_user as u','u.user_id=c.user_id','LEFT');
        $this->db->join('luxyart_tb_list_credit as l','c.id_list=l.id_list','LEFT');
        $this->db->where('c.id_ordercredit', $id);
        $get = $this->db->get()->row();

        $dataSendEmail["name_email_code"]       = "notification_receive_point";
        $dataSendEmail["lang_id"]               = 1;
        $dataSendEmail["email_to"]              = $get->user_email;
        $dataSendEmail["name"]                  = $get->user_lastname." ".$get->user_firstname;
        $dataSendEmail["key_1"]                 = $get->user_email;
        $dataSendEmail["key_2"]                 = $get->name_credit;
        $dataSendEmail["key_3"]                 = $get->point_credit;
        $dataSendEmail["key_4"]                 = $get->user_paymoney_getpoint;
        $dataSendEmail["key_5"]                 = $get->payment_status==1? $this->lang->line('received') : $this->lang->line('wait');

        $this->function_model->sendMailHTML($dataSendEmail);
    }
    public function status_user_order($id) {
        $item = ['payment_status'=>$this->input->post('status')];
        $check = $this->updateData($id, 'luxyart_tb_user_order_credit','id_ordercredit',$item);
        if($check && $this->input->post('status') == 1){
            $this->add_point_user_active_user_order($id);
            $this->send_mail_user_active_user_order($id);
            $this->add_order_credit();
        }
        return $this->messageUpdate($check);
    }

    public function add_order_credit() {
        $user_id = $this->input->post('user_id');
        $credit_id = $this->input->post('credit_id');
        $point = $this->input->post('point');
        $query = $this->db->query('SELECT id_ordercredit FROM luxyart_tb_user_order_credit WHERE user_id="'.$user_id.'"');
        $status = 0;
        if($query->num_rows() <= 1) $status = 1;
        $insert_use_point_package = array(
            'user_id'           => $user_id,
            'id_list'           => $credit_id,
            'value_point_pk'    => $point,
            'history_use_point' => 0,
            'status'            => $status,
            'date_add'          => date('Y-m-d H:i:s'),
        );
        $this->db->insert('luxyart_tb_user_use_point_package', $insert_use_point_package);
    }

    // ------------------- QA Category ----------------------------
    public function get_list_qa_category($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_qa_category');
        $listKey = ['title','content'];

        $this->db->where('lang_id', 1);

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }

        $this->db->order_by('category_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_qa_category($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_qa_category(false,false), $page);
        $data['list'] = $this->get_list_qa_category(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_qa_category(true,false), $page);
            $data['list'] = $this->get_list_qa_category(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_qa_category');
        $data['keyword'] = $search;
        $this->template->load('admin', 'qa_category/list', $data, __CLASS__);
    }

    public function add_edit_qa_category() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('category_id');
        $group_id = $this->input->post('group_id');
        foreach ($id as $key => $value) {
            $item = [
                'title'         =>  $this->input->post('title')[$key],
                'category_title_seo'         =>  $this->input->post('category_title_seo')[$key],
                'category_keyword_seo'         =>  $this->input->post('category_keyword_seo')[$key],
                'category_description_seo'         =>  $this->input->post('category_description_seo')[$key],
                'lang_id'       =>  $this->input->post('lang_id')[$key],
                'status'        =>  1,
            ];
            if($value != null) {
                if(!$this->updateData($value, "luxyart_tb_qa_category",'category_id', $item)){
                    return $this->messageUpdate(false);
                }    
            }else{
                $check = $this->insertData("luxyart_tb_qa_category", $item);
                if($group_id == null) $group_id = $check;
                $item_page_seo = ['group_id'=>$group_id];
                $this->updateData($check, "luxyart_tb_qa_category",'category_id', $item_page_seo);
               if(!$check){
                    return $this->messageCreate(false);
               }
            }
        }
        return $this->messageUpdate(true);
        return $this->res();
    }

    public function status_qa_category($id) {
        $item = ['status'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_qa_category','category_id',$item));
    }

    public function delete_qa_category($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_qa_category", 'group_id'));
    }

    public function page_add_edit_qa_category($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_qa_category','category_id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_qa_category').": ". $data['get']->title;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_qa_category');
            $data['type']   = 'add';
        }

        $this->template->load('admin', 'qa_category/edit', $data, __CLASS__);
    }

    // ------------------- QA ----------------------------
    public function get_list_qa($search = false, $pagination = false, $limit = null, $begin = null){
        $this->db->flush_cache();
        $keyword = $_GET['keyword'];
        $this->db->from('luxyart_tb_qa as q');
        $this->db->join('luxyart_tb_qa_category as c', 'c.category_id=q.category_id','LEFT');
        $listKey = ['q.title','q.content','c.title'];

        $this->db->where('q.lang_id', 1);

        if($search) {
            $like = '(';
            foreach ($listKey as $key => $value) {
                if($key>0) {
                    $like .= " OR ";
                }
                $like .= " $value LIKE '%$keyword%' ";
            }
            $like.=')';
            $this->db->where($like, null, false);
        }

        if($pagination) {
            $this->db->limit($limit, $begin);
        }
        $this->db->select('*,c.title as category_title,q.title as qa_title');
        $this->db->order_by('q.qa_id','desc');
        return $this->db->get()->result();
    }
    public function page_list_qa($page) { 
        $this->checkLoginAdmin();

        $data = $this->pagination($this->get_list_qa(false,false), $page);
        $data['list'] = $this->get_list_qa(false,true,$data['limit'],$data['begin']);

        $search = $_GET['keyword'];
        if($search != null) {
            $data = $this->pagination($this->get_list_qa(true,false), $page);
            $data['list'] = $this->get_list_qa(true,true,$data['limit'],$data['begin']);
        }

        $data['title'] = $this->lang->line('title_page_list_qa');
        $data['keyword'] = $search;
        $this->template->load('admin', 'qa/list', $data, __CLASS__);
    }

    public function add_edit_qa() {
        $this->checkLoginAdmin();
        
        $id = $this->input->post('qa_id');
        $group_id = $this->input->post('group_id');
        foreach ($id as $key => $value) {
            $item = [
                'title'         =>  $this->input->post('title')[$key],
                'content'         =>  $this->input->post('content')[$key],
                'title_seo'         =>  $this->input->post('title_seo')[$key],
                'keyword_seo'         =>  $this->input->post('keyword_seo')[$key],
                'description_seo'         =>  $this->input->post('description_seo')[$key],
                'lang_id'       =>  $this->input->post('lang_id')[$key],
                'category_id'       =>  $this->input->post('category_id'),
                'status'        =>  1,
            ];
            if($value != null) {
                if(!$this->updateData($value, "luxyart_tb_qa",'qa_id', $item)){
                    return $this->messageUpdate(false);
                }    
            }else{
                $check = $this->insertData("luxyart_tb_qa", $item);
                if($group_id == null) $group_id = $check;
                $item_page_seo = ['group_id'=>$group_id];
                $this->updateData($check, "luxyart_tb_qa",'qa_id', $item_page_seo);
               if(!$check){
                    return $this->messageCreate(false);
               }
            }
        }
        return $this->messageUpdate(true);
        return $this->res();
    }

    public function status_qa($id) {
        $item = ['status'=>$this->input->post('status')];
        return $this->messageUpdate($this->updateData($id, 'luxyart_tb_qa','qa_id',$item));
    }

    public function delete_qa($id) {
        return $this->messageDelete($this->deleteData($id, "luxyart_tb_qa", 'group_id'));
    }

    public function page_add_edit_qa($id) {
        $this->checkLoginAdmin();

        $data['get'] = $this->getData($id, 'luxyart_tb_qa','qa_id');

        if(count($data['get'])>0) {
            $data['title']  = $this->lang->line('title_page_add_edit_qa').": ". $data['get']->title;
            $data['type']   = 'edit';
        }else{
            $data['title']  = $this->lang->line('title_page_add_edit_qa');
            $data['type']   = 'add';
        }

        $this->template->load('admin', 'qa/edit', $data, __CLASS__);
    }


    // ------------------------------------------------------------------------------------
    //                                     Anh Tuan
    // -------------------------------------------------------------------------------------

    public function res($isSuccess = null, $message = null, $data = null) 
    {
        if(!is_null($isSuccess)) {
          if(is_bool($isSuccess)) {
            if($isSuccess) $this->data['success'] = $message;
            else $this->data['error'] = $message;
          }else if(is_string($isSuccess)){
            $this->data['message'] = $isSuccess;
          }else if(is_array($isSuccess)) {
            $this->data['data'] = $isSuccess;
          }   
        }

        if(!is_null($message)) {
          $this->data['message'] = $message;
        }

        if(!is_null($data)) {
          $this->data['data'] = $data;
        }

        if(is_null($isSuccess)){
          echo json_encode($this->data);
        }
    }



    /**

    * Change Language

    *

    * @access   public

    * @param    nil

    * @return   void

    * @author   dohuuthien 2013-12-04

    */

    public function change_language(){

        

        $data = array();

        

        //read file

        $my_file = 'application/language/vietnam/dich_lang.php';

        $handle = fopen($my_file, 'r');

        $data['content'] = fread($handle,filesize($my_file));

        

        if($this->input->post('change',TRUE)){

            

            $data = array();

            

            //get data

            $data['content']                    = $this->input->post('content',TRUE);

            

            //write file

            $my_file = 'application/language/vietnam/dich_lang.php';

            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

            fwrite($handle, html_entity_decode($data['content']));

            

            //notification message

            $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Thanks! You change language sucessfully'));

            redirect(base_url('admin/change_language'),'refresh');

            

            return;

            

        }

        

        $this->template->load('admin', 'change_language', $data, __CLASS__);

        

    }

    

    /**

     * Change Language English

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author  dohuuthien 2013-12-04

     */

    public function change_language_en(){

         

        $data = array();

         

        //read file

        $my_file = 'application/language/english/dich_lang.php';

        $handle = fopen($my_file, 'r');

        $data['content'] = fread($handle,filesize($my_file));

         

        if($this->input->post('change',TRUE)){

    

            $data = array();

    

            //get data

            $data['content']                    = $this->input->post('content',TRUE);

    

            //write file

            $my_file = 'application/language/english/dich_lang.php';

            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

            fwrite($handle, html_entity_decode($data['content']));

    

            //notification message

            $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Thanks! You change language english sucessfully'));

            redirect(base_url('admin/change_language_en'),'refresh');

    

            return;

    

        }

         

        $this->template->load('admin', 'change_language_en', $data, __CLASS__);

         

    }

    

    /**

     * Change Language Japan

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author  dohuuthien 2013-12-04

     */

    public function change_language_jp(){

    

        $data = array();

    

        //read file

        $my_file = 'application/language/janpan/dich_lang.php';

        $handle = fopen($my_file, 'r');

        $data['content'] = fread($handle,filesize($my_file));

    

        if($this->input->post('change',TRUE)){

    

            $data = array();

    

            //get data

            $data['content']                    = $this->input->post('content',TRUE);

    

            //write file

            $my_file = 'application/language/janpan/dich_lang.php';

            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

            fwrite($handle, html_entity_decode($data['content']));

    

            //notification message

            $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Thanks! You change language japan sucessfully'));

            redirect(base_url('admin/change_language_jp'),'refresh');

    

            return;

    

        }

    

        $this->template->load('admin', 'change_language_jp', $data, __CLASS__);

    

    }

    

    public function info(){

        

        $msg = $this->session->flashdata('msg');

        if(isset($msg) and $msg!=""){

            $title = $this->session->flashdata('title_page');

            $description = $this->session->flashdata('description_page');

            $keywords = $this->session->flashdata('keywords');

            $return_page = $this->session->flashdata('return_page');

            $data['msg'] = $msg;

            $data['return_page'] = $return_page;

            $data['title_page'] = $title;

            $data['description_page'] = $description;

            $data['keywords'] = $keywords;

            $this->template->load('admin', 'info', $data, __CLASS__);

        }

        else{

            redirect(base_url()."admin");

        }

        

    }


   

    

    /**

     * Manager Percent System

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author dohuuthien 2014-11-18

     */

    

    public function manager_percent_system(){

        

        $this->load->model('email_model');

        

        //load validation library

        $this->load->library('form_validation');        

        //Load Form Helper

        $this->load->helper('form');

        

        $data['role']   = $this->session->userdata('role');

        

        //Intialize values for library and helpers  

        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        

        if($this->input->post('manager_percent_system')){

            

            $this->form_validation->set_rules('admin_percent_system_fee','lang:percent system fee','required|numeric|greater_than[0]');

            $this->form_validation->set_rules('percent_vat','lang:percent vat','required|numeric|greater_than[0]');

            

            $data['updateAdmins'] = array();

            $data['updateAdmins']['admin_percent_system_fee']       = $this->input->post('admin_percent_system_fee');

            $data['updateAdmins']['percent_vat']                    = $this->input->post('percent_vat');

            

            if($this->form_validation->run()){  

    

                $conditionAdmins = array('fl_admin.admin_id'=> 1);

                $this->account_model->updateAdmins($conditionAdmins, $data['updateAdmins']);

                  

                //Notification message

                $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Updated success!'));

                redirect(base_url('admin/manager_percent_system'),'refresh');

                

            } 

            

            

        }



        $conditionAdmin = array('fl_admin.admin_id' => 1, 'fl_admin.admin_status' => 1);

        $data['infoAdmins'] = $this->admin_model->getAdmins($conditionAdmin)->row();

        

        //Load View

        $this->template->load('admin', 'manager_percent_system', $data, __CLASS__);

        

    }

    

    /**

     * Manager Change Password

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author dohuuthien 2014-11-18

     */

    

    public function change_password(){

        

        //load validation library

        $this->load->library('form_validation');

        

        //Load Form Helper

        $this->load->helper('form');

        

        //Intialize values for library and helpers

        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

    

        $data['role']   = $this->session->userdata('role'); 

    

        if($this->input->post('change_password')){

                

            $this->form_validation->set_rules('password_old','lang:password old','required|trim|min_length[5]|max_length[16]|xss_clean|callback_checkExitsPassword');

            $this->form_validation->set_rules('password_new','lang:password new','required|trim|min_length[5]|max_length[16]|xss_clean');

            $this->form_validation->set_rules('password_new_confirm','lang:password new confirm','required|trim|min_length[5]|max_length[16]|matches[password_new]');

                

            $data['updateAdmins'] = array();

            $data['updateAdmins']['password_old']           = $this->input->post('password_old');

            $data['updateAdmins']['password_new']           = $this->input->post('password_new');

            $data['updateAdmins']['password_new_confirm']   = $this->input->post('password_new_confirm');

                

            if($this->form_validation->run()){

                

                $data['change_password'] = array();

                $data['change_password']['admin_pass']      = md5($this->input->post('password_new'));

                $conditionAdmins = array('fl_admin.admin_id'=>1);

                $this->account_model->updateAdmins($conditionAdmins, $data['change_password']);

                

                $this->auth_model->clearAdminSession();

    

                //Notification message

                $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Change password success!'));

                redirect(base_url('admin/login'),'refresh');

    

            }

                

                

        }

    

        $conditionAdmin = array('fl_admin.admin_id' => 1, 'fl_admin.admin_status' => 1);

        $data['infoAdmins'] = $this->admin_model->getAdmins($conditionAdmin)->row();

    

        //Load View

        $this->template->load('admin', 'change_password', $data, __CLASS__);

    

    }

    

    /**

      * Edit User Function

      *

      * @access public

      * @param  nil

      * @return void

      * @author dohuuthien 2013-12-02

      */

    public function list_user_admin(){

        

        $data['admin_id']       = $this->session->userdata('admin_id');

        $data['admin_status']   = $this->session->userdata('admin_status');

        $data['role']           = $this->session->userdata('role');

        

        $this->load->library('pagination');

        $config['base_url'] = base_url('admin/list_user_admin');

        $config['total_rows'] = count($this->account_model->getUserAdmins($data['admin_id'], $data['role'], '', ''));

        $config['per_page'] = 30; // xác định số record ở mỗi trang 

        $config['uri_segment'] = 3; // xác định segment chứa page number 

        $this->pagination->initialize($config);

        $data['list_user_admins'] = $this->account_model->getUserAdmins($data['admin_id'], $data['role'], $config['per_page'], $this->uri->segment(3));

        $phantrang = $this->pagination->create_links();

        $data['phantrang'] = $phantrang;

        $this->template->load('admin', 'list_user_admin', $data, __CLASS__);

        

    }

    

     /**

      * Add User Admin Function

      *

      * @access public

      * @param  nil

      * @return void

      * @author dohuuthien 2013-12-02

      */

    public function add_user_admin(){

    

        $data = array();

        

        $data['admin_id']       = $this->session->userdata('admin_id');

        $data['admin_status']   = $this->session->userdata('admin_status');

        $data['role']           = $this->session->userdata('role');

        

        if($data['role'] == 3){

            if($data['admin_id'] != $user_admin_id){

                //set message

                $success_msg = "Thanks. You don't have permission access this link. Thanks.";

                //notification message

                $this->session->set_flashdata('msg',$success_msg);

                $this->session->set_flashdata('title',"You don't have permission access this link | Freelance");

                $this->session->set_flashdata('description',"You don't have permission access this link description");

                $this->session->set_flashdata('keywords',"You don't have permission access this link keywords");

                $this->session->set_flashdata('return_page','list_user_admin');

                redirect(base_url().'admin/info_notice');

                return;

            }

        }

        

        //Load Validation library

        $this->load->library('form_validation');

            

        //Load Form Helper

        $this->load->helper('form');

        

        if($this->input->post('add_user_admin',TRUE)){

        

            //get data

            $data['admin_user_add']['admin_user_name']                      = $this->input->post('admin_user_name',TRUE);

            $data['admin_user_add']['admin_user_full_name']                 = $this->input->post('admin_user_full_name',TRUE);

            $data['admin_user_add']['admin_user_role_id']                   = $this->input->post('admin_user_role_id',TRUE);

            $data['admin_user_add']['admin_user_pass']                      = $this->input->post('admin_user_pass',TRUE);

            $data['admin_confirm_user_pass']                                = $this->input->post('admin_confirm_user_pass',TRUE);

            $data['admin_user_add']['admin_user_email']                     = $this->input->post('admin_user_email',TRUE);

            $data['admin_user_add']['admin_user_status']                    = 1;

            

            //check valid

            $this->form_validation->set_rules('admin_user_name','lang:User name','required|trim|min_length[6]|xss_clean|alpha_space|callback_checkExitsAddUserNameForAdminUser');

            $this->form_validation->set_rules('admin_user_full_name','lang:Full name','required|trim|xss_clean|alpha_space');

            //$this->form_validation->set_rules('admin_user_pass','lang:password','required|trim|min_length[6]|xss_clean|alpha_space');

            //$this->form_validation->set_rules('admin_confirm_user_pass','lang:confirm password','required|trim|min_length[6]|xss_clean|alpha_space');

            $this->form_validation->set_rules('admin_user_pass','lang:Password','required|trim|min_length[5]|max_length[16]|xss_clean|matches[admin_confirm_user_pass]');

            $this->form_validation->set_rules('admin_confirm_user_pass','Confirm password','required|trim|min_length[5]|max_length[16]|xss_clean');

            $this->form_validation->set_rules('admin_user_email','lang:Email','required|trim|valid_email|xss_clean|alpha_space');

            

            if($this->form_validation->run()){

                

                $data['admin_user_add']['admin_user_pass']                  = md5($data['admin_user_add']['admin_user_pass']);

                $this->account_model->insertUserAdmins($data["admin_user_add"]);

                

                //set message

                $success_msg = 'Thanks. You add user admin success';



                //notification message

                $this->session->set_flashdata('msg',$success_msg);

                $this->session->set_flashdata('title','Add user admin sucess | Freelance');

                $this->session->set_flashdata('description','Add user admin sucess description');

                $this->session->set_flashdata('keywords','Add user admin sucess keywords');

                $this->session->set_flashdata('return_page','list_user_admin');

                redirect(base_url().'admin/info_notice');

                return;

                

            }

            

        }

        

        $this->template->load('admin', 'add_user_admin', $data, __CLASS__);

        

    }

    

    /**

     * Edit User Admin Function

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author dohuuthien 2013-12-02

     */

    public function edit_user_admin(){

    

        $data = array();

        

        $user_admin_id = is_numeric($this->uri->segment(3))?$this->uri->segment(3):0;

        

        $data['admin_id']       = $this->session->userdata('admin_id');

        $data['admin_status']   = $this->session->userdata('admin_status');

        $data['role']   = $this->session->userdata('role');

        

        if($data['role'] == 3){

            if($data['admin_id'] != $user_admin_id){

                //set message

                $success_msg = "Thanks. You don't have permission access this link. Thanks.";

                //notification message

                $this->session->set_flashdata('msg',$success_msg);

                $this->session->set_flashdata('title',"You don't have permission access this link | Freelance");

                $this->session->set_flashdata('description',"You don't have permission access this link description");

                $this->session->set_flashdata('keywords',"You don't have permission access this link keywords");

                $this->session->set_flashdata('return_page','list_user_admin');

                redirect(base_url().'admin/info_notice');

                return;

            }

        }

        

        $conditionUserAdmins = array('fl_user_admin.admin_user_id'=>$user_admin_id, 'fl_user_admin.admin_user_status'=>1);

        $data['userAdmins'] = $this->account_model->getUserAdmin($conditionUserAdmins)->row();

        

        //Load Validation library

        $this->load->library('form_validation');

        

        if($this->input->post('edit_user_admin',TRUE)){

            

            //get data

            $data['admin_user_edit']['admin_user_name']                     = $this->input->post('admin_user_name',TRUE);

            $data['admin_user_edit']['admin_user_full_name']                = $this->input->post('admin_user_full_name',TRUE);

            $data['admin_user_edit']['admin_user_role_id']                  = $this->input->post('admin_user_role_id',TRUE);

            $data['admin_user_pass']                                        = $this->input->post('admin_user_pass',TRUE);

            $data['admin_confirm_user_pass']                                = $this->input->post('admin_confirm_user_pass',TRUE);

            $data['admin_user_edit']['admin_user_email']                    = $this->input->post('admin_user_email',TRUE);

            $data['admin_user_edit']['admin_user_status']                   = 1;

                

            //check valid

            $this->form_validation->set_rules('admin_user_name','lang:user name','required|trim|min_length[6]|xss_clean|alpha_space|callback_checkExitsEditUserNameForAdminUser');

            $this->form_validation->set_rules('admin_user_full_name','lang:full name','required|trim|xss_clean|alpha_space');

            //$this->form_validation->set_rules('admin_user_pass','lang:password','required|trim|min_length[6]|xss_clean|alpha_space');

            //$this->form_validation->set_rules('admin_confirm_user_pass','lang:confirm password','required|trim|min_length[6]|xss_clean|alpha_space');

            if($data['admin_user_pass'] != "" || $data['admin_confirm_user_pass'] != ""){

                $data['admin_user_edit']['admin_user_pass'] = md5($data['admin_user_pass']);

                $this->form_validation->set_rules('admin_user_pass','lang:password','required|trim|min_length[5]|max_length[16]|xss_clean|matches[admin_confirm_user_pass]');

                $this->form_validation->set_rules('admin_confirm_user_pass','confirm password','required|trim|min_length[5]|max_length[16]|xss_clean');

            }

            

            $this->form_validation->set_rules('admin_user_email','lang:email','required|trim|valid_email|xss_clean|alpha_space');

                

            if($this->form_validation->run()){

                

                $updateKeyUserAdmin         = array('fl_user_admin.admin_user_id'=>$user_admin_id);

                $this->account_model->updateUserAdmin($updateKeyUserAdmin, $data['admin_user_edit']);

                

                //set message

                $success_msg = 'Thanks. You edit user admin success';

                //notification message

                $this->session->set_flashdata('msg',$success_msg);

                $this->session->set_flashdata('title','Edit user admin sucess | Freelance');

                $this->session->set_flashdata('description','Edit user admin sucess description');

                $this->session->set_flashdata('keywords','Edit user admin sucess keywords');

                $this->session->set_flashdata('return_page','list_user_admin');

                redirect(base_url().'admin/info_notice');

                return;

                

            }

            

        }

            

        //Load Form Helper

        $this->load->helper('form');

        $this->template->load('admin', 'edit_user_admin', $data, __CLASS__);

        

    }

    

    /**

     * Delete User Admin Function

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author dohuuthien 2013-12-02

     */

    public function delete_user_admin(){

    

        $data = array();

    

        $user_admin_id = is_numeric($this->uri->segment(3))?$this->uri->segment(3):0;

        

        $data['role']   = $this->session->userdata('role');

        

        if($data['role'] != ""){

            if($data['role'] != 2 && $data['role'] != 3){

                if($data['admin_id'] != $user_admin_id){

                    //set message

                    $success_msg = "Thanks. You don't have permission access this link. Thanks.";

                    //notification message

                    $this->session->set_flashdata('msg',$success_msg);

                    $this->session->set_flashdata('title',"You don't have permission access this link | Freelance");

                    $this->session->set_flashdata('description',"You don't have permission access this link description");

                    $this->session->set_flashdata('keywords',"You don't have permission access this link keywords");

                    $this->session->set_flashdata('return_page','list_user_admin');

                    redirect(base_url().'admin/info_notice');

                    return;

                }

            }

        }

        

        //delete user admin

        $conditionDeleteUserAdmin = array('fl_user_admin.admin_user_id'=>$user_admin_id);

        $this->account_model->deleteUserAdmin($conditionDeleteUserAdmin);

        

        //set message

        $success_msg = 'Thanks. You delete list user admin success';

        //notification message

        $this->session->set_flashdata('msg',$success_msg);

        $this->session->set_flashdata('title','Delete user admin sucess | Freelance');

        $this->session->set_flashdata('description','Delete user admin sucess description');

        $this->session->set_flashdata('keywords','Delete user admin sucess keywords');

        $this->session->set_flashdata('return_page','list_user_admin');

        redirect(base_url().'admin/info_notice');

        return;

        

    }



    public function logout() {

        $this->auth_model->clearAdminSession();

        

        redirect("admin/login");

        

    }

    

    /**

     * c=Check Exits Password Function

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author  dohuuthien 2015-09-02

     */

    function checkExitsPassword(){

        

        $password_old = $this->input->post('password_old',TRUE);

        

        $conditionAdmin = array('fl_admin.admin_id' => 1, 'fl_admin.admin_pass' => md5($password_old));

        $data['infoAdmins'] = $this->admin_model->getAdmins($conditionAdmin)->row();

        

        if(empty($data['infoAdmins'])){

            $this->form_validation->set_message('checkExitsPassword','<font color="#FF0000">Password old not true</font>');

            return FALSE;

        }

        else{

            return TRUE;

        }

        

    }

    

    /**

     * Check Exits Add User Name For Admin User Function

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author  dohuuthien 2013-12-02

     */

    function checkExitsAddUserNameForAdminUser(){

        $admin_user_name = $this->input->post('admin_user_name',TRUE);

        if($this->account_model->checkExitsUserNameForAdminUser($admin_user_name)==FALSE){

            $this->form_validation->set_message('checkExitsUserNameForAdminUser','<font color="#FF0000"><b>'.$admin_user_name.'</b></font><font color="#FF0000">&nbsp;already have</font>');

            return FALSE;

        }

        else{

            return TRUE;

        }

    }//End Of Check Exits Add User Name Function

    

    /**

     * Check Exits Edit User Name For Admin User Function

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author  dohuuthien 2013-12-02

     */

    function checkExitsEditUserNameForAdminUser(){

        $admin_user_name    = $this->input->post('admin_user_name',TRUE);

        $user_admin_id      = is_numeric($this->uri->segment(3))?$this->uri->segment(3):0;

        $conditionUserAdmins = array('fl_user_admin.admin_user_id'=>$user_admin_id, 'fl_user_admin.admin_user_status'=>1);

        $data['userAdmins'] = $this->account_model->getUserAdmin($conditionUserAdmins)->row();

        

        if($data['userAdmins']->admin_user_name == $admin_user_name){

            return TRUE;

        }

        if($this->account_model->checkExitsUserNameForAdminUser($admin_user_name)==FALSE){

            $this->form_validation->set_message('checkExitsUserNameForAdminUser','<font color="#FF0000"><b>'.$admin_user_name.'</b></font><font color="#FF0000">&nbsp;already have</font>');

            return FALSE;

        }

        else{

            return TRUE;

        }

    }//End Of Check Exits Edit User Name Function

    

    /**

     * Check Format Email Function

     *

     * @access  public

     * @param   nil

     * @return  void

     * @author  dohuuthien 2013-12-02

     */

    function checkFormatEmail(){

        $admin_user_email = $this->input->post('admin_user_email',TRUE);

        $syntax = "/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/";

        if(preg_match($syntax, $admin_user_email)){

            return true;

        }

        else{

            $this->form_validation->set_message('checkFormatEmail','<font color="#FF0000"><b>'.$admin_user_email.'</b></font><font color="#FF0000">&nbsp; not true format</font>');

            return false;

        }

    }//End Of Check Format Email Function
    
    function set_user_status(){
    	
    	$status = $_POST["status"];
    	$user_id = $_POST["user_id"];
		
    	if($user_id > 0){
    		if($status == 1){
				$this->db->where('user_id', $user_id);
				$this->db->update('luxyart_tb_user', array('user_status' => $status,'is_active' => 1));
    		}
    		else{
    			$this->db->where('user_id', $user_id);
    			$this->db->update('luxyart_tb_user', array('user_status' => $status));
    		}
			echo 1;
    	}
		else{
			echo 0;	
		}
    	
    }
    
}
