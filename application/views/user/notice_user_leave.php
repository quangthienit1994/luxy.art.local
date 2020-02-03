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
<div class="contents-leave" style="min-height:275px">
        <div class="contents-leave__wrapper clearfix">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('user_page_leave_h1'); ?></span></li>
            </ul>
          </div>
          <div class="contents-leave__main">
            <div class="alert--gray">
              <p><?php echo $this->lang->line('user_page_leave_notice_leave_success1'); ?><a href="<?php echo base_url()?>"><?php echo $this->lang->line('user_page_leave_notice_leave_success2'); ?></p>
            </div>
          </div>
        </div>
      </div>