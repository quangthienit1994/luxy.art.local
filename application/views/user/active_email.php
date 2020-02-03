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
?>
<div class="contents-other">
        <div class="ttl-surport">
          <div class="ttl-surport__filter">
            <div class="ttl-surport__box">
              <h1><?php echo $this->lang->line('page_active_email_h2'); ?></h1>
            </div>
          </div>
        </div>
        <div class="contents-other__main">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url(); ?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('page_active_email_h2'); ?></span></li>
            </ul>
          </div>
          <div class="contents-other__body">
            <h2><?php echo $this->lang->line('page_active_email_h2'); ?></h2>
            <?php if($error_active =="code_active_do")
			{?>
            <p><?php echo $this->lang->line('page_active_email_msg_ok'); ?></p>
                <div class="link-btn"><a href="<?php echo base_url('login.html');?>"><?php echo $this->lang->line('page_active_email_go_to_mypage'); ?></a></div>
			<?php }?>
            <?php if($error_active =="code_active_before")
			{?>
            <p><?php echo $this->lang->line('page_active_email_msg_error_already_active_code'); ?></p>
                <div class="link-btn"><a href="<?php echo base_url('login.html');?>"><?php echo $this->lang->line('page_active_email_go_to_mypage'); ?></a></div>
			<?php }?>
            <?php if($error_active =="code_not_true")
			{?>
            <p><?php echo $this->lang->line('page_active_email_msg_error_code_not_true'); ?></p>
                <div class="link-btn"><a href="<?php echo base_url();?>"><?php echo $this->lang->line('page_active_email_go_to_home_page'); ?></a></div>
			<?php }?>
            <?php if($error_active =="code_id_not_exits")
			{?>
            <p><?php echo $this->lang->line('page_active_email_msg_error_no_code'); ?></p>
                <div class="link-btn"><a href="<?php echo base_url();?>"><?php echo $this->lang->line('page_active_email_go_to_home_page'); ?></a></div>
			<?php }?>
          </div>
        </div>
      </div>