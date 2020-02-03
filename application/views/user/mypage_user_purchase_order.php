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
.list-pagenav {background-color:#fff!important;}
@media (max-width: 768px)
{
	.mypage-purchase__list .method .notice_admin {float:right!important;margin-top:-50px!important}
}
</style>
<script>
function view_page(num_page)
	{
		$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url()?>user/do_ajax_get_user_purchase',
					data: "num_page="+num_page,
					async:false,
					success: function(data) {
							document.location.reload();
						}

				   }); 
	}
function notice_admin(id_ordercredit)
	{
		$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url()?>user/do_notice_admin_transfer_money',
					data: "id_ordercredit="+id_ordercredit,
					async:false,
					success: function(data) {
							var msg=data.split("{return_do_notice_admin_transfer_money}");
						if(msg[1] == 1){
							$('.alert--green').css('display','block');
							$('.alert--green p').html("<?php echo $this->lang->line('notice_admin_transfer_money_success'); ?>");
							setTimeout(function() {
							   window.location.reload();
							}, 10000);
						}
					}

				   }); 
	}
</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('user_page_purchase_order_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('user_page_purchase_order_h1'); ?></h1>
            <div class="alert--green" style="display:none">
              <p>メッセージを既読にしました。</p>
              <div class="alert__delete">×</div>
            </div>
            <div class="mypage-purchase">
              <div class="mypage-purchase__num">
                <p><?php echo $this->lang->line('user_page_order_show_select_page'); ?>：<?php if($num_page==10){echo "<span class='curent'>10</span>";}else{ echo '<a onclick="view_page(10);">10</a>';}?>｜<?php if($num_page==20){echo "<span class='curent'>20</span>";}else{ echo '<a onclick="view_page(20);">20</a>';}?>｜<?php if($num_page==50){echo "<span class='curent'>50</span>";}else{ echo '<a onclick="view_page(50);">50</a>';}?></p>
              </div>
              <div class="mypage-purchase__header">
                <div class="date"><?php echo $this->lang->line('user_page_purchase_order_date'); ?></div>
                <div class="order"><?php echo $this->lang->line('user_page_purchase_order_id'); ?></div>
                <div class="detail"><?php echo $this->lang->line('user_page_purchase_order_detail'); ?></div>
                <div class="method"><?php echo $this->lang->line('user_page_purchase_order_method'); ?></div>
                <div class="price"><?php echo $this->lang->line('user_page_purchase_order_price'); ?></div>
              </div>
              <div class="mypage-purchase__list">
                <ul>
                  <?php
					if(!empty($list_purchase_order_point)){
						foreach($list_purchase_order_point as $show_list_purchase_order_point){?>
                	<li>
                    <div class="date"><?php echo date('Y-m-d', strtotime($show_list_purchase_order_point->date_order_credit));?><span>
                    <?php echo date('H:i', strtotime($show_list_purchase_order_point->date_order_credit));?></span></div>
                    <div class="order"><?php echo $show_list_purchase_order_point->id_ordercredit;?></div>
                    <div class="detail"><?php echo $this->function_model->num_format($show_list_purchase_order_point->point_credit,2); ?><?php echo $this->lang->line('user_page_purchase_order_text_point'); ?></div>
                    <div class="method"><?php if($show_list_purchase_order_point->payment_menthod==1){echo $this->lang->line('payment_bank_transfer');
					if($show_list_purchase_order_point->notice_status==0){echo '<span class="notice_admin" onclick="notice_admin('.$show_list_purchase_order_point->id_ordercredit.')">'.$this->lang->line('button_notice_transfer_money').'</span>';}
					}?><?php if($show_list_purchase_order_point->payment_menthod==2){echo $this->lang->line('payment_credit_card');}?><?php if($show_list_purchase_order_point->payment_menthod==3){echo $this->lang->line('payment_bit_coin');}?></div>
                    <div class="price"><?php echo number_format($show_list_purchase_order_point->money_pay_credit);?><?php echo $this->lang->line('user_page_purchase_order_text_currency'); ?></div>
                  </li>
                <?php }}else { echo $this->lang->line('user_page_purchase_order_no_record');}?>
                </ul>
              </div>
               <div class="mypage-purchase__pagenav"><?php echo $phantrang;?></div>
            </div>
          </div>
      
    </div>
  </div>