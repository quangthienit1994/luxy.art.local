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
<style>
.list-pagenav {background-color:#fff!important;}
</style>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('title_history_user_ask_export_money'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
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
                <div class="mypage-list__date"><?php if($show_list_ask_return_point->tranfer_status==1) echo $show_list_ask_return_point->date_add_require;?><?php if($show_list_ask_return_point->tranfer_status==2) echo $show_list_ask_return_point->date_tranfer;?></div>
                                 </li>
                                 <?php }?>
							                            </ul>
                          </div>
                          
                        </div>
            <div class="mypage-purchase__pagenav"><?php echo $phantrang;?></div>
            <?php } ?>
            <div class="clear clearfix"></div>
            
          </div>
    </div>
</div>