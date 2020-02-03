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
          <li><span itemprop="title"><?php echo $this->lang->line('user_page_order_list'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('user_page_order_list'); ?></h1>
            <div class="mypage-list">
              <div class="mypage-list__num">
                <p><?php echo $this->lang->line('user_page_order_show_select_page'); ?>：<?php if($num_page==10){echo "<span class='curent'>10</span>";}else{ echo '<a onclick="view_page(10);">10</a>';}?>｜<?php if($num_page==20){echo "<span class='curent'>20</span>";}else{ echo '<a onclick="view_page(20);">20</a>';}?>｜<?php if($num_page==50){echo "<span class='curent'>50</span>";}else{ echo '<a onclick="view_page(50);">50</a>';}?></p>
              </div>
              <div class="mypage-list__header">
                <div><?php echo $this->lang->line('user_page_order_table_col_name'); ?></div>
                <div class="w120"><?php echo $this->lang->line('user_page_order_table_col_date'); ?></div>
                <div class="w120"><?php echo $this->lang->line('user_page_order_table_col_text_action_download'); ?></div>
              </div>
              <?php
					if(!empty($p_list_order))
					{?>
              <div class="mypage-list__list">
                <ul>
                <?php foreach($p_list_order as $show_p_list_order){
					if($show_p_list_order->user_firstname!="")
								{
									$name_call_user_img = $show_p_list_order->user_firstname.' '.$show_p_list_order->user_lastname;
								}
								else if($show_p_list_order->user_lastname!="")
								{
									$name_call_user_img = $show_p_list_order->user_firstname.' '.$show_p_list_order->user_lastname;
								}
								else
								{
									$get_name_email = explode('@',$show_p_list_order->user_email);
									$name_call_user_img = $get_name_email[0];
								}
				?>
                  <li>
                    <div class="mypage-list__thumb">
                      <div class="mypage-list__thumb-inner"><img alt="<?php echo $show_p_list_order->img_title;?>" src="<?php echo $show_p_list_order->server_path_upload;?>/<?php echo $show_p_list_order->file_name_original_img;?>"></div>
                    </div>
                    <div class="mypage-list__info">
                      <p class="mypage-list__id"><a href="<?php echo base_url(); ?>detail/<?php echo $show_p_list_order->img_code;?>.html"><?php echo $show_p_list_order->img_title;?></a></p>
                      <p class="mypage-list__user"><?php echo $this->lang->line('user_page_order_table_col_by_user'); ?><a href="<?php echo base_url(); ?>timeline/<?php echo $show_p_list_order->user_id; ?>.html"><?php echo $show_p_list_order->display_name; ?></a></p>
                    </div>
                    <div class="mypage-list__date"><?php echo date('Y-m-d H:i', strtotime($show_p_list_order->date_order_img));?></div>
                    <?php if($show_p_list_order->date_end_download > date("Y-m-d H:i:s")){?>
                    <div class="mypage-list__download"><a onclick="download_img('<?php echo $show_p_list_order->code_download;?>','<?php echo $show_p_list_order->id_img;?>','<?php echo $show_p_list_order->id_order_img;?>');"><?php echo $show_p_list_order->code_download;?></a></div>
                    <?php } else {?>
                    <div class="mypage-list__download"><span><?php echo $this->lang->line('user_page_order_table_text_download_is_expired'); ?></span></div>
                    <?php } ?>
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