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
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('user_page_manager_point'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('user_page_manager_point'); ?></h1>
                <div class="mypage-list">
                          <div class="mypage-list__list">
                            <div class="mypage-sub__credit col_2">
                            	  <p>購入したLUX</p>
                                  <p class="credit"><?php echo $this->lang->line('balance'); ?>：<span><?php echo $this->function_model->num_format($user->user_paymoney_getpoint,2); ?></span></p>
                                  <p class="link"><a href="<?php echo base_url()?>credit.html"><?php echo $this->lang->line('purchase_credit'); ?>&gt;&gt;</a></p>
            				</div>
                            <div class="mypage-sub__credit col_2">
                            	  <p>収益として獲得したLUX</p>
                                  <p class="credit"><?php echo $this->lang->line('user_page_manager_point_can_return'); ?>：<span><?php echo $this->function_model->num_format($user->user_point,2); ?></span></p>
                                  <?php
                                  if($user->user_point >= $minium_export_money){?><p class="link"><a href="<?php echo base_url()?>mypage/export-money.html"><?php echo $this->lang->line('ask_return_money_link'); ?></a></p><?php } else {?><p class="link"><?php echo $this->lang->line('not_enought_money_to_return_text'); ?></p><?php }?>
            				</div>
                           </div>
                </div>
            <div class="clear clearfix"></div>
            <?php
					if(!empty($list_ask_return_point))
					{?>
            <h2 class="contents-mypage__find"><?php echo $this->lang->line('user_page_manager_point_text_history_return'); ?></h2>
            <div class="mypage-list">
                      <div class="mypage-list__header">
                        <div  style="width:688px;text-align:left"><span style="margin-left:30px"><?php echo $this->lang->line('user_page_manager_point_text_content_status'); ?></span></div>
                        <div class="w120"><?php echo $this->lang->line('user_page_order_table_col_date'); ?></div>
                      </div>
                      <div class="mypage-list__list">
                        	<ul>
                            <?php foreach($list_ask_return_point as $show_list_ask_return_point){?>
                                <li>
                <div class="mypage-list__info" style="margin-left:0;width:688px">
                  <p class="mypage-list__id"><?php if($show_list_ask_return_point->tranfer_status==1) echo '<span style="color:red;font-weight:bold">'.$this->lang->line('user_page_manager_point_text_admin_checking_money_return').'</span>';?><?php if($show_list_ask_return_point->tranfer_status==2) echo '<span style="color:#4caf50;font-weight:bold">'.$this->lang->line('user_page_manager_point_text_admin_status_money_return_done').'</span>';?></p>
                  <p class="mypage-list__user"><?php echo number_format($show_list_ask_return_point->money_user_get,0,".",",").'</span>';?> JPY</p>
                </div>
                <div class="mypage-list__date"><?php if($show_list_ask_return_point->tranfer_status==1) echo date('Y-m-d H:i', strtotime($show_list_ask_return_point->date_add_require));?><?php if($show_list_ask_return_point->tranfer_status==2) echo date('Y-m-d H:i', strtotime($show_list_ask_return_point->date_tranfer));?></div>
                                 </li>
                                 <?php }?>
							                            </ul>
                          </div>
                          
                        </div>
            <?php if($count_record_return_money>0){?><p class="mypage-top-morelink"><a href="<?php echo base_url();?>mypage/history-ask-return-money.html"><?php echo $this->lang->line('user_page_manager_point_text_history_return_money_link_view_more'); ?></a></p><?php } ?>
            <?php } ?>
            <div class="clear clearfix"></div>
            <?php
					if(!empty($list_history_use_point))
					{?>
            <h2 class="contents-mypage__find"><?php echo $this->lang->line('user_page_manager_point_recently_use_point'); ?></h2>
            <div class="mypage-list">
                      <div class="mypage-list__header">
                        <div  style="width:688px;text-align:left"><span style="margin-left:30px"><?php echo $this->lang->line('user_page_manager_point_recently_use_point_text_name_content'); ?></span></div>
                        <div class="w120"><?php echo $this->lang->line('user_page_order_table_col_date'); ?></div>
                      </div>
                      <div class="mypage-list__list">
                        	<ul>
                            <?php foreach($list_history_use_point as $show_list_history_use_point){?>
                                <li>
                <div class="mypage-list__info" style="margin-left:0;width:688px">
                  <p class="mypage-list__id"><?php if($show_list_history_use_point->status_change_point==1) echo $this->lang->line('user_page_manager_point_recently_use_point_text_plus').':';?><?php if($show_list_history_use_point->status_change_point==2) echo $this->lang->line('user_page_manager_point_recently_use_point_text_minus').':';?></p>
                  <p class="mypage-list__user"><?php if($show_list_history_use_point->status_change_point==1) echo '<span style="color:#4caf50;font-weight:bold">+ '.$show_list_history_use_point->point.'</span>';?><?php if($show_list_history_use_point->status_change_point==2) echo '<span style="color:red;font-weight:bold">- '.$show_list_history_use_point->point.'</span>';?>  <?php //echo $this->lang->line('point'); ?>
				<?php 
                $text_bien  = $show_list_history_use_point->content;
				if($text_bien=="Update level account" || $text_bien=="Buy Package Point")
				{
					$out_text = str_replace(' ','_', $text_bien);
					echo $this->lang->line($out_text.'_fix');
				}
				else
				{
					echo $text_bien;
				}?></p>
                </div>
                <div class="mypage-list__date"><?php echo date('Y-m-d H:i', strtotime($show_list_history_use_point->date_add));?></div>
                                 </li>
                                 <?php }?>
							                            </ul>
                          </div>
                        </div>
            <?php if($count_record_history_use_point>0){?><p class="mypage-top-morelink"><a href="<?php echo base_url();?>mypage/history-user-use-point.html"><?php echo $this->lang->line('user_page_manager_point_recently_use_point_link_view_more'); ?></a></p><?php } ?>
            <?php } ?>
          </div>
    </div>
</div>