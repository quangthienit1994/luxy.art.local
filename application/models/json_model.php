<?php

class Json_model extends CI_Model {

  private $data = [];

	function __construct(){
    $this->data['isSuccess']  = false;
    $this->data['message']  = '';
    $this->data['data']  = [];
    $this->load->model('function_model');
	  parent::__construct();
	  
	  $dir = getcwd();
	  define("DIR", $dir."/publics/");
	  
 	}

  function hideFieldUser($result) {
    unset($result->user_pass);
    unset($result->user_connect_facebook);
    unset($result->user_connect_google);
    unset($result->user_connect_twitter);
    unset($result->user_connect_instagram);
    unset($result->user_id_code_invite);
    return $result;
  }

  function countImageUser($user_id) {
    $this->db->where('user_id = ', $user_id);
    $this->db->where('img_check_status = ', 1);
    $this->db->where('img_is_join_compe = ', 0);
    $this->db->where('img_is_sold = ', 0);
    $result = $this->db->get('luxyart_tb_product_img')->result();
    return count($result);
  }

  function followUser($user_id, $follow_user_id) {
    $this->db->where('follow_user_id = ', $user_id);
    $this->db->where('user_id = ', $follow_user_id);
    $result = $this->db->get('luxyart_tb_user_follow')->result();
    return count($result)>0?1:0;
  }

  // function countfollowUser($user_id) {
  //   $this->db->where('follow_user_id = ', $user_id);
  //   $result = $this->db->get('luxyart_tb_user_follow')->result();
  //   return count($result);
  // }

  function checkMailUser($email) {
    $this->db->where('user_email = ', $email);
    $result = $this->db->get('luxyart_tb_user')->result();
    return count($result)>0?1:0;
  }

  function getUser($id) {
    $this->db->where('user_id', $id);
    $result = $this->db->get('luxyart_tb_user')->row();
    return count($result)>0?$result:0;
  }

  function checkUserExist($user_id) {
    if(!$this->getUser($user_id)){
      $this->res(false, "User không tồn tại !");
      $this->res();
    }
  }

  function checkUserLevel($user_id, $level) {
    if($this->getUser($user_id)->user_level < $level){
      $this->res(false, "Bạn không có quyền thực hiện chức năng này !");
      $this->res();
    }
  }

  function checkMailUserExceptMe($email, $id) {
    $this->db->where('user_email = ', $email);
    $this->db->where('user_id != ', $id);
    $result = $this->db->get('luxyart_tb_user')->result();
    return count($result)>0?1:0;
  }

  function checkImageApplyExist($competition_id, $image_id, $user_id) {
    $this->db->where('id_competition', $competition_id);
    $this->db->where('id_img', $image_id);
    $this->db->where('user_id', $user_id);
    $result = $this->db->get('luxyart_tb_competition_img_apply')->result();
    return count($result)>0?1:0;
  }

  function updateUrlImage($value) {
    if(isset($value->user_avatar) && $value->user_avatar!=null){
      $value->user_avatar = base_url('publics/avatar/'.$value->user_avatar);
    }

    if(isset($value->user_banner) && $value->user_banner!=null){
      $value->user_banner = base_url('publics/banner/'.$value->user_banner);
    }

    /*=== Tan comment ===*/
    // if(isset($value->file_name_original_img) && $value->file_name_original_img!=null){
    //   $value->file_name_original_img = base_url('publics/product_img/'.$value->file_name_original_img);
    // }
    //
    // if(isset($value->file_name_watermark_img) && $value->file_name_watermark_img!=null){
    //   $value->file_name_watermark_img = base_url('publics/product_img/'.$value->file_name_watermark_img);
    // }

    $this->db->where('id_server',$value->id_server);
    $getServer = $this->db->get('luxyart_tb_manager_server')->row();
    if($value->id_server!=null && count($getServer)>0) {
      if(isset($value->file_name_original_img) && $value->file_name_original_img!=null){
        $value->file_name_original_img = $getServer->server_path_upload.'/'.$value->file_name_original_img;
      }

      if(isset($value->file_name_watermark_img) && $value->file_name_watermark_img!=null){
        $value->file_name_watermark_img = $getServer->server_path_upload.'/'.$value->file_name_watermark_img;
      }
    }

    if(isset($value->photo_des_com) && $value->photo_des_com!=null){
      $value->photo_des_com = base_url('publics/competition_img/'.$value->photo_des_com);
    }

    if(isset($value->icon_com) && $value->icon_com!=null){
      $value->icon_com = base_url('publics/competition_img/'.$value->icon_com);
    }
    return $value;
  }

 	function login($param){
    $email = $param['email'];
    $password = md5($param['password']);
    $lang = $param['lang'];

    if(!$this->checkMailUser($email)) {
      $this->res(false, $this->getMessage('email_doesnt_exist',$lang));
      $this->res();
    }


    $this->db->from('luxyart_tb_user');
    $this->db->where('luxyart_tb_user.user_email = ', $email);
    $this->db->where('luxyart_tb_user.user_pass = ', $password);
    $this->db->select('luxyart_tb_user.*');
    $result = $this->db->get()->row();

    // print_r($result);
    if(count($result) > 0) {
      if ($result->is_active == 1){
          $item['os_register']  = $param['os_register'];
          $item['device_token'] = $param['device_token'];
          $this->db->where(array('user_email' => $email));
          $this->db->update('luxyart_tb_user', $item);

          $result = $this->hideFieldUser($result);
          $result->count_image = $this->countImageUser($result->user_id);
          $result = $this->updateUrlImage($result);

          /*=== Get Bank infomation ===*/
          $this->db->from('luxyart_tb_bank_infomation');
          $this->db->where('luxyart_tb_bank_infomation.user_id = ', $result->user_id);
          $this->db->select('luxyart_tb_bank_infomation.bank_id,luxyart_tb_bank_infomation.bank_name,luxyart_tb_bank_infomation.bank_branch_name,luxyart_tb_bank_infomation.bank_account,luxyart_tb_bank_infomation.bank_account_name,luxyart_tb_bank_infomation.bank_type_account');
          $bankInfo = $this->db->get()->row();
          if (count($bankInfo) > 0){
            $result->bank_id = $bankInfo->bank_id;
            $result->bank_name = $bankInfo->bank_name;
            $result->bank_branch_name = $bankInfo->bank_branch_name;
            $result->bank_account = $bankInfo->bank_account;
            $result->bank_account_name = $bankInfo->bank_account_name;
            $result->bank_type_account = $bankInfo->bank_type_account;
          }else{
            $result->bank_id = 0;
            $result->bank_name = '';
            $result->bank_branch_name = '';
            $result->bank_account = '';
            $result->bank_account_name = '';
            $result->bank_type_account = '0';
          }

          $this->res(true, $this->getMessage('login_success',$lang), $result);
      }else{
          $this->res(false, $this->getMessage('please_active_your_account',$lang));
      }
    }else{
      $this->res(false, $this->getMessage('password_is_wrong',$lang));
    }

    $this->res();
 	}

