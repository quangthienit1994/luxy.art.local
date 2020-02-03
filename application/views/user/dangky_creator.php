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
<script src="<?php echo base_url(); ?>publics/js/register_user_creator<?php echo $ngonngu?>.js"></script>
<div class="entry-page">
        <div class="entry-page__creator">
          <div class="entry-page__filter">
            <div class="entry-page__box">
              <div class="entry-page__box-inner">
                <h2 class="entry-page__find"><?php echo $this->lang->line('p_dang_ky_creator_page__find_h2'); ?></h2>
                <div id="error_register" class="warning" style="display: none;"></div>
                <div class="entry-page__form">
                    <dl>
                      <dt><?php echo $this->lang->line('p_dang_ky_creator_form_email'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                      <dd>
                        <input id="re_email" name="email" type="text" placeholder="<?php echo $this->lang->line('p_dang_ky_creator_form_email_placeholder'); ?>" value="">
                        <span id="re_email_msg" class="warning" style="display: none;"></span>
                      </dd>
                      <dt><?php echo $this->lang->line('p_dang_ky_creator_form_pass'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                      <dd>
                        <input id="re_password" name="password" type="password" placeholder="<?php echo $this->lang->line('p_dang_ky_creator_form_pass_placeholder'); ?>" value="">
                        <span id="re_password_msg" class="warning" style="display: none;"></span>
                      </dd>
                      <dt><?php echo $this->lang->line('p_dang_ky_creator_form_pass_2'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                      <dd>
                        <input id="re_againpassword" name="againpassword" type="password" placeholder="<?php echo $this->lang->line('p_dang_ky_creator_form_pass_placeholder_2'); ?>" value="">
                        <span id="re_againpassword_msg" class="warning" style="display: none;"></span>
                      </dd>
                      <dt><?php echo $this->lang->line('p_dang_ky_creator_form_username'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                      <dd>
                        <input id="username" name="username" type="text" placeholder="<?php echo $this->lang->line('p_dang_ky_creator_form_username_placeholder'); ?>" value="">
                        <span id="re_username_msg" class="warning" style="display: none;"></span>
                      </dd>
                    </dl>
                    <div class="entry-page__checkbox">
                      <label>
                        <input id="re_checknotice" name="checknotice" type="checkbox" checked="checked">
                        <p><?php echo $this->lang->line('p_dang_ky_creator_form_received_notice'); ?></p>
                      </label>
                    </div>
                    <div class="entry-page__btn">
                      <input name="submit" type="submit" id="btnRegisterCreator" value="<?php echo $this->lang->line('p_dang_ky_creator_form_button_submit'); ?>" onclick="return check_error_register();">
                    </div>
                    <div class="entry-page__notes">
                      <p><?php echo $this->lang->line('p_dang_ky_creator_bottom_form1'); ?><a href="<?php echo base_url('page/privacy.html');?>"><?php echo $this->lang->line('p_dang_ky_creator_bottom_form2'); ?><a href="<?php echo base_url('page/foruse.html');?>"><?php echo $this->lang->line('p_dang_ky_creator_bottom_form3'); ?></p>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> 
