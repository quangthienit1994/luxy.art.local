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
<script>
$(document).ready(function() {
	$('.leave-popup__btn--leave').on('click','input',function(){
		$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/do_user_update_account",
					data: "type_account="+2,
					success: function(data) {
						var msg=data.split("{return_do_user_update_account}");
						if(msg[0] == 1){
							location.replace("<?php echo base_url()?>mypage/notice-user-update-level/"+ msg[1] +".html");
						}
						if(msg[0] == 0){
							$('.alert--gray p').html('<?php echo $this->lang->line('p_text_error_when_update_account_try_again_later'); ?>');
						}
					},
					async: false
				});
	});
	$('.leave-popup__btn--cancel').on('click','span',function(){
		location.replace("<?php echo base_url()?>mypage/user-update-account.html");
	});
});
</script>
<div class="contents-leave" style="min-height:275px">
        <div class="contents-leave__wrapper clearfix">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
              <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('update_account_user_creator'); ?></span></li>
            </ul>
          </div>
          <div class="contents-leave__main">
            <div class="alert--gray">
            <?php if($user_level==1){?>
              <p><?php echo $this->lang->line('p_text_update_user_normal_to_creator'); ?></p>
              <div class="leave-popup__btn">
                  <div class="leave-popup__btn--leave">
                    <input type="submit" value="<?php echo $this->lang->line('button_agree_update'); ?>">
                  </div>
                  <div class="leave-popup__btn--cancel"><span><?php echo $this->lang->line('button_cancel_update'); ?></span></div>
        		</div>
            <?php } else {?>
            <p><?php echo $this->lang->line('p_text_error_current_level_is_higher'); ?></p>
            <div class="leave-popup__btn">
                  <div class="leave-popup__btn--cancel"><span><?php echo $this->lang->line('button_return_update_package_list'); ?></span></div>
        		</div>
            <?php }?>
              
            </div>
          </div>
        </div>
      </div>