  function login_facebook($param){
    $email = $param['email'];
    $facebook_id = $param['facebook_id'];
    $lang = $param['lang'];

    if(!$this->checkMailUser($email)) {
      // Create codeid invite user
      $bangkytuset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $code_repeat_1 = 1;
      $code_repeat_2 = 10;
      $dodaichuoicode = 15;
      $user_id_code_invite = substr(str_shuffle(str_repeat($bangkytuset, mt_rand($code_repeat_1,$code_repeat_2))),1,$dodaichuoicode);

      $itemR = array(
        'user_email'              =>  $email,
        'user_connect_facebook'   =>  $facebook_id,
        'user_firstname'          =>  $param['firstname'],
        'user_lastname'           =>  $param['lastname'],
        'user_avatar'             =>  $param['avatar'],
        'os_register'             =>  $param['os_register'],
        'device_token'            =>  $param['device_token'],
        'country_id'              => 1,
        'user_level'              => 1,
        'user_status'             => 0,
        'notice_luxyart'          => 1,
        'user_id_code_invite'=> $user_id_code_invite,
        'user_dateregister'       =>  date("Y-m-d H:i:sa")
      );

      $a = $this->db->insert('luxyart_tb_user', $itemR);
    }

    $this->db->flush_cache();
    $this->db->where('user_email = ', $email);
    $this->db->where('user_connect_facebook = ', $facebook_id);
    $result = $this->db->get('luxyart_tb_user')->row();


    if(count($result) > 0) {
      $item['os_register']  = $param['os_register'];
      $item['device_token'] = $param['device_token'];
      $this->db->update('luxyart_tb_user', $item);

      $result = $this->hideFieldUser($result);
      $result->count_image = $this->countImageUser($result->user_id);
      // $result = $this->updateUrlImage($result);

      /*=== Get Bank infomation ===*/
      $this->db->from('luxyart_tb_bank_infomation');
      $this->db->where('luxyart_tb_bank_infomation.user_id = ', $result->user_id);
      $this->db->select('luxyart_tb_bank_infomation.bank_id,luxyart_tb_bank_infomation.bank_name,luxyart_tb_bank_infomation.bank_branch_name,luxyart_tb_bank_infomation.bank_account,luxyart_tb_bank_infomation.bank_account_name,luxyart_tb_bank_infomation.bank_type_account');
      $bankInfo = $this->db->get()->row();
      if (count($bankInfo) > 0){
        $result->bank_id = $bankInfo->bank_id;
        $result->bank_name = $bankInfo->bank_name;
        $result->bank_branch_name = $bankInfo->bank_branch_name;
        $result->bank_account = $bankInfo->bank_account;
        $result->bank_account_name = $bankInfo->bank_account_name;
        $result->bank_type_account = $bankInfo->bank_type_account;
      }else{
        $result->bank_id = 0;
        $result->bank_name = '';
        $result->bank_branch_name = '';
        $result->bank_account = '';
        $result->bank_account_name = '';
        $result->bank_type_account = '0';
      }

      $this->res(true, $this->getMessage('login_success',$lang), $result);
    }else{
      $this->res(false, $this->getMessage('login_fail',$lang));
    }

    $this->res();
  }

  function register_email($param){
    $email        = $param['email'];
    $os_register  = $param['os_register'];
    $password     = md5($param['password']);
    $lang        = $param['$lang'];
    $display_name           = $param['display_name'];

    if($this->checkMailUser($email)) {
      $this->res(false, $this->getMessage('email_exist_in_system',$lang));
    }else{
      $this->db->flush_cache();
      // Create codeid invite user
      $bangkytuset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $code_repeat_1 = 1;
      $code_repeat_2 = 10;
      $dodaichuoicode = 15;
      $user_id_code_invite = substr(str_shuffle(str_repeat($bangkytuset, mt_rand($code_repeat_1,$code_repeat_2))),1,$dodaichuoicode);
      
      if($os_register == 1){
      	$user_level = 2;
      }
      else{
      	$user_level = 1;
      }
      
      $item = array(
          'user_email'    =>  $email,
          'user_pass'     =>  $password,
          'display_name'  =>  $display_name,
          'country_id'     => 1,
          'user_level'     => $user_level,
          'user_status'    => 0,
          'notice_luxyart' => 1,
          'user_id_code_invite'=> $user_id_code_invite,
          'user_dateregister'     => date("Y-m-d H:i:s", time()),
          'os_register'   =>  $os_register
      );
      
      if($user_level == 2){
      	$item["count_post_img"] = 12;
      }

      $result = $this->db->insert('luxyart_tb_user', $item);

      if($result) {
        $dataSendEmail = array();
        $dataSendEmail["name_email_code"]     = "register";
        $dataSendEmail["lang_id"]             = 1;
        $dataSendEmail["email_to"]            = $email;
        $dataSendEmail["name"]                = ucwords("New User");
        $dataSendEmail["link"]                = base_url()."active-email/".$user_id_code_invite;
        $dataSendEmail["username"]            = $email;
        $dataSendEmail["email"]               = $email;
        $dataSendEmail["password"]            = $param['password'];
        $this->function_model->sendMailHTML($dataSendEmail);
        $this->res(true, $this->getMessage('register_success',$lang));
      }else{
        $this->res(false, $this->getMessage('register_fail',$lang));
      }
    }

    $this->res();
  }

  function get_detail_user($param){
    $user_id = $param['user_id'];
    $creator_user_id = $param['creator_user_id'];
    $lang = $param['lang'];

    $this->checkUserExist($user_id);
    $this->checkUserExist($creator_user_id);

    $this->db->from('luxyart_tb_user');
    $this->db->where('luxyart_tb_user.user_id = ', $creator_user_id);
    $this->db->select('luxyart_tb_user.*');
    $result                 = $this->db->get()->row();
    $result                 = $this->hideFieldUser($result);
    $result->count_image    = $this->countImageUser($result->user_id);
    $result->is_follow  = $this->followUser($user_id, $creator_user_id);
    $result->count_follow = $this->countFollow($result->user_id);
    $result = $this->updateUrlImage($result);

    /*=== Get Bank infomation ===*/
    $this->db->from('luxyart_tb_bank_infomation');
    $this->db->where('luxyart_tb_bank_infomation.user_id = ', $result->user_id);
    $this->db->select('luxyart_tb_bank_infomation.bank_id,luxyart_tb_bank_infomation.bank_name,luxyart_tb_bank_infomation.bank_branch_name,luxyart_tb_bank_infomation.bank_account,luxyart_tb_bank_infomation.bank_account_name,luxyart_tb_bank_infomation.bank_type_account');
    $bankInfo = $this->db->get()->row();
    if (count($bankInfo) > 0){
      $result->bank_id = $bankInfo->bank_id;
      $result->bank_name = $bankInfo->bank_name;
      $result->bank_branch_name = $bankInfo->bank_branch_name;
      $result->bank_account = $bankInfo->bank_account;
      $result->bank_account_name = $bankInfo->bank_account_name;
      $result->bank_type_account = $bankInfo->bank_type_account;
    }else{
      $result->bank_id = 0;
      $result->bank_name = '';
      $result->bank_branch_name = '';
      $result->bank_account = '';
      $result->bank_account_name = '';
      $result->bank_type_account = '0';
    }

    if(count($result)) {
      $this->res(true, "", $result);
    }else{
      $this->res(false, $this->getMessage('user_not_exist',$lang));
    }

    $this->res();
  }

  function update_avatar_banner_user($param){
    $user_id = $param['user_id'];
    $image_base64 = $param['image_base64'];
    $type_upload = $param['type_upload'];
    $lang = $param['lang'];
    if($type_upload == '') $type_upload = "image";

    $this->checkUserExist($user_id);

    $filename = $user_id."_".$type_upload."_".time().".jpg";

    if($image_base64 == '') {
      $this->res(false, "File image base64 null !");
    }else{
      $url_image = $this->base64_to_image($image_base64, $filename, "publics/".$type_upload);
      $res = base_url().$url_image;
      if($type_upload == "avatar"){
        $item = array('user_avatar'=>$filename);
      }else{
        $item = array('user_banner'=>$filename);
      }
      $aaaaaa['url_image'] = $res;

      $this->db->where('user_id', $user_id);
      $result = $this->db->update('luxyart_tb_user', $item);

      if($result) {
        $this->res(true, $this->getMessage('update_success',$lang), $aaaaaa);
      }else{
        $this->res(false, $this->getMessage('update_fail',$lang));
      }
    }

    $this->res();
  }

  function update_user($param){
    $user_id            = $param['user_id'];
    $firstname          = $param['firstname'];
    $lastname           = $param['lastname'];
    $display_name           = $param['display_name'];
    $address            = $param['address'];
    $user_introduction  = $param['user_introduction'];
    $email              = $param['email'];
    $user_zipcode       = $param['user_zipcode'];
    $country_id       = $param['country_id'];
    $state_id       = $param['state_id'];
    $cityname       = $param['cityname'];
    $user_address_building       = $param['user_address_building'];
    $lang       = $param['lang'];

    $this->checkUserExist($user_id);

    if($this->checkMailUserExceptMe($email, $user_id)) {
      $this->res(false, $this->getMessage('email_exist_in_system',$lang));
      $this->res();
    }

    $item = array(
        'user_firstname'      =>  $firstname,
        'user_lastname'       =>  $lastname,
        'display_name'       =>  $display_name,
        'user_address'        =>  $address,
        'user_introduction'   =>  $user_introduction,
        'user_email'          =>  $email,
        'user_zipcode'        =>  $user_zipcode,
        'country_id'          =>  $country_id,
        'state_id'            =>  $state_id,
        'user_address_building' =>  $user_address_building,
        'cityname' => $cityname
    );

    $this->db->where('user_id', $user_id);
    $result = $this->db->update('luxyart_tb_user', $item);

    if($result) {
      $this->res(true, $this->getMessage('update_success',$lang));
    }else{
      $this->res(false, $this->getMessage('update_fail',$lang));
    }

    $this->res();
  }

