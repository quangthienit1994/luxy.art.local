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
function download_img(macode,id_img,id_order)
{
	var host = location.protocol + "//" + location.host
	var pathName = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/mypage') + 1);
	var url = host + pathName;
	if(macode!="" && id_img!="" && id_order!="")
	{
		//link download img 
		window.location=url+"download-img-order/"+macode;
	}
}
  	</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php'); ?>
      <div class="mypage-main">
      <?php if($show_update_account==1){?>
            <div class="alert--green">
              <p><?php echo $this->lang->line('error_input_info_account'); ?><a href="<?php echo base_url()?>mypage/user-config.html"><?php echo $this->lang->line('error_input_info_account_button'); ?></a></p>
            </div>
              <?php } ?>
      <?php
					if(!empty($list_order_mypage))
					{?>
                    <h2 class="contents-mypage__find"><?php echo $this->lang->line('mypage_order_title'); ?></h2>
                    <div class="mypage-list">
                      <div class="mypage-list__header">
                        <div><?php echo $this->lang->line('user_page_order_table_col_name')?></div>
                        <div class="w120"><?php echo $this->lang->line('user_page_order_table_col_date')?></div>
                        <div class="w120"><?php echo $this->lang->line('user_page_order_table_col_text_action_download')?></div>
                      </div>
                      <div class="mypage-list__list">
                        	<ul>
                    <?php 
							foreach($list_order_mypage as $show_list_order)
							{
								
								if($show_list_order->user_firstname!="")
								{
									$name_call_user_img = $show_list_order->user_firstname.' '.$show_list_order->user_lastname;
								}
								else if($show_list_order->user_lastname!="")
								{
									$name_call_user_img = $show_list_order->user_firstname.' '.$show_list_order->user_lastname;
								}
								else
								{
									$get_name_email = explode('@',$show_list_order->user_email);
									$name_call_user_img = $get_name_email[0];
								}
								?>
                            <li>
                <div class="mypage-list__thumb">
                  <div class="mypage-list__thumb-inner"><img src="<?php echo $show_list_order->server_path_upload;?>/<?php echo $show_list_order->file_name_original_img;?>"></div>
                </div>
                <div class="mypage-list__info">
                  <p class="mypage-list__id"><a href="<?php echo base_url()?>detail/<?php echo $show_list_order->img_code;?>.html"><?php echo $show_list_order->img_title;?></a></p>
                  <p class="mypage-list__user"> <?php echo $this->lang->line('user_page_order_table_col_by_user'); ?><a href="<?php echo base_url()?>timeline/<?php echo $show_list_order->user_id; ?>.html"><?php echo $show_list_order->display_name; ;?></a></p>
                </div>
                <div class="mypage-list__date"><?php echo $show_list_order->date_order_img;?></div>
                <?php if($show_list_order->date_end_download > date("Y-m-d H:i:s")){?>
                    <div class="mypage-list__download"><a onclick="download_img('<?php echo $show_list_order->code_download;?>','<?php echo $show_list_order->id_img;?>','<?php echo $show_list_order->id_order_img;?>');"></a></div>
                    <?php } else {?>
                    <div class="mypage-list__download"><span><?php echo $this->lang->line('user_page_order_table_text_download_is_expired');?></span></div>
                    <?php } ?>
              </li>
							<?php }
							?>
                            </ul>
                          </div>
                        </div>
                  <p class="mypage-top-morelink"><a href="<?php echo base_url()?>mypage/list-order.html"><?php echo $this->lang->line('mypage_load_more_order'); ?></a></p>
					<?php }
					else
					{
					?><h2 class="contents-mypage__find"><?php echo $this->lang->line('mypage_order_title'); ?></h2>
                    <div class="mypage-list">
                    <?php echo $this->lang->line('mypage_show_text_no_order'); ?>
                    </div>
					<?php }
				?>
        <?php
					if(!empty($list_history_view_mypage))
					{?>
                    <h2 class="contents-mypage__find"><?php echo $this->lang->line('mypage_history_view_title'); ?></h2>
                        <div class="mypage-img-list">
                            <ul>
                    <?php foreach($list_history_view_mypage as $show_list_view_history){?>
                    	<li class="mypage-img-list__list">
              <div class="mypage-img-list__wrapper">
                <div class="mypage-img-list__inner"><a href="<?php echo base_url()?>detail/<?php echo $show_list_view_history->img_code;?>.html"><img src="<?php echo $show_list_view_history->server_path_upload;?>/<?php echo $show_list_view_history->file_name_watermark_img_1;?>" alt="<?php echo $show_list_view_history->img_title;?>"></a></div>
              </div>
            </li>
                    	
                    <?php }?>
					</ul></div>
					<p class="mypage-top-morelink"><a href="<?php echo base_url()?>mypage/history-view-image.html"><?php echo $this->lang->line('mypage_load_more_history_view'); ?></a></p>
                    <?php }
					else
					{
					?><h2 class="contents-mypage__find"><?php echo $this->lang->line('mypage_history_view_title'); ?></h2>
                    <div class="mypage-list">
                    <?php echo $this->lang->line('mypage_history_view_show_no_item'); ?>
                    </div>
					<?php }
				?>
        <?php
					if(!empty($list_new_image_bookmark_user))
					{?>
                    <h2 class="contents-mypage__find"><?php echo $this->lang->line('mypage_bookmark_user_title'); ?></h2>
                        <div class="mypage-img-list">
                            <ul>
                    <?php foreach($list_new_image_bookmark_user as $show_list_new_image_bookmark_user){?>
                    	<li class="mypage-img-list__list">
              <div class="mypage-img-list__wrapper">
                <div class="mypage-img-list__inner"><a href="<?php echo base_url()?>detail/<?php echo $show_list_new_image_bookmark_user->img_code;?>.html"><img src="<?php echo $show_list_new_image_bookmark_user->server_path_upload;?>/<?php echo $show_list_new_image_bookmark_user->file_name_watermark_img_1;?>" alt="<?php echo $show_list_new_image_bookmark_user->img_title;?>"></a></div>
              </div>
            </li>
                    	
                    <?php }?>
					</ul></div>
					<p class="mypage-top-morelink"><a href="<?php echo base_url()?>mypage/favorite-user.html"><?php echo $this->lang->line('mypage_load_more_bookmark_user'); ?></a></p>
					<?php }
					else
					{
					?><h2 class="contents-mypage__find"><?php echo $this->lang->line('mypage_bookmark_user_title'); ?></h2>
                    <div class="mypage-list">
                    <?php echo $this->lang->line('mypage_show_no_bookmark_user'); ?>
                    </div>
					<?php }
				?>        
      </div>
    </div>
  </div>