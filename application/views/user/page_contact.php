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
              <h1><?php echo $this->lang->line('page_contact_h1'); ?></h1>
            </div>
          </div>
        </div>
        <div class="contents-other__main">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('page_contact_h1'); ?></span></li>
            </ul>
          </div>
          <div class="contents-other__body">
            <h2><?php echo $this->lang->line('page_contact_h1'); ?></h2>
            <?php echo $this->lang->line('page_contact_content_text'); ?>
            
            <div class="contact-form">
              <form action="" method="post" >
                <dl>
                  <dt><?php echo $this->lang->line('contact_input_name'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau'); ?></span></dt>
                  <dd>
                    <input name="tencontact" type="text" value="<?php if(@$load_post_contact['tencontact']) {echo $load_post_contact['tencontact'];}?>" placeholder="お名前を入力してください">
                    <span class="error"><?php echo form_error('tencontact'); ?></span>
                  </dd>
                  <dt><?php echo $this->lang->line('contact_input_email'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau'); ?></span></dt>
                  <dd>
                    <input name="emailcontact" value="<?php if(@$load_post_contact['emailcontact']) {echo $load_post_contact['emailcontact'];}?>" type="text" placeholder="メールアドレスを半角で入力してください">
                    <span class="error"><?php echo form_error('emailcontact'); ?></span>
                  </dd>
                  <dt><?php echo $this->lang->line('contact_input_type'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau'); ?></span></dt>
                  <dd>
                    <select name="casecontact">
                      <option value="0"><?php echo $this->lang->line('contact_input_select_type'); ?></option>
                      <option value="1" <?php if(@$load_post_contact['casecontact']==1) {echo 'selected';}?>><?php echo $this->lang->line('contact_input_text_option_1'); ?></option>
                      <option value="2" <?php if(@$load_post_contact['casecontact']==2) {echo 'selected';}?>><?php echo $this->lang->line('contact_input_text_option_2'); ?></option>
                      <option value="3" <?php if(@$load_post_contact['casecontact']==3) {echo 'selected';}?>><?php echo $this->lang->line('contact_input_text_option_3'); ?></option>
                    </select>
                    <span class="error"><?php echo form_error('casecontact'); ?></span>
                  </dd>
                  <dt><?php echo $this->lang->line('contact_input_content'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau'); ?></span></dt>
                  <dd>
                    <textarea name="noidungcontact"><?php if(@$load_post_contact['noidungcontact']) {echo $load_post_contact['noidungcontact'];}?></textarea>
                    <span class="error"><?php echo form_error('noidungcontact'); ?></span>
                  </dd>
                </dl>
                <div class="contact-form__btn">
                  <input name="submit" type="submit" value="<?php echo $this->lang->line('contact_input_submit_button'); ?>">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>