  function change_password($param){
    $user_id        = $param['user_id'];
    $password_old   = md5($param['password_old']);
    $password_new   = md5($param['password_new']);
    $lang = $param['lang'];

    $this->db->where('user_id = ', $user_id);
    $this->db->where('user_pass = ', $password_old);
    $result = $this->db->get('luxyart_tb_user')->row();

    if(count($result)>0) {
      $item = ['user_pass'=>$password_new];

      $this->db->where('user_id', $user_id);
      $update = $this->db->update('luxyart_tb_user', $item);

      if($update) {
        $this->res(true, $this->getMessage('update_success',$lang));
      }else{
        $this->res(false, $this->getMessage('update_fail',$lang));
      }
    }else{
      $this->res(false, $this->getMessage('userid_or_password_is_wrong',$lang));
    }

    $this->res();
  }

  function list_user($param){
    $user_id = $param['user_id'];
    $current_page = $param['current_page'];
    $amount_page  = $param['amount_page'];

    // $this->db->from('luxyart_tb_user as a');
		// $this->db->select('a.*');
		// $this->db->where(array('a.user_status' => 1));
    $this->db->join('luxyart_tb_user_pickup as b','a.user_id = b.user_id','left');
    $this->db->from('luxyart_tb_user as a');
		$this->db->select('a.*');
		$this->db->where(array('a.user_status' => 1));

		$rs = $this->db->get();
		$result = $rs->result();

    // $result       = $this->db->get('luxyart_tb_user')->result();

    $countData    = count($result);
    $numPage      = ceil($countData/$amount_page);
    $begin        = ($current_page-1)*$amount_page;

    $this->db->flush_cache();
    // $result2 = $this->db->get('luxyart_tb_user',$amount_page,$begin)->result();
    $this->db->join('luxyart_tb_user_pickup as b','a.user_id = b.user_id','left');
    $this->db->from('luxyart_tb_user as a');
		$this->db->select('a.*,b.position');
		$this->db->where(array('a.user_status' => 1));
    $this->db->order_by('b.position', 'desc');
		$this->db->limit($amount_page, $amount_page*($current_page-1));

		$rs2 = $this->db->get();
		$result2 = $rs2->result();

    $data = [];
    foreach ($result2 as $key => $value) {
      $item               = $this->hideFieldUser($value);
      $item->count_image  = $this->countImageUser($item->user_id);
      $item->is_follow    = $this->followUser($user_id, $item->user_id);
      $item->count_follow = $this->countFollow($item->user_id);
      $item = $this->updateUrlImage($item);
      array_push($data, $item);
    }

    $res['countData'] = $countData;
    $res['numPage']   = $numPage;
    $res['dt']        = $data;

    $this->res(true, "", $res);

    $this->res();
  }

  function list_note($param){
    $user_id        = $param['user_id'];
    $current_page   = $param['current_page'];
    $amount_page    = $param['amount_page'];

    $this->checkUserExist($user_id);

    $this->db->where("user_id = ", $user_id);
    $result = $this->db->get('luxyart_tb_user_message')->result();

    $countData  = count($result);
    $numPage    = ceil($countData/$amount_page);
    $begin      = ($current_page-1)*$amount_page;

    $this->db->flush_cache();
    // $this->db->from('luxyart_tb_user_message as m');
    // $this->db->join('luxyart_tb_user as u', 'm.user_send_id = u.user_id');
    // $this->db->where("m.user_id = ", $user_id);
    // SELECT * FROM `luxyart_tb_user_message` as a, `luxyart_tb_user` as b  WHERE a.user_id = 81 AND ((a.user_send_id = -1) OR (a.user_send_id > -1 AND a.user_send_id = b.user_id)) GROUP BY a.id_message
    $this->db->from('luxyart_tb_user_message as a, luxyart_tb_user as b');
    $this->db->where('a.user_id = '.$user_id.' AND ((a.user_send_id = -1 AND a.user_id = b.user_id) OR (a.user_send_id > -1 AND a.user_send_id = b.user_id))');
    $this->db->group_by('a.id_message');
    $this->db->order_by('a.date_message', 'desc');
    $this->db->limit($amount_page,$begin);
    $data = $this->db->get()->result();
    $data2 = [];
    foreach ($data as $key => $value) {
      unset($value->user_id);
      $value = $this->hideFieldUser($value);
      $value = $this->updateUrlImage($value);
      $value->ms_content = strip_tags($value->ms_content);
      $value->ms_content = str_replace('\n','',$value->ms_content);
      $value->ms_content = str_replace('\t','',$value->ms_content);
      $value->ms_content = str_replace('&nbsp;',' ',$value->ms_content);
      array_push($data2, $value);
    }

    $res['countData'] = $countData;
    $res['numPage']   = $numPage;
    $res['dt']        = $data2;

    $this->res(true, "", $res);

    $this->res();
  }

  function read_all_note($param){
    $user_id  = $param['user_id'];
    $lang  = $param['lang'];
    $this->checkUserExist($user_id);

    $item = ['ms_is_read'=>1];
    $this->db->where('user_id', $user_id);
    $update = $this->db->update('luxyart_tb_user_message', $item);
    if($update) $this->res(true, $this->getMessage('update_success',$lang));
    else $this->res(false, $this->getMessage('update_fail',$lang));
    $this->res();
  }

  function list_bookmark_image($param){
    $user_id  = $param['user_id'];
    $current_page   = $param['current_page'];
    $amount_page    = $param['amount_page'];

    $this->db->select('*');
    $this->db->from('luxyart_tb_user_bookmark_image as b');
    $this->db->join('luxyart_tb_product_img as p', 'b.id_img = p.id_img');
    $this->db->where('b.user_id', $user_id);
    $result = $this->db->get()->result();

    $countData  = count($result);
    $numPage    = ceil($countData/$amount_page);
    $begin      = ($current_page-1)*$amount_page;

    $this->db->flush_cache();
    $this->db->select('*');
    $this->db->from('luxyart_tb_user_bookmark_image as b');
    $this->db->join('luxyart_tb_product_img as p', 'b.id_img = p.id_img');
    $this->db->where('b.user_id', $user_id);
    $this->db->limit($amount_page, $begin);
    $data = $this->db->get()->result();
    $data2 = [];
    foreach ($data as $key => $value) {
      $value = $this->updateUrlImage($value);
      array_push($data2, $value);
    }

    $res['countData'] = $countData;
    $res['numPage']   = $numPage;
    $res['dt']        = $data2;

    $this->res(true, "", $res);

    $this->res();
  }

  function list_bookmark_user($param){
    $user_id        = $param['user_id'];
    $current_page   = $param['current_page'];
    $amount_page    = $param['amount_page'];

    $this->db->select('*');
    $this->db->from('luxyart_tb_user_follow as f');
    $this->db->join('luxyart_tb_user as u', 'f.user_id = u.user_id');
    $this->db->where('f.follow_user_id', $user_id);
    $result = $this->db->get()->result();

    $countData  = count($result);
    $numPage    = ceil($countData/$amount_page);
    $begin      = ($current_page-1)*$amount_page;

    $this->db->flush_cache();
    $this->db->select('*');
    $this->db->from('luxyart_tb_user_follow as f');
    $this->db->join('luxyart_tb_user as u', 'f.user_id = u.user_id');
    $this->db->where('f.follow_user_id', $user_id);
    $this->db->limit($amount_page, $begin);
    $result = $this->db->get()->result();

    $data = [];
    foreach ($result as $key => $value) {

      $item               = $this->hideFieldUser($value);
      $item->count_image  = $this->countImageUser($item->user_id);
      $item->count_follow = $this->countFollow($item->user_id);
      $item = $this->updateUrlImage($item);

      array_push($data, $item);
    }

    $res['countData'] = $countData;
    $res['numPage']   = $numPage;
    $res['dt']        = $data;

    $this->res(true, "", $res);

    $this->res();
  }

