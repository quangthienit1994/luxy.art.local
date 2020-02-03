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
</style>
<script>
function view_page(num_page)
	{
		var host = location.protocol + "//" + location.host
		var pathName = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/mypage/list-order') + 1);
		var url = host + pathName;
		$.ajax({
					type: "POST",cache: false,
					url: url+'user/do_ajax_get_list_order',
					data: "num_page="+num_page,
					async:false,
					success: function(data) {
							document.location.reload();
						}

				   }); 
	}
function download_img(macode,id_img,id_order)
{
	var host = location.protocol + "//" + location.host
	var pathName = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/mypage/list-order') + 1);
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
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('list_image_comment'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('list_image_comment'); ?></h1>
            <div class="mypage-list">
              <div class="mypage-list__header">
                <div><?php echo $this->lang->line('image'); ?></div>
                <div class="w120"><?php echo $this->lang->line('count_comment'); ?></div>
                <div class="w120"><?php echo $this->lang->line('view_comment'); ?></div>
              </div>
              <?php
					if(!empty($list_comment))
					{?>
              <div class="mypage-list__list">
                <ul>
                <?php foreach($list_comment as $detail_comment){
					$count_all_comment = $CI->manager_comment_model->get_list_comment_by_level($detail_comment->id_img,1,$user_id)->num_rows();
				?>
                  <li>
                    <div class="mypage-list__thumb">
                      <div class="mypage-list__thumb-inner"><img src="<?php echo $detail_comment->server_path_upload ?>/<?php echo $detail_comment->file_name_watermark_img ?>"></div>
                    </div>
                    <div class="mypage-list__info">
                      <p class="mypage-list__id"><a href="<?php echo base_url(); ?>detail/<?php echo $detail_comment->img_code;?>.html"><?php echo $detail_comment->img_title;?></a></p>
                      <p class="mypage-list__user"><?php echo $this->lang->line('user_page_order_table_col_by_user'); ?><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment->user_id; ?>.html"><?php echo $name_call_user_img;?></a></p>
                    </div>
                    <div class="mypage-list__date"><?php echo $count_all_comment; ?></div>
                   	<div class="mypage-list__date"><a target="_blank" href="<?php echo base_url(); ?>mypage/detail-comment/<?php echo $detail_comment->id_img;?>.html"><?php echo $this->lang->line('detail_comment'); ?></a></div>
                  </li>
                  <?php }?>
                </ul>
              </div>
              <div class="mypage-list__pagenav"><?php echo $phantrang;?></div>
              <?php }else {?>
			  <?php echo $this->lang->line('mypage_show_text_no_order'); ?><?php }?>
              
            </div>
          </div>
      
    </div>
  </div>