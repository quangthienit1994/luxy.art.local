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
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('manager_adwords_h2');?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h2 class="contents-mypage__find"><?php echo $this->lang->line('manager_adwords_h2');?></h2>
            <div class="mypage-list">
                    <p><?php echo $this->lang->line('manager_adwords_p_text');?></p>
                </div>
            <div class="clear clearfix"></div>
            <div class="mypage-form">
            <?php if($show_notice!=0){?>
            <div class="alert--green">
              <p><?php if($show_notice==1){ echo $this->lang->line('adwords_notice_update');} else {echo $this->lang->line('adwords_notice_insert');}?></p>
            </div>
            <?php }?>
            <form action="" method="post" enctype="multipart/form-data" >
            	
                 
                <dt><?php echo $this->lang->line('manager_adwords_text_label_input');?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau');?></span></dt>
                <dd>
                	<textarea rows="30" name="banner_code"><?php if(@$load_banner_code['banner_code']) {echo $load_banner_code['banner_code'];}?></textarea>
                    <span class="error"><?php echo form_error('banner_code'); ?></span>
                </dd>
            	<div class="mypage-form__btn">
                  <input type="submit" name="submit" value="<?php echo $this->lang->line('mypage_user_config_button');?>">
                </div>
            </form>
            </div>
          </div>
    </div>
</div>