  function get_competition($param){
    $user_id          = $param['user_id'];
    $competition_id   = $param['competition_id'];

    $this->db->from('luxyart_tb_user_competition as c');
    // $this->db->where('user_id', $user_id);
    $this->db->join('luxyart_tb_user as u', 'c.user_id = u.user_id');
    $this->db->where('c.id_competition', $competition_id);
    $result = $this->db->get()->row();
    $result = $this->hideFieldUser($result);
    $result = $this->updateUrlImage($result);

    /*=== Replace </ br> to /n ===*/
    $result->note_description_com = str_replace('<br />','',$result->note_description_com);
    $result->note_require_com = str_replace('<br />','',$result->note_require_com);
    $result->note_img_purpose_com = str_replace('<br />','',$result->note_img_purpose_com);
    $result->note_another_des_com = str_replace('<br />','',$result->note_another_des_com);

    $this->res(true, "", $result);

    $this->res();
  }

  function list_competition($param){
    $user_id        = $param['user_id'];
    $current_page   = $param['current_page'];
    $amount_page    = $param['amount_page'];

    $userInfo = $this->function_model->getUser($user_id);

    // $this->db->from('luxyart_tb_user_competition');
    // $this->db->where('status_com', 1);
    $sqlWhere = 'a.date_end_com >= "'.date('Y-m-d').'" AND (a.status_com = 1 OR a.status_com = 2)';
    if ($userInfo->user_level < 4){
      /*=== UserLevel < 4: don't show private competition ===*/
      // $this->db->where('is_private', 0);
      $sqlWhere = $sqlWhere.' AND a.is_private = 0';
    }
    $this->db->from('luxyart_tb_user_competition as a');
    $this->db->where($sqlWhere);
    $result = $this->db->get()->result();

    $countData  = count($result);
    $numPage    = ceil($countData/$amount_page);
    $begin      = ($current_page-1)*$amount_page;
    $this->db->flush_cache();

    // $this->db->from('luxyart_tb_user_competition as c');
    // $this->db->where('c.status_com', 1);
    // $this->db->where('c.date_end_com >=', date('Y-m-d'));
    if ($userInfo->user_level < 4){
      /*=== UserLevel < 4: don't show private competition ===*/
      // $this->db->where('is_private', 0);
    }
    $this->db->from('luxyart_tb_user_competition as a');
    $this->db->where($sqlWhere);
    // $this->db->or_where('c.status_com', 2);
    $this->db->join('luxyart_tb_user as u', 'a.user_id = u.user_id');
    $this->db->order_by('a.date_add_com', 'desc');
    $this->db->limit($amount_page, $begin);
    $result = $this->db->get()->result();
    // echo $this->db->last_query();
    // exit;

    $data = [];
    foreach ($result as $key => $value) {
      $value = $this->hideFieldUser($value);
      $value = $this->updateUrlImage($value);
      array_push($data, $value);
    }

    $res['countData'] = $countData;
    $res['numPage']   = $numPage;
    $res['dt']        = $data;
    $this->res(true, "", $res);

    $this->res();
  }

  function list_competition_user($param){
    $user_id        = $param['user_id'];
    $current_page   = $param['current_page'];
    $amount_page    = $param['amount_page'];

    $this->db->from('luxyart_tb_user_competition');
    $this->db->where('user_id', $user_id);
    $result = $this->db->get()->result();

    $countData  = count($result);
    $numPage    = ceil($countData/$amount_page);
    $begin      = ($current_page-1)*$amount_page;
    $this->db->flush_cache();

    $this->db->from('luxyart_tb_user_competition as c');
    $this->db->where('c.user_id', $user_id);
    $this->db->join('luxyart_tb_user as u', 'c.user_id = u.user_id');
    $this->db->order_by('c.id_competition', 'desc');
    $this->db->limit($amount_page, $begin);
    $result = $this->db->get()->result();
    $data = [];
    foreach ($result as $key => $value) {
      $value = $this->hideFieldUser($value);
      $value = $this->updateUrlImage($value);
      array_push($data, $value);
    }

    $res['countData'] = $countData;
    $res['numPage']   = $numPage;
    $res['dt']        = $data;
    $this->res(true, "", $res);

    $this->res();
  }

  function apply_competition($param){

    $user_id          = $param['user_id'];
    $list_image         = $param['list_image'];
    $competition_id   = $param['competition_id'];
    $data_join        = date('Y-m-d H:i:s');
    $lang = $param['lang'];

    /*=== check time expired ===*/
    $this->db->from('luxyart_tb_user_competition');
    $this->db->where(array('id_competition' => $competition_id));
		$this->db->select('luxyart_tb_user_competition.*');

		$rs = $this->db->get();
		$result = $rs->row();

    $this->checkUserLevel($user_id,2);

    if (date('Y-m-d') > $result->date_end_com){
      $this->res(false, $this->getMessage('competition_is_expired',$lang));
    }else{
      foreach ($list_image as $key => $value) {

        if($this->checkImageApplyExist($competition_id, $value['image_id'], $user_id)) {
          continue;
        }

        $item = [
              'id_img'          =>  $value['image_id'],
              'user_id'         =>  $user_id,
              'id_competition'  =>  $competition_id,
              'date_join'       =>  $data_join
        ];

        $insert = $this->db->insert('luxyart_tb_competition_img_apply', $item);

        if(!$insert){
          $this->res(false, $this->getMessage('apply_fail',$lang));
          break;
        }
        /*=== Update state apply competition for product image ===*/
        $data = array('img_is_join_compe' => 1);
        $this->db->where(array('id_img' => $value['image_id']));
        $this->db->update('luxyart_tb_product_img',$data);
      }

      /*=== Add notice & Push notification ===*/
      $userCreateComp = $this->function_model->getUser($result->user_id);
      $userApply = $this->function_model->getUser($user_id);

      $addNotice = array();
      $addNotice['type'] 						= "apply_competition";
      $addNotice["lang_id"] 					= $lang;
      $addNotice['user_id'] 					= $userCreateComp->user_id;
      $addNotice['user_send_id'] 				= $userApply->user_id;
      // Replace content
      $addNotice['email_apply'] 				= $userApply->email;
      $this->function_model->addNotice($addNotice);

      $content = $this->getContentNotice($addNotice['type'], $lang);
      $content =  str_replace('{email_apply}', $userApply->email,$content);
      $this->pushNotification($userCreateComp->user_id, $content);
      /*============== End ==================*/



      $this->res(true, $this->getMessage('apply_success',$lang));

      $this->db->where('id_competition', $competition_id);
      $get_competition = $this->db->get('luxyart_tb_user_competition')->row();
      if($get_competition->status_com == 1) {
        $aaa['status_com'] = 2;
        $this->db->where('id_competition', $competition_id);
        $this->db->update('luxyart_tb_user_competition', $aaa);
      }
    }
    $this->res();
  }

  function list_image_to_competition($param){
    $competition_id   = $param['competition_id'];
    $current_page     = $param['current_page'];
    $amount_page      = $param['amount_page'];

    $this->db->from('luxyart_tb_competition_img_apply');
    $this->db->where('id_competition', $competition_id);
    $result = $this->db->get()->result();

    $countData  = count($result);
    $numPage    = ceil($countData/$amount_page);
    $begin      = ($current_page-1)*$amount_page;
    $this->db->flush_cache();

    $this->db->from('luxyart_tb_competition_img_apply as a');
    $this->db->where('a.id_competition', $competition_id);
    $this->db->join('luxyart_tb_user as u', 'a.user_id = u.user_id');
    $this->db->join('luxyart_tb_user_competition as c', 'a.id_competition = c.id_competition');
    $this->db->join('luxyart_tb_product_img as i', 'a.id_img = i.id_img');
    $this->db->limit($amount_page, $begin);
    $result = $this->db->get()->result();
    $data = [];
    foreach ($result as $key => $value) {
      $value = $this->hideFieldUser($value);
      $value = $this->updateUrlImage($value);
      array_push($data, $value);
    }

    $res['countData'] = $countData;
    $res['numPage']   = $numPage;
    $res['dt']        = $data;
    $this->res(true, "", $res);


    $this->res();
  }

