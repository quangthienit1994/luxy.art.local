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
          <li><span itemprop="title"><?php echo $this->lang->line('title_history_user_use_point'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
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
                  <p class="mypage-list__user"><?php if($show_list_history_use_point->status_change_point==1) echo '<span style="color:#4caf50;font-weight:bold">+ '.$show_list_history_use_point->point.'</span>';?><?php if($show_list_history_use_point->status_change_point==2) echo '<span style="color:red;font-weight:bold">- '.$show_list_history_use_point->point.'</span>';?>  <?php echo $this->lang->line('point'); ?>
				<?php 
                $text_bien  = $show_list_history_use_point->content;
				if($text_bien=="Update level account" || $text_bien=="Buy Package Point")
				{
					$out_text = str_replace(' ','_', $text_bien);
					echo $this->lang->line($out_text);
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
            <div class="mypage-purchase__pagenav"><?php echo $phantrang;?></div>
            <?php } ?>
            <div class="clear clearfix"></div>
            
          </div>
    </div>
</div>