<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
$config['language'] = $_SESSION['lang'];
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);
$logged_in = $CI->function_model->get_logged_in();
$user_id = $logged_in['user_id'];
$CI->load->model('manager_user_model');
$user = $CI->manager_user_model->get_user_id($user_id);
?>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('title_bank_account_information'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h2 class="contents-mypage__find"><?php echo $this->lang->line('title_bank_account_information'); ?></h2>
            <?php if($notice_update!=""){?>
            <div class="alert--green">
              <p><?php echo $this->lang->line($notice_update); ?></p>
            </div>
              <?php } ?>
            <div class="mypage-form">
            <form action="" method="post" enctype="multipart/form-data" >
            	<dt><?php echo $this->lang->line('bank_account_input_bank_name'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <input type="text" name="bank_name" value="<?php if(@$load_bank_user['bank_name']) {echo $load_bank_user['bank_name'];}?>" placeholder="三菱UFJ銀行"><span class="error"><?php echo form_error('bank_name'); ?></span>
                </dd>
                <dt><?php echo $this->lang->line('bank_account_input_bank_branch_name'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <input type="text" name="bank_branch_name" value="<?php if(@$load_bank_user['bank_branch_name']) {echo $load_bank_user['bank_branch_name'];}?>" placeholder="銀座支店"><span class="error"><?php echo form_error('bank_branch_name'); ?></span>
                </dd>
                <dt><?php echo $this->lang->line('bank_account_input_bank_account_number'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <input type="text" name="bank_account_number" value="<?php if(@$load_bank_user['bank_account_number']) {echo $load_bank_user['bank_account_number'];}?>" placeholder="101000000"><span class="error"><?php echo form_error('bank_account_number'); ?></span>
                </dd>
                <dt><?php echo $this->lang->line('bank_account_input_account_name'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <input type="text" name="account_name" value="<?php if(@$load_bank_user['account_name']) {echo $load_bank_user['account_name'];}?>" placeholder="ラクシー タロウ"><span class="error"><?php echo form_error('account_name'); ?></span>
                </dd>
                <dt><?php echo $this->lang->line('bank_account_select_account_type'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <select name="type_bank">
                      <option value="1" <?php if(@$load_bank_user['type_bank']==1) {echo 'selected';}?> ><?php echo $this->lang->line('bank_account_select_account_type_person'); ?></option>
                      <option value="2" <?php if(@$load_bank_user['type_bank']==2) {echo 'selected';}?>><?php echo $this->lang->line('bank_account_select_account_type_company'); ?></option>
                    </select>
                </dd>
            	<div class="mypage-form__btn">
                  <input type="submit" name="submit" value="<?php echo $this->lang->line('mypage_user_config_button'); ?>">
                </div>
            </form>
            </div>
          </div>
    </div>
</div>