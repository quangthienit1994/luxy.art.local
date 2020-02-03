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
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('user_page_change_email_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('user_page_change_email_h1'); ?></h1>
            <div class="mypage-form">
              <div class="mypage-form__text">
                <p><?php echo $this->lang->line('user_page_change_email_content_description'); ?></p>
              </div>
              <form action="" method="post">
                <dl>
                  <dt><?php echo $this->lang->line('user_page_change_email_input_email_text'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau'); ?></span></dt>
                  <dd>
                    <input type="text" name="email" value="<?php if(isset($_POST['email'])) {echo $_POST['email'];}?>">
                    <span class="error"><?php echo form_error('email'); ?></span>
                  </dd>
                  <dt><?php echo $this->lang->line('user_page_change_email_input_email_text_once_more'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau'); ?></span></dt>
                  <dd>
                    <input type="text" name="xacnhanemail" value="<?php if(isset($_POST['xacnhanemail'])) {echo $_POST['xacnhanemail'];}?>">
                    <span class="error"><?php echo form_error('xacnhanemail'); ?></span>
                  </dd>
                </dl>
                <div class="mypage-form__btn">
                  <input type="submit" name="submit" value="<?php echo $this->lang->line('user_page_change_email_input_email_button'); ?>">
                </div>
              </form>
            </div>
          </div>
      
    </div>
  </div>