  function choose_image_competition($param){
    $user_id          = $param['user_id'];
    $list_image         = $param['list_image'];
    $competition_id   = $param['competition_id'];
    $lang   = $param['lang'];
    $data_join        = date("Y-m-d H:i:s", time());
    $this->res(true, "Choose success");

    /*=== Update status image apply competition ===*/
    foreach ($list_image as $key => $value) {
      $this->db->where('id_img', $value['image_id']);
      // $this->db->where('user_id', $user_id);
      $this->db->where('id_competition', $competition_id);
      $update = $this->db->update('luxyart_tb_competition_img_apply', ['result_img'=>1]);
      if(!$update){
        $this->res(false, "Choose fail");
        break;
      }else{
        $compeInfo = $this->getCompetition($competition_id);
        $userWinCompe = $this->getUserFromImageId($value['image_id']);

				$newUserPoint = $userWinCompe->user_point + $compeInfo->point_img_com;

				/*=== Update luxyart_tb_user ===*/
				$dataUpdate = array('user_point' => $newUserPoint);
				$this->db->where(array('user_id' => $userWinCompe->user_id));
				$this->db->update('luxyart_tb_user',$dataUpdate);

        /*=== Insert luxyart_tb_management_point ===*/
				$dataAddManagePoint = array(
					'user_id' => $userWinCompe->user_id,
					'point' => $compeInfo->point_img_com,
					'status_change_point' => 1, /* 1: tăng, 2: giảm */
					'content' => 'Reward from competition',
					'date_add'		=> date('Y-m-d H:i:s')
				);
				$this->db->insert('luxyart_tb_management_point', $dataAddManagePoint);


					/*=== Add notice & Push notification ===*/

					$addNotice = array();
					$addNotice['type'] 						= "review_competition_for_person_success";
					$addNotice["lang_id"] 					= $lang;
					$addNotice['user_id'] 					= $userWinCompe->user_id;
					$addNotice['user_send_id'] 				= -1;
					// Replace content
					$addNotice['email_post_competition'] 				= $userWinCompe->email;
					$this->function_model->addNotice($addNotice);

          $content = $this->getContentNotice($addNotice['type'], $lang);
          $content =  str_replace('{email_post_competition}', $userWinCompe->email,$content);
          $this->pushNotification($userWinCompe->user_id, $content);
					/*============== End ==================*/

      }
    }

    $this->db->flush_cache();
    $this->db->where('id_competition', $competition_id);
    $this->db->where('result_img', 1);
    $count_apply = count($this->db->get('luxyart_tb_competition_img_apply')->result());

    $this->db->flush_cache();
    $this->db->where('id_competition', $competition_id);
    $quantity_com = $this->db->get('luxyart_tb_user_competition')->row()->img_quantity_com;

    if($count_apply >= $quantity_com) {
      $this->db->flush_cache();
      $this->db->where('id_competition', $competition_id);
      $this->db->update('luxyart_tb_user_competition', ['status_com'=>3]);
    }


    $this->res();

  }

  function list_image_win_competition($param){
    $user_id          = $param['user_id'];
    $competition_id   = $param['competition_id'];

    $this->db->from('luxyart_tb_competition_img_apply as a');
    $this->db->where('a.id_competition', $competition_id);
    // $this->db->where('a.user_id', $user_id);
    $this->db->where('a.result_img', 1);
    $this->db->join('luxyart_tb_user as u', 'a.user_id = u.user_id');
    $this->db->join('luxyart_tb_user_competition as c', 'a.id_competition = c.id_competition');
    $this->db->join('luxyart_tb_product_img as i', 'i.id_img = a.id_img');
    $result = $this->db->get()->result();
    $data = [];
    foreach ($result as $key => $value) {
      $value = $this->hideFieldUser($value);
      $value = $this->updateUrlImage($value);
      array_push($data, $value);
    }

    $this->res(true, "", $data);

    $this->res();
  }

