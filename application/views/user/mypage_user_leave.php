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
<script src="<?php echo base_url(); ?>publics/js/user_leave<?php echo $ngonngu?>.js"></script>
<div id="leave-popup" class="leave-popup">
      <div class="leave-popup__filter"></div>
      <div class="leave-popup__box">
        <p><?php echo $this->lang->line('user_page_leave_pop_up_content'); ?></p>
        <div class="leave-popup__btn">
          <div class="leave-popup__btn--leave">
            <input type="submit" value="<?php echo $this->lang->line('user_page_leave_pop_up_content_button_agree_leave'); ?>">
          </div>
          <div class="leave-popup__btn--cancel"><span><?php echo $this->lang->line('user_page_leave_pop_up_content_button_cancel_leave'); ?></span></div>
        </div>
      </div>
    </div>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('user_page_leave_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('user_page_leave_h1'); ?></h1>
            <div class="alert--gray">
            <?php echo $this->lang->line('user_page_leave_description'); ?>
            </div>
            <div class="mypage-form">
              <form action="" method="post">
                <dl>
                  <dt><?php echo $this->lang->line('user_page_leave_input_pass_text'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau'); ?></span></dt>
                  <dd>
                    <input type="password" name="password" id="password">
                    <span id="re_password_msg" class="warning" style="display: none;"></span>
                  </dd>
                  <dt><?php echo $this->lang->line('user_page_leave_input_reason_text'); ?></dt>
                  <dd>
                    <textarea name="reason"></textarea>
                  </dd>
                </dl>
                
                <div class="mypage-form__btn"><span id="leave-conf-btn"><?php echo $this->lang->line('user_page_leave_button'); ?></span></div>
              </form>
            </div>
          </div>
    </div>
  </div>