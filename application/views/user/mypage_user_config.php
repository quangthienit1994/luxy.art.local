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
<style>
.mypage-avatar-user__icon {
    width: 44px;
    height: 44px;
    border-radius: 22px;
    overflow: hidden;
    display: inline-block;
    vertical-align: top;
    margin-right: 15px;
}
.mypage-avatar-user__icon img {
    width: 44px;
    height: 44px;
    float: left;
    margin-right: 20px;
}
</style>
<script>
$(document).ready(function() {
    var checkbox_id = '<?php echo $load_user_config['notice_luxyart'];?>';
	if(checkbox_id==1)
	{
	$("div.mypage-form input[type='checkbox']").attr('checked','checked');
	}
	
});
function check_checkbox(element)
	{
		if(element.checked){
			element.value='1';
		  }else{
			element.value='0';
		  }
	}
</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('mypage_user_config_title'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('mypage_user_config_title'); ?></h1>
            <div class="mypage-form">
            
            <?php if($show_update_notice==1){?>
            <div class="alert--green">
              <p><?php echo $this->lang->line('need_to_update_basic_info_before_to_post_image_compe_view_imge_view_compe');?></p>
            </div>
              <?php } ?>
            <?php if($notice_update!=""){?>
            <div class="alert--green">
              <p><?php echo $this->lang->line('edit_user_success'); ?></p>
            </div>
              <?php } ?>
              <form action="" method="post" enctype="multipart/form-data" >
                <dl>
                  <dt><?php echo $this->lang->line('p_dang_ky_creator_form_email'); ?></dt>
                  <dd>
                    <p><?php echo isset($load_user_config['user_email']) ? $load_user_config['user_email'] : ""; ?><a href="<?php echo base_url()?>mypage/user-email.html"><?php echo $this->lang->line('mypage_user_config_change_email_text'); ?></a></p>
                  </dd>
                  <dt><?php echo $this->lang->line('p_dang_ky_creator_form_pass'); ?></dt>
                  <dd>
                    <p>●●●●●●●● <a href="<?php echo base_url()?>mypage/user-password.html"><?php echo $this->lang->line('mypage_user_config_change_pass_text'); ?></a></p>
                  </dd>
                </dl>
                
                <div class="form-name">
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('mypage_user_config_last_name'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></p>
                    <input type="text" name="lastname" value="<?php if(@$load_user_config['user_lastname']) {echo $load_user_config['user_lastname'];}?>"><span class="error"><?php echo form_error('lastname'); ?></span>
                  </div>
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('mypage_user_config_first_name'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></p>
                    <input type="text" name="firstname" value="<?php if(@$load_user_config['user_firstname']) {echo $load_user_config['user_firstname'];}?>"><span class="error"><?php echo form_error('firstname'); ?></span>
                  </div>
                  
                </div>
                <dt><?php echo $this->lang->line('p_dang_ky_creator_form_username'); ?><span class="contact-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                      <dd>
                        <input id="display_name" name="display_name" type="text" placeholder="<?php echo $this->lang->line('p_dang_ky_creator_form_username_placeholder'); ?>" value="<?php echo isset($load_user_config['display_name']) ? $load_user_config['display_name'] : ""; ?>">
                        <span class="error"><?php echo form_error('display_name'); ?></span>
                      </dd>
                <dl>
                  <dt><?php echo $this->lang->line('mypage_user_config_avatar_user'); ?></dt>
                  <dd>
                        <span class="mypage-avatar-user__icon">
                        <?php if($load_user_config['user_avatar']!="")
						{?>
                        <img src="<?php echo base_url()?>publics/avatar/<?php echo $load_user_config['user_avatar'];?>">
                        <?php } else {?>
                        <img src="<?php echo base_url(); ?>publics/avatar/prof-img.png">
                        <?php }?>
                        </span>
                    <input type="file" id="photo_file" name="photo_file">
                    <span class="error"><?php echo form_error('photo_file'); ?></span>
                  </dd>
                  <dt><?php echo $this->lang->line('mypage_user_config_banner_user'); ?></dt>
                  <dd>
                        <span class="mypage-avatar-user__icon">
                        <?php if($load_user_config['user_banner']!="")
						{?>
                        <img src="<?php echo base_url()?>publics/banner/<?php echo $load_user_config['user_banner'];?>">
                        <?php } else {?>
                        <img src="<?php echo base_url(); ?>publics/banner/user-bg_no_image.jpg">
                        <?php }?>
                        </span>
                    <input type="file" id="banner_file" name="banner_file">
                    <span class="error"><?php echo form_error('banner_file'); ?></span>
                  </dd>
                  <dt><?php echo $this->lang->line('mypage_user_config_coutry'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                  <dd>
                    <input readonly="readonly" type="text" name="" value="<?php echo isset($load_user_config['country_name']) ? $load_user_config['country_name'] : ""; ?>">
                  </dd>
                  <dt><?php echo $this->lang->line('mypage_user_config_zip_code'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                  <dd>
                    <input type="text" name="zipcode" value="<?php if(@$load_user_config['user_zipcode']) {echo $load_user_config['user_zipcode'];}?>"><span class="error"><?php echo form_error('zipcode'); ?></span>
                  </dd>
                  <dt><?php echo $this->lang->line('mypage_user_config_state'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                  <dd>
                    <select name="state_select">
                    	<?php 
						
						foreach($load_list_state as $show_load_list_state){?>
                      <option value="<?php echo $show_load_list_state->state_id;?>" <?php if(@$load_user_config['state_id']==$show_load_list_state->state_id) {echo 'selected';}?>><?php echo $show_load_list_state->state_name;?></option>
                      <?php }?>
                    </select>
                  </dd>
                  
                  <dt><?php echo $this->lang->line('mypage_user_config_cityname'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                  <dd>
                    <input type="text" name="cityname" value="<?php if(@$load_user_config['cityname']) {echo $load_user_config['cityname'];}?>">
                  <span class="error"><?php echo form_error('cityname'); ?></span></dd>
                  <dt><?php echo $this->lang->line('mypage_user_config_address'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                  <dd>
                    <input type="text" name="address" value="<?php if(@$load_user_config['user_address']) {echo $load_user_config['user_address'];}?>">
                    <br/><?php echo $this->lang->line('address_not_show_for_any_user'); ?>
                  <span class="error"><?php echo form_error('address'); ?></span></dd>
                  <dt><?php echo $this->lang->line('mypage_user_config_address_building'); ?></dt>
                  <dd>
                    <input type="text" name="addressbuilding" value="<?php if(@$load_user_config['user_address_building']) {echo $load_user_config['user_address_building'];}?>">
                  <span class="error"><?php echo form_error('addressbuilding'); ?></span></dd>
                  <dt><?php echo $this->lang->line('mypage_user_desscription'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_creator_form_yeucau'); ?></span></dt>
                  <dd>
                    <textarea name="mota"><?php if(@$load_user_config['user_introduction']) {echo $load_user_config['user_introduction'];}?></textarea>
                  <span class="error"><?php echo form_error('mota'); ?></span></dd>
                </dl>
                <div class="mypage-form__check">
                
                  <input id="checkbox_id" onclick="check_checkbox(this);" name="check_notice" type="checkbox" value="<?php if($load_user_config['notice_luxyart']){echo $load_user_config['notice_luxyart'];}?>" <?php if($load_user_config['notice_luxyart']==1){echo 'checked';} else {echo '';}?>>
                  <p><?php echo $this->lang->line('mypage_user_config_notice_luxyart'); ?></p>
                </div>
                <div class="mypage-form__btn">
                  <input type="submit" name="submit" value="<?php echo $this->lang->line('mypage_user_config_button'); ?>">
                </div>
              </form>
            </div>
          </div>
      
    </div>
  </div>