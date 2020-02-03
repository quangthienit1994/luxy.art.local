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
$logged_in = $CI->function_model->get_logged_in();
$user_id = $logged_in['user_id'];
$CI->load->model('manager_user_model');
$user = $CI->manager_user_model->get_user_id($user_id);
?>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('title_user_manager_card'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h2 class="contents-mypage__find"><?php echo $this->lang->line('title_user_manager_card'); ?></h2>
            <div class="alert--green" style="display:none">
              <p>メッセージを既読にしました。</p>
              <div class="alert__delete">×</div>
            </div>
            <div class="mypage-list">
                      <div class="mypage-list__header">
                        <div  style="width:300px;text-align:left"><span style="margin-left:30px"><?php echo $this->lang->line('user_credit_order_label_account_number_credit'); ?></span></div>
                        <div class="w120"><?php echo $this->lang->line('card_exp_mm_yyyy'); ?></div>
                        <div class="w120">CVC</div>
                        <div class="w120">&nbsp;</div>
                      </div>
                      <div class="mypage-list__list">
                        	<ul>
                            <?php foreach($list_user_card as $show_list_user_card){?>
                                <li>
                <div class="mypage-list__info" style="margin-left:0;width:300px">
                  <p class="mypage-list__id"><?php echo ucfirst($show_list_user_card->account_type_card)?>&nbsp;:&nbsp;<?php echo $CI->String2Stars($show_list_user_card->account_number,5,-5);?></p>
                </div>
                <div class="mypage-list__date"><?php echo substr($show_list_user_card->account_exp,0,2).'/'.substr($show_list_user_card->account_exp,2);?></div>
                <div class="mypage-list__date"><?php echo $show_list_user_card->account_ccv_value?></div>
                <div class="mypage-list__date"><a onclick="do_delete_card(<?php echo $show_list_user_card->card_id;?>)"><?php echo $this->lang->line('list_card_button_delete'); ?></a> || <a href="<?php echo base_url()?>mypage/user-edit-card/<?php echo $show_list_user_card->card_id;?>.html"><?php echo $this->lang->line('list_card_button_edit'); ?></a></div>
                                 </li>
                                 <?php }?>
							                            </ul>
                          </div>
                        </div>
          </div>
    </div>
</div>
<script type="text/javascript">
function do_delete_card(id)
{
	$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/do_user_delete_card_bank",
					data: "cardid="+id,
					success: function(data) {
						var msg=data.split("{return_do_user_delete_card_bank}");
						if(msg[0] == 1){
							$('.alert--green').css('display','block');
							$('.alert--green p').html("<?php echo $this->lang->line('notice_user_delete_card_ok'); ?>");
							setTimeout(function() {
							   window.location.reload();
							}, 5000);
						}
						if(msg[0] == 0){
							$('.alert--green').css('display','block');
							$('.alert--green p').html("<?php echo $this->lang->line('notice_user_delete_card_not_ok'); ?>");
							setTimeout(function() {
							   $('.alert--green').fadeIn('slow');
							}, 5000);
						}
					},
					async: false
				})
}
</script>