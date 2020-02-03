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
              <li><span itemprop="title"><?php echo $this->lang->line('error_404_title'); ?></span></li>
            </ul>
          </div>
          <div class="contents-leave__main">
            <div class="alert--gray">
            
            	<p><?php echo $this->lang->line('error_404_content'); ?></p>
              <div class="leave-popup__btn">
                  <div class="leave-popup__btn--cancel"><span><?php echo $this->lang->line('path_home'); ?></span></div>
        		</div>
            </div>
          </div>
        </div>
      </div>