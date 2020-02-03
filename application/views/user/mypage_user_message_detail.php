<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
$CI->load->model('manager_user_model');
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
$config['language'] = $_SESSION['lang'];
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);
?>
<script>
function delete_message(id_msg)
	{
		$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/mypage_do_delete_msg",
					data: "id_msg="+id_msg,
					success: function(data) {
						var msg=data.split("{return_do_delete_msg}");
						if(msg[0] == 1){
							location.replace("<?php echo base_url()?>mypage/user-message.html");
						}
					},
					async: false
				})
	}
</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage/user-message.html"><span itemprop="title"><?php echo $this->lang->line('user_page_change_email_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('mypage_message_detail_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('mypage_message_detail_h1'); ?></h1>
            <div class="mypage-message-detail">
            <?php if($error_check=='0') {?>
              <div class="mypage-message-detail__sp-menu">
                <div class="return"><a href="<?php echo base_url()?>mypage/user-message.html"><span>一<?php echo $this->lang->line('page_user_message_h1')?></span></a></div>
                <div class="delete" onclick="delete_message('<?php echo $message_id_msg;?>');"><span><?php echo $this->lang->line('del'); ?></span></div>
              </div>
              <div class="mypage-message-detail__header">
              	<?php if($message_user_send_id =='-1'){?>
                <div class="mypage-message-detail__icon"><img src="<?php echo base_url()?>publics/img/logo-luxyArt.png"></div>
                <div class="mypage-message-detail__subject">
                  <p class="mypage-message-detail__date"><?php echo $message_date_message; ?></p>
                  <p><?php //echo $this->lang->line('message_send_by_admin'); ?><?php echo $message_ms_subject?></p>
                </div>
                <?php } else { $user = $CI->manager_user_model->get_user_id($message_user_send_id);?>
                <div class="mypage-message-detail__icon">
                	<img src="<?php echo base_url()?>publics/avatar/<?php echo ($user->user_avatar != "") ? $user->user_avatar : "prof-img.png"; ?>">
              	</div>
                <div class="mypage-message-detail__subject">
                  <p class="mypage-message-detail__date"><?php echo $message_date_message?></p>
                  <p><?php echo $user->user_firstname.' '.$user->user_lastname?> <?php echo $message_ms_subject?></p>
                </div>
                <?php } ?>
                
              </div>
              <div class="mypage-message-detail__body">
                <div class="delete" onclick="delete_message('<?php echo $message_id_msg;?>');"><?php echo $this->lang->line('button_del_this_message'); ?></div>
                <p><?php echo $message_ms_content?></p>
                </div>
           <?php } else {?>
           	<?php echo $this->lang->line('error_read_message'); ?>
           <?php } ?>
              </div>
            </div>
          </div>
      
    </div>
  </div>