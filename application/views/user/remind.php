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
<script src="<?php echo base_url(); ?>publics/js/user_reset_password<?php echo $ngonngu?>.js"></script>
<div class="contents-other">
        <div class="ttl-surport">
          <div class="ttl-surport__filter">
            <div class="ttl-surport__box">
              <h1><?php echo $this->lang->line('page_remin_h2'); ?></h1>
            </div>
          </div>
        </div>
        <div class="contents-other__main">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url(); ?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('page_remin_h2'); ?></span></li>
            </ul>
          </div>
          <div class="contents-other__body">
            <h2><?php echo $this->lang->line('page_remin_h2'); ?></h2>
            <p><?php echo $this->lang->line('page_remin_description'); ?></p>
            <div class="contact-form remind">
                <dl>
                  <dt><?php echo $this->lang->line('p_dang_ky_creator_form_email'); ?></dt>
                  <dd>
                    <input name="re_email" id="re_email" type="text" placeholder="<?php echo $this->lang->line('p_dang_ky_creator_form_email_placeholder'); ?>">
                  </dd>
                </dl>
                <div id="re_email_msg" class="warning" style="display: none;"></div>
                <div class="contact-form__btn">
                  <input type="submit" value="<?php echo $this->lang->line('page_remin_button'); ?>" onclick="return check_error_user_reset_password();">
                </div>
            </div>
          </div>
        </div>
      </div>