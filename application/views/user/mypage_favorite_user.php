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
</style>
<script>
function unfollow_user(id_follow)
	{
		$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/mypage_do_delete_follow_user",
					data: "id_follow="+id_follow,
					success: function(data) {
						var msg=data.split("{return_do_delete_follow_user}");
						if(msg[0] == 1){
							window.location.reload();
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
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title">><?php echo $this->lang->line('mypage'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('mypage_bookmark_user_title'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      
      <div class="mypage-main">
      <h1 class="contents-mypage__find"><?php echo $this->lang->line('mypage_bookmark_user_title'); ?></h1>
      <div class="mypage-favorite-user">
      <?php if(!empty($p_favorite_user)){?>
      <?php foreach($p_favorite_user as $show_p_favorite_user){
		  if($show_p_favorite_user->user_avatar!=""){$img_user = $show_p_favorite_user->user_avatar;}else{$img_user ='prof-img.png';}
		  if($show_p_favorite_user->user_firstname=="" && $show_p_favorite_user->user_lastname==""){$get_name = explode('@',$show_p_favorite_user->user_email); $user_name = $get_name['0'];}else{$user_name = $show_p_favorite_user->user_firstname.' '.$show_p_favorite_user->user_lastname;}?>
              <div class="mypage-favorite-user__list">
                <div class="mypage-favorite-user__header">
                  <div class="mypage-favorite-user__icon"><img alt="<?php echo $user_name;?>" src="<?php echo base_url(); ?>publics/avatar/<?php echo $img_user; ?>"></div>
                  <p><a href="<?php echo base_url(); ?>timeline/<?php echo $show_p_favorite_user->user_id?>.html"><?php echo $show_p_favorite_user->display_name; ?></a></p>
                  <div class="mypage-favorite-user__follow" onclick="unfollow_user('<?php echo $show_p_favorite_user->id_follow;?>');"><span><?php echo $this->lang->line('mypage_text_unbookmark_user'); ?></span></div>
                </div>
                <?php $productImg = $CI->manager_user_model->get_img_user_follow($show_p_favorite_user->user_id)->result();?>
                
                <div class="mypage-img-list">
                <?php if(!empty($productImg)){?>
                  <ul>
                  	<?php foreach($productImg as $detailProductImg){?>
                    <li class="mypage-img-list__list">
                      <div class="mypage-img-list__wrapper">
                        <div class="mypage-img-list__inner"><a href="<?php echo base_url(); ?>detail/<?php echo $detailProductImg->img_code?>.html">
                        <?php if($detailProductImg->file_name_watermark_img_1 != ""){?>
                        <img alt="<?php echo $detailProductImg->img_title;?>" src="<?php echo $detailProductImg->server_path_upload."/".$detailProductImg->file_name_watermark_img_1; ?>">
						<?php }else{?><img alt="<?php echo $detailProductImg->img_title;?>" src="<?php echo base_url(); ?>publics/img/no_image.jpg"><?php } ?></a></div>
                      </div>
                    </li>
					<?php } ?>
                  </ul>
                <?php }else {?>
                <?php echo $this->lang->line('page_bookmark_user_notice_this_user_no_image'); ?>
                <?php } ?>
                </div>
                <div class="mypage-favorite-user__more"><a href="<?php echo base_url(); ?>timeline/<?php echo $show_p_favorite_user->user_id?>.html"> <?php echo $this->lang->line('view_time_line_user'); ?></a></div>
              </div>
      <?php } ?>
      <div class="mypage-favorite-user__pagenav"><?php echo $phantrang;?></div>
      <?php } else {?>
      <?php echo $this->lang->line('mypage_show_no_bookmark_user'); ?>
      <?php }?>
            </div>
            
          </div>
      
    </div>
  </div>