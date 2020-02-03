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
					data: "type_account="+5,
					success: function(data) {
						var msg=data.split("{return_do_user_update_account}");
						if(msg[0] == 1){
							location.replace("<?php echo base_url()?>mypage/notice-user-update-level/"+ msg[1] +".html");
						}
						if(msg[0] == 0){
							$('.alert--gray p').html('Loi trong qua trinh dang ky acount. Vui long thu lai');
						}
					},
					async: false
				});
	});
	$('.leave-popup__btn--cancel').on('click','span',function(){
		location.replace("<?php echo base_url()?>mypage/user-update-account.html");
	});
	$('.leave-popup__btn--buypoint').on('click','input',function(){
		$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/add_session_return_update_level",
					data: "level_update="+5,
					success: function(data) {
						var msg=data.split("{tieptuc_do_update}");
						if(msg[0] == 1){
							location.replace("<?php echo base_url()?>credit.html");
						}
					},
					async: false
				});
		
	});
});
</script>
<style>
.leave-popup__btn--buypoint input {
    width: inherit;
    height: inherit;
    line-height: 40px;
    color: #fff;
    font-size: 15px;
    font-weight: bold;
    background: gradient(linear, left top, left bottom, from(#00d2b9), to(#00c6ad));
    background: -webkit-gradient(linear, left top, left bottom, from(#00d2b9), to(#00c6ad));
    background: linear-gradient(to bottom, #00d2b9, #00c6ad);
    border-radius: 3px;
    display: block;
    text-align: center;
    cursor: pointer;
}
.leave-popup__btn--buypoint {
    width: 160px;
    height: 42px;
    position: relative;
    display: inline-block;
    vertical-align: top;
}
.leave-popup__btn--buypoint:before {
    content: "";
    display: block;
    position: absolute;
    margin: -4px 0 0;
    top: 50%;
    right: 12px;
    background-image: url(<?php echo base_url()?>publics/img/sprite.png);
    background-position: -294px -172px;
    width: 6px;
    height: 9px;
}
</style>
<div class="contents-leave" style="min-height:275px">
        <div class="contents-leave__wrapper clearfix">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
              
              <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('update_account_user_enterprise'); ?></span></li>
            </ul>
          </div>
          <div class="contents-leave__main">
          <div class="alert--gray">
          <?php if($user_level<=4){?>
          
          <?php if($show_buy_point==1){
			  	$text_bien_notice_buy_more_point  = $this->lang->line('p_text_notice_user_buy_more_point_when_update_level_account');
			  	$chuoilay = array("{level_current}", "{level_update}", "{point_user}", "{point_need}");
			  	$choidoi   = array($CI->check_level_user($user_level), $this->lang->line('user_level_5'),$point_user,$point_enterprise - $point_user);
				$out_text_notice_buy_more_point = str_replace($chuoilay, $choidoi, $text_bien_notice_buy_more_point);
			?>
          
              <p><?php echo $out_text_notice_buy_more_point;?></p>
              <div class="leave-popup__btn">
                  <div class="leave-popup__btn--buypoint">
                    <input type="submit" value="<?php echo $this->lang->line('button_buy_more_point'); ?>">
                  </div>
                  <div class="leave-popup__btn--cancel"><span><?php echo $this->lang->line('button_cancel_update'); ?></span></div>
        		</div>
            
          <?php } ?>
          <?php if($show_buy_point==0){
			  	$text_bien  = $this->lang->line('are_you_want_to_update_to_become_this_level');
			  	$chuoilay = array("{point}", "{point_have}");
			  	$choidoi   = array($point_enterprise, $point_user);
				$out_text = str_replace($chuoilay, $choidoi, $text_bien);
			  ?>
              <p><?php echo $out_text;?></p>
              <div class="leave-popup__btn">
                  <div class="leave-popup__btn--leave">
                    <input type="submit" value="<?php echo $this->lang->line('button_agree_update'); ?>">
                  </div>
                  <div class="leave-popup__btn--cancel"><span><?php echo $this->lang->line('button_cancel_update'); ?></span></div>
        		</div>
            
          <?php } ?>
          <?php } else {?>
            <p><?php echo $this->lang->line('p_text_error_current_level_max'); ?></p>
            <div class="leave-popup__btn">
                  <div class="leave-popup__btn--cancel"><span><?php echo $this->lang->line('button_return_update_package_list'); ?></span></div>
        		</div>
            <?php }?>
            </div>
          </div>
        </div>
      </div>