  function add_update_competition($param){

    $id_competition  = $param['id_competition'];
    $lang = $param['lang'];
    $data = array();
    $message = '';
    $isSuccess = false;

    if($id_competition == 0 || $id_competition == null) {

      /*=== Params use for Add new competition ===*/

    $item = [
      'user_id'               => $param['user_id'],
      'title_com'             => $param['title_com'],
      'date_start_com'        => $param['date_start_com'],
      'date_end_com'          => $param['date_end_com'],
      'icon_com'              => $param['icon_com'],
      'note_description_com'  => $param['note_description_com'],
      'note_require_com'      => $param['note_require_com'],
      'img_type_com'          => $param['img_type_com'],
      'img_color_id'          => $param['img_color_id'],
      'note_img_purpose_com'  => $param['note_img_purpose_com'],
      'point_img_com'         => $param['point_img_com'],
      'img_quantity_com'      => $param['img_quantity_com'],
      'link_url_com'          => $param['link_url_com'],
      'note_another_des_com'  => $param['note_another_des_com'],
      'os_register'           => $param['os_register'],
      'competition_time_agree_id' => $param['competition_time_agree_id'],
      'is_private' 			  => $param['is_private'],
      'is_private_img_apply'  => $param['is_private_img_apply']
    ];
  }else{

    /*=== Params use for Update new competition ===*/

    $item = [
      'user_id'               => $param['user_id'],
      'icon_com'              => $param['icon_com'],
      'note_description_com'  => $param['note_description_com'],
      'note_require_com'      => $param['note_require_com'],
      'img_type_com'          => $param['img_type_com'],
      'img_color_id'          => $param['img_color_id'],
      'note_img_purpose_com'  => $param['note_img_purpose_com'],
      'link_url_com'          => $param['link_url_com'],
      'note_another_des_com'  => $param['note_another_des_com'],
      'os_register'           => $param['os_register'],
      'competition_time_agree_id' => $param['competition_time_agree_id'],
      'is_private_img_apply'  => $param['is_private_img_apply']
    ];
  }

    $photo_des_com =  $param['photo_des_com'];
    $pointNeedToPostCompetition = $param['point_img_com']*$param['img_quantity_com'];
    $user = $this->function_model->getUser($param['user_id']);
    $totalPointUser = $user->user_point + $user->user_paymoney_getpoint;


        if($photo_des_com != '') {
          $filename = $param['user_id']."_".time().".jpg";
          $url_image = $this->base64_to_image($photo_des_com, $filename, DIR."competition_img/");
          $item['photo_des_com'] = $filename;
          //$item['photo_des_com'] = "test.jpg";
        }

        if($id_competition == 0 || $id_competition == null) {

          // Check enough point to post or not
          if ($totalPointUser >= $pointNeedToPostCompetition){
              /*=== Add new competition ===*/

              $item['date_add_com'] = date("Y-m-d H:i:s", time());
              $insert = $this->db->insert('luxyart_tb_user_competition', $item);
              $id_competition = $this->db->insert_id();//dhthien add


              // echo $this->db->last_query();
              $data['is_new'] = 1;
              if($insert){
                $isSuccess = true;
                $message = $this->getMessage('add_competition_success',$lang);

                /*=== Process subtract point when add new competition ===*/
                $newUserPaymentMoneyGetPoint = $user->user_paymoney_getpoint - $pointNeedToPostCompetition;
                $newUserPoint = $user->user_point;

                if ($newUserPaymentMoneyGetPoint < 0) {
                  $newUserPoint = $newUserPoint + $newUserPaymentMoneyGetPoint;
                  $newUserPaymentMoneyGetPoint = 0;
                }

                $data['user_paymoney_getpoint'] = $newUserPaymentMoneyGetPoint;
                $data['user_point'] = $newUserPoint;

                /*=== Update luxyart_tb_user ===*/
                $dataUpdate = array('user_paymoney_getpoint' => $newUserPaymentMoneyGetPoint,'user_point' => $newUserPoint);
                $this->db->where(array('user_id' => $user->user_id));
                $this->db->update('luxyart_tb_user',$dataUpdate);


                /*=== Insert luxyart_tb_management_point ===*/

                //dhthien 2017-12-04
                $data['user_fullname'] = $this->function_model->get_fullname($user->user_id, $lang);

                //send email
                $dataSendEmail = array();
                $dataSendEmail["name_email_code"] 		= "post_competition";
                $dataSendEmail["lang_id"] 				= $lang;
                $dataSendEmail["email_to"] 				= $user->user_email;
                $dataSendEmail["name"] 					= ucwords($data['user_fullname']);
                $dataSendEmail["title_com"]				= $param['title_com'];
                $dataSendEmail["date_start_com"]		= $param['date_start_com'];
                $dataSendEmail["date_end_com"]			= $param['date_end_com'];
                $dataSendEmail["point_img_com"]			= $param['point_img_com'];
                $dataSendEmail["img_quantity_com"]		= $param['img_quantity_com'];
                $dataSendEmail["link_url_com"]			= $param['link_url_com'];
                $dataSendEmail["link"]					= base_url()."mypage/list-compe.html";
                $dataSendEmail["is_send_admin"]			= 1;
                //$dataSendEmail["is_debug"]			= 1;

                $this->function_model->sendMailHTML($dataSendEmail);

                //send notice
                $addNotice = array();
                $addNotice['type'] 						= "post_competition";
                $addNotice["lang_id"] 					= $lang;
                $addNotice['user_id'] 					= $param['user_id'];
                $addNotice['title_com'] 				= $param['title_com'];
                $addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
                $addNotice['option_id'] 				= $id_competition;
                //$addNotice['is_debug'] 				= 1;

                $this->function_model->addNotice($addNotice);

                $notice_post_competition = $this->get_message('management_point_post_competition');
                $notice_post_competition = str_replace("{point}", $pointNeedToPostCompetition, $notice_post_competition);
                $notice_post_competition = str_replace("{title_com}", $param['title_com'], $notice_post_competition);
                $notice_post_competition = str_replace("{link}", base_url()."compe-detail/".$id_competition.".html", $notice_post_competition);

                //add point user
                $dataAddManagePoint = array(
                	'user_id' 				=> $user->user_id,//$user_id
                  	'point' 				=> $pointNeedToPostCompetition,//point_img_com*$img_quantity_com,
                  	'status_change_point' 	=> 2, /* 1: tăng, 2: giảm */
                	'inbox_id'				=> 14,
                  	'content' 				=> $notice_post_competition,
                  	'date_add'				=> date('Y-m-d H:i:s')
                );

                $this->db->insert('luxyart_tb_management_point', $dataAddManagePoint);
                //end dhthien 2017-12-04
                
                $this->function_model->set_use_point_package(round($pointNeedToPostCompetition,2), $user->user_id);

              }else{
                $isSuccess = false;
                $message = $this->getMessage('add_competition_fail',$lang);
               }
         }else{
          //  $this->res(false, "You isn't enough point to post competition!");
           $isSuccess = false;
           $message = $this->getMessage('you_ist_enough_point_to_post_competition',$lang);
         }
        }else{

          /*=== Check have user appply competition or not ===*/

          $this->db->from('luxyart_tb_user_competition');
          $this->db->where('id_competition', $id_competition);
          $this->db->select('*');
          $rs = $this->db->get();
          $result = $rs->row();
          if ($result->status_com < 2){

              /*=== Update competition ===*/

              $item['date_update_com'] = date("Y-m-d H:i:s", time());

              $this->db->where('id_competition', $id_competition);
              $update = $this->db->update('luxyart_tb_user_competition', $item);
              $data['is_new'] = 0;
              if($update) {
                $isSuccess = true;
                $message = $this->getMessage('update_success',$lang);

                //send email
                $dataSendEmail = array();
                $dataSendEmail["name_email_code"] 		= "edit_competition";
                $dataSendEmail["lang_id"] 				= $lang;
                $dataSendEmail["email_to"] 				= $user->user_email;
                $dataSendEmail["name"] 					= ucwords($data['user_fullname']);
                $dataSendEmail["title_com"]				= $param['title_com'];
                $dataSendEmail["date_start_com"]		= $param['date_start_com'];
                $dataSendEmail["date_end_com"]			= $param['date_end_com'];
                $dataSendEmail["point_img_com"]			= $param['point_img_com'];
                $dataSendEmail["img_quantity_com"]		= $param['img_quantity_com'];
                $dataSendEmail["link_url_com"]			= $param['link_url_com'];
                $dataSendEmail["link"]					= base_url()."mypage/list-compe.html";
                $dataSendEmail["is_send_admin"]			= 1;
                //$dataSendEmail["is_debug"]			= 1;

                $this->function_model->sendMailHTML($dataSendEmail);

                //send notice
                $addNotice = array();
                $addNotice['type'] 						= "edit_competition";
                $addNotice["lang_id"] 					= $lang;
                $addNotice['user_id'] 					= $param['user_id'];;
                $addNotice['title_com'] 				= $param['title_com'];
                $addNotice['link'] 						= base_url()."compe-detail/".$id_competition;
                $addNotice['option_id'] 				= $id_competition;
                //$addNotice['is_debug'] 				= 1;

                $this->function_model->addNotice($addNotice);

              }else{
                $isSuccess = false;
                $message = $this->getMessage('update_fail',$lang);
              }
          }else{
            $isSuccess = false;
            $message = $this->getMessage('have_user_apply_you_cant_edit_competition',$lang);
          }
        }

      $this->res($isSuccess, $message, $data);
      $this->res();
  }

  function bookmark_image($param){
    $user_id  = $param['user_id'];
    $image_id  = $param['image_id'];
    $status  = $param['status'];
    $lang  = $param['lang'];

    if($status) {
      $this->db->where('user_id', $user_id);
      $this->db->where('id_img', $image_id);
      $list = $this->db->get('luxyart_tb_user_bookmark_image')->result();
      if(count($list)==0) {
        $item = ['user_id'=>$user_id,'id_img'=>$image_id,'date_bookmark'=>date("Y-m-d H:i:s", time())];
        $insert = $this->db->insert('luxyart_tb_user_bookmark_image', $item);
        if($insert) {
          $this->res(true, "Bookmark thành công");

          /*=== Add notice & Push notification ===*/
          $this->db->from('luxyart_tb_product_img');
      		$this->db->where(array('id_img' => $image_id));
      		$this->db->select('luxyart_tb_product_img.user_id');
      		$rs = $this->db->get();
          $userReceive = $rs->row();
          $userSend = $this->function_model->getUser($user_id);

          $addNotice = array();
          $addNotice['type'] 						= "bookmark_image";
          $addNotice["lang_id"] 					= $lang;
          $addNotice['user_id'] 					= $userReceive->user_id;
          $addNotice['user_send_id'] 				= $user_id;
          // Replace content
          $addNotice['img_title'] 				= $userReceive->img_title;
          $addNotice['email'] 				= $userSend->user_email;
          $this->function_model->addNotice($addNotice);

          $content = $this->getContentNotice($addNotice['type'], $lang);
          $content =  str_replace('{email}', $userSend->user_email,$content);
          $content =  str_replace('{img_title}', $userSend->img_title,$content);
          $this->pushNotification($userReceive->user_id, $content);

          /*============== End ==================*/

        }else {
          $this->res(false, "Bookmark thất bại");

        }
      }else{
        $this->res(true, "Bookmark đã tồn tại");
      }
    }else{
      $this->db->where('user_id', $user_id);
      $this->db->where('id_img', $image_id);
      $delete = $this->db->delete('luxyart_tb_user_bookmark_image');
      if($delete) $this->res(true, "Bỏ bookmark thành công");
      else $this->res(false, "Bỏ bookmark thất bại");
    }

    $this->res();
  }

