<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Json extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->library('cart');

        $this->load->model('main_model');

        $this->load->model('function_model');
        $this->load->model('json_model');
        $this->load->model('shopping_model');
        $this->load->model('manager_user_model');
        $this->load->model('manager_image_model');
        $this->load->model('manager_comment_model');

        $dir = getcwd();
        define("DIR", $dir . "/publics/");
        define("DIR_TEMP", $dir . "/publics/product_img/temp/");
        define("DIR_DOWNLOAD", $dir . "/publics/product_img/download/");

        define("SRC", "https://luxy.art");

        $ngonngu = $config['language'];
        $this->lang->load('dich', "ja");

    }

    public function index()
    {

        header("Content-Type: application/json");

        $handle = fopen('php://input', 'r');

        $jsonInput = fgets($handle);

        $decoded = json_decode($jsonInput, true);

        $key = $decoded["method"];

        $item = $decoded["params"];

        switch ($key) {

            case "list_color": //1

                $this->list_color($item);

                break;

            case "list_product_tags": //1

                $this->list_product_tags($item);

                break;
                
          	case "list_ng_word": //1.1
                
            	$this->list_ng_word($item);
                
                break;
                
           	case "list_static_page": //1.1
                
            	$this->list_static_page($item);
                
                break;

            case "list_manager_server": //2

                $this->list_manager_server($item);

                break;

            case "list_category": //3

                $this->list_category($item);

                break;

            case "list_product_category": //4

                $this->list_product_category($item);

                break;

            case "login": //5

                $this->json_model->login($item);

                break;

            case "login_facebook": //6

                $this->json_model->login_facebook($item);

                break;

            case "register_email": //7

                $this->json_model->register_email($item);

                break;

            case "forget_password": //8

                $this->json_model->forget_password($item);

                break;

            case "get_detail_user":

                $this->json_model->get_detail_user($item);

                break;

            case "update_avatar_banner_user":

                $this->json_model->update_avatar_banner_user($item);

                break;

            case "update_user":

                $this->json_model->update_user($item);

                break;

            case "change_password":

                $this->json_model->change_password($item);

                break;

            case "image_auth_account":

                $this->json_model->image_auth_account($item);

                break;

            case "list_user":

                $this->json_model->list_user($item);

                break;

            case "change_password":

                $this->json_model->change_password($item);

                break;

            case "list_image_follow_category": //13

                $this->list_image_follow_category($item);

                break;

            case "get_detail_image": //14

                $this->get_detail_image($item);

                break;

            case "list_image": //18

                $this->list_image($item);

                break;

            case "list_management_image": //18

                $this->list_management_image($item);

                break;

            case "search_image": //18

                $this->search_image($item);

                break;

            case "search_image_test": //18

                $this->search_image_test($item);

                break;

            case "active_image": //18

                $this->active_image($item);

                break;

            case "post_image": //18

                $this->post_image($item);

                break;

            case "post_image_test": //18

                $this->post_image_test($item);

                break;

            case "update_image": //18

                $this->update_image($item);

                break;

            case "list_note": //???

                $this->json_model->list_note($item);

                break;

            case "list_bookmark_image": //???

                $this->json_model->list_bookmark_image($item);

                break;

            case "list_bookmark_user": //???

                $this->json_model->list_bookmark_user($item);

                break;

            case "read_all_note": //???

                $this->json_model->read_all_note($item);

                break;

            case "add_update_competition": //???

                $this->json_model->add_update_competition($item);

                break;

            case "choose_image_competition": //???

                $this->json_model->choose_image_competition($item);

                break;

            case "list_competition": //???

                $this->json_model->list_competition($item);

                break;

            case "apply_competition": //???

                $this->json_model->apply_competition($item);

                break;

            case "get_competition": //???

                $this->json_model->get_competition($item);

                break;

            case "list_image_to_competition": //???

                $this->json_model->list_image_to_competition($item);

                break;

            case "list_image_win_competition": //???

                $this->json_model->list_image_win_competition($item);

            case "bookmark_image": //???

                $this->json_model->bookmark_image($item);

            case "bookmark_user": //???

                $this->json_model->bookmark_user($item);

            case "list_competition_user": //???

                $this->json_model->list_competition_user($item);

            case "save_to_cart": //???

                $this->save_to_cart($item);

                break;

            /***Tan Code****/
            case "get_point_package_list":

                $this->get_point_package_list($item);

                break;

            case "buy_point":

                $this->buy_point($item);

                break;
                
          	case "buy_point_test":
                
            	$this->buy_point_test($item);
                
                break;
                
            case "test_push_notification":

                $this->testPushNotification($item['user_id'], $item['content']);

                break;
            case "get_list_image_buy":

                $this->get_list_image_buy($item);

                break;
            case "get_list_history_payment":

                $this->get_list_history_payment($item);

                break;
            case "reset_password":
                $this->reset_password($item);
                break;
            case "add_image_to_user":
                $this->add_image_to_user($item);
                break;
            case "upgrade_level_account":
                $this->upgrade_level_account($item);
                break;
            case "get_list_visa_card":
                $this->get_list_visa_card($item);
                break;
            case "add_edit_visa_card":
                $this->add_edit_visa_card($item);
                break;
            case "get_list_package_upgrade_account":
                $this->get_list_package_upgrade_account($item);
                break;
            case "get_history_bank_tranfer":
                $this->get_history_bank_tranfer($item);
                break;
            case "require_admin_to_tranfer":
                $this->require_admin_to_tranfer($item);
                break;
            case "report_image":
                $this->report_image($item);
                break;
            case "edit_bank_infomation":
                $this->edit_bank_infomation($item);
                break;
            case "get_list_setting":
                $this->get_list_setting($item);
                break;
            case "get_list_competition_apply":
                $this->get_list_competition_apply($item);
                break;
            case "get_list_competition_time_agree":
                $this->get_list_competition_time_agree($item);
                break;
            case "count_new_notice":
                $this->count_new_notice($item);
                break;
            case "get_country":
                $this->get_country($item);
                break;
            case "get_country_state":
                $this->get_country_state($item);
                break;
            case "compareColor":
                $this->compareColor($item);
                break;
            case "get_list_history_buy_point":
                $this->get_list_history_buy_point($item);
                break;
            case "require_admin_check_status_buy_point":
                $this->require_admin_check_status_buy_point($item);
                break;
                
          	case "list_manager_comment":
            	$this->list_manager_comment($item);
                break;
            case "list_manager_comment_by_image":
            	$this->list_manager_comment_by_image($item);
                break;
           	case "list_comment_by_image":
            	$this->list_comment_by_image($item);
                break;
            case "post_comment":
            	$this->post_comment($item);
                break;
           	case "set_status_comment":
            	$this->set_status_comment($item);
                break;
           	case "listinvited":
            	$this->listinvited($item);
                break;
           	case "listmember":
            	$this->listmember($item);
            case "check_email_add_member":
            	$this->check_email_add_member($item);
                break;
           	case "add_member":
            	$this->add_member($item);
                break;
           	case "delete_member":
            	$this->delete_member($item);
                break;
           	case "is_agree_member":
            	$this->is_agree_member($item);
                break;
                
                
                
                
        }
    }

    public function list_category($item)
    {

        $dt = array();

        $message = "";

        $product_category_id = $item['product_category_id'];
        $level_cate = $item['level_cate'];
        $parent_cate = $item['parent_cate'];

        $lang = $item['lang'];

        if ($lang == "") {
            $lang == 1;
        }

        $this->db->select('luxyart_tb_product_category.*');
        $this->db->from('luxyart_tb_product_category');
        if ($product_category_id != "") {
            if ($parent_cate != "") {
                $this->db->where(array('status_cate' => 1, 'product_category_id' => $product_category_id, 'level_cate' => $level_cate, 'parent_cate' => $parent_cate, 'lang_id' => $lang));
            } else {
                $this->db->where(array('status_cate' => 1, 'product_category_id' => $product_category_id, 'level_cate' => $level_cate, 'lang_id' => $lang));
            }
        } else {
            if ($parent_cate != "") {
                $this->db->where(array('level_cate' => $level_cate, 'parent_cate' => $parent_cate, 'lang_id' => $lang, 'status_cate' => 1));
            } else {
                $this->db->where(array('level_cate' => $level_cate, 'lang_id' => $lang, 'status_cate' => 1));
            }
        }
        $rs = $this->db->get();
        $result = $rs->result();
        // echo $this->db->last_query();

        $i = 0;

        foreach ($result as $_result) {

            $dt[$i]['product_category_id'] = $_result->product_category_id;
            $dt[$i]['cate_name'] = $_result->cate_name;
            $dt[$i]['note_description'] = $_result->note_description;
            $dt[$i]['img_category'] = SRC . "/publics/cate_img/" . $_result->img_category;
            $dt[$i]['level_cate'] = $_result->level_cate;
            $dt[$i]['root_cate'] = $_result->root_cate;
            $dt[$i]['parent_cate'] = $_result->parent_cate;
            $dt[$i]['status_cate'] = $_result->status_cate;

            $i++;

        }

        if (!empty($dt)) {
            $isSuccess = 1;

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        } else {
            $isSuccess = 0;

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

        exit;

    }

    public function list_color($item)
    {

        $dt = array();

        $message = "";

        $id_color = $item['id_color'];
        $lang = $item['lang'];

        $this->db->select('luxyart_tb_product_color.*');

        $this->db->from('luxyart_tb_product_color');
        if ($id_color != "") {

            $this->db->where(array('id_color' => $id_color, 'lang_id' => $lang));
        } else {
            $this->db->where(array('lang_id' => $lang));
        }

        $rs = $this->db->get();

        $result = $rs->result();

        $i = 0;

        foreach ($result as $_result) {

            $dt[$i]['id_color'] = $_result->product_color_id;

            $dt[$i]['name_color'] = $_result->name_color;

            $dt[$i]['css_color_style'] = $_result->css_color_style;

            $i++;

        }

        if (!empty($dt)) {

            $isSuccess = 1;

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        } else {

            $isSuccess = 0;

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

        exit;

    }

    public function list_product_tags($item)
    {

        $dt = array();
        $img_root = array();
        $img_parent = array();

        $message = "";

        $id_product_tag = $item['id_product_tag'];
        $img_root = $item['img_root'];
        $img_parent = $item['img_parent'];
        $lang = $item['lang'];

        if ($img_root != "") {
            $img_root = explode(",", $img_root);
            $img_root = array_unique($img_root);
            $count_img_root = count($img_root);
        } else {
            $count_img_root = 0;
        }

        if ($img_parent != "") {
            $img_parent = explode(",", $img_parent);
            $img_parent = array_unique($img_parent);
            $count_img_parent = count($img_parent);
        } else {
            $count_img_parent = 0;
        }

        if ($count_img_root == 0 && $count_img_parent == 0) {
            $this->db->from('luxyart_tb_product_tags');
            $this->db->select('luxyart_tb_product_tags.*');
            //$this->db->where_not_in('tag_name', $img_tags_explode);
            $this->db->order_by('luxyart_tb_product_tags.tag_name', 'asc');
        } else {
            if ($count_img_parent == 0) {
                $this->db->from('luxyart_tb_product_tags');
                $this->db->select('luxyart_tb_product_tags.*');
                $this->db->where_in('root_cate', $img_root);
                //$this->db->where_not_in('tag_name', $img_tags_explode);
                $this->db->order_by('luxyart_tb_product_tags.tag_name', 'asc');
            } else {
                $this->db->from('luxyart_tb_product_tags');
                $this->db->select('luxyart_tb_product_tags.*');
                $this->db->where_in('parent_cate', $img_parent);
                //$this->db->where_not_in('tag_name', $img_tags_explode);
                $this->db->order_by('luxyart_tb_product_tags.tag_name', 'asc');
            }
        }

        if ($id_product_tag != "") {
            $this->db->where(array('id_product_tag' => $id_product_tag, 'status' => 1));
        } else {
            $this->db->where(array('status' => 1));
        }

        $rs = $this->db->get();
        $result = $rs->result();

        $i = 0;
        foreach ($result as $_result) {

            $dt[$i]['id_product_tag'] = $_result->id_product_tag;
            $dt[$i]['tag_name'] = $_result->tag_name;
            $dt[$i]['counter'] = $_result->counter;
            $i++;

        }

        if (!empty($dt)) {
            $isSuccess = 1;
            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);
        } else {
            $isSuccess = 0;
            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);
        }

        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);
        echo $json;

        exit;

    }
    
    public function list_ng_word($item){
    
    	$dt = array();
    	$message = "";
    
    	$word_id = $item['word_id'];
    
    	$this->db->select('luxyart_tb_ngwordlist.*');
    	$this->db->from('luxyart_tb_ngwordlist');
    	if ($word_id != "") {
    		$this->db->where(array('word_id' => $word_id, 'status' => 1));
    	}
    	else{
    		$this->db->where(array('status' => 1));
    	}
    
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$i = 0;
    
    	foreach ($result as $_result) {
    		$dt[$i]['word_id'] = $_result->word_id;
    		$dt[$i]['word'] = $_result->word;
    		$i++;
    	}
    
    	if(!empty($dt)){
    		$isSuccess = 1;
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);
    	}
    	else{
    		$isSuccess = 0;
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);
    	}
    
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    	echo $json;
    	exit;
    
    }
    
    public function list_static_page($item){
    
    	$dt = array();
    	$message = "";
    
    	$id_static_page = $item['id_static_page'];
    
    	$this->db->select('luxyart_static_page.*');
    	$this->db->from('luxyart_static_page');
    	if ($id_static_page != "") {
    		$this->db->where(array('id_static_page' => $id_static_page,'lang_id' => 1));
    	}
    	else{
    		$this->db->where(array('lang_id' => 1));
    	}
    
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$i = 0;
    
    	foreach ($result as $_result) {
    		$dt[$i]['id_static_page'] = $_result->id_static_page;
    		$dt[$i]['code_call'] = $_result->code_call;
    		$dt[$i]['content_page_show'] = $_result->content_page_show;
    		$i++;
    	}
    
    	if(!empty($dt)){
    		$isSuccess = 1;
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);
    	}
    	else{
    		$isSuccess = 0;
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);
    	}
    
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    	echo $json;
    	exit;
    
    }

    public function list_manager_server($item)
    {

        $dt = array();

        $message = "";

        $id_manager_server = $item['id_manager_server'];

        $this->db->select('luxyart_tb_manager_server.*');

        $this->db->from('luxyart_tb_manager_server');

        if ($id_manager_server != "") {

            $this->db->where(array('id_server' => $id_manager_server));

        }

        $rs = $this->db->get();

        $result = $rs->result();

        $i = 0;

        foreach ($result as $_result) {

            $dt[$i]['id_server'] = $_result->id_server;

            $dt[$i]['server_name'] = $_result->server_name;

            $dt[$i]['server_path_upload'] = $_result->server_path_upload;
            $dt[$i]['server_ftp_account'] = $_result->server_ftp_account;

            $dt[$i]['server_ftp_pass'] = $_result->server_ftp_pass;

            $dt[$i]['server_port'] = $_result->server_port;
            $dt[$i]['img_store'] = $_result->img_store;

            $dt[$i]['img_count'] = $_result->img_count;

            $dt[$i]['server_status'] = $_result->server_status;

            $i++;

        }

        if (!empty($dt)) {

            $isSuccess = 1;

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        } else {

            $isSuccess = 0;

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

        exit;

    }

    public function list_product_category($item)
    {

        $dt = array();

        $message = "";

        $product_category_id = $item['product_category_id'];

        $level_cate = $item['level_cate'];

        $parent_cate = $item['parent_cate'];

        $lang = $item['lang'];

        if ($lang == "") {

            $lang == 1;

        }

        $this->db->select('luxyart_tb_product_category.*');

        $this->db->from('luxyart_tb_product_category');

        if ($level_cate == 0) {

            if ($product_category_id != "") {
                $this->db->where(array('product_category_id' => $product_category_id, 'lang_id' => $lang, 'status_cate' => 1));
            } else {
                $this->db->where(array('lang_id' => $lang, 'status_cate' => 1));
            }

        } else {

            if ($product_category_id != "") {

                if ($parent_cate != "") {

                    $this->db->where(array('product_category_id' => $product_category_id, 'level_cate' => $level_cate, 'parent_cate' => $parent_cate, 'lang_id' => $lang));

                } else {

                    $this->db->where(array('product_category_id' => $product_category_id, 'level_cate' => $level_cate, 'lang_id' => $lang));

                }

            } else {

                if ($parent_cate != "") {

                    $this->db->where(array('level_cate' => $level_cate, 'parent_cate' => $parent_cate, 'lang_id' => $lang, 'status_cate' => 1));

                } else {

                    $this->db->where(array('level_cate' => $level_cate, 'lang_id' => $lang, 'status_cate' => 1));

                }

            }

        }

        $rs = $this->db->get();

        $result = $rs->result();
        // echo $this->db->last_query();

        $i = 0;

        foreach ($result as $_result) {

            $dt[$i]['product_category_id'] = $_result->product_category_id;

            $dt[$i]['cate_name'] = $_result->cate_name;

            $dt[$i]['note_description'] = $_result->note_description;

            $dt[$i]['img_category'] = SRC . "/publics/cate_img/" . $_result->img_category;

            $dt[$i]['level_cate'] = $_result->level_cate;

            $dt[$i]['root_cate'] = $_result->root_cate;

            $dt[$i]['parent_cate'] = $_result->parent_cate;

            $dt[$i]['status_cate'] = $_result->status_cate;

            $i++;

        }

        if (!empty($dt)) {

            $isSuccess = 1;

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        } else {

            $isSuccess = 0;

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

        exit;

    }

    public function get_detail_image($item)
    {

        $dt = array();
        $message = "";

        $lang = $item['lang'];
        $user_id = $item['user_id'];
        $image_id = $item['image_id'];

        if ($lang == "" || $lang == 0) {
            $lang = 1;
        }

        $user_level = $this->function_model->getUserLevel($user_id);

        if ($user_level == 0) {

            $isSuccess = 0;

        } else if ($user_level >= 1) {

            $this->db->select('luxyart_tb_product_img.*');
            $this->db->from('luxyart_tb_product_img');
            $this->db->where(array('id_img' => $image_id));

            $rs = $this->db->get();

            $row = $rs->row();

            if (!is_array($row)) {

                $id_img = $row->id_img;
                //$user_id = $row->user_id;
                $id_server = $row->id_server;
                $id_color = $row->id_color;
                $option_sale = $row->option_sale;
                $date_active = $row->date_active;

                $user = $this->function_model->getUser($row->user_id);

                $manager_server = $this->function_model->getManagerServer($id_server);
                $product_color = $this->function_model->getProductColor($id_color);
                $product_img_size = $this->function_model->getProductImgSizeShoppingCart($id_img);

                // if($option_sale == 1){//1d
                //     $date_limit = date('Y-m-d', strtotime('+1 day', strtotime($date_active)));
                // }
                // else if($option_sale == 2){//1w
                //
                //     $date_limit = date('Y-m-d', strtotime('+1 week', strtotime($date_active)));
                //
                // }
                // else if($option_sale == 3){//1m
                //
                //     $date_limit = date('Y-m-d', strtotime('+1 month', strtotime($date_active)));
                //
                // }
                // else{
                //     $date_limit = date('Y-m-d');
                // }

                $date_limit = $this->getExpiredImageDate($date_active, $option_sale);

                $value_date_limit = date_create($date_limit);

                $now = date_create(date('Y-m-d h:i:s'));

                $diff = date_diff($value_date_limit, $now);

                // $dt['diff']                             = $diff;
                $dt['id_img'] = $id_img;
                $dt['user_id'] = $row->user_id;
                $dt['user_firstname'] = $user->user_firstname;
                $dt['user_lastname'] = $user->user_lastname;
                $dt['display_name'] = $user->display_name;
                $dt['user_email'] = $user->user_email;
                $dt['user_avatar'] = SRC . "/publics/avatar/" . $user->user_avatar;
                $dt['user_introduction'] = $user->user_introduction;
                $dt['count_follow'] = $this->json_model->countFollow($row->user_id);
                $dt['img_title'] = $row->img_title;

                $dt['file_name_original_img'] = $manager_server->server_path_upload . "/" . $row->file_name_original_img;
                $dt['file_name_watermark_img'] = $manager_server->server_path_upload . "/" . $row->file_name_watermark_img;
                // $dt['hexMainColor']             = $this->function_model->main_color_image($dt['file_name_original_img']);
                // $dt['mainColorNo#'] = $this->function_model->get_compare_color($dt['hexMainColor']);
                // $dt['mainColorHave#'] = $this->function_model->get_compare_color('#'.$dt['hexMainColor']);
                $dt['img_file_md5'] = $row->img_file_md5;
                $dt['img_width'] = $row->img_width;

                $dt['img_height'] = $row->img_height;
                $dt['img_dpi'] = $row->img_dpi;
                $dt['img_type'] = $row->img_type;

                $dt['kind_img'] = $row->user_id;

                $dt['id_color'] = $row->id_color;
                $dt['id_colors'] = $row->id_colors;

                if (!empty($product_color)) {
                    $dt['css_color_style'] = $product_color->css_color_style;
                } else {
                    $dt['css_color_style'] = "";
                }

                $dt['id_server'] = $row->id_server;

                $dt['option_img_watermark'] = $row->option_img_watermark;

                $dt['option_sale'] = $row->option_sale;

                $dt['hour'] = $diff->format("%R%a");
                $dt['minute'] = $diff->format("%h");
                $dt['date_expired'] = $date_limit;
                $dt['date_active'] = $date_active;

                $dt['is_limit'] = $row->is_limit;

                $dt['img_tags'] = $row->img_tags;

                $dt['date_add_img'] = $row->date_add_img;
                $dt['img_is_sold'] = $row->img_is_sold;

                $dt['img_is_join_compe'] = $row->img_is_join_compe;

                $dt['img_check_status'] = $row->img_check_status;

                $dt['date_update_img'] = $row->date_update_img;
                $dt['img_link'] = base_url() . "detail/" . $row->img_code . ".html";

                $j = 0;

                $arr_product_img_size = array();

                foreach ($product_img_size as $_product_img_size) {

                    $arr_product_img_size[$j]['id_img_size'] = $_product_img_size->id_img_size;
                    $arr_product_img_size[$j]['type_size'] = $_product_img_size->type_size;
                    $arr_product_img_size[$j]['type_size_description'] = $_product_img_size->type_size_description;
                    $arr_product_img_size[$j]['price_size_point'] = $_product_img_size->price_size_point;
                    $arr_product_img_size[$j]['size_stock'] = $_product_img_size->size_stock;
                    $arr_product_img_size[$j]['date_add_img_size'] = $_product_img_size->date_add_img_size;
                    $arr_product_img_size[$j]['status_img_size'] = $_product_img_size->status_img_size;
                    $arr_product_img_size[$j]['is_buy'] = $_product_img_size->is_buy;

                    $j++;

                }

                $dt['product_img_size'] = $arr_product_img_size;

                $dt['img_category'] = $this->getImgCategory($id_img, $lang);

                //is_bookmark
                $dt['is_bookmark'] = $this->isBookmark($id_img, $user_id);

                //is_follow
                $dt['is_follow'] = $this->isFollow($row->user_id, $user_id);

                //is_get_to_sell
                $dt['is_get_to_sell'] = $this->isGetToSell($id_img, $user_id);

                //get link invite
                $code = $this->function_model->encrypt_id($id_img);
                $dt["link_invite"] = base_url() . "invite/" . $code . "-" . $user->user_id_code_invite;

                //get user view product img
                if ($user_id != "") {

                    $whereUserViewProductImg = array('user_id' => $user_id, 'id_img' => $id_img);
                    $orderUserViewProductImg = array();
                    $data['detail_user_view_product_img'] = $this->main_model->getAllData("luxyart_tb_user_view_product_img", $whereUserViewProductImg, $orderUserViewProductImg)->row();

                    if (count($data['detail_user_view_product_img']) == 0) {

                        //add user view product img
                        $dataUserViewProductImg = array(
                            'user_id' => $user_id,
                            'id_img' => $id_img,
                            'date_view' => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('luxyart_tb_user_view_product_img', $dataUserViewProductImg);

                    }

                }

                $isSuccess = 1;

            } else {

                $isSuccess = 0;

            }

        }

        if (!empty($dt)) {

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        } else {

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $dt);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

    }

    public function list_image($item)
    {

        $dt = array();

        $message = "";

        $lang = $item['lang'];
        $creator_user_id = $item['creator_user_id'];
        $user_id = $item['user_id'];

        $current_page = $item['current_page'];
        $amount_page = $item['amount_page'];

        if ($current_page == "") {

            $current_page = 1;

        }

        if ($amount_page == "") {

            $amount_page = 1000000;

        }

        $count_product_img = 0;

        $user_level = $this->function_model->getUserLevel($user_id);

        if ($user_level == 0) {

            $isSuccess = 1;

        } else if ($user_level == 1) {

            $isSuccess = 1;

        } else if ($user_level >= 2) {
            // $this->db->select('luxyart_tb_product_img.*');
            // $this->db->from('luxyart_tb_product_img');
            // $this->db->where(array('user_id' => $creator_user_id, 'img_check_status' => 1));
            $this->db->select('luxyart_tb_product_img.*');
            $this->db->distinct('luxyart_tb_product_img.id_img');
            $this->db->from('luxyart_tb_product_img, luxyart_tb_user_get_img_sell');
            $this->db->where('(luxyart_tb_product_img.user_id = ' . $creator_user_id . ' AND luxyart_tb_product_img.img_check_status = 1 AND luxyart_tb_product_img.img_is_join_compe = 0) OR (luxyart_tb_product_img.id_img = luxyart_tb_user_get_img_sell.id_img AND luxyart_tb_user_get_img_sell.user_id = ' . $creator_user_id . ' AND luxyart_tb_user_get_img_sell.user_owner = luxyart_tb_product_img.user_id AND luxyart_tb_product_img.img_check_status = 1 AND luxyart_tb_product_img.img_is_join_compe = 0)');

            $rsCount = $this->db->get();

            $resultCount = $rsCount->result();
            $count_product_img = count($resultCount);

            // $this->db->from('luxyart_tb_product_img');
            // $this->db->where(array('luxyart_tb_product_img.user_id' => $creator_user_id, 'luxyart_tb_product_img.img_check_status' => 1));
            // $this->db->select('luxyart_tb_product_img.*');
            // $this->db->limit($amount_page, $amount_page*($current_page-1));
            // $this->db->order_by('date_add_img', 'desc');

            $this->db->select('luxyart_tb_product_img.*');
            $this->db->distinct('luxyart_tb_product_img.id_img');
            $this->db->from('luxyart_tb_product_img, luxyart_tb_user_get_img_sell');
            $this->db->where('(luxyart_tb_product_img.user_id = ' . $creator_user_id . ' AND luxyart_tb_product_img.img_check_status = 1 AND luxyart_tb_product_img.img_is_join_compe = 0) OR (luxyart_tb_product_img.id_img = luxyart_tb_user_get_img_sell.id_img AND luxyart_tb_user_get_img_sell.user_id = ' . $creator_user_id . ' AND luxyart_tb_user_get_img_sell.user_owner = luxyart_tb_product_img.user_id AND luxyart_tb_product_img.img_check_status = 1 AND luxyart_tb_product_img.img_is_join_compe = 0)');
            $this->db->limit($amount_page, $amount_page * ($current_page - 1));
            $this->db->order_by('luxyart_tb_product_img.date_add_img', 'desc');

            $rs = $this->db->get();

            $result = $rs->result();

            $i = 0;

            foreach ($result as $_result) {

                $id_img = $_result->id_img;

                $id_server = $_result->id_server;

                $id_color = $_result->id_color;

                $manager_server = $this->function_model->getManagerServer($id_server);

                $product_color = $this->function_model->getProductColor($id_color);

                $product_img_size = $this->function_model->getProductImgSize($id_img);

                /*=== Check image expired ===*/
                $date_limit = $this->getExpiredImageDate($_result->date_active, $_result->option_sale);
                // echo '['.$date_limit.' - '.date('Y-m-d').']<br>';
                if (($creator_user_id == $user_id) || (($creator_user_id != $user_id) && ($date_limit >= date('Y-m-d')))) {

                    $dt[$i]['id_img'] = $id_img;

                    $dt[$i]['user_id'] = $_result->user_id;

                    $dt[$i]['img_title'] = $_result->img_title;

                    $dt[$i]['file_name_original_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_original_img;

                    $dt[$i]['file_name_watermark_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_watermark_img;

                    $dt[$i]['img_file_md5'] = $_result->img_file_md5;

                    $dt[$i]['img_width'] = $_result->img_width;

                    $dt[$i]['img_height'] = $_result->img_height;

                    $dt[$i]['img_type'] = $_result->img_type;

                    $dt[$i]['kind_img'] = $_result->user_id;

                    $dt[$i]['id_color'] = $_result->id_color;

                    if (!empty($product_color)) {
                        $dt[$i]['css_color_style'] = $product_color->css_color_style;
                    } else {
                        $dt[$i]['css_color_style'] = "";
                    }

                    $dt[$i]['id_server'] = $_result->id_server;

                    $dt[$i]['option_img_watermark'] = $_result->option_img_watermark;

                    $dt[$i]['option_sale'] = $_result->option_sale;

                    $dt[$i]['img_tags'] = $_result->img_tags;

                    $dt[$i]['date_add_img'] = $_result->date_add_img;

                    $dt[$i]['img_is_sold'] = $_result->img_is_sold;

                    $dt[$i]['img_is_join_compe'] = $_result->img_is_join_compe;

                    $dt[$i]['img_check_status'] = $_result->img_check_status;

                    $dt[$i]['date_update_img'] = $_result->date_update_img;

                    $dt[$i]['img_category'] = $this->getImgCategory($id_img, $lang);

                    //is_bookmark

                    $dt[$i]['is_bookmark'] = $this->isBookmark($id_img, $user_id);

                    //is_follow

                    $dt[$i]['is_follow'] = $this->isFollow($creator_user_id, $user_id);

                    $i++;
                }

                $isSuccess = 1;

            }

        }

        if ($count_product_img % $amount_page == 0) {

            $data = array("countData" => $count_product_img, "numPage" => $count_product_img / $amount_page, "dt" => $dt);

        } else {

            $data = array("countData" => $count_product_img, "numPage" => floor($count_product_img / $amount_page + 1), "dt" => $dt);

        }

        if (!empty($dt)) {

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);

        } else {

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

        exit;

    }

    public function list_management_image($item)
    {

        $dt = array();

        $message = "";

        $lang = $item['lang'];

        $user_id = $item['user_id'];

        $current_page = $item['current_page'];

        $amount_page = $item['amount_page'];

        if ($current_page == "") {

            $current_page = 1;

        }

        if ($amount_page == "") {

            $amount_page = 1000000;

        }

        $count_product_img = 0;

        $user_level = $this->function_model->getUserLevel($user_id);
        
        $isSuccess = 0;

        if ($user_level == 0) {

            $isSuccess = 0;

        } else if ($user_level == 1) {

            $isSuccess = 0;

        } else if ($user_level >= 2) {

            $this->db->select('luxyart_tb_product_img.*');

            $this->db->from('luxyart_tb_product_img');

            $this->db->where(array('user_id' => $user_id));

            $rsCount = $this->db->get();

            $resultCount = $rsCount->result();

            if (!empty($resultCount)) {

                $count_product_img = count($resultCount);

            } else {

                $count_product_img = 0;

            }

            $this->db->select('luxyart_tb_product_img.*');

            $this->db->from('luxyart_tb_product_img');

            $this->db->where(array('user_id' => $user_id));

            $this->db->order_by('date_add_img', 'desc');

            $this->db->limit($amount_page, $amount_page * ($current_page - 1));

            $rs = $this->db->get();

            $result = $rs->result();

            $i = 0;

            foreach ($result as $_result) {

                $id_img = $_result->id_img;

                $id_server = $_result->id_server;

                $id_color = $_result->id_color;

                $manager_server = $this->function_model->getManagerServer($id_server);

                $product_color = $this->function_model->getProductColor($id_color);

                $product_img_size = $this->function_model->getProductImgSize($id_img);

                $dt[$i]['id_img'] = $id_img;

                $dt[$i]['user_id'] = $_result->user_id;

                $dt[$i]['img_title'] = $_result->img_title;

                $dt[$i]['file_name_original_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_original_img;

                $dt[$i]['file_name_watermark_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_watermark_img;

                $dt[$i]['img_file_md5'] = $_result->img_file_md5;

                $dt[$i]['img_width'] = $_result->img_width;

                $dt[$i]['img_height'] = $_result->img_height;

                $dt[$i]['img_type'] = $_result->img_type;

                $dt[$i]['kind_img'] = $_result->user_id;

                $dt[$i]['id_color'] = $_result->id_color;

                if (!empty($product_color)) {
                    $dt[$i]['css_color_style'] = $product_color->css_color_style;
                } else {
                    $dt[$i]['css_color_style'] = "";
                }

                $dt[$i]['id_server'] = $_result->id_server;

                $dt[$i]['option_img_watermark'] = $_result->option_img_watermark;

                $dt[$i]['option_sale'] = $_result->option_sale;

                $dt[$i]['img_tags'] = $_result->img_tags;

                $dt[$i]['date_add_img'] = $_result->date_add_img;

                $dt[$i]['img_is_sold'] = $_result->img_is_sold;

                $dt[$i]['img_is_join_compe'] = $_result->img_is_join_compe;

                $dt[$i]['img_check_status'] = $_result->img_check_status;

                $dt[$i]['date_update_img'] = $_result->date_update_img;

                $dt[$i]['img_category'] = $this->getImgCategory($id_img, $lang);

                //is_bookmark

                $dt[$i]['is_bookmark'] = $this->isBookmark($id_img, $user_id);

                //is_follow

                $dt[$i]['is_follow'] = $this->isFollow($_result->user_id, $user_id);

                $i++;

                $isSuccess = 1;

            }

        }

        if ($count_product_img % $amount_page == 0) {

            $data = array("countData" => $count_product_img, "numPage" => $count_product_img / $amount_page, "dt" => $dt);

        } else {

            $data = array("countData" => $count_product_img, "numPage" => floor($count_product_img / $amount_page + 1), "dt" => $dt);

        }

        if (!empty($dt)) {

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);

        } else {

            $list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

        exit;

    }

    public function search_image($item)
    {

        $dt = array();

        $message = "";

        $key = trim($item['key']);
        $is_cate = $item['is_cate'];

        $root_cate = $item['root_cate'];
        $parent_cate = $item['parent_cate'];

        $sub_cate = $item['sub_cate'];
        $color = $item['color'];

        $kind_img = $item['kind_img'];
        $option_sale = $item['option_sale'];
        $is_limit = $item['is_limit'];

        $type_order = $item['type_order'];
        $lang = $item['lang'];

        $user_id = $item['user_id'];
        $current_page = $item['current_page'];

        $amount_page = $item['amount_page'];

        if ($current_page == "") {

            $current_page = 1;

        }

        if ($amount_page == "") {

            $amount_page = 1000000;

        }

        if ($is_cate == 1) {
            $key = "";
        }

        $condition_search = "";

        //key: img_title + img_tags
        if ($key != "") {
            $condition_search .= " and (img_title like ('%" . addslashes($key) . "%') or img_tags like ('%" . addslashes($key) . "%'))";
        }

        //root_cate
        // if(is_numeric($root_cate) && $root_cate > 0){
        if ($root_cate != "") {

            $condition_search .= " and luxyart_tb_product_img.id_img in(select id_img from luxyart_tb_img_category where root_cate in(" . $root_cate . "))";

        }

        //parent_cate
        if ($parent_cate != "") {

            $condition_search .= " and luxyart_tb_product_img.id_img in(select id_img from luxyart_tb_img_category where parent_cate IN (" . $parent_cate . "))";

        }

        //sub_cate
        // if(is_numeric($sub_cate) && $sub_cate > 0){
        //
        //     $condition_search .= " and luxyart_tb_product_img.id_img in(select id_img from luxyart_tb_img_category where sub_cate = ".$sub_cate.")";
        //
        // }

        //$color
        $str_color = "";
        $i = 0;
        foreach ($color as $_color) {
            if ($i == 0) {
                $str_color .= $_color["color_id"];
            } else {
                $str_color .= "," . $_color["color_id"];
            }
            $i++;
        }
        if ($str_color != "") {
        	
        	$data['search_color'] = (explode(",",$str_color));
        	 
        	$str_search_color = "";
        	for($k=0;$k<count($data['search_color']);$k++){
        		if($k == 0){
        			$str_search_color .= "luxyart_tb_product_img.id_colors in (".$data['search_color'][$k].")";
        		}
        		else{
        			$str_search_color .= " or luxyart_tb_product_img.id_colors in (".$data['search_color'][$k].")";
        		}
        	}
        	$condition_search .= ' and ('.$str_search_color.')';
            
        }

        //kind_img
        if (is_numeric($kind_img) && $kind_img > 0) {
            $condition_search .= " and kind_img = " . $kind_img;
        }

        //option_sale
        if (is_numeric($option_sale) && $option_sale > 0) {

            $condition_search .= " and option_sale = " . $option_sale;

        }

        //is_limit
        if (is_numeric($is_limit) && $is_limit > 0) {
            $condition_search .= " and is_limit = " . $is_limit;
        }

        $str_cate = 'luxyart_tb_product_img.option_sale';

        //type_order
        $this->db->select('luxyart_tb_product_img.*, COUNT(luxyart_tb_user_bookmark_image.id_img) as states_count');

        $this->db->join('luxyart_tb_user_bookmark_image', 'luxyart_tb_user_bookmark_image.id_img = luxyart_tb_product_img.id_img', 'left');

        $this->db->from('luxyart_tb_product_img');

        $this->db->where("img_check_status = 1  AND img_is_join_compe = 0 " . $condition_search);
        $this->db->group_by('luxyart_tb_product_img.id_img');
        // $this->db->having('date_limit >= CURRENT_DATE');
        $rsCount = $this->db->get();
// echo $this->db->last_query();

        $resultCount = $rsCount->result();

        $count_product_img = count($resultCount);

        $this->db->select('luxyart_tb_product_img.*, COUNT(luxyart_tb_user_bookmark_image.id_img) as states_count, COUNT(luxyart_tb_product_img_order_detail.id_img) as amount_buy');
        $this->db->join('luxyart_tb_user_bookmark_image', 'luxyart_tb_user_bookmark_image.id_img = luxyart_tb_product_img.id_img', 'left');
        $this->db->join('luxyart_tb_product_img_order_detail', 'luxyart_tb_product_img_order_detail.id_img = luxyart_tb_product_img.id_img', 'left');

        $this->db->from('luxyart_tb_product_img');

        $this->db->where("img_check_status = 1  AND img_is_join_compe = 0 " . $condition_search);
        $this->db->group_by('luxyart_tb_product_img.id_img');
        // $this->db->having('date_limit >= CURRENT_DATE');
        if ($type_order == 1) {
            $this->db->order_by('amount_buy', 'desc');
            $this->db->order_by('states_count', 'desc');
        }
        $this->db->order_by('date_add_img', 'desc');
        $this->db->order_by('date_update_img', 'desc');
        $this->db->limit($amount_page, $amount_page * ($current_page - 1));

        $rs = $this->db->get();
        // echo $this->db->last_query();

        $result = $rs->result();

        $i = 0;

        foreach ($result as $_result) {

            $id_img = $_result->id_img;

            $id_server = $_result->id_server;

            $id_color = $_result->id_color;

            $manager_server = $this->function_model->getManagerServer($id_server);

            $product_color = $this->function_model->getProductColor($id_color);

            $product_img_size = $this->function_model->getProductImgSize($id_img);

            /*=== Check image expired ===*/
            $date_limit = $this->getExpiredImageDate($_result->date_active, $_result->option_sale);
            // echo '['.$date_limit.' - '.date('Y-m-d').']<br>';
            if ($date_limit >= date('Y-m-d')) {

                $dt[$i]['id_img'] = $id_img;

                $dt[$i]['user_id'] = $_result->user_id;

                $dt[$i]['img_title'] = $_result->img_title;

                $dt[$i]['file_name_original_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_original_img;

                $dt[$i]['file_name_watermark_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_watermark_img;

                $dt[$i]['img_file_md5'] = $_result->img_file_md5;

                $dt[$i]['img_width'] = $_result->img_width;

                $dt[$i]['img_height'] = $_result->img_height;

                $dt[$i]['img_type'] = $_result->img_type;

                $dt[$i]['kind_img'] = $_result->user_id;

                $dt[$i]['id_color'] = $_result->id_color;
                
                $dt[$i]['id_colors'] = $_result->id_colors;

                if (!empty($product_color)) {
                    $dt[$i]['css_color_style'] = $product_color->css_color_style;
                } else {
                    $dt[$i]['css_color_style'] = "";
                }

                $dt[$i]['id_server'] = $_result->id_server;

                $dt[$i]['option_img_watermark'] = $_result->option_img_watermark;

                $dt[$i]['option_sale'] = $_result->option_sale;

                $dt[$i]['img_tags'] = $_result->img_tags;

                $dt[$i]['date_add_img'] = $_result->date_add_img;

                $dt[$i]['img_is_sold'] = $_result->img_is_sold;

                $dt[$i]['img_is_join_compe'] = $_result->img_is_join_compe;

                $dt[$i]['img_check_status'] = $_result->img_check_status;

                $dt[$i]['date_update_img'] = $_result->date_update_img;

                $dt[$i]['img_category'] = $this->getImgCategory($id_img, $lang);

                //is_bookmark

                $dt[$i]['is_bookmark'] = $this->isBookmark($id_img, $user_id);

                //is_follow

                $dt[$i]['is_follow'] = $this->isFollow($_result->user_id, $user_id);

                //Số lượng bookmark và số lượng mua
                $dt[$i]['amount_bookmark'] = $_result->states_count;
                $dt[$i]['amount_bought'] = $_result->amount_buy;

                $i++;
            }

            $isSuccess = 1;
        }

        if ($count_product_img % $amount_page == 0) {

            $data = array("countData" => $count_product_img, "numPage" => $count_product_img / $amount_page, "dt" => $dt);

        } else {

            $data = array("countData" => $count_product_img, "numPage" => floor($count_product_img / $amount_page + 1), "dt" => $dt);

        }

        if (!empty($data)) {

            $list = array("isSuccess" => 1, "message" => "", "data" => $data);

        } else {

            $list = array("isSuccess" => 0, "message" => "", "data" => $data);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

        exit;

    }

    public function search_image_test($item)
    {

        $dt = array();

        $message = "";

        $key = trim($item['key']);
        $is_cate = $item['is_cate'];

        $root_cate = $item['root_cate'];
        $parent_cate = $item['parent_cate'];

        $sub_cate = $item['sub_cate'];
        $color = $item['color'];

        $kind_img = $item['kind_img'];
        $option_sale = $item['option_sale'];
        $is_limit = $item['is_limit'];

        $type_order = $item['type_order'];
        $lang = $item['lang'];

        $user_id = $item['user_id'];
        $current_page = $item['current_page'];

        $amount_page = $item['amount_page'];

        if ($current_page == "") {

            $current_page = 1;

        }

        if ($amount_page == "") {

            $amount_page = 1000000;

        }

        if ($is_cate == 1) {
            $key = "";
        }

        $condition_search = "";

        //key: img_title + img_tags
        if ($key != "") {
            $condition_search .= " and (img_title like ('%" . addslashes($key) . "%') or img_tags like ('%" . addslashes($key) . "%'))";
        }

        //root_cate
        if (is_numeric($root_cate) && $root_cate > 0) {

            $condition_search .= " and luxyart_tb_product_img.id_img in(select id_img from luxyart_tb_img_category where root_cate = $root_cate)";

        }

        //parent_cate
        if (is_numeric($parent_cate) && $parent_cate > 0) {

            $condition_search .= " and luxyart_tb_product_img.id_img in(select id_img from luxyart_tb_img_category where parent_cate = $parent_cate)";

        }

        //sub_cate
        if (is_numeric($sub_cate) && $sub_cate > 0) {

            $condition_search .= " and luxyart_tb_product_img.id_img in(select id_img from luxyart_tb_img_category where sub_cate = $sub_cate)";

        }

        //$color
        $str_color = "";
        $i = 0;
        foreach ($color as $_color) {
            if ($i == 0) {
                $str_color .= $_color["color_id"];
            } else {
                $str_color .= "," . $_color["color_id"];
            }
            $i++;
        }
        if ($str_color != "") {
            $condition_search .= " and id_color in (" . $str_color . ")";
        }

        //kind_img
        if (is_numeric($kind_img) && $kind_img > 0) {
            $condition_search .= " and kind_img = " . $kind_img;
        }

        //option_sale
        if (is_numeric($option_sale) && $option_sale > 0) {

            $condition_search .= " and option_sale = " . $option_sale;

        }

        //is_limit
        if (is_numeric($is_limit) && $is_limit > 0) {
            $condition_search .= " and is_limit = " . $is_limit;
        }

        $str_cate = 'luxyart_tb_product_img.option_sale';

        //type_order
        $this->db->select('luxyart_tb_product_img.*, COUNT(luxyart_tb_user_bookmark_image.id_img) as states_count');

        $this->db->join('luxyart_tb_user_bookmark_image', 'luxyart_tb_user_bookmark_image.id_img = luxyart_tb_product_img.id_img', 'left');

        $this->db->from('luxyart_tb_product_img');

        $this->db->where("img_check_status = 1 " . $condition_search);
        $this->db->group_by('luxyart_tb_product_img.id_img');
        //$this->db->having('date_limit >= CURRENT_DATE');
        $rsCount = $this->db->get();

        $resultCount = $rsCount->result();

        $count_product_img = count($resultCount);

        $this->db->select('luxyart_tb_product_img.*, COUNT(luxyart_tb_user_bookmark_image.id_img) as states_count');
        $this->db->join('luxyart_tb_user_bookmark_image', 'luxyart_tb_user_bookmark_image.id_img = luxyart_tb_product_img.id_img', 'left');

        $this->db->from('luxyart_tb_product_img');

        $this->db->where("img_check_status = 1 " . $condition_search);
        $this->db->group_by('luxyart_tb_product_img.id_img');
        //$this->db->having('date_limit >= CURRENT_DATE');

        $this->db->order_by('states_count', 'desc');

        $this->db->limit($amount_page, $amount_page * ($current_page - 1));

        $rs = $this->db->get();

        $result = $rs->result();

        $i = 0;

        foreach ($result as $_result) {

            $id_img = $_result->id_img;

            $id_server = $_result->id_server;

            $id_color = $_result->id_color;

            $manager_server = $this->function_model->getManagerServer($id_server);

            $product_color = $this->function_model->getProductColor($id_color);

            $product_img_size = $this->function_model->getProductImgSize($id_img);

            $dt[$i]['id_img'] = $id_img;

            $dt[$i]['user_id'] = $_result->user_id;

            $dt[$i]['img_title'] = $_result->img_title;

            $dt[$i]['file_name_original_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_original_img;

            $dt[$i]['file_name_watermark_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_watermark_img;

            $dt[$i]['img_file_md5'] = $_result->img_file_md5;

            $dt[$i]['img_width'] = $_result->img_width;

            $dt[$i]['img_height'] = $_result->img_height;

            $dt[$i]['img_type'] = $_result->img_type;

            $dt[$i]['kind_img'] = $_result->user_id;

            $dt[$i]['id_color'] = $_result->id_color;

            if (!empty($product_color)) {
                $dt[$i]['css_color_style'] = $product_color->css_color_style;
            } else {
                $dt[$i]['css_color_style'] = "";
            }

            $dt[$i]['id_server'] = $_result->id_server;

            $dt[$i]['option_img_watermark'] = $_result->option_img_watermark;

            $dt[$i]['option_sale'] = $_result->option_sale;

            $dt[$i]['img_tags'] = $_result->img_tags;

            $dt[$i]['date_add_img'] = $_result->date_add_img;

            $dt[$i]['img_is_sold'] = $_result->img_is_sold;

            $dt[$i]['img_is_join_compe'] = $_result->img_is_join_compe;

            $dt[$i]['img_check_status'] = $_result->img_check_status;

            $dt[$i]['date_update_img'] = $_result->date_update_img;

            $dt[$i]['img_category'] = $this->getImgCategory($id_img, $lang);

            //is_bookmark

            $dt[$i]['is_bookmark'] = $this->isBookmark($id_img, $user_id);

            //is_follow

            $dt[$i]['is_follow'] = $this->isFollow($_result->user_id, $user_id);

            $i++;

            $isSuccess = 1;
        }

        if ($count_product_img % $amount_page == 0) {

            $data = array("countData" => $count_product_img, "numPage" => $count_product_img / $amount_page, "dt" => $dt);

        } else {

            $data = array("countData" => $count_product_img, "numPage" => floor($count_product_img / $amount_page + 1), "dt" => $dt);

        }

        if (!empty($data)) {

            $list = array("isSuccess" => 1, "message" => "", "data" => $data);

        } else {

            $list = array("isSuccess" => 0, "message" => "", "data" => $data);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

        exit;

    }

    public function active_image($item)
    {

        $dt = array();

        $message = "";

        $image_id = $item['image_id'];

        $user_id = $item['user_id'];

        $status = $item['status'];
        $lang = $item['lang'];

        $user_level = $this->function_model->getUserLevel($user_id);

        if ($user_level == 0) {

            $isSuccess = 0;

        } else if ($user_level == 1) {

            $isSuccess = 0;

        } else if ($user_level >= 2) {

            //0:not active, 1:active, 2:imge bad check by admin, 3:img reported, 4:delete local

            $this->db->select('id_img, img_check_status');

            $this->db->from('luxyart_tb_product_img');

            $this->db->where(array('id_img' => $image_id, 'user_id' => $user_id));

            $rs = $this->db->get();

            if (!empty($rs)) {

                $row = $rs->row();

                $img_check_status = $row->img_check_status;

                if ($img_check_status != $status) {

                    $data = array('img_check_status' => $status, 'date_active' => date('Y-m-d H:i:s'));

                    $this->db->where(array('id_img' => $image_id));

                    $this->db->update('luxyart_tb_product_img', $data);

                    $isSuccess = 1;

                    //$message = $this->lang->line('cap_nhat_thanh_cong');

                    $message = $this->json_model->getMessage('update_success', $lang);

                } else {

                    $isSuccess = 0;

                    //$message = $this->lang->line('ban_dang_o_trang_thai_nay');

                    $message = $this->json_model->getMessage('you_already_been_this_state', $lang);

                }

            } else {

                $isSuccess = 0;

                //$message = $this->lang->line('ban_dang_o_trang_thai_nay');

                $message = $this->json_model->getMessage('you_cant_enter_this_page', $lang);

            }

        }

        if (!empty($dt)) {

            $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);

        } else {

            $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);

        }

        $json = json_encode($list);

        $json = str_replace('\\/', '/', $json);

        echo $json;

    }

    public function post_image($item)
    {

        $this->load->library('sftp');

        $dt = array();

        $message = "";

        $file_name_original_img = $item['file_name_original_img'];
        $option_img_watermark = $item['option_img_watermark'];
        $lang = $item['lang'];
        $user_id = $item['user_id'];
        $os_register = $item['os_register'];
        
        if($os_register == 3){
        	exit;
        }

        $user = $this->function_model->getUser($user_id);
        $user_level = $user->user_level;
        $country_id = $user->country_id;

        $setting = $this->function_model->get_setting();

        $data['allowed_image_formats'] = $setting['allowed_image_formats'];
        $data['max_mb'] = $setting['max_mb'];
        $data['min_width_img'] = $setting['min_width_img'];
        $data['min_height_img'] = $setting['min_height_img'];
        $data['min_width_img_thumnail'] = $setting['min_width_img_thumnail'];
        $data['min_height_img_thumnail'] = $setting['min_height_img_thumnail'];
        $data['percent_image_pixel'] = $setting['percent_image_pixel'];
        $data['date_begin_random_image'] = $setting['date_begin_random_image'];
        $data['date_end_random_image'] = $setting['date_end_random_image'];

        $img_code = $this->function_model->get_code_image();

        //check_is_post_img
        $check_is_post_img = $this->function_model->check_is_post_img($user_id);
        $check_is_post_img_explode = explode("{luxyart}", $check_is_post_img);
        $is_post_img = $check_is_post_img_explode[0];
        $user_level = $check_is_post_img_explode[1];

        if ($is_post_img == 0) {
            if ($user_level == 1) {
                $message = $this->lang->line('please_upgrade_to_creator_or_enterprise');
                $isSuccess = 0;
            } elseif ($user_level == 2) {
                $message = $this->lang->line('please_upgrade_to_enterprise');
                $isSuccess = 0;
            } else {
                $message = $this->lang->line('number_of_picture_exceeded_the_number_of_allowed');
                $isSuccess = 0;
            }
        } else {

            if ($user_level == 0) {
                $isSuccess = 0;
            } else if ($user_level == 1) {
                $isSuccess = 0;
            } else if ($user_level >= 2) {

                //choose server upload image
                $manager_server_upload = $this->function_model->getManagerServerUpload($lang);

                $file_name_original_img_explode = explode(";base64,", $file_name_original_img);

                $file_name_original_type = trim(str_replace("data:image/", "", $file_name_original_img_explode[0]));
                $file_name_original_type = trim(str_replace("image/", "", $file_name_original_type));
                if ($file_name_original_type == "jpeg") {
                    $file_name_original_type = "jpg";
                }

                $file_name_original_img = $file_name_original_img_explode[1];

                $time = time();

                $image_name = $this->function_model->get_image_name() . '.' . $file_name_original_type;
                
                $rand = rand(100000,999999);

                $image_watermark_no_extension = $time.$rand . '-watermark';
                $image_watermark = $time.$rand . '-watermark.jpg';

                $image_blur_no_extension = $time.$rand . '-blur';
                $image_blur = $time.$rand . '-blur.jpg';

                $image_thumnail_no_extension = $time.$rand . '-thumnail';
                $image_thumnail = $time.$rand . '-thumnail.jpg';

                $manager_server = $this->function_model->getManagerServer($country_id);

                $image_upload = $manager_server->server_path_upload_img;

                $image_link = $manager_server->server_path_upload;

                $image = $this->function_model->base64_to_jpeg($file_name_original_img, DIR_TEMP . "/" . $image_name);

                $md5_file = md5_file(DIR_TEMP . $image_name);

                if ($this->function_model->check_md5_file($md5_file) == 0) {

                    $bytes = filesize(DIR_TEMP . $image_name);
                    $mb = $this->function_model->byte_convert_to_mb($bytes);
                    $resolution = $this->function_model->get_dpi(DIR_TEMP . $image_name);

                    if ($resolution[0] == $resolution[1] && $dpi > 36) {
                        $dpi = $resolution[0];
                    } else {
                        $dpi = 0;
                    }

                    $main_color = $this->function_model->main_color_image($image_link . "/" . $image_name);
                    $id_color = $this->function_model->get_compare_color('#' . $main_color);
                    $id_colors = $this->function_model->compareColor($image_link . "/" . $image_name);
                    
                    list($width, $height, $type, $attr) = getimagesize(DIR_TEMP . $image_name);

                    if ($width >= $height) {
                        $kind_img = 1;
                    } else {
                        $kind_img = 2;
                    }

                    if ($option_img_watermark == 1) {

                        $dir = DIR_TEMP . $image_name;
                        $this->function_model->fix_rotate_image($dir,$dir);
                        
                        $dir_end = DIR_TEMP . $image_blur;
                        $ext = strtolower(pathinfo($dir, PATHINFO_EXTENSION));

                        switch ($ext) {
                            case 'gif':
                                $img = imagecreatefromgif($dir);
                                break;
                            case 'jpg':
                                $img = imagecreatefromjpeg($dir);
                                break;
                            case 'png':
                                $img = imagecreatefrompng($dir);
                                break;
                        }

                        imagejpeg($img, $dir_end, 100);

                        $this->function_model->convert_to_pixel(DIR_TEMP . $image_name, DIR_TEMP . "/" . $image_blur, $data['percent_image_pixel']);

                        if ($kind_img == 1 && $width > $data['min_width_img']) {
                            $this->function_model->resize_width($data['min_width_img'], DIR_TEMP . $image_blur_no_extension, DIR_TEMP . $image_blur);
                        } elseif ($kind_img == 2 && $height > $data['min_height_img']) {
                            $this->function_model->resize_width($data['min_width_img'], DIR_TEMP . $image_blur_no_extension, DIR_TEMP . $image_blur);
                        }

                        if ($kind_img == 1 && $width > $data['min_width_img_thumnail']) {
                            $this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP . $image_thumnail_no_extension, DIR_TEMP . $image_blur);
                        } elseif ($kind_img == 2 && $height > $data['min_height_img_thumnail']) {
                            $this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP . $image_thumnail_no_extension, DIR_TEMP . $image_blur);
                        }

                        $image_new = $image_blur;

                    } else if ($option_img_watermark == 2) {

                        $dir = DIR_TEMP . $image_name;
                        $this->function_model->fix_rotate_image($dir,$dir);
                        
                        $dir_end = DIR_TEMP . $image_watermark;
                        $ext = strtolower(pathinfo($dir, PATHINFO_EXTENSION));

                        switch ($ext) {
                            case 'gif':
                                $img = imagecreatefromgif($dir);
                                break;
                            case 'jpg':
                                $img = imagecreatefromjpeg($dir);
                                break;
                            case 'png':
                                $img = imagecreatefrompng($dir);
                                break;
                        }

                        imagejpeg($img, $dir_end, 100);
                        if ($kind_img == 1 && $width > $data['min_width_img']) {
                            $this->function_model->resize_width($data['min_width_img'], DIR_TEMP . $image_watermark_no_extension, DIR_TEMP . $image_watermark);
                        } elseif ($kind_img == 2 && $height > $data['min_height_img']) {
                            $this->function_model->resize_width($data['min_width_img'], DIR_TEMP . $image_watermark_no_extension, DIR_TEMP . $image_watermark);
                        }

                        $this->function_model->watermark_image(DIR_TEMP . $image_watermark_no_extension, DIR . 'img/watermark.png', DIR_TEMP . "/" . $image_watermark, 0);
                        $this->function_model->watermark_image(DIR_TEMP . "/" . $image_watermark, DIR . 'img/watermark.png', DIR_TEMP . "/" . $image_watermark, 0);
                        $this->function_model->watermark_image(DIR_TEMP . "/" . $image_watermark, DIR . 'img/watermark.png', DIR_TEMP . "/" . $image_watermark, 1);
                        $this->function_model->watermark_image(DIR_TEMP . "/" . $image_watermark, DIR . 'img/watermark.png', DIR_TEMP . "/" . $image_watermark, 2);
                        $this->function_model->watermark_image(DIR_TEMP . "/" . $image_watermark, DIR . 'img/watermark.png', DIR_TEMP . "/" . $image_watermark, 3);
                        $this->function_model->watermark_image(DIR_TEMP . "/" . $image_watermark, DIR . 'img/watermark.png', DIR_TEMP . "/" . $image_watermark, 4);
                        $image_new = $image_watermark;

                        if ($kind_img == 1 && $width > $data['min_width_img_thumnail']) {
                            $this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP . $image_thumnail_no_extension, DIR_TEMP . $image_watermark);
                        } elseif ($kind_img == 2 && $height > $data['min_height_img_thumnail']) {
                            $this->function_model->resize_width($data['min_width_img_thumnail'], DIR_TEMP . $image_thumnail_no_extension, DIR_TEMP . $image_watermark);
                        }

                    }

                    //connect server upload
                    $sftp_config['hostname'] = $manager_server_upload->server_name;
                    $sftp_config['username'] = $manager_server_upload->server_ftp_account;
                    $sftp_config['password'] = $manager_server_upload->server_ftp_pass;
                    $sftp_config['port'] = $manager_server_upload->server_port;
                    $sftp_config['debug'] = true;
                    $this->sftp->connect($sftp_config);

                    //upload file new
                    $this->sftp->upload(DIR_TEMP . $image_name, $manager_server_upload->server_path_upload_img . "/" . $image_name);
                    $this->sftp->upload(DIR_TEMP . $image_new, $manager_server_upload->server_path_upload_img . "/" . $image_new);
                    $this->sftp->upload(DIR_TEMP . $image_thumnail, $manager_server_upload->server_path_upload_img . "/" . $image_thumnail);

                    //delete file temp
                    unlink(DIR_TEMP . $image_name);
                    unlink(DIR_TEMP . $image_new);
                    unlink(DIR_TEMP . $image_thumnail);

                    //upload luxyart_tb_manager_server + 2
                    $this->db->where('id_server', $manager_server_upload->id_server);
                    $this->db->set('img_count', 'img_count+2', false);
                    $this->db->update('luxyart_tb_manager_server');

                    $date_add_img = date('Y-m-d H:i:s');

                    $dataAddProductImg = array(
                        'user_id' => $user_id,
                        'file_name_original_img' => $image_name,
                        'file_name_watermark_img' => $image_new,
                        'file_name_watermark_img_1' => $image_thumnail,
                        'img_file_md5' => $md5_file,
                        'img_width' => $width . "px",
                        'img_height' => $height . "px",
                        'img_dpi' => $dpi . "dpi",
                        'img_type' => $file_name_original_type,
                        'kind_img' => $kind_img,
                        'id_color' => $id_color,
                    	'id_colors'	=> $id_colors,
                        'id_server' => $manager_server_upload->id_server,
                        'option_img_watermark' => $option_img_watermark,
                        'date_add_img' => $date_add_img,
                        'os_register' => $os_register,
                        'img_code' => $img_code,
                    );

                    $this->db->insert('luxyart_tb_product_img', $dataAddProductImg);
                    $id_img = $this->db->insert_id();

                    $this->function_model->setFilterImg($id_img, $user_id);

                    //update img link
                    $this->db->where('id_img', $id_img);
                    $this->db->update('luxyart_tb_product_img', array('img_link' => base_url() . "detail/" . $id_img . ".html"));

                    //set count post img
                    $this->db->from('luxyart_tb_product_img');
                    $this->db->select('*');
                    $this->db->where(array('user_id' => $user_id));
                    $result = $this->db->get();
                    $count_all_img = $result->num_rows();
                    
                    $this->db->where('user_id', $user_id);
                    //$this->db->set('count_post_img','count_post_img+1',FALSE);
                    $this->db->set('count_post_img',$count_all_img);
                    $this->db->update('luxyart_tb_user');

                    $dt['id_img'] = $id_img;
                    $dt['user_id'] = $user_id;
                    $dt['file_name_original_img'] = $manager_server_upload->server_path_upload . "/" . $image_name;
                    $dt['file_name_watermark_img'] = $manager_server_upload->server_path_upload . "/" . $image_new;
                    $dt['img_width'] = $width;
                    $dt['img_height'] = $height;
                    $dt['img_dpi'] = $dpi;
                    $dt['img_type'] = $file_name_original_type;
                    $dt['kind_img'] = $kind_img;
                    $dt['id_server'] = $country_id;
                    $dt['option_img_watermark'] = $option_img_watermark;
                    $dt['date_add_img'] = $date_add_img;

                } else {

                    $message = $this->json_model->getMessage('image_already_exist_md5', $lang);

                    //delete image
                    if (file_exists($image_upload . "/" . $image_name)) {
                        unlink($image_upload . "/" . $image_name);
                    }

                }

            }

        }

        if (!empty($dt)) {
            $isSuccess = 1;
            $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);

        } else {
            $isSuccess = 0;
            $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);
        }

        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);
        echo $json;

    }

    public function update_image($item)
    {

        $dt = array();

        $message = "";

        $img_title = $item['img_title'];
        $category = $item['category'];
        $img_tags = $item['img_tags'];
        $option_sale = $item['option_sale'];
        $option_img_watermark = $item['option_img_watermark'];
        $product_img_size = $item['product_img_size'];
        $is_limit = $item['is_limit'];
        $lang = $item['lang'];
        $id_img = $item['id_img'];
        $user_id = $item['user_id'];

        $user = $this->function_model->getUser($user_id);
        $user_level = $this->function_model->getUserLevel($user_id);

        if ($user_level == 0) {
            $isSuccess = 0;
        } else if ($user_level == 1) {
            $isSuccess = 0;
        } else if ($user_level >= 2) {

            $this->db->select('*');
            $this->db->from('luxyart_tb_product_img');
            $this->db->where(array('id_img' => $id_img, 'user_id' => $user_id));

            $rs = $this->db->get();
            $row = $rs->row();

            $add_parent_cate = array();
            $add_sub_cate = array();

            if (!empty($row)) {

                /*=== Check image apply Competition ===*/
                // img_is_join_compe: 0: not apply, 1: already apply
                $date_update_img = $row->date_update_img;
                $type_export_image = $row->option_img_watermark;
                $img_width = str_replace("px", "", $row->img_width);
                $img_height = str_replace("px", "", $row->img_height);

                if ($row->img_is_join_compe == 0) {

                    // Check if new image not active => auto active when update image information
                    if (($row->img_check_status == 0) && ($img_title != '') && ($img_tags != '') && !empty($category)) {
                        //update product
                        $dataProductImg = array(
                            'img_title' => $img_title,
                            'img_tags' => $img_tags,
                            'option_sale' => $option_sale,
                            'is_limit' => $is_limit,
                            'option_img_watermark' => $option_img_watermark,
                            'date_update_img' => date('Y-m-d H:i:s'),
                            'date_active' => date('Y-m-d H:i:s'),
                            'img_check_status' => 1,
                        );
                        $this->db->where('id_img', $id_img);

                        $this->db->update('luxyart_tb_product_img', $dataProductImg);
                    } else {
                        //update product
                        $dataProductImg = array(

                            'img_title' => $img_title,
                            'img_tags' => $img_tags,
                            'option_sale' => $option_sale,
                            'is_limit' => $is_limit,
                            'option_img_watermark' => $option_img_watermark,
                            'date_update_img' => date('Y-m-d H:i:s'),
                        );
                        $this->db->where('id_img', $id_img);

                        $this->db->update('luxyart_tb_product_img', $dataProductImg);
                    }

                    //update img category
                    ////delete img category
                    $this->db->where('id_img', $id_img);
                    $this->db->delete('luxyart_tb_img_category');

                    foreach ($category as $item) {
                        $data = array(
                            'id_img' => $id_img,
                            'root_cate' => $item['root_cate'],
                            'parent_cate' => $item['parent_cate'],
                            'sub_cate' => 0,
                        );
                        $this->db->insert('luxyart_tb_img_category', $data);
                    }

                    //update product img size
                    $dataEditProductImgSize = array(
                        'status_img_size' => 1,
                    );

                    $this->db->where('id_img', $id_img);
                    $this->db->update('luxyart_tb_product_img_size', $dataEditProductImgSize);

                    $i = 1;
                    $total_size_stock = 0;
                    foreach ($product_img_size as $_product_img_size) {

                        $type_size = $_product_img_size['type_size'];
                        $type_size_description = $_product_img_size['type_size_description'];
                        $price_size_point = $_product_img_size['price_size_point'];
                        if ($is_limit == 0) {
                            $size_stock = 0;
                        } else {
                            $size_stock = $_product_img_size['size_stock'];
                        }

                        $total_size_stock += $size_stock;

                        //insert product img size
                        $data = array(
                            'id_img' => $id_img,
                            'type_size' => $type_size,
                            'type_size_description' => $type_size_description,
                            'price_size_point' => $price_size_point,
                            'size_stock' => $size_stock,
                            'code_download' => $this->function_model->get_code_download($id_img . $i),
                            'date_add_img_size' => date('Y-m-d H:i:s'),

                        );
                        if ($type_size != 0) {
                            $this->db->insert('luxyart_tb_product_img_size', $data);
                        }

                        $i++;

                    }

                    //set img is sold
                    if ($is_limit == 0) {
                        $this->db->where('id_img', $id_img);
                        $this->db->set('img_is_sold', 0);
                        $this->db->update('luxyart_tb_product_img');
                    } else {
                        if ($total_size_stock > 0) {
                            $this->db->where('id_img', $id_img);
                            $this->db->set('img_is_sold', 0);
                            $this->db->update('luxyart_tb_product_img');
                        }
                    }

                    $isSuccess = 1;

                    //info send email
                    $email_to = $user->user_email;
                    $name = $this->function_model->get_fullname($user->user_id, $lang);

                    if ($option_sale == 1) {
                        $str_option_sale = "24 " . $this->json_model->get_message('hour', $lang);
                    } elseif ($option_sale == 2) {
                        $str_option_sale = "1 " . $this->json_model->get_message('week', $lang);
                    } elseif ($option_sale == 3) {
                        $str_option_sale = "1 " . $this->json_model->get_message('month', $lang);
                    } else {
                        $str_option_sale = $this->json_model->get_message('limited_of_time_expired', $lang);
                    }

                    if ($type_export_image == 1) {
                        $str_type_export_image = $this->json_model->get_message('blur', $lang);
                    } elseif ($type_export_image == 2) {
                        $str_type_export_image = $this->json_model->get_message('watermark', $lang);
                    }

                    if ($date_update_img == "0000-00-00 00:00:00") { //post image

                        //send email
                        $dataSendEmail = array();
                        $dataSendEmail["name_email_code"] = "post_image";
                        $dataSendEmail["lang_id"] = $lang;
                        $dataSendEmail["email_to"] = $email_to;
                        $dataSendEmail["name"] = ucwords($name);
                        $dataSendEmail["img_title"] = $img_title;
                        $dataSendEmail["img_width"] = $img_width;
                        $dataSendEmail["img_height"] = $img_height;
                        $dataSendEmail["option_sale"] = $str_option_sale;
                        $dataSendEmail["type_export_image"] = $str_type_export_image;
                        $dataSendEmail["link"] = base_url() . "detail/" . $row->img_code . ".html";
                        //$dataSendEmail["is_send_admin"]        = 1;

                        $this->function_model->sendMailHTML($dataSendEmail);

                        //send notice
                        $addNotice = array();
                        $addNotice['type'] = "post_image";
                        $addNotice["lang_id"] = $lang;
                        $addNotice['user_id'] = $user_id;
                        $addNotice['img_title'] = $img_title;
                        $addNotice['link'] = base_url() . "detail/" . $row->img_code . ".html";
                        $addNotice['option_id'] = $id_img;

                        $this->function_model->addNotice($addNotice);

                    } else { //edit image

                        //send email
                        $dataSendEmail = array();
                        $dataSendEmail["name_email_code"] = "edit_image";
                        $dataSendEmail["lang_id"] = $lang;
                        $dataSendEmail["email_to"] = $email_to;
                        $dataSendEmail["name"] = ucwords($name);
                        $dataSendEmail["img_title"] = $img_title;
                        $dataSendEmail["img_width"] = $img_width;
                        $dataSendEmail["img_height"] = $img_height;
                        $dataSendEmail["option_sale"] = $str_option_sale;
                        $dataSendEmail["type_export_image"] = $str_type_export_image;
                        $dataSendEmail["link"] = base_url() . "detail/" . $row->img_code . ".html";
                        $dataSendEmail["is_send_admin"] = 1;
                        //$dataSendEmail["is_debug"]            = 1;

                        $this->function_model->sendMailHTML($dataSendEmail);

                        //send notice
                        $addNotice = array();
                        $addNotice['type'] = "edit_image";
                        $addNotice["lang_id"] = $lang;
                        $addNotice['user_id'] = $user_id;
                        $addNotice['img_title'] = $img_title;
                        $addNotice['link'] = base_url() . "detail/" . $row->img_code . ".html";
                        $addNotice['option_id'] = $id_img;

                        $this->function_model->addNotice($addNotice);

                    }

                    $message = $this->json_model->getMessage('update_success', $lang);

                } else {
                    $isSuccess = 0;
                    $message = $this->json_model->getMessage('your_image_already_apply_competition_cannot_edit_information', $lang);
                }

            }
        }

        if (!empty($dt)) {
            $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);
        } else {
            $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);
        }

        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);
        echo $json;

    }

    public function save_to_cart($item)
    {

        $this->load->library('sftp');

        $dt = array();

        $message = "";

        $id_img_sizes = $item['id_img_sizes'];
        $user_id = $item['user_id'];
        $lang = $item['lang'];

        $data['detail_user'] = $this->function_model->getUser($user_id);

        $setting = $this->function_model->get_setting();
        $value_end_download = "+".$setting['value_end_download']." month";
        $admin_get_point = $setting['admin_get_point'];
        $user_get_point = $setting['user_get_point'];
        $investor_get_point = $setting['investor_get_point'];
        $seller_get_point = $setting['seller_get_point'];
        $member_get_point = $setting['member_get_point'];

        $user_level = $this->function_model->getUserLevel($user_id);

        if ($user_level == 0) {
            $isSuccess = 0;
            $message = $this->json_model->getMessage('you_do_not_have_permission_to_access_this_page', $lang);
        } else if ($user_level >= 1) {

            foreach ($id_img_sizes as $_id_img_size) {

                $id_img_size = $_id_img_size['id_img_size'];

                if ($id_img_size > 0) {

                    $data['add_cart'] = $this->shopping_model->get_data_add_cart($id_img_size)->row();

                    $is_limit = $data['add_cart']->is_limit;
                    // echo json_encode($data['add_cart']);
                    // exit;
                    // echo "check isLimit & sizeStock";
                    /*=== Check isLimit & sizeStock ===*/
                    if (($data['add_cart']->is_limit == 1) && ($data['add_cart']->size_stock == 0)) {
                        // echo "This image is out of stock";
                        $isSuccess = 0;
                        $message = $this->json_model->getMessage('this_image_is_out_of_stock', $lang);
                    } else {
                        // echo "Can buy";
                        // exit;
                        $price_size_point = $data['add_cart']->price_size_point;
                        $date_end_download = date('Y-m-d H:i:s', strtotime($value_end_download, time()));

                        if ($data['add_cart']->user_id == $user_id) {
                            $isSuccess = 0;
                            $message = $this->json_model->getMessage('you_cant_buy_your_image', $lang);
                        }

                        $user_paymoney_getpoint = $data['detail_user']->user_paymoney_getpoint;
                        $user_point = $data['detail_user']->user_point;
                        $total_point = $user_paymoney_getpoint + $user_point;

                        if ($total_point < $price_size_point) {
                            $isSuccess = 0;
                            $message = $this->json_model->getMessage('your_point_isnt_enough_to_buy_image', $lang);
                        }

                        //add order
                        $dataAddProductImgOrder = array(
                            'user_buy_img' => $user_id,
                            'total_point_order' => $price_size_point,
                            'date_order_img' => date('Y-m-d H:i:s'),
                            'status_order' => 2,
                            'date_end_download' => $date_end_download,
                        );

                        $this->db->insert('luxyart_tb_product_img_order', $dataAddProductImgOrder);
                        $id_order_detail = $this->db->insert_id();

                        $point_user_owner_get = $user_get_point * $price_size_point / 100;
                        $investor_get_point = $investor_get_point * $price_size_point / 100;
                        $member_get_point = $member_get_point*$price_size_point/100;
                        
                        $inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart");
                        
                        //member
                        $this->db->from('luxyart_tb_member');
                        $this->db->select('*');
                        $this->db->where(array('user_id_invited' => $data['add_cart']->user_id,'status' => 2));
                        $rsMember = $this->db->get();
                        $countMember = $rsMember->num_rows();
                        	
                        $user_member_get = 0;
                        $point_user_member_get = 0;
                        if($countMember == 1){
                        	$resultMember = $rsMember->row();
                        	$user_member_get = $resultMember->user_id;
                        	$point_user_member_get = $member_get_point*$price_size_point/100;
                        }
                        	
                        //invite
                        $user_invite_sale = 0;
                        $point_user_invite_sale_get = 0;
                        $invite_sale = $this->session->userdata['invite_sale'];
                        if($invite_sale){
                        	$invite_user_invite_sale = $invite_sale['user_invite_sale'];
                        	$invite_id_img = $invite_sale['id_img'];
                        	if($invite_id_img == $data['add_cart']->id_img){
                        		$user_invite_sale = $invite_user_invite_sale;
                        		$point_user_invite_sale_get = $invite_get_point*$price_size_point/100;
                        	}
                        }
                        
                        //get img
                        $user_get_img = 0;
                        $point_user_get_img = 0;
                        $get_img = $this->session->userdata['get_img'];
                        if($get_img){
                        	$get_user_id = $get_img['user_id_get_img'];
                        	$get_id_img = $get_img['id_img'];
                        	if($get_id_img == $data['add_cart']->id_img){
                        		$user_get_img = $get_user_id;
                        		$point_user_get_img = $seller_get_point*$price_size_point/100;
                        	}
                        }
                        
                        //admin
                        $admin_get_point = $price_size_point - $point_user_owner_get - $investor_get_point - $point_user_invite_sale_get - $point_user_get_img;

                        //add order detail
                        $dataAddProductImgOrder = array(
                            'id_order_img' 					=> $id_order_detail,
                            'id_img' 						=> $data['add_cart']->id_img,
                            'id_img_size'					=> $data['add_cart']->id_img_size,
                            'user_owner' 					=> $data['add_cart']->user_id,
                            'point_user_owner_get' 			=> $point_user_owner_get,
                            'user_invite_sale' 				=> $user_invite_sale,
							'point_user_invite_sale_get' 	=> $point_user_invite_sale_get,
							'user_keep_store' 				=> $user_get_img,
							'point_user_keep_store_get'		=> $point_user_get_img,
							'admin_get_point'				=> $admin_get_point,
							'investor_get_point'			=> $investor_get_point,
							'user_member_get'				=> $user_member_get,
							'point_user_member_get'			=> $point_user_member_get
                        );

                        $this->db->insert('luxyart_tb_product_img_order_detail', $dataAddProductImgOrder);

                        //user buy: 1.minus point 2.notice
                        if($user_paymoney_getpoint - $price_size_point >= 0){
                        		
                        	$this->db->where('user_id', $user_id);
                        	$this->db->set('user_paymoney_getpoint','user_paymoney_getpoint-'.$price_size_point,FALSE);
                        	$this->db->update('luxyart_tb_user');
                        
                        	$this->function_model->set_use_point_package(round($price_size_point,2), $user_id);
                        		
                        }
                        else{
                        		
                        	$this->db->where('user_id', $user_id);
                        	$this->db->set('user_paymoney_getpoint',0);
                        	$this->db->update('luxyart_tb_user');
                        		
                        	$this->db->where('user_id', $user_id);
                        	$this->db->set('user_point','user_point-'.$price_size_point."+".$user_paymoney_getpoint,FALSE);
                        	$this->db->update('luxyart_tb_user');
                        		
                        }
                        
                        $notice_point_user_buy = $this->lang->line('management_point_shopping_cart_user_buy');
                        $notice_point_user_buy = str_replace("{point}", $price_size_point, $notice_point_user_buy);
                        $notice_point_user_buy = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_buy);
                        $notice_point_user_buy = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_buy);
                        
                        $dataAddPointUser = array(
                        		'user_id' 						=> $user_id,
                        		'point' 						=> $price_size_point,
                        		'status_change_point' 			=> 2,
                        		'inbox_id' 						=> $inbox_id,
                        		'content' 						=> $notice_point_user_buy,
                        		'date_add' 						=> date('Y-m-d H:i:s')
                        );
                        
                        $this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
                        
                        $addNotice = array();
                        $addNotice['type'] 						= "shopping_cart_user_buy";
                        $addNotice["lang_id"] 					= $this->lang_id;
                        $addNotice['user_id'] 					= $user_id;
                        $addNotice['user_send_id'] 				= -1;
                        $addNotice['point'] 					= $price_size_point;
                        $addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
                        $addNotice['img_title'] 				= $data['add_cart']->img_title;
                        //$addNotice['is_debug'] 				= 1;
                        
                        $this->function_model->addNotice($addNotice);
                        
                        //user sale: 1:add point 2:notice
                        $this->db->where('user_id', $data['add_cart']->user_id);
                        $this->db->set('user_point','user_point+'.($point_user_owner_get-$point_user_member_get),FALSE);
                        $this->db->update('luxyart_tb_user');
                        
                        $user_fullname = $data['detail_user']->display_name;
                        
                        $notice_point_user_get_point = $this->lang->line('management_point_shopping_cart_user_get_point');
                        $notice_point_user_get_point = str_replace("{point}", ($point_user_owner_get-$point_user_member_get), $notice_point_user_get_point);
                        $notice_point_user_get_point = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_point);
                        $notice_point_user_get_point = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_point);
                        $notice_point_user_get_point = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_point);
                        
                        $dataAddPointUser = array(
                        		'user_id' 						=> $data['add_cart']->user_id,
                        		'point' 						=> ($point_user_owner_get-$point_user_member_get),
                        		'status_change_point' 			=> 1,
                        		'inbox_id' 						=> $inbox_id,
                        		'content' 						=> $notice_point_user_get_point,
                        		'date_add' 						=> date('Y-m-d H:i:s')
                        );
                        $this->db->insert('luxyart_tb_management_point2', $dataAddPointUser);
                        
                        $addNotice = array();
                        $addNotice['type'] 						= "shopping_cart_user_sale_image";
                        $addNotice["lang_id"] 					= $this->lang_id;
                        $addNotice['user_id'] 					= $data['add_cart']->user_id;
                        $addNotice['user_fullname'] 			= $data['add_cart']->display_name;
                        $addNotice['point'] 					= ($point_user_owner_get-$point_user_member_get);
                        $addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
                        $addNotice['img_title'] 				= $data['add_cart']->img_title;
                        //$addNotice['is_debug'] 				= 1;
                        
                        $this->function_model->addNotice($addNotice);

                        //user member
                        if($point_user_member_get > 0){
                        		
                        	$data['detail_user_member_get'] = $this->function_model->getUser($user_member_get);
                        	$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_member_get");
                        		
                        	$this->db->where('user_id', $user_member_get);
                        	$this->db->set('user_point','user_point+'.$point_user_member_get,FALSE);
                        	$this->db->update('luxyart_tb_user');
                        		
                        	$user_fullname = $data['detail_user_member_get']->display_name;
                        		
                        	$notice_point_user_member_get = $this->lang->line('management_point_shopping_cart_user_member_get');
                        	$notice_point_user_member_get = str_replace("{point}", $point_user_member_get, $notice_point_user_member_get);
                        	$notice_point_user_member_get = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_member_get);
                        	$notice_point_user_member_get = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_member_get);
                        	$notice_point_user_member_get = str_replace("{user_fullname}", $user_fullname, $notice_point_user_member_get);
                        		
                        	$dataAddPointUser = array(
                        			'user_id' 						=> $user_member_get,
                        			'point' 						=> $point_user_member_get,
                        			'status_change_point' 			=> 1,
                        			'inbox_id' 						=> $inbox_id,
                        			'content' 						=> $notice_point_user_member_get,
                        			'date_add' 						=> date('Y-m-d H:i:s')
                        	);
                        		
                        	$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
                        		
                        	$addNotice = array();
                        	$addNotice['type'] 						= "shopping_cart_user_member_get";
                        	$addNotice["lang_id"] 					= $this->lang_id;
                        	$addNotice['user_id'] 					= $user_member_get;
                        	$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
                        	$addNotice['point'] 					= $point_user_member_get;
                        	$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
                        	$addNotice['img_title'] 				= $data['add_cart']->img_title;
                        	//$addNotice["is_debug"] 				= 1;
                        		
                        	$this->function_model->addNotice($addNotice);
                        		
                        }
                        
                        //user invite
                        if($point_user_invite_sale_get > 0){
                        
                        	$data['detail_user_invite_sale'] = $this->function_model->getUser($user_invite_sale);
                        
                        	$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_invite_image");
                        
                        	$this->db->where('user_id', $user_invite_sale);
                        	$this->db->set('user_point','user_point+'.$point_user_invite_sale_get,FALSE);
                        	$this->db->update('luxyart_tb_user');
                        
                        	$user_fullname = $data['detail_user_invite_sale']->display_name;
                        
                        	$notice_point_user_invite = $this->lang->line('management_point_shopping_cart_user_invite');
                        	$notice_point_user_invite = str_replace("{point}", $point_user_invite_sale_get, $notice_point_user_invite);
                        	$notice_point_user_invite = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_invite);
                        	$notice_point_user_invite = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_invite);
                        	$notice_point_user_invite = str_replace("{user_fullname}", $user_fullname, $notice_point_user_invite);
                        
                        	$dataAddPointUser = array(
                        			'user_id' 						=> $user_invite_sale,
                        			'point' 						=> $point_user_invite_sale_get,
                        			'status_change_point' 			=> 1,
                        			'inbox_id' 						=> $inbox_id,
                        			'content' 						=> $notice_point_user_invite,
                        			'date_add' 						=> date('Y-m-d H:i:s')
                        	);
                        		
                        	$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
                        
                        	$addNotice = array();
                        	$addNotice['type'] 						= "shopping_cart_user_invite_image";
                        	$addNotice["lang_id"] 					= $this->lang_id;
                        	$addNotice['user_id'] 					= $user_invite_sale;
                        	$addNotice['user_fullname'] 			= $data['add_cart']->display_name;
                        	$addNotice['point'] 					= $point_user_invite_sale_get;
                        	$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
                        	$addNotice['img_title'] 				= $data['add_cart']->img_title;
                        	//$dataSendEmail["is_debug"] 			= 1;
                        
                        	$this->function_model->addNotice($addNotice);
                        
                        }
                        
                        //user get img
                        if($point_user_get_img > 0){
                        		
                        	$data['detail_user_get_img'] = $this->function_model->getUser($user_get_img);
                        		
                        	$inbox_id = $this->function_model->get_inbox_templates_id("shopping_cart_user_get_image");
                        		
                        	$this->db->where('user_id', $user_get_img);
                        	$this->db->set('user_point','user_point+'.$point_user_get_img,FALSE);
                        	$this->db->update('luxyart_tb_user');
                        		
                        	$user_fullname = $data['detail_user_get_img']->display_name;
                        		
                        	$notice_point_user_get_img = $this->lang->line('management_point_shopping_cart_user_get_img');
                        	$notice_point_user_get_img = str_replace("{point}", $point_user_get_img, $notice_point_user_get_img);
                        	$notice_point_user_get_img = str_replace("{link}", base_url()."detail/".$data['add_cart']->img_code.".html", $notice_point_user_get_img);
                        	$notice_point_user_get_img = str_replace("{img_title}", $data['add_cart']->img_title, $notice_point_user_get_img);
                        	$notice_point_user_get_img = str_replace("{user_fullname}", $user_fullname, $notice_point_user_get_img);
                        		
                        	$dataAddPointUser = array(
                        			'user_id' 						=> $user_get_img,
                        			'point' 						=> $point_user_get_img,
                        			'status_change_point' 			=> 1,
                        			'inbox_id' 						=> $inbox_id,
                        			'content' 						=> $notice_point_user_get_img,
                        			'date_add' 						=> date('Y-m-d H:i:s')
                        	);
                        		
                        	$this->db->insert('luxyart_tb_management_point', $dataAddPointUser);
                        		
                        	$addNotice = array();
                        	$addNotice['type'] 						= "shopping_cart_user_get_image";
                        	$addNotice["lang_id"] 					= $this->lang_id;
                        	$addNotice['user_id'] 					= $user_get_img;
                        	$addNotice['user_fullname'] 			= $data['detail_user_get_img']->display_name;
                        	$addNotice['point'] 					= $point_user_get_img;
                        	$addNotice['link'] 						= base_url()."detail/".$data['add_cart']->img_code.".html";
                        	$addNotice['img_title'] 				= $data['add_cart']->img_title;
                        	//$addNotice["is_debug"] 				= 1;
                        		
                        	$this->function_model->addNotice($addNotice);
                        		
                        }
                        
                        //update size stock
                        if($is_limit == 1){
                        
                        	$this->db->where('id_img_size', $id_img_size);
                        	$this->db->set('size_stock','size_stock-1',FALSE);
                        	$this->db->update('luxyart_tb_product_img_size');
                        
                        	$list_img_size = $this->manager_image_model->get_list_img_size($data['add_cart']->id_img)->result();
                        	if(count($list_img_size) == 0){
                        		$this->db->where('id_img', $data['add_cart']->id_img);
                        		$this->db->set('img_is_sold',1);
                        		$this->db->update('luxyart_tb_product_img');
                        	}
                        
                        }
                        
                        //download file to folder download
                        $manager_server = $this->function_model->getManagerServer($data['add_cart']->id_server);
                        
                        //connect server old
                        $sftp_config['hostname'] 	= $manager_server->server_name;
                        $sftp_config['username'] 	= $manager_server->server_ftp_account;
                        $sftp_config['password'] 	= $manager_server->server_ftp_pass;
                        $sftp_config['port'] 		= $manager_server->server_port;
                        $sftp_config['debug'] 		= TRUE;
                        $this->sftp->connect($sftp_config);
                        
                        $file_name_original_img = $data['add_cart']->file_name_original_img;
                        $file_name_original_img_name = explode(".",$file_name_original_img)[0];
                        
                        $type_size_description = $data['add_cart']->type_size_description;
                        $type_size_description_width = explode("px",$type_size_description)[0];
                        
                        $this->sftp->download($manager_server->server_path_upload_img."/".$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img);
                        $this->function_model->resize($type_size_description_width, DIR_DOWNLOAD.$file_name_original_img, DIR_DOWNLOAD.$file_name_original_img_name);
                        
                        //send email buy image
                        $dataSendEmail = array();
                        $dataSendEmail["name_email_code"] 		= "shopping_cart_user_buy";
                        $dataSendEmail["lang_id"] 				= $this->lang_id;
                        $dataSendEmail["email_to"] 				= $data['detail_user']->user_email;
                        $dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
                        $dataSendEmail["img_title"]				= $data['add_cart']->img_title;
                        $dataSendEmail["img_width"]				= $data['add_cart']->img_width;
                        $dataSendEmail["img_height"]			= $data['add_cart']->img_height;
                        $dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
                        $dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
                        $dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
                        $dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
                        $dataSendEmail["date_end_download"] 	= $date_end_download;
                        $dataSendEmail["file_attachment"] 		= DIR_DOWNLOAD.$data['add_cart']->file_name_original_img;
                        $dataSendEmail["is_send_admin"] 		= 1;
                        //$dataSendEmail["is_debug"] 			= 1;
                        
                        //$save_email["shopping_cart_user_buy"][0] = $dataSendEmail;
                        $this->function_model->sendMailHTML($dataSendEmail);
                        
                        //send email sale image
                        $dataSendEmail = array();
                        $dataSendEmail["name_email_code"] 		= "shopping_cart_user_sale";
                        $dataSendEmail["lang_id"] 				= $this->lang_id;
                        $dataSendEmail["email_to"] 				= $data['add_cart']->user_email;
                        $dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['add_cart']->user_id,$this->lang_id));
                        $dataSendEmail["img_title"]				= $data['add_cart']->img_title;
                        $dataSendEmail["img_width"]				= $data['add_cart']->img_width;
                        $dataSendEmail["img_height"]			= $data['add_cart']->img_height;
                        $dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
                        $dataSendEmail["email_buy_image"] 		= $data['detail_user']->user_email;
                        $dataSendEmail["person_buy_image"] 		= ucwords($this->function_model->get_fullname($data['detail_user']->user_id,$this->lang_id));
                        $dataSendEmail["link_order"] 			= base_url()."mypage/list-order";
                        $dataSendEmail["date_end_download"] 	= $date_end_download;
                        //$dataSendEmail["is_debug"] 			= 1;
                        	
                        $this->function_model->sendMailHTML($dataSendEmail);
                        //$save_email["shopping_cart_user_sale"][0] = $dataSendEmail;
                        
                        //send email invite image
                        if($point_user_invite_sale_get > 0){
                        
                        	$dataSendEmail = array();
                        	$dataSendEmail["name_email_code"] 		= "shopping_cart_user_invite";
                        	$dataSendEmail["lang_id"] 				= $this->lang_id;
                        	$dataSendEmail["email_to"] 				= $data['detail_user_invite_sale']->user_email;
                        	$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_invite_sale']->user_id,$this->lang_id));
                        	$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
                        	$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
                        	$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
                        	$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
                        	$dataSendEmail["email_buy_image"] 		= $data['detail_user_invite_sale']->user_email;
                        	$dataSendEmail["date_end_download"] 	= $date_end_download;
                        	//$dataSendEmail["is_debug"] 			= 1;
                        
                        	$this->function_model->sendMailHTML($dataSendEmail);
                        	//$save_email["shopping_cart_user_invite"][0] = $dataSendEmail;
                        		
                        }
                        
                        //send email invite image
                        if($point_user_get_img > 0){
                        
                        	$dataSendEmail = array();
                        	$dataSendEmail["name_email_code"] 		= "shopping_cart_user_get_img";
                        	$dataSendEmail["lang_id"] 				= $this->lang_id;
                        	$dataSendEmail["email_to"] 				= $data['detail_user_get_img']->user_email;
                        	$dataSendEmail["name"] 					= ucwords($this->function_model->get_fullname($data['detail_user_get_img']->user_id,$this->lang_id));
                        	$dataSendEmail["img_title"]				= $data['add_cart']->img_title;
                        	$dataSendEmail["img_width"]				= $data['add_cart']->img_width;
                        	$dataSendEmail["img_height"]			= $data['add_cart']->img_height;
                        	$dataSendEmail["link"]					= base_url()."detail/".$data['add_cart']->img_code.".html";
                        	$dataSendEmail["email_buy_image"] 		= $data['detail_user_get_img']->user_email;
                        	$dataSendEmail["date_end_download"] 	= $date_end_download;
                        	//$dataSendEmail["is_debug"] 			= 1;
                        		
                        	$this->function_model->sendMailHTML($dataSendEmail);
                        	//$save_email["shopping_cart_user_get_img"][0] = $dataSendEmail;
                        		
                        }

                        //delete file temp
                        unlink(DIR_DOWNLOAD.$data['add_cart']->file_name_original_img);

                        /*=== Push notification ===*/
                        $this->db->from('luxyart_tb_product_img');
                        $this->db->where(array('id_img' => $data['add_cart']->id_img));
                        $this->db->select('luxyart_tb_product_img.user_id');
                        $rs = $this->db->get();
                        $userReceive = $rs->row();

                        $content = $this->json_model->getContentNotice("shopping_cart_user_buy", $lang);
                        $content = str_replace('{img_title}', $data['add_cart']->img_title, $content);
                        $this->json_model->pushNotification($userReceive->user_id, $content);
                        /*============== End ==================*/

                        $isSuccess = 1;
                    }
                } else {
                    $isSuccess = 0;
                }
            }
            /*=== End For ===*/
        }

        $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => array());

        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;

    }

    public function getImgCategory($id_img, $lang)
    {

        $this->db->from('luxyart_tb_img_category');
        $this->db->select('*');
        $this->db->where('id_img', $id_img);
        $rs = $this->db->get();
        $result = $rs->result();

        $j = 0;

        $dt = array();

        foreach ($result as $_result) {

            $root_cate = @$this->function_model->getRootCate($_result->root_cate, $lang);
            $parent_cate = $this->function_model->getParentCate($_result->parent_cate, $lang);
            $sub_cate = @$this->function_model->getSubCate($_result->sub_cate, $lang);

            $dt[$j]['id_img_cate'] = $_result->id_img_cate;
            $dt[$j]['id_img'] = $_result->id_img;

            if (!empty($root_cate)) {
                $dt[$j]['root_cate'] = $root_cate->product_category_id;

                $dt[$j]['root_cate_name'] = $root_cate->cate_name;
            } else {
                $dt[$j]['root_cate'] = -1;

                $dt[$j]['root_cate_name'] = "";
            }

            $dt[$j]['parent_cate'] = $_result->parent_cate;
            if (!empty($parent_cate)) {
                $dt[$j]['parent_cate_name'] = $parent_cate->cate_name;
            } else {
                $dt[$j]['parent_cate_name'] = "";
            }

            $dt[$j]['sub_cate'] = $_result->sub_cate;
            if (!empty($sub_cate)) {
                $dt[$j]['sub_cate_name'] = $sub_cate->cate_name;
            } else {
                $dt[$j]['sub_cate_name'] = "";
            }

            $j++;

        }

        return $dt;

    }
    
    public function getListSubComment($comment_id, $user_id, $lang){
    	
    	$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
    	$this->db->from('luxyart_tb_comment');
    	$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
    	if($comment_id > 0){
    		if($user_id == ""){
    			$this->db->where(array('luxyart_tb_comment.comment_id_parent' => $comment_id, 'luxyart_tb_comment.level' => 2, 'luxyart_tb_comment.status' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
    		}
    		else{
    			$this->db->where("`luxyart_tb_comment`.`comment_id_parent` = '".$comment_id."' AND `luxyart_tb_comment`.`level` = 2 AND (`luxyart_tb_comment`.`status` = 1 OR (`luxyart_tb_comment`.`status` = 0 AND `luxyart_tb_comment`.`user_id` = ".$user_id.")) AND `luxyart_tb_product_img`.`img_check_status` != 4");
    		}
    	}
    	$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
    	$rs = $this->db->get();
    	$result = $rs->result();
    
    	$j = 0;
    	$dt = array();
    
    	foreach ($result as $_result) {
    
    		$dt[$j]['comment_id'] = $_result->comment_id;
    		$dt[$j]['id_img'] = $_result->id_img;
    		$dt[$j]['user_id'] = $_result->user_id;
    		$dt[$j]['display_name'] = $_result->display_name;
    		
    		if($_result->user_avatar == ""){
    			$dt[$j]['avatar'] = SRC."/publics/avatar/prof-img.png";
    		}
    		else{
    			$dt[$j]['avatar'] = SRC."/publics/avatar/".$_result->user_avatar;
    		}
    		
    		$dt[$j]['level'] = $_result->level;
    		$dt[$j]['comment_id_parent'] = $_result->comment_id_parent;
    		$dt[$j]['comment'] = $_result->comment;
    		$dt[$j]['date_create'] = $_result->date_create;
    		$dt[$j]['status'] = $_result->status;
    
    		$j++;
    
    	}
    
    	return $dt;
    
    }

    public function isBookmark($id_img, $user_id)
    {

        $this->db->from('luxyart_tb_user_bookmark_image');

        $this->db->select('*');

        $this->db->where(array('id_img' => $id_img, 'user_id' => $user_id));

        $rs = $this->db->get();

        $row = $rs->row();

        if (!empty($row)) {

            return 1;

        } else {

            return 0;

        }

    }

    public function isFollow($follow_user_id, $user_id)
    {

        $this->db->from('luxyart_tb_user_follow');

        $this->db->select('*');

        $this->db->where(array('follow_user_id' => $user_id, 'user_id' => $follow_user_id));

        $rs = $this->db->get();

        $row = $rs->row();

        if (!empty($row)) {

            return 1;

        } else {

            return 0;

        }

    }

    // function countFollow($user_id){
    //
    //     $this->db->from('luxyart_tb_user_follow');
    //     $this->db->select('*');
    //     $this->db->where(array('user_id' => $user_id));
    //     $rs = $this->db->get();
    //     $count = $rs->num_rows();
    //     return $count;
    //
    // }

    /*============================*/
    /*======= Tan Code ===========*/
    /*============================*/

    public function get_point_package_list($item)
    {
        $message = "";
        $lang = $item['lang'];
        $option_pay = $item['option_pay'];

        $this->db->select('luxyart_tb_list_credit.*');
        $this->db->from('luxyart_tb_list_credit');
        if($option_pay == 2){
        	$this->db->where(array('status_credit'=>0,'option_pay'=>$option_pay));
        }
        else{
        	$this->db->where(array('status_credit'=>0,'option_pay'=>1));
        }
        $rs = $this->db->get();
        $result = $rs->result();
        $data = $result;
        $list = array("isSuccess" => 1, "message" => "", "data" => $data);

        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);
        echo $json;
        exit;
    }

    public function buy_point($item)
    {
    	$message = "";
    	$isSuccess = 0;
    	$data = array();
    
    	$user_id = $item['user_id'];
    	$id_point_package = $item['id_point_package'];
    	$payment_method = $item['payment_method'];
    	$token_ordercredit = $item['token_ordercredit'];
    	$payment_status = $item['payment_status'];
    	$lang = $item['lang'];
    	
    	if($payment_method == 5){
    		$apple_id = $item['apple_id'];
    		$money_pay_credit = $item['money_pay_credit'];
    		$local_pay = $item['local_pay'];
    		$point_credit = $item['point_credit'];
    		$package_name = $item['package_name'];
    	}
    	
    	//get for local_pay + apple_id
    	$this->db->select('luxyart_tb_list_credit.*');
    	$this->db->from('luxyart_tb_list_credit');
    	$this->db->where(array('local_pay'=>$local_pay,'apple_id'=>$apple_id,'option_pay'=>2));
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_credit = count($result);
    	
    	if($count_credit == 0){
    		//insert
    		$insertListCredit = array(
    			'name_credit' 		=> $package_name,
    			'point_credit' 		=> $point_credit,//15000
    			'money_pay_credit' 	=> $money_pay_credit,
    			'value_point_money' => round($money_pay_credit/$point_credit,2),//15
    			'status_credit' 	=> 0,
    			'option_pay'		=> 2,
    			'local_pay' 		=> $local_pay,
    			'apple_id' 			=> $apple_id
    		);
    		$this->db->insert('luxyart_tb_list_credit', $insertListCredit);
    		$id_point_package = $this->db->insert_id();
    	}
    	else{
    		
    		$rowListCredit = $rs->row();
    		$id_point_package = $rowListCredit->id_list;
    		
    		//update
    		$dataListCredit = array(
    			'name_credit' 		=> $package_name,
    			'point_credit' 		=> $point_credit,//15000
    			'money_pay_credit' 	=> $money_pay_credit,
    			'value_point_money' => round($money_pay_credit/$point_credit,2),//15
    			'option_pay'		=> 2,
    			'local_pay' 		=> $local_pay,
    			'apple_id' 			=> $apple_id
    		);
    		$this->db->where(array('id_list' => $id_point_package));
    		$this->db->update('luxyart_tb_list_credit', $dataListCredit);
    		
    	}
    
    	$data['payment_method'] = $payment_method;
    	$data['user_paymentmoney_getpoint'] = 0;
    
    	$dataAdd = array(
    			'user_id' => $user_id,
    			'id_list' => $id_point_package,
    			'payment_menthod' => $payment_method,
    			'token_ordercredit' => $token_ordercredit,
    			'payment_status' => $payment_status,
    			'date_order_credit' => date('Y-m-d H:i:s'),
    	);
    	$this->db->insert('luxyart_tb_user_order_credit', $dataAdd);
    	$id_order = $this->db->insert_id();
    
    	if ($id_order > 0) {
    		$this->db->select('luxyart_tb_list_credit.*');
    		$this->db->from('luxyart_tb_list_credit');
    		$this->db->where('id_list', $id_point_package);
    		$rs = $this->db->get();
    		$result = $rs->result();
    		$money_pay_credit = $result[0]->money_pay_credit;
    		$point_credit = $result[0]->point_credit;
    
    		$result = $this->function_model->getUser($user_id);
    		$user_paymentmoney_getpoint = $result->user_paymoney_getpoint;
    		if ($payment_method == 4) {
    			/*=== Stripe payment ===*/
    			require '/var/www/html/application/libraries/stripe/Stripe.php';
    
    			//Key Test
    			// Stripe::setApiKey("sk_test_coDx2kN3APcZL8EfX5AYPdge");
    			//Key Public
    			Stripe::setApiKey("pk_live_JhEIbvKZpdsTsAe520dzVTC9");
    
    			$invoiceid = $user_id . "_" . $id_point_package . "_" . $point_credit;
    			$description = "Order point package  #" . $invoiceid;
    			$charge = Stripe_Charge::create(array(
    					"amount" => $money_pay_credit,
    					"currency" => "JPY",
    					"description" => $description,
    					"source" => $token_ordercredit,
    			));
    
    			if ($charge->card->address_zip_check == "fail") {
    				$isSuccess = 0;
    				$message = $this->json_model->getMessage('buy_point_fail', $lang);
    			} else if ($charge->card->address_line1_check == "fail") {
    				$isSuccess = 0;
    				$message = $this->json_model->getMessage('buy_point_fail', $lang);
    			} else if ($charge->card->cvc_check == "fail") {
    				$isSuccess = 0;
    				$message = $this->json_model->getMessage('buy_point_fail', $lang);
    			} else {
    
    				$newUserPaymentMoneyGetPoint = $user_paymentmoney_getpoint + $point_credit;
    				$data['user_paymentmoney_getpoint'] = $newUserPaymentMoneyGetPoint;
    
    				/*=== Update luxyart_tb_user ===*/
    				$dataUpdate = array('user_paymoney_getpoint' => $newUserPaymentMoneyGetPoint);
    				$this->db->where(array('user_id' => $user_id));
    				$this->db->update('luxyart_tb_user', $dataUpdate);
    
    				/*=== Insert luxyart_tb_management_point ===*/
    				$dataAddManagePoint = array(
    						'user_id' => $user_id,
    						'point' => $point_credit,
    						'status_change_point' => 1, /* 1: tăng, 2: giảm */
    						'content' => 'Buy Package Point',
    						'date_add' => date('Y-m-d H:i:s'),
    				);
    				$this->db->insert('luxyart_tb_management_point', $dataAddManagePoint);
    
    				/*=== Add notice & Push notification ===*/
    
    				$addNotice = array();
    				$addNotice['type'] = "user-buy-point-package";
    				$addNotice["lang_id"] = $lang;
    				$addNotice['user_id'] = $user_id;
    				$addNotice['user_send_id'] = -1;
    				// Replace content
    				$addNotice['img_title'] = $userReceive->img_title;
    				$addNotice['email'] = $userSend->user_email;
    				$this->function_model->addNotice($addNotice);
    
    				/*============== End ==================*/
    
    				// $message = $charge;
    				$isSuccess = 1;
    				$message = $this->json_model->getMessage('buy_point_success', $lang);
    			}
    		} else if ($payment_method >= 2) {
    			$newUserPaymentMoneyGetPoint = $user_paymentmoney_getpoint + $point_credit;
    			$data['user_paymentmoney_getpoint'] = $newUserPaymentMoneyGetPoint;
    
    			/*=== Update luxyart_tb_user ===*/
    			$dataUpdate = array('user_paymoney_getpoint' => $newUserPaymentMoneyGetPoint);
    			$this->db->where(array('user_id' => $user_id));
    			$this->db->update('luxyart_tb_user', $dataUpdate);
    
    			/*=== Insert luxyart_tb_management_point ===*/
    			$dataAddManagePoint = array(
    					'user_id' => $user_id,
    					'point' => $point_credit,
    					'status_change_point' => 1, /* 1: tăng, 2: giảm */
    					'content' => 'Buy Package Point',
    					'date_add' => date('Y-m-d H:i:s'),
    			);
    			$this->db->insert('luxyart_tb_management_point', $dataAddManagePoint);
    
    			/*=== Add notice & Push notification ===*/
    
    			$addNotice = array();
    			$addNotice['type'] = "user-buy-point-package";
    			$addNotice["lang_id"] = $lang;
    			$addNotice['user_id'] = $user_id;
    			$addNotice['user_send_id'] = -1;
    			// Replace content
    			$addNotice['img_title'] = $userReceive->img_title;
    			$addNotice['email'] = $userSend->user_email;
    			$this->function_model->addNotice($addNotice);
    
    			/*============== End ==================*/
    
    			$isSuccess = 1;
    			$message = $this->json_model->getMessage('buy_point_success', $lang);
    		} else {
    			$isSuccess = 1;
    			$message = $this->json_model->getMessage('order_point_success', $lang);
    		}
    	} else {
    		$isSuccess = 0;
    		$message = $this->json_model->getMessage('buy_point_fail', $lang);
    	}
    
    	$list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $data);
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    	exit;
    }
    
    public function buy_point_test($item)
    {
    	$message = "";
    	$isSuccess = 0;
    	$data = array();
    
    	$user_id = $item['user_id'];
    	$id_point_package = $item['id_point_package'];
    	$payment_method = $item['payment_method'];
    	$token_ordercredit = $item['token_ordercredit'];
    	$payment_status = $item['payment_status'];
    	$lang = $item['lang'];
    	
    	if($payment_method == 5){
    		$apple_id = $item['apple_id'];
    		$money_pay_credit = $item['money_pay_credit'];
    		$local_pay = $item['local_pay'];
    		$point_credit = $item['point_credit'];
    		$package_name = $item['package_name'];
    	}
    	
    	//get for local_pay + apple_id
    	$this->db->select('luxyart_tb_list_credit.*');
    	$this->db->from('luxyart_tb_list_credit');
    	$this->db->where(array('local_pay'=>$local_pay,'apple_id'=>$apple_id,'option_pay'=>2));
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_credit = count($result);
    	
    	if($count_credit == 0){
    		//insert
    		$insertListCredit = array(
    			'name_credit' 		=> $package_name,
    			'point_credit' 		=> $point_credit,//15000
    			'money_pay_credit' 	=> $money_pay_credit,
    			'value_point_money' => round($money_pay_credit/$point_credit,2),//15
    			'status_credit' 	=> 1,
    			'option_pay'		=> 2,
    			'local_pay' 		=> $local_pay,
    			'apple_id' 			=> $apple_id
    		);
    		$this->db->insert('luxyart_tb_list_credit', $insertListCredit);
    		$id_point_package = $this->db->insert_id();
    	}
    	else{
    		
    		$rowListCredit = $rs->row();
    		$id_point_package = $rowListCredit->id_list;
    		
    		//update
    		$dataListCredit = array(
    			'name_credit' 		=> $package_name,
    			'point_credit' 		=> $point_credit,//15000
    			'money_pay_credit' 	=> $money_pay_credit,
    			'value_point_money' => round($money_pay_credit/$point_credit,2),//15
    			'option_pay'		=> 2,
    			'local_pay' 		=> $local_pay,
    			'apple_id' 			=> $apple_id
    		);
    		$this->db->where(array('id_list' => $id_point_package));
    		$this->db->update('luxyart_tb_list_credit', $dataListCredit);
    		
    	}
    
    	$data['payment_method'] = $payment_method;
    	$data['user_paymentmoney_getpoint'] = 0;
    
    	$dataAdd = array(
    			'user_id' => $user_id,
    			'id_list' => $id_point_package,
    			'payment_menthod' => $payment_method,
    			'token_ordercredit' => $token_ordercredit,
    			'payment_status' => $payment_status,
    			'date_order_credit' => date('Y-m-d H:i:s'),
    	);
    	$this->db->insert('luxyart_tb_user_order_credit', $dataAdd);
    	$id_order = $this->db->insert_id();
    
    	if ($id_order > 0) {
    		$this->db->select('luxyart_tb_list_credit.*');
    		$this->db->from('luxyart_tb_list_credit');
    		$this->db->where('id_list', $id_point_package);
    		$rs = $this->db->get();
    		$result = $rs->result();
    		$money_pay_credit = $result[0]->money_pay_credit;
    		$point_credit = $result[0]->point_credit;
    
    		$result = $this->function_model->getUser($user_id);
    		$user_paymentmoney_getpoint = $result->user_paymoney_getpoint;
    		if ($payment_method == 4) {
    			/*=== Stripe payment ===*/
    			require '/var/www/html/application/libraries/stripe/Stripe.php';
    
    			//Key Test
    			// Stripe::setApiKey("sk_test_coDx2kN3APcZL8EfX5AYPdge");
    			//Key Public
    			Stripe::setApiKey("pk_live_JhEIbvKZpdsTsAe520dzVTC9");
    
    			$invoiceid = $user_id . "_" . $id_point_package . "_" . $point_credit;
    			$description = "Order point package  #" . $invoiceid;
    			$charge = Stripe_Charge::create(array(
    					"amount" => $money_pay_credit,
    					"currency" => "JPY",
    					"description" => $description,
    					"source" => $token_ordercredit,
    			));
    
    			if ($charge->card->address_zip_check == "fail") {
    				$isSuccess = 0;
    				$message = $this->json_model->getMessage('buy_point_fail', $lang);
    			} else if ($charge->card->address_line1_check == "fail") {
    				$isSuccess = 0;
    				$message = $this->json_model->getMessage('buy_point_fail', $lang);
    			} else if ($charge->card->cvc_check == "fail") {
    				$isSuccess = 0;
    				$message = $this->json_model->getMessage('buy_point_fail', $lang);
    			} else {
    
    				$newUserPaymentMoneyGetPoint = $user_paymentmoney_getpoint + $point_credit;
    				$data['user_paymentmoney_getpoint'] = $newUserPaymentMoneyGetPoint;
    
    				/*=== Update luxyart_tb_user ===*/
    				$dataUpdate = array('user_paymoney_getpoint' => $newUserPaymentMoneyGetPoint);
    				$this->db->where(array('user_id' => $user_id));
    				$this->db->update('luxyart_tb_user', $dataUpdate);
    
    				/*=== Insert luxyart_tb_management_point ===*/
    				$dataAddManagePoint = array(
    						'user_id' => $user_id,
    						'point' => $point_credit,
    						'status_change_point' => 1, /* 1: tăng, 2: giảm */
    						'content' => 'Buy Package Point',
    						'date_add' => date('Y-m-d H:i:s'),
    				);
    				$this->db->insert('luxyart_tb_management_point', $dataAddManagePoint);
    
    				/*=== Add notice & Push notification ===*/
    
    				$addNotice = array();
    				$addNotice['type'] = "user-buy-point-package";
    				$addNotice["lang_id"] = $lang;
    				$addNotice['user_id'] = $user_id;
    				$addNotice['user_send_id'] = -1;
    				// Replace content
    				$addNotice['img_title'] = $userReceive->img_title;
    				$addNotice['email'] = $userSend->user_email;
    				$this->function_model->addNotice($addNotice);
    
    				/*============== End ==================*/
    
    				// $message = $charge;
    				$isSuccess = 1;
    				$message = $this->json_model->getMessage('buy_point_success', $lang);
    			}
    		} else if ($payment_method >= 2) {
    			$newUserPaymentMoneyGetPoint = $user_paymentmoney_getpoint + $point_credit;
    			$data['user_paymentmoney_getpoint'] = $newUserPaymentMoneyGetPoint;
    
    			/*=== Update luxyart_tb_user ===*/
    			$dataUpdate = array('user_paymoney_getpoint' => $newUserPaymentMoneyGetPoint);
    			$this->db->where(array('user_id' => $user_id));
    			$this->db->update('luxyart_tb_user', $dataUpdate);
    
    			/*=== Insert luxyart_tb_management_point ===*/
    			$dataAddManagePoint = array(
    					'user_id' => $user_id,
    					'point' => $point_credit,
    					'status_change_point' => 1, /* 1: tăng, 2: giảm */
    					'content' => 'Buy Package Point',
    					'date_add' => date('Y-m-d H:i:s'),
    			);
    			$this->db->insert('luxyart_tb_management_point', $dataAddManagePoint);
    
    			/*=== Add notice & Push notification ===*/
    
    			$addNotice = array();
    			$addNotice['type'] = "user-buy-point-package";
    			$addNotice["lang_id"] = $lang;
    			$addNotice['user_id'] = $user_id;
    			$addNotice['user_send_id'] = -1;
    			// Replace content
    			$addNotice['img_title'] = $userReceive->img_title;
    			$addNotice['email'] = $userSend->user_email;
    			$this->function_model->addNotice($addNotice);
    
    			/*============== End ==================*/
    
    			$isSuccess = 1;
    			$message = $this->json_model->getMessage('buy_point_success', $lang);
    		} else {
    			$isSuccess = 1;
    			$message = $this->json_model->getMessage('order_point_success', $lang);
    		}
    	} else {
    		$isSuccess = 0;
    		$message = $this->json_model->getMessage('buy_point_fail', $lang);
    	}
    
    	$list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $data);
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    	exit;
    }

    public function get_list_image_buy($item)
    {
        $message = "";
        $lang = $item['lang'];
        $current_page = $item['current_page'];
        $amount_page = $item['amount_page'];
        $user_id = $item['user_id'];

        $this->db->join('luxyart_tb_product_img_order_detail', 'luxyart_tb_product_img_order_detail.id_order_img = luxyart_tb_product_img_order.id_order_img', 'left');
        $this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img_order_detail.id_img = luxyart_tb_product_img.id_img', 'left');
        $this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_product_img_order_detail.user_owner', 'left');
        $this->db->join('luxyart_tb_product_img_size', 'luxyart_tb_product_img_order_detail.id_img_size = luxyart_tb_product_img_size.id_img_size', 'left');
        $this->db->join('luxyart_tb_manager_server', 'luxyart_tb_manager_server.id_server = luxyart_tb_product_img.id_server', 'left');

        $this->db->from('luxyart_tb_product_img_order');
        $this->db->select('luxyart_tb_product_img_order.id_order_img,luxyart_tb_product_img_order.user_buy_img,luxyart_tb_product_img_order.date_order_img,luxyart_tb_product_img_order.date_end_download,luxyart_tb_product_img_size.id_img_size,luxyart_tb_product_img_size.type_size,luxyart_tb_product_img_size.type_size_description,luxyart_tb_product_img.img_title,luxyart_tb_product_img.file_name_original_img,luxyart_tb_product_img.id_img,luxyart_tb_manager_server.server_path_upload,luxyart_tb_user.user_firstname,luxyart_tb_user.user_lastname,luxyart_tb_user.user_email,luxyart_tb_product_img_size.code_download, luxyart_tb_product_img.file_name_watermark_img');
        $this->db->where('luxyart_tb_product_img_size.status_img_size', 0);
        $this->db->where('luxyart_tb_product_img_order.user_buy_img', $user_id);
        $this->db->where('luxyart_tb_manager_server.server_status', 0);
        $this->db->order_by('luxyart_tb_product_img_order_detail.id_or_detail', 'desc');
        $this->db->limit($amount_page, $amount_page * ($current_page - 1));

        $rs = $this->db->get();

        $result = $rs->result();

        $list = array("isSuccess" => 1, "message" => "", "data" => $result);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);
        echo $json;
        exit;
    }

    public function get_list_history_payment($item)
    {
        $message = "";
        $lang = $item['lang'];
        $current_page = $item['current_page'];
        $amount_page = $item['amount_page'];
        $user_id = $item['user_id'];

        $this->db->from('luxyart_tb_management_point');
        $this->db->select('luxyart_tb_management_point.*');
        $this->db->where('luxyart_tb_management_point.user_id', $user_id);
        $this->db->order_by('luxyart_tb_management_point.date_add', 'desc');
        $this->db->limit($amount_page, $amount_page * ($current_page - 1));

        $rs = $this->db->get();

        $result = $rs->result();
        for ($i = 0; $i <= (count($result) - 1); $i++) {
            if (empty($result[$i]->content)) {
                $this->db->from('luxyart_tb_inbox_templates');
                $this->db->select('luxyart_tb_inbox_templates.*');
                $this->db->where('luxyart_tb_inbox_templates.inbox_templates_id', $result[$i]->inbox_id);
                $rs = $this->db->get();
                $item = $rs->row();
                $result[$i]->content = strip_tags($item->message);
            }
        }

        $list = array("isSuccess" => 1, "message" => "", "data" => $result);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);
        echo $json;
        exit;
    }

    public function reset_password($item)
    {
        $message = "";
        $lang = $item['lang'];
        $email = $item["email"];
        $isSuccess = 0;
        if ($this->json_model->checkMailUser($email)) {
            $bangkytuset = '!*%$#@~/?{}[]0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code_repeat_1 = 1;
            $code_repeat_2 = 10;
            $dodaichuoicode = 7;
            $password_new = substr(str_shuffle(str_repeat($bangkytuset, mt_rand($code_repeat_1, $code_repeat_2))), 1, $dodaichuoicode);
            $this->db->where('user_email', $email);

            $message = "";
            $lang = $item['lang'];
            $isSuccess = 0;
            if ($this->db->update('luxyart_tb_user', array('user_pass' => md5($password_new))) === true) {
                $username = explode('@', $email);
                $dataSendEmail = array();
                $dataSendEmail["name_email_code"] = "remind-password";
                $dataSendEmail["lang_id"] = $lang;
                $dataSendEmail["email_to"] = $email;
                $dataSendEmail["name"] = ucwords("User");
                $dataSendEmail["username"] = $email;
                $dataSendEmail["email"] = $username[0];
                $dataSendEmail["password"] = $password_new;
                $this->function_model->sendMailHTML($dataSendEmail);

                $isSuccess = 1;
                $message = $this->json_model->getMessage('please_check_your_email_to_reset_password', $lang);
            } else {
                $isSuccess = 0;
                $message = $this->json_model->getMessage('email_doesnt_exist', $lang);
            }
        } else {
            $isSuccess = 0;
            $message = $this->json_model->getMessage('email_doesnt_exist', $lang);
        }

        $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => array());
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);
        echo $json;
        exit;
    }

    public function add_image_to_user($item)
    {
        $message = "";
        $isSuccess = 0;
        $data = array();

        $user_id = $item['user_id'];
        $id_img = $item['id_image'];
        $user_owner = $item['user_owner'];
        $lang = $item['lang'];

        /*== Check image already get or not ==*/
        $this->db->from('luxyart_tb_user_get_img_sell');
        $this->db->select('luxyart_tb_user_get_img_sell.*');
        $this->db->where('luxyart_tb_user_get_img_sell.user_id', $user_id);
        $this->db->where('luxyart_tb_user_get_img_sell.id_img', $id_img);
        $rs = $this->db->get();
        $result = $rs->result();

        if (empty($result)) {

            /*== Add user get image sell ==*/
            $dataAdd = array(
                'user_id' => $user_id,
                'id_img' => $id_img,
                'user_owner' => $user_owner,
                'date_get_img' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('luxyart_tb_user_get_img_sell', $dataAdd);
            $id_get_image = $this->db->insert_id();

            if ($id_get_image > 0) {
                $isSuccess = 1;
                $message = $this->json_model->getMessage('get_image_to_your_album_success', $lang);
            } else {
                $isSuccess = 0;
                $message = $this->json_model->getMessage('get_image_to_your_album_fail', $lang);
            }
        } else {
            $this->db->where('user_id', $user_id);
            $this->db->where('id_img', $id_img);
            $delete = $this->db->delete('luxyart_tb_user_get_img_sell');
            if ($delete) {
                $isSuccess = 1;
                $message = $this->json_model->getMessage('remove_get_image_success', $lang);
            } else {
                $isSuccess = 0;
                $message = $this->json_model->getMessage('remove_this_image_from_your_album_fail', $lang);
            }
        }

        $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function upgrade_level_account($item)
    {
        $message = "";
        $isSuccess = 0;
        $data = array();
        $point_upgrade = 0;

        $user_id = $item['user_id'];
        $level = $item['level'];
        $lang = $item['lang'];
        $user_level = $this->function_model->getUserLevel($user_id);

        /*== Get point upgrade Account from Setting ==*/
        $this->db->from('luxyart_tb_settings');
        $this->db->select('luxyart_tb_settings.*');
        $rs = $this->db->get();
        $arr_point = $rs->result();

        // 1:Regular, 2:Creator, 3:Gold, 4:Platinum, 5:Enterprise
        if ($user_level > $level) {
            $message = $this->json_model->getMessage('you_cant_upgrade_below_level', $lang);
            $isSuccess = 0;
        } else if ($user_level == $level) {
            $message = $this->json_model->getMessage('you_already_upgrade_this_level', $lang);
            $isSuccess = 0;
        } else if ($level == 1) {
            $point_upgrade = 0;
        } else if ($level == 2) {
            $point_upgrade = 0;
        } else if ($level == 3) {
            $point_upgrade = $arr_point[9]->value;
        } else if ($level == 4) {
            $point_upgrade = $arr_point[10]->value;
        } else if ($level == 5) {
            $point_upgrade = $arr_point[11]->value;
        }

        $user = $this->function_model->getUser($user_id);
        $total_point = $user->user_point + $user->user_paymoney_getpoint;
        if ($message != '') {

            /* Don't do anything */

        } else if ($total_point >= $point_upgrade) {
            /*=== Process subtract point when add new competition ===*/
            $newUserPaymentMoneyGetPoint = $user->user_paymoney_getpoint - $point_upgrade;
            $newUserPoint = $user->user_point;

            if ($newUserPaymentMoneyGetPoint < 0) {
                $newUserPoint = $newUserPoint + $newUserPaymentMoneyGetPoint;
                $newUserPaymentMoneyGetPoint = 0;
            }

            /*=== Update level user luxyart_tb_user ===*/
            $point_after_upgrade = $user->user_point - $point_upgrade;
            $dataUpdate = array('user_level' => $level, 'user_point' => $newUserPoint, 'user_paymoney_getpoint' => $newUserPaymentMoneyGetPoint);
            $this->db->where(array('user_id' => $user_id));
            $this->db->update('luxyart_tb_user', $dataUpdate);

            /*=== Insert luxyart_tb_management_point ===*/
            if ($point_upgrade > 0) {
                $dataAddManagePoint = array(
                    'user_id' => $user_id,
                    'point' => $point_upgrade,
                    'status_change_point' => 2, /* 1: tăng, 2: giảm */
                    'content' => 'Update level account',
                    'date_add' => date('Y-m-d H:i:s'),
                );
                $this->db->insert('luxyart_tb_management_point', $dataAddManagePoint);
            }
            $message = $this->json_model->getMessage('upgrade_account_success', $lang);
            $isSuccess = 1;

            /*=== Add notice & Push notification ===*/

            $addNotice = array();
            $addNotice['type'] = "user-update-level";
            $addNotice["lang_id"] = $lang;
            $addNotice['user_id'] = $user_id;
            $addNotice['user_send_id'] = -1;
            // Replace content
            $addNotice['img_title'] = $userReceive->img_title;
            $addNotice['email'] = $userSend->user_email;
            $this->function_model->addNotice($addNotice);

            /*============== End ==================*/
        } else {
            $message = $this->json_model->getMessage('you_isnt_enough_point_to_upgrade_account', $lang);
            $isSuccess = 0;
        }

        $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => array());
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function get_list_visa_card($item)
    {
        $message = "";
        $isSuccess = 0;
        $data = array();

        $user_id = $item['user_id'];

        $this->db->from('luxyart_user_card');
        $this->db->where(array('user_id' => $user_id));
        $this->db->select('luxyart_user_card.*');

        $rs = $this->db->get();
        $result = $rs->result();

        if (!empty($result)) {
            $data = $result;
            $message = "Success";
            $isSuccess = 1;
        } else {
            $message = "";
            $isSuccess = 1;
        }

        $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function add_edit_visa_card($item)
    {
        $message = "";
        $isSuccess = 0;
        $data = array();

        $user_id = $item['user_id'];
        $card_id = $item['card_id'];
        $account_number = $item['account_number'];
        $account_ccv_value = $item['account_ccv_value'];
        $account_exp = $item['account_exp'];
        $account_type_card = $item['account_type_card'];

        if ($card_id == 0) {
            $this->db->from('luxyart_user_card');
            $this->db->where(array('user_id' => $user_id, 'account_number' => $account_number));
            $this->db->select('luxyart_tb_tranfer_money_user.*');
            $rs = $this->db->get();
            $result = $rs->result();

            if (!empty($result)) {
                /*=== Add new visa card ===*/
                $dataAddVisa = array(
                    'user_id' => $user_id,
                    'account_number' => $account_number,
                    'account_ccv_value' => $account_ccv_value,
                    'account_exp' => $account_exp,
                    'account_type_card' => $account_type_card,
                    'date_add_card' => date('Y-m-d H:i:s'),
                );
                $this->db->insert('luxyart_user_card', $dataAddVisa);

                $message = "Add visa card success";
                $isSuccess = 1;
            }else{
				$message = $this->getMessage('update_fail',$lang);
                $isSuccess = 0;
			}
        } else {

            /*=== Update visa card ===*/
            $dataEditVisa = array(
                'account_number' => $account_number,
                'account_ccv_value' => $account_ccv_value,
                'account_exp' => $account_exp,
                'account_type_card' => $account_type_card,
                'date_add_card' => date('Y-m-d H:i:s'),
            );

            $this->db->where(array('card_id' => $card_id));
            $this->db->update('luxyart_user_card', $dataEditVisa);

            $message = "Update visa card success";
            $isSuccess = 1;
        }

        $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => array());
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function get_list_package_upgrade_account($item)
    {
        $setting = $this->function_model->get_setting();
        $point_update_level_user_gold = $setting['point_update_level_user_gold'];
        $point_update_level_user_platinum = $setting['point_update_level_user_platinum'];
        $point_update_level_user_enterprise = $setting['point_update_level_user_enterprise'];
        $user_id = ($item['user_id'] != '') ? $item['user_id'] : 0;
        $user_level = $this->function_model->getUserLevel($user_id);

        $arr_role_name = array("素材検索", "購入", "コンペ閲覧", "コンペ参加", "コンペ開催", "クローズドコンペ閲覧", "クローズドコンペ参加", "クローズドコンペ開催", "写真販売");
        $data = array(
            array(
                'level_id' => 1,
                'level_name_en' => 'Regular user',
                'level_name' => '一般ユーザー',
                'point' => 0,
                'array_role' => array(
                    array('role_id' => 1, 'role_name' => $arr_role_name[0], 'is_can_use' => 1),
                    array('role_id' => 2, 'role_name' => $arr_role_name[1], 'is_can_use' => 1),
                    array('role_id' => 3, 'role_name' => $arr_role_name[2], 'is_can_use' => 1),
                    array('role_id' => 4, 'role_name' => $arr_role_name[3], 'is_can_use' => 0),
                    array('role_id' => 5, 'role_name' => $arr_role_name[4], 'is_can_use' => 1),
                    array('role_id' => 6, 'role_name' => $arr_role_name[5], 'is_can_use' => 0),
                    array('role_id' => 7, 'role_name' => $arr_role_name[6], 'is_can_use' => 0),
                    array('role_id' => 8, 'role_name' => $arr_role_name[7], 'is_can_use' => 0),
                    array('role_id' => 9, 'role_name' => $arr_role_name[8], 'is_can_use' => 0),
                )),
            array(
                'level_id' => 2,
                'level_name_en' => 'Creator',
                'level_name' => 'クリエイター',
                'point' => 0,
                'array_role' => array(
                    array('role_id' => 1, 'role_name' => $arr_role_name[0], 'is_can_use' => 1),
                    array('role_id' => 2, 'role_name' => $arr_role_name[1], 'is_can_use' => 1),
                    array('role_id' => 3, 'role_name' => $arr_role_name[2], 'is_can_use' => 1),
                    array('role_id' => 4, 'role_name' => $arr_role_name[3], 'is_can_use' => 1),
                    array('role_id' => 5, 'role_name' => $arr_role_name[4], 'is_can_use' => 1),
                    array('role_id' => 6, 'role_name' => $arr_role_name[5], 'is_can_use' => 1),
                    array('role_id' => 7, 'role_name' => $arr_role_name[6], 'is_can_use' => 0),
                    array('role_id' => 8, 'role_name' => $arr_role_name[7], 'is_can_use' => 0),
                    array('role_id' => 9, 'role_name' => $arr_role_name[8] . ' 12枚', 'is_can_use' => 1),
                )),
            array(
                'level_id' => 3,
                'level_name_en' => 'Platinum',
                'level_name' => 'プラチナ',
                'point' => $point_update_level_user_gold,
                'array_role' => array(
                    array('role_id' => 1, 'role_name' => $arr_role_name[0], 'is_can_use' => 1),
                    array('role_id' => 2, 'role_name' => $arr_role_name[1], 'is_can_use' => 1),
                    array('role_id' => 3, 'role_name' => $arr_role_name[2], 'is_can_use' => 1),
                    array('role_id' => 4, 'role_name' => $arr_role_name[3], 'is_can_use' => 1),
                    array('role_id' => 5, 'role_name' => $arr_role_name[4], 'is_can_use' => 1),
                    array('role_id' => 6, 'role_name' => $arr_role_name[5], 'is_can_use' => 1),
                    array('role_id' => 7, 'role_name' => $arr_role_name[6], 'is_can_use' => 1),
                    array('role_id' => 8, 'role_name' => $arr_role_name[7], 'is_can_use' => 0),
                    array('role_id' => 9, 'role_name' => $arr_role_name[8] . ' 50枚', 'is_can_use' => 1),
                )),
            array(
                'level_id' => 4,
                'level_name_en' => 'Diamond',
                'level_name' => 'ダイヤモンド',
                'point' => $point_update_level_user_platinum,
                'array_role' => array(
                    array('role_id' => 1, 'role_name' => $arr_role_name[0], 'is_can_use' => 1),
                    array('role_id' => 2, 'role_name' => $arr_role_name[1], 'is_can_use' => 1),
                    array('role_id' => 3, 'role_name' => $arr_role_name[2], 'is_can_use' => 1),
                    array('role_id' => 4, 'role_name' => $arr_role_name[3], 'is_can_use' => 1),
                    array('role_id' => 5, 'role_name' => $arr_role_name[4], 'is_can_use' => 1),
                    array('role_id' => 6, 'role_name' => $arr_role_name[5], 'is_can_use' => 1),
                    array('role_id' => 7, 'role_name' => $arr_role_name[6], 'is_can_use' => 1),
                    array('role_id' => 8, 'role_name' => $arr_role_name[7], 'is_can_use' => 0),
                    array('role_id' => 9, 'role_name' => $arr_role_name[8] . ' 250枚', 'is_can_use' => 1),
                )),
            array(
                'level_id' => 5,
                'level_name_en' => 'Enteprise',
                'level_name' => '法人',
                'point' => $point_update_level_user_enterprise,
                'array_role' => array(
                    array('role_id' => 1, 'role_name' => $arr_role_name[0], 'is_can_use' => 1),
                    array('role_id' => 2, 'role_name' => $arr_role_name[1], 'is_can_use' => 1),
                    array('role_id' => 3, 'role_name' => $arr_role_name[2], 'is_can_use' => 1),
                    array('role_id' => 4, 'role_name' => $arr_role_name[3], 'is_can_use' => 1),
                    array('role_id' => 5, 'role_name' => $arr_role_name[4], 'is_can_use' => 1),
                    array('role_id' => 6, 'role_name' => $arr_role_name[5], 'is_can_use' => 1),
                    array('role_id' => 7, 'role_name' => $arr_role_name[6], 'is_can_use' => 1),
                    array('role_id' => 8, 'role_name' => $arr_role_name[7], 'is_can_use' => 1),
                    array('role_id' => 9, 'role_name' => $arr_role_name[8] . ' 25枚', 'is_can_use' => 1),
                )),
        );

        if ($user_level <= 3) {
            unset($data[2]);
            unset($data[3]);
        }

        $list = array("isSuccess" => 1, "message" => "", "data" => array_values($data));
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function get_history_bank_tranfer($item)
    {
        $message = "";
        $data = array();

        $user_id = $item['user_id'];
        $current_page = $item['current_page'];
        $amount_page = $item['amount_page'];

        if ($current_page == "") {
            $current_page = 1;
        }
        if ($amount_page == "") {
            $amount_page = 1000000;
        }

        $this->db->from('luxyart_tb_tranfer_money_user');
        $this->db->where(array('user_id' => $user_id));
        $this->db->select('luxyart_tb_tranfer_money_user.*');
        $this->db->order_by('date_add_require', 'desc');
        $this->db->limit($amount_page, $amount_page * ($current_page - 1));

        $rs = $this->db->get();
        $result = $rs->result();

        if (!empty($result)) {
            $data = $result;
            $message = "Success";
            $isSuccess = 1;
        } else {
            $message = "";
            $isSuccess = 1;
        }

        $list = array("isSuccess" => 1, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function require_admin_to_tranfer($item)
    {
        $message = "";
        $isSuccess = 0;
        $data = array();

        $user_id = $item['user_id'];
        $lang = $item['lang'];
        $tranfer_point_require = $item['tranfer_point_require'];
        $user = $this->function_model->getUser($user_id);

        if ($tranfer_point_require < 10) {
            $message = $this->json_model->getMessage('minimum_point_tranfer_need_bigger_10', $lang);
            $isSuccess = 0;
        } else if ($user->user_point < $tranfer_point_require) {
            $message = $this->json_model->getMessage('your_point_get_inst_enough_to_tranfer', $lang);
            $isSuccess = 0;
        } else {
            $setting = $this->function_model->get_setting();
            $point_convert_money = $setting['point_convert_money'];
            $money_user_get = $point_convert_money * $tranfer_point_require;

            /*=== Insert luxyart_tb_tranfer_money_user ===*/
            $dataTranfer = array(
                'user_id' => $user_id,
                'tranfer_point_require' => $tranfer_point_require,
                'money_user_get' => $money_user_get,
                'tranfer_status' => 1, /* 1: Vừa gửi yêu cầu, 2: Đã chuyển */
                'date_add_require' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('luxyart_tb_tranfer_money_user', $dataTranfer);

            $newUserPoint = $user->user_point - $tranfer_point_require;
            $data['user_point'] = $newUserPoint;

            /*=== Update luxyart_tb_user ===*/
            $dataUpdate = array('user_point' => $newUserPoint);
            $this->db->where(array('user_id' => $user_id));
            $this->db->update('luxyart_tb_user', $dataUpdate);

            /*=== Insert luxyart_tb_management_point ===*/
            $dataAddManagePoint = array(
                'user_id' => $user_id,
                'point' => $tranfer_point_require,
                'status_change_point' => 2, /* 1: tăng, 2: giảm */
                'content' => 'Require admin transfer money',
                'date_add' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('luxyart_tb_management_point', $dataAddManagePoint);

            $message = $this->json_model->getMessage('require_admin_tranfer_success', $lang);
            $isSuccess = 1;
        }

        $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function report_image($item)
    {
        $message = "";
        $isSuccess = 0;
        $data = array();
        $user_id = $item['user_id'];
        $id_image = $item['id_image'];
        $content = $item['content'];

        $user = $this->function_model->getUser($user_id);

        $nameReport = $user->user_firstname . ' ' . $user->user_lastname;
        $dataInsert = array('img_id' => $id_image,
            'email' => $user->user_email,
            'name' => $nameReport,
            'type' => $content,
            'date_create' => date("Y-m-d H:i:s"),
            'status' => 0);
        $this->db->insert('luxyart_tb_report_image', $dataInsert);
        // echo $this->db->last_query();
        $message = $this->json_model->getMessage('report_success', $lang);
        $list = array("isSuccess" => 1, "message" => $message, "data" => array());
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function edit_bank_infomation($item)
    {
        $message = "";
        $isSuccess = 0;
        $data = array();

        $user_id = $item['user_id'];
        $bank_name = $item['bank_name'];
        $bank_branch_name = $item['bank_branch_name'];
        $bank_account = $item['bank_account'];
        $bank_account_name = $item['bank_account_name'];
        $bank_type_account = $item['bank_type_account'];

        $this->db->from('luxyart_tb_bank_infomation');
        $this->db->where(array('user_id' => $user_id));
        $this->db->select('luxyart_tb_bank_infomation.*');
        $rs = $this->db->get();
        $result = $rs->result();

        if (empty($result)) {
            /*=== Add new bank information ===*/
            $dataAddBank = array(
                'user_id' => $user_id,
                'bank_name' => $bank_name,
                'bank_branch_name' => $bank_branch_name,
                'bank_account' => $bank_account,
                'bank_account_name' => $bank_account_name,
                'bank_type_account' => $bank_type_account,
                'date_add_bank' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('luxyart_tb_bank_infomation', $dataAddBank);

            $message = $this->json_model->getMessage('update_bank_information_success', $lang);
            $isSuccess = 1;
        } else {

            /*=== Update bank information ===*/
            $dataAddBank = array(
                'bank_name' => $bank_name,
                'bank_branch_name' => $bank_branch_name,
                'bank_account' => $bank_account,
                'bank_type_account' => $bank_type_account,
                'bank_account_name' => $bank_account_name,
                'date_add_bank' => date('Y-m-d H:i:s'),
            );

            $this->db->where(array('user_id' => $user_id));
            $this->db->update('luxyart_tb_bank_infomation', $dataAddBank);

            $message = $this->json_model->getMessage('update_bank_information_success', $lang);
            $isSuccess = 1;
        }

        $list = array("isSuccess" => $isSuccess, "message" => $message, "data" => array());
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function get_list_setting($item)
    {
        $message = "";
        $data = array();

        $this->db->from('luxyart_tb_settings');
        $this->db->select('luxyart_tb_settings.*');

        $rs = $this->db->get();
        $result = $rs->result();

        if (!empty($result)) {
            for ($i = 0; $i <= (count($result) - 1); $i++) {
                $data[$result[$i]->name] = $result[$i]->value;
            }
            $message = "Success";
            $isSuccess = 1;
        } else {
            $message = "";
            $isSuccess = 1;
        }

        $list = array("isSuccess" => 1, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function get_list_history_buy_point($item)
    {
        $message = "";
        $data = array();

        $user_id = $item['user_id'];
        $current_page = $item['current_page'];
        $amount_page = $item['amount_page'];

        if ($current_page == "") {
            $current_page = 1;
        }
        if ($amount_page == "") {
            $amount_page = 1000000;
        }

        $this->db->from('luxyart_tb_user_order_credit as a');
        $this->db->join('luxyart_tb_list_credit as b', 'a.id_list = b.id_list', 'left');
        $this->db->where(array('a.user_id' => $user_id));
        $this->db->select('a.*, b.name_credit, b.point_credit, b.money_pay_credit');
        $this->db->order_by('a.date_order_credit', 'desc');
        $this->db->limit($amount_page, $amount_page * ($current_page - 1));

        $rs = $this->db->get();
        $data = $rs->result();

        $list = array("isSuccess" => 1, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function require_admin_check_status_buy_point($item)
    {
        $message = '';
        $id_ordercredit = $item['id_ordercredit'];
        $user_id = $item['user_id'];
        $dataUpdate = array(
            'notice_status' => 1,
        );

        $this->db->where(array('id_ordercredit' => $id_ordercredit, 'user_id' => $user_id));
        $this->db->update('luxyart_tb_user_order_credit', $dataUpdate);

        // $message = $this->json_model->getMessage('update_bank_information_success',$lang);
        $isSuccess = 1;
        $list = array("isSuccess" => 1, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function get_list_competition_apply($item)
    {
        $message = "";
        $data = array();
        $user_id = $item["user_id"];
        $current_page = $item['current_page'];
        $amount_page = $item['amount_page'];

        if ($current_page == "") {
            $current_page = 1;
        }

        if ($amount_page == "") {
            $amount_page = 1000000;
        }

        $this->db->join('luxyart_tb_competition_img_apply', 'luxyart_tb_competition_img_apply.id_competition = luxyart_tb_user_competition.id_competition', 'left');
        $this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_competition_img_apply.id_img', 'left');
        $this->db->from('luxyart_tb_user_competition');
        $this->db->select('luxyart_tb_user_competition.id_competition,luxyart_tb_user_competition.title_com,luxyart_tb_user_competition.date_end_com,luxyart_tb_competition_img_apply.result_img,luxyart_tb_product_img.file_name_watermark_img,luxyart_tb_product_img.id_img,luxyart_tb_product_img.img_title, luxyart_tb_product_img.id_server');
        $this->db->where(array('luxyart_tb_user_competition.status_com != ' => 4, 'luxyart_tb_competition_img_apply.user_id' => $user_id));
        $this->db->limit($amount_page, $amount_page * ($current_page - 1));
        $this->db->order_by('luxyart_tb_user_competition.id_competition', 'desc');

        $rs = $this->db->get();
        $result = $rs->result();

        $data = $result;

        $i = 0;
        foreach ($result as $_result) {
            $manager_server = $this->function_model->getManagerServer($_result->id_server);
            $data[$i]->file_name_watermark_img = $manager_server->server_path_upload . "/" . $_result->file_name_watermark_img;
            $i++;
        }

        $list = array("isSuccess" => 1, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
        exit;
    }

    public function testPushNotification($userIdReceive, $content)
    {

        $this->db->select('luxyart_tb_user.*');
        $this->db->from('luxyart_tb_user');
        $this->db->where('user_id', $userIdReceive);
        $rs2 = $this->db->get();
        $result = $rs2->result();
        $os_register = $result[0]->os_register;
        $device_token = $result[0]->device_token;
        if ($os_register == 1) /* ios */ {

        } else if ($os_register == 2) /* android */ {

        }

        $KEY_FIREBASE_SERVER = 'key=AAAAB7k6zuE:APA91bEoKEnmfHSHdavoCg5uye_kbkbF_7I2ZG2yx7bRxTCuwC83O5teYgrXFpyMhhHmdvvoZmFk0kzPwEAAnmKun3T5BAGae7u3VAIkeVe0esLv8P23OB-_qjD2YfwRU0JAwKaseKVU';

        $data_string = json_encode(array("notification" => array("title" => "", "body" => $content, "sound" => "default", "badge" => "1"), "to" => $device_token));
        $url = 'https://fcm.googleapis.com/fcm/send';
        $ch = curl_init($url);
        $result = file_get_contents('https://fcm.googleapis.com/fcm/send', null, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/json' . "\r\n"
                . 'Content-Length: ' . strlen($data_string) . "\r\n" . 'Authorization: ' . $KEY_FIREBASE_SERVER . "\r\n",
                'content' => $data_string,
            ),
        )));

        $data = json_decode($result, true);
        echo json_encode($data);
        exit;
    }

    public function get_list_competition_time_agree($item)
    {
        $message = "";
        $data = array();

        $this->db->from('luxyart_tb_competition_time_agree');
        $this->db->select('luxyart_tb_competition_time_agree.*');

        $rs = $this->db->get();
        $result = $rs->result();

        if (!empty($result)) {
            $data = $result;
            $message = "Success";
            $isSuccess = 1;
        } else {
            $message = "";
            $isSuccess = 1;
        }

        $list = array("isSuccess" => 1, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
    }

    public function count_new_notice($item)
    {
        $user_id = $item['user_id'];
        $this->db->select('luxyart_tb_user_message.id_message');
        $this->db->from('luxyart_tb_user_message');
        $this->db->where(array('user_id' => $user_id, 'ms_is_read' => 0));

        $rs = $this->db->get();
        $result = $rs->result();
        $data['count_new_notice'] = count($result);
        $list = array("isSuccess" => 1, "message" => '', "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
    }

    public function get_country($item)
    {
        $message = "";
        $data = array();

        $this->db->from('luxyart_tb_country');
        $this->db->select('luxyart_tb_country.*');

        $rs = $this->db->get();
        $result = $rs->result();

        if (!empty($result)) {
            $data = $result;
            $message = "Success";
            $isSuccess = 1;
        } else {
            $message = "";
            $isSuccess = 1;
        }

        $list = array("isSuccess" => 1, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
    }

    public function get_country_state($item)
    {
        $message = "";
        $data = array();
        $country_id = $item['country_id'];

        $this->db->from('luxyart_tb_country_state');
        $this->db->select('luxyart_tb_country_state.*');
        if ($country_id > 0) {
            $this->db->where(array('luxyart_tb_country_state.country_id' => $country_id));
        }
        $rs = $this->db->get();
        $result = $rs->result();

        if (!empty($result)) {
            $data = $result;
            $message = "Success";
            $isSuccess = 1;
        } else {
            $message = "";
            $isSuccess = 1;
        }

        $list = array("isSuccess" => 1, "message" => $message, "data" => $data);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
    }

    public function isGetToSell($img_id, $user_id)
    {
        $this->db->from('luxyart_tb_user_get_img_sell');
        $this->db->select('luxyart_tb_user_get_img_sell.*');
        $this->db->where('luxyart_tb_user_get_img_sell.user_id', $user_id);
        $this->db->where('luxyart_tb_user_get_img_sell.id_img', $img_id);
        $rs = $this->db->get();
        $row = $rs->row();
        // echo $this->db->last_query();
        if (!empty($row)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function compareColor($item)
    {
        $this->db->select('luxyart_tb_product_img.*');
        $this->db->from('luxyart_tb_product_img');
        $this->db->where(array('id_img' => $item['image_id']));
        $rs = $this->db->get();

        $row = $rs->row();
        $manager_server = $this->function_model->getManagerServer($row->id_server);
        $urlImage = $manager_server->server_path_upload . "/" . $row->file_name_original_img;
        $mainColor = $this->function_model->main_color_image($urlImage);
        $color = $this->function_model->get_compare_color('#' . strtolower($mainColor));

        $list = array("NewColor" => $color, "OldColor" => $row->id_color, "MainColor" => $mainColor, "URL" => $urlImage);
        $json = json_encode($list);
        $json = str_replace('\\/', '/', $json);

        echo $json;
    }
    
    public function list_manager_comment($item){
    
    	$dt = array();
    
    	$message = "";
    
    	$lang = $item['lang'];
    	$user_id = $item['user_id'];
    	$current_page = $item['current_page'];
    	$amount_page = $item['amount_page'];
    
    	if($current_page == ""){
    		$current_page = 1;
    	}
    	
    	if($amount_page == ""){
    		$amount_page = 1000000;
    	}
    	
    	$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
    	$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_product_img.user_id','left');
    	$this->db->from('luxyart_tb_comment');
    	$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_manager_server.server_path_upload');
    	$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_comment.status >=' => 0));
    	$this->db->order_by('luxyart_tb_comment.id_img', 'desc');
    	$this->db->group_by('luxyart_tb_comment.id_img');
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_comment = count($result);
    	
    	$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
    	$this->db->join('luxyart_tb_manager_server', 'luxyart_tb_product_img.id_server = luxyart_tb_manager_server.id_server','left');
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_product_img.user_id','left');
    	$this->db->from('luxyart_tb_comment');
    	$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_manager_server.server_path_upload,luxyart_tb_user.*');
    	$this->db->where(array('luxyart_tb_product_img.user_id' => $user_id, 'luxyart_tb_comment.status >=' => 0));
    	$this->db->order_by('luxyart_tb_comment.id_img', 'desc');
    	$this->db->group_by('luxyart_tb_comment.id_img');
    	$this->db->limit($amount_page, $amount_page * ($current_page - 1));
    	$rs = $this->db->get();
    	$result = $rs->result();
    	
    	$i = 0;
    	
    	foreach ($result as $_result) {

                $id_img = $_result->id_img;

                $id_server = $_result->id_server;

                $id_color = $_result->id_color;

                $manager_server = $this->function_model->getManagerServer($id_server);

                $product_color = $this->function_model->getProductColor($id_color);

                $product_img_size = $this->function_model->getProductImgSize($id_img);

                /*=== Check image expired ===*/
                $date_limit = $this->getExpiredImageDate($_result->date_active, $_result->option_sale);
                // echo '['.$date_limit.' - '.date('Y-m-d').']<br>';
                //if (($creator_user_id == $user_id) || (($creator_user_id != $user_id) && ($date_limit >= date('Y-m-d')))) {

                    $dt[$i]['id_img'] = $id_img;

                    $dt[$i]['user_id'] = $_result->user_id;

                    $dt[$i]['img_title'] = $_result->img_title;

                    $dt[$i]['file_name_original_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_original_img;

                    $dt[$i]['file_name_watermark_img'] = $manager_server->server_path_upload . "/" . $_result->file_name_watermark_img;

                    $dt[$i]['img_file_md5'] = $_result->img_file_md5;

                    $dt[$i]['img_width'] = $_result->img_width;

                    $dt[$i]['img_height'] = $_result->img_height;

                    $dt[$i]['img_type'] = $_result->img_type;

                    $dt[$i]['kind_img'] = $_result->user_id;

                    $dt[$i]['id_color'] = $_result->id_color;
                    
                    $dt[$i]['id_colors'] = $_result->id_colors;

                    if (!empty($product_color)) {
                        $dt[$i]['css_color_style'] = $product_color->css_color_style;
                    } else {
                        $dt[$i]['css_color_style'] = "";
                    }

                    $dt[$i]['id_server'] = $_result->id_server;

                    $dt[$i]['option_img_watermark'] = $_result->option_img_watermark;

                    $dt[$i]['option_sale'] = $_result->option_sale;

                    $dt[$i]['img_tags'] = $_result->img_tags;

                    $dt[$i]['date_add_img'] = $_result->date_add_img;

                    $dt[$i]['img_is_sold'] = $_result->img_is_sold;

                    $dt[$i]['img_is_join_compe'] = $_result->img_is_join_compe;

                    $dt[$i]['img_check_status'] = $_result->img_check_status;

                    $dt[$i]['date_update_img'] = $_result->date_update_img;

                    //$dt[$i]['img_category'] = $this->getImgCategory($id_img, $lang);

                    //is_bookmark

                    //$dt[$i]['is_bookmark'] = $this->isBookmark($id_img, $user_id);

                    //is_follow

                    //$dt[$i]['is_follow'] = $this->isFollow($creator_user_id, $user_id);
                    
                    $dt[$i]['display_name'] = $_result->display_name;
                    
                    if($_result->user_avatar == ""){
                    	$dt[$i]['avatar'] = SRC."/publics/avatar/prof-img.png";
                    }
                    else{
                    	$dt[$i]['avatar'] = SRC."/publics/avatar/".$_result->user_avatar;
                    }
                    
                    $count_all_comment = $this->manager_comment_model->get_list_comment_by_level($id_img,1,$user_id)->num_rows();
                    
                    $dt[$i]['count_all_comment'] = $count_all_comment;

                    $i++;
                }

                $isSuccess = 1;

            //}
    
    	if ($count_comment % $amount_page == 0) {
    
    		$data = array("countData" => $count_comment, "numPage" => $count_comment / $amount_page, "dt" => $dt);
    
    	} else {
    
    		$data = array("countData" => $count_comment, "numPage" => floor($count_comment / $amount_page + 1), "dt" => $dt);
    
    	}
    
    	if (!empty($dt)) {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	} else {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	}
    
    	$json = json_encode($list);
    
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    
    	exit;
    
    }
    
    public function list_manager_comment_by_image($item){
    
    	$dt = array();
    
    	$message = "";
    
    	$lang = $item['lang'];
    	$id_img = $item['id_img'];
    	$creator_user_id = $item['creator_user_id'];
    	$current_page = $item['current_page'];
    	$amount_page = $item['amount_page'];
    
    	if($current_page == ""){
    		$current_page = 1;
    	}
    	 
    	if($amount_page == ""){
    		$amount_page = 1000000;
    	}
    	
    	$this->db->select('luxyart_tb_product_img.*');
    	$this->db->from('luxyart_tb_product_img');
    	$this->db->where(array('id_img' => $id_img));
    	$rs = $this->db->get();
    	$row = $rs->row();
    	
    	$count_comment = 0;
    	
    	if($row->user_id == $creator_user_id){
    	
	    	$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
	    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
	    	$this->db->from('luxyart_tb_comment');
	    	$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
	    	$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.level' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
	    	$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
	    	$rs = $this->db->get();
	    	$result = $rs->result();
	    	$count_comment = count($result);
	    	 
	    	$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
	    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
	    	$this->db->from('luxyart_tb_comment');
	    	$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
	    	$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.level' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
	    	$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
	    	$this->db->limit($amount_page, $amount_page * ($current_page - 1));
	    	$rs = $this->db->get();
	    	$result = $rs->result();
	    	 
	    	$i = 0;
	    	 
	    	foreach ($result as $_result) {
	    		
	    		$dt[$i]['comment_id'] = $_result->comment_id;
	    		$dt[$i]['id_img'] = $_result->id_img;
	    		$dt[$i]['user_id'] = $_result->user_id;
	    		$dt[$i]['display_name'] = $_result->display_name;
	    		
	    		if($_result->user_avatar == ""){
	    			$dt[$i]['avatar'] = SRC."/publics/avatar/prof-img.png";
	    		}
	    		else{
	    			$dt[$i]['avatar'] = SRC."/publics/avatar/".$_result->user_avatar;
	    		}
	    		
	    		$dt[$i]['level'] = $_result->level;
	    		$dt[$i]['comment_id_parent'] = $_result->comment_id_parent;
	    		$dt[$i]['comment'] = $_result->comment;
	    		$dt[$i]['date_create'] = $_result->date_create;
	    		$dt[$i]['status'] = $_result->status;
	    		
	    		$dt[$i]['list_sub_comment'] = $this->getListSubComment($_result->comment_id, $_result->user_id, $lang);
	    
	    		$i++;
	    		$isSuccess = 1;
	    
	    	}
    	
    	}
    	else{
    		
    		$isSuccess = 0;
    		
    	}
    
    	if ($count_comment % $amount_page == 0) {
    
    		$data = array("countData" => $count_comment, "numPage" => $count_comment / $amount_page, "dt" => $dt);
    
    	} else {
    
    		$data = array("countData" => $count_comment, "numPage" => floor($count_comment / $amount_page + 1), "dt" => $dt);
    
    	}
    
    	if (!empty($dt)) {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	} else {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	}
    
    	$json = json_encode($list);
    
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    
    	exit;
    
    }
    
    public function list_comment_by_image($item){
    
    	$dt = array();
    
    	$message = "";
    
    	$lang = $item['lang'];
    	$id_img = $item['id_img'];
    	$user_id = $item['user_id'];
    	$current_page = $item['current_page'];
    	$amount_page = $item['amount_page'];
    
    	if($current_page == ""){
    		$current_page = 1;
    	}
    
    	if($amount_page == ""){
    		$amount_page = 1000000;
    	}
    	 
    	$this->db->select('luxyart_tb_product_img.*');
    	$this->db->from('luxyart_tb_product_img');
    	$this->db->where(array('id_img' => $id_img));
    	$rs = $this->db->get();
    	$row = $rs->row();
    	 
    	$count_comment = 0;
    	 
    	$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
    	$this->db->from('luxyart_tb_comment');
    	$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
    	if($user_id == ""){
    		$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.level' => 1, 'luxyart_tb_comment.status' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
    	}
    	else{
    		$this->db->where("`luxyart_tb_product_img`.`id_img` = '".$id_img."' AND `luxyart_tb_comment`.`level` = 1 AND (`luxyart_tb_comment`.`status` = 1 OR (`luxyart_tb_comment`.`status` = 0 AND `luxyart_tb_comment`.`user_id` = ".$user_id.")) AND `luxyart_tb_product_img`.`img_check_status` != 4");
    	}
    	$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_comment = count($result);
    	 
    	$this->db->join('luxyart_tb_product_img', 'luxyart_tb_product_img.id_img = luxyart_tb_comment.id_img','left');
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_comment.user_id','left');
    	$this->db->from('luxyart_tb_comment');
    	$this->db->select('luxyart_tb_comment.*,luxyart_tb_product_img.*,luxyart_tb_user.*');
    	if($user_id == ""){
    		$this->db->where(array('luxyart_tb_product_img.id_img' => $id_img, 'luxyart_tb_comment.level' => 1, 'luxyart_tb_comment.status' => 1, 'luxyart_tb_product_img.img_check_status != ' => 4));
    	}
    	else{
    		$this->db->where("`luxyart_tb_product_img`.`id_img` = '".$id_img."' AND `luxyart_tb_comment`.`level` = 1 AND (`luxyart_tb_comment`.`status` = 1 OR (`luxyart_tb_comment`.`status` = 0 AND `luxyart_tb_comment`.`user_id` = ".$user_id.")) AND `luxyart_tb_product_img`.`img_check_status` != 4");
    	}
    	$this->db->order_by('luxyart_tb_comment.comment_id', 'desc');
    	$this->db->limit($amount_page, $amount_page * ($current_page - 1));
    	$rs = $this->db->get();
    	$result = $rs->result();
    	 
    	$i = 0;
    	 
    	foreach ($result as $_result) {
    	
    		$dt[$i]['comment_id'] = $_result->comment_id;
    		$dt[$i]['id_img'] = $_result->id_img;
    		$dt[$i]['user_id'] = $_result->user_id;
    		$dt[$i]['display_name'] = $_result->display_name;
    		
    		if($_result->user_avatar == ""){
    			$dt[$i]['avatar'] = SRC."/publics/avatar/prof-img.png";
    		}
    		else{
    			$dt[$i]['avatar'] = SRC."/publics/avatar/".$_result->user_avatar;
    		}
    		
    		$dt[$i]['level'] = $_result->level;
    		$dt[$i]['comment_id_parent'] = $_result->comment_id_parent;
    		$dt[$i]['comment'] = $_result->comment;
    		$dt[$i]['date_create'] = $_result->date_create;
    		$dt[$i]['status'] = $_result->status;
    	
    		$dt[$i]['list_sub_comment'] = $this->getListSubComment($_result->comment_id, $user_id, $lang);
    	
    		$i++;
    		$isSuccess = 1;
    	
    	}
    
    	if ($count_comment % $amount_page == 0) {
    
    		$data = array("countData" => $count_comment, "numPage" => $count_comment / $amount_page, "dt" => $dt);
    
    	} else {
    
    		$data = array("countData" => $count_comment, "numPage" => floor($count_comment / $amount_page + 1), "dt" => $dt);
    
    	}
    
    	if (!empty($dt)) {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	} else {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	}
    
    	$json = json_encode($list);
    
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    
    	exit;
    
    }
    
    public function post_comment($item){
    	
    	$message = "";
    	$isSuccess = 0;
    	
    	$data = array();
    	$id_img = $item['id_img'];
    	$comment = $item['comment'];
    	$comment_id = $item['comment_id'];
    	$level = $item['level'];
    	$user_id = $item['user_id'];
    	
    	$detail_user = $this->function_model->getUser($user_id);
    	$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
    	
    	if($detail_user->user_id > 0 && $detail_product->id_img > 0){
    		
    		$arr_users = array();
    		
    		$user = $this->function_model->getUser($user_id);
    		
    		//$detail_product = $this->manager_image_model->get_detail_image($id_img)->row();
    		$arr_users[0]["user_id"] = $detail_product->user_id."$$$".$detail_product->user_email."$$$".$detail_product->display_name;
    		
    		if($comment_id > 0){
    		
    			$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
    			$arr_users[1]["user_id"] = $detail_comment->user_id."$$$".$detail_comment->user_email."$$$".$detail_comment->display_name;
    		
    			if($detail_comment->level == 2){
    		
    				$detail_comment_root = $this->manager_comment_model->get_detail_comment($detail_comment->comment_id_parent)->row();
    				$arr_users[2]["user_id"]  = $detail_comment_root->user_id."$$$".$detail_comment_root->user_email."$$$".$detail_comment_root->display_name;
    		
    			}
    		
    		}
    		
    		if($detail_comment->level == 2){
    			$dataInsertComment = array(
    					'id_img' 			=> $id_img,
    					'user_id' 			=> $user_id,
    					'level' 			=> $level,
    					'comment_id_parent' => $detail_comment_root->comment_id,
    					'comment' 			=> $comment,
    					'date_create' 		=> date('Y-m-d H:i:s'),
    					'status' 			=> 0,
    			);
    		}
    		else{
    			$dataInsertComment = array(
    					'id_img' 			=> $id_img,
    					'user_id' 			=> $user_id,
    					'level' 			=> $level,
    					'comment_id_parent' => $comment_id,
    					'comment' 			=> $comment,
    					'date_create' 		=> date('Y-m-d H:i:s'),
    					'status' 			=> 0,
    			);
    		}
    		$this->db->insert('luxyart_tb_comment', $dataInsertComment);
    		
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
    			$dataSendEmail["name_email_code"] 		= "post_comment";
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
    		
    			//thien***$this->function_model->sendMailHTML($dataSendEmail);
    		
    		}
    		
    		$isSuccess = 1;
    		
    	}
    	else{
    		
    		$isSuccess = 0;
    		
    	}
    	
    	$list = array("isSuccess" => $isSuccess, "message" => $message, "data" => array());
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    	exit;
    }
    
    public function set_status_comment($item){
    	 
    	$message = "";
    	$isSuccess = 0;
    	 
    	$data = array();
    	$comment_id = $item['comment_id'];
    	$status = $item['status'];
    	$user_id = $item['user_id'];
    	 
    	$detail_user = $this->function_model->getUser($user_id);
    	$detail_comment = $this->manager_comment_model->get_detail_comment($comment_id)->row();
    	 
    	if($detail_user->user_id > 0 && $detail_comment->comment_id > 0){
    		
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
    			if($status == 1){
    				$dataSendEmail["name_email_code"] 		= "active_comment";
    			}
    			else{
    				$dataSendEmail["name_email_code"] 		= "not_active_comment";
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
    			$dataSendEmail["email_post_comment"]	= $detail_user->user_email;
    			$dataSendEmail["name_post_comment"]		= $detail_user->display_name;
    			//$dataSendEmail["is_send_admin"]		= 1;
    			//$dataSendEmail["is_debug"]			= 1;
    		
    			//thien***$this->function_model->sendMailHTML($dataSendEmail);
    		
    		}
    		
    		$isSuccess = 1;
    		
    	}
    	else{
    
    		$isSuccess = 0;
    
    	}
    	
    	$this->db->where('comment_id', $comment_id);
    	$this->db->update('luxyart_tb_comment',array('status' => $status));
    	 
    	$list = array("isSuccess" => $isSuccess, "message" => $message, "data" => array());
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    	exit;
    }
    
    public function listinvited($item){
    
    	$dt = array();
    
    	$message = "";
    
    	$lang = $item['lang'];
    	$user_id = $item['user_id'];
    	$current_page = $item['current_page'];
    	$amount_page = $item['amount_page'];
    
    	if($current_page == ""){
    		$current_page = 1;
    	}
    	 
    	if($amount_page == ""){
    		$amount_page = 1000000;
    	}
    	 
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_member.user_id','left');
    	$this->db->from('luxyart_tb_member');
    	$this->db->select('luxyart_tb_member.member_id');
    	$this->db->where(array('luxyart_tb_member.user_id' => $user_id));
    	$this->db->order_by('luxyart_tb_member.member_id', 'desc');
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_member = count($result);
    	 
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_member.user_id','left');
    	$this->db->from('luxyart_tb_member');
    	$this->db->select('luxyart_tb_member.*,luxyart_tb_user.*');
    	$this->db->where(array('luxyart_tb_member.user_id' => $user_id));
    	$this->db->order_by('luxyart_tb_member.member_id', 'desc');
    	$this->db->limit($amount_page, $amount_page * ($current_page - 1));
    	$rs = $this->db->get();
    	$result = $rs->result();
    	 
    	$i = 0;
    	 
    	foreach ($result as $_result) {
    
    		$dt[$i]['member_id'] 					= $_result->member_id;
    		$dt[$i]['user_id'] 						= $_result->user_id;
    		
    		$dt[$i]['display_name'] 				= $_result->display_name;
    		$dt[$i]['user_email'] 					= $_result->user_email;
    		
    		if($_result->user_avatar != ""){
    			$dt[$i]['user_avatar'] 				= SRC."/publics/avatar/".$_result->user_avatar;
    		}
    		else{
    			$dt[$i]['user_avatar'] 				= SRC."/publics/avatar/prof-img.png";
    		}
    		
    		$dt[$i]['user_id_invited'] 				= $_result->user_id_invited;
    		$dt[$i]['email_invite'] 				= $_result->email_invite;
    		$dt[$i]['user_code_invite_person'] 		= $_result->user_code_invite_person;
    		$dt[$i]['count'] 						= $_result->count;
    		$dt[$i]['date_create'] 					= $_result->date_create;
    		$dt[$i]['date_invite'] 					= $_result->date_invite;
    		$dt[$i]['status'] 						= $_result->status;
    
    		$i++;
    	}
    
    	$isSuccess = 1;
    
    	if ($count_member % $amount_page == 0) {
    
    		$data = array("countData" => $count_member, "numPage" => $count_member / $amount_page, "dt" => $dt);
    
    	} else {
    
    		$data = array("countData" => $count_member, "numPage" => floor($count_member / $amount_page + 1), "dt" => $dt);
    
    	}
    
    	if (!empty($dt)) {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	} else {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	}
    
    	$json = json_encode($list);
    
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    
    	exit;
    
    }
    
    public function listmember($item){
    
    	$dt = array();
    
    	$message = "";
    
    	$lang = $item['lang'];
    	$user_id = $item['user_id'];
    	$current_page = $item['current_page'];
    	$amount_page = $item['amount_page'];
    
    	if($current_page == ""){
    		$current_page = 1;
    	}
    
    	if($amount_page == ""){
    		$amount_page = 1000000;
    	}
    
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_member.user_id','left');
    	$this->db->from('luxyart_tb_member');
    	$this->db->select('luxyart_tb_member.member_id');
    	$this->db->where(array('luxyart_tb_member.user_id' => $user_id,'luxyart_tb_member.status' => 2));
    	$this->db->order_by('luxyart_tb_member.member_id', 'desc');
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_member = count($result);
    
    	$this->db->join('luxyart_tb_user', 'luxyart_tb_user.user_id = luxyart_tb_member.user_id','left');
    	$this->db->from('luxyart_tb_member');
    	$this->db->select('luxyart_tb_member.*,luxyart_tb_user.*');
    	$this->db->where(array('luxyart_tb_member.user_id' => $user_id,'luxyart_tb_member.status' => 2));
    	$this->db->order_by('luxyart_tb_member.member_id', 'desc');
    	$this->db->limit($amount_page, $amount_page * ($current_page - 1));
    	$rs = $this->db->get();
    	$result = $rs->result();
    
    	$i = 0;
    
    	foreach ($result as $_result) {
    
    		$dt[$i]['member_id'] 					= $_result->member_id;
    		$dt[$i]['user_id'] 						= $_result->user_id;
    
    		$dt[$i]['display_name'] 				= $_result->display_name;
    		$dt[$i]['user_email'] 					= $_result->user_email;
    
    		if($_result->user_avatar != ""){
    			$dt[$i]['user_avatar'] 				= SRC."/publics/avatar/".$_result->user_avatar;
    		}
    		else{
    			$dt[$i]['user_avatar'] 				= SRC."/publics/avatar/prof-img.png";
    		}
    
    		$dt[$i]['user_id_invited'] 				= $_result->user_id_invited;
    		
    		$this->db->from('luxyart_tb_user');
	    	$this->db->select('*');
	    	$this->db->where(array('luxyart_tb_user.user_id' => $_result->user_id_invited));
	    	$rsUser = $this->db->get();
	    	$resultUser = $rsUser->row();
    		
    		$dt[$i]['email_invite'] 				= $_result->email_invite;
    		$dt[$i]['display_name_invite'] 			= $resultUser->display_name;
    		if($resultUser->user_avatar != ""){
    			$dt[$i]['user_avatar_invited'] 		= SRC."/publics/avatar/".$resultUser->user_avatar;
    		}
    		else{
    			$dt[$i]['user_avatar_invited'] 		= SRC."/publics/avatar/prof-img.png";
    		}
    		
    		$dt[$i]['user_code_invite_person'] 		= $_result->user_code_invite_person;
    		$dt[$i]['count'] 						= $_result->count;
    		$dt[$i]['date_create'] 					= $_result->date_create;
    		$dt[$i]['date_invite'] 					= $_result->date_invite;
    		$dt[$i]['status'] 						= $_result->status;
    
    		$i++;
    	}
    
    	$isSuccess = 1;
    
    	if ($count_member % $amount_page == 0) {
    
    		$data = array("countData" => $count_member, "numPage" => $count_member / $amount_page, "dt" => $dt);
    
    	} else {
    
    		$data = array("countData" => $count_member, "numPage" => floor($count_member / $amount_page + 1), "dt" => $dt);
    
    	}
    
    	if (!empty($dt)) {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	} else {
    
    		$list = array("isSuccess" => $isSuccess, "message" => "", "data" => $data);
    
    	}
    
    	$json = json_encode($list);
    
    	$json = str_replace('\\/', '/', $json);
    
    	echo $json;
    
    	exit;
    
    }
    
    public function check_email_add_member($item){
    	
    	$dt = array();
    	
    	$message = "";
    	
    	$email_check = $item['email'];
    	
    	$this->db->from('luxyart_tb_user');
    	$this->db->select('user_id');
    	$this->db->where(array('user_email'=>$email_check));
    	
    	$rsUser = $this->db->get();
    	$countUser = $rsUser->num_rows();
    	
    	$this->db->from('luxyart_tb_member');
    	$this->db->select('*');
    	$this->db->where(array('email_invite'=>$email_check));
    	
    	$rsMember = $this->db->get();
    	$countMember = $rsMember->num_rows();
    	
    	$isSuccess = 1;
    		
    	if($countUser == 1){//email ton tai roi khong duoc add
    		if($countMember == 1){
    			$resultMember = $rsMember->row();
    			$status = $resultMember->status;
    			if($status == 0 || $status == 1){
    				$isSuccess = 1;
    			}
    			else{
    				$isSuccess = 0;
    			}
    		}
    		else{
    			if($countMember == 0){
    				$isSuccess = 1;
    			}
    			else{
    				$isSuccess = 0;
    			}
    		}
    	}
    	else{
    		$isSuccess = 1;
    	}
    	
    	$list = array("isSuccess" => $isSuccess, "message" => "", "data" => "");
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    	echo $json;
    	exit;
    	
    }
    
    public function add_member($item){
    
    	$dt = array();
    
    	$message = "";
    
    	$lang = $item['lang'];
    	$user_id = $item['user_id'];
    	$list_email = $item['list_email'];
    	
    	$user = $this->function_model->getUser($user_id);
    	
    	$this->db->from('luxyart_tb_user');
    	$this->db->select('luxyart_tb_user.user_id');
    	$this->db->where(array('luxyart_tb_user.user_id' => $user_id,'luxyart_tb_user.user_status' => 1));
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_user = count($result);
    	
    	$i = 0;
    	
    	if($count_user == 1){
    		
    		foreach($list_email as $detail_email){
    			
    			$email = trim($detail_email["email"]);
    			
    			if($this->function_model->is_check_email_add_member($email)){
    				
    				$this->db->from('luxyart_tb_member');
    				$this->db->select('*');
    				$this->db->where(array('user_id'=>$user_id,'email_invite'=>$email));
    				
    				$rsMember = $this->db->get();
    				$countMember = $rsMember->num_rows();
    				
    				if($countMember == 0){//mời lần đầu tiên
    				
    					$dataInsertMember = array(
    							'user_id' 					=> $user_id,
    							'email_invite' 				=> $email,
    							'user_code_invite_person'	=> $user->user_macode,
    							'date_create' 				=> date('Y-m-d H:i:s'),
    							'count' 					=> 1,
    							'status'					=> 0
    					);
    					$this->db->insert('luxyart_tb_member', $dataInsertMember);
    					$member_id = $this->db->insert_id();
    				
    					$dataSendEmail = array();
    					$dataSendEmail["name_email_code"] 		= "add_member";
    					$dataSendEmail["lang_id"] 				= $this->lang_id;
    					$dataSendEmail["email_to"] 				= $email;
    					$dataSendEmail["name"] 					= ucwords($email);
    					$dataSendEmail["email"] 				= $email;
    					$dataSendEmail["email_invite"] 			= $user->user_email;
    					$dataSendEmail["name_invite"] 			= $user->display_name;
    					$dataSendEmail["link_invite"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
    					$dataSendEmail["link_accept"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
    					$dataSendEmail["link_not_accept"] 		= base_url()."not_accept_member/".$member_id."-".md5($user_id).".html";
    					//$dataSendEmail["is_debug"]			= 1;
    						
    					$this->function_model->sendMailHTML($dataSendEmail);
    				
    					$i++;
    				
    				}
    				else{
    				
    					$resultMember = $rsMember->row();
    					$member_id = $resultMember->member_id;
    					$status = $resultMember->status;
    				
    					if($status == 0 || $status == 1){//mời + từ chối => mời lại
    				
    						$this->db->where(array('user_id'=>$user_id,'email_invite'=>$email));
    						$this->db->set('status','0');
    						$this->db->set('count','count+1',FALSE);
    						$this->db->update('luxyart_tb_member');
    							
    						$dataSendEmail = array();
    						$dataSendEmail["name_email_code"] 		= "add_member";
    						$dataSendEmail["lang_id"] 				= $this->lang_id;
    						$dataSendEmail["email_to"] 				= $email;
    						$dataSendEmail["name"] 					= ucwords($email);
    						$dataSendEmail["email"] 				= $email;
    						$dataSendEmail["email_invite"] 			= $user->user_email;
    						$dataSendEmail["name_invite"] 			= $user->display_name;
    						$dataSendEmail["link_invite"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
    						$dataSendEmail["link_accept"] 			= base_url()."accept_member/".$member_id."-".md5($user_id).".html";
    						$dataSendEmail["link_not_accept"] 		= base_url()."not_accept_member/".$member_id."-".md5($user_id).".html";
    						//$dataSendEmail["is_debug"]			= 1;
    				
    						$this->function_model->sendMailHTML($dataSendEmail);
    							
    						$i++;
    							
    					}
    					else if($status == 2){//đồng ý => không mời
    						//khong xu ly
    					}
    				
    				}
    			
    			}
    			
    		}
    		
    	}
    	else{
    		
    		$isSuccess = 0;
    		
    	}
    	
    	if($i > 0){
    		$isSuccess = 1;
    	}
    	else{
    		$isSuccess = 0;
    	}
    	
    	$dt["count_update"] = $i;
    	
    	$list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);
    	
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    	echo $json;
    	
    	exit;
    
    }
    
    public function delete_member($item){
    
    	$dt = array();
    
    	$message = "";
    
    	$lang = $item['lang'];
    	$user_id = $item['user_id'];
    	$member_id = $item['member_id'];
    	 
    	$user = $this->function_model->getUser($user_id);
    	 
    	$this->db->from('luxyart_tb_user');
    	$this->db->select('luxyart_tb_user.user_id');
    	$this->db->where(array('luxyart_tb_user.user_id' => $user_id,'luxyart_tb_user.user_status' => 1));
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_user = count($result);
    	 
    	$i = 0;
    	 
    	if($count_user == 1){
    		
    		$this->db->from('luxyart_tb_member');
    		$this->db->select('*');
    		$this->db->where(array('user_id'=>$user_id,'member_id'=>$member_id));
    
    		$rsMember = $this->db->get();
    		$countMember = $rsMember->num_rows();
    		
    		if($countMember == 0){
    			$isSuccess = 0;
    		}
    		else{
    			
    			$resultMember = $rsMember->row();
    			$status = $resultMember->status;
    			
    			if($status != 2){
    				$isSuccess = 0;
    			}
    			else{
    				
    				$user_id_invited = $resultMember->user_id_invited;
    					
    				$this->db->from('luxyart_tb_user');
    				$this->db->select('*');
    				$this->db->where(array('user_id'=>$user_id_invited));
    				
    				$rsUserInvited = $this->db->get();
    				$resultUserInvited = $rsUserInvited->row();
    				
    				$dataSendEmail = array();
    				$dataSendEmail["name_email_code"] 		= "delete_member";
    				$dataSendEmail["lang_id"] 				= $this->lang_id;
    				$dataSendEmail["email_to"] 				= $resultUserInvited->user_email;
    				$dataSendEmail["name"] 					= ucwords($user->display_name);
    				$dataSendEmail["name_invite"] 			= ucwords($resultUserInvited->display_name);
    				$dataSendEmail["email"] 				= $resultUserInvited->user_email;
    				//$dataSendEmail["is_debug"]			= 1;
    				
    				$this->function_model->sendMailHTML($dataSendEmail);
    				
    				$this->db->where(array('member_id'=>$member_id));
    				$this->db->delete('luxyart_tb_member');
    				
    				$isSuccess = 1;
    				
    			}
    		}
    
    	}
    	else{
    
    		$isSuccess = 0;
    
    	}
    	 
    	$list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);
    	 
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    	echo $json;
    	 
    	exit;
    
    }
    
    public function is_agree_member($item){
    
    	$dt = array();
    
    	$message = "";
    
    	$lang = $item['lang'];
    	$user_id = $item['user_id'];
    	$member_id = $item['member_id'];
    	$status = $item['status'];
    
    	$user = $this->function_model->getUser($user_id);
    
    	$this->db->from('luxyart_tb_user');
    	$this->db->select('luxyart_tb_user.user_id');
    	$this->db->where(array('luxyart_tb_user.user_id' => $user_id,'luxyart_tb_user.user_status' => 1));
    	$rs = $this->db->get();
    	$result = $rs->result();
    	$count_user = count($result);
    
    	$i = 0;
    
    	if($count_user == 1){
    
    		$this->db->from('luxyart_tb_member');
    		$this->db->select('*');
    		$this->db->where(array('user_id'=>$user_id,'member_id'=>$member_id));
    
    		$rsMember = $this->db->get();
    		$countMember = $rsMember->num_rows();
    
    		if($countMember == 0){
    			$isSuccess = 0;
    		}
    		else{
    			 
    			$resultMember = $rsMember->row();
    			$member_status = $resultMember->status;
    			 
    			if($member_status != 0){
    				$isSuccess = 0;
    			}
    			else{
    				
    				if($status == 0){
    					
    					$this->db->from('luxyart_tb_user');
    					$this->db->select('*');
    					$this->db->where(array('user_id'=>$resultMember->user_id));
    						
    					$rsUserMember = $this->db->get();
    					$resultUserMember = $rsUserMember->row();
    						
    					$dataSendEmail = array();
    					$dataSendEmail["name_email_code"] 		= "not_accept_member";
    					$dataSendEmail["lang_id"] 				= $lang;
    					$dataSendEmail["email_to"] 				= $resultUserMember->user_email;
    					$dataSendEmail["name"] 					= ucwords($resultUserMember->display_name);
    					$dataSendEmail["email_invite"] 			= $resultMember->email_invite;
    					$dataSendEmail["link_invite"] 			= base_url()."mypage/listmember";
    					//$dataSendEmail["is_debug"]			= 1;
    					
    					$this->function_model->sendMailHTML($dataSendEmail);
    						
    					$this->db->set('status', 1);
    					if($user_id != ""){
    						$this->db->set('user_id_invited', $user_id);
    					}
    					$this->db->set('date_invite', date('Y-m-d H:i:s'));
    					$this->db->where('member_id', $member_id);
    					$this->db->update('luxyart_tb_member');
    					
    					$isSuccess = 1;
    					
    				}
    				else if($status == 1){
    					
    					$email_invite = $resultMember->email_invite;
    						
    					$this->db->from('luxyart_tb_user');
    					$this->db->select('*');
    					$this->db->where(array('user_email'=>$email_invite));
    					
    					$rsUserInvited = $this->db->get();
    					$resultUserInvited = $rsUserInvited->row();
    					$user_id_invited = $resultUserInvited->user_id;
    						
    					$this->db->where('member_id', $member_id);
    					$this->db->set('user_id_invited',$user_id_invited);
    					$this->db->set('date_invite',date("Y-m-d H:i:s"));
    					$this->db->set('status',2);
    					$this->db->update('luxyart_tb_member');
    						
    					$this->db->from('luxyart_tb_user');
    					$this->db->select('*');
    					$this->db->where(array('user_id'=>$resultMember->user_id));
    						
    					$rsUserSend = $this->db->get();
    					$resultUserSend = $rsUserSend->row();
    						
    					//send email
    					$dataSendEmail = array();
    					$dataSendEmail["name_email_code"] 		= "accept_member";
    					$dataSendEmail["lang_id"] 				= $lang;
    					$dataSendEmail["email_to"] 				= $resultUserSend->user_email;
    					$dataSendEmail["name"] 					= ucwords($resultUserSend->display_name);
    					$dataSendEmail["email_invite"] 			= $resultMember->email_invite;
    					$dataSendEmail["link_member"] 			= base_url()."mypage/listmember";
    					//$dataSendEmail["is_debug"]			= 1;
    						
    					$this->function_model->sendMailHTML($dataSendEmail);

    					$isSuccess = 1;
    					
    				}
    
    			}
    		}
    
    	}
    	else{
    
    		$isSuccess = 0;
    
    	}
    
    	$list = array("isSuccess" => $isSuccess, "message" => $message, "data" => $dt);
    
    	$json = json_encode($list);
    	$json = str_replace('\\/', '/', $json);
    	echo $json;
    
    	exit;
    
    }

    public function getExpiredImageDate($date_active, $option_sale)
    {

        if ($option_sale == 1) { //1d
            $date_limit = date('Y-m-d', strtotime('+1 day', strtotime($date_active)));
        } else if ($option_sale == 2) { //1w

            $date_limit = date('Y-m-d', strtotime('+1 week', strtotime($date_active)));

        } else if ($option_sale == 3) { //1m

            $date_limit = date('Y-m-d', strtotime('+1 month', strtotime($date_active)));

        } else {
            $date_limit = date('Y-m-d');
        }
        return $date_limit;
    }

}
