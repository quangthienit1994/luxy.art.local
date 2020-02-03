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
<style>
.list-pagenav {background-color:#fff!important;}
.mypage-message-list__list li.read {
    background-color:rgba(199, 199, 206, 0.13);
}
.mypage-message-list__list li.read a {
    color:#000;
}
</style>
<script>
$( document ).ready(function() {
    $('.alert--green').css('display','none');
	$("#readall").click(function () {
	var list_items = [];
		$('div.mypage-message-list__check input[type=checkbox]').each(function() {
		   if ($(this).is(":checked")) {
			   list_items.push($(this).attr('value'));
		   }
		});
		if(list_items.length > 0){
				$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/do_read_all_message",
					data: "list_items="+list_items,
					success: function(data) {
						var msg=data.split("{return_do_read_all}");
						if(msg[0] == 1){
							$('.alert--green').css('display','block');
							<?php if($ngonngu=='ja'){?>
							$('.alert--green p').html("あなたはすでに"+msg[1]+"つのメッセージを読んでいます");
							<?php } ?>
							<?php if($ngonngu=='vi'){?>
							$('.alert--green p').html("You're already read "+msg[1]+" message");
							<?php } ?>
							<?php if($ngonngu=='en'){?>
							$('.alert--green p').html("You're already read "+msg[1]+" message");
							<?php } ?>
							setTimeout(function() {
							   window.location.reload();
							}, 5000);
						}
					},
					async: false
				})
		}
	});
	$("#deleteall").click(function () {
	var list_items = [];
		$('div.mypage-message-list__check input[type=checkbox]').each(function() {
		   if ($(this).is(":checked")) {
			   list_items.push($(this).attr('value'));
		   }
		});
		if(list_items.length > 0){
				$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/do_delete_all_message",
					data: "list_items="+list_items,
					success: function(data) {
						var msg=data.split("{return_do_delete_all}");
						if(msg[0] == 1){
							$('.alert--green').css('display','block');
							<?php if($ngonngu=='ja'){?>
							$('.alert--green p').html("あなたはすでに"+msg[1]+"つのメッセージを削除しています");
							<?php } ?>
							<?php if($ngonngu=='vi'){?>
							$('.alert--green p').html("You're already delete "+msg[1]+" message");
							<?php } ?>
							<?php if($ngonngu=='en'){?>
							$('.alert--green p').html("You're already delete "+msg[1]+" message");
							<?php } ?>
							
							setTimeout(function() {
							   window.location.reload();
							}, 5000);
						}
					},
					async: false
				})
		}
	});
});

</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('page_user_message_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('page_user_message_h1'); ?></h1>
            <div class="alert--green" style="display:none">
              <p>メッセージを既読にしました。</p>
              <div class="alert__delete">×</div>
            </div>
            <div class="mypage-message-list">
              <div class="mypage-message-list__action">
                <div>
                  <label class="message-all-select">
                    <input type="checkbox"><?php echo $this->lang->line('select_all'); ?>
                  </label>
                </div>
                <p><a id="readall"><?php echo $this->lang->line('page_user_message_read_all'); ?></a> / <a id="deleteall"><?php echo $this->lang->line('page_user_message_delete_all'); ?></a></p>
              </div>
              <div class="mypage-message-list__header">
                <div class="check message-all-select">
                  <input type="checkbox">
                </div>
                <div><?php echo $this->lang->line('page_user_message_content_message_subject'); ?></div>
                <div class="date"><?php echo $this->lang->line('page_user_message_content_message_date'); ?></div>
              </div>
              <div class="mypage-message-list__list">
                <ul>
                <?php
					if(!empty($list_user_message)){
						foreach($list_user_message as $show_list_user_message){?>
                	<li class="<?php if($show_list_user_message->ms_is_read ==0){echo 'unread';}else {echo 'read';}?>">
                    <div class="mypage-message-list__check">
                      <input type="checkbox" name="checkoption[<?php echo $show_list_user_message->id_message;?>]" value="<?php echo $show_list_user_message->id_message;?>">
                    </div>
                    <div class="mypage-message-list__subject">
                      
                      <?php if($show_list_user_message->user_send_id ==-1)
					  {?>
                      			<div class="mypage-message-list__icon"><img src="<?php echo base_url()?>publics/img/logo-luxyArt.png"></div>
                                <div class="mypage-message-list__text">
                        <p><?php //echo $this->lang->line('page_user_message_user_send_is_admin'); ?><a href="<?php echo base_url()?>mypage/user-message-detail/<?php echo $show_list_user_message->id_message;?>.html"><?php echo $show_list_user_message->ms_subject;?></a></p>
                      </div>
                      <?php } else {
						  $user = $CI->manager_user_model->get_user_id($show_list_user_message->user_send_id);?>
                          <div class="mypage-message-list__icon">
                          	<img src="<?php echo base_url()?>publics/avatar/<?php echo ($user->user_avatar != "") ? $user->user_avatar : "prof-img.png"; ?>">
                          </div>
                          <div class="mypage-message-list__text">
                        <p><?php echo ($user->display_name != '') ? $user->display_name : $user->user_firstname.' '.$user->user_lastname?> <a href="<?php echo base_url()?>mypage/user-message-detail/<?php echo $show_list_user_message->id_message;?>.html"><?php echo $show_list_user_message->ms_subject;?></a></p>
                      </div>
                      <?php }?>
                    </div>
                    <div class="mypage-message-list__date"><?php echo date('Y/m/d', strtotime($show_list_user_message->date_message));?></div>
                  </li>
                <?php }}else {?> <?php echo $this->lang->line('page_user_message_show_no_message'); ?><?php }?>
                  
                </ul>
              </div>
              <div class="mypage-purchase__pagenav"><?php echo $phantrang;?></div>
            </div>
          </div>
      
    </div>
  </div>