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
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('user_page_history_view_image_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      
      <div class="mypage-main">
      <h1 class="contents-mypage__find"><?php echo $this->lang->line('user_page_history_view_image_h1'); ?></h1>
      		<?php
					if(!empty($p_history_view_image))
					{?>
                    
                        <div class="mypage-img-list">
                            <ul>
                    <?php foreach($p_history_view_image as $show_p_history_view_image){?>
                    	<li class="mypage-img-list__list">
              <div class="mypage-img-list__wrapper">
                <div class="mypage-img-list__inner"><a href="<?php echo base_url()?>detail/<?php echo $show_p_history_view_image->img_code;?>.html"><img src="<?php echo $show_p_history_view_image->server_path_upload;?>/<?php echo $show_p_history_view_image->file_name_watermark_img_1;?>" alt="<?php echo $show_p_history_view_image->img_title;?>"></a></div>
              </div>
            </li>
                    	
                    <?php }?>
					</ul></div><?php echo $phantrang;?><?php } else{?>
                    <?php echo $this->lang->line('mypage_history_view_show_no_item'); ?>
                    <?php }?>
          </div>
      
    </div>
  </div>