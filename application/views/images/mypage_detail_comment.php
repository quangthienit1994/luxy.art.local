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

<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/comment.css">

<style>
.list-pagenav {background-color:#fff!important;}
.txt_666 a{
	color: #004f8b !important;
}
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
          <li><span itemprop="title"><?php echo $this->lang->line('list_detail_image_comment'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find"><?php echo $this->lang->line('list_detail_image_comment'); ?></h1>
            <div class="mypage-list">
              <?php
					if(!empty($list_comment)){
						foreach($list_comment as $detail_comment){
			  ?>
              <div class="mypage-list__list">
                <ul>
                
                
                
                <div class="comment_item width_common">
                                <p class="full_content"><?php echo $detail_comment->comment; ?></p>
                                <div class="user_status width_common" data-user-type="8">
                                    <a class="avata_coment" href="#">
                                        <img class="img_avatar" src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_comment->user_avatar!="") ? $detail_comment->user_avatar : "prof-img.png" ?>">
                                    </a>
                                    <span class="left txt_666 txt_14">
                                        <a class="nickname txt_666" href="#" title="Xem trang ý kiến của <?php echo $detail_comment->display_name; ?>" target="_blank"><b><?php echo $detail_comment->display_name; ?></b></a> - <?php echo date('Y-m-d H:i', strtotime($detail_comment->date_create)); ?>
                                    </span>
                                    <p class="txt_666 right block_like_web">
                                        <?php
											if($detail_comment->status == 1){
												echo '<span id="status_'.$detail_comment->comment_id.'"><a onclick="set_status('.$detail_comment->comment_id.',1,1)">'.$this->lang->line('active').'</a></span>';
											}
											else{
												echo '<span id="status_'.$detail_comment->comment_id.'"><a onclick="set_status('.$detail_comment->comment_id.',1,0)">'.$this->lang->line('inactive').'</a></span>';
											}
										?>&nbsp;
                                        <i class="ic ic-caret-down"></i>
                                   </p>
                                </div>
                                
                                <?php
									//$list_comment_sub = $CI->manager_comment_model->get_list_comment_by_level_sub($detail_comment->comment_id)->result();
									$count_comment_sub = $CI->manager_comment_model->get_list_comment_by_level_sub_paging($detail_comment->comment_id,$user_id)->num_rows();
									$list_comment_sub = $CI->manager_comment_model->get_list_comment_by_level_sub_paging($detail_comment->comment_id,$user_id,3,0)->result();
									if(count($count_comment_sub) > 0){
										foreach($list_comment_sub as $detail_comment_sub){						
								?>
                                
                                <div class="div-sub-comment" id="div-sub-comment-<?php echo $detail_comment_sub->comment_id; ?>">
                                    <div class="div-sub-comment-left">&nbsp;</div>
                                    <div class="div-sub-comment-right">
                                        <div class="sub_comment width_common clearfix">
                                            <div class="sub_comment_item width_common">
                                                <p class="full_content"><?php echo $detail_comment_sub->comment; ?></p>
                                                <div class="user_status width_common" data-user-type="0">
                                                    <a class="avata_coment" href="#"></a>
                                                    <span class="left txt_666 txt_14"><img src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_comment_sub->user_avatar!="") ? $detail_comment_sub->user_avatar : "prof-img.png" ?>" alt="Đang tải ảnh đại diện"><a class="nickname txt_666" href="#" title="Xem trang ý kiến của <?php echo $detail_comment_sub->display_name; ?>" target="_blank">&nbsp;&nbsp;&nbsp;<b><?php echo $detail_comment_sub->display_name; ?></b></a> - <?php echo date('Y-m-d H:i', strtotime($detail_comment_sub->date_create)); ?></span>
                                                    <p class="txt_666 right block_like_web">
                                                        <?php
															if($detail_comment_sub->status == 1){
																echo '<span id="status_'.$detail_comment_sub->comment_id.'"><a onclick="set_status('.$detail_comment_sub->comment_id.',2,1)">'.$this->lang->line('active').'</a></span>';
															}
															else{
																 echo '<span id="status_'.$detail_comment_sub->comment_id.'"><a onclick="set_status('.$detail_comment_sub->comment_id.',2,0)">'.$this->lang->line('inactive').'</a></span>';
															}
														?>&nbsp;
                                                        <i class="ic ic-caret-down"></i>
                                                   </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php
										}
								?>
                                
                                <?php
									 if($count_comment_sub > 3){
								?>	
                                <div class="txt_view_more width_common">
                                    <a id="load_page_sub" class="txt_blue view_all_reply detail-image__more_sub-btn" onclick="loadMoreDataSub(<?php echo $detail_comment->comment_id; ?>,<?php echo $detail_comment_sub->comment_id; ?>);"><?php echo $this->lang->line('view_all_comment'); ?> <?php echo $count_comment_sub; ?> <?php echo $this->lang->line('reply'); ?></a>
                                </div>
                                <?php
									 }
								?>    
                                    
                                <?php
									}
								?>
                                
                            </div>
                  
                  
                  
                </ul>
              </div>
              <?php
					}
			  ?>
              	<div class="clearfix"></div>
              	<div class="mypage-list__pagenav"><?php echo $phantrang;?></div>
              <?php
              	}
			  	else {
			  ?>
			  	<?php echo $this->lang->line('mypage_show_text_no_order'); ?>
			  <?php
              	}
			  ?>
              
            </div>
          </div>
      
    </div>
  </div>
  
<script>

function set_status(comment_id,level,status){
	
	id_img = "<?php echo $id_img ?>";
	
	if(status == 0){
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/set_status_comment",
			data: "comment_id="+comment_id+"&level="+level+"&status="+status+"&id_img="+id_img,
			success: function(data) {
				if(data == 1){
					$('#status_'+comment_id).html('<a onclick="set_status('+comment_id+','+level+',1)"><?php echo $this->lang->line('active'); ?></span>');
				}
			},
			async: false
		})
		
	}
	else if(status == 1){
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/set_status_comment",
			data: "comment_id="+comment_id+"&level="+level+"&status="+status+"&id_img="+id_img,
			success: function(data) {
				if(data == 1){
					$('#status_'+comment_id).html('<a onclick="set_status('+comment_id+','+level+',0)"><?php echo $this->lang->line('inactive'); ?></span>');
				}
			},
			async: false
		})
		
	}
	
}

function loadMoreDataSub(comment_id, comment_sub_id){
		
	$.ajax(
		{
			url: '<?php echo base_url(); ?>images/manager_comment_sub_load?comment_id='+comment_id,
			type: "get",
			beforeSend: function()
			{
				$('.detail-image__more_sub-btn').show();
			}
		})
		.done(function(data)
		{
			if(data == " "){
				$('.detail-image__more_sub-btn').html("No more records found");
				return;
			}
			/*if((page*3)<(count_comment-3))
			{
				$('.detail-image__more_sub-btn').show();
				var page_onlick = page + 1;
				document.getElementById('load_page').setAttribute("onClick","loadMoreData("+page_onlick+");");
			}
			else
			{
				$('.detail-image__more_sub-btn').hide();
			}
			$("#post-data").append(data);*/
			$('.detail-image__more_sub-btn').hide();
			$("#div-sub-comment-"+comment_sub_id).html(data);
		})
		.fail(function(jqXHR, ajaxOptions, thrownError)
		{
			  alert('server not responding...');
	});
	
}

</script>