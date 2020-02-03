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
		location.replace("<?php echo base_url()?>");
	});
});
</script>
<div class="contents-leave" style="min-height:275px">
        <div class="contents-leave__wrapper clearfix">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('title_notice_transfer_money'); ?></span></li>
            </ul>
          </div>
          <div class="contents-leave__main">
            <div class="alert--gray">
            
            	<p><?php 
				$text_bien  = $this->lang->line('notice_transfer_money_content');
			  	$chuoilay = array("{name_credit}", "{point_credit}");
			  	$choidoi   = array($name_credit, $point_credit);
				$out_text = str_replace($chuoilay, $choidoi, $text_bien);
				echo $out_text; ?></p>
              <div class="leave-popup__btn">
                  <div class="leave-popup__btn--dosome"><span><a href="<?php echo base_url()?>mypage/user-purchase-order.html"><?php echo $this->lang->line('link_go_to_notice_admin_transfer_money'); ?></span></div>
        		</div>
            </div>
          </div>
        </div>
      </div>