  function bookmark_user($param){
    $user_id  = $param['user_id'];
    $follow_user_id  = $param['follow_user_id'];
    $status  = $param['status'];
    $lang  = $param['lang'];

    if($status) {
      $this->db->where('follow_user_id', $user_id);
      $this->db->where('user_id', $follow_user_id);
      $list = $this->db->get('luxyart_tb_user_follow')->result();
      if(count($list)==0) {
        $item = ['follow_user_id'=>$user_id,'user_id'=>$follow_user_id,'date_follow'=>date("Y-m-d H:i:s", time())];
        $insert = $this->db->insert('luxyart_tb_user_follow', $item);
        if($insert){
          $this->res(true, "Follow thành công");

          /*=== Add notice & Push notification ===*/
          $userFollow = $this->function_model->getUser($user_id);

          $addNotice = array();
          $addNotice['type'] 						= "follow_user";
          $addNotice["lang_id"] 					= $lang;
          $addNotice['user_id'] 					= $follow_user_id;
          $addNotice['user_send_id'] 				= $user_id;
          // replace content
          $addNotice['email'] 				= $userFollow->user_email;

          $this->function_model->addNotice($addNotice);

          $content = $this->getContentNotice($addNotice['type'], $lang);
          $content =  str_replace('{email}', $userFollow->user_email,$content);
          $this->pushNotification($follow_user_id, $content);

          /*============== End ==================*/

        }else{
          $this->res(false, "Follow thất bại");
        }
      }else{
        $this->res(true, "Follow đã tồn tại");
      }
    }else{
      $this->db->where('follow_user_id', $user_id);
      $this->db->where('user_id', $follow_user_id);
      $delete = $this->db->delete('luxyart_tb_user_follow');
      if($delete) $this->res(true, "Bỏ follow thành công");
      else $this->res(false, "Bỏ follow thất bại");
    }

    $this->res();
  }










/*
|-------------------------------------------------------------------
| Function
|-------------------------------------------------------------------
*/
  public function res($isSuccess = null, $message = null, $data = null)
  {
    if(!is_null($isSuccess)) {
      if(is_bool($isSuccess)) {
        $this->data['isSuccess'] = $isSuccess?1:0;
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

      $json = json_encode($this->data);

      $json = str_replace('\\/', '/', $json);

      echo $json;

      exit;
    }
  }

  public function createDir($name) {
    if (!file_exists($name)) {
        mkdir($name, 0777, true);
      }
  }

  public function createDirFull($name) {
      $ex = explode("/", $name);
      $url = '';
      foreach ($ex as $key => $value) {
          if($key == 0) $url.= $value;
          else $url.= "/".$value;
          $this->createDir($url);
      }
  }

  public function base64_to_image($base64_string, $filename, $folder = '') {
      $url = $filename;
      if($folder != ''){
          $this->createDirFull($folder);
          $url = $folder."/".$filename;
      }
      file_put_contents($url, base64_decode(explode(',',$base64_string)[1]));
      return $folder."/".$filename;
  }


  /*=========================*/
  /*======= Tan Code ========*/
  /*=========================*/

  function pushNotification($userIdReceive, $content){

		$this->db->select('luxyart_tb_user.*');
		$this->db->from('luxyart_tb_user');
		$this->db->where('user_id', $userIdReceive);
		$rs2 = $this->db->get();
		$result = $rs2->result();
		$os_register = $result[0]->os_register;
		$device_token = $result[0]->device_token;
		if ($os_register == 1) /* ios */ {

		}else if ($os_register == 2) /* android */ {

		}

		$KEY_FIREBASE_SERVER = 'key=AAAAB7k6zuE:APA91bEoKEnmfHSHdavoCg5uye_kbkbF_7I2ZG2yx7bRxTCuwC83O5teYgrXFpyMhhHmdvvoZmFk0kzPwEAAnmKun3T5BAGae7u3VAIkeVe0esLv8P23OB-_qjD2YfwRU0JAwKaseKVU';


		$data_string = json_encode(array("notification" => array("title" => "", "body" => $content, "sound" => "default", "badge" => "1"), "to" => $device_token));
    $url = 'https://fcm.googleapis.com/fcm/send';
    $ch = curl_init($url);
    $result = file_get_contents('https://fcm.googleapis.com/fcm/send', null, stream_context_create(array(
    		'http' => array(
    				'method' => 'POST',
    				'header' => 'Content-Type: application/json' . "\r\n"
    				. 'Content-Length: ' . strlen($data_string) . "\r\n".'Authorization: '.$KEY_FIREBASE_SERVER. "\r\n",
    				'content' => $data_string,
    		),
    )));

    // $data = json_decode($result,true);
    // echo json_encode($data);
    // exit;
}

function getContentNotice($type,$lang){
  $this->db->from('luxyart_tb_inbox_templates');
  $this->db->select('*');
  $this->db->where(array('name' => $type, 'lang_id' => $lang));
  $rs = $this->db->get();
  $content = "";
  $row = $rs->row();

  if(!empty($row)){
    $inbox_templates_id = $row->inbox_templates_id;
    $subject = $row->subject;
    $message = $row->message;
    $link = $row->link;
    $content = strip_tags($message);
  }
  return $content;
}

function getUserFromImageId($imgId){
  $this->db->from('luxyart_tb_product_img');
  $this->db->join('luxyart_tb_user','luxyart_tb_user.user_id = luxyart_tb_product_img.user_id');
  $this->db->select('luxyart_tb_user.*');
  $this->db->where(array('luxyart_tb_product_img.id_img' => $imgId));
  $rs = $this->db->get();
  $result = $rs->row();
  return $result;
}

function getCompetition($competitionId){
  $this->db->from('luxyart_tb_user_competition');
  $this->db->select('luxyart_tb_user_competition.*');
  $this->db->where(array('luxyart_tb_user_competition.id_competition' => $competitionId));
  $rs = $this->db->get();
  $result = $rs->row();
  return $result;
}

function countFollow($user_id){

  $this->db->from('luxyart_tb_user_follow');
  $this->db->select('*');
  $this->db->where(array('user_id' => $user_id));
  $rs = $this->db->get();
  $count = $rs->num_rows();
  return $count;

}

function getMessage($value,$lang){
  if (($lang == '') || ($lang == 1)) {
    $lang = 'jp';
  }else if ($lang == 2) {
    $lang = 'vi';
  }else if ($lang == 3) {
    $lang = 'en';
  }

  /*=== LOGIN ===*/
  $arrayMessage["email_doesnt_exist"] = ["en" => "Email doesn't exist !",
                              "jp" => "このメールアドレスは登録されていません。",
                              "vi" => "Email không tồn tại"];
  $arrayMessage["login_success"] = ["en" => "Login success",
                                      "jp" => "ログイン完了",
                                      "vi" => "Đăng nhập thành công"];
  $arrayMessage["please_active_your_account"] = ["en" => "Please active your account",
                                                  "jp" => "お送りしているメール内のリンクからアカウントをアクティベートして下さい。",
                                                  "vi" => "Vui lòng kích hoạt tài khoản"];
  $arrayMessage["password_is_wrong"] = ["en" => "Password is wrong",
                                        "jp" => "パスワードが違います。",
                                        "vi" => "Mật khẩu sai"];
  $arrayMessage["login_fail"] = ["en" => "Login fail",
                                        "jp" => "ログインできませんでした。",
                                        "vi" => "Đăng nhập thất bại"];

  /*=== REGISTER EMAIL ===*/
  $arrayMessage["email_exist_in_system"] = ["en" => "Email exist in system!",
                                            "jp" => "このメールアドレスは既に使用されています。",
                                            "vi" => "Email đã tồn tại trong hệ thống"];
  $arrayMessage["register_success"] = ["en" => "Register success",
                                       "jp" => "会員登録が完了しました。確認メールからアカウントをアクティベートして下さい。",
                                       "vi" => "Đăng ký thành công"];
  $arrayMessage["register_fail"] = ["en" => "Register fail",
                                    "jp" => "登録できませんでした。",
                                    "vi" => "Đăng ký thất bại"];

  /*=== REGISTER EMAIL ===*/
  $arrayMessage["user_not_exist"] = ["en" => "User not exist in system!",
                                            "jp" => "このユーザーは存在しません。",
                                            "vi" => "Người dùng không tồn tại"];
  /*=== UPDATE INFORMATION ===*/
  $arrayMessage["update_success"] = ["en" => "Update success!",
                                     "jp" => "更新が完了しました。",
                                     "vi" => "Cập nhật thành công"];
  $arrayMessage["update_fail"] = ["en" => "Update fail!",
                                  "jp" => "更新できませんでした。",
                                  "vi" => "Cập nhật thất bại"];
  $arrayMessage["userid_or_password_is_wrong"] = ["en" => "User id or password is wrong",
                                  "jp" => "ユーザー名かパスワードが間違っています。",
                                  "vi" => "user id hoặc mật khẩu sai"];

  /*=== APPLY COMPETITION ===*/
  $arrayMessage["competition_is_expired"] = ["en" => "Competition is expired",
                                    "jp" => "コンペは終了しています。",
                                    "vi" => "Cuộc thi đã hết hạn"];
  $arrayMessage["apply_fail"] = ["en" => "Apply fail",
                                            "jp" => "応募できませんでした。",
                                            "vi" => "Tham gia thất bại"];
  $arrayMessage["apply_success"] = ["en" => "Apply success", "jp" => "応募が完了しました。", "vi" => "Tham gia thành công"];

  /*=== ADD COMPETITION ===*/
  $arrayMessage["add_competition_success"] = ["en" => "Add competition success", "jp" => "コンペの投稿が完了しました。", "vi" => "Thêm cuộc thi thành công"];
  $arrayMessage["add_competition_fail"] = ["en" => "Add competition fail", "jp" => "コンペが投稿できませんでした。", "vi" => "Thêm cuộc thi thất bại"];
  $arrayMessage["you_ist_enough_point_to_post_competition"] = ["en" => "You don’t have enough LUX to post competition!", "jp" => "LUXが足りません。", "vi" => "Bạn không đủ LUX để đăng cuộc thi"];
  $arrayMessage["have_user_apply_you_cant_edit_competition"] = ["en" => "Have user apply, you can't edit competition", "jp" => "応募者がいるため、コンペの内容を編集できません。", "vi" => "Đã có người tham gia, bạn không thể tham gia tấm hình"];

  /*=== ACTIVE IMAGE ===*/
  $arrayMessage["you_already_been_this_state"] = ["en" => "You already been this state", "jp" => "", "vi" => "Bạn đang ở trạng thái này!"];
  $arrayMessage["you_cant_enter_this_page"] = ["en" => "You can't enter this page", "jp" => "このページは表示できません。", "vi" => "Bạn không có quyền truy cập trang này!"];

  /*=== UPLOAD IMAGE ===*/
  $arrayMessage["image_already_exist_md5"] = ["en" => "This image already exist md5", "jp" => "この素材は既に他のユーザーに使用されています。", "vi" => "Hình ảnh tồn tại md5"];
  $arrayMessage["your_image_already_apply_competition_cannot_edit_information"] = ["en" => "Your image already apply competition, cannot edit infomation", "jp" => "この素材はコンペに応募中のため、編集できません。", "vi" => "Tấm hình của bạn đã tham gia cuộc thi, nên không thể cập nhật thông tin"];

  /*=== BUY IMAGE ===*/
  $arrayMessage["this_image_is_out_of_stock"] = ["en" => "This image is out of stock", "jp" => "この素材は売り切れです。", "vi" => "Tấm ảnh đã hết"];
  $arrayMessage["you_cant_buy_your_image"] = ["en" => "You can't buy your image", "jp" => "自分の素材は購入できません。", "vi" => "Bạn không thể mua ảnh của mình"];
  $arrayMessage["your_point_isnt_enough_to_buy_image"] = ["en" => "Your point isn't enough to buy image", "jp" => "素材購入に必要なLUXが足りません。", "vi" => "Số điểm của bạn không đủ để mua ảnh"];

  /*=== BUY POINT ===*/
  $arrayMessage["buy_point_success"] = ["en" => "Buy point success", "jp" => "LUXの購入が完了しました。", "vi" => "Mua điểm thành công"];
  $arrayMessage["buy_point_fail"] = ["en" => "Buy point fail", "jp" => "LUXが購入できませんでした。", "vi" => "Mua điểm thất bại"];
  $arrayMessage["order_point_success"] = ["en" => "We received order to purchase LUX. We send email for detail. Please transfer fee to our account and let us know by LUX history page.", "jp" => "LUXの購入申請が完了しました。emailをご確認下さい。振込完了後、LUX利用履歴から管理人に送金完了の旨を通知して下さい。", "vi" => "Đặt mua điểm thành công"];

  /*=== RESET PASSWORD ===*/
  $arrayMessage["please_check_your_email_to_reset_password"] = ["en" => "Please check your email to reset password", "jp" => "登録したメールアドレスを確認して下さい。パスワードリセット情報をお送りしました。", "vi" => "Vui lòng kiểm tra email để lấy lại mật khẩu"];

  /*=== ADD IMAGE TO SELL ===*/
  $arrayMessage["get_image_to_your_album_success"] = ["en" => "Get image to your album success", "jp" => "マイアルバムへの追加が完了しました。", "vi" => "Lấy hình để bán thành công"];
  $arrayMessage["get_image_to_your_album_fail"] = ["en" => "Get image to your album fail", "jp" => "マイアルバムへ追加できませんでした。", "vi" => "Lấy hình ảnh để bán thất bại"];
  $arrayMessage["remove_get_image_success"] = ["en" => "Remove get image success", "jp" => "マイアルバムから削除しました。", "vi" => "Xoá ảnh đã lấy thành công"];
  $arrayMessage["remove_this_image_from_your_album_fail"] = ["en" => "Remove this image from your album fail", "jp" => "マイアルバムから削除できませんでした。", "vi" => "Xoá ảnh đã lấy thất bại"];

  /*=== UPGRADE ACCOUNT ===*/
  $arrayMessage["you_cant_upgrade_below_level"] = ["en" => "You can't upgrade below level", "jp" => "グレードダウンすることはできません。", "vi" => "Bạn không thể nâng cấp tài khoản ở mức thấp hơn hiện tại"];
  $arrayMessage["you_already_upgrade_this_level"] = ["en" => "You already upgrade this level", "jp" => "既にアップグレードしています。", "vi" => "Bạn đã nâng cấp tài khoản ở mức này"];
  $arrayMessage["upgrade_account_success"] = ["en" => "Upgrade Account success", "jp" => "アップグレードが完了しました。", "vi" => "Nâng cấp tài khoản thành công"];
  $arrayMessage["you_isnt_enough_point_to_upgrade_account"] = ["en" => "You don’t have enough point to upgrade account", "jp" => "アップグレードに必要なLUXが足りません。", "vi" => "Bạn không đủ điểm để nâng cấp tài khoản"];

  /*=== REQUIRE ADMIN TO TRANFER ===*/
  $arrayMessage["minimum_point_tranfer_need_bigger_10"] = ["en" => "Minimun point tranfer need > 10", "jp" => "出金には10LUX以上の素材販売で得たLUXが必要です。", "vi" => "Số điểm tối thiểu yêu cầu chuyển khoản phải lớn hơn 10"];
  $arrayMessage["your_point_get_inst_enough_to_tranfer"] = ["en" => "Your point get isn't enough to tranfer", "jp" => "出金に必要なLUXが足りません。", "vi" => "Số điểm bạn yêu cầu vượt quá số điểm hiện tại"];
  $arrayMessage["require_admin_tranfer_success"] = ["en" => "Require admin tranfer success", "jp" => "出金依頼が完了しました。", "vi" => "Yêu cầu chuyển khoản thành công"];

  /*=== REPORT IMAGE ===*/
  $arrayMessage["report_success"] = ["en" => "Report success", "jp" => "違反報告が完了しました。", "vi" => "Báo lỗi thành công"];

  /*=== REPORT IMAGE ===*/
  $arrayMessage["update_bank_information_success"] = ["en" => "Update bank information success", "jp" => "銀行口座情報の更新が完了しました。", "vi" => "Cập nhật thông tin ngân hàng thành công"];


  return $arrayMessage[$value][$lang];
}

	function get_message($value, $lang){

		$CI =& get_instance();
		$CI->config->load();

		if($lang == 1){
			$lang = "ja";
		}
		else if($lang == 2){
			$lang = "vi";
		}
		else if($lang == 3){
			$lang = "en";
		}

		$this->lang->load('dich', $lang);
		return $this->lang->line($value);

	}

}
