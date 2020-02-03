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
	$('.leave-popup__btn--cancel').on('click','span',function(){
		location.replace("<?php echo base_url()?>mypage.html");
	});
});
</script>
<div class="contents-leave" style="min-height:275px">
        <div class="contents-leave__wrapper clearfix">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
              
              <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('creator_registration'); ?></span></li>
            </ul>
          </div>
          <div class="contents-leave__main">
            <div class="alert--gray">
            <?php if($error==1){?>
              <p><?php echo $this->lang->line('cannot_find_notice_update');?></p>
            <?php }else{
				$text_bien_notice_buy_more_point  = $this->lang->line('p_text_after_update_level_notice_page_update_completed');
			  	$chuoilay = array("{level_name}");
			  	$choidoi   = array($CI->check_level_user($level));
				$out_text_notice_buy_more_point = str_replace($chuoilay, $choidoi, $text_bien_notice_buy_more_point);
				?>
            	<p><?php echo $out_text_notice_buy_more_point;?></p>
            <?php }?>
              <div class="leave-popup__btn">
                  <div class="leave-popup__btn--cancel"><span><?php echo $this->lang->line('return_mypage');?></span></div>
        		</div>
            </div>
          </div>
        </div>
      </div>